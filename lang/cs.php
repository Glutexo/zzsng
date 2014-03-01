<?php
class master_lang {
    const unsupported_database_type = "Nepodporovaný typ databáze.";
    const database_connection_error = "Selhalo připojení k databázi";
    const database_selection_error = "Selhalo zvolení databáze";
	const no_row_limit_set = "Nenastaven nejvyšší možný počet řádků v tabulce, nelze používat dělené tabulky.";
	const comparison_error = "Chyba při porovnávání/řazení.";

    const value_pairs_must_be_array = "Páry hodnot pro vložení musejí být pole.";
    const sql_pairs_must_be_array = "Páry SQL hodnot pro vložení musejí být pole.";
    const pair_collision = "Kolize prostých a SQL hodnot při vkládání do databáze. Klíč «{{KEY}}» je přítomen v obou sadách.";
    const query_failed = "SQL dotaz selhal. Chyba: «{{ERROR}}». Dotaz: {{QUERY}}";

    const sql_file_read_error = "Nepodařilo se načíst SQL soubor.";
}
?>