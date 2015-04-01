<?php
class SessionTest extends ZzsTestCase {
	public $session;

	public function setUp() {
		parent::setUp();
		$this->initDb();

		$this->session = new Session($this->db);

		static::includeProjectClass('Language');
		$this->enumerateExistingLanguages();

		$_SESSION = array();
	}

	public function testLanguageCanBeGotten() {
		$self = $this;
		static::withSessionBackup(function() use($self) {
			$session = $self->session;

			$language = new stdClass;
			$_SESSION[master_config::APPLICATION]['language'] = $language;
			$self->assertSame($language, $session->getLanguage(), "Current language cannot be retrieved from the session.");
		});
	}

	/**
	 * @depends testLanguageCanBeGotten
	 */
	public function testLanguageCanBeSet() {
		$self = $this;
		static::withSessionBackup(function() use($self) {
			$session = $self->session;

			$randomLanguage = $self->pickRandomLanguage();
			$session->setLanguage($randomLanguage);

			$sessionLanguage = $session->getLanguage();
			$self->assertNotNull($sessionLanguage, "Current language cannot be saved to the session.");
			$self->assertEquals($randomLanguage, $sessionLanguage, "Current language is not correctly saved to the session.");
		});
	}

	/**
	 * @depends testLanguageCanBeSet
	 */
	public function testLanguageIsSetAsInteger() {
		$self = $this;
		static::withSessionBackup(function() use($self) {
			$session = $self->session;

			$randomLanguage = $self->pickRandomLanguage();

			$session->setLanguage($randomLanguage);
			$self->assertInternalType('integer', $session->getLanguage(), "Current language is not stored as an integer in the session.");
		});
	}

	/**
	 * @depends testLanguageCanBeSet
	 */
	public function testInvalidLanguageCannotBeSet() {
		$self = $this;
		static::withSessionBackup(function() use($self) {
			$session = $self->session;

			$maxId = max($self->existingLanguages);
			$nonExistantId = $maxId + 1;

			$session->setLanguage($nonExistantId);
			$self->assertNull($session->getLanguage());
		});
	}

}
