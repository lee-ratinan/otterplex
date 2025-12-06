<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Helper extends BaseController
{

    /**
     * Format phone number
     * GET Parameters:
     * - phone_number
     * - country_code
     * @return ResponseInterface
     */
    public function format_phone_number(): ResponseInterface
    {
        try {
            $phone_number  = $this->request->getPost('phone_number');
            $country_code  = $this->request->getPost('country_code');
            $phone_util    = PhoneNumberUtil::getInstance();
            $phone_obj     = $phone_util->parse($phone_number, $country_code);
            $international = $phone_util->format($phone_obj, PhoneNumberFormat::INTERNATIONAL);
            $national      = $phone_util->format($phone_obj, PhoneNumberFormat::NATIONAL);
            $e164          = $phone_util->format($phone_obj, PhoneNumberFormat::E164);
            $country       = $phone_obj->getCountryCode();
            return $this->response->setJSON([
                'status'        => STATUS_RESPONSE_OK,
                'international' => $international,
                'national'      => $national,
                'e164'          => $e164,
                'country'       => $country,
            ]);
        } catch (NumberParseException $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ]);
        }
    }
}