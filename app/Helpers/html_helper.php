<?php
function gen_js_fields_checker (array $fields = []): void
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

function gen_json_fields_to_fields (array $fields = []): void
{
    $assigned_vals = [];
    foreach ($fields as $field) {
        $assigned_vals[] = "{$field}: {$field}";
    }
    echo '{' . implode(',', $assigned_vals) . '}';
}