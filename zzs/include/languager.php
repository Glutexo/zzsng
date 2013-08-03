<?php
	class Languager extends Doer {
        var $language;

		// Composes a page block regarding to the language management.
		function out() {
			$tpl = new Template;

			try {
				// Add a new language.
				if(isset($_POST["add"])) {
					$this->language = new Language();
					$this->add($_POST["name"]);
				}
				
				// Delete an existing language.
				if(isset($_POST["delete"])) {
					foreach($_POST["delete"] as $k => $v) {
						$this->language = new Language($k);
						$this->delete();
					}
				}
				
				// Edit an existing language.
				if(isset($_POST["edit"])) {
					foreach($_POST["edit"] as $k => $v) {
						$this->language = new Language($k);
					}
					if(isset($_POST["done"]))	$this->edit($_POST["done"]);
					elseif(!$this->language->getId()) $this->error[] = lang::language_uneditable;
					else {
                        $tpl->reg("LANGUAGE_TO_EDIT", array(
                            "id" => $this->language->getId(),
                            "name" => $this->language->getName()
                        ), true);
                        $tpl->reg("EDIT_TITLE", str_replace("{{NAME}}", $this->language->getName(), lang::language_edit_title), true);
                    }
				}
				
				// Set the language as default.
				if(isset($_POST["set_default"])) {
					foreach($_POST["set_default"] as $k => $v) {
						$this->language = new Language($k);
						$this->set_default();
					}
				}
			} catch(Exception $e) {
				$this->error[] = lang::action_failed . ": " . $e->getMessage();
			}

			$tpl->reg("LANGUAGES", $this->get_list(), true);
            $tpl->reg("LANG", AdminFunctions::lang_to_array(), true);
			$tpl->load("languages.tpl");
			$tpl->execute();
			
			return(parent::out($tpl->out()));
		}
		
		// Adds a given language to the language table.
		function add($name) {
			try {
				$name = $this->language->setName($name);
				$this->language->save();
				$this->notice[] = str_replace("{{NAME}}", $name, lang::language_added);
				
				$out = true;
			}
			catch(Exception $e) {
				$this->error[] = str_replace("", $name, lang::language_not_added) . ": " . $e->getMessage();
				
				$out = false;
			}
			
			return($out);
		}
		
		// Gets the language table.
		function get_list() {
			try {
				$out = $this->db->fetch_assocs("SELECT * FROM " . Language::TABLE_LANGUAGES . " ORDER BY " . Language::COL_NAME);
				if(!$out) $this->warning[] = lang::no_language_exists;
				
				foreach($out as $k => $language) {
					$language_obj = new Language($language[Language::COL_ID]);
					$out[$k]["lesson_count"] = $language_obj->getLessonCount();
				}
			} catch(Exception $e) {
				$this->error[] = lang::language_list_could_not_be_obtained . ": " . $e->getMessage();
				$out = array();
			}
			
			return($out);
		}
		
		// Deletes the language from the table.
		function delete() {
			if(!$this->language->getId()) {
				$this->error[] = lang::language_undeletable;
				return(false);
			}

			$name = $this->language->getName();
			try {
				$this->language->delete();
				$this->notice[] = str_replace("{{NAME}}", $name, lang::language_deleted);
				
				$out = true;
			} catch(Exception $e) {
				$this->error[] = str_replace("{{NAME}}", $name, lang::language_delete_error) . ": " . $e->getMessage();
				
				$out = false;
			}

			return($out);
		}
		
		// Edits the language.
		function edit($new_name) {
			if(!$this->language->getId()) {
				$this->error[] = lang::language_uneditable;
				return(false);
			}
			
			$original_name = $this->language->getName();
			try {			
				$this->language->setName($new_name);
				$this->language->save();
				
				$this->notice[] = str_replace("{{NAME}}", $original_name, lang::language_edited);
			} catch(Exception $e) {
				$this->error[] = str_replace("{{NAME}}", $original_name, lang::language_edit_error) . ": " . $e->getMessage();
			}
			
			return(true);
		}
		
		// Sets the language as a default and removes this flag from the current default.
		function set_default() {
			$name = $this->language->getName();
			try {
				$original = $this->db->select_where(Language::TABLE_LANGUAGES, "`" . Language::COL_DEFAULT . "`" . "='1'", Language::COL_ID)->fetch_assoc();
				if(is_array($original)) { // To prevent failures when no languages present.
					$original_obj = new Language($original[Language::COL_ID]);
					$original_obj->setDefault(false);
					$original_obj->save();
				}
				
				$this->language->setDefault(true);
				$this->language->save();
				
				$this->notice[] = str_replace("{{NAME}}", $name, lang::language_set_as_default);
			} catch(Exception $e) {
				$this->error[] = str_replace("{{NAME}}", $name, lang::language_set_as_default_error) . ": " . $e->getMessage();
			}
			
			return(true);
		}
	}
?>