<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // LOGIN
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Login page
     * @return string
     */
    public function login(): string
    {
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
        return $this->response->setJSON([]);
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
