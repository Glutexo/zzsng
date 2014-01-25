<?php
class Helpers {
	static function ConvertToCamelCase($string) {
		$parts = preg_split("/[^a-z]+/i",$string);
		foreach($parts as &$part) {
			$first_letter = substr($part, 0, 1);
			$rest = substr($part, 1);
			$part = strtoupper($first_letter).strtolower($rest);
		}
		unset($part);

		return implode("", $parts);
	}
}
?>