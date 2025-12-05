<?php
if (!function_exists('gen_js_fields_checker')) {
    /**
     * Generate the field checker in JS - just ensure the fields are not empty
     * @param array $fields IDs of the fields that can't be empty
     * @return void
     */
    function gen_js_fields_checker(array $fields = []): void
    {
        $assigned_vals = [];
        foreach ($fields as $field) {
            $assigned_vals[] = "{$field} = $('#{$field}').val()";
        }
        echo 'let ' . implode(',', $assigned_vals) . ";\n";
        foreach ($fields as $field) {
            echo "if ('' === {$field}) { $('#{$field}').focus(); return false; }";
        }
    }
}
if (!function_exists('gen_json_fields_to_fields')) {
    /**
     * Assign the fields to JSON array
     * @param array $fields
     * @return void
     */
    function gen_json_fields_to_fields(array $fields = []): void
    {
        $assigned_vals = [];
        foreach ($fields as $field) {
            $assigned_vals[] = "{$field}: $('#{$field}').val()";
        }
        echo '{' . implode(',', $assigned_vals) . '}';
    }
}