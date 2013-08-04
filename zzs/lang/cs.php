<?php
class lang {
    // Configuration.
    const lang = "cs";
    const project_name = "Zkoušení ze slovíček";

    // General errors.
    const section_does_not_exist = "Tato sekce neexistuje. (Ale měla by!)";
    const action_failed = "Akce selhala";
    const master_error = "Program se nepodařilo spustit!";
    const reason = "Důvod";

    // Examination errors.
    const no_terms_in_lesson = "Zvolená lekce neobsahuje žádná slovíčka.";

    // Examination.
    const statistics_template = "Při prvním cyklu jste {{HITS}} z celkového počtu {{SUM}} {{TERMS}}, což je {{PERCENT}}% úspěšnost.";
    const no_hits = "nevěděli žádné slovíčko";
    const hits = "věděli {{HITS}} {{TERMS}}";
    const term_genitive_singular = "slovíčka";
    const term_genitive_plural = "slovíček";
    const term_accusative_singular = "slovíčko";
    const term_accusative_plural = "slovíček";
    const examination = "Zkoušení";
    const exam_lesson = "Lekce ke zkoušení";
    const order = "Pořadí";
    const random = "Náhodně";
    const sequence = "Popořadě";
    const action = "Akce";
    const start_exam = "Zahájit zkoušení";
    const named_lesson = "lekce <span class=\"code\">«{{NAME}}»</span>";
    const more_lessons = "více lekcí";
    const exam_title = "Zkoušení z&nbsp;{{LESSON}}";
    const term = "Slovíčko";
    const metadata = "Metadata";
    const translation = "Překlad";
    const comment = "Poznámka";
    const knew = "Věděl jsem";
    const did_not_know = "Nevěděl jsem";
    const reveal = "Odhalit";

    // Import errors.
    const import_file_does_not_exist = "Zvolený soubor k importování neexistuje.";
    const import_file_is_dir = "Zvolený soubor k importování není souborem, nýbrž adresářem.";
    const import_file_empty_or_unreadable = "Zvolený soubor k importování se nepodařilo načíst nebo je prázdný.";
    const import_data_invalid = "Importovaná data jsou neplatná.";
    const new_lesson_must_have_language = "Nová lekce musí mít zvolen jazyk.";
    const unsupported_import_method = "Nepodporovaný způsob importování dat.";
    const invalid_line = "Za neplatný byl prohlášen řádek obsahující «<span class=\"code\">{{LINE}}</span>».";
    const lesson_create_error = "Vytvoření lekce se nezdařilo";

    // Import.
    const bulk_title = "Dávkově vložit slovíčka v&nbsp;CSV formátu";
    const create_new_lesson = "Vytvořit novou lekci";
    const insert_to_existing_lesson = "Vložit do stávající lekce";
    const new_lesson_name = "Jméno nové lekce";
    const existing_lesson = "Stávající lekce";
    const language = "Jazyk";
    const data = "Data";
    const start_import = "Importovat";
    const show = "Ukázat";
    const duplicate_found = "Nalezen duplikát v&nbsp;lekci «<span class=\"code\">{{LESSON}}</span>»!";
    const import_title = "Importovat slovíčka z&nbsp;CSV souboru";
    const lesson_name = "Jméno lekce";
    const import_file = "Soubor k&nbsp;importování";

    // Language errors.
    const language_must_have_name = "Jazyk nelze uložit, pokud nemá jméno.";
    const language_must_have_id = "Jazyk nelze smazat, neznáme-li jeho ID.";
    const language_undeletable = "Tento jazyk nelze smazat.";
    const language_name_cant_be_empty = "Jméno nemůže být prázdné.";
    const language_uneditable = "Tento jazyk nelze upravovat.";
    const language_not_added = "Jazyk «<span style=\"code\">{{NAME}}</span>» se nepodařilo přidat";
    const no_language_exists = "Žádný jazyk neexistuje.";
    const language_list_could_not_be_obtained = "Nepodařilo se získat seznam jazyků";
    const language_delete_error = "Jazyk «<span class=\"code\">{{NAME}}</span>» se nepodařilo smazat";
    const language_edit_error = "Jazyk «<span class=\"code\">{{NAME}}</span>» se nepodařilo upravit";
    const language_set_as_default_error = "Jazyk «<span class=\"code\">{{NAME}}</span>» se nepodařilo nastavit jako výchozí.";

    // Language.
    const language_added = "Přidán jazyk «<span class=\"code\">{{NAME}}</span>».";
    const language_deleted = "Jazyk «<span class=\"code\">{{NAME}}</span>» byl smazán.";
    const language_edited = "Jazyk «<span class=\"code\">{{NAME}}</span>» byl upraven.";
    const language_set_as_default = "Jazyk «<span class=\"code\">{{NAME}}</span>» byl nastaven jako výchozí.";
    const add_language = "Přidat jazyk";
    const name = "Jméno";
    const add = "Přidat";
    const language_edit_title = "Upravit jazyk «<span class=\"code\">{{NAME}}</span>»";
    const edit = "Upravit";
    const language_list = "Seznam jazyků";
    const lesson_count = "Lesson count";
    const default_language = "výchozí";
    const delete = "Smazat";
    const set_as_default = "Nastavit jako výchozí";

    // Lesson errors.
    const lesson_name_cant_be_empty = "Jméno lekce nemůže být prázdné.";
    const term_must_be_term_object = "Slovíčko jako objekt musí být typu Term.";
    const lesson_must_have_name = "Lekci nelze uložit, pokud nemá jméno.";
    const lesson_must_have_language = "Lekci nelze uložit, pokud nemá zvolen jazyk.";
    const lesson_must_have_terms = "Lekci nelze uložit, pokud neobsahuje žádná slovíčka.";
    const lesson_must_have_id = "Lekci nelze smazat, neznáme-li jeho ID.";
    const no_lesson_exists = "Žádná lekce neexistuje.";
    const lesson_list_could_not_be_obtained = "Nepodařilo se získat seznam lekcí";
    const lesson_delete_error = "Lekci «<span class=\"code\">{{NAME}}</span>» se nepodařilo smazat";
    const lesson_add_error = "Lekci «<span style=\"code\">{{NAME}}</span>» se nepodařilo přidat";
    const lesson_edit_error = "Lekci «<span class=\"code\">{{NAME}}</span>» se nepodařilo upravit";

    // Lesson.
    const suffix_duplicate = " (duplikát)";
    const proxy_unknown = "(neznámý)";
    const lesson_deleted = "Lekce «<span class=\"code\">{{NAME}}</span>» byla smazána.";
    const lesson_added = "Přidána lekce «<span class=\"code\">{{NAME}}</span>».";
    const lesson_edited = "Lekce «<span class=\"code\">{{NAME}}</span>» byla upravena.";
    const add_lesson = "Přidat novou lekci";
    const lesson_edit_title = "Upravit lekci «<span class=\"code\">{{NAME}}</span>»";
    const term_count = "Počet slovíček";
    const duplicate = "Duplikovat";
    const terms = "Slovíčka";

    // Term errors.
    const term_must_have_lesson = "Lekci nelze nastavit jako žádnou.";
    const term_must_have_term = "Slovíčko nelze nastavit jako prázdné.";
    const term_must_have_translation = "Překlad nelze nastavit jako prázdný.";
    const term_must_have_id = "Slovíčko nelze smazat, neznáme-li jeho ID.";
    const term_list_must_have_lesson = "Pro získání seznamu slovíček je třeba zvolit lekci.";
    const lesson_empty = "V lekci «<span class=\"code\">{{NAME}}}</span>» nejsou žádná slovíčka.";
    const term_list_could_not_be_obtained = "Nepodařilo se získat seznam slovíček pro lekci «<span class=\"code\">{{NAME}}</span>».";
    const term_delete_error = "Slovíčko «<span class=\"code\">{{TERM}}</span>» se nepodařilo smazat";
    const term_edit_error = "Slovíčko «<span class=\"code\">{{TERM}} </span>» se nepodařilo upravit";
    const term_add_error = "Slovíčko «<span style=\"code\">{{TERM}}</span>» se nepodařilo přidat";

    // Term.
    const term_deleted = "Slovíčko «<span class=\"code\">{{TERM}}</span>» bylo smazáno.";
    const term_edited = "Slovíčko «<span class=\"code\">{{TERM}}</span>» bylo upraveno.";
    const term_added = "Slovíčko «<span class=\"code\">{{TERM}}</span>» bylo přidáno.";
    const add_term = "Přidat slovíčko";
    const term_edit_title = "Upravit slovíčko «<span class=\"code\">{{TERM}}</span>»";
    const term_list_title = "Seznam slovíček pro lekci «<span class=\"code\">{{LESSON}}</span>»";
    const sort_by_order = "Seřadit podle pořadí";
    const sort_by_term = "Seřadit podle abecedy";

    // User interface.
    const exam = "Zkoušení";
    const import = "Import";
    const bulk = "Dávka";
    const languages = "Jazyky";
    const lessons = "Lekce";

    // Setup.
    const structure_import_success = "Import databázové struktury proběhl úspěšně.";
    const structure_import_error = "Import databázové struktury se nezdařil";
}
?>