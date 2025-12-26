<?php

namespace App\Controllers;

use App\Models\BranchMasterModel;
use App\Models\BusinessMasterModel;
use App\Models\ProductMasterModel;
use App\Models\ProductVariantModel;
use App\Models\ServiceMasterModel;
use App\Models\ServiceVariantModel;
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
        $local_names                            = json_decode($business['business_local_names'], true);
        $type_names                             = json_decode($business['type_local_names'], true);
        $mart_meta_descriptions                 = json_decode($business['mart_meta_description'], true);
        $mart_meta_keywords_array               = json_decode($business['mart_meta_keywords'], true);
        $mart_store_intro_paragraphs            = json_decode($business['mart_store_intro_paragraph'], true);
        $business['social_media']               = json_decode($business['social_media'], true);
        $business['country']                    = get_country_list($business['country_code']);
        $business['business_name']              = $local_names[$languageCode] ?? $business['business_name'];
        $business['type_name']                  = $type_names[$languageCode] ?? $business['type_name'];
        $business['mart_meta_description']      = $mart_meta_descriptions[$languageCode] ?? '';
        $business['mart_meta_keywords']         = $mart_meta_keywords_array[$languageCode] ?? '';
        $business['mart_store_intro_paragraph'] = $mart_store_intro_paragraphs[$languageCode] ?? '';
        unset($business['business_local_names']);
        unset($business['type_local_names']);
        if (!empty($business['business_logo'])) {
            $business['business_logo'] = base_url('/file/business_' . $business['business_logo']);
        }
        // BRANCHES
        $branchModel = new BranchMasterModel();
        $branches    = $branchModel
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
        // SERVICES
        $serviceModel = new ServiceMasterModel();
        $svModel      = new ServiceVariantModel();
        $servicesRaw  = $serviceModel->where('business_id', $business['id'])->findAll();
        $services     = [];
        $sId          = [];
        foreach ($servicesRaw as $service) {
            $local_names              = json_decode($service['service_local_names'], true);
            $service['service_name']  = $local_names[$languageCode] ?? $service['service_name'];
            unset($service['service_local_names']);
            if (!empty($service['service_image'])) {
                $service['service_image'] = base_url('file/' . $service['service_image']);
            }
            $services[$service['id']] = $service;
            $sId[]                    = $service['id'];
        }
        $business['services'] = [];
        if (!empty($sId)) {
            $sVariantRaw = $svModel->whereIn('service_id', $sId)->findAll();
            foreach ($sVariantRaw as $sv) {
                $local_names = json_decode($sv['variant_local_names'], true);
                $sv['variant_name'] = $local_names[$languageCode] ?? $sv['variant_name'];
                unset($sv['variant_local_names']);
                $services[$sv['service_id']]['variants'][] = $sv;
            }
            $business['services'] = $services;
        }
        // PRODUCTS
        $productModel = new ProductMasterModel();
        $pvModel      = new ProductVariantModel();
        $productRaw   = $productModel->where('business_id', $business['id'])->findAll();
        $products     = [];
        $pId          = [];
        foreach ($productRaw as $product) {
            $local_names              = json_decode($product['product_local_names'], true);
            $product['product_name']  = $local_names[$languageCode] ?? $product['product_name'];
            unset($product['product_local_names']);
            if (!empty($product['product_image'])) {
                $product['product_image'] = base_url('file/' . $product['product_image']);
            }
            $products[$product['id']] = $product;
            $pId[]                    = $product['id'];
        }
        $business['products'] = [];
        if (!empty($pId)) {
            $pVariantRaw = $pvModel->whereIn('product_id', $pId)->findAll();
            foreach ($pVariantRaw as $pv) {
                $local_names = json_decode($pv['variant_local_names'], true);
                $pv['variant_name'] = $local_names[$languageCode] ?? $pv['variant_name'];
                unset($pv['variant_local_names']);
                $products[$pv['product_id']]['variants'][] = $pv;
            }
            $business['products'] = $products;
        }
        return $this->response->setJSON([
            'query'    => $query,
            'business' => $business
        ]);
    }
}