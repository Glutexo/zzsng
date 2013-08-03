<?php
	class Doer {
		var $notice = array();
		var $warning = array();
		var $error = array();
		
		function __construct() {
			$this->db = new Db;
		}
	
		// Composes a page block with status messages.
		function out_status() {
			$tpl = new Template;
			
			$tpl->reg("NOTICE", $this->notice, true);
			$tpl->reg("WARNING", $this->warning, true);
			$tpl->reg("ERROR", $this->error, true);
			
			$tpl->load("status.tpl");
			$tpl->execute();
			return($tpl->out());
		}
		
		function out($out = "") {
			return($this->out_status() . $out);
		}
	}
?>