<?php
	class Termer extends Doer {
	
		const DUP_LESSON_ID = 0;
		const DUP_LESSON_NAME = 1;
		const DUP_TERM_ID = 2;
		
		const TPL_DUPLICATE_FOUND = 'duplicate_found.tpl';
		
		const EDIT = true;
		const ADD = false;

        var $term;
        var $lesson;

        // Composes a page block regarding the term management.
		function out() {
			$tpl = new Template;
			
			try {
                // Sort the list.
                if(isset($_POST["sort"])) {
                    list($sort) = array_keys($_POST["sort"]);
                    $_SESSION["sort"] = $sort;
                }

				// Add a new term.
				if(isset($_POST["add"])) {
					$this->term = new Term();
					$this->add($_POST["term"], $_POST["metadata"], $_POST["translation"], $_POST["comment"], $_POST["lesson"]);
				}

				// Delete an existing term.
				if(isset($_POST["delete"])) {
					foreach($_POST["delete"] as $k => $v) {
						$this->term = new Term($k);
						$this->delete();
					}
				}

				// Edit an existing term.
				if(isset($_POST["edit"])) {
					foreach($_POST["edit"] as $k => $v) {
						$this->term = new Term($k);
					}
					if(isset($_POST["done"])) $this->edit($_POST["term"], $_POST["metadata"], $_POST["translation"], $_POST["comment"]);
					else {
                        $tpl->reg("TERM_TO_EDIT", array(
                            "id" => $this->term->getId(),
                            "term" => $this->term->getTerm(),
                            "metadata" => $this->term->getMetadata(),
                            "translation" => $this->term->getTranslation(),
                            "comment" => $this->term->getComment()), true);
                        $tpl->reg("EDIT_TITLE", str_replace("{{TERM}}", $this->term->getTerm(), lang::term_edit_title), true);
                    }
				}

				// Save term order.

				if(isset($_POST['save_order'])) {
					$order = $_POST['order'];
					$this->save_order($_POST['order']);
					$this->notice[] = lang::order_save_succeeded;
				}
			} catch(Exception $e) {
				$this->error[] = lang::action_failed . ": " . $e->getMessage();
			}

			if(isset($_POST["lesson"])) {
				$this->lesson = new Lesson($_POST["lesson"]);
				$tpl->reg("LESSON_ID", $this->lesson->getId(), true);
				$tpl->reg("LESSON_NAME", $this->lesson->getName(), true);
				$tpl->reg("TERMS", $this->get_list(), true);
                $tpl->reg("LIST_TITLE", str_replace("{{LESSON}}", $this->lesson->getName(), lang::term_list_title), true);
			}
			else $this->error[] = lang::term_list_must_have_lesson;

			$tpl->load("terms.tpl");
            $tpl->reg("LANG", AdminFunctions::lang_to_array(), true);
			$tpl->execute();
			return(parent::out($tpl->out()));
		}
		
        // Gets the term list for the given lesson and returns it as an associative array.
		function get_list() {
			$name = $this->lesson->getName();
			try {
                switch($_SESSION["sort"]) {
                    case "order":
                        $orderby = $this->db->escape_column(Term::COL_ORDER) . " ASC";
                        break;
                    case "term":
                    default:
                        $orderby = implode(",", array(Term::COL_TERM . " ASC", Term::COL_METADATA . " ASC", Term::COL_TRANSLATION . " ASC"));
                }
				$out = $this->db->fetch_assocs("
					SELECT *
					FROM " . Term::TABLE_TERMS . "
					WHERE " . $this->db->escape_column(Term::COL_LESSON) . "='" . $this->lesson->getId() . "'
					ORDER BY " . $orderby);
				if(!$out) $this->warning[] = str_replace("{{NAME}}", $name, lang::lesson_empty);
			} catch(Exception $e) {
				$this->error[] = str_replace("{{NAME}}", $name, lang::term_list_could_not_be_obtained);
				$out = array();
			}
			
			return($out);
		}
		
		// Deletes an existing term.
		function delete() {
			$term = $this->term->getTerm();
			try {
				$this->term->delete();
				$this->notice[] = str_replace("{{TERM}}", $term, lang::term_deleted);
				
				$out = true;
			} catch(Exception $e) {
				$this->error[] = str_replace("{{TERM}}", $term, lang::term_delete_error) . ": " . $e->getMessage();

				$out = false;
			}
			
			return($out);
		}

		// Edits the term. (Warning: Edit its alias “$this->add” too!)
		function edit($new_term, $new_metadata, $new_translation, $new_comment, $lesson = null, $edit = self::EDIT) {
			try {
				if($lesson) $this->term->setLesson($lesson);
				if($edit) $original_term = $this->term->getTerm();
				$new_term = $this->term->setTerm($new_term);
				$this->term->setMetadata($new_metadata);
				$this->term->setTranslation($new_translation);
				$this->term->setComment($new_comment);

				if($duplicates = $this->find_duplicates($term)) foreach($duplicates as $duplicate) {
					$tpl = new Template();
					$tpl->reg('LESSON_ID', $duplicate[self::DUP_LESSON_ID], true);
					$tpl->reg('LESSON_NAME', $duplicate[self::DUP_LESSON_NAME], true);
					$tpl->reg('TERM_ID', $duplicate[self::DUP_TERM_ID], true);
                    $tpl->reg('LANG', AdminFunctions::lang_to_array(), true);
                    $tpl->reg('MESSAGE', str_replace("{{LESSON}}", $duplicate[self::DUP_LESSON_NAME], lang::duplicate_found), true);
					$tpl->load(self::TPL_DUPLICATE_FOUND);
					$tpl->execute();
					$this->warning[] = $tpl->out();
				}

				$this->term->SAVE();
				$this->notice[] = ($edit
					? str_replace("{{TERM}}", $original_term, lang::term_edited)
					: str_replace("{{TERM}}", $new_term, lang::term_added));
				$out = true;
			} catch(Exception $e) {
				$this->error[] = ($edit
					? str_replace("{{TERM}}", $original_term, lang::term_edit_error)
					: str_replace("{{TERM}}", $new_term, lang::term_add_error)) . ": " . $e->getMessage();;
				$out = false;
			}
			
			return($out);
		}

		// Adds a new term. (Actualy just an alias to “$this->edit()”.
		function add($term, $metadata, $translation, $comment, $lesson) {
			return($this->edit($term, $metadata, $translation, $comment, $lesson, self::ADD));
		}
		
		function find_duplicates($term, $same_language = true) {
			return($this->db->fetch_rows("
				SELECT l." . $this->db->escape_column(Lesson::COL_ID) . ",l." . $this->db->escape_column(Lesson::COL_NAME) . ",s." . $this->db->escape_column(Term::COL_ID) . "
				FROM " . Term::TABLE_TERMS . " s
					JOIN " . Lesson::TABLE_LESSONS . " l ON s." . $this->db->escape_column(Term::COL_LESSON) . "=l." . $this->db->escape_column(Lesson::COL_ID) . "
				WHERE s." . $this->db->escape_column(Term::COL_TERM) . " LIKE '" . $this->db->escape($term) ."'"));
		}

		function save_order($orders) {
			// Don’t trust the received data. Only order is preserved.
			asort($orders);
			foreach(array_keys($orders) as $k => $term_id) {
				$pairs = array(Term::COL_ORDER => $k + 1);
				$this->db->update(Term::TABLE_TERMS, $term_id, $pairs);
			}
		}
	}
?>