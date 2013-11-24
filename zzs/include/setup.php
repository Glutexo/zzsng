<?php
class Setup {
    private $filename;

    function __construct() {
        $this->db = new Db;

        $this->filename = "structure." . DbConfig::TYPE . ".sql";
    }

    function import() {
        $sql = @file_get_contents($this->filename);
        if(!$sql) throw new Exception(lang::sql_file_read_error);
        $sql($this->db->query($sql));
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