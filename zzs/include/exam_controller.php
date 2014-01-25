<?php
	class ExamController extends Doer {
		const TABLE_EXAM_TERMS = "exam_terms";
		const TABLE_EXAM_MISTAKES = "exam_mistakes";
		const TABLE_EXAM_RESULTS = "exam_results";
		const COL_TERM = "term";
    const COL_ORDER = "order";
		const COL_CYCLE = "cycle";
		const COL_HITS = "hits";
		const COL_MISTAKES = "mistakes";
		
		var $cycle = 0;
		
		function __construct() {
			parent::__construct();
			$row = $this->db->select_where(self::TABLE_EXAM_RESULTS, null, "MAX(" . $this->db->escape_column(self::COL_CYCLE) . ") " . $this->db->escape_column(self::COL_CYCLE))->fetch_assoc();
			if($row[self::COL_CYCLE]) $this->cycle = $row[self::COL_CYCLE];
		}
		
		// Composes a page block with a lesson selection form for the exam beginning.
		function out($void = "") {
			$tpl = new Template;
			
			try {				
				// Exposing a new word.
				if(isset($_POST["next"]) || isset($_POST["run"])) {
					if(isset($_POST["run"])) {
						$this->fill($_POST["lesson"], $_POST["random"] == "1"); // Start an exam.
					}
					
					if(!empty($_POST["next"])) {
						foreach($_POST["next"] as $k => $v) {
							$next = $k;
						}
					} else $next = "";
					
					switch($next) {
						case "mistake": $this->mistake($_POST["term"]); break;
						case "hit": $this->hit(); break;
					}

					if($term = $this->pick()) {
					  $term_tpl_map = array(
              "id" => $term->getId()
            );
            
            // If “invert” is set, use translation as a term and vice versa.
            if(empty($_POST["invert"])) {
              $term_tpl_map["term"] = $term->getTerm(); 
            } else {
              $term_tpl_map["term"] = $term->getTranslation(); 
            }
            
						$tpl->reg("TERM", $term_tpl_map, true);
          } else $this->stats();
				}
				
				// Reveal the complete term (with an answer).
				if(isset($_POST["reveal"])) {
					$term = new Term($_POST["term"]);
          
          $term_tpl_map = array(
            "metadata" => $term->getMetadata(),
            "comment" => $term->getComment(),
            "id" => $term->getId()
          );

          // If “invert” is set, use translation as a term and vice versa.
          if($_POST["invert"] != "1") {
            $term_tpl_map["term"] = $term->getTerm(); 
            $term_tpl_map["translation"] = $term->getTranslation();
          } else {
            $term_tpl_map["term"] = $term->getTranslation(); 
            $term_tpl_map["translation"] = $term->getTerm();
          }
          
					$tpl->reg("TERM", $term_tpl_map, true);
					$tpl->reg("REVEAL", true, true);
				}
				
				// Get a name and ID of the lesson.
				if(isset($_POST["lesson"])) {
					foreach((array) $_POST["lesson"] as $v) {
						$lesson[] = new Lesson($v);
					}
					if(count($_POST["lesson"]) == 1) {
						$tpl->reg("LESSON_A", array(
							"name" => $lesson[0]->getName(),
							"id" => $lesson[0]->getId()), true);
                        $lesson_name = str_replace("{{NAME}}", $lesson[0]->getName(), lang::named_lesson);
                    } else $lesson_name = lang::more_lessons;
				}
			} catch(Exception $e) {
				$this->error[] = lang::action_failed . ": " . $e->getMessage();
			}
			
			$lessoner = new LessonsController;
			if(!isset($term) || !$term) $tpl->reg("LESSONS", $lessoner->get_list(), true); // No need to load the list if it wouldn’t be displayed.
            $tpl->reg("LANG", AdminFunctions::lang_to_array(), true);
            if(isset($lesson_name)) {
				$tpl->reg("TITLE", str_replace("{{LESSON}}", $lesson_name, lang::exam_title), true);
			}
			if(array_key_exists("invert", $_POST)) {
				$tpl->reg("INVERT", $_POST["invert"], true);
			}
			$tpl->load("exam.tpl");
			$tpl->execute();
			return(parent::out($tpl->out()));
		}
		
		// Fills the table with terms of a given lesson.
		function fill($lesson, $random = true, $invert = false) {
			// Remove data from the last exam in case there are some left.
			$this->db->truncate(self::TABLE_EXAM_TERMS);
			$this->db->truncate(self::TABLE_EXAM_MISTAKES);
			$this->db->truncate(self::TABLE_EXAM_RESULTS);
			
			if(!is_array($lesson)) $lesson = array($lesson);
			
			foreach($lesson as $l) {
				$lesson_obj = new Lesson($l);
			
				if($terms = $lesson_obj->getTerms()) {
                    // If the terms shall be random, shuffle them:
                    // Each time pick a random item and remove it from the source array.
                    // Repeating until nothing is left.
                    if($random) {
                        $shuffled_terms = array();
                        while($terms) {
                            $random_key = rand(0, count($terms) - 1);
                            $shuffled_terms[] = $terms[$random_key];
                            unset($terms[$random_key]);
                            $terms = array_values($terms);
                        }
                        $terms = $shuffled_terms;
                    }

                    $i = 1;
					foreach($terms as $term) {
						$this->db->insert(self::TABLE_EXAM_TERMS, array(
                            self::COL_TERM => $term->getId(),
                            self::COL_ORDER => $i++
                        ));
					}
				} else throw new Exception(lang::no_terms_in_lesson);
			}
			
			$this->db->insert(self::TABLE_EXAM_RESULTS, array(self::COL_CYCLE => $this->cycle));

			return(true);
		}
		
		// Pick a term from the exam term list.
		function pick($moved = false) {
			$retval = false;
			
			$row = $this->db->select_where(self::TABLE_EXAM_TERMS, null, "COUNT(*) " . $this->db->escape("count"))->fetch_assoc();
			if($row["count"]) {
				$row = $this->db->select_where(self::TABLE_EXAM_TERMS, "TRUE", "*", $this->db->escape_column(self::COL_ORDER), "1")->fetch_assoc();
				$this->db->delete_where(self::TABLE_EXAM_TERMS, $this->db->escape_column(self::COL_TERM) . "='" . $row[self::COL_TERM] . "'");
				$retval = new Term($row[self::COL_TERM]);
			}
			elseif(!$moved) { // If the mistakes are already moved, there is no need to make an infinite loop.
				if($mistakes = $this->db->select_where(self::TABLE_EXAM_MISTAKES,"TRUE","*",$this->db->escape_column(self::COL_ORDER))->fetch_assocs()) {
                    $i = 1;
					foreach($mistakes as $row) {
						$this->db->insert(self::TABLE_EXAM_TERMS, array(
                            self::COL_TERM => $row[self::COL_TERM],
                            self::COL_ORDER => $i++
                        ));
					}
					$this->db->truncate(self::TABLE_EXAM_MISTAKES);
					$this->db->insert(self::TABLE_EXAM_RESULTS, array(self::COL_CYCLE => ++$this->cycle));
					return($this->pick(true)); // Recursion: Now this function shall pick something. And if not, it won’t make it here again.
				}
			}
						
			return($retval);
		}
		
		// Saves the term to the mistake list and decrements the appropriate value in the cycle information.
		function mistake($term) {
            $max = $this->db->select_where(self::TABLE_EXAM_MISTAKES, "TRUE", "MAX(" . $this->db->escape_column(self::COL_ORDER) . ")")->fetch_single_field();
			$this->db->insert(self::TABLE_EXAM_MISTAKES, array(
                self::COL_TERM => $term,
                self::COL_ORDER => $max + 1
            ));
			
			$row = $this->db->select_where(self::TABLE_EXAM_RESULTS, $this->db->escape_column(self::COL_CYCLE) . "=" . $this->cycle, self::COL_MISTAKES)->fetch_assoc();
			$this->db->update_where(self::TABLE_EXAM_RESULTS, $this->db->escape_column(self::COL_CYCLE) . "=" . $this->cycle, array(self::COL_MISTAKES => ++$row[self::COL_MISTAKES]));

			return(true);
		}
				
		// Increments the appropriate value in the cycle information.
		function hit() {
			$row = $this->db->select_where(self::TABLE_EXAM_RESULTS, $this->db->escape_column(self::COL_CYCLE) . "=" . $this->cycle, self::COL_HITS)->fetch_assoc();
			$this->db->update_where(self::TABLE_EXAM_RESULTS, $this->db->escape_column(self::COL_CYCLE) . "=" . $this->cycle, array(self::COL_HITS => ++$row[self::COL_HITS]));

			return(true);
		}
		
		// Puts some brief statistics to the notices.
		function stats() {
			$row = $this->db->select_where(self::TABLE_EXAM_RESULTS, $this->db->escape_column(self::COL_CYCLE) . "=(SELECT MIN(" . $this->db->escape_column(self::COL_CYCLE) . ") FROM " . self::TABLE_EXAM_RESULTS . ")", array(self::COL_HITS, self::COL_MISTAKES))->fetch_assoc();
			$sum = $row[self::COL_HITS] + $row[self::COL_MISTAKES];
			
			if($row[self::COL_HITS] == 0) $hits = lang::no_hits;
			else {
				$terms = $this->decline_term($row[self::COL_HITS]);
				
				$hits = strtr(lang::hits, array(
                    "{{HITS}}" => $row[self::COL_HITS],
                    "{{TERMS}}" => $terms
                ));
			}
            $this->notice[] = strtr(lang::statistics_template, array(
                "{{HITS}}" => $hits,
                "{{SUM}}" => $sum,
                "{{TERMS}}" => $this->decline_term($sum, true),
                "{{PERCENT}}" => strtr(round($row[self::COL_HITS] / $sum * 100, 1), ".", ",")
            ));
			
			return(true);
		}
		
        // Declines the word “slovíčko” (term) according the the number and the grammar case needed.
		function decline_term($count, $genitive = false) {
			if($genitive) {
				if($count == 1) $terms = lang::term_genitive_singular;
				else $terms = lang::term_genitive_plural;
			}
			else {
				if($count == 1) $terms = lang::term_accusative_singular;
				elseif($count >= 2 && $count <= 4) $terms = lang::term_accusative_plural;
				else $terms = lang::term_genitive_plural;
			}
			
			return($terms);
		}
	}
?>