<?php
class ZzsTestCase extends PHPUnit_Framework_TestCase {
	const FILE_EXTENSION_SEPARATOR = '.';
	const PHP_FILE_EXTENSION = 'php';

	protected $db;

	public function setUp() {
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

	private static function frameworkIncludePath() {
		$pathParts = array(__DIR__, '..', '..', 'include');
		return implode(DIRECTORY_SEPARATOR, $pathParts);
	}

	private static function requireDb() {
		$includePath = self::frameworkIncludePath();
		$filesToInclude = array('db', 'db_config', 'db_object', 'db_result');
		array_walk($filesToInclude, function($fileToInclude) use($includePath) {
			require_once $includePath . DIRECTORY_SEPARATOR . $fileToInclude . ZzsTestCase::FILE_EXTENSION_SEPARATOR . ZzsTestCase::PHP_FILE_EXTENSION;
		});
	}

}
