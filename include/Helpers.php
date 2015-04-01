<?php
class Helpers {

	public static function ConvertToCamelCase($string) {
		$parts = preg_split("/[^a-z]+/i",$string);
		foreach($parts as &$part) {
			$first_letter = substr($part, 0, 1);
			$rest = substr($part, 1);
			$part = strtoupper($first_letter).strtolower($rest);
		}
		unset($part);

		return implode("", $parts);
	}

	public static function ConvertToSnakeCase($string) {
		preg_match_all("/[A-Z][a-z]*/", $string, $matches);
		list($camelParts) = $matches;
		$snakeParts = array_map(function($part) {
			return strtolower($part);
		}, $camelParts);
		return implode('_', $snakeParts);
	}

}
?>