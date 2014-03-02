<?php
	class Lesson {
		const TABLE_LESSONS = "lessons";
		const COL_ID = "id";
		const COL_NAME = "name";
		const COL_LANGUAGE = "language";
		const COL_CREATED = "created";
		const COL_LAST_CHANGE = "last_change";
		const COL_USER_ID = "user_id";

        var $id = null;
		var $name = "";
		var $language = null;
        var $user_id = null;
		var $terms = array();
		
		function __construct($id = null) {
			$this->db = new Db;
			
			if($id) {
				$tabledata = $this->db->select(self::TABLE_LESSONS, $id)->fetch_assoc();
				$this->name = $tabledata[self::COL_NAME];
				$this->language = $tabledata[self::COL_LANGUAGE];
                $this->user_id = $tabledata[self::COL_USER_ID];
				
				$res = $this->db->select_where(Term::TABLE_TERMS, Term::COL_LESSON . "='" . $id . "'", Term::COL_ID, $this->db->escape_column(Term::COL_ORDER));
				while($row = $res->fetch_assoc())
					$this->terms[] = new Term($row[Term::COL_ID]);
			}
			$this->id = $id;
		}
		
		// Gets and returns an ID of the lesson.
		function getId() {
			return($this->id);
		}
		
		// Gets and returns a name of the lesson.
		function getName() {
			return($this->name);
		}
		
		// Sets a name of the lesson.
		function setName($name) {
			if(!($name = trim($name))) throw new Exception(lang::lesson_name_cant_be_empty);
			else return($this->name = stripslashes($name));
		}
		
		// Gets and returns a language of the lesson.
		function getLanguage() {
			return($this->language);
		}
		
		// Sets a language of the lesson.
		function setLanguage($language) {
			return($this->language = (int) $language);
		}
		
		// Adds a term to the lesson.
		function addTerm($term, $translation = "", $metadata = "") {
			$retval = false;
			
			if(is_object($term)) {
				if(get_class($term) == "Term") $retval = $term;
				else throw new Exception(lang::term_must_be_term_object);
			}
			else {
				$term_obj = new Term;
				$term_obj->setTerm($term);
				$term_obj->setMetadata($metadata);
				$term_obj->setTranslation($translation);
				$retval = $term_obj;
			}
			
			return($this->terms[] = $retval);
		}
		
		// Gets and returns terms in the lesson.
		function getTerms() {
			return($this->terms);
		}
		
		// Saves the lesson and its terms to the database.
		function save() {
			if(!$this->name) throw new Exception(lang::lesson_must_have_language);
			if($this->language === null) throw new Exception(lang::lesson_must_have_language);
//			if(!$this->terms) throw new Exception(lang::lesson_must_have_terms);
			
			if($this->id) { // Edit.
				$this->db->update(self::TABLE_LESSONS, $this->id, array(
					self::COL_NAME => $this->name,
					self::COL_LANGUAGE => $this->language,
					self::COL_LAST_CHANGE => AdminFunctions::dbtime()));
			}
			else { // Add.
				$time = AdminFunctions::dbtime();
				$login = new Login;
				$this->db->insert(self::TABLE_LESSONS, array(
					self::COL_USER_ID => $login->get_active_user_id(),
					self::COL_NAME => $this->name,
					self::COL_LANGUAGE => $this->language,
					self::COL_CREATED => $time,
					self::COL_LAST_CHANGE => $time));
				$this->id = $this->db->select_where(self::TABLE_LESSONS, self::COL_CREATED . "='$time'", self::COL_ID)->fetch_object()->id; // Get back the new lesson’s ID by its creation time.
			}

            // Add all terms that have no lesson assigned to the lesson.
            foreach($this->terms as $term) {
                if($term->getLesson()) continue;

                $term->setLesson($this->id);
                $term->save();
            }

			return(true);
		}
		
		// Deletes the lesson and its terms from the database.
		function delete() {
			if(!$this->id) throw new Exception(lang::lesson_must_have_id);
			$this->db->delete(self::TABLE_LESSONS, $this->id);
			
			foreach($this->terms as $term) {
				$term->delete();
			}
			
			return(true);
		} 

		// Get and returns a number of terms contained in the lesson.
		function getTermCount() {
			return(count($this->terms));
		}
		
		static function getLessonTermCount($id) {
			$db = new Db;
			return($db->query("SELECT COUNT(*) FROM " . Term::TABLE_TERMS . " WHERE " . $db->escape_column(Term::COL_LESSON) . "=" . (int) $id)->fetch_single_field());
		}

        public function getUserId() {
            return $this->user_id;
        }
	}
?>