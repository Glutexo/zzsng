<?php
	/* *** Launching procedure *** */
	
	// Set up some global settings specific for this application.
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

	class Application {
		public $out = "";

		public function __construct() {
			$login = new Login;
			$login->ensure();

			DbMigration::migrate();
		}

		/* *** Decider sections. *** */
		
		public function section_bulk() {
			$importer = new ImportController;
			$this->out .= $importer->out();
		}
	}
?>