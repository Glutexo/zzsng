<?php
	class Db {
		private $link;

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
		public function insert($table, $pairs, $sql_pairs = array()) {
			if(!is_array($pairs)) throw new Exception(master_lang::value_pairs_must_be_array);
            if(!is_array($sql_pairs)) throw new Exception(master_lang::sql_pairs_must_be_array);

            $vals = array();
            foreach($pairs as $k => $v) {
                if(array_key_exists($k, $sql_pairs)) throw new Exception(str_replace("{{KEY}}", $k, master_lang::pair_collision));
                $vals[$k] = array('value', $v);
            }
            foreach($sql_pairs as $k => $v) $vals[$k] = array('sql', $v);

			$table = $this->get_insert_table($table);

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
                else $q .= $this->escape_string($val) . ",";
			}
			$q = substr($q, 0, -1) . ")";

			return($this->query($q));
		}
		
		// Runs a query and returns its result as an object.
		function query($q) {
			$res = $error = null;

            switch(DbConfig::TYPE) {
                case 'mysql':
                    $res = @mysql_query($q, $this->link);
                    if(mysql_errno()) $error = mysql_error();
                    break;
                case 'pgsql':
                    $res = @pg_query($this->link, $q);
                    $error = pg_last_error($this->link);
                    break;
            }

            if(!empty($error) || !$res) {
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
			$vals = array();
			foreach($pairs as $k => $v) {
				$vals[] = $this->escape_column($k) . "='" . addslashes($v) . "'";
			}
			if(is_array($cond)) $cond = implode(" AND ", $cond);
			return($this->query("UPDATE $table SET " . implode(",", $vals) . " WHERE $cond"));
		}

		// Finds records in a table according to the given conditions and returns the result.
		function select_where($table, $cond = "TRUE", $columns = "*", $orderby = "", $limit = "", $offset = "") {
			if(is_array($columns)) $columns = implode(",", $columns);
			if(is_array($cond)) $cond = implode(" AND ", $cond);
			if(!$cond) $cond = "TRUE";
			if(is_array($cond)) {
				array_walk($cond, function(&$item) {
					$item = "($item)";
				});
				$cond = implode(" AND ", $cond);
			}
			if(!$columns) $columns = "*";

			if($orderby) {
				$orderby_suffix = " ORDER BY ";
				(is_array($orderby))?
					$orderby_suffix .= implode(",", $orderby):
					$orderby_suffix .= $orderby;
			} else {
				$orderby_suffix = "";
			}

			if($limit) {
                $limit_suffix = " LIMIT $limit";
            } else {
				$limit_suffix = "";
			}

			if($offset) {
				if(DbConfig::TYPE === "mysql") {
					throw new Exception(lang::NOT_SUPPORTED_ON_MYSQL);
				}
				$offset_suffix = " OFFSET $offset";
			} else {
				$offset_suffix = "";
			}

			$query = "SELECT $columns";
			$query .= " FROM $table";
			$query .= " WHERE $cond";
			$query .= $orderby_suffix;
			$query .= $limit_suffix;
			$query .= $offset_suffix;

			return $this->query($query);
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
			$escaped = null;
            switch(DbConfig::TYPE) {
                case 'mysql':
                    $escaped = mysql_real_escape_string($s);
                    break;
                case 'pgsql':
                    $escaped = pg_escape_string($this->link, $s);
                    break;
				default:
					throw new Exception(master_lang::unsupported_database_type);
            }
			return($escaped);
		}

        function escape_column($col) {
			$escaped = null;
            switch(DbConfig::TYPE) {
                case 'mysql':
                    $escaped = "`$col`";
                    break;
                case 'pgsql':
                    $escaped = "\"$col\"";
                    break;
				default:
					throw new Exception(master_lang::unsupported_database_type);
            }

            return $escaped;
        }

		function escape_string($s) {
			return "'".$this->escape($s)."'";
		}

		public function list_tables($conds = array()) {
			if(DbConfig::TYPE === 'mysql') {
				throw new Exception(lang::NOT_SUPPORTED_ON_MYSQL);
			}

			if(!is_array($conds)) {
				$conds = array($conds);
			}

			$table = $this->escape_column("information_schema").".".$this->escape_column("tables");
			$conds[] = $this->escape_column("table_schema")." = ".$this->escape_string("public");
			$cols = array("table_name");
			$orderby = new DbObject("table_name");

			$result = $this->select_where($table, $conds, $cols, $orderby);
			return $result->fetch_single_fields();
		}

		/**
		 * Gets a list of all tables inherited from the given
		 * one. E. g. if a table name "table" is given, a list
		 * like ["table 1", "table 2"] may be returned.
		 *
		 * The function assumes the naming convention that
		 * all inherited tables have a name of their parent
		 * followed by a single space and a number.
		 *
		 * The list is sorted in an ascending order by the
		 * number.
		 *
		 * @param $table
		 * @return array
		 */
		public function list_inherited_tables($table) {
			$table_list = $this->list_tables();
			if(is_a($table, "DbObject")) {
				$table = clone $table;
				$table->escape = false;
			}
			$table_list = array_filter($table_list, function($item) use($table) {
				$table = preg_quote($table, "/");
				return preg_match("/^$table \\d+$/", $item);
			});

			$db = $this;
			usort($table_list, function($a, $b) use($db) {
				$a_num = $db->get_table_number($a);
				$b_num = $db->get_table_number($b);
				if($a_num > $b_num) {
					return 1;
				} elseif($a_num < $b_num) {
					return -1;
				} elseif($a_num == $b_num) {
					return 0;
				} else {
					throw new Exception(master_lang::comparison_error);
				}
			});

			return $table_list;
		}

		public function get_table_number($table) {
			if(is_a($table, "DbObject")) {
				$table = clone $table;
				$table->escape = false;
			}

			if(preg_match("/ (\\d+)\$/", $table, $match)) {
				return intval($match[1]);
			}

			return "";
		}

        public function get_max_table_number($table) {
            $tables = $this->list_inherited_tables($table);
            $last_table = array_pop($tables);

            if(!is_a($last_table, "DbObject")) {
                $last_table = new DbObject($last_table);
            }

            return $this->get_table_number($last_table);
        }

		public function row_count($table) {
			if(!is_a($table, "DbObject")) {
				$table = new DbObject($table);
			}

			$result = $this->select_where($table, "TRUE", "count(*)");
			$count = $result->fetch_single_field();
			return intval($count);
		}

		function get_primary_key($table) {
			if(DbConfig::TYPE === "mysql") {
				throw new Exception(lang::NOT_SUPPORTED_ON_MYSQL);
			}

			if(is_a($table, "DbObject")) {
				$table = clone $table;
				$table->escape = false;
			}
			$table_escaped = $this->escape_string($table);

			$sql = <<<"EOQ"
				SELECT "kcu"."column_name"
				FROM "information_schema"."key_column_usage" AS "kcu"
					JOIN "information_schema"."table_constraints" AS "tc"
						ON "tc"."constraint_name" = "kcu"."constraint_name"
				WHERE "tc"."constraint_type" = 'PRIMARY KEY'
					AND "kcu"."table_name" = $table_escaped
EOQ;
			$result = $this->query($sql);
			$pk_name = $result->fetch_single_field();
			$pk = new DbObject($pk_name);
			return $pk;
		}

		function table_exists($table) {
			$tables = $this->list_tables();
			return in_array($table, $tables);
		}

		private function create_inherited_table($new_table, $source_table) {
			if(DbConfig::TYPE !== 'pgsql') {
				throw new Exception(lang::NOT_SUPPORTED_ON_MYSQL);
			}

			if(!is_a($new_table, "DbObject")) {
				$new_table = new DbObject($new_table);
			}

			if(!is_a($source_table, "DbObject")) {
				$source_table = new DbObject($source_table);
			}

			$sql = <<<"EOQ"
CREATE TABLE $new_table (
	LIKE $source_table
	INCLUDING DEFAULTS
	INCLUDING CONSTRAINTS
	INCLUDING INDEXES
	INCLUDING STORAGE
	INCLUDING COMMENTS
) INHERITS ($source_table)
EOQ;
			$this->query($sql);
			return "";
		}

		/**
		 * Creates inherited tables from the given one, each to contain a
		 * maximum of given number of rows. If a table has 105 records and
		 * a limit of 10 is given, 11 inherited tables will be created. The
		 * pattern for new table names is "original_table_name 1" with the
		 * number being separated with a single space and being increased
		 * by one for each new table. The first one has number 1.
		 *
		 * This function assumes that the table is not split yet and no
		 * inherited tables exist.
		 *
		 * @param $table_name
		 * @param $row_limit
		 */
		public function split_table($parent_table) {
			if(!defined('DbConfig::ROW_LIMIT')) {
				throw new Exception(master_lang::no_row_limit_set);
			}
			if(!is_a($parent_table, "DbObject")) {
				$parent_table = new DbObject($parent_table);
			}
			$child_table = clone $parent_table;
			$pk = $this->get_primary_key($parent_table);
            $pk_name = $pk->expression;

			$result_all_rows = $this->select_where($parent_table, "TRUE", "*", $pk);

			$child_table_number = 1;
			$rows_inserted = 0;
			while($row = $result_all_rows->fetch_assoc()) {
				// A new table needs to be created.
				$first_record = $rows_inserted == 0;
				$limit_reached = $rows_inserted == DbConfig::ROW_LIMIT;
				if($first_record || $limit_reached) {
					if($limit_reached) {
						$child_table_number++;
						$rows_inserted = 0;
					}

					$parent_table_name = $parent_table->expression;
					$child_table_name = "$parent_table_name $child_table_number";
					$child_table = new DbObject($child_table_name);
					$this->create_inherited_table($child_table, $parent_table);
				}

				// Delete before insert. Insert is into the specific inherited
				// table, but delete without the ONLY keyword affects not only
				// the parent table, but the children tables as well.
				$this->delete($parent_table, $row[$pk_name]);
				$this->insert($child_table, $row);

				$rows_inserted++;
			}
			return;
		}

        public function merge_table($parent_table) {
            if(!is_a($parent_table, "DbObject")) {
                $parent_table = new DbObject($parent_table);
            }

            $pk = $this->get_primary_key($parent_table);
            $pk_name = $pk->expression;

            $tables = $this->list_inherited_tables($parent_table);
            foreach($tables as $child_table) {
                if(!is_a($child_table, "DbObject")) {
                    $child_table = new DbObject($child_table);
                }
                $rows = $this->select_where($child_table, "TRUE", "*", $pk);

                while($row = $rows->fetch_assoc()) {
                    $this->delete($child_table, $row[$pk_name]);
                    $this->insert($parent_table, $row);
                }

                $this->drop_table($child_table);
            }
        }

        public function drop_table($child_table) {
            if(!is_a($child_table, "DbObject")) {
                $child_table = new DbObject($child_table);
            }

            return $this->query("DROP TABLE $child_table");
        }

		/**
		 * Figures out which table to insert a new row into. Figures
		 * out, whether the table is a split table and if so, which
		 * partition is the last. Then it checks whether the last
		 * partitions has enough space for another row to be inserted.
		 * If not it creates a new partition.
		 *
		 * Would be slow on massive inserts, because no caching is
		 * done.
		 *
		 * @param $table
		 */
		private function get_insert_table($table) {
			$table_list = $this->list_inherited_tables($table);
			if(!$table_list) {
				return $table;
			}
			list($last_table) = array_slice($table_list, -1);

			$last_table = new DbObject($last_table);
			if(!defined("DbConfig::ROW_LIMIT")) {
				// If the row limit disappeard, let’s insert simply
				// to the last table.
				return $last_table;
			}

			$count = $this->row_count($last_table);
			if($count < DbConfig::ROW_LIMIT) {
				return $last_table;
			}

			$table_num = $this->get_table_number($last_table);
			if(!$table_num) {
				return $table;
			}
			$table_num++;

			$table_name = $table;
			if(is_a($table_name, "DbObject")) {
				$table_name = $table->expression;
			}
			$new_table = new DbObject("$table_name $table_num");
			$this->create_inherited_table($new_table, $table);

			return $new_table;
		}

		function __destruct() {
//			mysql_close($this->link);
		}

	}
?>