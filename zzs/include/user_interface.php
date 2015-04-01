<?php
	class UserInterface {
		var $xmlhead = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>"; // "
		var $links = array(
			array(
				"text" => lang::languages,
				"href" => "index.php?section=languages",
				"style" => ""
			),
			array(
				"text" => lang::lessons,
				"href" => "index.php?section=lessons",
				"style" => ""
			),
			array(
				"text" => lang::exam,
				"href" => "index.php?section=exam",
				"style" => "font-weight: bold; text-transform: uppercase;"
			),
			array(
				"text" => lang::import,
				"href" => "index.php?section=import",
				"style" => ""
			),
			array(
				"text" => lang::bulk,
				"href" => "index.php?section=bulk",
				"style" => ""
			)
        );

		/* Inserts header. */
		function out_head() {
			$out = $this->xmlhead;

			$db = new Db;
			$db->connect();

			$session = new Session($db);
			$languages_controller = new LanguagesController;

			$login_obj = new Login;
			$active_user_login = $login_obj->get_active_user_login();

			$tpl = new Template;
			$tpl->reg("PROJECT_NAME", lang::project_name, true);
			$tpl->reg("TEMPLATE_DIR", config::APPLICATION . config::TEMPLATE_DIR, true);

			$tpl->reg("LOGIN", $active_user_login, true);

			$section = array_key_exists('section', $_GET) ?
				$_GET['section'] :
				config::DEFAULT_SECTION_NAME;

			$controllerClassName = "{$section}_controller";
			$controllerClassName = Helpers::ConvertToCamelCase($controllerClassName);

			$constantName = "$controllerClassName::BASE_SECTION";

			$section = defined($constantName) ?
				constant($constantName) :
				$section;
			$tpl->reg("SECTION", $section, true);

			$tpl->reg("LANGUAGES", $languages_controller->get_list(), true);
			$tpl->reg("LANGUAGE", $session->getLanguage(), true);

			$tpl->reg("LANG", AdminFunctions::lang_to_array(), true);

			$tpl->load("header.tpl");
			$tpl->execute();
			$out .= $tpl->out();

			return($out);
		}

		/* Inserts footer. */
		function out_foot() {
			$tpl = new Template;
			$tpl->reg("LINKS", $this->links, true);
			$tpl->load("footer.tpl");
			$tpl->execute();
			return($tpl->out());
		}
	}
?>