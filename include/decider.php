<?php
    require_once(master_config::MASTER_INCLUDE_DIR . 'doer.php');
	class Decider extends Doer {
		var $out = "";
		
		// Does what shall be done according to what shall be done.
		function perform($application, $section) {
			$function_name = config::SECTION_FUNCTION_PREFIX . $section;
			if($section) {
				if(method_exists($application, $function_name)) {
					$application->$function_name();
					return;
				} else {
					$controller_name = Helpers::ConvertToCamelCase($section)."Controller";
					if(class_exists($controller_name) && method_exists($controller_name, 'out')) {
						$controller = new $controller_name;
						$application->out .= $controller->out();
						return;
					}
				}
			}

			// If the appropriate controller couldn’t be launched, fall back to
			// the default one. If $section is already a default controller and
			// the code reached this point, there is nowhere to fall back.
			if($section !== config::DEFAULT_SECTION_NAME) {
				return $this->perform($application, config::DEFAULT_SECTION_NAME);
			}

			return;
		}
		
		function out($application = "") {
			if(is_object($application)) {
				$out = $application->out;
			} else {
				$out = $application;
			}
			return parent::out($out);
		}
		
	}
?>