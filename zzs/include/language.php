<?php
	class Language {
		const TABLE_LANGUAGES = "languages";
		const COL_ID = "id";
		const COL_NAME = "name";
		const COL_DEFAULT = "default";
		const COL_CREATED = "created";
		const COL_LAST_CHANGE = "last_change";
		
		const ID_NONE = "0";
		
		var $name = "";
		var $default = false;
		
		function __construct($id = null) {
			$this->db = new Db; // Database connection.

			if($id !== null) { // If the language already exists, get its information from the database.
				$tabledata = $this->db->select(self::TABLE_LANGUAGES, $id)->fetch_assoc();
				$this->name = $tabledata[self::COL_NAME];
				$this->default = $tabledata[self::COL_DEFAULT];
			}
			$this->id = $id;
		}
		
		// Saves the language to the database (either as a new record or edits the existing one).
		function save() {
			if(!$this->name) throw new Exception(lang::language_must_have_name);
			if($this->id !== null) { // Edit.
				if($this->id == self::ID_NONE) $this->db->update(self::TABLE_LANGUAGES, $this->id, array( // Name can’t be edited.
					self::COL_DEFAULT => (string) (int) $this->default,
					self::COL_LAST_CHANGE => AdminFunctions::dbtime()));
				else $this->db->update(self::TABLE_LANGUAGES, $this->id, array(
					self::COL_NAME => $this->name,
					self::COL_DEFAULT => (string) (int) $this->default,
					self::COL_LAST_CHANGE => AdminFunctions::dbtime()));
			} else { // Add.
				$time = AdminFunctions::dbtime();
				$this->db->insert(self::TABLE_LANGUAGES, array(
					self::COL_NAME => $this->name,
					self::COL_DEFAULT => (string) (int) $this->default,
					self::COL_CREATED => $time,
					self::COL_LAST_CHANGE => $time));
			}
			
			return (true);
		}
		
		// Deletes the language from the database.
		function delete() {
			if($this->id === null) throw new Exception(lang::language_must_have_id);
			elseif($this->id == self::ID_NONE) throw new Exception(lang::language_undeletable); // The “no language” can’t be deleted.
			$this->db->delete(self::TABLE_LANGUAGES, $this->id);
			
			// Set the “no language” to the lessons that were assigned to this language.
			$res = $this->db->select_where(Lesson::TABLE_LESSONS, Lesson::COL_LANGUAGE . "='" . $this->id . "'", Lesson::COL_ID);
			while($row = $res->fetch_assoc()) {
				$lesson = new Lesson($row[Lesson::COL_ID]);
				$lesson->setLanguage(self::ID_NONE);
				$lesson->save();
			}
			
			// Set “no language” as default if this was the default one.
			if($this->default) {
				$language = new Language(self::ID_NONE);
				$language->setDefault(true);
				$language->save();
			}
			
			return(true);
		}
		
		// Gets and returns an ID of the language.
		function getId() {
			return($this->id);
		}


        // Gets and returns a name of the language.
		function getName() {
			return($this->name);
		}
		
		// Sets a name of the language.
		function setName($name) {
			if(!($name = trim($name))) throw new Exception(lang::language_name_cant_be_empty);
			return($this->name = stripslashes($name));
		}
		
		// Gets and returns a defaultness of the language.
		function getDefault() {
			return($this->default);
		}

		// Sets the defaultness of the language.
		function setDefault($výchozí) {
			return($this->default = (bool) $výchozí);
		}
		
		// Gets and returns a count of lessons assigned to the language.
		function getLessonCount() {
			if($this->id === null) return(0); // New language has obviously no lessons assigned.
			$res = $this->db->select_where(Lesson::TABLE_LESSONS, Lesson::COL_LANGUAGE . "='" . $this->id . "'", "COUNT(*) count")->fetch_assoc();
			return((int) $res["count"]);
		}
		
	}
?>