<?php
	class Db {
		function __construct() {
            switch(DbConfig::TYPE) {
                case 'mysql':
                    break;
                case 'pgsql':
                    break;
                default: throw new Exception(master_lang::unsupported_database_type);
            }

            $this->connect();
		}

        // Establishes $this->link with db connection using the credentials set up in class constants.
        function connect() {
            switch(DbConfig::TYPE) {
                case 'mysql':
                    $this->link = @mysql_connect(DbConfig::SERVER, DbConfig::USERNAME, DbConfig::PASSWORD);
                    $error = mysql_error();
                    if(!$error) {
                        $this->db_select();
                        $this->set_encoding();
                    }
                    break;
                case 'pgsql':
                    $con_string  =  "host='" . DbConfig::SERVER . "'";
                    $con_string .= " port='" . DbConfig::PORT . "'";
                    $con_string .= " dbname='" . DbConfig::DB . "'";
                    $con_string .= " user='" . DbConfig::USERNAME . "'";
                    $con_string .= " password='" . DbConfig::PASSWORD . "'";
                    $con_string .= " options='--client_encoding=" . DbConfig::ENCODING . "'";

                    $this->link = @pg_connect($con_string);
                    if($this->link) $error = pg_last_error($this->link);
            }
            if(!$this->link) {
                $message = master_lang::database_connection_error;
                if(!empty($error)) $message .= ": " . $error;
                throw new Exception($message);
            }
        }

        // Select the database in DbConfig::DB as active.
        function db_select() {
            $db_selected = mysql_select_db(DbConfig::DB, $this->link);
            if(!$db_selected) throw new Exception(master_lang::database_selection_error . ": " . mysql_error());
        }

        // Sets the connection encoding to DbConfig::ENCODING.
        function set_encoding() {
            $this->query("SET CHARACTER SET " . DbConfig::ENCODING);
        }
		
		// Inserts new record with given values to the table.
		function insert($table, $pairs, $sql_pairs = array()) {
			if(!is_array($pairs)) throw new Exception(master_lang::value_pairs_must_be_array);
            if(!is_array($sql_pairs)) throw new Exception(master_lang::sql_pairs_must_be_array);

            $vals = array();
            foreach($pairs as $k => $v) {
                if(array_key_exists($k, $sql_pairs)) throw new Exception(str_replace("{{KEY}}", $k, lang::pair_collision));
                $vals[$k] = array('value', $v);
            }
            foreach($sql_pairs as $k => $v) $vals[$k] = array('sql', $v);

			// Compose the query.
			$q = "INSERT INTO $table (";
			foreach($vals as $k => $v) {
				$q .= $this->escape_column($k) . ","; // Sloupce
			}
			$q = substr($q, 0, -1) . ") VALUES (";
			foreach($vals as $v) {
                // Hodnoty
                list($type, $val) = $v;
                if($type === 'sql') $q .= $val.",";
                else $q .= "'" . addslashes($val) . "',";
			}
			$q = substr($q, 0, -1) . ")";

			return($this->query($q));
		}
		
		// Runs a query and returns its result as an object.
		function query($q) {
            switch(DbConfig::TYPE) {
                case 'mysql':
                    $res = mysql_query($q, $this->link);
                    if(mysql_errno()) $error = mysql_error();
                    break;
                case 'pgsql':
                    $res = pg_query($this->link, $q);
                    $error = pg_last_error($this->link);
                    break;
            }

            if(!empty($error)) {
                throw new Exception(strtr(master_lang::query_failed, array(
                    "{{ERROR}}" => $error,
                    "{{QUERY}}" => $q
                )));
            }

			$res_obj = new DbResult($res);
			return($res_obj);
		}

		// Runs a query and returns its result as an associative array.
		function fetch_assocs($q) {
			return($res = $this->query($q)->fetch_assocs());
		}
		
		// Runs a query and returns its result as a first column array.
		function fetch_single_fields($q) {
			return($this->query($q)->fetch_single_fields());
		}
		
		// Runs a query and returns its result as a simple array.
		function fetch_rows($q) {
			return($this->query($q)->fetch_rows());
		}
		
		// Deletes a record from the table according to the given condition.
		function delete_where($table, $cond = "TRUE") {
			if(!$cond) $cond = "TRUE";
			if(is_array($cond)) $cond = implode(" AND ", $cond);
			return($this->query("DELETE FROM $table WHERE $cond"));
		}

        // Deletes a record from the table according to the given ID.
		function delete($table, $id) {
			return($this->delete_where($table, "id='$id'"));
		}
		
		// Edits a record from the table according to the given ID and values.
		function update($table, $id, $pairs) {
			return($this->update_where($table, "id='$id'", $pairs));
		}
		
		function update_where($table, $cond, $pairs) {
			foreach($pairs as $k => $v) {
				$vals[] = $this->escape_column($k) . "='" . addslashes($v) . "'";
			}
			if(is_array($cond)) $cond = implode(" AND ", $cond);
			return($this->query("UPDATE $table SET " . implode(",", $vals) . " WHERE $cond"));
		}
		
		// Finds records in a table according to the given conditions and returns the result.
		function select_where($table, $cond = "TRUE", $sloupce = "*", $orderby = "", $limit = "") {
			if(is_array($sloupce)) $sloupce = implode(",", $sloupce);
			if(is_array($cond)) $cond = implode(" AND ", $cond);
			if(!$cond) $cond = "TRUE";
			if(!$sloupce) $sloupce = "*";
			if($orderby) {
				$orderby_suffix = " ORDER BY ";
				(is_array($orderby))?
					$orderby_suffix .= implode(",", $orderby):
					$orderby_suffix .= $orderby;
			} else $orderby_suffix = "";
            if($limit) {
                $limit_suffix = " LIMIT $limit";
            } else $limit_suffix = "";
			
			return($this->query("SELECT $sloupce FROM $table WHERE $cond" . $orderby_suffix . $limit_suffix));
		}
		
		// Finds record in the table according to the ID and returns the result.
		function select($table, $id, $sloupce = null, $orderby = null) {
			return($this->select_where($table, "id='" . $id . "'", $sloupce, $orderby));
		}
		
		// Empties a table.
		function truncate($table, $escaped = false) {
			if(!$escaped) $table = $this->escape($table);
			return($this->query("TRUNCATE TABLE $table"));
		}
		
		function escape($s) {
            switch(DbConfig::TYPE) {
                case 'mysql':
                    $escaped = mysql_real_escape_string($s);
                    break;
                case 'pgsql':
                    $escaped = pg_escape_string($this->link, $s);
                    break;
            }
			return($escaped);
		}

        function escape_column($col) {
            switch(DbConfig::TYPE) {
                case 'mysql':
                    $escaped = "`$col`";
                    break;
                case 'pgsql':
                    $escaped = "\"$col\"";
                    break;
            }

            return $escaped;
        }

		function __destruct() {
//			mysql_close($this->link);
		}
		
	}
?>