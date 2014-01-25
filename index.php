<?php
	/* Master settings applied to all applications. */
	class master_config {
		const MASTER_INCLUDE_DIR = "include/";
        const MASTER_INCLUDE_MASK = "/\\.php$/";
        const MASTER_LANG_DIR = "lang/";
        const DEFAULT_EXTENSION = ".php";
	
		const SECTION_FUNCTION_PREFIX = "section_";
		const DEFAULT_SECTION_NAME = "default";
        const DEFAULT_LANGUAGE = "cs";

		const APPLICATION = "zzs";
		const INITIALIZATION_SCRIPT = "/index.php";
		const TEMPLATE_DIR = "/template/";
		const INCLUDE_DIR = "/include/";
		const INCLUDE_MASK = "/\\.php$/";
        const LANG_DIR = "/lang/";

		const TIME_ZONE = "Europe/Prague";

		static function debug($s) { return("<pre>" . print_r($s, true) . "</pre>"); }
		
		// Includes/requires all files from the directory according to the mask.
		static function include_dir($dir, $mask, $required) {
			if(file_exists($dir) && is_dir($dir)) {
				$d = dir($dir);
				while($f = $d->read()) {
					if(preg_match($mask, $f)) {
						$f = $dir . $f;
						if($required) require_once($f);
						else include_once($f);
					}
				}
			} elseif($required) {
                switch(self::DEFAULT_LANGUAGE) {
                    case "cs": $message = "Adresář pro zahrnutí souborů neexistuje."; break;
                    case "en": $message = "Include folder does not exist."; break;
                }
                throw new Exception($message);
            }
		}
	}
    
    session_start();
	
	// Include all framework PHP files.

	date_default_timezone_set(master_config::TIME_ZONE);
    require_once(master_config::MASTER_LANG_DIR . master_config::DEFAULT_LANGUAGE . master_config::DEFAULT_EXTENSION);
	master_config::include_dir(master_config::MASTER_INCLUDE_DIR, master_config::INCLUDE_MASK, true);

	if(!file_exists(master_config::APPLICATION) || !is_dir(master_config::APPLICATION)) throw new Exception("Aplikace neexistuje.");

	require(master_config::APPLICATION . master_config::INITIALIZATION_SCRIPT);
	
	// Include all application PHP files.
    require_once(config::APPLICATION . master_config::LANG_DIR . master_config::DEFAULT_LANGUAGE . master_config::DEFAULT_EXTENSION);
	master_config::include_dir(config::APPLICATION . config::INCLUDE_DIR, config::INCLUDE_MASK, false);

	// Begin with a header.
	
	$user_interface = new UserInterface;
	$doc = $user_interface->out_head();
	
	/* *** The application itself *** */
	
	$application = new Application; // Application instance to be given to the Decider.

	// Module selection.
	
	$section = (isset($_REQUEST['section']) ? $_REQUEST['section'] : null);
	
	try {
		$decider = new Decider;
		$decider->perform($application, $section);
		$doc .= $decider->out($application);
	} catch(Exception $e) {
		$tpl = new Template;
		$tpl->reg("ERROR", $e->getMessage(), true);
        $tpl->reg("LANG", AdminFunctions::lang_to_array(), true);
		$tpl->load("master_error.tpl");
		$tpl->execute();
		$doc .= $tpl->out();
	}

	// End with a footer.
	
	$doc .= $user_interface->out_foot();
	
	// Output!
	
	print($doc); // Final output
?>