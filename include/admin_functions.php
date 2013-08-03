<?php
	class AdminFunctions {
		static function dbtime($time = null) {
			if(!$time) $time = time();
			return(date("Y-m-d H:i:s", $time));
		}

		static function unaccent($s) {
			return(strtr($s, array(
				"Á" => "A",
				"á" => "a",
				"Č" => "C",
				"č" => "c",
				"Ď" => "D",
				"ď" => "d",
				"É" => "E",
				"é" => "e",
				"Ě" => "E",
				"ě" => "e",
				"Í" => "I",
				"í" => "i",
				"Ň" => "N",
				"ň" => "n",
				"Ó" => "O",
				"ó" => "o",
				"Ř" => "R",
				"ř" => "r",
				"Š" => "S",
				"š" => "s",
				"Ť" => "T",
				"ť" => "t",
				"Ú" => "U",
				"ú" => "u",
				"Ů" => "U",
				"ů" => "u",
				"Ý" => "Y",
				"ý" => "y",
				"Ž" => "Z",
				"ž" => "z"
            )));
		}

        static function lang_to_array() {
            $refl = new ReflectionClass("lang");
            return $refl->getConstants();
        }
	}
?>