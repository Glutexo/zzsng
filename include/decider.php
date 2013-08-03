<?php
    require_once(master_config::MASTER_INCLUDE_DIR . 'doer.php');
	class Decider extends Doer {
		var $out = "";
		
		// Does what shall be done according to what shall be done.
		function perform($application, $section) {
			$function_name = config::SECTION_FUNCTION_PREFIX . $section;
			if(method_exists($application, $function_name)) $application->$function_name();
			else $application->{config::SECTION_FUNCTION_PREFIX . config::DEFAULT_FUNCTION_NAME}();
		}
		
		function out($application) {
			return(parent::out($application->out));
		}
		
	}
?>