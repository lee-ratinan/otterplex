<?php

namespace App\Controllers;

use App\Models\BusinessUserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{

    /**
     * Dashboard page
     * @return string
     */
    public function index(): string
    {
        $data    = [
            'slug'         => 'dashboard',
            'lang'         => $this->request->getLocale(),
        ];
        return view('admin/dashboard', $data);
    }

    /**
     * Profile page
     * @return string
     */
    public function profile(): string
    {
        $data    = [
            'slug'         => 'profile',
            'lang'         => $this->request->getLocale(),
        ];
        return view('admin/profile', $data);
    }

    /**
     * My Businesses page
     * @return string
     */
    public function my_businesses(): string
    {
        $session           = session();
        $businessUserModel = new BusinessUserModel();
        $myBusinesses      = $businessUserModel->getBusinessesByUserId($session->user_id);
        $data              = [
            'slug'         => 'my-businesses',
            'lang'         => $this->request->getLocale(),
            'myBusinesses' => $myBusinesses
        ];
        return view('admin/my-businesses', $data);
    }

    /**
     * Switch current business
     * @return ResponseInterface
     */
    public function switch_business(): ResponseInterface
    {
        $session              = session();
        $businessUserModel    = new BusinessUserModel();
        $target_business_slug = $this->request->getPost('target_business_slug');
        $business             = $businessUserModel->getBusinessesByUserId($session->user_id, true, $target_business_slug);
        if (!$business) {
            return $this->response->setJSON([
                'status' => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.business-inactive')
            ]);
        }
        $session->set([
            'user_role' => $business[0]['user_role'],
            'business'  => $business[0]
        ]);
        return $this->response->setJSON([
            'status' => STATUS_RESPONSE_OK,
            'message' => lang('System.response-msg.success.business-switched')
        ]);
    }

    /**
     * About page
     * @return string
     */
    public function about(): string
    {
        $data    = [
            'slug'         => 'about',
            'lang'         => $this->request->getLocale(),
        ];
        return view('admin/about', $data);
    }
}