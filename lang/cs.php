<?php
class master_lang {
    const unsupported_database_type = "Nepodporovaný typ databáze.";
    const database_connection_error = "Selhalo připojení k databázi";
    const database_selection_error = "Selhalo zvolení databáze";

    const value_pairs_must_be_array = "Páry hodnot pro vložení musejí být pole.";
    const sql_pairs_must_be_array = "Páry SQL hodnot pro vložení musejí být pole.";
    const pair_collision = "Kolize prostých a SQL hodnot při vkládání do databáze. Klíč «{{KEY}}» je přítomen v obou sadách.";
    const query_failed = "SQL dotaz selhal. Chyba: «{{ERROR}}». Dotaz: {{QUERY}}";
}
?>