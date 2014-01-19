<?php
	class DbResult {
		function __construct($res) {
			$this->res = $res;
		}
		
		// Object alias of mysql_fetch_assoc().
		function fetch_assoc() {
            switch(DbConfig::TYPE) {
                case 'mysql':
                    $assoc = mysql_fetch_assoc($this->res);
                    break;
                case 'pgsql':
                    $assoc = pg_fetch_assoc($this->res);
                    break;
            }
			return($assoc);
		}
		
		// Object alias of mysql_fetch_object().
		function fetch_object() {
            switch(DbConfig::TYPE) {
                case 'mysql':
                    $object = mysql_fetch_object($this->res);
                    break;
                case 'pgsql':
                    $object = pg_fetch_object($this->res);
                    break;
            }
			return($object);
		}
		
		function fetch_single_field() {
            switch(DbConfig::TYPE) {
                case 'mysql':
                    list($field) = mysql_fetch_row($this->res);
                    break;
                case 'pgsql':
                    list($field) = pg_fetch_row($this->res);
                    break;
            }
			return($field);
		}

		function fetch_single_fields() {
            switch(DbConfig::TYPE) {
                case 'mysql':
                    while($field = mysql_fetch_row($this->res))
                        $fields[] = $field[0];
                    break;
                case 'pgsql':
                    while($field = pg_fetch_row($this->res))
                        $fields[] = $field[0];
                    break;
            }
			return($fields);
		}

		function fetch_rows() {
			$rows = array();
            switch(DbConfig::TYPE) {
                case 'mysql':
                    while($row = mysql_fetch_row($this->res))
                        $rows[] = $row;
                    break;
                case 'pgsql':
                    while($row = pg_fetch_row($this->res))
                        $rows[] = $row;
                    break;
            }
 			return($rows);
		}

		function fetch_assocs() {
			$data = array();
			while($row = $this->fetch_assoc())
				$data[] = $row;

			return($data);
		}
	}
?>