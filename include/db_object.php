<?php
class DbObject {
	public $escape = true;
	public $expression;

	private $db;

	/**
	 * The simpliest expression is a table name and it is escaped
	 * by default. If a full SQL expression is provided and it
	 * shall not be escaped, the $escape property must be set to
	 * false.
	 *
	 * @param $expression
	 * @param $escape
	 */
	public function __construct($expression, $escape = true) {
		$this->db = new Db;

		// The simpliest expression is just a table name.
		$this->expression = $expression;
		$this->escape = $escape;
	}

	public function __toString() {
		if($this->escape) {
			return $this->db->escape_column($this->expression);
		} else {
			return $this->expression;
		}
	}
}
?>