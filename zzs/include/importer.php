<?php
	class Importer extends Doer {
		const TERM_DELIMITER = "\n";
		const COLUMN_DELIMITER = ";";
		const PSEUDODELIMITER = "#####";
		var $valid_term_mask = "/^[^#####]+#####[^#####]*#####.+$/"; // ##### is a delimiter.
				
		function __construct() {
			$this->valid_term_mask = str_replace(self::PSEUDODELIMITER, self::COLUMN_DELIMITER, $this->valid_term_mask);
		}

		// Imports data from an uploaded file.
		const IMPORT_FILE = 0;
		const IMPORT_STRING = 1;
		function import($data, $lesson, $language, $method = self::IMPORT_FILE) {
            // $lekce is an integer: Import to the given lesson, $lesson is its ID.
            // $lekce is a string: Create a new lesson, $lesson is its name.
            if(!is_int($lesson)) { $name = $lesson; $lesson = null; }

			switch($method) {
				case self::IMPORT_FILE:
					// Get the data from the file.
					if(!file_exists($data))	throw new Exception(lang::import_file_does_not_exist);
					if(is_dir($data)) throw new Exception(lang::import_file_is_dir);
					if(!$data = trim(file_get_contents($data))) throw new Exception(lang::import_file_empty_or_unreadable);
				case self::IMPORT_STRING:
					// Process the data.
					$data = explode(self::TERM_DELIMITER, $data);
                    
                    // Remove empty rows.
                    foreach($data as $key => $line) if(!trim($line)) unset($data[$key]);
                    
					if(!$this->validate($data)) throw new Exception(lang::import_data_invalid);

                    if(!$lesson && !$language) throw new Exception(lang::new_lesson_must_have_language);

					$lesson = new Lesson($lesson);
					if(isset($name)) $name = $lesson->setName($name);
                    else $name = $lesson->getName();
					if($language) $lesson->setLanguage($language);
					foreach($data as $line) {
						list($term, $metadata, $translation) = explode(self::COLUMN_DELIMITER, $line);
						$lesson->addTerm($term, $translation, $metadata);
					}
					$lesson->save();
					
					$this->notice[] = "Lekce «<span class=\"code\">$name</span>» byla úspěšně importována.";
					
					return(true);
				default:
					throw new Exception(lang::unsupported_import_method);
			}
		}
		
		// Input data validation.
		function validate($data) {
			$retval = true;
			foreach($data as $line) {
				$line = trim($line);
				if(!preg_match($this->valid_term_mask, $line)) {
					$this->notice[] = str_replace("{{LINE}}", $line, lang::invalid_line);
					$retval = false;
				}
			}
			return($retval);
		}

		// Composes the page block with an import form.
		function out($void = "") {
			if(isset($_POST["import"])) {
				try {
                    if(isset($_POST["data"])) {
                        $source = $_POST["data"];
                        $method = self::IMPORT_STRING;
                    } else {
                        $source = $_FILES["file"]["tmp_name"];
                        $method = self::IMPORT_FILE;
                    }

                    // If it’s said that a new lesson shall be created, it’s its name what matters.
                    // Otherwise it‘s an ID of the given existing lesson.
                    // If it’s not known which way to go, assume creating a new lesson.
                    if(
                        (isset($_POST["create_new_lesson"]) && $_POST["create_new_lesson"]) ||
                        !isset($_POST["create_new_lesson"])
                    ) $lesson = $_POST["name"];
                    else $lesson = intval($_POST["lesson"]);

                    $language = isset($_POST["language"]) ? $_POST["language"] : null;
                    $this->import($source, $lesson, $language, $method);
                } catch(Exception $e) {
                    $this->error[] = lang::action_failed . ": " . $e->getMessage();
                }
			}
			
            
			$languager = new Languager;
            $lessoner = new Lessoner;
			
			$tpl = new Template;
			$tpl->reg("LANGUAGES", $languager->get_list(), true);
			$tpl->reg("LESSONS", $lessoner->get_list(), true);
            $tpl->reg("LANG", AdminFunctions::lang_to_array(), true);
			$tpl->load($_GET["section"].".tpl");
			$tpl->execute();

			return(parent::out($tpl->out()));
		}
		
	}
?>