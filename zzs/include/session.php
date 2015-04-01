<?php
class Session {
	private $db;

	public function __construct(Db $db) {
		$this->db = $db;
	}

	public function setLanguage($language) {
		$language = intval($language);

		$tableName = new DbObject(Language::TABLE_LANGUAGES);
		$idColName = new DbObject(Language::COL_ID);

		list($exists) = $this->db->fetch_single_fields(<<<EOSQL
SELECT EXISTS(
	SELECT 1
	FROM $tableName
	WHERE $idColName = $language
		AND $idColName > 0
)
EOSQL
);

		if($exists == 't') {
			$_SESSION[master_config::APPLICATION]['language'] = $language;
		}
	}

	public function getLanguage() {
		return array_key_exists(master_config::APPLICATION, $_SESSION) && is_array($_SESSION[master_config::APPLICATION]) && array_key_exists('language', $_SESSION[master_config::APPLICATION]) ?
			$_SESSION[master_config::APPLICATION]['language'] :
			null;
	}

}