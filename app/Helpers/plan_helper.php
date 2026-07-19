<?php
if (!function_exists('retrieve_plans')) {
    /**
     * Retrieve plan information for a specific country
     * @param string $countryCode
     * @return array
     */
    function retrieve_plans(string $countryCode): array
    {
        $countryCode = strtoupper($countryCode);
        $plans = [
            'TH' => [
                'free'     => [
                    'monthly'  => [0, 0],
                    'annually' => [0, 0]
                ],
                'basic'    => [
                    'monthly'  => [299, 400],
                    'annually' => [2990, 4000]
                ],
                'standard' => [
                    'monthly'  => [599, 800],
                    'annually' => [5990, 8000]
                ],
                'premium'  => [
                    'monthly'  => [1290, 1800],
                    'annually' => [12900, 18000]
                ],
            ]
        ];
        if ($plans[$countryCode]) {
            return $plans[$countryCode];
        }
        return [];
    }
}
if (!function_exists('retrieve_plan_options')) {
    /**
     * Retrieve plan options
     * @param string $plan_name (optional)
     * @return array
     */
    function retrieve_plan_options(string $plan_name = ''): array
    {
        $plan_name = strtolower($plan_name);
        $options   = [
            'free'     => [
                'user_accounts' => 1,
                'services'      => 1,
                'products'      => 1,
                'variants'      => 1,
                'images'        => 1,
                'branches'      => 1,
                'timeslots'     => true,
                'sessions'      => false,
                'about-us'      => false
            ],
            'basic'    => [
                'user_accounts' => 3,
                'services'      => 5,
                'products'      => 5,
                'variants'      => 5,
                'images'        => 1,
                'branches'      => 1,
                'timeslots'     => true,
                'sessions'      => false,
                'about-us'      => false
            ],
            'standard' => [
                'user_accounts' => 10,
                'services'      => 20,
                'products'      => 20,
                'variants'      => 5,
                'images'        => 3,
                'branches'      => 5,
                'timeslots'     => true,
                'sessions'      => true,
                'about-us'      => false
            ],
            'premium'  => [
                'user_accounts' => 30,
                'services'      => 50,
                'products'      => 50,
                'variants'      => 10,
                'images'        => 5,
                'branches'      => 10,
                'timeslots'     => true,
                'sessions'      => true,
                'about-us'      => true
            ],
        ];
        if (empty($plan_name)) {
            return $options;
        } else if ($options[$plan_name]) {
            return $options[$plan_name];
        }
        return [];
    }
}