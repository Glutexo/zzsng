<?php
/* Master settings applied to all applications. */
class master_config {
	const MASTER_INCLUDE_DIR = "include/";
	const MASTER_INCLUDE_MASK = "/\\.php$/";
	const MASTER_LANG_DIR = "lang/";
	const DEFAULT_EXTENSION = ".php";

	const SECTION_FUNCTION_PREFIX = "section_";
	const DEFAULT_FUNCTION_NAME = "default";
	const DEFAULT_LANGUAGE = "en";

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
