<?php

namespace App\Controllers;

use App\Models\BranchMasterModel;
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

    public function business_retrieve(string $languageCode, string $countryCode): ResponseInterface
    {
        $this->request->setLocale($languageCode);
        $session       = session();
        $session->set('lang', $languageCode);
        $query         = $this->request->getGet('business-slug');
        $businessModel = new BusinessMasterModel();
        $branchModel   = new BranchMasterModel();
        // BUSINESS
        $business      = $businessModel
            ->select('business_master.*, business_type.type_name, business_type.type_local_names')
            ->join('business_type', 'business_type.id = business_master.business_type_id')
            ->where('business_slug', $query)
            ->where('country_code', $countryCode)
            ->first();
        if (empty($business)) {
            return $this->response->setJSON([
                'query'    => $query,
                'business' => []
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }
        $local_names               = json_decode($business['business_local_names'], true);
        $business['business_name'] = $local_names[$languageCode] ?? $business['business_name'];
        $type_names                = json_decode($business['type_local_names'], true);
        $business['type_name']     = $type_names[$languageCode] ?? $business['type_name'];
        $business['mart_store_intro_paragraph'] = nl2br($business['mart_store_intro_paragraph']);
        unset($business['business_local_names']);
        unset($business['type_local_names']);
        if (!empty($business['business_logo'])) {
            $business['business_logo'] = base_url('/file/business_' . $business['business_logo']);
        }
        // BRANCHES
        $branches = $branchModel
            ->where('business_id', $business['id'])
            ->findAll();
        foreach ($branches as $i => $branch) {
            $local_names                 = json_decode($branch['branch_local_names'], true);
            $branches[$i]['branch_name'] = $local_names[$languageCode] ?? $branch['branch_name'];
            unset($branches[$i]['branch_local_names']);
            $branches[$i]['subdivision'] = get_country_subdivisions($business['country_code'], $branch['subdivision_code']);
            unset($branches[$i]['subdivision_code']);
        }
        $business['branches'] = $branches;
        return $this->response->setJSON([
            'query'    => $query,
            'business' => $business
        ]);
    }
}