<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\BadRequestException;

class File extends BaseController
{

    /**
     * @param string $file_name
     * @param int $download
     * @return void
     */
    public function index(string $file_name, int $download = 0): void
    {
        // 1. FIX FILE PATH FIRST - IF REQUIRED
        $file_name          = str_replace('profile_picture_', 'profile_pictures/profile_', $file_name);
        $file_name          = str_replace('business_logo_', 'business_logos/logo_', $file_name);
        // 2. CHECK SESSION FOR INTERNAL FOLDERS
//        $file_name_sections = explode('/', $file_name);
//        $internal_folders   = ['profile_pictures'];
//        if (in_array($file_name_sections[0], $internal_folders)) {
//            $session = session();
//            if (!isset($session->user_id)) {
//                throw new BadRequestException('Unauthorized access');
//            }
//        }
        // 3. CHECK FILE EXISTS
        $file      = realpath(WRITEPATH . 'uploads/' . $file_name);
        if (!file_exists($file)) {
            throw PageNotFoundException::forPageNotFound();
        }
        $file_extension = pathinfo($file, PATHINFO_EXTENSION);
        $mime_types     = [
            'pdf'  => 'application/pdf',
            'txt'  => 'text/plain',
            'gif'  => 'image/gif',
            'png'  => 'image/png',
            'jpeg' => 'image/jpg',
            'jpg'  => 'image/jpg',
            'webp' => 'image/webp',
            'svg'  => 'image/svg+xml'
        ];
        $mime_type = $mime_types[$file_extension] ?? 'text/plain';
        // RETURN FILE
        if (0 == $download) {
            $this->response->setHeader('Content-Type', $mime_type)
                ->setHeader('Content-Disposition', 'inline')
                ->setHeader('Content-Length', filesize($file))
                ->setBody(file_get_contents($file))
                ->send();
        } else {
            $this->response->setHeader('Content-Type', $mime_type)
                ->setHeader('Content-Disposition', 'attachment; filename="' . basename($file) . '"')
                ->setHeader('Content-Length', filesize($file))
                ->setBody(file_get_contents($file))
                ->send();
        }
    }
}