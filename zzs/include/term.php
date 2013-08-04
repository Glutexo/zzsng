<?php
	class Term {
		const TABLE_TERMS = "terms";
		const COL_ID = "id";
        const COL_ORDER = "order";
		const COL_LESSON = "lesson";
		const COL_TERM = "term";
		const COL_METADATA = "metadata";
		const COL_TRANSLATION = "translation";
		const COL_COMMENT = "comment";
		const COL_CREATED = "created";
		const COL_LAST_CHANGE = "last_change";
		
		var $lesson = null;
		var $term = "";
		var $metadata = "";
		var $translation = "";
		var $comment = "";
		
		function __construct($id = null) {
			$this->db = new Db;
			
			if($id) {
				$tabledata = $this->db->select(self::TABLE_TERMS, $id)->fetch_assoc();
				$this->lesson = $tabledata[self::COL_LESSON];
				$this->term = $tabledata[self::COL_TERM];
				$this->metadata = $tabledata[self::COL_METADATA];
				$this->translation = $tabledata[self::COL_TRANSLATION];
				$this->comment = $tabledata[self::COL_COMMENT];
			}
			$this->id = $id;
		}
		
		// Gets and returns an ID of the term.
		function getId() {
			return($this->id);
		}
		
		// Gets and returns a lesson of the term.
		function getLesson() {
			return($this->lesson);
		}
		
		// Sests a lesson of the term.
		function setLesson($lesson) {
			if(!$lesson) throw new Exception(lang::term_must_have_lesson);
			return($this->lesson = (int) $lesson);
		}
		
		// Gets and returns a term of the term.
		function getTerm() {
			return($this->term);
		}
		
		// Sets a term of the term.
		function setTerm($term) {
			if(!($term = trim($term))) throw new Exception(lang::term_must_have_term);
			return($this->term = stripslashes($term));
		}
		
		// Gets and returns metadata of the term.
		function getMetadata() {
			return($this->metadata);
		}

		// Sets metadata of the term.
		function setMetadata($metadata) {
			return($this->metadata = stripslashes(trim($metadata)));
		}
		
		// Gets and returns a translation of the term.
		function getTranslation() {
			return($this->translation);
		}
		
		// Sets a translation of the term.
		function setTranslation($translation) {
			if(($translation = trim($translation)) === "" || $translation === null) throw new Exception(lang::term_must_have_translation);
			return($this->translation = stripslashes($translation));
		}
		
		// Gets and returns a comment of the term.
		function getComment() {
			return($this->comment);
		}

		// Sets a comment of the term.
		function setComment($comment) {
			return($this->comment = stripslashes(trim($comment)));
		}

		// Saves the term to the database. Either as a new record or as an existing one.
		function save() {
			if(!$this->lesson) throw new Exception(lang::term_must_have_lesson);
			if($this->term === "" || $this->term === null) throw new Exception(lang::term_must_have_term);
			if($this->translation === "" || $this->translation === null) throw new Exception(lang::term_must_have_translation);

			if($this->id) // Edit.
				$this->db->update(self::TABLE_TERMS, $this->id, array(
					self::COL_TERM => $this->term,
					self::COL_METADATA => $this->metadata,
					self::COL_TRANSLATION => $this->translation,
					self::COL_COMMENT => $this->comment,
					self::COL_LAST_CHANGE => AdminFunctions::dbtime()));
			else { // Add.
                $max = $this->db->select_where(self::TABLE_TERMS, self::COL_LESSON . " = " . $this->lesson, "MAX(" . $this->db->escape_column(self::COL_ORDER) . ")")->fetch_single_field();

				$time = AdminFunctions::dbtime();
				$this->db->insert(self::TABLE_TERMS, array(
					self::COL_LESSON => $this->lesson,
					self::COL_TERM => $this->term,
					self::COL_METADATA => $this->metadata,
					self::COL_TRANSLATION => $this->translation,
					self::COL_COMMENT => $this->comment,
					self::COL_CREATED => $time,
					self::COL_LAST_CHANGE => $time,
                    self::COL_ORDER => $max + 1
                ));
			}
				
			return(true);
		}
		
		// Deletes the term from the database.
		function delete() {
			if(!$this->id) throw new Exception(lang::term_must_have_id);
			$this->db->delete(self::TABLE_TERMS, $this->id);
			
			return(true);
		}
	}
?>