<?php

namespace App\Models;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use ReflectionException;

class UserMasterModel extends AppBaseModel
{
    protected $table = 'user_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'email_address',
        'password_hash',
        'password_expiry',
        'telephone_number',
        'account_status',
        'user_name_first',
        'user_name_last',
        'user_gender',
        'user_date_of_birth',
        'user_nationality',
        'profile_status_msg',
        'user_type',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    private array $password_options = [
        'memory_cost' => 1 << 17, // 128MB
        'time_cost'   => 4,
        'threads'     => 2
    ];
    const string PASSWORD_EXPIRY = '+180 days';
    CONST string USER_GENDER_MALE = 'M';
    CONST string USER_GENDER_FEMALE = 'F';
    CONST string USER_GENDER_NON_BINARY = 'NB';
    CONST string USER_GENDER_UNKNOWN = 'U';
    const string USER_TYPE_OTTERNAUT = 'ONAUT';
    const string USER_TYPE_CLIENT = 'CLIENT';
    const string ACCOUNT_STATUS_ACTIVE = 'A';
    const string ACCOUNT_STATUS_BLOCKED = 'B';
    const string ACCOUNT_STATUS_SUSPENDED = 'S';
    const string ACCOUNT_STATUS_PENDING = 'P';

    private array $configurations = [
        'email_address'      => [
            'type' => 'email'
        ],
        'password_expiry'    => [
            'type' => 'date'
        ],
        'telephone_number'   => [
            'type' => 'text'
        ],
        'account_status'     => [
            'type' => 'select',
        ],
        'user_name_first'    => [
            'type' => 'text'
        ],
        'user_name_last'     => [
            'type' => 'text'
        ],
        'user_gender'        => [
            'type' => 'select'
        ],
        'user_date_of_birth' => [
            'type' => 'date'
        ],
        'user_nationality'   => [
            'type' => 'select'
        ],
        'profile_status_msg' => [
            'type' => 'text'
        ],
        'user_type'          => [
            'type' => 'select'
        ],
    ];

    /**
     * Get configurations for generating forms
     * @return array
     */
    public function getConfigurations(): array
    {
        $configurations  = $this->configurations;
        foreach ($this->allowedFields as $field) {
            $configurations[$field]['label'] = lang('UserMaster.field.' . $field);
        }
        // account status
        $configurations['account_status']['options'] = [
            self::ACCOUNT_STATUS_ACTIVE    => lang('UserMaster.enum.account_status.A'),
            self::ACCOUNT_STATUS_PENDING   => lang('UserMaster.enum.account_status.P'),
            self::ACCOUNT_STATUS_BLOCKED   => lang('UserMaster.enum.account_status.B'),
            self::ACCOUNT_STATUS_SUSPENDED => lang('UserMaster.enum.account_status.S'),
        ];
        // user gender
        $configurations['user_gender']['options'] = [
            self::USER_GENDER_MALE       => lang('UserMaster.enum.user_gender.M'),
            self::USER_GENDER_FEMALE     => lang('UserMaster.enum.user_gender.F'),
            self::USER_GENDER_NON_BINARY => lang('UserMaster.enum.user_gender.NB'),
            self::USER_GENDER_UNKNOWN    => lang('UserMaster.enum.user_gender.U'),
        ];
        // user type
        $configurations['user_type']['options'] = [
            self::USER_TYPE_OTTERNAUT => lang('UserMaster.enum.user_type.ONAUT'),
            self::USER_TYPE_CLIENT    => lang('UserMaster.enum.user_type.CLIENT'),
        ];
        // nationality
        $configurations['user_nationality']['options'] = [];
        $countries                                     = get_country_codes();
        foreach ($countries['countries'] as $code => $fields) {
            $configurations['user_nationality']['options'][$code] = $fields['common_name'];
        }
        return $configurations;
    }

    /**
     * Update password and extend the expiry date
     * @param int $user_id
     * @param string $new_password
     * @param string $current_password
     * @return bool
     * @throws ReflectionException
     */
    public function updatePassword(int $user_id, string $new_password, string $current_password): bool
    {
        $updating_user = $this->find($user_id);
        log_message('debug', "Updating password for user {$user_id} - START");
        if (password_verify($current_password, $updating_user['account_password_hash'])) {
            // Verified, then, do the new
            if (!password_verify($new_password, $updating_user['account_password_hash'])) {
                // New password is not the same as the previous one
                $row      = [
                    'account_password_hash'   => password_hash($new_password, PASSWORD_DEFAULT, $this->password_options),
                    'account_password_expiry' => date('Y-m-d', strtotime(self::PASSWORD_EXPIRY))
                ];
                return $this->update($user_id, $row);
            }
            log_message('debug', "Updating password for user #{$user_id} - New password seems to be the same as previous one.");
            return false;
        }
        log_message('debug', "Updating password for user #{$user_id} - Fail to verify current password.");
        return false;
    }

    /**
     * Generate random password and its hash
     * @param int $length
     * @return array
     */
    public function generateRandomPassword(int $length = 12): array
    {
        $uppercase    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase    = 'abcdefghijklmnopqrstuvwxyz';
        $numbers      = '0123456789';
        $specialChars = '@$!%*?&';
        $allChars     = $uppercase . $lowercase . $numbers . $specialChars;
        $password     = $uppercase[rand(0, strlen($uppercase) - 1)] .
            $lowercase[rand(0, strlen($lowercase) - 1)] .
            $numbers[rand(0, strlen($numbers) - 1)] .
            $specialChars[rand(0, strlen($specialChars) - 1)];
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }
        $password = str_shuffle($password);
        $hashed   = password_hash($password, PASSWORD_DEFAULT, $this->password_options);
        return [
            'plain'  => $password,
            'hashed' => $hashed
        ];
    }
}