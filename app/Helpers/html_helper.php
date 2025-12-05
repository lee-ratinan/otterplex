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
if (!function_exists('retrieve_avatars')) {
    /**
     * Create avatar - only for admin
     * @param string $email_address
     * @param string $full_name
     * @return string
     */
    function retrieve_avatars(string $email_address, string $full_name): string
    {
        $email_address  = preg_replace('/[^a-z0-9]/i', '', strtolower($email_address));
        $file_url       = base_url('file/profile_picture_' . $email_address . '.jpg');
        $file_path      = WRITEPATH . 'uploads/profile_pictures/profile_' . $email_address . '.jpg';
        if (file_exists($file_path)) {
            return "<img src='" . $file_url . "' class='avatar-img' title='{$full_name}' data-bs-toggle='tooltip' data-bs-placement='top'>";
        }
        $hash  = hash('md5', $email_address . $full_name);
        $color = '#' . substr($hash, 0, 6);
        $r     = hexdec(substr($hash, 0, 2));
        $g     = hexdec(substr($hash, 2, 2));
        $b     = hexdec(substr($hash, 4, 2));
        $avg   = (($r/255*100) + ($g/255*100) + ($b/255*100))/3;
        $text_color = '#fff';
        if ($avg > 50) {
            $text_color = '#000';
        }
        $name_pieces = explode(' ', $full_name);
        $initials = '';
        for ($i = 0; $i <= 1; $i++) {
            if ($name_pieces[$i]) {
                $initials .= strtoupper(substr($name_pieces[$i], 0, 1));
            }
        }
        return "<div class='avatar-txt' style='background-color:$color;color:$text_color' title='$full_name' data-bs-toggle='tooltip' data-bs-placement='top'>$initials</div>";
    }
}