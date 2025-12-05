<?php
if (!function_exists('get_session_field')) {
    /**
     * Get values from the session if any
     * @param string $field
     * @return mixed
     */
    function get_session_field(string $field): mixed
    {
        $session = session();
        return $session->get($field) ?? null;
    }
}