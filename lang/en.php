<?php
class master_lang {
    const unsupported_database_type = "Unsupported database type"
    const database_connection_error = "Database connection error";
    const database_selection_error = "Database selection error";

    const value_pairs_must_be_array = "Value pairs must be an array.";
    const sql_pairs_must_be_array = "SQL pairs must be an array.";
    const pair_collision = "Plain and SQL pair collision. Key «{{KEY}}» is present in both sets.";
    const query_failed = "SQL query failed. Error: «{{ERROR}}». Query: {{QUERY}}";
}
?>