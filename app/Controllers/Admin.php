<?php

namespace App\Controllers;

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
        $data    = [
            'slug'         => 'my-businesses',
            'lang'         => $this->request->getLocale(),
        ];
        return view('admin/my-businesses', $data);
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