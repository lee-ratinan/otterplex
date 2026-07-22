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
            echo "if ('' === {$field}) { $('#{$field}').focus(); console.log('{$field} is empty'); return false; }\n";
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
     * @param string $word
     * @return string
     */
    function retrieve_avatars_extract_initial(string $word): string
    {
        $thai_consonants = [
            'ก','ข','ฃ','ค','ฅ','ฆ',
            'ง','จ','ฉ','ช','ซ','ฌ',
            'ญ','ฎ','ฏ','ฐ','ฑ','ฒ',
            'ณ','ด','ต','ถ','ท','ธ',
            'น','บ','ป','ผ','ฝ','พ','ฟ',
            'ภ','ม','ย','ร','ล','ว',
            'ศ','ษ','ส','ห','ฬ','อ','ฮ'
        ];
        $chars = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
        // If Thai: try to find first consonant
        if (preg_match('/\p{Thai}/u', $word)) {
            foreach ($chars as $c) {
                if (in_array($c, $thai_consonants, true)) {
                    return $c;
                }
            }
        }
        return $chars[0];
    }

    /**
     * Create avatar - only for admin
     * @param string $email_address
     * @param string $full_name
     * @return string
     */
    function retrieve_avatars(string $email_address, string $full_name): string
    {
        $full_name      = trim(preg_replace('/\s+/u', ' ', $full_name));
        $email_address  = preg_replace('/[^a-z0-9]/i', '', strtolower($email_address));
        $file_url       = base_url('file/profile_picture_' . $email_address . '.webp');
        $file_path      = WRITEPATH . 'uploads/profile_pictures/profile_' . $email_address . '.webp';
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
        $parts    = explode(' ', $full_name);
        $first    = $parts[0];
        $last     = $parts[count($parts) - 1];
        $initials = retrieve_avatars_extract_initial($first) . retrieve_avatars_extract_initial($last);
        return "<div class='avatar-txt' style='background-color:$color;color:$text_color' title='$full_name' data-bs-toggle='tooltip' data-bs-placement='top'>$initials</div>";
    }
}
if (!function_exists('format_date')) {
    /**
     * Format date
     * @param string $date Date in YYYY-MM-DD (MySQL) format
     * @param string $lang
     * @return string
     */
    function format_date(string $date, string $lang = ''): string
    {
        if (empty($lang)) {
            $lang = get_session_field('lang');
        }
        if (!in_array($lang, ['en', 'th', 'ja', 'zh'])) {
            $lang = 'en';
        }
        $month_array = [
            'en' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'th' => ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.']
        ];
        $pieces = explode('-', $date);
        $dd     = intval($pieces[2]);
        $mm     = intval($pieces[1]);
        $mmmm   = $month_array[$lang][intval($pieces[1]) - 1];
        $yyyy   = intval($pieces[0]);
        if ('th' == $lang) {
            $yyyy += 543;
            return "{$dd} {$mmmm} {$yyyy}";
        } else if ('ja' == $lang || 'zh' == $lang) {
            return "{$yyyy}年{$mm}月{$dd}日";
        }
        return "{$dd} {$mmmm} {$yyyy}";
    }
}
if (!function_exists('format_time')) {
    /**
     * Format time
     * @param string $time Time in HH:II:SS (MySQL) format
     * @param string $lang
     * @return string
     */
    function format_time(string $time, string $lang = ''): string
    {
        if (empty($lang)) {
            $lang = get_session_field('lang');
        }
        if ('th' == $lang) {
            return substr($time, 0, 5) . 'น.';
        } else if ('ja' == $lang || 'zh' == $lang) {
            return substr($time, 0, 5);
        }
        // English (US)
        $pieces = explode(':', $time);
        $am     = 'am';
        $hh     = intval($pieces[0]);
        $mm     = intval($pieces[1]);
        if (11 < $hh) {
            $am = 'pm';
        }
        if (12 < $hh) {
            $hh -= 12;
        }
        if (10 > $mm) {
            $mm = '0' . $mm;
        }
        return "$hh:$mm $am";
    }
}
if (!function_exists('format_date_time')) {
    /**
     * Format date-time
     * @param string $date Date-time in YYYY-MM-DD HH:II:SS (MySQL) format
     * @param string $languageCode
     * @return string
     */
    function format_date_time(string $date, string $languageCode = ''): string
    {
        $pieces = explode(' ', $date);
        return format_date($pieces[0], $languageCode) . ' ' . format_time($pieces[1], $languageCode);
    }
}
if (!function_exists('build_form_input')) {
    /**
     * @param string $id
     * @param string $label
     * @param array $attributes
     * @param string|null $current_value
     * @param string $other_classes
     * @param array $options
     * @return string
     */
    function build_form_input(string $id, string $label, array $attributes, string|null $current_value = '', string $other_classes = '', array $options = []): string
    {
        $required = '';
        if (isset($attributes['required']) && 'true' == $attributes['required']) {
            $required = '*';
        }
        $label_cls = '';
        if (empty($label)) {
            $label_cls = 'd-none';
        }
        $structure = "<div class='mb-3' id='div-{$id}-container'><label for='{$id}' class='form-label {$label_cls}'>{$label} {$required}</label> ###FORM### ###EXP### </div>";
        $attr      = [];
        $attr[]    = "id='{$id}'";
        $attr[]    = "class='form-control $other_classes'";
        foreach ($attributes as $key => $value) {
            $attr[]    = "{$key}='{$value}'";
        }
        if (isset($attributes['data-explanation'])) {
            $structure = str_replace('###EXP###', '<p class="small mt-1 ms-2">' . $attributes['data-explanation'] . '</p>', $structure);
        } else {
            $structure = str_replace('###EXP###', '', $structure);
        }
        if ('select' == $attributes['type']) {
            $attr_str   = implode(' ', $attr);
            $select_tag = "<select {$attr_str}><option value=''></option>";
            foreach ($options as $key => $value) {
                $select_tag .= "<option value='{$key}' " . ($current_value == $key ? 'selected' : '') . ">{$value}</option>";
            }
            $select_tag .= "</select>";
            return str_replace('###FORM###' , $select_tag, $structure);
        } else if ('checkbox' == $attributes['type'] || 'radio' == $attributes['type']) {
            return '';
        }
        // TextArea and Others
        $stripped  = trim(strip_tags($label));
        $attr[]    = "placeholder='{$stripped}'";
        if ('textarea' == $attributes['type']) {
            $attr_str  = implode(' ', $attr);
            $ta_tag    = "<textarea {$attr_str}>{$current_value}</textarea>";
            return str_replace('###FORM###' , $ta_tag, $structure);
        }
        if (!is_null($current_value)) {
            $attr[] = "value='{$current_value}'";
        }
        $attr_str  = implode(' ', $attr);
        $input_tag = "<input {$attr_str} />";
        return str_replace('###FORM###' , $input_tag, $structure);
    }
}
if (!function_exists('format_price')) {
    /**
     * Currency format
     * @param float $price
     * @param string $currency
     * @param int $decimals_override
     * @return string
     */
    function format_price(float $price, string $currency, int $decimals_override = -1): string
    {
        $currency = strtoupper($currency);
        $country_to_currency = [
            'JP' => 'JPY',
            'MY' => 'MYR',
            'SG' => 'SGD',
            'TH' => 'THB',
            'TW' => 'TWD',
            'US' => 'USD',
        ];
        if (isset($country_to_currency[$currency])) {
            $currency = $country_to_currency[$currency];
        }
        $default_decimals = (in_array($currency, ['JPY', 'IDR', 'KRW']) ? 0 : 2);
        if (-1 == $decimals_override) {
            $decimals_override = $default_decimals;
        }
        // Check negative
        $negative = '';
        if (0 > $price) {
            $negative = '-';
            $price    = abs($price);
        }
        if ('JPY' == $currency) {
            return $negative . number_format($price, $decimals_override) . '円';
        } else if ('MYR' == $currency) {
            return $negative . 'RM' . number_format($price, $decimals_override);
        } else if ('SGD' == $currency) {
            return $negative . 'S$' . number_format($price, $decimals_override);
        } else if ('THB' == $currency) {
            return $negative . '฿' . number_format($price, $decimals_override);
        } else if ('TWD' == $currency) {
            return $negative . 'NT$' . number_format($price, $decimals_override);
        }
        return $negative . '$' . number_format($price, $decimals_override);
    }
}

if (!function_exists('translate_plan')) {
    function translate_plan(string $plan_name, string $plan_duration, string $country_code): array
    {
        $names = [
            'TH' => [
                'basic'    => 'Basic / เบสิก',
                'standard' => 'Standard / สแตนดาร์ด',
                'premium'  => 'Premium / พรีเมียม',
            ]
        ];
        $durations = [
            'TH' => [
                'monthly'  => '1 month / 1 เดือน',
                'annually' => '1 year / 1 ปี',
            ]
        ];
        return [
            'name'     => $names[$country_code][$plan_name] ?? $plan_name,
            'duration' => $durations[$country_code][$plan_duration] ?? $plan_duration,
        ];
    }
}

if (!function_exists('generate_invoice_receipt_core_features')) {
    function generate_invoice_receipt_core_features(string $country_code, string $invoice_no, string $invoice_date, string $billed_to, string $plan, string $duration, string $amount, string $total): string
    {
        $template_words = [
            'invoice_no'   => 'Invoice No.',
            'invoice_date' => 'Invoice Date',
            'billed_to'    => 'Billed to',
            'plan'         => 'Plan',
            'duration'     => 'Duration',
            'amount'       => 'Amount',
            'total'        => 'Total',
        ];
        if ('TH' == $country_code) {
            $template_words = [
                'invoice_no'   => 'Invoice No. / ใบเรียกเก็บเงินเลขที่',
                'invoice_date' => 'Invoice Date / วันที่เรียกเก็บ',
                'billed_to'    => 'Billed to / ลูกค้า',
                'plan'         => 'Plan / แผน',
                'duration'     => 'Duration / ระยะเวลา',
                'amount'       => 'Amount / จำนวนเงิน',
                'total'        => 'Total / จำนวนเงินทั้งหมด',
            ];
        }
        $invoice_date = date('j F Y', strtotime($invoice_date));
        return "<p><b>{$template_words['invoice_no']}</b><br/>{$invoice_no}</p>" .
            "<p><b>{$template_words['invoice_date']}</b><br/>{$invoice_date}</p>" .
            "<p><b>{$template_words['billed_to']}</b><br/>{$billed_to}</p>" .
            "<div style=\"height:1px;background:#e5e5ea;line-height:1px;font-size:1px;\">&nbsp;</div>" .
            "<p><span style=\"float:right;\">{$plan}</span>{$template_words['plan']}</p>" .
            "<p><span style=\"float:right;\">{$duration}</span>{$template_words['duration']}</p>" .
            "<p><span style=\"float:right;\">{$amount}</span>{$template_words['amount']}</p>" .
            "<div style=\"height:1px;background:#e5e5ea;line-height:1px;font-size:1px;\">&nbsp;</div>" .
            "<p><b style=\"float:right;font-size:1.2em;\">{$total}</b>{$template_words['total']}</p>" .
            "<div style=\"height:1px;background:#e5e5ea;line-height:1px;font-size:1px;\">&nbsp;</div>";
    }
}

if (!function_exists('generate_invoice')) {
    function generate_invoice(string $country_code, string $plan_name, string $plan_duration, string $invoice_no, string $invoice_date, string $billed_to, float $amount, float $total, string $payment_instruction): string
    {
        $template_words = [
            'invoice'             => 'Invoice',
            'payment_instruction' => 'Payment Instruction'
        ];
        if ('TH' == $country_code) {
            $template_words = [
                'invoice'             => 'Invoice / ใบเรียกเก็บเงิน',
                'payment_instruction' => 'Payment Instruction / วิธีการชำระเงิน'
            ];
        }
        $plans  = translate_plan($plan_name, $plan_duration, $country_code);
        $amount = format_price($amount, $country_code);
        $total  = format_price($total, $country_code);
        return "<h2>{$template_words['invoice']}</h2>" . generate_invoice_receipt_core_features($country_code, $invoice_no, $invoice_date, $billed_to, $plans['name'], $plans['duration'], $amount, $total) .
            "<h3>{$template_words['payment_instruction']}</h3>" . $payment_instruction;
    }
}

if (!function_exists('generate_receipt')) {
    function generate_receipt(string $prefix, string $country_code, string $reiwa_year, string $month, string $next_number): string
    {
        return '';
    }
}