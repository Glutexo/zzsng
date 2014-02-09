<?php

	/* *** Launching procedure *** */
	
	// Set up some global settings specific for this application.
	class config extends master_config {
		const DEMO_USER_ID = 2;

		const DEFAULT_SECTION_NAME = "exam";
	}

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