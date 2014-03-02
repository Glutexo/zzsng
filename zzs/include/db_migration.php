<?php
class DbMigration {
	private static $db;

	public static function migrate() {
        self::merge_terms();
	}

    private static function ensure_db() {
        if(!self::$db) {
            self::$db = new Db;
        }
    }

    /**
     * If there is a split table of terms in the db,
     * it will be merged.
     */
    private static function merge_terms() {
        self::ensure_db();

        $table = new DbObject("terms");
        $tables = self::$db->list_inherited_tables($table);
        if($tables) {
            self::$db->merge_table($table);
        }
    }
}
?>