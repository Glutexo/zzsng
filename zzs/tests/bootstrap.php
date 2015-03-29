<?php
class ZzsTestCase extends PHPUnit_Framework_TestCase {
	const FILE_EXTENSION_SEPARATOR = '.';
	const PHP_FILE_EXTENSION = 'php';

	public $existingLanguages;
	protected $db;

	public function setUp() {
		$frameworkBasePath = self::frameworkBasePath();
		require_once $frameworkBasePath . DIRECTORY_SEPARATOR . 'config.php';

		$frameworkIncludePath = self::frameworkIncludePath();
		require_once $frameworkIncludePath . DIRECTORY_SEPARATOR . 'Helpers.php';

		$className = get_class($this);
		$classNameStripped = preg_replace('/Test$/', '', $className);
		self::includeProjectClass($classNameStripped);
	}

	public function initDb() {
		self::requireDb();
		$this->db = new Db;
		$this->db->connect();
	}

	public static function includeProjectClass($className) {
		$classNameSnake = Helpers::ConvertToSnakeCase($className);

		$projectIncludePath = self::projectIncludePath();
		require_once $projectIncludePath . DIRECTORY_SEPARATOR . $classNameSnake . self::FILE_EXTENSION_SEPARATOR . self::PHP_FILE_EXTENSION;
	}

	private static function projectIncludePath() {
		$pathParts = array(__DIR__, '..', 'include');
		return implode(DIRECTORY_SEPARATOR, $pathParts);
	}

	private static function frameworkBasePath() {
		$pathParts = array(__DIR__, '..', '..');
		return implode(DIRECTORY_SEPARATOR, $pathParts);
	}

	private static function frameworkIncludePath() {
		return self::frameworkBasePath() . DIRECTORY_SEPARATOR . 'include';
	}

	private static function requireDb() {
		$includePath = self::frameworkIncludePath();
		$filesToInclude = array('db', 'db_config', 'db_object', 'db_result');
		array_walk($filesToInclude, function($fileToInclude) use($includePath) {
			require_once $includePath . DIRECTORY_SEPARATOR . $fileToInclude . ZzsTestCase::FILE_EXTENSION_SEPARATOR . ZzsTestCase::PHP_FILE_EXTENSION;
		});
	}

	protected static function withSessionBackup($test) {
		$_session = $_SESSION;
		call_user_func($test);
		$_SESSION = $_session;
	}

	protected function enumerateExistingLanguages() {
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

	public function pickRandomLanguage() {
		$randomKey = array_rand($this->existingLanguages);
		return $this->existingLanguages[$randomKey];
	}

}
