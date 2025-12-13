<?php

namespace App\Controllers;

use App\Models\BusinessMasterModel;
use App\Models\BusinessUserModel;
use App\Models\LogActivityModel;
use App\Models\UserMasterModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Transliterator;

class Home extends BaseController
{

    const string SESSION_EXPIRY_STRING = '+1 hour';
    const int THROTTLER_LOGIN_CAPACITY = 5;
    const int THROTTLER_LOGIN_SECOND = 60;

    /**
     * Generate error response JSON
     * @param int $statusCode
     * @param string $message
     * @return ResponseInterface
     */
    private function generateErrorResponse(int $statusCode, string $message): ResponseInterface
    {
        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $message
            ]);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // LOGIN
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Login page
     * @return string|RedirectResponse
     */
    public function login(): string|RedirectResponse
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }
        $session = session();
        $data    = [
            'slug'  => 'login',
            'lang'  => $this->request->getLocale(),
            'error' => $session->get('error') ?? ''
        ];
        return view('home/login', $data);
    }

    /**
     * Login script
     * Require
     * POST username
     * POST password
     * @return ResponseInterface
     */
    public function login_post(): ResponseInterface
    {
        try {
            $userModel     = new UserMasterModel();
            $businessModel = new BusinessUserModel();
            $logModel      = new LogActivityModel();
            $throttler     = Services::throttler();
            $session       = session();
            $username      = $this->request->getPost('username');
            $password      = $this->request->getPost('password');
            // Throttling by IP + email (simple but effective)
            $throttleKey = 'login-' . $this->request->getIPAddress() . '-' . md5($username);
            if (!$throttler->check($throttleKey, self::THROTTLER_LOGIN_CAPACITY, self::THROTTLER_LOGIN_SECOND)) {
                // Too many attempts
                return $this->generateErrorResponse(ResponseInterface::HTTP_UNAUTHORIZED, lang('System.response-msg.error.generic') . ' [MA]'); // too Many Attempts
            }
            // Validate input
            if (empty($username) || empty($password)) {
                $throttler->check($throttleKey, self::THROTTLER_LOGIN_CAPACITY, self::THROTTLER_LOGIN_SECOND);
                return $this->generateErrorResponse(ResponseInterface::HTTP_UNAUTHORIZED, lang('System.response-msg.error.wrong-credentials') . ' [VF]'); // Verification
            }
            $user = $userModel->where('email_address', $username)->first();
            if (!$user) {
                $throttler->check($throttleKey, self::THROTTLER_LOGIN_CAPACITY, self::THROTTLER_LOGIN_SECOND);
                return $this->generateErrorResponse(ResponseInterface::HTTP_UNAUTHORIZED, lang('System.response-msg.error.wrong-credentials') . ' [NF]'); // Not Found
            }
            // Verify password
            if (!password_verify($password, $user['password_hash'])) {
                $throttler->check($throttleKey, self::THROTTLER_LOGIN_CAPACITY, self::THROTTLER_LOGIN_SECOND);
                return $this->generateErrorResponse(ResponseInterface::HTTP_UNAUTHORIZED, lang('System.response-msg.error.wrong-credentials') . ' [PH]'); // not equal Password Hash
            }
            // Check status
            if ($userModel::ACCOUNT_STATUS_ACTIVE != $user['account_status']) {
                return $this->generateErrorResponse(ResponseInterface::HTTP_UNAUTHORIZED, lang('System.response-msg.error.inactive-account') . ' [NA]'); // account Not Active
            }
            // Log
            $userAgent  = $this->request->getUserAgent();
            $ipAddress  = $this->request->getIPAddress();
            if ($userAgent->isMobile()) {
                $deviceType = 'mobile';
                $uaStr = strtolower($userAgent->getAgentString());
                if (str_contains($uaStr, 'tab') ||
                    str_contains($uaStr, 'ipad')) {
                    $deviceType = 'tablet';
                }
            } elseif ($userAgent->isRobot()) {
                $deviceType = 'robot';
            } else {
                $deviceType = 'desktop';
            }
            $browser  = $userAgent->getBrowser();
            $platform = $userAgent->getPlatform();
            $logData  = [
                'emailAddress'  => $user['email_address'],
                'ipAddress'     => $ipAddress,
                'deviceType'    => $deviceType,
                'browser'       => $browser,
                'platform'      => $platform,
            ];
            $session->set('user_id', $user['id']);
            $logModel->insertLogin($logData);
            // Successful login: regenerate session ID to prevent fixation
            $businesses      = $businessModel->getBusinessesByUserId($user['id'], true);
            log_message('debug', '[SYSTEM] ' . json_encode($businesses));
            $businessIds     = [];
            $currentBusiness = [];
            if (!empty($businesses)) {
                foreach ($businesses as $business) {
                    $businessIds[] = $business['business_id'];
                    if ($business['my_default_business'] == 'Y') {
                        $currentBusiness = $business;
                    }
                }
                if (empty($currentBusiness)) {
                    $currentBusiness = $businesses[0];
                }
            }
            $businessLogo = '';
            if (!empty($currentBusiness['business_logo'])) {
                $businessLogo = base_url('file/business_' . $currentBusiness['business_logo']);
            }
            $session->regenerate();
            unset($user['password_hash']);
            $session->set([
                'lang'           => $user['lang_code'], // Override everything because it's the user's preferred setting
                'isLoggedIn'     => true,
                'sessionStart'   => date(DATETIME_FORMAT_DB),
                'sessionExpiry'  => date(DATETIME_FORMAT_DB, strtotime(self::SESSION_EXPIRY_STRING)),
                'user_id'        => $user['id'],
                'full_name'      => $user['user_name_first'] . ' ' . $user['user_name_last'],
                'avatar'         => retrieve_avatars($user['email_address'], $user['user_name_first'] . ' ' . $user['user_name_last']),
                'business_logo'  => $businessLogo,
                'user'           => $user,
                'user_role'      => $currentBusiness['user_role'],
                'business'       => $currentBusiness,
                'business_ids'   => $businessIds,
                'needOTP'        => false, // Not for now
            ]);
            // Redirect to intended page or dashboard
            $redirectTo = $session->get('redirect_url') ?? '/admin/dashboard';
            $session->remove('redirect_url');
            return $this->response->setJSON([
                'status'   => STATUS_RESPONSE_OK,
                'redirect' => $redirectTo,
            ]);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON([
                    'status'  => STATUS_RESPONSE_ERR,
                    'message' => lang('System.response-msg.error.generic') . ' [' . $e->getMessage() . ']'
                ]);
        }
    }

    /**
     * Logout script
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // CREATE ACCOUNT
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Create account page
     * @return string
     */
    public function create_account(): string
    {
        $data = [
            'slug' => 'create-account',
            'lang' => $this->request->getLocale(),
        ];
        return view('home/create_account', $data);
    }

    /**
     * Create account script
     * @return ResponseInterface
     */
    public function create_account_post(): ResponseInterface
    {
        $user_master_fields     = ['user_name_first', 'user_name_last', 'email_address', 'password'];
        $business_master_fields = ['business_name', 'country_code'];
        $user_master            = [];
        $business_master        = [];
        foreach ($user_master_fields as $field) {
            $user_master[$field] = $this->request->getPost($field);
        }
        foreach ($business_master_fields as $field) {
            $business_master[$field] = $this->request->getPost($field);
        }
        $userMasterModel     = new UserMasterModel();
        $businessMasterModel = new BusinessMasterModel();
        $businessUserModel   = new BusinessUserModel();
        // Get the DB connection to manage the transaction
        $db = \Config\Database::connect();
        $db->transBegin(); // <<< START TRANSACTION
        try {
            $password                          = $user_master['password'];
            unset($user_master['password']);
            $user_master['password_hash']      = $userMasterModel->hash_password($password);
            $user_master['password_expiry']    = date(DATE_FORMAT_DB, strtotime($userMasterModel::PASSWORD_EXPIRY));
            $user_master['telephone_number']   = null;
            $user_master['lang_code']          = $this->request->getLocale();
            $user_master['account_status']     = $userMasterModel::ACCOUNT_STATUS_PENDING;
            $user_master['user_gender']        = $userMasterModel::USER_GENDER_UNKNOWN;
            $user_master['date_of_birth']      = null;
            $user_master['nationality']        = null;
            $user_master['profile_status_msg'] = null;
            $user_master['user_type']          = $userMasterModel::USER_TYPE_CLIENT;
            $userMasterModel->insert($user_master);
            $userId                            = $userMasterModel->getInsertID();
            if (!$userId) {
                throw new \Exception(lang('System.response-msg.error.account-created-issue') . ' [USR-ERR]');
            }
            // SLUG
            $transliterator = Transliterator::create('Any-Latin; Latin-ASCII; Lower()');
            $slug = $transliterator->transliterate($business_master['business_name']);
            $slug = strtolower($slug);
            $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
            $slug = preg_replace('/-+/', '-', $slug);
            $slug = trim($slug, '-');
            // LOCALES
            $available_language_codes = get_available_locales();
            $available_language_codes = array_keys($available_language_codes);
            $local_names = [];
            foreach ($available_language_codes as $code) {
                $local_names[$code] = $business_master['business_name'];
            }
            // CURRENCIES AND TAX
            $available_currencies = get_available_currency_code_by_country($business_master['country_code']);
            $tax_default_settings = get_default_tax_settings_by_country($business_master['country_code']);
            // CONTRACT
            $free_trial_expiry    = date(DATE_FORMAT_DB, strtotime(DEFAULT_FREE_TRIAL));
            // DATA
            $business_master['business_type_id']      = 1; // DEFAULT
            $business_master['business_slug']         = $slug;
            $business_master['business_local_names']  = json_encode($local_names);
            $business_master['currency_code']         = $available_currencies[0];
            $business_master['tax_percentage']        = $tax_default_settings['tax_percentage'];
            $business_master['tax_inclusive']         = $tax_default_settings['tax_inclusive'];
            $business_master['mart_primary_color']    = '00AA00';
            $business_master['mart_text_color']       = '000000';
            $business_master['mart_background_color'] = 'FFFFFF';
            $business_master['business_logo']         = null;
            $business_master['contract_anchor_date']  = null;
            $business_master['contract_expiry']       = $free_trial_expiry;
            $businessMasterModel->insert($business_master);
            $businessId = $businessMasterModel->getInsertID();
            if (!$businessId) {
                throw new \Exception(lang('System.response-msg.error.account-created-issue') . ' [BIZ-ERR]');
            }
            $business_user['business_id']         = $businessId;
            $business_user['user_id']             = $userId;
            $business_user['user_role']           = $businessUserModel::USER_ROLE_OWNER;
            $business_user['role_status']         = $businessUserModel::ROLE_STATUS_ACTIVE;
            $business_user['my_default_business'] = $businessUserModel::MY_DEFAULT_BUSINESS_YES;
            $businessUserModel->insert($business_user);
            $buId = $businessUserModel->getInsertID();
            if (!$buId) {
                throw new \Exception(lang('System.response-msg.error.account-created-issue') . ' [BIZUSR-ERR]');
            }
            if ($db->transStatus() === false) {
                $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_ERR,
                    'message' => lang('System.response-msg.error.account-created-issue') . ' [UKNDB]'
                ]);
            }
            // EMAIL
            $exp     = dechex(strtotime('+20 minutes')*11);
            $userTkn = dechex($userId*37);
            $hash    = substr(hash('sha256', $user_master['email_address']), 0, 15);
            $token   = "$exp-$userTkn-$hash";
            $tknLnk  = base_url('account-activation?token=' . $token);
            $subject = lang('System.email.account-activation.subject');
            $message = lang('System.email.account-activation.message', [$tknLnk, $tknLnk]);
            $preheader = substr($message, 0, 50);
            $reply_to  = getenv('SUPPORT_EMAIL');
            log_message('debug', 'EMAIL: SUBJECT: ' . $subject);
            log_message('debug', 'EMAIL: MESSAGE: ' . $message);
            if (!send_system_email($user_master['email_address'], $subject, $preheader, $message, $reply_to)) {
                $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_ERR,
                    'message' => lang('System.response-msg.error.account-created-issue') . ' [ACTEM]'
                ]);
            }
            $db->transCommit(); // <<< COMMIT (Saves changes from all Models)
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_OK,
                'message' => lang('System.response-msg.success.account-created')
            ]);
        } catch (\Exception $e) {
            $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
            log_message('error', $e->getMessage());
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Verify token from the email, if correct, activate the account
     * @return string
     */
    public function account_activation(): string
    {
        $token  = $this->request->getGet('token');
        $error  = '';
        if (empty($token)) {
            $error = 'bad-token';
        } else {
            $pieces = explode("-", $token);
            if (3 !== count($pieces)) {
                $error = 'bad-token';
            } else {
                // Check expiry
                $now    = strtotime('now');
                $expiry = (int) hexdec($pieces[0])/11;
                log_message('debug', 'ACTIVATION: EXPIRY: ' . $expiry . ' VS NOW: ' . $now);
                if ($expiry < $now) {
                    $error = 'expired';
                } else {
                    $userModel = new UserMasterModel();
                    $userId    = (int) hexdec($pieces[1])/37;
                    log_message('debug', 'ACTIVATION: USER ID: ' . $userId);
                    $user      = $userModel->find($userId);
                    if (!$user) {
                        $error = 'not-found';
                    } else {
                        $new_hash = substr(hash('sha256', $user['email_address']), 0, 15);
                        $old_hash = $pieces[2];
                        log_message('debug', 'ACTIVATION: EMAIL: ' . $user['email_address']);
                        log_message('debug', 'ACTIVATION: HASH: ' . $old_hash . ' VS NEW HASH: ' . $new_hash);
                        if ($old_hash != $new_hash) {
                            $error = 'wrong-hash';
                        } else {
                            try {
                                $userModel->update($userId, ['account_status' => $userModel::ACCOUNT_STATUS_ACTIVE]);
                            } catch (\Exception $e) {
                                $error = $e->getMessage();
                            }
                        }
                    }
                }
            }
        }
        log_message('debug', 'ACTIVATION: ERROR: ' . $error);
        $data    = [
            'slug'  => 'account-activation',
            'lang'  => $this->request->getLocale(),
            'error' => $error
        ];
        return view('home/account_activation', $data);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FORGET PASSWORD
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Forget password page
     * @return string
     */
    public function forgot_password(): string
    {
        $data = [
            'slug' => 'login',
            'lang' => $this->request->getLocale(),
        ];
        return view('home/forgot_password', $data);
    }

    /**
     * Forget password script - send email with token for resetting the password
     * @return ResponseInterface
     */
    public function forgot_password_post(): ResponseInterface
    {
        return $this->response->setJSON([]);
    }

    /**
     * Reset password page
     * @param string $token
     * @return string
     */
    public function reset_password(string $token): string
    {
        $valid = false;
        if ($token == 'token') {
            $valid = true;
        }
        $data = [
            'slug'           => 'login',
            'lang'           => $this->request->getLocale(),
            'token_validity' => $valid,
        ];
        return view('home/reset_password', $data);
    }

    /**
     * Reset password script
     * @return ResponseInterface
     */
    public function reset_password_post(): ResponseInterface
    {
        return $this->response->setJSON([]);
    }
}
