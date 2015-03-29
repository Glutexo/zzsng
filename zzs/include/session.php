<?php
class Session {

	public static function setLanguage($language) {
		$_SESSION['language'] = intval($language);
	}

	public static function getLanguage() {
		return $_SESSION['language'];
	}

}