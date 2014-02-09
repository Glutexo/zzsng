<?php
class DbMigration {
	private static $db;

	public static function migrate() {
		if(!self::$db) {
			self::$db = new Db;
		}

		$table = new DbObject("terms");
		$tables = self::$db->list_inherited_tables($table);
		if(!$tables) {
			self::$db->split_table($table);
		}
	}
}
?>