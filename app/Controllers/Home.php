<?php

namespace App\Controllers;

use App\Models\BusinessUserModel;
use App\Models\LogActivityModel;
use App\Models\UserMasterModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

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
            'slug' => 'login',
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
        return $this->response->setJSON([]);
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
