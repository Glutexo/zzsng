<?php
class SessionTest extends ZzsTestCase {

	private static function withSessionBackup($test) {
		$_session = $_SESSION;
		call_user_func($test);
		$_SESSION = $_session;
	}

	public function setUp() {
		parent::setUp();
		static::requireDb();

		$_SESSION = array();
	}

	public function testLanguageCanBeGotten() {
		$self = $this;
		self::withSessionBackup(function() use($self) {
			$language = new stdClass;
			$_SESSION['language'] = $language;
			$self->assertSame($language, Session::getLanguage(), "Current language cannot be retrieved from the session.");
		});
	}

	/**
	 * @depends testLanguageCanBeGotten
	 */
	public function testLanguageCanBeSet() {
		$self = $this;
		self::withSessionBackup(function() use($self) {
			$_SESSION['language'] = null;
			$language = 1;
			Session::setLanguage($language);
			$self->assertEquals($language, Session::getLanguage(), "Current language cannot be saved to the session.");
		});
	}
	/**
	 * @depends testLanguageCanBeSet
	 */
	public function testLanguageIsSetAsInteger() {
		$self = $this;
		self::withSessionBackup(function() use($self) {
			$_SESSION['language'] = null;
			$language = '1';
			Session::setLanguage($language);
			$self->assertInternalType('integer', Session::getLanguage(), "Current language is not stored as an integer in the session.");
		});
	}

}
