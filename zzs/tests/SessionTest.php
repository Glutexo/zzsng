<?php
class SessionTest extends ZzsTestCase {
	public $existingLanguages;
	public $session;

	private static function withSessionBackup($test) {
		$_session = $_SESSION;
		call_user_func($test);
		$_SESSION = $_session;
	}

	private function enumerateExistingLanguages() {
		$tableName = new DbObject(Language::TABLE_LANGUAGES);
		$idColName = new DbObject(Language::COL_ID);
		$result = $this->db->query(<<<EOSQL
SELECT $idColName
FROM $tableName
WHERE $idColName > 0
EOSQL
		);

		$this->existingLanguages = array();
		while(!is_null($id = $result->fetch_single_field())) {
			$this->existingLanguages[] = intval($id);
		}
	}

	public function setUp() {
		parent::setUp();
		$this->initDb();

		$this->session = new Session($this->db);

		static::includeProjectClass('Language');
		$this->enumerateExistingLanguages();

		$_SESSION = array();
	}

	public function pickRandomLanguage() {
		$randomKey = array_rand($this->existingLanguages);
		return $this->existingLanguages[$randomKey];
	}

	public function testLanguageCanBeGotten() {
		$self = $this;
		self::withSessionBackup(function() use($self) {
			$session = $self->session;

			$language = new stdClass;
			$_SESSION['language'] = $language;
			$self->assertSame($language, Session::getLanguage(), "Current language cannot be retrieved from the session.");
			$self->assertSame($language, $session->getLanguage(), "Current language cannot be retrieved from the session.");
		});
	}

	/**
	 * @depends testLanguageCanBeGotten
	 */
	public function testLanguageCanBeSet() {
		$self = $this;
		self::withSessionBackup(function() use($self) {
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
		self::withSessionBackup(function() use($self) {
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
		self::withSessionBackup(function() use($self) {
			$session = $self->session;

			$maxId = max($self->existingLanguages);
			$nonExistantId = $maxId + 1;

			$session->setLanguage($nonExistantId);
			$self->assertNull($session->getLanguage());
		});
	}

}
