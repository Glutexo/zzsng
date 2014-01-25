<?php
class SetupController {
    const FILENAME = "sql/structure.sql";

    function __construct() {
        $this->db = new Db;
    }

    function import() {
        $sql = file_get_contents(self::FILENAME);
        $this->db->query($sql);
    }

    function out() {
        try {
            $this->import();
            return lang::structure_import_success;
        } catch(Exception $e) {
            return lang::structure_import_error . ": " . $e->getMessage();
        }
    }
}