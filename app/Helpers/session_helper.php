<?php
if (!function_exists('is_login')) {
    /**
     * Check the session information
     * @return array
     */
    function is_login(): array
    {
        $session    = session();
        $session_id = session_id();
        if ($session->session_id !== $session_id) {
            return [
                'login'        => false,
                'user'         => null,
                'business'     => null,
                'business_ids' => []
            ];
        }
        return [
            'login'        => true,
            'user'         => $session->user,
            'business'     => @$session->business,
            'business_ids' => @$session->business_ids
        ];
    }
}