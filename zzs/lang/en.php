<?php
class lang {
    // Configuration.
    const lang = "en";
    const project_name = "Vocabulary exam";
    const master_error = "The application can’t run!";
    const reason = "Reason";

    // General errors.
    const section_does_not_exist = "Section does not exist. (But it should!)";
    const action_failed = "Action failed";

    // Examination errors.
    const no_terms_in_lesson = "Lesson does not contain any terms.";

    // Examination.
    const statistics_template = "In the first cycle, you {{HITS}} from the total sum of {{SUM}} {{TERMS}}. That makes rate of success {{PERCENT}}%.";
    const no_hits = "didn’t know any term";
    const hits = "knew {{HITS}} {{TERMS}}";
    const term_genitive_singular = "term";
    const term_genitive_plural = "terms";
    const term_accusative_singular = "term";
    const term_accusative_plural = "terms";
    const examination = "Examination";
    const exam_lesson = "Lesson to be tested.";
    const invert = "Base exam on translations";
    const order = "Order";
    const random = "Random";
    const sequence = "Sequence";
    const action = "Action";
    const start_exam = "Start exam";
    const named_lesson = "lesson <span class=\"code\">«{{NAME}}»</span>";
    const more_lessons = "more lessons";
    const exam_title = "Exam from {{LESSON}}";
    const term = "Term";
    const metadata = "Metadata";
    const translation = "Translation";
    const comment = "Comment";
    const knew = "I knew it";
    const did_not_know = "I didn’t know it";
    const reveal = "Reveal";

    // Import errors.
    const import_file_does_not_exist = "Imported file does not exist.";
    const import_file_is_dir = "Imported file is a folder.";
    const import_file_empty_or_unreadable = "Imported file is empty or unreadable.";
    const import_data_invalid = "Imported data is invalid.";
    const new_lesson_must_have_language = "New lesson must have its language set.";
    const unsupported_import_method = "Nepodporovaný způsob importování dat.";
    const invalid_line = "Following line was marked as invalid «<span class=\"code\">{{LINE}}</span>».";
    const lesson_create_error = "Lesson creation failed.";

    // Import.
    const bulk_title = "Bulk import terms in CSV format";
    const create_new_lesson = "Create new lesson";
    const insert_to_existing_lesson = "Insert to an existing lesson";
    const new_lesson_name = "New lesson name";
    const existing_lesson = "Existing lesson";
    const language = "Language";
    const data = "Data";
    const start_import = "Import";
    const show = "Show";
    const duplicate_found = "Duplicate found in lesson «<span class=\"code\">{{LESSON}}</span>»!";
    const import_title = "Import terms from a CSV file";
    const lesson_name = "Lesson name";
    const import_file = "Import file";

    // Language errors.
    const language_must_have_name = "Language must have a name.";
    const language_must_have_id = "Language can’t be deleted without its ID.";
    const language_undeletable = "Language is undeletable.";
    const language_name_cant_be_empty = "Name can’t be empty.";
    const language_uneditable = "Language is uneditable.";
    const language_not_added = "Error adding language «<span style=\"code\">{{NAME}}</span>»";
    const no_language_exists = "No language exists.";
    const language_list_could_not_be_obtained = "Language list could not be obtained";
    const language_delete_error = "Language «<span class=\"code\">{{NAME}}</span>» could not be deleted";
    const language_edit_error = "Language «<span class=\"code\">{{NAME}}</span>» could not be edited";
    const language_set_as_default_error = "Language «<span class=\"code\">{{NAME}}</span>» could not be set as default.";

    // Language.
    const language_added = "Language «<span class=\"code\">{{NAME}}</span>» added.";
    const language_deleted = "Language «<span class=\"code\">{{NAME}}</span>» deleted.";
    const language_edited = "Language «<span class=\"code\">{{NAME}}</span>» edited.";
    const language_set_as_default = "Language «<span class=\"code\">{{NAME}}</span>» set as default.";
    const add_language = "Add a new language";
    const name = "Name";
    const add = "Add";
    const language_edit_title = "Edit language «<span class=\"code\">{{NAME}}</span>»";
    const edit = "Edit";
    const language_list = "Language list";
    const lesson_count = "Lesson count";
    const default_language = "default";
    const delete = "Delete";
    const set_as_default = "Set as default";

    // Lesson errors.
    const lesson_name_cant_be_empty = "Jméno lekce nemůže být prázdné.";
    const term_must_be_term_object = "Term object must be of type Term.";
    const lesson_must_have_name = "Lesson must have a name.";
    const lesson_must_have_language = "Lesson must have a language";
    const lesson_must_have_terms = "Lesson must contain some terms.";
    const lesson_must_have_id = "Lesson can’t be deletet without its ID.";
    const no_lesson_exists = "No lesson exists.";
    const lesson_list_could_not_be_obtained = "Lesson list couldn’t be obtained";
    const lesson_delete_error = "Lesson «<span class=\"code\">{{NAME}}</span>» could not be deleted.";
    const lesson_add_error = "Lesson «<span style=\"code\">{{NAME}}</span>» could not be added";
    const lesson_edit_error = "Lessons «<span class=\"code\">{{NAME}}</span>» could not be edited";
    const language_must_exist_for_lesson_add = "At least one language must exists for adding a new lesson.";

    // Lesson.
    const suffix_duplicate = " (duplicate)";
    const proxy_unknown = "(unknown)";
    const lesson_deleted = "Lesson «<span class=\"code\">{{NAME}}</span>» deleted.";
    const lesson_added = "Lesson «<span class=\"code\">{{NAME}}</span>» added.";
    const lesson_edited = "Lesson «<span class=\"code\">{{NAME}}</span>» edited.";
    const add_lesson = "Add a new lesson";
    const lesson_edit_title = "Edit lesson «<span class=\"code\">{{NAME}}</span>»";
    const term_count = "Term count";
    const duplicate = "Duplicate";
    const terms = "Terms";

    // Term errors.
    const term_must_have_lesson = "Lesson can’t be set to no value.";
    const term_must_have_term = "Term must have a term.";
    const term_must_have_translation = "Term must have a translation.";
    const term_must_have_id = "Term can’t be deleted without its ID.";
    const term_list_must_have_lesson = "Term list can’t be obtained without specifying a lesson.";
    const lesson_empty = "Lesson «<span class=\"code\">{{NAME}}</span>» doesn’t contain any terms.";
    const term_list_could_not_be_obtained = "Term list for lesson «<span class=\"code\">{{NAME}}</span>» could not be obtained.";
    const term_delete_error = "Term «<span class=\"code\">{{TERM}}</span>» could not be deleted";
    const term_edit_error = "Term «<span class=\"code\">{{TERM}} </span>» could not be edited";
    const term_add_error = "Term «<span style=\"code\">{{TERM}}</span>» could not be added";

    // Term.
    const term_deleted = "Term «<span class=\"code\">{{TERM}}</span>» deleted.";
    const term_edited = "Term «<span class=\"code\">{{TERM}}</span>» edited.";
    const term_added = "Term «<span class=\"code\">{{TERM}}</span>» added.";
    const add_term = "Add term";
    const term_edit_title = "Edit term «<span class=\"code\">{{TERM}}</span>»";
    const term_list_title = "Term list for lesson «<span class=\"code\">{{LESSON}}</span>»";
    const sort_by_order = "Sort by order";
    const sort_by_term = "Sort alphabetically";
	const order_save_succeeded = "Term order saved.";

    // User interface.
    const exam = "Exam";
    const import = "Import";
    const bulk = "Bulk";
    const languages = "Languages";
    const lessons = "Lessons";

    // Setup.
    const structure_import_success = "Database structure import successful.";
    const structure_import_error = "Database structure import failed";

	// Login.
	const user_id_not_provided = "User ID not provided.";
	const invalid_user_id = "Invalid user ID.";

	// Db.
	const NOT_SUPPORTED_ON_MYSQL = "Not supported on MySQL database.";
}
?>