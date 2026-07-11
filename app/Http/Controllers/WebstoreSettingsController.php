<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SellerVerification;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WebstoreSettingsController extends Controller
{
    public function edit(Request $request)
    {
        $userId = Auth::id();

        $type = $request->query('layout_type', 'webstore');

        if (!in_array($type, ['webstore', 'wet_market', 'sari_sari'])) {
            $type = 'webstore';
        }

        $store = SellerVerification::firstOrCreate(
            [
                'user_id' => $userId,
            ],
            [
                'id_type' => 'Not Provided Yet',
                'id_number' => 'PENDING_SETUP',
                'document_path' => 'storefront/placeholders/default.png',
                'status' => 'pending',
                'promotional_text' => 'Welcome to our webstore showcase arena!',
                'contact_representatives' => [],
                'certificates_data' => [],
                'banner_slider_paths' => [],
                'wet_market_banner_paths' => [],
                'sari_sari_banner_paths' => [],
            ]
        );

        $columnMap = [
            'webstore' => 'webstore_name',
            'wet_market' => 'wet_market_name',
            'sari_sari' => 'sari_sari_name',
        ];

        $bannerColumnMap = [
            'webstore' => 'banner_slider_paths',
            'wet_market' => 'wet_market_banner_paths',
            'sari_sari' => 'sari_sari_banner_paths',
        ];

        $activeColumn = $columnMap[$type];
        $activeBannerColumn = $bannerColumnMap[$type];

        if (empty($store->{$activeColumn})) {
            if (!empty($store->store_name)) {
                $store->{$activeColumn} = $store->store_name;
            } else {
                $mainStore = Store::where('user_id', $userId)->first();

                if ($mainStore) {
                    $store->{$activeColumn} = $mainStore->name;

                    if (empty($store->store_name)) {
                        $store->store_name = $mainStore->name;
                    }
                }
            }

            $store->save();
        }

        if (is_string($store->contact_representatives)) {
            $store->contact_representatives =
                json_decode($store->contact_representatives, true) ?? [];
        }

        if (is_string($store->certificates_data)) {
            $store->certificates_data =
                json_decode($store->certificates_data, true) ?? [];
        }

        $activeBanners = $store->{$activeBannerColumn} ?? [];

        if (is_string($activeBanners)) {
            $activeBanners = json_decode($activeBanners, true) ?? [];
        }

        if (!is_array($activeBanners)) {
            $activeBanners = [];
        }

        $store->{$activeBannerColumn} = $activeBanners;

        return view('vendor.webstore.edit', compact(
            'store',
            'type'
        ));
    }

    public function update(Request $request)
    {
        $userId = Auth::id();

        $store = SellerVerification::where('user_id', $userId)
            ->firstOrFail();

        $request->validate([
            'store_name' => ['required', 'string', 'max:150'],
            'layout_type' => [
                'required',
                'string',
                'in:webstore,wet_market,sari_sari',
            ],
            'new_banners' => ['nullable', 'array'],
            'new_banners.*' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:3072',
            ],
            'video_intro' => [
                'nullable',
                'file',
                'mimes:mp4,mov,avi',
                'max:30720',
            ],
            'promo_text' => ['nullable', 'string', 'max:2000'],
            'established' => [
                'nullable',
                'integer',
                'digits:4',
                'min:1900',
                'max:' . date('Y'),
            ],
            'hours_open' => ['nullable', 'string', 'max:100'],
            'rep_names' => ['nullable', 'array'],
            'rep_names.*' => ['nullable', 'string', 'max:100'],
            'new_certificate' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ]);

        $layoutType = $request->input('layout_type');

        $columnMap = [
            'webstore' => 'webstore_name',
            'wet_market' => 'wet_market_name',
            'sari_sari' => 'sari_sari_name',
        ];

        $bannerColumnMap = [
            'webstore' => 'banner_slider_paths',
            'wet_market' => 'wet_market_banner_paths',
            'sari_sari' => 'sari_sari_banner_paths',
        ];

        $bannerFolderMap = [
            'webstore' => 'storefront/webstore/banners',
            'wet_market' => 'storefront/wet-market/banners',
            'sari_sari' => 'storefront/sari-sari/banners',
        ];

        
        $promoColumnMap = [
            'webstore' => 'webstore_promotional_text',
            'wet_market' => 'wet_market_promotional_text',
            'sari_sari' => 'sari_sari_promotional_text',
        ];

        $establishedColumnMap = [
            'webstore' => 'webstore_established_year',
            'wet_market' => 'wet_market_established_year',
            'sari_sari' => 'sari_sari_established_year',
        ];

        $hoursColumnMap = [
            'webstore' => 'webstore_store_hours',
            'wet_market' => 'wet_market_store_hours',
            'sari_sari' => 'sari_sari_store_hours',
        ];

        $activeColumn = $columnMap[$layoutType];
        $activeBannerColumn = $bannerColumnMap[$layoutType];
        $activeBannerFolder = $bannerFolderMap[$layoutType];

        $store->{$activeColumn} = $request->input('store_name');

        if ($layoutType === 'webstore') {
            $store->store_name = $request->input('store_name');
        }

        $currentSliders = $store->{$activeBannerColumn} ?? [];

        if (is_string($currentSliders)) {
            $currentSliders = json_decode($currentSliders, true) ?? [];
        }

        if (!is_array($currentSliders)) {
            $currentSliders = [];
        }

        if ($request->hasFile('new_banners')) {
            foreach ($request->file('new_banners') as $bannerFile) {
                if ($bannerFile && $bannerFile->isValid()) {
                    $currentSliders[] = $bannerFile->store(
                        $activeBannerFolder,
                        'public'
                    );
                }
            }
        }

        $store->{$activeBannerColumn} = array_values($currentSliders);

        if ($layoutType === 'webstore') {
            $store->banner_path = !empty($currentSliders)
                ? $currentSliders[0]
                : null;
        }

        if (
            $layoutType === 'webstore'
            && $request->hasFile('video_intro')
        ) {
            if ($store->video_intro_path) {
                Storage::disk('public')->delete(
                    $store->video_intro_path
                );
            }

            $store->video_intro_path = $request
                ->file('video_intro')
                ->store('storefront/webstore/videos', 'public');
        }

        $store->{$promoColumnMap[$layoutType]} = $request->input('promo_text');
        $store->{$establishedColumnMap[$layoutType]} = $request->input('established');
        $store->{$hoursColumnMap[$layoutType]} = $request->input('hours_open');

        if ($request->has('rep_names')) {
            $representatives = [];

            foreach ($request->input('rep_names', []) as $name) {
                $cleanName = trim((string) $name);

                if ($cleanName !== '') {
                    $representatives[] = [
                        'name' => $cleanName,
                        'slug' => strtolower(
                            str_replace(' ', '-', $cleanName)
                        ),
                    ];
                }
            }

            $store->contact_representatives = $representatives;
        }

        if ($request->hasFile('new_certificate')) {
            $currentCertificates = $store->certificates_data ?? [];

            if (is_string($currentCertificates)) {
                $currentCertificates =
                    json_decode($currentCertificates, true) ?? [];
            }

            if (!is_array($currentCertificates)) {
                $currentCertificates = [];
            }

            $newCertPath = $request
                ->file('new_certificate')
                ->store('storefront/certificates', 'public');

            $currentCertificates[] = $newCertPath;
            $store->certificates_data = array_values($currentCertificates);
        }

        $store->save();

        return redirect()
            ->route('vendor.webstore.edit', [
                'layout_type' => $layoutType,
            ])
            ->with(
                'success',
                ucfirst(str_replace('_', ' ', $layoutType))
                . ' layout components updated successfully!'
            );
    }

    public function removeBanner(Request $request, $index)
    {
        $store = SellerVerification::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $layoutType = $request->input(
            'layout_type',
            $request->query('layout_type', 'webstore')
        );

        if (!in_array(
            $layoutType,
            ['webstore', 'wet_market', 'sari_sari']
        )) {
            $layoutType = 'webstore';
        }

        $bannerColumnMap = [
            'webstore' => 'banner_slider_paths',
            'wet_market' => 'wet_market_banner_paths',
            'sari_sari' => 'sari_sari_banner_paths',
        ];

        $activeBannerColumn = $bannerColumnMap[$layoutType];

        $sliders = $store->{$activeBannerColumn} ?? [];

        if (is_string($sliders)) {
            $sliders = json_decode($sliders, true) ?? [];
        }

        if (!is_array($sliders)) {
            $sliders = [];
        }

        if (isset($sliders[$index])) {
            Storage::disk('public')->delete($sliders[$index]);

            unset($sliders[$index]);

            $cleanSliders = array_values($sliders);

            $store->{$activeBannerColumn} = $cleanSliders;

            if ($layoutType === 'webstore') {
                $store->banner_path = !empty($cleanSliders)
                    ? $cleanSliders[0]
                    : null;
            }

            $store->save();
        }

        return redirect()
            ->route('vendor.webstore.edit', [
                'layout_type' => $layoutType,
            ])
            ->with(
                'success',
                'Selected '
                . ucfirst(str_replace('_', ' ', $layoutType))
                . ' banner removed.'
            );
    }

    public function removeCertificate($index)
    {
        $store = SellerVerification::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $certs = $store->certificates_data ?? [];

        if (is_string($certs)) {
            $certs = json_decode($certs, true) ?? [];
        }

        if (!is_array($certs)) {
            $certs = [];
        }

        if (isset($certs[$index])) {
            Storage::disk('public')->delete($certs[$index]);

            unset($certs[$index]);

            $store->certificates_data = array_values($certs);
            $store->save();
        }

        return redirect()
            ->back()
            ->with(
                'success',
                'Selected certificate asset removed.'
            );
    }
}
