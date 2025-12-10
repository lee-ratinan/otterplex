<?php
if (!function_exists('get_setting_limitation')) {
    /**
     * Get the limitation of the creation of data according to the package
     * @param string $package
     * @return array
     */
    function get_setting_limitation(string $package): array
    {
        $data = [
            'basic'    => [
                'business_user'   => 10,
                'branch_master'   => 10,
                'service_master'  => 10,
                'service_variant' => 50,
                'product_master'  => 10,
                'product_variant' => 50,
            ],
            'standard' => [
                'business_user'   => 20,
                'branch_master'   => 20,
                'service_master'  => 30,
                'service_variant' => 150,
                'product_master'  => 30,
                'product_variant' => 150,
            ],
            'premium'  => [
                'business_user'   => 50,
                'branch_master'   => 50,
                'service_master'  => 100,
                'service_variant' => 500,
                'product_master'  => 100,
                'product_variant' => 500,
            ],
        ];
        return $data[$package] ?? [];
    }
}

if (!function_exists('get_country_codes')) {
    /**
     * Get ISO3166 country codes
     * @return array
     */
    function get_country_codes(): array
    {
        return [
            'countries'    => [
                'AF' => [
                    'common_name'   => 'Afghanistan',
                    'official_name' => 'The Islamic Republic of Afghanistan'
                ],
                'AX' => [
                    'common_name'   => 'Åland Islands',
                    'official_name' => 'Åland'
                ],
                'AL' => [
                    'common_name'   => 'Albania',
                    'official_name' => 'the Republic of Albania'
                ],
                'DZ' => [
                    'common_name'   => 'Algeria',
                    'official_name' => 'the People’s Democratic Republic of Algeria'
                ],
                'AS' => [
                    'common_name'   => 'American Samoa',
                    'official_name' => 'American Samoa'
                ],
                'AD' => [
                    'common_name'   => 'Andorra',
                    'official_name' => 'the Principality of Andorra'
                ],
                'AO' => [
                    'common_name'   => 'Angola',
                    'official_name' => 'the Republic of Angola'
                ],
                'AI' => [
                    'common_name'   => 'Anguilla',
                    'official_name' => 'Anguilla'
                ],
                'AQ' => [
                    'common_name'   => 'Antarctica',
                    'official_name' => 'Antarctica'
                ],
                'AG' => [
                    'common_name'   => 'Antigua and Barbuda',
                    'official_name' => 'Antigua and Barbuda'
                ],
                'AR' => [
                    'common_name'   => 'Argentina',
                    'official_name' => 'the Argentine Republic'
                ],
                'AM' => [
                    'common_name'   => 'Armenia',
                    'official_name' => 'the Republic of Armenia'
                ],
                'AW' => [
                    'common_name'   => 'Aruba',
                    'official_name' => 'the Country of Aruba'
                ],
                'AU' => [
                    'common_name'   => 'Australia',
                    'official_name' => 'the Commonwealth of Australia'
                ],
                'AT' => [
                    'common_name'   => 'Austria',
                    'official_name' => 'the Republic of Austria'
                ],
                'AZ' => [
                    'common_name'   => 'Azerbaijan',
                    'official_name' => 'the Republic of Azerbaijan'
                ],
                'BS' => [
                    'common_name'   => 'Bahamas',
                    'official_name' => 'the Commonwealth of The Bahamas'
                ],
                'BH' => [
                    'common_name'   => 'Bahrain',
                    'official_name' => 'the Kingdom of Bahrain'
                ],
                'BD' => [
                    'common_name'   => 'Bangladesh',
                    'official_name' => 'the People’s Republic of Bangladesh'
                ],
                'BB' => [
                    'common_name'   => 'Barbados',
                    'official_name' => 'Barbados'
                ],
                'BY' => [
                    'common_name'   => 'Belarus',
                    'official_name' => 'the Republic of Belarus'
                ],
                'BE' => [
                    'common_name'   => 'Belgium',
                    'official_name' => 'the Kingdom of Belgium'
                ],
                'BZ' => [
                    'common_name'   => 'Belize',
                    'official_name' => 'Belize'
                ],
                'BJ' => [
                    'common_name'   => 'Benin',
                    'official_name' => 'the Republic of Benin'
                ],
                'BM' => [
                    'common_name'   => 'Bermuda',
                    'official_name' => 'Bermuda'
                ],
                'BT' => [
                    'common_name'   => 'Bhutan',
                    'official_name' => 'the Kingdom of Bhutan'
                ],
                'BO' => [
                    'common_name'   => 'Bolivia',
                    'official_name' => 'the Plurinational State of Bolivia'
                ],
                'BQ' => [
                    'common_name'   => 'Bonaire, Sint Eustatius and Saba',
                    'official_name' => 'Bonaire, Sint Eustatius and Saba'
                ],
                'BA' => [
                    'common_name'   => 'Bosnia and Herzegovina',
                    'official_name' => 'Bosnia and Herzegovina'
                ],
                'BW' => [
                    'common_name'   => 'Botswana',
                    'official_name' => 'the Republic of Botswana'
                ],
                'BV' => [
                    'common_name'   => 'Bouvet Island',
                    'official_name' => 'Bouvet Island'
                ],
                'BR' => [
                    'common_name'   => 'Brazil',
                    'official_name' => 'the Federative Republic of Brazil'
                ],
                'IO' => [
                    'common_name'   => 'British Indian Ocean Territory',
                    'official_name' => 'the British Indian Ocean Territory'
                ],
                'BN' => [
                    'common_name'   => 'Brunei Darussalam',
                    'official_name' => 'Brunei Darussalam'
                ],
                'BG' => [
                    'common_name'   => 'Bulgaria',
                    'official_name' => 'the Republic of Bulgaria'
                ],
                'BF' => [
                    'common_name'   => 'Burkina Faso',
                    'official_name' => 'Burkina Faso'
                ],
                'BI' => [
                    'common_name'   => 'Burundi',
                    'official_name' => 'the Republic of Burundi'
                ],
                'CV' => [
                    'common_name'   => 'Cabo Verde (Cape Verde)',
                    'official_name' => 'the Republic of Cabo Verde'
                ],
                'KH' => [
                    'common_name'   => 'Cambodia',
                    'official_name' => 'the Kingdom of Cambodia'
                ],
                'CM' => [
                    'common_name'   => 'Cameroon',
                    'official_name' => 'the Republic of Cameroon'
                ],
                'CA' => [
                    'common_name'   => 'Canada',
                    'official_name' => 'Canada'
                ],
                'KY' => [
                    'common_name'   => 'Cayman Islands',
                    'official_name' => 'the Cayman Islands'
                ],
                'CF' => [
                    'common_name'   => 'Central African Republic',
                    'official_name' => 'the Central African Republic'
                ],
                'TD' => [
                    'common_name'   => 'Chad',
                    'official_name' => 'the Republic of Chad'
                ],
                'CL' => [
                    'common_name'   => 'Chile',
                    'official_name' => 'the Republic of Chile'
                ],
                'CN' => [
                    'common_name'   => 'China (PRC)',
                    'official_name' => 'the People’s Republic of China'
                ],
                'CX' => [
                    'common_name'   => 'Christmas Island',
                    'official_name' => 'the Territory of Christmas Island'
                ],
                'CC' => [
                    'common_name'   => 'Cocos (Keeling) Islands',
                    'official_name' => 'the Territory of Cocos (Keeling) Islands'
                ],
                'CO' => [
                    'common_name'   => 'Colombia',
                    'official_name' => 'the Republic of Colombia'
                ],
                'KM' => [
                    'common_name'   => 'Comoros',
                    'official_name' => 'the Union of the Comoros'
                ],
                'CD' => [
                    'common_name'   => 'Congo (DRC)',
                    'official_name' => 'the Democratic Republic of the Congo'
                ],
                'CG' => [
                    'common_name'   => 'Congo',
                    'official_name' => 'the Republic of the Congo'
                ],
                'CK' => [
                    'common_name'   => 'Cook Islands',
                    'official_name' => 'the Cook Islands'
                ],
                'CR' => [
                    'common_name'   => 'Costa Rica',
                    'official_name' => 'the Republic of Costa Rica'
                ],
                'CI' => [
                    'common_name'   => 'Côte d’Ivoire (Ivory Coast)',
                    'official_name' => 'the Republic of Côte d’Ivoire'
                ],
                'HR' => [
                    'common_name'   => 'Croatia',
                    'official_name' => 'the Republic of Croatia'
                ],
                'CU' => [
                    'common_name'   => 'Cuba',
                    'official_name' => 'the Republic of Cuba'
                ],
                'CW' => [
                    'common_name'   => 'Curaçao',
                    'official_name' => 'the Country of Curaçao'
                ],
                'CY' => [
                    'common_name'   => 'Cyprus',
                    'official_name' => 'the Republic of Cyprus'
                ],
                'CZ' => [
                    'common_name'   => 'Czechia',
                    'official_name' => 'the Czech Republic'
                ],
                'DK' => [
                    'common_name'   => 'Denmark',
                    'official_name' => 'the Kingdom of Denmark'
                ],
                'DJ' => [
                    'common_name'   => 'Djibouti',
                    'official_name' => 'the Republic of Djibouti'
                ],
                'DM' => [
                    'common_name'   => 'Dominica',
                    'official_name' => 'the Commonwealth of Dominica'
                ],
                'DO' => [
                    'common_name'   => 'Dominican Republic',
                    'official_name' => 'the Dominican Republic'
                ],
                'EC' => [
                    'common_name'   => 'Ecuador',
                    'official_name' => 'the Republic of Ecuador'
                ],
                'EG' => [
                    'common_name'   => 'Egypt',
                    'official_name' => 'the Arab Republic of Egypt'
                ],
                'SV' => [
                    'common_name'   => 'El Salvador',
                    'official_name' => 'the Republic of El Salvador'
                ],
                'GQ' => [
                    'common_name'   => 'Equatorial Guinea',
                    'official_name' => 'the Republic of Equatorial Guinea'
                ],
                'ER' => [
                    'common_name'   => 'Eritrea',
                    'official_name' => 'the State of Eritrea'
                ],
                'EE' => [
                    'common_name'   => 'Estonia',
                    'official_name' => 'the Republic of Estonia'
                ],
                'SZ' => [
                    'common_name'   => 'Eswatini',
                    'official_name' => 'the Kingdom of Eswatini'
                ],
                'ET' => [
                    'common_name'   => 'Ethiopia',
                    'official_name' => 'the Federal Democratic Republic of Ethiopia'
                ],
                'FK' => [
                    'common_name'   => 'Falkland Islands',
                    'official_name' => 'the Falkland Islands'
                ],
                'FO' => [
                    'common_name'   => 'Faroe Islands',
                    'official_name' => 'the Faroe Islands'
                ],
                'FJ' => [
                    'common_name'   => 'Fiji',
                    'official_name' => 'the Republic of Fiji'
                ],
                'FI' => [
                    'common_name'   => 'Finland',
                    'official_name' => 'the Republic of Finland'
                ],
                'FR' => [
                    'common_name'   => 'France',
                    'official_name' => 'the French Republic'
                ],
                'GF' => [
                    'common_name'   => 'French Guiana',
                    'official_name' => 'Guyane'
                ],
                'PF' => [
                    'common_name'   => 'French Polynesia',
                    'official_name' => 'Overseas Lands of French Polynesia'
                ],
                'TF' => [
                    'common_name'   => 'French Southern Territories',
                    'official_name' => 'the French Southern and Antarctic Lands'
                ],
                'GA' => [
                    'common_name'   => 'Gabon',
                    'official_name' => 'the Gabonese Republic'
                ],
                'GM' => [
                    'common_name'   => 'Gambia',
                    'official_name' => 'the Republic of The Gambia'
                ],
                'GE' => [
                    'common_name'   => 'Georgia',
                    'official_name' => 'Georgia'
                ],
                'DE' => [
                    'common_name'   => 'Germany',
                    'official_name' => 'the Federal Republic of Germany'
                ],
                'GH' => [
                    'common_name'   => 'Ghana',
                    'official_name' => 'the Republic of Ghana'
                ],
                'GI' => [
                    'common_name'   => 'Gibraltar',
                    'official_name' => 'Gibraltar'
                ],
                'GR' => [
                    'common_name'   => 'Greece',
                    'official_name' => 'the Hellenic Republic'
                ],
                'GL' => [
                    'common_name'   => 'Greenland',
                    'official_name' => 'Greenland'
                ],
                'GD' => [
                    'common_name'   => 'Grenada',
                    'official_name' => 'Grenada'
                ],
                'GP' => [
                    'common_name'   => 'Guadeloupe',
                    'official_name' => 'Guadeloupe'
                ],
                'GU' => [
                    'common_name'   => 'Guam',
                    'official_name' => 'Guam'
                ],
                'GT' => [
                    'common_name'   => 'Guatemala',
                    'official_name' => 'the Republic of Guatemala'
                ],
                'GG' => [
                    'common_name'   => 'Guernsey',
                    'official_name' => 'the Bailiwick of Guernsey'
                ],
                'GN' => [
                    'common_name'   => 'Guinea',
                    'official_name' => 'the Republic of Guinea'
                ],
                'GW' => [
                    'common_name'   => 'Guinea-Bissau',
                    'official_name' => 'the Republic of Guinea-Bissau'
                ],
                'GY' => [
                    'common_name'   => 'Guyana',
                    'official_name' => 'the Co-operative Republic of Guyana'
                ],
                'HT' => [
                    'common_name'   => 'Haiti',
                    'official_name' => 'the Republic of Haiti'
                ],
                'HM' => [
                    'common_name'   => 'Heard Island and McDonald Islands',
                    'official_name' => 'the Territory of Heard Island and McDonald Islands'
                ],
                'VA' => [
                    'common_name'   => 'Holy See (Vatican City)',
                    'official_name' => 'the Holy See'
                ],
                'HN' => [
                    'common_name'   => 'Honduras',
                    'official_name' => 'the Republic of Honduras'
                ],
                'HK' => [
                    'common_name'   => 'Hong Kong',
                    'official_name' => 'the Hong Kong Special Administrative Region of China'
                ],
                'HU' => [
                    'common_name'   => 'Hungary',
                    'official_name' => 'Hungary'
                ],
                'IS' => [
                    'common_name'   => 'Iceland',
                    'official_name' => 'Iceland'
                ],
                'IN' => [
                    'common_name'   => 'India',
                    'official_name' => 'the Republic of India'
                ],
                'ID' => [
                    'common_name'   => 'Indonesia',
                    'official_name' => 'the Republic of Indonesia'
                ],
                'IR' => [
                    'common_name'   => 'Iran',
                    'official_name' => 'the Islamic Republic of Iran'
                ],
                'IQ' => [
                    'common_name'   => 'Iraq',
                    'official_name' => 'the Republic of Iraq'
                ],
                'IE' => [
                    'common_name'   => 'Ireland',
                    'official_name' => 'Ireland'
                ],
                'IM' => [
                    'common_name'   => 'Isle of Man',
                    'official_name' => 'the Isle of Man'
                ],
                'IL' => [
                    'common_name'   => 'Israel',
                    'official_name' => 'the State of Israel'
                ],
                'IT' => [
                    'common_name'   => 'Italy',
                    'official_name' => 'the Italian Republic'
                ],
                'JM' => [
                    'common_name'   => 'Jamaica',
                    'official_name' => 'Jamaica'
                ],
                'JP' => [
                    'common_name'   => 'Japan',
                    'official_name' => 'Japan'
                ],
                'JE' => [
                    'common_name'   => 'Jersey',
                    'official_name' => 'the Bailiwick of Jersey'
                ],
                'JO' => [
                    'common_name'   => 'Jordan',
                    'official_name' => 'the Hashemite Kingdom of Jordan'
                ],
                'KZ' => [
                    'common_name'   => 'Kazakhstan',
                    'official_name' => 'the Republic of Kazakhstan'
                ],
                'KE' => [
                    'common_name'   => 'Kenya',
                    'official_name' => 'the Republic of Kenya'
                ],
                'KI' => [
                    'common_name'   => 'Kiribati',
                    'official_name' => 'the Republic of Kiribati'
                ],
                'KP' => [
                    'common_name'   => 'North Korea',
                    'official_name' => 'the Democratic People’s Republic of Korea'
                ],
                'KR' => [
                    'common_name'   => 'South Korea',
                    'official_name' => 'the Republic of Korea'
                ],
                'KW' => [
                    'common_name'   => 'Kuwait',
                    'official_name' => 'the State of Kuwait'
                ],
                'KG' => [
                    'common_name'   => 'Kyrgyzstan',
                    'official_name' => 'the Kyrgyz Republic'
                ],
                'LA' => [
                    'common_name'   => 'Laos',
                    'official_name' => 'the Lao People’s Democratic Republic'
                ],
                'LV' => [
                    'common_name'   => 'Latvia',
                    'official_name' => 'the Republic of Latvia'
                ],
                'LB' => [
                    'common_name'   => 'Lebanon',
                    'official_name' => 'the Lebanese Republic'
                ],
                'LS' => [
                    'common_name'   => 'Lesotho',
                    'official_name' => 'the Kingdom of Lesotho'
                ],
                'LR' => [
                    'common_name'   => 'Liberia',
                    'official_name' => 'the Republic of Liberia'
                ],
                'LY' => [
                    'common_name'   => 'Libya',
                    'official_name' => 'the State of Libya'
                ],
                'LI' => [
                    'common_name'   => 'Liechtenstein',
                    'official_name' => 'the Principality of Liechtenstein'
                ],
                'LT' => [
                    'common_name'   => 'Lithuania',
                    'official_name' => 'the Republic of Lithuania'
                ],
                'LU' => [
                    'common_name'   => 'Luxembourg',
                    'official_name' => 'the Grand Duchy of Luxembourg'
                ],
                'MO' => [
                    'common_name'   => 'Macao',
                    'official_name' => 'the Macao Special Administrative Region of China'
                ],
                'MG' => [
                    'common_name'   => 'Madagascar',
                    'official_name' => 'the Republic of Madagascar'
                ],
                'MW' => [
                    'common_name'   => 'Malawi',
                    'official_name' => 'the Republic of Malawi'
                ],
                'MY' => [
                    'common_name'   => 'Malaysia',
                    'official_name' => 'Malaysia'
                ],
                'MV' => [
                    'common_name'   => 'Maldives',
                    'official_name' => 'the Republic of Maldives'
                ],
                'ML' => [
                    'common_name'   => 'Mali',
                    'official_name' => 'the Republic of Mali'
                ],
                'MT' => [
                    'common_name'   => 'Malta',
                    'official_name' => 'the Republic of Malta'
                ],
                'MH' => [
                    'common_name'   => 'Marshall Islands',
                    'official_name' => 'the Republic of the Marshall Islands'
                ],
                'MQ' => [
                    'common_name'   => 'Martinique',
                    'official_name' => 'Martinique'
                ],
                'MR' => [
                    'common_name'   => 'Mauritania',
                    'official_name' => 'the Islamic Republic of Mauritania'
                ],
                'MU' => [
                    'common_name'   => 'Mauritius',
                    'official_name' => 'the Republic of Mauritius'
                ],
                'YT' => [
                    'common_name'   => 'Mayotte',
                    'official_name' => 'the Department of Mayotte'
                ],
                'MX' => [
                    'common_name'   => 'Mexico',
                    'official_name' => 'the United Mexican States'
                ],
                'FM' => [
                    'common_name'   => 'Micronesia',
                    'official_name' => 'the Federated States of Micronesia'
                ],
                'MD' => [
                    'common_name'   => 'Moldova',
                    'official_name' => 'the Republic of Moldova'
                ],
                'MC' => [
                    'common_name'   => 'Monaco',
                    'official_name' => 'the Principality of Monaco'
                ],
                'MN' => [
                    'common_name'   => 'Mongolia',
                    'official_name' => 'Mongolia'
                ],
                'ME' => [
                    'common_name'   => 'Montenegro',
                    'official_name' => 'Montenegro'
                ],
                'MS' => [
                    'common_name'   => 'Montserrat',
                    'official_name' => 'Montserrat'
                ],
                'MA' => [
                    'common_name'   => 'Morocco',
                    'official_name' => 'the Kingdom of Morocco'
                ],
                'MZ' => [
                    'common_name'   => 'Mozambique',
                    'official_name' => 'the Republic of Mozambique'
                ],
                'MM' => [
                    'common_name'   => 'Myanmar',
                    'official_name' => 'the Republic of the Union of Myanmar'
                ],
                'NA' => [
                    'common_name'   => 'Namibia',
                    'official_name' => 'the Republic of Namibia'
                ],
                'NR' => [
                    'common_name'   => 'Nauru',
                    'official_name' => 'the Republic of Nauru'
                ],
                'NP' => [
                    'common_name'   => 'Nepal',
                    'official_name' => 'the Federal Democratic Republic of Nepal'
                ],
                'NL' => [
                    'common_name'   => 'Netherlands',
                    'official_name' => 'the Kingdom of the Netherlands'
                ],
                'NC' => [
                    'common_name'   => 'New Caledonia',
                    'official_name' => 'New Caledonia'
                ],
                'NZ' => [
                    'common_name'   => 'New Zealand',
                    'official_name' => 'New Zealand'
                ],
                'NI' => [
                    'common_name'   => 'Nicaragua',
                    'official_name' => 'the Republic of Nicaragua'
                ],
                'NE' => [
                    'common_name'   => 'Niger',
                    'official_name' => 'the Republic of the Niger'
                ],
                'NG' => [
                    'common_name'   => 'Nigeria',
                    'official_name' => 'the Federal Republic of Nigeria'
                ],
                'NU' => [
                    'common_name'   => 'Niue',
                    'official_name' => 'Niue'
                ],
                'NF' => [
                    'common_name'   => 'Norfolk Island',
                    'official_name' => 'the Territory of Norfolk Island'
                ],
                'MK' => [
                    'common_name'   => 'North Macedonia',
                    'official_name' => 'the Republic of North Macedonia'
                ],
                'MP' => [
                    'common_name'   => 'Northern Mariana Islands',
                    'official_name' => 'the Commonwealth of the Northern Mariana Islands'
                ],
                'NO' => [
                    'common_name'   => 'Norway',
                    'official_name' => 'the Kingdom of Norway'
                ],
                'OM' => [
                    'common_name'   => 'Oman',
                    'official_name' => 'the Sultanate of Oman'
                ],
                'PK' => [
                    'common_name'   => 'Pakistan',
                    'official_name' => 'the Islamic Republic of Pakistan'
                ],
                'PW' => [
                    'common_name'   => 'Palau',
                    'official_name' => 'the Republic of Palau'
                ],
                'PS' => [
                    'common_name'   => 'Palestine',
                    'official_name' => 'the State of Palestine'
                ],
                'PA' => [
                    'common_name'   => 'Panama',
                    'official_name' => 'the Republic of Panama'
                ],
                'PG' => [
                    'common_name'   => 'Papua New Guinea',
                    'official_name' => 'the Independent State of Papua New Guinea'
                ],
                'PY' => [
                    'common_name'   => 'Paraguay',
                    'official_name' => 'the Republic of Paraguay'
                ],
                'PE' => [
                    'common_name'   => 'Peru',
                    'official_name' => 'the Republic of Peru'
                ],
                'PH' => [
                    'common_name'   => 'Philippines',
                    'official_name' => 'the Republic of the Philippines'
                ],
                'PN' => [
                    'common_name'   => 'Pitcairn',
                    'official_name' => 'the Pitcairn, Henderson, Ducie and Oeno Islands'
                ],
                'PL' => [
                    'common_name'   => 'Poland',
                    'official_name' => 'the Republic of Poland'
                ],
                'PT' => [
                    'common_name'   => 'Portugal',
                    'official_name' => 'the Portuguese Republic'
                ],
                'PR' => [
                    'common_name'   => 'Puerto Rico',
                    'official_name' => 'the Commonwealth of Puerto Rico'
                ],
                'QA' => [
                    'common_name'   => 'Qatar',
                    'official_name' => 'the State of Qatar'
                ],
                'RE' => [
                    'common_name'   => 'Réunion',
                    'official_name' => 'Réunion'
                ],
                'RO' => [
                    'common_name'   => 'Romania',
                    'official_name' => 'Romania'
                ],
                'RU' => [
                    'common_name'   => 'Russia',
                    'official_name' => 'the Russian Federation'
                ],
                'RW' => [
                    'common_name'   => 'Rwanda',
                    'official_name' => 'the Republic of Rwanda'
                ],
                'BL' => [
                    'common_name'   => 'Saint Barthélemy',
                    'official_name' => 'the Collectivity of Saint-Barthélemy'
                ],
                'SH' => [
                    'common_name'   => 'Saint Helena, Ascension and Tristan da Cunha',
                    'official_name' => 'Saint Helena, Ascension and Tristan da Cunha'
                ],
                'KN' => [
                    'common_name'   => 'Saint Kitts and Nevis',
                    'official_name' => 'the Federation of Saint Kitts and Nevis'
                ],
                'LC' => [
                    'common_name'   => 'Saint Lucia',
                    'official_name' => 'Saint Lucia'
                ],
                'MF' => [
                    'common_name'   => 'Saint Martin (French)',
                    'official_name' => 'the Collectivity of Saint-Martin'
                ],
                'PM' => [
                    'common_name'   => 'Saint Pierre and Miquelon',
                    'official_name' => 'the Overseas Collectivity of Saint-Pierre and Miquelon'
                ],
                'VC' => [
                    'common_name'   => 'Saint Vincent and the Grenadines',
                    'official_name' => 'Saint Vincent and the Grenadines'
                ],
                'WS' => [
                    'common_name'   => 'Samoa',
                    'official_name' => 'the Independent State of Samoa'
                ],
                'SM' => [
                    'common_name'   => 'San Marino',
                    'official_name' => 'the Republic of San Marino'
                ],
                'ST' => [
                    'common_name'   => 'São Tomé and Príncipe',
                    'official_name' => 'the Democratic Republic of São Tomé and Príncipe'
                ],
                'SA' => [
                    'common_name'   => 'Saudi Arabia',
                    'official_name' => 'the Kingdom of Saudi Arabia'
                ],
                'SN' => [
                    'common_name'   => 'Senegal',
                    'official_name' => 'the Republic of Senegal'
                ],
                'RS' => [
                    'common_name'   => 'Serbia',
                    'official_name' => 'the Republic of Serbia'
                ],
                'SC' => [
                    'common_name'   => 'Seychelles',
                    'official_name' => 'the Republic of Seychelles'
                ],
                'SL' => [
                    'common_name'   => 'Sierra Leone',
                    'official_name' => 'the Republic of Sierra Leone'
                ],
                'SG' => [
                    'common_name'   => 'Singapore',
                    'official_name' => 'the Republic of Singapore'
                ],
                'SX' => [
                    'common_name'   => 'Sint Maarten (Dutch)',
                    'official_name' => 'Sint Maarten'
                ],
                'SK' => [
                    'common_name'   => 'Slovakia',
                    'official_name' => 'the Slovak Republic'
                ],
                'SI' => [
                    'common_name'   => 'Slovenia',
                    'official_name' => 'the Republic of Slovenia'
                ],
                'SB' => [
                    'common_name'   => 'Solomon Islands',
                    'official_name' => 'the Solomon Islands'
                ],
                'SO' => [
                    'common_name'   => 'Somalia',
                    'official_name' => 'the Federal Republic of Somalia'
                ],
                'ZA' => [
                    'common_name'   => 'South Africa',
                    'official_name' => 'the Republic of South Africa'
                ],
                'GS' => [
                    'common_name'   => 'South Georgia and the South Sandwich Islands',
                    'official_name' => 'South Georgia and the South Sandwich Islands'
                ],
                'SS' => [
                    'common_name'   => 'South Sudan',
                    'official_name' => 'the Republic of South Sudan'
                ],
                'ES' => [
                    'common_name'   => 'Spain',
                    'official_name' => 'the Kingdom of Spain'
                ],
                'LK' => [
                    'common_name'   => 'Sri Lanka',
                    'official_name' => 'the Democratic Socialist Republic of Sri Lanka'
                ],
                'SD' => [
                    'common_name'   => 'Sudan',
                    'official_name' => 'the Republic of the Sudan'
                ],
                'SR' => [
                    'common_name'   => 'Suriname',
                    'official_name' => 'the Republic of Suriname'
                ],
                'SJ' => [
                    'common_name'   => 'Svalbard and Jan Mayen',
                    'official_name' => 'Svalbard and Jan Mayen'
                ],
                'SE' => [
                    'common_name'   => 'Sweden',
                    'official_name' => 'the Kingdom of Sweden'
                ],
                'CH' => [
                    'common_name'   => 'Switzerland',
                    'official_name' => 'the Swiss Confederation'
                ],
                'SY' => [
                    'common_name'   => 'Syria',
                    'official_name' => 'the Syrian Arab Republic'
                ],
                'TW' => [
                    'common_name'   => 'Taiwan (ROC)',
                    'official_name' => 'the Republic of China'
                ],
                'TJ' => [
                    'common_name'   => 'Tajikistan',
                    'official_name' => 'the Republic of Tajikistan'
                ],
                'TZ' => [
                    'common_name'   => 'Tanzania',
                    'official_name' => 'the United Republic of Tanzania'
                ],
                'TH' => [
                    'common_name'   => 'Thailand',
                    'official_name' => 'the Kingdom of Thailand'
                ],
                'TL' => [
                    'common_name'   => 'Timor-Leste (East Timor)',
                    'official_name' => 'the Democratic Republic of Timor-Leste'
                ],
                'TG' => [
                    'common_name'   => 'Togo',
                    'official_name' => 'the Togolese Republic'
                ],
                'TK' => [
                    'common_name'   => 'Tokelau',
                    'official_name' => 'Tokelau'
                ],
                'TO' => [
                    'common_name'   => 'Tonga',
                    'official_name' => 'the Kingdom of Tonga'
                ],
                'TT' => [
                    'common_name'   => 'Trinidad and Tobago',
                    'official_name' => 'the Republic of Trinidad and Tobago'
                ],
                'TN' => [
                    'common_name'   => 'Tunisia',
                    'official_name' => 'the Republic of Tunisia'
                ],
                'TR' => [
                    'common_name'   => 'Türkiye (Turkey)',
                    'official_name' => 'the Republic of Türkiye'
                ],
                'TM' => [
                    'common_name'   => 'Turkmenistan',
                    'official_name' => 'Turkmenistan'
                ],
                'TC' => [
                    'common_name'   => 'Turks and Caicos Islands',
                    'official_name' => 'the Turks and Caicos Islands'
                ],
                'TV' => [
                    'common_name'   => 'Tuvalu',
                    'official_name' => 'Tuvalu'
                ],
                'UG' => [
                    'common_name'   => 'Uganda',
                    'official_name' => 'the Republic of Uganda'
                ],
                'UA' => [
                    'common_name'   => 'Ukraine',
                    'official_name' => 'Ukraine'
                ],
                'AE' => [
                    'common_name'   => 'United Arab Emirates',
                    'official_name' => 'the United Arab Emirates'
                ],
                'GB' => [
                    'common_name'   => 'United Kingdom',
                    'official_name' => 'the United Kingdom of Great Britain and Northern Ireland'
                ],
                'UM' => [
                    'common_name'   => 'United States Minor Outlying Islands',
                    'official_name' => 'United States Pacific Island Wildlife Refuges, Navassa Island, and Wake Island'
                ],
                'US' => [
                    'common_name'   => 'United States',
                    'official_name' => 'the United States of America'
                ],
                'UY' => [
                    'common_name'   => 'Uruguay',
                    'official_name' => 'the Oriental Republic of Uruguay'
                ],
                'UZ' => [
                    'common_name'   => 'Uzbekistan',
                    'official_name' => 'the Republic of Uzbekistan'
                ],
                'VU' => [
                    'common_name'   => 'Vanuatu',
                    'official_name' => 'the Republic of Vanuatu'
                ],
                'VE' => [
                    'common_name'   => 'Venezuela',
                    'official_name' => 'the Bolivarian Republic of Venezuela'
                ],
                'VN' => [
                    'common_name'   => 'Vietnam',
                    'official_name' => 'the Socialist Republic of Vietnam'
                ],
                'VG' => [
                    'common_name'   => 'Virgin Islands (British)',
                    'official_name' => 'the Virgin Islands'
                ],
                'VI' => [
                    'common_name'   => 'Virgin Islands (U.S.)',
                    'official_name' => 'the Virgin Islands of the United States'
                ],
                'WF' => [
                    'common_name'   => 'Wallis and Futuna',
                    'official_name' => 'the Territory of the Wallis and Futuna Islands'
                ],
                'EH' => [
                    'common_name'   => 'Western Sahara',
                    'official_name' => 'the Sahrawi Arab Democratic Republic'
                ],
                'YE' => [
                    'common_name'   => 'Yemen',
                    'official_name' => 'the Republic of Yemen'
                ],
                'ZM' => [
                    'common_name'   => 'Zambia',
                    'official_name' => 'the Republic of Zambia'
                ],
                'ZW' => [
                    'common_name'   => 'Zimbabwe',
                    'official_name' => 'the Republic of Zimbabwe'
                ],
                'XK' => [
                    'common_name'   => 'Kosovo',
                    'official_name' => 'the Republic of Kosovo'
                ],
            ],
            'subdivisions' => [
                'AU' => [
                    'AU-NSW' => 'New South Wales',
                    'AU-QLD' => 'Queensland',
                    'AU-SA'  => 'South Australia',
                    'AU-TAS' => 'Tasmania',
                    'AU-VIC' => 'Victoria',
                    'AU-WA'  => 'Western Australia',
                    'AU-ACT' => 'Australian Capital Territory',
                    'AU-NT'  => 'Northern Territory',
                ],
                'ID' => [
                    'ID-AC' => 'Aceh',
                    'ID-BA' => 'Bali',
                    'ID-BB' => 'Bangka Belitung Islands',
                    'ID-BE' => 'Bengkulu',
                    'ID-BT' => 'Banten',
                    'ID-GO' => 'Gorontalo',
                    'ID-JA' => 'Jambi',
                    'ID-JB' => 'West Java',
                    'ID-JI' => 'East Java',
                    'ID-JK' => 'Jakarta',
                    'ID-JT' => 'Central Java',
                    'ID-KB' => 'West Kalimantan',
                    'ID-KI' => 'East Kalimantan',
                    'ID-KR' => 'Riau Islands',
                    'ID-KS' => 'South Kalimantan',
                    'ID-KT' => 'Central Kalimantan',
                    'ID-KU' => 'North Kalimantan',
                    'ID-LA' => 'Lampung',
                    'ID-MA' => 'Maluku',
                    'ID-MU' => 'North Maluku',
                    'ID-NB' => 'West Nusa Tenggara',
                    'ID-NT' => 'East Nusa Tenggara',
                    'ID-PA' => 'Papua',
                    'ID-PB' => 'West Papua',
                    'ID-PD' => 'Southwest Papua',
                    'ID-PE' => 'Highland Papua',
                    'ID-PS' => 'South Papua',
                    'ID-PT' => 'Central Papua',
                    'ID-RI' => 'Riau',
                    'ID-SA' => 'North Sulawesi',
                    'ID-SB' => 'West Sumatra',
                    'ID-SG' => 'Southeast Sulawesi',
                    'ID-SN' => 'South Sulawesi',
                    'ID-SR' => 'West Sulawesi',
                    'ID-SS' => 'South Sumatra',
                    'ID-ST' => 'Central Sulawesi',
                    'ID-SU' => 'North Sumatra',
                    'ID-YO' => 'Yogyakarta'
                ],
                'JP' => [
                    'JP-01' => 'Hokkaido 北海道',
                    'JP-02' => 'Aomori 青森',
                    'JP-03' => 'Iwate 岩手',
                    'JP-04' => 'Miyagi 宮城',
                    'JP-05' => 'Akita 秋田',
                    'JP-06' => 'Yamagata 山形',
                    'JP-07' => 'Fukushima 福島',
                    'JP-08' => 'Ibaraki 茨城',
                    'JP-09' => 'Tochigi 栃木',
                    'JP-10' => 'Gunma 群馬',
                    'JP-11' => 'Saitama 埼玉',
                    'JP-12' => 'Chiba 千葉',
                    'JP-13' => 'Tokyo 東京',
                    'JP-14' => 'Kanagawa 神奈川',
                    'JP-15' => 'Niigata 新潟',
                    'JP-16' => 'Toyama 富山',
                    'JP-17' => 'Ishikawa 石川',
                    'JP-18' => 'Fukui 福井',
                    'JP-19' => 'Yamanashi 山梨',
                    'JP-20' => 'Nagano 長野',
                    'JP-21' => 'Gifu 岐阜',
                    'JP-22' => 'Shizuoka 静岡',
                    'JP-23' => 'Aichi 愛知',
                    'JP-24' => 'Mie 三重',
                    'JP-25' => 'Shiga 滋賀',
                    'JP-26' => 'Kyoto 京都',
                    'JP-27' => 'Osaka 大阪',
                    'JP-28' => 'Hyogo 兵庫',
                    'JP-29' => 'Nara 奈良',
                    'JP-30' => 'Wakayama 和歌山',
                    'JP-31' => 'Tottori 鳥取',
                    'JP-32' => 'Shimane 島根',
                    'JP-33' => 'Okayama 岡山',
                    'JP-34' => 'Hiroshima 広島',
                    'JP-35' => 'Yamaguchi 山口',
                    'JP-36' => 'Tokushima 徳島',
                    'JP-37' => 'Kagawa 香川',
                    'JP-38' => 'Ehime 愛媛',
                    'JP-39' => 'Kochi 高知',
                    'JP-40' => 'Fukuoka 福岡',
                    'JP-41' => 'Saga 佐賀',
                    'JP-42' => 'Nagasaki 長崎',
                    'JP-43' => 'Kumamoto 熊本',
                    'JP-44' => 'Oita 大分',
                    'JP-45' => 'Miyazaki 宮崎',
                    'JP-46' => 'Kagoshima 鹿児島',
                    'JP-47' => 'Okinawa 沖縄'
                ],
                'KR' => [
                    'KR-11' => 'Seoul 서울',
                    'KR-26' => 'Busan 부산',
                    'KR-27' => 'Daegu 대구',
                    'KR-28' => 'Incheon 인천',
                    'KR-29' => 'Gwangju 광주',
                    'KR-30' => 'Daejeon 대전',
                    'KR-31' => 'Ulsan 울산',
                    'KR-41' => 'Gyeonggi 경기도',
                    'KR-42' => 'Gangwon	강원',
                    'KR-43' => 'North Chungcheong 충청북',
                    'KR-44' => 'South Chungcheong 충청남',
                    'KR-45' => 'North Jeolla 전라북',
                    'KR-46' => 'South Jeolla 전라남',
                    'KR-47' => 'North Gyeongsang 경상북',
                    'KR-48' => 'South Gyeongsang 경상남',
                    'KR-49' => 'Jeju 제주',
                    'KR-50' => 'Sejong 세종'
                ],
                'MY' => [
                    'MY-01' => 'Johor',
                    'MY-02' => 'Kedah',
                    'MY-03' => 'Kelantan',
                    'MY-04' => 'Melaka',
                    'MY-05' => 'Negeri Sembilan',
                    'MY-06' => 'Pahang',
                    'MY-07' => 'Pulau Pinang',
                    'MY-08' => 'Perak',
                    'MY-09' => 'Perlis',
                    'MY-10' => 'Selangor',
                    'MY-11' => 'Terengganu',
                    'MY-12' => 'Sabah',
                    'MY-13' => 'Sarawak',
                    'MY-14' => 'Kuala Lumpur',
                    'MY-15' => 'Labuan',
                    'MY-16' => 'Putrajaya'
                ],
                'PH' => [
                    'PH-ABR' => 'Abra',
                    'PH-AGN' => 'Agusan del Norte',
                    'PH-AGS' => 'Agusan del Sur',
                    'PH-AKL' => 'Aklan',
                    'PH-ALB' => 'Albay',
                    'PH-ANT' => 'Antique',
                    'PH-APA' => 'Apayao',
                    'PH-AUR' => 'Aurora',
                    'PH-BAS' => 'Basilan',
                    'PH-BAN' => 'Bataan',
                    'PH-BTN' => 'Batanes',
                    'PH-BTG' => 'Batangas',
                    'PH-BEN' => 'Benguet',
                    'PH-BIL' => 'Biliran',
                    'PH-BOH' => 'Bohol',
                    'PH-BUK' => 'Bukidnon',
                    'PH-BUL' => 'Bulacan',
                    'PH-CAG' => 'Cagayan',
                    'PH-CAN' => 'Camarines Norte',
                    'PH-CAS' => 'Camarines Sur',
                    'PH-CAM' => 'Camiguin',
                    'PH-CAP' => 'Capiz',
                    'PH-CAT' => 'Catanduanes',
                    'PH-CAV' => 'Cavite',
                    'PH-CEB' => 'Cebu',
                    'PH-NCO' => 'Cotabato',
                    'PH-COM' => 'Davao de Oro',
                    'PH-DAV' => 'Davao del Norte',
                    'PH-DAS' => 'Davao del Sur',
                    'PH-DVO' => 'Davao Occidental',
                    'PH-DAO' => 'Davao Oriental',
                    'PH-DIN' => 'Dinagat Islands',
                    'PH-EAS' => 'Eastern Samar',
                    'PH-GUI' => 'Guimaras',
                    'PH-IFU' => 'Ifugao',
                    'PH-ILN' => 'Ilocos Norte',
                    'PH-ILS' => 'Ilocos Sur',
                    'PH-ILI' => 'Iloilo',
                    'PH-ISA' => 'Isabela',
                    'PH-KAL' => 'Kalinga',
                    'PH-LUN' => 'La Union',
                    'PH-LAG' => 'Laguna',
                    'PH-LAN' => 'Lanao del Norte',
                    'PH-LAS' => 'Lanao del Sur',
                    'PH-LEY' => 'Leyte',
                    'PH-MAD' => 'Marinduque',
                    'PH-MAS' => 'Masbate',
                    'PH-MDC' => 'Mindoro Occidental',
                    'PH-MDR' => 'Mindoro Oriental',
                    'PH-MGN' => 'Maguindanao del Norte',
                    'PH-MGS' => 'Maguindanao del Sur',
                    'PH-MSC' => 'Misamis Occidental',
                    'PH-MSR' => 'Misamis Oriental',
                    'PH-MOU' => 'Mountain Province',
                    'PH-NEC' => 'Negros Occidental',
                    'PH-NER' => 'Negros Oriental',
                    'PH-NSA' => 'Northern Samar',
                    'PH-NUE' => 'Nueva Ecija',
                    'PH-NUV' => 'Nueva Vizcaya',
                    'PH-PLW' => 'Palawan',
                    'PH-PAM' => 'Pampanga',
                    'PH-PAN' => 'Pangasinan',
                    'PH-QUE' => 'Quezon',
                    'PH-QUI' => 'Quirino',
                    'PH-RIZ' => 'Rizal',
                    'PH-ROM' => 'Romblon',
                    'PH-WSA' => 'Samar',
                    'PH-SAR' => 'Sarangani',
                    'PH-SIG' => 'Siquijor',
                    'PH-SOR' => 'Sorsogon',
                    'PH-SCO' => 'South Cotabato',
                    'PH-SLE' => 'Southern Leyte',
                    'PH-SUK' => 'Sultan Kudarat',
                    'PH-SLU' => 'Sulu',
                    'PH-SUN' => 'Surigao del Norte',
                    'PH-SUR' => 'Surigao del Sur',
                    'PH-TAR' => 'Tarlac',
                    'PH-TAW' => 'Tawi-Tawi',
                    'PH-ZMB' => 'Zambales',
                    'PH-ZAN' => 'Zamboanga del Norte',
                    'PH-ZAS' => 'Zamboanga del Sur',
                    'PH-ZSI' => 'Zamboanga Sibugay',
                ],
                'TH' => [
                    'TH-10' => 'Bangkok กรุงเทพมหานคร',
                    'TH-11' => 'Samut Prakan สมุทรปราการ',
                    'TH-12' => 'Nonthaburi นนทบุรี',
                    'TH-13' => 'Pathum Thani ปทุมธานี',
                    'TH-14' => 'Ayutthaya อยุธยา',
                    'TH-15' => 'Ang Thong อ่างทอง',
                    'TH-16' => 'Lop Buri ลพบุรี',
                    'TH-17' => 'Sing Buri สิงห์บุรี',
                    'TH-18' => 'Chai Nat ชัยนาท',
                    'TH-19' => 'Saraburi สระบุรี',
                    'TH-20' => 'Chon Buri ชลบุรี',
                    'TH-21' => 'Rayong ระยอง',
                    'TH-22' => 'Chanthaburi จันทบุรี',
                    'TH-23' => 'Trat ตราด',
                    'TH-24' => 'Chachoengsao ฉะเชิงเทรา',
                    'TH-25' => 'Prachin Buri ปราจีนบุรี',
                    'TH-26' => 'Nakhon Nayok นครนายก',
                    'TH-27' => 'Sa Kaeo สระแก้ว',
                    'TH-30' => 'Nakhon Ratchasima นครราชสีมา',
                    'TH-31' => 'Buri Ram บุรีรัมย์',
                    'TH-32' => 'Surin สุรินทร์',
                    'TH-33' => 'Si Sa Ket ศรีสะเกษ',
                    'TH-34' => 'Ubon Ratchathani อุบลราชธานี',
                    'TH-35' => 'Yasothon ยโสธร',
                    'TH-36' => 'Chaiyaphum ชัยภูมิ',
                    'TH-37' => 'Amnat Charoen อำนาจเจริญ',
                    'TH-38' => 'Bueng Kan บึงกาฬ',
                    'TH-39' => 'Nong Bua Lam Phu หนองบัวลำภู',
                    'TH-40' => 'Khon Kaen ขอนแก่น',
                    'TH-41' => 'Udon Thani อุดรธานี',
                    'TH-42' => 'Loei เลย',
                    'TH-43' => 'Nong Khai หนองคาย',
                    'TH-44' => 'Maha Sarakham มหาสารคาม',
                    'TH-45' => 'Roi Et ร้อยเอ็ด',
                    'TH-46' => 'Kalasin กาฬสินธุ์',
                    'TH-47' => 'Sakon Nakhon สกลนคร',
                    'TH-48' => 'Nakhon Phanom นครพนม',
                    'TH-49' => 'Mukdahan มุกดาหาร',
                    'TH-50' => 'Chiang Mai เชียงใหม่',
                    'TH-51' => 'Lamphun ลำพูน',
                    'TH-52' => 'Lampang ลำปาง',
                    'TH-53' => 'Uttaradit อุตรดิตถ์',
                    'TH-54' => 'Phrae แพร่',
                    'TH-55' => 'Nan น่าน',
                    'TH-56' => 'Phayao พะเยา',
                    'TH-57' => 'Chiang Rai เชียงราย',
                    'TH-58' => 'Mae Hong Son แม่ฮ่องสอน',
                    'TH-60' => 'Nakhon Sawan นครสวรรค์',
                    'TH-61' => 'Uthai Thani อุทัยธานี',
                    'TH-62' => 'Kamphaeng Phet กำแพงเพชร',
                    'TH-63' => 'Tak ตาก',
                    'TH-64' => 'Sukhothai สุโขทัย',
                    'TH-65' => 'Phitsanulok พิษณุโลก',
                    'TH-66' => 'Phichit พิจิตร',
                    'TH-67' => 'Phetchabun เพชรบูรณ์',
                    'TH-70' => 'Ratchaburi ราชบุรี',
                    'TH-71' => 'Kanchanaburi กาญจนบุรี',
                    'TH-72' => 'Suphan Buri สุพรรณบุรี',
                    'TH-73' => 'Nakhon Pathom นครปฐม',
                    'TH-74' => 'Samut Sakhon สมุทรสาคร',
                    'TH-75' => 'Samut Songkhram สมุทรสงคราม',
                    'TH-76' => 'Phetchaburi เพชรบุรี',
                    'TH-77' => 'Prachuap Khiri Khan ประจวบคีรีขันธ์',
                    'TH-80' => 'Nakhon Si Thammarat นครศรีธรรมราช',
                    'TH-81' => 'Krabi กระบี่',
                    'TH-82' => 'Phangnga พังงา',
                    'TH-83' => 'Phuket ภูเก็ต',
                    'TH-84' => 'Surat Thani สุราษฎร์ธานี',
                    'TH-85' => 'Ranong ระนอง',
                    'TH-86' => 'Chumphon ชุมพร',
                    'TH-90' => 'Songkhla สงขลา',
                    'TH-91' => 'Satun สตูล',
                    'TH-92' => 'Trang ตรัง',
                    'TH-93' => 'Phatthalung พัทลุง',
                    'TH-94' => 'Pattani ปัตตานี',
                    'TH-95' => 'Yala ยะลา',
                    'TH-96' => 'Narathiwat นราธิวาส',
                    'TH-S'  => 'Pattaya พัทยา'
                ],
                'TW' => [
                    'TW-CHA' => 'Changhua 彰化',
                    'TW-CYI' => 'Chiayi 嘉義',
                    'TW-CYQ' => 'Chiayi 嘉義',
                    'TW-HSZ' => 'Hsinchu 新竹',
                    'TW-HSQ' => 'Hsinchu 新竹',
                    'TW-HUA' => 'Hualien 花蓮',
                    'TW-KHH' => 'Kaohsiung 高雄',
                    'TW-KEE' => 'Keelung 基隆',
                    'TW-KIN' => 'Kinmen 金門',
                    'TW-LIE' => 'Lienchiang 連江',
                    'TW-MIA' => 'Miaoli 苗栗',
                    'TW-NAN' => 'Nantou 南投',
                    'TW-NWT' => 'New Taipei 新北',
                    'TW-PEN' => 'Penghu 澎湖',
                    'TW-PIF' => 'Pingtung 屏東',
                    'TW-TXG' => 'Taichung 臺中',
                    'TW-TNN' => 'Tainan 臺南',
                    'TW-TPE' => 'Taipei 臺北',
                    'TW-TTT' => 'Taitung 臺東',
                    'TW-TAO' => 'Taoyuan 桃園',
                    'TW-ILA' => 'Yilan 宜蘭',
                    'TW-YUN' => 'Yunlin 雲林'
                ],
                'US' => [
                    'US-AK' => 'Alaska',
                    'US-AL' => 'Alabama',
                    'US-AR' => 'Arkansas',
                    'US-AZ' => 'Arizona',
                    'US-CA' => 'California',
                    'US-CO' => 'Colorado',
                    'US-CT' => 'Connecticut',
                    'US-DC' => 'District of Columbia',
                    'US-DE' => 'Delaware',
                    'US-FL' => 'Florida',
                    'US-GA' => 'Georgia',
                    'US-HI' => 'Hawaii',
                    'US-IA' => 'Iowa',
                    'US-ID' => 'Idaho',
                    'US-IL' => 'Illinois',
                    'US-IN' => 'Indiana',
                    'US-KS' => 'Kansas',
                    'US-KY' => 'Kentucky',
                    'US-LA' => 'Louisiana',
                    'US-MA' => 'Massachusetts',
                    'US-MD' => 'Maryland',
                    'US-ME' => 'Maine',
                    'US-MI' => 'Michigan',
                    'US-MN' => 'Minnesota',
                    'US-MO' => 'Missouri',
                    'US-MS' => 'Mississippi',
                    'US-MT' => 'Montana',
                    'US-NC' => 'North Carolina',
                    'US-ND' => 'North Dakota',
                    'US-NE' => 'Nebraska',
                    'US-NH' => 'New Hampshire',
                    'US-NJ' => 'New Jersey',
                    'US-NM' => 'New Mexico',
                    'US-NV' => 'Nevada',
                    'US-NY' => 'New York',
                    'US-OH' => 'Ohio',
                    'US-OK' => 'Oklahoma',
                    'US-OR' => 'Oregon',
                    'US-PA' => 'Pennsylvania',
                    'US-RI' => 'Rhode Island',
                    'US-SC' => 'South Carolina',
                    'US-SD' => 'South Dakota',
                    'US-TN' => 'Tennessee',
                    'US-TX' => 'Texas',
                    'US-UT' => 'Utah',
                    'US-VA' => 'Virginia',
                    'US-VT' => 'Vermont',
                    'US-WA' => 'Washington',
                    'US-WI' => 'Wisconsin',
                    'US-WV' => 'West Virginia',
                    'US-WY' => 'Wyoming',
                ],
                'VN' => [
                    'VN-44' => 'An Giang',
                    'VN-43' => 'Bà Rịa - Vũng Tàu',
                    'VN-54' => 'Bắc Giang',
                    'VN-53' => 'Bắc Kạn',
                    'VN-55' => 'Bạc Liêu',
                    'VN-56' => 'Bắc Ninh',
                    'VN-50' => 'Bến Tre',
                    'VN-31' => 'Bình Định',
                    'VN-57' => 'Bình Dương',
                    'VN-58' => 'Bình Phước',
                    'VN-40' => 'Bình Thuận',
                    'VN-59' => 'Cà Mau',
                    'VN-CT' => 'Cần Thơ',
                    'VN-04' => 'Cao Bằng',
                    'VN-DN' => 'Đà Nẵng',
                    'VN-33' => 'Đắk Lắk',
                    'VN-72' => 'Đắk Nông',
                    'VN-71' => 'Điện Biên',
                    'VN-39' => 'Đồng Nai',
                    'VN-45' => 'Đồng Tháp',
                    'VN-30' => 'Gia Lai',
                    'VN-03' => 'Hà Giang',
                    'VN-63' => 'Hà Nam',
                    'VN-HN' => 'Hà Nội',
                    'VN-23' => 'Hà Tĩnh',
                    'VN-61' => 'Hải Dương',
                    'VN-HP' => 'Hải Phòng',
                    'VN-73' => 'Hậu Giang',
                    'VN-SG' => 'Hồ Chí Minh (Sài Gòn)',
                    'VN-14' => 'Hòa Bình',
                    'VN-66' => 'Hưng Yên',
                    'VN-34' => 'Khánh Hòa',
                    'VN-47' => 'Kiến Giang',
                    'VN-28' => 'Kon Tum',
                    'VN-01' => 'Lai Châu',
                    'VN-35' => 'Lâm Đồng',
                    'VN-09' => 'Lạng Sơn',
                    'VN-02' => 'Lào Cai',
                    'VN-41' => 'Long An',
                    'VN-67' => 'Nam Định',
                    'VN-22' => 'Nghệ An',
                    'VN-18' => 'Ninh Bình',
                    'VN-36' => 'Ninh Thuận',
                    'VN-68' => 'Phú Thọ',
                    'VN-32' => 'Phú Yên',
                    'VN-24' => 'Quảng Bình',
                    'VN-27' => 'Quảng Nam',
                    'VN-29' => 'Quảng Ngãi',
                    'VN-13' => 'Quảng Ninh',
                    'VN-25' => 'Quảng Trị',
                    'VN-52' => 'Sóc Trăng',
                    'VN-05' => 'Sơn La',
                    'VN-37' => 'Tây Ninh',
                    'VN-20' => 'Thái Bình',
                    'VN-69' => 'Thái Nguyên',
                    'VN-21' => 'Thanh Hóa',
                    'VN-26' => 'Thừa Thiên-Huế',
                    'VN-46' => 'Tiền Giang',
                    'VN-51' => 'Trà Vinh',
                    'VN-07' => 'Tuyên Quang',
                    'VN-49' => 'Vĩnh Long',
                    'VN-70' => 'Vĩnh Phúc',
                    'VN-06' => 'Yên Bái',
                ]
            ],
        ];
    }
}
if (!function_exists('get_country_common_name')) {
    /**
     * Get country common name
     * @param string $country_code
     * @return string
     */
    function get_country_common_name(string $country_code): string
    {
        $countries = get_country_codes();
        return $countries['countries'][$country_code]['common_name'] ?? $country_code;
    }
}
if (!function_exists('get_currency_codes')) {
    /**
     * Get ISO4217 currency codes
     * @return array
     */
    function get_currency_codes(): array
    {
        return [
            'AED' => [
                'currency_name'  => 'United Arab Emirates dirham',
                'decimal_places' => '2'
            ],
            'AFN' => [
                'currency_name'  => 'Afghan afghani',
                'decimal_places' => '2'
            ],
            'ALL' => [
                'currency_name'  => 'Albanian lek',
                'decimal_places' => '2'
            ],
            'AMD' => [
                'currency_name'  => 'Armenian dram',
                'decimal_places' => '2'
            ],
            'ANG' => [
                'currency_name'  => 'Netherlands Antillean guilder',
                'decimal_places' => '2'
            ],
            'AOA' => [
                'currency_name'  => 'Angolan kwanza',
                'decimal_places' => '2'
            ],
            'ARS' => [
                'currency_name'  => 'Argentine peso',
                'decimal_places' => '2'
            ],
            'AUD' => [
                'currency_name'  => 'Australian dollar',
                'decimal_places' => '2'
            ],
            'AWG' => [
                'currency_name'  => 'Aruban florin',
                'decimal_places' => '2'
            ],
            'AZN' => [
                'currency_name'  => 'Azerbaijani manat',
                'decimal_places' => '2'
            ],
            'BAM' => [
                'currency_name'  => 'Bosnia and Herzegovina convertible mark',
                'decimal_places' => '2'
            ],
            'BBD' => [
                'currency_name'  => 'Barbados dollar',
                'decimal_places' => '2'
            ],
            'BDT' => [
                'currency_name'  => 'Bangladeshi taka',
                'decimal_places' => '2'
            ],
            'BGN' => [
                'currency_name'  => 'Bulgarian lev',
                'decimal_places' => '2'
            ],
            'BHD' => [
                'currency_name'  => 'Bahraini dinar',
                'decimal_places' => '3'
            ],
            'BIF' => [
                'currency_name'  => 'Burundian franc',
                'decimal_places' => '0'
            ],
            'BMD' => [
                'currency_name'  => 'Bermudian dollar',
                'decimal_places' => '2'
            ],
            'BND' => [
                'currency_name'  => 'Brunei dollar',
                'decimal_places' => '2'
            ],
            'BOB' => [
                'currency_name'  => 'Boliviano',
                'decimal_places' => '2'
            ],
            'BOV' => [
                'currency_name'  => 'Bolivian Mvdol (funds code)',
                'decimal_places' => '2'
            ],
            'BRL' => [
                'currency_name'  => 'Brazilian real',
                'decimal_places' => '2'
            ],
            'BSD' => [
                'currency_name'  => 'Bahamian dollar',
                'decimal_places' => '2'
            ],
            'BTN' => [
                'currency_name'  => 'Bhutanese ngultrum',
                'decimal_places' => '2'
            ],
            'BWP' => [
                'currency_name'  => 'Botswana pula',
                'decimal_places' => '2'
            ],
            'BYN' => [
                'currency_name'  => 'Belarusian ruble',
                'decimal_places' => '2'
            ],
            'BZD' => [
                'currency_name'  => 'Belize dollar',
                'decimal_places' => '2'
            ],
            'CAD' => [
                'currency_name'  => 'Canadian dollar',
                'decimal_places' => '2'
            ],
            'CDF' => [
                'currency_name'  => 'Congolese franc',
                'decimal_places' => '2'
            ],
            'CHE' => [
                'currency_name'  => 'WIR euro',
                'decimal_places' => '2'
            ],
            'CHF' => [
                'currency_name'  => 'Swiss franc',
                'decimal_places' => '2'
            ],
            'CHW' => [
                'currency_name'  => 'WIR franc',
                'decimal_places' => '2'
            ],
            'CLP' => [
                'currency_name'  => 'Chilean peso',
                'decimal_places' => '0'
            ],
            'CNY' => [
                'currency_name'  => 'Chinese yuan (Renminbi)',
                'decimal_places' => '2'
            ],
            'COP' => [
                'currency_name'  => 'Colombian peso',
                'decimal_places' => '2'
            ],
            'CRC' => [
                'currency_name'  => 'Costa Rican colon',
                'decimal_places' => '2'
            ],
            'CUP' => [
                'currency_name'  => 'Cuban peso',
                'decimal_places' => '2'
            ],
            'CVE' => [
                'currency_name'  => 'Cape Verdean escudo',
                'decimal_places' => '2'
            ],
            'CZK' => [
                'currency_name'  => 'Czech koruna',
                'decimal_places' => '2'
            ],
            'DJF' => [
                'currency_name'  => 'Djiboutian franc',
                'decimal_places' => '0'
            ],
            'DKK' => [
                'currency_name'  => 'Danish krone',
                'decimal_places' => '2'
            ],
            'DOP' => [
                'currency_name'  => 'Dominican peso',
                'decimal_places' => '2'
            ],
            'DZD' => [
                'currency_name'  => 'Algerian dinar',
                'decimal_places' => '2'
            ],
            'EGP' => [
                'currency_name'  => 'Egyptian pound',
                'decimal_places' => '2'
            ],
            'ERN' => [
                'currency_name'  => 'Eritrean nakfa',
                'decimal_places' => '2'
            ],
            'ETB' => [
                'currency_name'  => 'Ethiopian birr',
                'decimal_places' => '2'
            ],
            'EUR' => [
                'currency_name'  => 'Euro',
                'decimal_places' => '2'
            ],
            'FJD' => [
                'currency_name'  => 'Fiji dollar',
                'decimal_places' => '2'
            ],
            'FKP' => [
                'currency_name'  => 'Falkland Islands pound',
                'decimal_places' => '2'
            ],
            'GBP' => [
                'currency_name'  => 'Pound sterling',
                'decimal_places' => '2'
            ],
            'GEL' => [
                'currency_name'  => 'Georgian lari',
                'decimal_places' => '2'
            ],
            'GHS' => [
                'currency_name'  => 'Ghanaian cedi',
                'decimal_places' => '2'
            ],
            'GIP' => [
                'currency_name'  => 'Gibraltar pound',
                'decimal_places' => '2'
            ],
            'GMD' => [
                'currency_name'  => 'Gambian dalasi',
                'decimal_places' => '2'
            ],
            'GNF' => [
                'currency_name'  => 'Guinean franc',
                'decimal_places' => '0'
            ],
            'GTQ' => [
                'currency_name'  => 'Guatemalan quetzal',
                'decimal_places' => '2'
            ],
            'GYD' => [
                'currency_name'  => 'Guyanese dollar',
                'decimal_places' => '2'
            ],
            'HKD' => [
                'currency_name'  => 'Hong Kong dollar',
                'decimal_places' => '2'
            ],
            'HNL' => [
                'currency_name'  => 'Honduran lempira',
                'decimal_places' => '2'
            ],
            'HTG' => [
                'currency_name'  => 'Haitian gourde',
                'decimal_places' => '2'
            ],
            'HUF' => [
                'currency_name'  => 'Hungarian forint',
                'decimal_places' => '2'
            ],
            'IDR' => [
                'currency_name'  => 'Indonesian rupiah',
                'decimal_places' => '2'
            ],
            'ILS' => [
                'currency_name'  => 'Israeli new shekel',
                'decimal_places' => '2'
            ],
            'INR' => [
                'currency_name'  => 'Indian rupee',
                'decimal_places' => '2'
            ],
            'IQD' => [
                'currency_name'  => 'Iraqi dinar',
                'decimal_places' => '3'
            ],
            'IRR' => [
                'currency_name'  => 'Iranian rial',
                'decimal_places' => '2'
            ],
            'ISK' => [
                'currency_name'  => 'Icelandic króna',
                'decimal_places' => '0'
            ],
            'JMD' => [
                'currency_name'  => 'Jamaican dollar',
                'decimal_places' => '2'
            ],
            'JOD' => [
                'currency_name'  => 'Jordanian dinar',
                'decimal_places' => '3'
            ],
            'JPY' => [
                'currency_name'  => 'Japanese yen',
                'decimal_places' => '0'
            ],
            'KES' => [
                'currency_name'  => 'Kenyan shilling',
                'decimal_places' => '2'
            ],
            'KGS' => [
                'currency_name'  => 'Kyrgyzstani som',
                'decimal_places' => '2'
            ],
            'KHR' => [
                'currency_name'  => 'Cambodian riel',
                'decimal_places' => '2'
            ],
            'KMF' => [
                'currency_name'  => 'Comoro franc',
                'decimal_places' => '0'
            ],
            'KPW' => [
                'currency_name'  => 'North Korean won',
                'decimal_places' => '2'
            ],
            'KRW' => [
                'currency_name'  => 'South Korean won',
                'decimal_places' => '0'
            ],
            'KWD' => [
                'currency_name'  => 'Kuwaiti dinar',
                'decimal_places' => '3'
            ],
            'KYD' => [
                'currency_name'  => 'Cayman Islands dollar',
                'decimal_places' => '2'
            ],
            'KZT' => [
                'currency_name'  => 'Kazakhstani tenge',
                'decimal_places' => '2'
            ],
            'LAK' => [
                'currency_name'  => 'Lao kip',
                'decimal_places' => '2'
            ],
            'LBP' => [
                'currency_name'  => 'Lebanese pound',
                'decimal_places' => '2'
            ],
            'LKR' => [
                'currency_name'  => 'Sri Lankan rupee',
                'decimal_places' => '2'
            ],
            'LRD' => [
                'currency_name'  => 'Liberian dollar',
                'decimal_places' => '2'
            ],
            'LSL' => [
                'currency_name'  => 'Lesotho loti',
                'decimal_places' => '2'
            ],
            'LYD' => [
                'currency_name'  => 'Libyan dinar',
                'decimal_places' => '3'
            ],
            'MAD' => [
                'currency_name'  => 'Moroccan dirham',
                'decimal_places' => '2'
            ],
            'MDL' => [
                'currency_name'  => 'Moldovan leu',
                'decimal_places' => '2'
            ],
            'MGA' => [
                'currency_name'  => 'Malagasy ariary',
                'decimal_places' => '2'
            ],
            'MKD' => [
                'currency_name'  => 'Macedonian denar',
                'decimal_places' => '2'
            ],
            'MMK' => [
                'currency_name'  => 'Myanmar kyat',
                'decimal_places' => '2'
            ],
            'MNT' => [
                'currency_name'  => 'Mongolian tögrög',
                'decimal_places' => '2'
            ],
            'MOP' => [
                'currency_name'  => 'Macanese pataca',
                'decimal_places' => '2'
            ],
            'MRU' => [
                'currency_name'  => 'Mauritanian ouguiya',
                'decimal_places' => '2'
            ],
            'MUR' => [
                'currency_name'  => 'Mauritian rupee',
                'decimal_places' => '2'
            ],
            'MVR' => [
                'currency_name'  => 'Maldivian rufiyaa',
                'decimal_places' => '2'
            ],
            'MWK' => [
                'currency_name'  => 'Malawian kwacha',
                'decimal_places' => '2'
            ],
            'MXN' => [
                'currency_name'  => 'Mexican peso',
                'decimal_places' => '2'
            ],
            'MYR' => [
                'currency_name'  => 'Malaysian ringgit',
                'decimal_places' => '2'
            ],
            'MZN' => [
                'currency_name'  => 'Mozambican metical',
                'decimal_places' => '2'
            ],
            'NAD' => [
                'currency_name'  => 'Namibian dollar',
                'decimal_places' => '2'
            ],
            'NGN' => [
                'currency_name'  => 'Nigerian naira',
                'decimal_places' => '2'
            ],
            'NIO' => [
                'currency_name'  => 'Nicaraguan córdoba',
                'decimal_places' => '2'
            ],
            'NOK' => [
                'currency_name'  => 'Norwegian krone',
                'decimal_places' => '2'
            ],
            'NPR' => [
                'currency_name'  => 'Nepalese rupee',
                'decimal_places' => '2'
            ],
            'NZD' => [
                'currency_name'  => 'New Zealand dollar',
                'decimal_places' => '2'
            ],
            'OMR' => [
                'currency_name'  => 'Omani rial',
                'decimal_places' => '3'
            ],
            'PAB' => [
                'currency_name'  => 'Panamanian balboa',
                'decimal_places' => '2'
            ],
            'PEN' => [
                'currency_name'  => 'Peruvian sol',
                'decimal_places' => '2'
            ],
            'PGK' => [
                'currency_name'  => 'Papua New Guinean kina',
                'decimal_places' => '2'
            ],
            'PHP' => [
                'currency_name'  => 'Philippine peso',
                'decimal_places' => '2'
            ],
            'PKR' => [
                'currency_name'  => 'Pakistani rupee',
                'decimal_places' => '2'
            ],
            'PLN' => [
                'currency_name'  => 'Polish złoty',
                'decimal_places' => '2'
            ],
            'PYG' => [
                'currency_name'  => 'Paraguayan guaraní',
                'decimal_places' => '0'
            ],
            'QAR' => [
                'currency_name'  => 'Qatari riyal',
                'decimal_places' => '2'
            ],
            'RON' => [
                'currency_name'  => 'Romanian leu',
                'decimal_places' => '2'
            ],
            'RSD' => [
                'currency_name'  => 'Serbian dinar',
                'decimal_places' => '2'
            ],
            'RUB' => [
                'currency_name'  => 'Russian ruble',
                'decimal_places' => '2'
            ],
            'RWF' => [
                'currency_name'  => 'Rwandan franc',
                'decimal_places' => '0'
            ],
            'SAR' => [
                'currency_name'  => 'Saudi riyal',
                'decimal_places' => '2'
            ],
            'SBD' => [
                'currency_name'  => 'Solomon Islands dollar',
                'decimal_places' => '2'
            ],
            'SCR' => [
                'currency_name'  => 'Seychelles rupee',
                'decimal_places' => '2'
            ],
            'SDG' => [
                'currency_name'  => 'Sudanese pound',
                'decimal_places' => '2'
            ],
            'SEK' => [
                'currency_name'  => 'Swedish krona',
                'decimal_places' => '2'
            ],
            'SGD' => [
                'currency_name'  => 'Singapore dollar',
                'decimal_places' => '2'
            ],
            'SHP' => [
                'currency_name'  => 'Saint Helena pound',
                'decimal_places' => '2'
            ],
            'SLE' => [
                'currency_name'  => 'Sierra Leonean leone',
                'decimal_places' => '2'
            ],
            'SOS' => [
                'currency_name'  => 'Somalian shilling',
                'decimal_places' => '2'
            ],
            'SRD' => [
                'currency_name'  => 'Surinamese dollar',
                'decimal_places' => '2'
            ],
            'SSP' => [
                'currency_name'  => 'South Sudanese pound',
                'decimal_places' => '2'
            ],
            'STN' => [
                'currency_name'  => 'São Tomé and Príncipe dobra',
                'decimal_places' => '2'
            ],
            'SVC' => [
                'currency_name'  => 'Salvadoran colón',
                'decimal_places' => '2'
            ],
            'SYP' => [
                'currency_name'  => 'Syrian pound',
                'decimal_places' => '2'
            ],
            'SZL' => [
                'currency_name'  => 'Swazi lilangeni',
                'decimal_places' => '2'
            ],
            'THB' => [
                'currency_name'  => 'Thai baht',
                'decimal_places' => '2'
            ],
            'TJS' => [
                'currency_name'  => 'Tajikistani somoni',
                'decimal_places' => '2'
            ],
            'TMT' => [
                'currency_name'  => 'Turkmenistan manat',
                'decimal_places' => '2'
            ],
            'TND' => [
                'currency_name'  => 'Tunisian dinar',
                'decimal_places' => '3'
            ],
            'TOP' => [
                'currency_name'  => 'Tongan paʻanga',
                'decimal_places' => '2'
            ],
            'TRY' => [
                'currency_name'  => 'Turkish lira',
                'decimal_places' => '2'
            ],
            'TTD' => [
                'currency_name'  => 'Trinidad and Tobago dollar',
                'decimal_places' => '2'
            ],
            'TWD' => [
                'currency_name'  => 'New Taiwan dollar',
                'decimal_places' => '2'
            ],
            'TZS' => [
                'currency_name'  => 'Tanzanian shilling',
                'decimal_places' => '2'
            ],
            'UAH' => [
                'currency_name'  => 'Ukrainian hryvnia',
                'decimal_places' => '2'
            ],
            'UGX' => [
                'currency_name'  => 'Ugandan shilling',
                'decimal_places' => '0'
            ],
            'USD' => [
                'currency_name'  => 'United States dollar',
                'decimal_places' => '2'
            ],
            'UYU' => [
                'currency_name'  => 'Uruguayan peso',
                'decimal_places' => '2'
            ],
            'UZS' => [
                'currency_name'  => 'Uzbekistani sum',
                'decimal_places' => '2'
            ],
            'VED' => [
                'currency_name'  => 'Venezuelan digital bolívar',
                'decimal_places' => '2'
            ],
            'VES' => [
                'currency_name'  => 'Venezuelan sovereign bolívar',
                'decimal_places' => '2'
            ],
            'VND' => [
                'currency_name'  => 'Vietnamese đồng',
                'decimal_places' => '0'
            ],
            'VUV' => [
                'currency_name'  => 'Vanuatu vatu',
                'decimal_places' => '0'
            ],
            'WST' => [
                'currency_name'  => 'Samoan tala',
                'decimal_places' => '2'
            ],
            'XAF' => [
                'currency_name'  => 'CFA franc BEAC',
                'decimal_places' => '0'
            ],
            'XCD' => [
                'currency_name'  => 'East Caribbean dollar',
                'decimal_places' => '2'
            ],
            'XOF' => [
                'currency_name'  => 'CFA franc BCEAO',
                'decimal_places' => '0'
            ],
            'XPF' => [
                'currency_name'  => 'CFP franc (franc Pacifique)',
                'decimal_places' => '0'
            ],
            'YER' => [
                'currency_name'  => 'Yemeni rial',
                'decimal_places' => '2'
            ],
            'ZAR' => [
                'currency_name'  => 'South African rand',
                'decimal_places' => '2'
            ],
            'ZMW' => [
                'currency_name'  => 'Zambian kwacha',
                'decimal_places' => '2'
            ],
            'ZWG' => [
                'currency_name'  => 'Zimbabwe Gold',
                'decimal_places' => '2'
            ]
        ];
    }
}
if (!function_exists('get_currency_common_name')) {
    /**
     * @param string $currency_code
     * @return string
     */
    function get_currency_common_name(string $currency_code): string
    {
        $currencies = get_currency_codes();
        return $currencies[$currency_code]['currency_name'] ?? $currency_code;
    }
}
if (!function_exists('get_tzdb')) {
    /**
     * Get IANA TZ Database Codes
     * @return array
     */
    function get_tzdb(): array
    {
        return [
            'Africa/Abidjan'                 => [
                'label'        => 'Côte d’Ivoire',
                'country_code' => 'CI'
            ],
            'Africa/Accra'                   => [
                'label'        => 'Ghana',
                'country_code' => 'GH'
            ],
            'Africa/Addis_Ababa'             => [
                'label'        => 'Ethiopia',
                'country_code' => 'ET'
            ],
            'Africa/Algiers'                 => [
                'label'        => 'Algeria',
                'country_code' => 'DZ'
            ],
            'Africa/Asmara'                  => [
                'label'        => 'Eritrea',
                'country_code' => 'ER'
            ],
            'Africa/Bamako'                  => [
                'label'        => 'Mali',
                'country_code' => 'ML'
            ],
            'Africa/Bangui'                  => [
                'label'        => 'Central African Republic',
                'country_code' => 'CF'
            ],
            'Africa/Banjul'                  => [
                'label'        => 'Gambia',
                'country_code' => 'GM'
            ],
            'Africa/Bissau'                  => [
                'label'        => 'Guinea-Bissau',
                'country_code' => 'GW'
            ],
            'Africa/Blantyre'                => [
                'label'        => 'Malawi',
                'country_code' => 'MW'
            ],
            'Africa/Brazzaville'             => [
                'label'        => 'Congo',
                'country_code' => 'CG'
            ],
            'Africa/Bujumbura'               => [
                'label'        => 'Burundi',
                'country_code' => 'BI'
            ],
            'Africa/Cairo'                   => [
                'label'        => 'Egypt',
                'country_code' => 'EG'
            ],
            'Africa/Casablanca'              => [
                'label'        => 'Morocco',
                'country_code' => 'MA'
            ],
            'Africa/Ceuta'                   => [
                'label'        => 'Spain, Ceuta',
                'country_code' => 'ES'
            ],
            'Africa/Conakry'                 => [
                'label'        => 'Guinea',
                'country_code' => 'GN'
            ],
            'Africa/Dakar'                   => [
                'label'        => 'Senegal',
                'country_code' => 'SN'
            ],
            'Africa/Dar_es_Salaam'           => [
                'label'        => 'Tanzania',
                'country_code' => 'TZ'
            ],
            'Africa/Djibouti'                => [
                'label'        => 'Djibouti',
                'country_code' => 'DJ'
            ],
            'Africa/Douala'                  => [
                'label'        => 'Cameroon',
                'country_code' => 'CM'
            ],
            'Africa/El_Aaiun'                => [
                'label'        => 'Western Sahara',
                'country_code' => 'EH'
            ],
            'Africa/Freetown'                => [
                'label'        => 'Sierra Leone',
                'country_code' => 'SL'
            ],
            'Africa/Gaborone'                => [
                'label'        => 'Botswana',
                'country_code' => 'BW'
            ],
            'Africa/Harare'                  => [
                'label'        => 'Zimbabwe',
                'country_code' => 'ZW'
            ],
            'Africa/Johannesburg'            => [
                'label'        => 'South Africa',
                'country_code' => 'ZA'
            ],
            'Africa/Juba'                    => [
                'label'        => 'South Sudan',
                'country_code' => 'SS'
            ],
            'Africa/Kampala'                 => [
                'label'        => 'Uganda',
                'country_code' => 'UG'
            ],
            'Africa/Khartoum'                => [
                'label'        => 'Sudan',
                'country_code' => 'SD'
            ],
            'Africa/Kigali'                  => [
                'label'        => 'Rwanda',
                'country_code' => 'RW'
            ],
            'Africa/Kinshasa'                => [
                'label'        => 'Congo (DRC), Kinshasa',
                'country_code' => 'CD'
            ],
            'Africa/Lagos'                   => [
                'label'        => 'Nigeria',
                'country_code' => 'NG'
            ],
            'Africa/Libreville'              => [
                'label'        => 'Gabon',
                'country_code' => 'GA'
            ],
            'Africa/Lome'                    => [
                'label'        => 'Togo',
                'country_code' => 'TG'
            ],
            'Africa/Luanda'                  => [
                'label'        => 'Angola',
                'country_code' => 'AO'
            ],
            'Africa/Lubumbashi'              => [
                'label'        => 'Congo (DRC), Lubumbashi',
                'country_code' => 'CD'
            ],
            'Africa/Lusaka'                  => [
                'label'        => 'Zambia',
                'country_code' => 'ZM'
            ],
            'Africa/Malabo'                  => [
                'label'        => 'Equatorial Guinea',
                'country_code' => 'GQ'
            ],
            'Africa/Maputo'                  => [
                'label'        => 'Mozambique',
                'country_code' => 'MZ'
            ],
            'Africa/Maseru'                  => [
                'label'        => 'Lesotho',
                'country_code' => 'LS'
            ],
            'Africa/Mbabane'                 => [
                'label'        => 'Eswatini',
                'country_code' => 'SZ'
            ],
            'Africa/Mogadishu'               => [
                'label'        => 'Somalia',
                'country_code' => 'SO'
            ],
            'Africa/Monrovia'                => [
                'label'        => 'Liberia',
                'country_code' => 'LR'
            ],
            'Africa/Nairobi'                 => [
                'label'        => 'Kenya',
                'country_code' => 'KE'
            ],
            'Africa/Ndjamena'                => [
                'label'        => 'Chad',
                'country_code' => 'TD'
            ],
            'Africa/Niamey'                  => [
                'label'        => 'Niger',
                'country_code' => 'NE'
            ],
            'Africa/Nouakchott'              => [
                'label'        => 'Mauritania',
                'country_code' => 'MR'
            ],
            'Africa/Ouagadougou'             => [
                'label'        => 'Burkina Faso',
                'country_code' => 'BF'
            ],
            'Africa/Porto-Novo'              => [
                'label'        => 'Benin',
                'country_code' => 'BJ'
            ],
            'Africa/Sao_Tome'                => [
                'label'        => 'São Tomé and Príncipe',
                'country_code' => 'ST'
            ],
            'Africa/Tripoli'                 => [
                'label'        => 'Libya',
                'country_code' => 'LY'
            ],
            'Africa/Tunis'                   => [
                'label'        => 'Tunisia',
                'country_code' => 'TN'
            ],
            'Africa/Windhoek'                => [
                'label'        => 'Namibia',
                'country_code' => 'NA'
            ],
            'America/Adak'                   => [
                'label'        => 'United States, Alaska (Adak) with DST',
                'country_code' => 'US'
            ],
            'America/Anchorage'              => [
                'label'        => 'United States, Alaska (AKT) with DST',
                'country_code' => 'US'
            ],
            'America/Anguilla'               => [
                'label'        => 'Anguilla',
                'country_code' => 'AI'
            ],
            'America/Antigua'                => [
                'label'        => 'Antigua and Barbuda',
                'country_code' => 'AG'
            ],
            'America/Argentina/Buenos_Aires' => [
                'label'        => 'Argentina',
                'country_code' => 'AR'
            ],
            'America/Aruba'                  => [
                'label'        => 'Aruba',
                'country_code' => 'AW'
            ],
            'America/Asuncion'               => [
                'label'        => 'Paraguay',
                'country_code' => 'PY'
            ],
            'America/Barbados'               => [
                'label'        => 'Barbados',
                'country_code' => 'BB'
            ],
            'America/Belize'                 => [
                'label'        => 'Belize',
                'country_code' => 'BZ'
            ],
            'America/Boa_Vista'              => [
                'label'        => 'Brazil, Amazon Time (BRT-1)',
                'country_code' => 'BR'
            ],
            'America/Bogota'                 => [
                'label'        => 'Colombia',
                'country_code' => 'CO'
            ],
            'America/Caracas'                => [
                'label'        => 'Venezuela',
                'country_code' => 'VE'
            ],
            'America/Cayenne'                => [
                'label'        => 'French Guiana',
                'country_code' => 'GF'
            ],
            'America/Cayman'                 => [
                'label'        => 'Cayman Islands',
                'country_code' => 'KY'
            ],
            'America/Chicago'                => [
                'label'        => 'United States, Central Time (CT) with DST',
                'country_code' => 'US'
            ],
            'America/Costa_Rica'             => [
                'label'        => 'Costa Rica',
                'country_code' => 'CR'
            ],
            'America/Curacao'                => [
                'label'        => 'Curaçao',
                'country_code' => 'CW'
            ],
            'America/Danmarkshavn'           => [
                'label'        => 'Greenland, Danmarkshavn (GMT)',
                'country_code' => 'GL'
            ],
            'America/Dawson'                 => [
                'label'        => 'Canada, Pacific Time (PST)',
                'country_code' => 'CA'
            ],
            'America/Denver'                 => [
                'label'        => 'United States, Mountain Time (MT) with DST',
                'country_code' => 'US'
            ],
            'America/Dominica'               => [
                'label'        => 'Dominica',
                'country_code' => 'DM'
            ],
            'America/Edmonton'               => [
                'label'        => 'Canada, Mountain Time (MT) with DST',
                'country_code' => 'CA'
            ],
            'America/El_Salvador'            => [
                'label'        => 'El Salvador',
                'country_code' => 'SV'
            ],
            'America/Grand_Turk'             => [
                'label'        => 'Turks and Caicos Islands',
                'country_code' => 'TC'
            ],
            'America/Grenada'                => [
                'label'        => 'Grenada',
                'country_code' => 'GD'
            ],
            'America/Guadeloupe'             => [
                'label'        => 'Guadeloupe',
                'country_code' => 'GP'
            ],
            'America/Guatemala'              => [
                'label'        => 'Guatemala',
                'country_code' => 'GT'
            ],
            'America/Guayaquil'              => [
                'label'        => 'Ecuador, Quito',
                'country_code' => 'EC'
            ],
            'America/Guyana'                 => [
                'label'        => 'Guyana',
                'country_code' => 'GY'
            ],
            'America/Halifax'                => [
                'label'        => 'Canada, Atlantic Time (AT) with DST',
                'country_code' => 'CA'
            ],
            'America/Havana'                 => [
                'label'        => 'Cuba',
                'country_code' => 'CU'
            ],
            'America/Jamaica'                => [
                'label'        => 'Jamaica',
                'country_code' => 'JM'
            ],
            'America/Kralendijk'             => [
                'label'        => 'Bonaire',
                'country_code' => 'BQ'
            ],
            'America/La_Paz'                 => [
                'label'        => 'Bolivia',
                'country_code' => 'BO'
            ],
            'America/Lima'                   => [
                'label'        => 'Peru',
                'country_code' => 'PE'
            ],
            'America/Los_Angeles'            => [
                'label'        => 'United States, Pacific Time (PT) with DST',
                'country_code' => 'US'
            ],
            'America/Lower_Princes'          => [
                'label'        => 'Sint Maarten',
                'country_code' => 'SX'
            ],
            'America/Managua'                => [
                'label'        => 'Nicaragua',
                'country_code' => 'NI'
            ],
            'America/Marigot'                => [
                'label'        => 'Dominica',
                'country_code' => 'DM'
            ],
            'America/Martinique'             => [
                'label'        => 'Martinique',
                'country_code' => 'MQ'
            ],
            'America/Mazatlan'               => [
                'label'        => 'Mexico, Mountain Time (MST)',
                'country_code' => 'MX'
            ],
            'America/Mexico_City'            => [
                'label'        => 'Mexico, Central Time (CT) with DST',
                'country_code' => 'MX'
            ],
            'America/Miquelon'               => [
                'label'        => 'Saint Pierre and Miquelon',
                'country_code' => 'PM'
            ],
            'America/Montevideo'             => [
                'label'        => 'Uruguay',
                'country_code' => 'UY'
            ],
            'America/Montserrat'             => [
                'label'        => 'Montserrat',
                'country_code' => 'MS'
            ],
            'America/Nassau'                 => [
                'label'        => 'The Bahamas',
                'country_code' => 'BS'
            ],
            'America/New_York'               => [
                'label'        => 'United States, Eastern Time (ET) with DST',
                'country_code' => 'US'
            ],
            'America/Noronha'                => [
                'label'        => 'Brazil, Fernando de Noronha Time (BRT+1)',
                'country_code' => 'BR'
            ],
            'America/Nuuk'                   => [
                'label'        => 'Greenland, Nuuk (WGT)',
                'country_code' => 'GL'
            ],
            'America/Panama'                 => [
                'label'        => 'Panama',
                'country_code' => 'PA'
            ],
            'America/Paramaribo'             => [
                'label'        => 'Suriname',
                'country_code' => 'SR'
            ],
            'America/Phoenix'                => [
                'label'        => 'United States and Canada, Mountain Time (MST)',
                'country_code' => 'US'
            ],
            'America/Port_of_Spain'          => [
                'label'        => 'Trinidad and Tobago',
                'country_code' => 'TT'
            ],
            'America/Port-au-Prince'         => [
                'label'        => 'Haiti',
                'country_code' => 'HT'
            ],
            'America/Puerto_Rico'            => [
                'label'        => 'Puerto Rico, Atlantic Time (AST)',
                'country_code' => 'PR'
            ],
            'America/Punta_Arenas'           => [
                'label'        => 'Chile, Magallanes and Chilean Antarctica',
                'country_code' => 'CL'
            ],
            'America/Regina'                 => [
                'label'        => 'Canada, Central Time (CST)',
                'country_code' => 'CA'
            ],
            'America/Rio_Branco'             => [
                'label'        => 'Brazil, Acre Time (BST-2)',
                'country_code' => 'BR'
            ],
            'America/Santiago'               => [
                'label'        => 'Chile, Continental',
                'country_code' => 'CL'
            ],
            'America/Santo_Domingo'          => [
                'label'        => 'Dominican Republic',
                'country_code' => 'DO'
            ],
            'America/Sao_Paulo'              => [
                'label'        => 'Brazil, Brasília Time (BRT)',
                'country_code' => 'BR'
            ],
            'America/St_Barthelemy'          => [
                'label'        => 'Saint Barthélemy',
                'country_code' => 'BL'
            ],
            'America/St_Johns'               => [
                'label'        => 'Canada, Newfoundland Time (NT) with DST',
                'country_code' => 'CA'
            ],
            'America/St_Kitts'               => [
                'label'        => 'Saint Kitts and Nevis',
                'country_code' => 'KN'
            ],
            'America/St_Lucia'               => [
                'label'        => 'Saint Lucia',
                'country_code' => 'LC'
            ],
            'America/St_Thomas'              => [
                'label'        => 'US Virgin Islands, Atlantic Time (AST)',
                'country_code' => 'VI'
            ],
            'America/St_Vincent'             => [
                'label'        => 'Saint Vincent and the Grenadines',
                'country_code' => 'VC'
            ],
            'America/Tegucigalpa'            => [
                'label'        => 'Honduras',
                'country_code' => 'HN'
            ],
            'America/Thule'                  => [
                'label'        => 'Greenland, Thule (AT)',
                'country_code' => 'GL'
            ],
            'America/Tijuana'                => [
                'label'        => 'Mexico, Pacific Time (PT) with DST',
                'country_code' => 'MX'
            ],
            'America/Toronto'                => [
                'label'        => 'Canada, Eastern Time (ET) with DST',
                'country_code' => 'CA'
            ],
            'America/Tortola'                => [
                'label'        => 'British Virgin Islands',
                'country_code' => 'VG'
            ],
            'America/Vancouver'              => [
                'label'        => 'Canada, Pacific Time (PT) with DST',
                'country_code' => 'CA'
            ],
            'America/Winnipeg'               => [
                'label'        => 'Canada, Central Time (CT) with DST',
                'country_code' => 'CA'
            ],
            'Asia/Aden'                      => [
                'label'        => 'Yemen',
                'country_code' => 'YE'
            ],
            'Asia/Almaty'                    => [
                'label'        => 'Kazakhstan',
                'country_code' => 'KZ'
            ],
            'Asia/Amman'                     => [
                'label'        => 'Jordan',
                'country_code' => 'JO'
            ],
            'Asia/Ashgabat'                  => [
                'label'        => 'Turkmenistan',
                'country_code' => 'TM'
            ],
            'Asia/Baghdad'                   => [
                'label'        => 'Iraq',
                'country_code' => 'IQ'
            ],
            'Asia/Bahrain'                   => [
                'label'        => 'Bahrain',
                'country_code' => 'BH'
            ],
            'Asia/Baku'                      => [
                'label'        => 'Azerbaijan',
                'country_code' => 'AZ'
            ],
            'Asia/Bangkok'                   => [
                'label'        => 'Thailand',
                'country_code' => 'TH'
            ],
            'Asia/Beirut'                    => [
                'label'        => 'Lebanon',
                'country_code' => 'LB'
            ],
            'Asia/Bishkek'                   => [
                'label'        => 'Kyrgyzstan',
                'country_code' => 'KG'
            ],
            'Asia/Brunei'                    => [
                'label'        => 'Brunei',
                'country_code' => 'BN'
            ],
            'Asia/Colombo'                   => [
                'label'        => 'Sri Lanka',
                'country_code' => 'LK'
            ],
            'Asia/Damascus'                  => [
                'label'        => 'Syria',
                'country_code' => 'SY'
            ],
            'Asia/Dhaka'                     => [
                'label'        => 'Bangladesh',
                'country_code' => 'BD'
            ],
            'Asia/Dili'                      => [
                'label'        => 'Timor-Leste',
                'country_code' => 'TL'
            ],
            'Asia/Dubai'                     => [
                'label'        => 'United Arab Emirates',
                'country_code' => 'AE'
            ],
            'Asia/Dushanbe'                  => [
                'label'        => 'Tajikistan',
                'country_code' => 'TJ'
            ],
            'Asia/Famagusta'                 => [
                'label'        => 'Northern Cyprus',
                'country_code' => 'CY'
            ],
            'Asia/Gaza'                      => [
                'label'        => 'Palestine',
                'country_code' => 'PS'
            ],
            'Asia/Ho_Chi_Minh'               => [
                'label'        => 'Vietnam',
                'country_code' => 'VN'
            ],
            'Asia/Hong_Kong'                 => [
                'label'        => 'Hong Kong',
                'country_code' => 'HK'
            ],
            'Asia/Hovd'                      => [
                'label'        => 'Mongolia, Hovd',
                'country_code' => 'MN'
            ],
            'Asia/Irkutsk'                   => [
                'label'        => 'Russia, Irkutsk (IRKT)',
                'country_code' => 'RU'
            ],
            'Asia/Jakarta'                   => [
                'label'        => 'Indonesia, Jakarta (WIB)',
                'country_code' => 'ID'
            ],
            'Asia/Jayapura'                  => [
                'label'        => 'Indonesia, Papua (WIT)',
                'country_code' => 'ID'
            ],
            'Asia/Jerusalem'                 => [
                'label'        => 'Israel',
                'country_code' => 'IL'
            ],
            'Asia/Kabul'                     => [
                'label'        => 'Afghanistan',
                'country_code' => 'AF'
            ],
            'Asia/Kamchatka'                 => [
                'label'        => 'Russia, Kamchatka (PETT)',
                'country_code' => 'RU'
            ],
            'Asia/Karachi'                   => [
                'label'        => 'Pakistan',
                'country_code' => 'PK'
            ],
            'Asia/Kathmandu'                 => [
                'label'        => 'Nepal',
                'country_code' => 'NP'
            ],
            'Asia/Kolkata'                   => [
                'label'        => 'India',
                'country_code' => 'IN'
            ],
            'Asia/Krasnoyarsk'               => [
                'label'        => 'Russia, Krasnoyarsk (KRAT)',
                'country_code' => 'RU'
            ],
            'Asia/Kuala_Lumpur'              => [
                'label'        => 'Malaysia',
                'country_code' => 'MY'
            ],
            'Asia/Kuwait'                    => [
                'label'        => 'Kuwait',
                'country_code' => 'KW'
            ],
            'Asia/Macau'                     => [
                'label'        => 'Macao',
                'country_code' => 'MO'
            ],
            'Asia/Magadan'                   => [
                'label'        => 'Russia, Magadan (MAGT)',
                'country_code' => 'RU'
            ],
            'Asia/Makassar'                  => [
                'label'        => 'Indonesia, Bali (WITA)',
                'country_code' => 'ID'
            ],
            'Asia/Manila'                    => [
                'label'        => 'Philippines',
                'country_code' => 'PH'
            ],
            'Asia/Muscat'                    => [
                'label'        => 'Oman',
                'country_code' => 'OM'
            ],
            'Asia/Nicosia'                   => [
                'label'        => 'Cyprus',
                'country_code' => 'CY'
            ],
            'Asia/Omsk'                      => [
                'label'        => 'Russia, Omsk (OMST)',
                'country_code' => 'RU'
            ],
            'Asia/Phnom_Penh'                => [
                'label'        => 'Cambodia',
                'country_code' => 'KH'
            ],
            'Asia/Pyongyang'                 => [
                'label'        => 'North Korea',
                'country_code' => 'KP'
            ],
            'Asia/Qatar'                     => [
                'label'        => 'Qatar',
                'country_code' => 'QA'
            ],
            'Asia/Riyadh'                    => [
                'label'        => 'Saudi Arabia',
                'country_code' => 'SA'
            ],
            'Asia/Seoul'                     => [
                'label'        => 'South Korea',
                'country_code' => 'KR'
            ],
            'Asia/Shanghai'                  => [
                'label'        => 'China (PRC)',
                'country_code' => 'CN'
            ],
            'Asia/Singapore'                 => [
                'label'        => 'Singapore',
                'country_code' => 'SG'
            ],
            'Asia/Taipei'                    => [
                'label'        => 'Taiwan (ROC)',
                'country_code' => 'TW'
            ],
            'Asia/Tashkent'                  => [
                'label'        => 'Uzbekistan',
                'country_code' => 'UZ'
            ],
            'Asia/Tbilisi'                   => [
                'label'        => 'Georgia',
                'country_code' => 'GE'
            ],
            'Asia/Tehran'                    => [
                'label'        => 'Iran',
                'country_code' => 'IR'
            ],
            'Asia/Thimphu'                   => [
                'label'        => 'Bhutan',
                'country_code' => 'BT'
            ],
            'Asia/Tokyo'                     => [
                'label'        => 'Japan',
                'country_code' => 'JP'
            ],
            'Asia/Ulaanbaatar'               => [
                'label'        => 'Mongolia, Ulaanbaatar',
                'country_code' => 'MN'
            ],
            'Asia/Vientiane'                 => [
                'label'        => 'Laos',
                'country_code' => 'LA'
            ],
            'Asia/Vladivostok'               => [
                'label'        => 'Russia, Vladivostok (VLAT)',
                'country_code' => 'RU'
            ],
            'Asia/Yakutsk'                   => [
                'label'        => 'Russia, Yakutsk (YAKT)',
                'country_code' => 'RU'
            ],
            'Asia/Yangon'                    => [
                'label'        => 'Myanmar',
                'country_code' => 'MM'
            ],
            'Asia/Yekaterinburg'             => [
                'label'        => 'Russia, Yekaterinburg (YEKT)',
                'country_code' => 'RU'
            ],
            'Asia/Yerevan'                   => [
                'label'        => 'Armenia',
                'country_code' => 'AM'
            ],
            'Atlantic/Azores'                => [
                'label'        => 'Portugal, Azores',
                'country_code' => 'PT'
            ],
            'Atlantic/Bermuda'               => [
                'label'        => 'Bermuda',
                'country_code' => 'BM'
            ],
            'Atlantic/Canary'                => [
                'label'        => 'Canary Islands',
                'country_code' => 'ES'
            ],
            'Atlantic/Cape_Verde'            => [
                'label'        => 'Cabo Verde',
                'country_code' => 'CV'
            ],
            'Atlantic/Faroe'                 => [
                'label'        => 'Faroe Islands',
                'country_code' => 'FO'
            ],
            'Atlantic/Madeira'               => [
                'label'        => 'Portugal, Madeira',
                'country_code' => 'PT'
            ],
            'Atlantic/Reykjavik'             => [
                'label'        => 'Iceland',
                'country_code' => 'IS'
            ],
            'Atlantic/South_Georgia'         => [
                'label'        => 'South Georgia and the South Sandwich Islands',
                'country_code' => 'GS'
            ],
            'Australia/Adelaide'             => [
                'label'        => 'Australia, Central Time (ACT)',
                'country_code' => 'AU'
            ],
            'Australia/Brisbane'             => [
                'label'        => 'Australia, Eastern Time (AEST) year-round',
                'country_code' => 'AU'
            ],
            'Australia/Darwin'               => [
                'label'        => 'Australia, Central Time (ACST) year-round',
                'country_code' => 'AU'
            ],
            'Australia/Eucla'                => [
                'label'        => 'Australia, Eucla Time',
                'country_code' => 'AU'
            ],
            'Australia/Lord_Howe'            => [
                'label'        => 'Lord Howe Island',
                'country_code' => 'AU'
            ],
            'Australia/Perth'                => [
                'label'        => 'Australia, Western Time (WEST) year-round',
                'country_code' => 'AU'
            ],
            'Australia/Sydney'               => [
                'label'        => 'Australia, Eastern Time (AET)',
                'country_code' => 'AU'
            ],
            'Europe/Amsterdam'               => [
                'label'        => 'Netherlands',
                'country_code' => 'NL'
            ],
            'Europe/Andorra'                 => [
                'label'        => 'Andorra',
                'country_code' => 'AD'
            ],
            'Europe/Athens'                  => [
                'label'        => 'Greece',
                'country_code' => 'GR'
            ],
            'Europe/Belgrade'                => [
                'label'        => 'Serbia',
                'country_code' => 'RS'
            ],
            'Europe/Berlin'                  => [
                'label'        => 'Germany',
                'country_code' => 'DE'
            ],
            'Europe/Bratislava'              => [
                'label'        => 'Slovakia',
                'country_code' => 'SK'
            ],
            'Europe/Brussels'                => [
                'label'        => 'Belgium',
                'country_code' => 'BE'
            ],
            'Europe/Bucharest'               => [
                'label'        => 'Romania',
                'country_code' => 'RO'
            ],
            'Europe/Budapest'                => [
                'label'        => 'Hungary',
                'country_code' => 'HU'
            ],
            'Europe/Chisinau'                => [
                'label'        => 'Moldova',
                'country_code' => 'MD'
            ],
            'Europe/Copenhagen'              => [
                'label'        => 'Denmark',
                'country_code' => 'DK'
            ],
            'Europe/Dublin'                  => [
                'label'        => 'Ireland',
                'country_code' => 'IE'
            ],
            'Europe/Gibraltar'               => [
                'label'        => 'Gibraltar',
                'country_code' => 'GI'
            ],
            'Europe/Helsinki'                => [
                'label'        => 'Finland',
                'country_code' => 'FI'
            ],
            'Europe/Istanbul'                => [
                'label'        => 'Türkiye',
                'country_code' => 'TR'
            ],
            'Europe/Kaliningrad'             => [
                'label'        => 'Russia, Kaliningrad (KALT)',
                'country_code' => 'RU'
            ],
            'Europe/Kyiv'                    => [
                'label'        => 'Ukraine',
                'country_code' => 'UA'
            ],
            'Europe/Lisbon'                  => [
                'label'        => 'Portugal',
                'country_code' => 'PT'
            ],
            'Europe/Ljubljana'               => [
                'label'        => 'Slovenia',
                'country_code' => 'SI'
            ],
            'Europe/London'                  => [
                'label'        => 'United Kingdom',
                'country_code' => 'GB'
            ],
            'Europe/Luxembourg'              => [
                'label'        => 'Luxembourg',
                'country_code' => 'LU'
            ],
            'Europe/Madrid'                  => [
                'label'        => 'Spain',
                'country_code' => 'ES'
            ],
            'Europe/Malta'                   => [
                'label'        => 'Malta',
                'country_code' => 'MT'
            ],
            'Europe/Minsk'                   => [
                'label'        => 'Belarus',
                'country_code' => 'BY'
            ],
            'Europe/Monaco'                  => [
                'label'        => 'Monaco',
                'country_code' => 'MC'
            ],
            'Europe/Moscow'                  => [
                'label'        => 'Russia, Moscow (MSK)',
                'country_code' => 'RU'
            ],
            'Europe/Oslo'                    => [
                'label'        => 'Norway',
                'country_code' => 'NO'
            ],
            'Europe/Paris'                   => [
                'label'        => 'France',
                'country_code' => 'FR'
            ],
            'Europe/Podgorica'               => [
                'label'        => 'Montenegro',
                'country_code' => 'ME'
            ],
            'Europe/Prague'                  => [
                'label'        => 'Czechia',
                'country_code' => 'CZ'
            ],
            'Europe/Riga'                    => [
                'label'        => 'Latvia',
                'country_code' => 'LV'
            ],
            'Europe/Rome'                    => [
                'label'        => 'Italy',
                'country_code' => 'IT'
            ],
            'Europe/Samara'                  => [
                'label'        => 'Russia, Samara (SAMT)',
                'country_code' => 'RU'
            ],
            'Europe/San_Marino'              => [
                'label'        => 'San Marino',
                'country_code' => 'SM'
            ],
            'Europe/Sarajevo'                => [
                'label'        => 'Bosnia and Herzegovina',
                'country_code' => 'BA'
            ],
            'Europe/Skopje'                  => [
                'label'        => 'North Macedonia',
                'country_code' => 'MK'
            ],
            'Europe/Sofia'                   => [
                'label'        => 'Bulgaria',
                'country_code' => 'BG'
            ],
            'Europe/Stockholm'               => [
                'label'        => 'Sweden',
                'country_code' => 'SE'
            ],
            'Europe/Tallinn'                 => [
                'label'        => 'Estonia',
                'country_code' => 'EE'
            ],
            'Europe/Tirane'                  => [
                'label'        => 'Albania',
                'country_code' => 'AL'
            ],
            'Europe/Vaduz'                   => [
                'label'        => 'Liechtenstein',
                'country_code' => 'LI'
            ],
            'Europe/Vienna'                  => [
                'label'        => 'Austria',
                'country_code' => 'AT'
            ],
            'Europe/Vilnius'                 => [
                'label'        => 'Lithuania',
                'country_code' => 'LT'
            ],
            'Europe/Warsaw'                  => [
                'label'        => 'Poland',
                'country_code' => 'PL'
            ],
            'Europe/Zagreb'                  => [
                'label'        => 'Croatia',
                'country_code' => 'HR'
            ],
            'Europe/Zurich'                  => [
                'label'        => 'Switzerland',
                'country_code' => 'CH'
            ],
            'Indian/Antananarivo'            => [
                'label'        => 'Madagascar',
                'country_code' => 'MG'
            ],
            'Indian/Chagos'                  => [
                'label'        => 'Chagos',
                'country_code' => 'GB'
            ],
            'Indian/Christmas'               => [
                'label'        => 'Christmas Island',
                'country_code' => 'CX'
            ],
            'Indian/Cocos'                   => [
                'label'        => 'Cocos Islands',
                'country_code' => 'CC'
            ],
            'Indian/Kerguelen'               => [
                'label'        => 'Kerguelen Islands',
                'country_code' => 'FR'
            ],
            'Indian/Mahe'                    => [
                'label'        => 'Seychelles',
                'country_code' => 'SC'
            ],
            'Indian/Maldives'                => [
                'label'        => 'Maldives',
                'country_code' => 'MV'
            ],
            'Indian/Mauritius'               => [
                'label'        => 'Mauritius',
                'country_code' => 'MU'
            ],
            'Indian/Mayotte'                 => [
                'label'        => 'Mayotte',
                'country_code' => 'YT'
            ],
            'Indian/Reunion'                 => [
                'label'        => 'Réunion Island',
                'country_code' => 'RE'
            ],
            'Pacific/Apia'                   => [
                'label'        => 'Samoa',
                'country_code' => 'WS'
            ],
            'Pacific/Auckland'               => [
                'label'        => 'New Zealand',
                'country_code' => 'NZ'
            ],
            'Pacific/Bougainville'           => [
                'label'        => 'Papua New Guinea, Bougainville (BST)',
                'country_code' => 'PG'
            ],
            'Pacific/Chatham'                => [
                'label'        => 'New Zealand, Chatham Island',
                'country_code' => 'NZ'
            ],
            'Pacific/Chuuk'                  => [
                'label'        => 'Micronesia, Chuuk (CHUT)',
                'country_code' => 'FM'
            ],
            'Pacific/Easter'                 => [
                'label'        => 'Chile, Easter Island',
                'country_code' => 'CL'
            ],
            'Pacific/Efate'                  => [
                'label'        => 'Vanuatu',
                'country_code' => 'VU'
            ],
            'Pacific/Fakaofo'                => [
                'label'        => 'Tokelau',
                'country_code' => 'TK'
            ],
            'Pacific/Fiji'                   => [
                'label'        => 'Fiji',
                'country_code' => 'FJ'
            ],
            'Pacific/Funafuti'               => [
                'label'        => 'Tuvalu',
                'country_code' => 'TV'
            ],
            'Pacific/Galapagos'              => [
                'label'        => 'Ecuador, Galápagos Islands',
                'country_code' => 'EC'
            ],
            'Pacific/Gambier'                => [
                'label'        => 'French Polynesia, Gambier Islands',
                'country_code' => 'PF'
            ],
            'Pacific/Guadalcanal'            => [
                'label'        => 'Solomon Islands',
                'country_code' => 'SB'
            ],
            'Pacific/Guam'                   => [
                'label'        => 'Guam',
                'country_code' => 'GU'
            ],
            'Pacific/Honolulu'               => [
                'label'        => 'United States, Hawaii',
                'country_code' => 'US'
            ],
            'Pacific/Kanton'                 => [
                'label'        => 'Kiribati, Kanton (PHOT)',
                'country_code' => 'KI'
            ],
            'Pacific/Kiritimati'             => [
                'label'        => 'Kiribati, Kiritimati (LINT)',
                'country_code' => 'KI'
            ],
            'Pacific/Majuro'                 => [
                'label'        => 'Marshall Islands',
                'country_code' => 'MH'
            ],
            'Pacific/Marquesas'              => [
                'label'        => 'French Polynesia, Marquesas Islands',
                'country_code' => 'PF'
            ],
            'Pacific/Midway'                 => [
                'label'        => 'Midway Atoll',
                'country_code' => 'US'
            ],
            'Pacific/Nauru'                  => [
                'label'        => 'Nauru',
                'country_code' => 'NR'
            ],
            'Pacific/Niue'                   => [
                'label'        => 'Niue',
                'country_code' => 'NU'
            ],
            'Pacific/Norfolk'                => [
                'label'        => 'Norfolk Island',
                'country_code' => 'NF'
            ],
            'Pacific/Noumea'                 => [
                'label'        => 'New Caledonia',
                'country_code' => 'NC'
            ],
            'Pacific/Pago_Pago'              => [
                'label'        => 'American Samoa',
                'country_code' => 'AS'
            ],
            'Pacific/Palau'                  => [
                'label'        => 'Palau',
                'country_code' => 'PW'
            ],
            'Pacific/Pitcairn'               => [
                'label'        => 'Pitcairn Islands',
                'country_code' => 'PN'
            ],
            'Pacific/Pohnpei'                => [
                'label'        => 'Micronesia, Pohnpei (PONT)',
                'country_code' => 'FM'
            ],
            'Pacific/Port_Moresby'           => [
                'label'        => 'Papua New Guinea, Port Moresby (PGT)',
                'country_code' => 'PG'
            ],
            'Pacific/Rarotonga'              => [
                'label'        => 'Cook Islands',
                'country_code' => 'CK'
            ],
            'Pacific/Saipan'                 => [
                'label'        => 'Northern Mariana Islands',
                'country_code' => 'MP'
            ],
            'Pacific/Tahiti'                 => [
                'label'        => 'French Polynesia, Tahiti',
                'country_code' => 'PF'
            ],
            'Pacific/Tarawa'                 => [
                'label'        => 'Kiribati, Tarawa (GILT)',
                'country_code' => 'KI'
            ],
            'Pacific/Tongatapu'              => [
                'label'        => 'Tonga',
                'country_code' => 'TO'
            ],
            'Pacific/Wake'                   => [
                'label'        => 'Wake Island',
                'country_code' => 'US'
            ],
            'Pacific/Wallis'                 => [
                'label'        => 'Wallis and Futuna',
                'country_code' => 'WF'
            ],
            'UTC'                            => [
                'label'        => 'UTC',
                'country_code' => ''
            ],
        ];
    }
}
if (!function_exists('get_available_locales')) {
    /**
     * @param string $format (optional) either long or short
     * @return array
     */
    function get_available_locales(string $format = 'short'): array
    {
        if ('long' == $format) {
            return [
                'en' => 'English (US)',
                'th' => 'ภาษาไทย'
            ];
        }
        return [
            'en' => 'E',
            'th' => 'ท'
        ];
    }
}
if (!function_exists('get_telephone_country_calling_code')) {
    /**
     * @return array
     */
    function get_telephone_country_calling_code(): array
    {
        return [
            [
                'code'       => '+1',
                'code_label' => '+1',
                'label'      => 'United States/Canada'
            ],
            [
                'code'       => '+1242',
                'code_label' => '+1 (242) ',
                'label'      => 'Bahamas'
            ],
            [
                'code'       => '+1246',
                'code_label' => '+1 (246) ',
                'label'      => 'Barbados'
            ],
            [
                'code'       => '+1264',
                'code_label' => '+1 (264) ',
                'label'      => 'Anguilla'
            ],
            [
                'code'       => '+1268',
                'code_label' => '+1 (268) ',
                'label'      => 'Antigua and Barbuda'
            ],
            [
                'code'       => '+1284',
                'code_label' => '+1 (284) ',
                'label'      => 'Virgin Islands (British)'
            ],
            [
                'code'       => '+1340',
                'code_label' => '+1 (340) ',
                'label'      => 'Virgin Islands (U.S.)'
            ],
            [
                'code'       => '+1345',
                'code_label' => '+1 (345) ',
                'label'      => 'Cayman Islands'
            ],
            [
                'code'       => '+1441',
                'code_label' => '+1 (441) ',
                'label'      => 'Bermuda'
            ],
            [
                'code'       => '+1473',
                'code_label' => '+1 (473) ',
                'label'      => 'Grenada'
            ],
            [
                'code'       => '+1649',
                'code_label' => '+1 (649) ',
                'label'      => 'Turks and Caicos Islands'
            ],
            [
                'code'       => '+1658',
                'code_label' => '+1 (658) ',
                'label'      => 'Jamaica'
            ],
            [
                'code'       => '+1664',
                'code_label' => '+1 (664) ',
                'label'      => 'Montserrat'
            ],
            [
                'code'       => '+1670',
                'code_label' => '+1 (670) ',
                'label'      => 'Northern Mariana Islands'
            ],
            [
                'code'       => '+1671',
                'code_label' => '+1 (671) ',
                'label'      => 'Guam'
            ],
            [
                'code'       => '+1684',
                'code_label' => '+1 (684) ',
                'label'      => 'American Samoa'
            ],
            [
                'code'       => '+1721',
                'code_label' => '+1 (721) ',
                'label'      => 'Sint Maarten'
            ],
            [
                'code'       => '+1758',
                'code_label' => '+1 (758) ',
                'label'      => 'Saint Lucia'
            ],
            [
                'code'       => '+1767',
                'code_label' => '+1 (767) ',
                'label'      => 'Dominica'
            ],
            [
                'code'       => '+1784',
                'code_label' => '+1 (784) ',
                'label'      => 'Saint Vincent and the Grenadines'
            ],
            [
                'code'       => '+1787',
                'code_label' => '+1 (787) ',
                'label'      => 'Puerto Rico'
            ],
            [
                'code'       => '+1809',
                'code_label' => '+1 (809) ',
                'label'      => 'Dominican Republic'
            ],
            [
                'code'       => '+1829',
                'code_label' => '+1 (829) ',
                'label'      => 'Dominican Republic'
            ],
            [
                'code'       => '+1849',
                'code_label' => '+1 (849) ',
                'label'      => 'Dominican Republic'
            ],
            [
                'code'       => '+1868',
                'code_label' => '+1 (868) ',
                'label'      => 'Trinidad and Tobago'
            ],
            [
                'code'       => '+1869',
                'code_label' => '+1 (869) ',
                'label'      => 'Saint Kitts and Nevis'
            ],
            [
                'code'       => '+1876',
                'code_label' => '+1 (876) ',
                'label'      => 'Jamaica'
            ],
            [
                'code'       => '+1939',
                'code_label' => '+1 (939) ',
                'label'      => 'Puerto Rico'
            ],
            [
                'code'       => '+20',
                'code_label' => '+20',
                'label'      => 'Egypt'
            ],
            [
                'code'       => '+211',
                'code_label' => '+211',
                'label'      => 'South Sudan'
            ],
            [
                'code'       => '+212',
                'code_label' => '+212',
                'label'      => 'Morocco'
            ],
            [
                'code'       => '+212',
                'code_label' => '+212',
                'label'      => 'Western Sahara'
            ],
            [
                'code'       => '+213',
                'code_label' => '+213',
                'label'      => 'Algeria'
            ],
            [
                'code'       => '+216',
                'code_label' => '+216',
                'label'      => 'Tunisia'
            ],
            [
                'code'       => '+218',
                'code_label' => '+218',
                'label'      => 'Libya'
            ],
            [
                'code'       => '+220',
                'code_label' => '+220',
                'label'      => 'Gambia'
            ],
            [
                'code'       => '+221',
                'code_label' => '+221',
                'label'      => 'Senegal'
            ],
            [
                'code'       => '+222',
                'code_label' => '+222',
                'label'      => 'Mauritania'
            ],
            [
                'code'       => '+223',
                'code_label' => '+223',
                'label'      => 'Mali'
            ],
            [
                'code'       => '+224',
                'code_label' => '+224',
                'label'      => 'Guinea'
            ],
            [
                'code'       => '+225',
                'code_label' => '+225',
                'label'      => 'Côte d’Ivoire (Ivory Coast)'
            ],
            [
                'code'       => '+226',
                'code_label' => '+226',
                'label'      => 'Burkina Faso'
            ],
            [
                'code'       => '+227',
                'code_label' => '+227',
                'label'      => 'Niger'
            ],
            [
                'code'       => '+228',
                'code_label' => '+228',
                'label'      => 'Togo'
            ],
            [
                'code'       => '+229',
                'code_label' => '+229',
                'label'      => 'Benin'
            ],
            [
                'code'       => '+230',
                'code_label' => '+230',
                'label'      => 'Mauritius'
            ],
            [
                'code'       => '+231',
                'code_label' => '+231',
                'label'      => 'Liberia'
            ],
            [
                'code'       => '+232',
                'code_label' => '+232',
                'label'      => 'Sierra Leone'
            ],
            [
                'code'       => '+233',
                'code_label' => '+233',
                'label'      => 'Ghana'
            ],
            [
                'code'       => '+234',
                'code_label' => '+234',
                'label'      => 'Nigeria'
            ],
            [
                'code'       => '+235',
                'code_label' => '+235',
                'label'      => 'Chad'
            ],
            [
                'code'       => '+236',
                'code_label' => '+236',
                'label'      => 'Central African Republic'
            ],
            [
                'code'       => '+237',
                'code_label' => '+237',
                'label'      => 'Cameroon'
            ],
            [
                'code'       => '+238',
                'code_label' => '+238',
                'label'      => 'Cabo Verde (Cape Verde)'
            ],
            [
                'code'       => '+239',
                'code_label' => '+239',
                'label'      => 'São Tomé and Príncipe'
            ],
            [
                'code'       => '+240',
                'code_label' => '+240',
                'label'      => 'Equatorial Guinea'
            ],
            [
                'code'       => '+241',
                'code_label' => '+241',
                'label'      => 'Gabon'
            ],
            [
                'code'       => '+242',
                'code_label' => '+242',
                'label'      => 'Congo'
            ],
            [
                'code'       => '+243',
                'code_label' => '+243',
                'label'      => 'Congo (DRC)'
            ],
            [
                'code'       => '+244',
                'code_label' => '+244',
                'label'      => 'Angola'
            ],
            [
                'code'       => '+245',
                'code_label' => '+245',
                'label'      => 'Guinea-Bissau'
            ],
            [
                'code'       => '+246',
                'code_label' => '+246',
                'label'      => 'British Indian Ocean Territory'
            ],
            [
                'code'       => '+247',
                'code_label' => '+247',
                'label'      => 'Ascension Island'
            ],
            [
                'code'       => '+248',
                'code_label' => '+248',
                'label'      => 'Seychelles'
            ],
            [
                'code'       => '+249',
                'code_label' => '+249',
                'label'      => 'Sudan'
            ],
            [
                'code'       => '+250',
                'code_label' => '+250',
                'label'      => 'Rwanda'
            ],
            [
                'code'       => '+251',
                'code_label' => '+251',
                'label'      => 'Ethiopia'
            ],
            [
                'code'       => '+252',
                'code_label' => '+252',
                'label'      => 'Somalia'
            ],
            [
                'code'       => '+252',
                'code_label' => '+252',
                'label'      => 'Somaliland'
            ],
            [
                'code'       => '+253',
                'code_label' => '+253',
                'label'      => 'Djibouti'
            ],
            [
                'code'       => '+254',
                'code_label' => '+254',
                'label'      => 'Kenya'
            ],
            [
                'code'       => '+255',
                'code_label' => '+255',
                'label'      => 'Tanzania'
            ],
            [
                'code'       => '+256',
                'code_label' => '+256',
                'label'      => 'Uganda'
            ],
            [
                'code'       => '+257',
                'code_label' => '+257',
                'label'      => 'Burundi'
            ],
            [
                'code'       => '+258',
                'code_label' => '+258',
                'label'      => 'Mozambique'
            ],
            [
                'code'       => '+260',
                'code_label' => '+260',
                'label'      => 'Zambia'
            ],
            [
                'code'       => '+261',
                'code_label' => '+261',
                'label'      => 'Madagascar'
            ],
            [
                'code'       => '+262',
                'code_label' => '+262',
                'label'      => 'Réunion'
            ],
            [
                'code'       => '+263',
                'code_label' => '+263',
                'label'      => 'Zimbabwe'
            ],
            [
                'code'       => '+264',
                'code_label' => '+264',
                'label'      => 'Namibia'
            ],
            [
                'code'       => '+265',
                'code_label' => '+265',
                'label'      => 'Malawi'
            ],
            [
                'code'       => '+266',
                'code_label' => '+266',
                'label'      => 'Lesotho'
            ],
            [
                'code'       => '+267',
                'code_label' => '+267',
                'label'      => 'Botswana'
            ],
            [
                'code'       => '+268',
                'code_label' => '+268',
                'label'      => 'Eswatini'
            ],
            [
                'code'       => '+269',
                'code_label' => '+269',
                'label'      => 'Comoros'
            ],
            [
                'code'       => '+27',
                'code_label' => '+27',
                'label'      => 'South Africa'
            ],
            [
                'code'       => '+290',
                'code_label' => '+290',
                'label'      => 'Saint Helena'
            ],
            [
                'code'       => '+291',
                'code_label' => '+291',
                'label'      => 'Eritrea'
            ],
            [
                'code'       => '+297',
                'code_label' => '+297',
                'label'      => 'Aruba'
            ],
            [
                'code'       => '+298',
                'code_label' => '+298',
                'label'      => 'Faroe Islands'
            ],
            [
                'code'       => '+299',
                'code_label' => '+299',
                'label'      => 'Greenland'
            ],
            [
                'code'       => '+30',
                'code_label' => '+30',
                'label'      => 'Greece'
            ],
            [
                'code'       => '+31',
                'code_label' => '+31',
                'label'      => 'Netherlands'
            ],
            [
                'code'       => '+32',
                'code_label' => '+32',
                'label'      => 'Belgium'
            ],
            [
                'code'       => '+33',
                'code_label' => '+33',
                'label'      => 'France'
            ],
            [
                'code'       => '+34',
                'code_label' => '+34',
                'label'      => 'Spain'
            ],
            [
                'code'       => '+350',
                'code_label' => '+350',
                'label'      => 'Gibraltar'
            ],
            [
                'code'       => '+351',
                'code_label' => '+351',
                'label'      => 'Portugal'
            ],
            [
                'code'       => '+352',
                'code_label' => '+352',
                'label'      => 'Luxembourg'
            ],
            [
                'code'       => '+353',
                'code_label' => '+353',
                'label'      => 'Ireland'
            ],
            [
                'code'       => '+354',
                'code_label' => '+354',
                'label'      => 'Iceland'
            ],
            [
                'code'       => '+355',
                'code_label' => '+355',
                'label'      => 'Albania'
            ],
            [
                'code'       => '+356',
                'code_label' => '+356',
                'label'      => 'Malta'
            ],
            [
                'code'       => '+357',
                'code_label' => '+357',
                'label'      => 'Cyprus'
            ],
            [
                'code'       => '+357',
                'code_label' => '+357',
                'label'      => 'Akrotiri and Dhekelia'
            ],
            [
                'code'       => '+358',
                'code_label' => '+358',
                'label'      => 'Finland'
            ],
            [
                'code'       => '+359',
                'code_label' => '+359',
                'label'      => 'Bulgaria'
            ],
            [
                'code'       => '+36',
                'code_label' => '+36',
                'label'      => 'Hungary'
            ],
            [
                'code'       => '+370',
                'code_label' => '+370',
                'label'      => 'Lithuania'
            ],
            [
                'code'       => '+371',
                'code_label' => '+371',
                'label'      => 'Latvia'
            ],
            [
                'code'       => '+372',
                'code_label' => '+372',
                'label'      => 'Estonia'
            ],
            [
                'code'       => '+373',
                'code_label' => '+373',
                'label'      => 'Moldova'
            ],
            [
                'code'       => '+374',
                'code_label' => '+374',
                'label'      => 'Armenia'
            ],
            [
                'code'       => '+375',
                'code_label' => '+375',
                'label'      => 'Belarus'
            ],
            [
                'code'       => '+376',
                'code_label' => '+376',
                'label'      => 'Andorra'
            ],
            [
                'code'       => '+377',
                'code_label' => '+377',
                'label'      => 'Monaco'
            ],
            [
                'code'       => '+378',
                'code_label' => '+378',
                'label'      => 'San Marino'
            ],
            [
                'code'       => '+380',
                'code_label' => '+380',
                'label'      => 'Ukraine'
            ],
            [
                'code'       => '+381',
                'code_label' => '+381',
                'label'      => 'Serbia'
            ],
            [
                'code'       => '+382',
                'code_label' => '+382',
                'label'      => 'Montenegro'
            ],
            [
                'code'       => '+383',
                'code_label' => '+383',
                'label'      => 'Kosovo'
            ],
            [
                'code'       => '+385',
                'code_label' => '+385',
                'label'      => 'Croatia'
            ],
            [
                'code'       => '+386',
                'code_label' => '+386',
                'label'      => 'Slovenia'
            ],
            [
                'code'       => '+387',
                'code_label' => '+387',
                'label'      => 'Bosnia and Herzegovina'
            ],
            [
                'code'       => '+389',
                'code_label' => '+389',
                'label'      => 'North Macedonia'
            ],
            [
                'code'       => '+39',
                'code_label' => '+39',
                'label'      => 'Italy'
            ],
            [
                'code'       => '+390549',
                'code_label' => '+390549',
                'label'      => 'San Marino'
            ],
            [
                'code'       => '+3906698',
                'code_label' => '+3906698',
                'label'      => 'Holy See (Vatican City)'
            ],
            [
                'code'       => '+40',
                'code_label' => '+40',
                'label'      => 'Romania'
            ],
            [
                'code'       => '+41',
                'code_label' => '+41',
                'label'      => 'Switzerland'
            ],
            [
                'code'       => '+420',
                'code_label' => '+420',
                'label'      => 'Czechia'
            ],
            [
                'code'       => '+421',
                'code_label' => '+421',
                'label'      => 'Slovakia'
            ],
            [
                'code'       => '+423',
                'code_label' => '+423',
                'label'      => 'Liechtenstein'
            ],
            [
                'code'       => '+43',
                'code_label' => '+43',
                'label'      => 'Austria'
            ],
            [
                'code'       => '+44',
                'code_label' => '+44',
                'label'      => 'United Kingdom'
            ],
            [
                'code'       => '+45',
                'code_label' => '+45',
                'label'      => 'Denmark'
            ],
            [
                'code'       => '+46',
                'code_label' => '+46',
                'label'      => 'Sweden'
            ],
            [
                'code'       => '+47',
                'code_label' => '+47',
                'label'      => 'Norway'
            ],
            [
                'code'       => '+48',
                'code_label' => '+48',
                'label'      => 'Poland'
            ],
            [
                'code'       => '+49',
                'code_label' => '+49',
                'label'      => 'Germany'
            ],
            [
                'code'       => '+500',
                'code_label' => '+500',
                'label'      => 'Falkland Islands'
            ],
            [
                'code'       => '+500',
                'code_label' => '+500',
                'label'      => 'South Georgia and the South Sandwich Islands'
            ],
            [
                'code'       => '+501',
                'code_label' => '+501',
                'label'      => 'Belize'
            ],
            [
                'code'       => '+502',
                'code_label' => '+502',
                'label'      => 'Guatemala'
            ],
            [
                'code'       => '+503',
                'code_label' => '+503',
                'label'      => 'El Salvador'
            ],
            [
                'code'       => '+504',
                'code_label' => '+504',
                'label'      => 'Honduras'
            ],
            [
                'code'       => '+505',
                'code_label' => '+505',
                'label'      => 'Nicaragua'
            ],
            [
                'code'       => '+506',
                'code_label' => '+506',
                'label'      => 'Costa Rica'
            ],
            [
                'code'       => '+507',
                'code_label' => '+507',
                'label'      => 'Panama'
            ],
            [
                'code'       => '+508',
                'code_label' => '+508',
                'label'      => 'Saint-Pierre and Miquelon'
            ],
            [
                'code'       => '+509',
                'code_label' => '+509',
                'label'      => 'Haiti'
            ],
            [
                'code'       => '+51',
                'code_label' => '+51',
                'label'      => 'Peru'
            ],
            [
                'code'       => '+52',
                'code_label' => '+52',
                'label'      => 'Mexico'
            ],
            [
                'code'       => '+53',
                'code_label' => '+53',
                'label'      => 'Cuba'
            ],
            [
                'code'       => '+54',
                'code_label' => '+54',
                'label'      => 'Argentina'
            ],
            [
                'code'       => '+55',
                'code_label' => '+55',
                'label'      => 'Brazil'
            ],
            [
                'code'       => '+56',
                'code_label' => '+56',
                'label'      => 'Chile'
            ],
            [
                'code'       => '+57',
                'code_label' => '+57',
                'label'      => 'Colombia'
            ],
            [
                'code'       => '+58',
                'code_label' => '+58',
                'label'      => 'Venezuela'
            ],
            [
                'code'       => '+590',
                'code_label' => '+590',
                'label'      => 'Guadeloupe'
            ],
            [
                'code'       => '+590',
                'code_label' => '+590',
                'label'      => 'Saint Barthélemy'
            ],
            [
                'code'       => '+590',
                'code_label' => '+590',
                'label'      => 'Saint Martin'
            ],
            [
                'code'       => '+591',
                'code_label' => '+591',
                'label'      => 'Bolivia'
            ],
            [
                'code'       => '+592',
                'code_label' => '+592',
                'label'      => 'Guyana'
            ],
            [
                'code'       => '+593',
                'code_label' => '+593',
                'label'      => 'Ecuador'
            ],
            [
                'code'       => '+594',
                'code_label' => '+594',
                'label'      => 'French Guiana'
            ],
            [
                'code'       => '+595',
                'code_label' => '+595',
                'label'      => 'Paraguay'
            ],
            [
                'code'       => '+596',
                'code_label' => '+596',
                'label'      => 'Martinique'
            ],
            [
                'code'       => '+597',
                'code_label' => '+597',
                'label'      => 'Suriname'
            ],
            [
                'code'       => '+598',
                'code_label' => '+598',
                'label'      => 'Uruguay'
            ],
            [
                'code'       => '+5993',
                'code_label' => '+5993',
                'label'      => 'Sint Eustatius'
            ],
            [
                'code'       => '+5994',
                'code_label' => '+5994',
                'label'      => 'Saba'
            ],
            [
                'code'       => '+5997',
                'code_label' => '+5997',
                'label'      => 'Bonaire'
            ],
            [
                'code'       => '+5999',
                'code_label' => '+5999',
                'label'      => 'Curaçao'
            ],
            [
                'code'       => '+60',
                'code_label' => '+60',
                'label'      => 'Malaysia'
            ],
            [
                'code'       => '+61',
                'code_label' => '+61',
                'label'      => 'Australia'
            ],
            [
                'code'       => '+62',
                'code_label' => '+62',
                'label'      => 'Indonesia'
            ],
            [
                'code'       => '+63',
                'code_label' => '+63',
                'label'      => 'Philippines'
            ],
            [
                'code'       => '+64',
                'code_label' => '+64',
                'label'      => 'New Zealand'
            ],
            [
                'code'       => '+64',
                'code_label' => '+64',
                'label'      => 'Pitcairn Islands'
            ],
            [
                'code'       => '+65',
                'code_label' => '+65',
                'label'      => 'Singapore'
            ],
            [
                'code'       => '+66',
                'code_label' => '+66',
                'label'      => 'Thailand'
            ],
            [
                'code'       => '+670',
                'code_label' => '+670',
                'label'      => 'Timor-Leste (East Timor)'
            ],
            [
                'code'       => '+6721',
                'code_label' => '+6721',
                'label'      => 'Australian Antarctic Territory'
            ],
            [
                'code'       => '+6723',
                'code_label' => '+6723',
                'label'      => 'Norfolk Island'
            ],
            [
                'code'       => '+673',
                'code_label' => '+673',
                'label'      => 'Brunei Darussalam'
            ],
            [
                'code'       => '+674',
                'code_label' => '+674',
                'label'      => 'Nauru'
            ],
            [
                'code'       => '+675',
                'code_label' => '+675',
                'label'      => 'Papua New Guinea'
            ],
            [
                'code'       => '+676',
                'code_label' => '+676',
                'label'      => 'Tonga'
            ],
            [
                'code'       => '+677',
                'code_label' => '+677',
                'label'      => 'Solomon Islands'
            ],
            [
                'code'       => '+678',
                'code_label' => '+678',
                'label'      => 'Vanuatu'
            ],
            [
                'code'       => '+679',
                'code_label' => '+679',
                'label'      => 'Fiji'
            ],
            [
                'code'       => '+680',
                'code_label' => '+680',
                'label'      => 'Palau'
            ],
            [
                'code'       => '+681',
                'code_label' => '+681',
                'label'      => 'Wallis and Futuna'
            ],
            [
                'code'       => '+682',
                'code_label' => '+682',
                'label'      => 'Cook Islands'
            ],
            [
                'code'       => '+683',
                'code_label' => '+683',
                'label'      => 'Niue'
            ],
            [
                'code'       => '+685',
                'code_label' => '+685',
                'label'      => 'Samoa'
            ],
            [
                'code'       => '+686',
                'code_label' => '+686',
                'label'      => 'Kiribati'
            ],
            [
                'code'       => '+687',
                'code_label' => '+687',
                'label'      => 'New Caledonia'
            ],
            [
                'code'       => '+688',
                'code_label' => '+688',
                'label'      => 'Tuvalu'
            ],
            [
                'code'       => '+689',
                'code_label' => '+689',
                'label'      => 'French Polynesia'
            ],
            [
                'code'       => '+690',
                'code_label' => '+690',
                'label'      => 'Tokelau'
            ],
            [
                'code'       => '+691',
                'code_label' => '+691',
                'label'      => 'Micronesia'
            ],
            [
                'code'       => '+692',
                'code_label' => '+692',
                'label'      => 'Marshall Islands'
            ],
            [
                'code'       => '+7',
                'code_label' => '+7',
                'label'      => 'Russia'
            ],
            [
                'code'       => '+7',
                'code_label' => '+7',
                'label'      => 'Kazakhstan'
            ],
            [
                'code'       => '+81',
                'code_label' => '+81',
                'label'      => 'Japan'
            ],
            [
                'code'       => '+82',
                'code_label' => '+82',
                'label'      => 'South Korea'
            ],
            [
                'code'       => '+84',
                'code_label' => '+84',
                'label'      => 'Vietnam'
            ],
            [
                'code'       => '+850',
                'code_label' => '+850',
                'label'      => 'North Korea'
            ],
            [
                'code'       => '+852',
                'code_label' => '+852',
                'label'      => 'Hong Kong'
            ],
            [
                'code'       => '+853',
                'code_label' => '+853',
                'label'      => 'Macao'
            ],
            [
                'code'       => '+855',
                'code_label' => '+855',
                'label'      => 'Cambodia'
            ],
            [
                'code'       => '+856',
                'code_label' => '+856',
                'label'      => 'Laos'
            ],
            [
                'code'       => '+86',
                'code_label' => '+86',
                'label'      => 'China (PRC)'
            ],
            [
                'code'       => '+880',
                'code_label' => '+880',
                'label'      => 'Bangladesh'
            ],
            [
                'code'       => '+886',
                'code_label' => '+886',
                'label'      => 'Taiwan (ROC)'
            ],
            [
                'code'       => '+90',
                'code_label' => '+90',
                'label'      => 'Türkiye (Turkey)'
            ],
            [
                'code'       => '+91',
                'code_label' => '+91',
                'label'      => 'India'
            ],
            [
                'code'       => '+92',
                'code_label' => '+92',
                'label'      => 'Pakistan'
            ],
            [
                'code'       => '+93',
                'code_label' => '+93',
                'label'      => 'Afghanistan'
            ],
            [
                'code'       => '+94',
                'code_label' => '+94',
                'label'      => 'Sri Lanka'
            ],
            [
                'code'       => '+95',
                'code_label' => '+95',
                'label'      => 'Myanmar'
            ],
            [
                'code'       => '+960',
                'code_label' => '+960',
                'label'      => 'Maldives'
            ],
            [
                'code'       => '+961',
                'code_label' => '+961',
                'label'      => 'Lebanon'
            ],
            [
                'code'       => '+962',
                'code_label' => '+962',
                'label'      => 'Jordan'
            ],
            [
                'code'       => '+963',
                'code_label' => '+963',
                'label'      => 'Syria'
            ],
            [
                'code'       => '+964',
                'code_label' => '+964',
                'label'      => 'Iraq'
            ],
            [
                'code'       => '+965',
                'code_label' => '+965',
                'label'      => 'Kuwait'
            ],
            [
                'code'       => '+966',
                'code_label' => '+966',
                'label'      => 'Saudi Arabia'
            ],
            [
                'code'       => '+967',
                'code_label' => '+967',
                'label'      => 'Yemen'
            ],
            [
                'code'       => '+968',
                'code_label' => '+968',
                'label'      => 'Oman'
            ],
            [
                'code'       => '+970',
                'code_label' => '+970',
                'label'      => 'Palestine'
            ],
            [
                'code'       => '+971',
                'code_label' => '+971',
                'label'      => 'United Arab Emirates'
            ],
            [
                'code'       => '+972',
                'code_label' => '+972',
                'label'      => 'Israel'
            ],
            [
                'code'       => '+973',
                'code_label' => '+973',
                'label'      => 'Bahrain'
            ],
            [
                'code'       => '+974',
                'code_label' => '+974',
                'label'      => 'Qatar'
            ],
            [
                'code'       => '+975',
                'code_label' => '+975',
                'label'      => 'Bhutan'
            ],
            [
                'code'       => '+976',
                'code_label' => '+976',
                'label'      => 'Mongolia'
            ],
            [
                'code'       => '+977',
                'code_label' => '+977',
                'label'      => 'Nepal'
            ],
            [
                'code'       => '+98',
                'code_label' => '+98',
                'label'      => 'Iran'
            ],
            [
                'code'       => '+992',
                'code_label' => '+992',
                'label'      => 'Tajikistan'
            ],
            [
                'code'       => '+993',
                'code_label' => '+993',
                'label'      => 'Turkmenistan'
            ],
            [
                'code'       => '+994',
                'code_label' => '+994',
                'label'      => 'Azerbaijan'
            ],
            [
                'code'       => '+995',
                'code_label' => '+995',
                'label'      => 'Georgia'
            ],
            [
                'code'       => '+996',
                'code_label' => '+996',
                'label'      => 'Kyrgyzstan'
            ],
            [
                'code'       => '+998',
                'code_label' => '+998',
                'label'      => 'Uzbekistan'
            ],
        ];
    }
}
if (!function_exists('calculate_bill_cycle')) {
    /**
     * Calculate bill cycle
     * @param string $baseDate
     * @param int $anchorDay
     * @param string $cycle Either 'month' or 'year'
     * @return string
     * @throws DateMalformedStringException
     */
    function calculate_bill_cycle(string $baseDate, int $anchorDay, string $cycle = 'month'): string
    {
        $dt    = new DateTime($baseDate);
        $day   = (int) $dt->format('d');
        if ('month' == $cycle) {
            // Jump to first day of the month to avoid overflow weirdness
            $dt->modify('first day of this month');
            $dt->modify("+1 month");               // now first day of target month
            $daysInTarget = (int) $dt->format('t'); // days in target month
            $day          = min($anchorDay, $daysInTarget);
            $dt->setDate(
                (int) $dt->format('Y'),
                (int) $dt->format('m'),
                $day
            );
            return $dt->format('Y-m-d');
        }
        $month = (int) $dt->format('m');
        $year  = (int) $dt->format('Y') + 1;
        // Try same day/month next year
        $candidate = DateTime::createFromFormat(
            'Y-m-d',
            sprintf('%04d-%02d-%02d', $year, $month, $day)
        );
        if ($candidate === false) {
            // Invalid date (e.g. 2020-02-29 + 1 year)
            $candidate = DateTime::createFromFormat(
                'Y-m-d',
                sprintf('%04d-%02d-01', $year, $month)
            );
            $candidate->modify('last day of this month');
        }
        return $candidate->format('Y-m-d');
    }
}
if (!function_exists('calculate_invoice_number')) {
    /**
     * Generate an almost random invoice number that means something
     * @return string
     */
    function calculate_invoice_number(): string
    {
        $session     = session();
        $year        = date('Y') - 2018; // Use Reiwa year
        $year        = sprintf('%02d', $year);
        $month       = date('m') - 1;
        $month_array = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M'];
        $month       = $month_array[$month];
        $date        = date('d');
        $slug        = $session->business['business_slug'];
        $hash        = substr(strtoupper(md5($slug)), 0, 4);
        $rand        = rand(10, 99);
        return "{$year}{$date}{$month}{$hash}{$rand}";
    }
}