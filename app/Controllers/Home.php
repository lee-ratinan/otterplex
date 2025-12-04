<?php

namespace App\Controllers;

use App\Models\UserMasterModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // LOGIN
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Login page
     * @return string|RedirectResponse
     */
    public function login(): string|RedirectResponse
    {
        if (is_login()) {
            return redirect()->to('admin/dashboard');
        }
        $data = [
            'slug' => 'login',
            'lang' => $this->request->getLocale(),
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
            $user_name  = $this->request->getPost('username');
            $password   = $this->request->getPost('password');
            $user_model = new UserMasterModel();
            $user_row   = $user_model->where('email_address', $user_name)->first();
            if (empty($user_row) || !password_verify($password, $user_row['password_hash'])) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                    ->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.wrong-credentials')
                    ]);
            }
            if ($user_model::ACCOUNT_STATUS_ACTIVE != $user_row['account_status']) {
                return $this->response
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                    ->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.inactive-account')
                    ]);
            }
            // Create session here
            $session = session();
            $session->set([
                'session_id'     => session_id(),
                'session_expiry' => date(DATETIME_FORMAT_DB, strtotime('+5 hour')),
                'user'           => [
                    'user_id'        => $user_row['id'],
                    'email'          => $user_row['email_address'],
                    'full_name'      => $user_row['user_name_first'] . ' ' . $user_row['user_name_last'],
                    'account_status' => $user_row['account_status'],
                    'user_type'      => $user_row['user_type'],
                    'user_gender'    => $user_row['user_gender'],
                ],
                'business_ids'   => [],
                'business'       => null
            ]);
            return $this->response->setJSON([
                'status' => STATUS_RESPONSE_OK
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
