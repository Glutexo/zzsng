<?php
	class Lessoner extends Doer {
        var $lesson;
		
		// Output.
		function out($void = "") {
			$tpl = new Template;
			
			try {
				// Add a new lesson.
				if(isset($_POST["add"])) {
					$this->lesson = new Lesson();
					$this->add($_POST["name"], $_POST["language"]);
				}

				// Delete an existing lesson.
				if(isset($_POST["delete"])) {
					foreach($_POST["delete"] as $k => $v) {
						$this->lesson = new Lesson($k);
						$this->delete();
					}
				}

				// Edit an existing lesson.
				if(isset($_POST["edit"])) {
					foreach($_POST["edit"] as $k => $v) {
						$this->lesson = new Lesson($k);
						if(isset($_POST["done"]))	$this->edit($_POST["name"], $_POST["language"]);
						else $tpl->reg("LESSON_TO_EDIT", array(
							"id" => $this->lesson->getId(),
							"name" => $this->lesson->getName(),
							"language" => $this->lesson->getLanguage()), true);
                        $tpl->reg("EDIT_TITLE", str_replace("{{NAME}}", $this->lesson->getName(), lang::lesson_edit_title), true);
					}
				}
				
				// Duplication of an existing lesson.
				if(isset($_POST["duplicate"])) {
					foreach($_POST["duplicate"] as $k => $v) {
						$this->lesson = new Lesson($k);
						$this->duplicate();
					}
				}
                
			} catch(Exception $e) {
				$this->error[] = lang::action_failed . ": " . $e->getMessage();
			}
            
            // Set a language filter.
            if(isset($_GET["language"])) {
                $_SESSION["language"] = $_GET["language"];
            }
            
			$languager = new Languager;
			
			$tpl->reg("LESSONS", $this->get_list(), true);
			$tpl->reg("LANGUAGES", $languager->get_list(), true);
            $tpl->reg("SECTION", $_REQUEST["section"], true);
			$tpl->reg("LANGUAGE", isset($_SESSION["language"]) ? $_SESSION["language"] : null, true);
            $tpl->reg("LANG", AdminFunctions::lang_to_array(), true);
			$tpl->load("lessons.tpl");
			$tpl->execute();
			return(parent::out($tpl->out()));
		}

		// Gets a lesson list and returns it as an associative array.
		function get_list() {
			try {
/*				if(!($out = $this->db->fetch_assocs("
					SELECT l.id,l.jmeno,IFNULL(j.jmeno,'" . lang::proxy_unknown . "') jazyk,COUNT(s.id) term_count
					FROM lekce l
					LEFT JOIN jazyky j ON l.jazyk=j.id
					LEFT JOIN slovicka s ON l.id=s.lekce
					GROUP BY l.id
					ORDER BY l.jmeno,l.jazyk"))) */
// Cleaner, but slow:
                if(!empty($_REQUEST["section"]) && $_REQUEST["section"] == "lessons" && !empty($_SESSION["language"])) {
                    $where = "WHERE " . Lesson::COL_LANGUAGE . " = " . $_SESSION["language"];
                } else $where = "";
                $query = "SELECT * FROM " . Lesson::TABLE_LESSONS . " " . $where . " ORDER BY " . Lesson::COL_NAME;
				if($out = $this->db->fetch_assocs($query)) {
					foreach($out as $k => $lesson) {
						$jazyk = new Language($lesson[Lesson::COL_LANGUAGE]);
						$out[$k]["language"] = $jazyk->getName();

//						$lekce_obj = new Lekce($lekce[Lekce::COL_ID]);
						$out[$k]["term_count"] = Lesson::getLessonTermCount($lesson[Lesson::COL_ID]);
					}  
				} else 
					$this->warning[] = lang::no_lesson_exists;
			} catch(Exception $e) {
				$this->error[] = lang::lesson_list_could_not_be_obtained . ": " . $e->getMessage;
				$out = array();
			}
			return($out);
		}

		// Deletes the lesson.
		function delete() {
			$name = $this->lesson->getName();
			try {
				$this->lesson->delete();
				$this->notice[] = str_replace("{{NAME}}", $name, lang::lesson_deleted);
				
				$out = true;
			} catch(Exception $e) {
				$this->error[] = str_replace("{{NAME}}", $name, lang::lesson_delete_error) . ": " . $e->getMessage();
				
				$out = false;
			}
			
			return($out);
		}

		// Adds a new lesson.
		function add($name, $language) {
			try {
				$name = $this->lesson->setName($name);
				$this->lesson->setLanguage($language);
				$this->lesson->save();
				$this->notice[] = str_replace("{{NAME}}", $name, lang::lesson_added);
				
				$out = true;
			}
			catch(Exception $e) {
				$this->error[] = str_replace("{{NAME}}", $name, lang::lesson_add_error) . ": " . $e->getMessage();
				
				$out = false;
			}
			
			return($out);
		}

		// Edits a lesson.
		function edit($new_name, $new_language) {
			$original_name = $this->lesson->getName();
			try {			
				$this->lesson->setName($new_name);
				$this->lesson->setLanguage($new_language);
				$this->lesson->save();
				
				$this->notice[] = str_replace("{{NAME}}", $original_name, lang::lesson_edited);
			} catch(Exception $e) {
				$this->error[] = str_replace("{{NAME}}", $original_name, lang::lesson_edit_error) . ": " . $e->getMessage();
			}
			
			return(true);
		}
		
		// Duplicates a lesson.
		function duplicate() {
			$lesson = new Lesson();
			$lesson->setName($this->lesson->getName() . lang::suffix_duplicate);
			$lesson->setLanguage($this->lesson->getLanguage());
			foreach($this->lesson->getTerms() as $term) {
				$lesson->addTerm(
					$term->getTerm(),
					$term->getTranslation(),
					$term->getMetadata());
			}

			return($lesson->save());
		}
	}
?>