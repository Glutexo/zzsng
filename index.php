<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

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

/* *** The application itself *** */

$application = new Application; // Application instance to be given to the Decider.

Form2Json::Json2Request();

// Module selection.

$section = (isset($_REQUEST['section']) ? $_REQUEST['section'] : null);

try {
	$decider = new Decider;
	$decider->perform($application, $section);
	$body = $decider->out($application);
} catch(Exception $e) {
	$tpl = new Template;
	$tpl->reg("ERROR", $e->getMessage(), true);
	$tpl->reg("LANG", AdminFunctions::lang_to_array(), true);
	$tpl->load("master_error.tpl");
	$tpl->execute();
	$body = $tpl->out();
}

// Compose the page.
$user_interface = new UserInterface;
$head = $user_interface->out_head();
$foot = $user_interface->out_foot();
$doc = $head.$body.$foot;

// Output!

print($doc); // Final output
?>