<?php
class LessonsControllerTest extends ZzsTestCase {

	public function setUp() {
		parent::setUp();
		$this->initDb();
		$this->enumerateExistingLanguages();

		self::includeProjectClass('Lesson');

		$_SESSION = array(
			master_config::APPLICATION => array(
				'active_user' => config::DEMO_USER_ID
			)
		);
	}

	public function testLessonListCallWithoutLanguagePickedFails() {
		$self = $this;

		static::withSessionBackup(function() use($self) {
			$controller = new LessonsController;
			$controller->get_list();

			$self->assertCount(1, $controller->error, "Lesson enumeration does not fail upon no language being picked.");

			list($error) = $controller->error;
			$self->assertStringEndsWith(lang::no_language_picked, $error, "Lesson enumeration does not fail with the right error when no language is picked.");
		});
	}

	public function testLessonListCallWithLanguagePickedSucceeds() {
		$session = new Session($this->db);
		$self = $this;

		static::withSessionBackup(function() use($self, $session) {
			$randomLanguage = $self->pickRandomLanguage();
			$session->setLanguage($randomLanguage);

			$controller = new LessonsController;
			$controller->get_list();

			$self->assertEmpty($controller->error, "Lesson enumeration fails when current language is set in the session.");
		});
	}

}