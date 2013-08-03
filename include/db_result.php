<?php
	class DbResult {
		function __construct($res) {
			$this->res = $res;
		}
		
		// Object alias of mysql_fetch_assoc().
		function fetch_assoc() {
			return(mysql_fetch_assoc($this->res));
		}
		
		// Object alias of mysql_fetch_object().
		function fetch_object() {
			return(mysql_fetch_object($this->res));
		}
		
		function fetch_single_field() {
			list($field) = mysql_fetch_row($this->res);
			return($field);
		}

		function fetch_single_fields() {
			while($field = mysql_fetch_row($this->res))
				$fields[] = $field[0];
			return($fields);
		}

		function fetch_rows() {
			while($row = mysql_fetch_row($this->res))
				$rows[] = $row;
			return($rows);
		}

		function fetch_assocs() {
			while($row = $this->fetch_assoc())
				$data[] = $row;

			return($data);
		}
	}
?>