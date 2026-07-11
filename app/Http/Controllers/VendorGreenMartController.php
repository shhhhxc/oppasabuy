<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;

class VendorGreenMartController extends Controller
{
    public function showStore($id, Request $request)
    {
        $store = Store::with('verification')->findOrFail($id);

        $type = $request->query('type', 'wet-market');

        if (!in_array($type, ['wet-market', 'sari-sari'])) {
            $type = 'wet-market';
        }

        $marketType = match ($type) {
            'wet-market' => 'wet_market',
            'sari-sari' => 'sari_sari',
        };

        $products = Product::where('seller_id', $store->user_id)
            ->where('channel', 'green_market')
            ->whereJsonContains(
                'meta_data->green_market_type',
                $marketType
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $bannerColumn = match ($type) {
            'wet-market' => 'wet_market_banner_paths',
            'sari-sari' => 'sari_sari_banner_paths',
        };

        $logoColumn = match ($type) {
            'wet-market' => 'wet_market_logo',
            'sari-sari' => 'sari_sari_logo',
        };

        $promoColumn = match ($type) {
            'wet-market' => 'wet_market_promotional_text',
            'sari-sari' => 'sari_sari_promotional_text',
        };

        $establishedColumn = match ($type) {
            'wet-market' => 'wet_market_established_year',
            'sari-sari' => 'sari_sari_established_year',
        };

        $hoursColumn = match ($type) {
            'wet-market' => 'wet_market_store_hours',
            'sari-sari' => 'sari_sari_store_hours',
        };

        $representativesColumn = match ($type) {
            'wet-market' => 'wet_market_contact_representatives',
            'sari-sari' => 'sari_sari_contact_representatives',
        };

        $certificatesColumn = match ($type) {
            'wet-market' => 'wet_market_certificates_data',
            'sari-sari' => 'sari_sari_certificates_data',
        };

        $bannerPaths = [];
        $logoPath = null;
        $promoText = null;
        $establishedYear = null;
        $storeHours = null;
        $representatives = [];
        $certificates = [];

        if ($store->verification) {
            $bannerPaths = $store->verification->{$bannerColumn} ?? [];

            if (is_string($bannerPaths)) {
                $bannerPaths = json_decode($bannerPaths, true) ?? [];
            }

            if (!is_array($bannerPaths)) {
                $bannerPaths = [];
            }

            $logoPath = $store->verification->{$logoColumn} ?? null;
            $promoText = $store->verification->{$promoColumn} ?? null;
            $establishedYear = $store->verification->{$establishedColumn} ?? null;
            $storeHours = $store->verification->{$hoursColumn} ?? null;

            $representatives = $store->verification->{$representativesColumn} ?? [];

            if (is_string($representatives)) {
                $representatives = json_decode($representatives, true) ?? [];
            }

            if (!is_array($representatives)) {
                $representatives = [];
            }

            $certificates = $store->verification->{$certificatesColumn} ?? [];

            if (is_string($certificates)) {
                $certificates = json_decode($certificates, true) ?? [];
            }

            if (!is_array($certificates)) {
                $certificates = [];
            }
        }

        $storeName = $store->name;

        if ($store->verification) {
            if (
                $type === 'wet-market' &&
                !empty($store->verification->wet_market_name)
            ) {
                $storeName = $store->verification->wet_market_name;
            } elseif (
                $type === 'sari-sari' &&
                !empty($store->verification->sari_sari_name)
            ) {
                $storeName = $store->verification->sari_sari_name;
            } elseif (!empty($store->verification->store_name)) {
                $storeName = $store->verification->store_name;
            }
        }

        return view('vendor.greenmart.store', compact(
            'store',
            'type',
            'storeName',
            'bannerPaths',
            'logoPath',
            'promoText',
            'establishedYear',
            'storeHours',
            'representatives',
            'certificates',
            'products'
        ));
    }
}
