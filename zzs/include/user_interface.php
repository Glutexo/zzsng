<?php
	class UserInterface {
		var $xmlhead = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>"; // "
		var $links = array(
				array(
					"text" => lang::exam,
					"href" => "index.php?section=exam"),
				array(
					"text" => lang::import,
					"href" => "index.php?section=import"),
				array(
					"text" => lang::bulk,
					"href" => "index.php?section=bulk"),
				array(
					"text" => lang::languages,
					"href" => "index.php?section=languages"),
				array(
					"text" => lang::lessons,
					"href" => "index.php?section=lessons")
        );

		/* Inserts header. */
		function out_head() {
			$out = $this->xmlhead;

			$login_obj = new Login;
			$active_user_login = $login_obj->get_active_user_login();

			$tpl = new Template;
			$tpl->reg("PROJECT_NAME", lang::project_name, true);
			$tpl->reg("TEMPLATE_DIR", config::APPLICATION . config::TEMPLATE_DIR, true);
			$tpl->reg("LOGIN", $active_user_login, true);
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