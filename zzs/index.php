<?php

	/* *** Launching procedure *** */
	
	// Set up some global settings specific for this application.
	class config extends master_config {
	}

	class Application implements iApplication {
		public $out = "";

		/* *** Decider sections. *** */
		
		public function section_default($selected_section = false) {
			$examiner = new Examiner;
//            if($selected_section) $examiner->error[] = lang::section_does_not_exist;
			$this->out .= $examiner->out();
		}

		public function section_import() {
			$importer = new Importer;
			$this->out .= $importer->out();
		}

		public function section_bulk() {
			$importer = new Importer;
			$this->out .= $importer->out();
		}
		
		public function section_lessons() {
			$lessoner = new Lessoner;
			$this->out .= $lessoner->out();
		}
		
		public function section_terms() {
			$termer = new Termer;
			$this->out .= $termer->out();
		}
		
		public function section_languages() {
			$languager = new Languager;
			$this->out .= $languager->out();
		}
		
		public function section_exam() {
			$this->section_default(true);
		}

        public function section_setup() {
            $setup = new Setup;
            $this->out = $setup->out();
        }
	}
?>