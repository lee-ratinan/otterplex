<?php

namespace App\Controllers;

use App\Models\BusinessMasterModel;
use CodeIgniter\HTTP\ResponseInterface;

class Api extends BaseController
{

    public function business_search(string $languageCode, string $countryCode): ResponseInterface
    {
        $query         = $this->request->getGet('query');
        $businessModel = new BusinessMasterModel();
        $countryCode   = strtolower($countryCode);
        $languageCode  = strtolower($languageCode);
        $rawResults    = $businessModel
            ->select('business_master.*, business_type.type_name, business_type.type_local_names')
            ->join('business_type', 'business_type.id = business_master.business_type_id')
            ->where('country_code', $countryCode)
            ->groupStart()
            ->like('business_name', $query)
            ->orLike('business_local_names', $query)
            ->groupEnd()
            ->orderBy('business_name')
            ->limit(10)
            ->findAll();
        $results       = [];
        foreach ($rawResults as $row) {
            $local_names = json_decode($row['business_local_names'], true);
            $name        = $local_names[$languageCode] ?? $row['business_name'];
            $types       = json_decode($row['type_local_names'], true);
            $type        = $types[$languageCode] ?? $row['business_type'];
            $results[]   = [
                'link'         => getenv('marketplace_site') . '@' . $row['business_slug'],
                'businessType' => $type,
                'name'         => $name,
                'businessLogo' => (!empty($row['business_logo']) ? base_url('/file/business_' . $row['business_logo']) : '')
            ];
        }
        return $this->response->setJSON([
            'query' => $query,
            'results' => $results
        ]);
    }
}