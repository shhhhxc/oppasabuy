@extends('layouts.app')

@section('content')
@php
    // Paraan 2: Dynamic Column Mapping depende sa layout context profile
    $columnMap = [
        'webstore'   => 'webstore_name',
        'wet_market' => 'wet_market_name',
        'sari_sari'  => 'sari_sari_name',
    ];
    $activeColumn = $columnMap[$type] ?? 'webstore_name';
@endphp

@php
    $promoColumnMap = [
        'webstore'=>'webstore_promotional_text',
        'wet_market'=>'wet_market_promotional_text',
        'sari_sari'=>'sari_sari_promotional_text',
    ];
    $establishedColumnMap = [
        'webstore'=>'webstore_established_year',
        'wet_market'=>'wet_market_established_year',
        'sari_sari'=>'sari_sari_established_year',
    ];
    $hoursColumnMap = [
        'webstore'=>'webstore_store_hours',
        'wet_market'=>'wet_market_store_hours',
        'sari_sari'=>'sari_sari_store_hours',
    ];
    $repsColumnMap = [
        'webstore'=>'webstore_contact_representatives',
        'wet_market'=>'wet_market_contact_representatives',
        'sari_sari'=>'sari_sari_contact_representatives',
    ];
    $certsColumnMap = [
        'webstore'=>'webstore_certificates_data',
        'wet_market'=>'wet_market_certificates_data',
        'sari_sari'=>'sari_sari_certificates_data',
    ];
    $activePromoColumn=$promoColumnMap[$type];
    $activeEstablishedColumn=$establishedColumnMap[$type];
    $activeHoursColumn=$hoursColumnMap[$type];
    $activeRepsColumn=$repsColumnMap[$type];
    $activeCertsColumn=$certsColumnMap[$type];
@endphp

<div class="min-h-screen bg-[#f6f8fb] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Status Notification Alerts --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-semibold">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Section Breadcrumb Navigation Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">
                    @if($type === 'wet_market')
                        Wet Market Storefront Configurations
                    @elseif($type === 'sari_sari')
                        Sari-Sari Store Storefront Configurations
                    @else
                        Webstore Storefront Configurations
                    @endif
                </h1>
                <p class="text-xs text-gray-500 mt-0.5">
                    @if($type === 'wet_market')
                        Configure your wet market retail experience. Upload setup banners, layout configurations, and freshly-stocked marketplace asset displays.
                    @elseif($type === 'sari_sari')
                        Customize your local sari-sari neighborhood hub digital display. Add announcement boards, micro-retail identity banners, and operation schedules.
                    @else
                        Customize the visual assets, branding elements, promotional text banners, and live customer reps.
                    @endif
                </p>
            </div>
            <a href="{{ route('vendor.dashboard') }}" class="text-xs font-bold text-blue-800 hover:underline">&larr; Back to Dashboard</a>
        </div>

        {{-- 
          Unified Form Route Submission to resolve RouteNotFoundExceptions.
          All modes share the single webstore.update pipeline securely.
        --}}
        <form action="{{ route('vendor.webstore.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Hidden Field payload to track dynamic context block layout flags --}}
            <input type="hidden" name="layout_type" value="{{ $type }}">

            {{-- Component Card 1: Visual Media Management (Banner & Video Intro) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-2">1. Visual Branding Components</h3>
                
                <div class="grid grid-cols-1 {{ $type === 'webstore' ? 'md:grid-cols-2' : '' }} gap-6">
                    {{-- Multi Banner Carousel Sliders Area Workspace --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Storefront Slider Banners</label>
                        
                        @php
                            $bannerColumnMap = [
                                'webstore' => 'banner_slider_paths',
                                'wet_market' => 'wet_market_banner_paths',
                                'sari_sari' => 'sari_sari_banner_paths',
                            ];

                            $activeBannerColumn = $bannerColumnMap[$type] ?? 'banner_slider_paths';

                            $sliders = $store->{$activeBannerColumn} ?? [];

                            if (is_string($sliders)) {
                                $sliders = json_decode($sliders, true) ?? [];
                            }

                            if (!is_array($sliders)) {
                                $sliders = [];
                            }
                        @endphp

                        @if(!empty($sliders) && count($sliders) > 0)
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                @foreach($sliders as $idx => $slidePath)
                                    <div class="relative group border border-gray-200 rounded-xl overflow-hidden h-24 bg-gray-50">
                                        <img src="{{ asset('storage/' . $slidePath) }}" class="w-full h-full object-cover" alt="Store Banner Slider">
                                        <button type="button" onclick="event.preventDefault(); document.getElementById('delete-banner-form-{{ $idx }}').submit();" class="absolute inset-0 bg-rose-900/80 text-white font-black text-[10px] uppercase tracking-wider flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-150">
                                            Delete Slide
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <input type="file" name="new_banners[]" multiple class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-[10px] text-gray-400">Upload single or multiple assets simultaneously to activate the layout sliding sequence. (Max 3MB per graphic. Format: JPG, PNG, WEBP)</p>
                    </div>

                    @if($type === 'webstore')
                        {{-- Introduction Video Upload --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Store Video Presentation</label>

                            @if($store->video_intro_path)
                                <div class="w-full h-24 rounded-xl overflow-hidden border border-gray-100 bg-black mb-2">
                                    <video src="{{ asset('storage/' . $store->video_intro_path) }}" controls class="w-full h-full object-contain"></video>
                                </div>
                            @endif

                            <input type="file" name="video_intro" accept="video/mp4,video/quicktime" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                            <p class="text-[10px] text-gray-400">
                                Short store background clip or walkthrough teaser (Max 30MB. Format: MP4, MOV)
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Component Card 2: Core Identity & Operational Settings --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-2">2. Store Identity & Operational Stats</h3>
                
                {{-- Store Name Input Field (Dynamic Column Connection) --}}
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Store Name</label>
                    
                    {{-- Gagamitin nito ang specific column value (e.g. wet_market_name) depende sa layout --}}
                    <input type="text" 
                           name="store_name" 
                           value="{{ old('store_name', $store->$activeColumn ?? $store->store_name) }}" 
                           class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-semibold text-gray-900" 
                           placeholder="Enter your official store name" 
                           required>
                           
                    <p class="text-[10px] text-gray-400">Changing this updates your primary business branding header contextually for this current layout view mode.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    {{-- Open Since / Established Input --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Open Since / Established Year</label>
                        <input type="number" name="established" value="{{ old('established', $store->{$activeEstablishedColumn}) }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., 1997" min="1900" max="{{ date('Y') }}">
                    </div>

                    {{-- Business Service Hours Range Input --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Operational Store Hours Range</label>
                        <input type="text" name="hours_open" value="{{ old('hours_open', $store->{$activeHoursColumn}) }}" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., 8:30 AM to 5:30 PM">
                    </div>
                </div>

                <div class="space-y-2 pt-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Promotional Text / Announcement Broadcast</label>
                    <textarea name="promo_text" rows="3" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400" placeholder="e.g., UP TO 50% OFF on all local items this weekend!">{{ old('promo_text', $store->{$activePromoColumn}) }}</textarea>
                    <p class="text-[10px] text-gray-400">This highlights message blocks directly under the header panel of your store layout.</p>
                </div>
            </div>

            {{-- Component Card 3: Live Messaging Customer Support Staffing --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-2">3. Chat Customer Representatives</h3>
                <p class="text-xs text-gray-500">Provide names for customer service channels so buyers can launch focused conversation pipelines directly from your storefront profile.</p>

                <div id="reps-form-container" class="space-y-3">
                    @php
                        $reps = is_string($store->{$activeRepsColumn}) ? json_decode($store->{$activeRepsColumn}, true) : ($store->{$activeRepsColumn} ?? []);
                    @endphp

                    @if(!empty($reps) && count($reps) > 0)
                        @foreach($reps as $index => $rep)
                            <div class="flex items-center gap-2 rep-row">
                                <input type="text" name="rep_names[]" value="{{ $rep['name'] }}" class="flex-1 rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Representative Name (e.g., Juan Carlo)">
                                <button type="button" onclick="this.parentElement.remove()" class="p-2.5 bg-gray-50 text-rose-600 rounded-xl hover:bg-rose-50 transition text-xs font-bold">Remove</button>
                            </div>
                        @endforeach
                    @else
                        <div class="flex items-center gap-2 rep-row">
                            <input type="text" name="rep_names[]" class="flex-1 rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Representative Name (e.g., Juan Carlo)">
                            <button type="button" onclick="this.parentElement.remove()" class="p-2.5 bg-gray-50 text-rose-600 rounded-xl hover:bg-rose-50 transition text-xs font-bold">Remove</button>
                        </div>
                    @endif
                </div>

                <button type="button" id="btn-add-rep" class="inline-flex items-center text-xs font-black text-blue-800 hover:text-blue-600 mt-2">
                    + Add New Staff Representative Channel Slot
                </button>
            </div>

            {{-- Component Card 4: Store Certifications / Trust Badges --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 pb-2">4. Official Certifications & Legal Trust Badges</h3>
                
                @php
                    $certs = is_string($store->{$activeCertsColumn}) ? json_decode($store->{$activeCertsColumn}, true) : ($store->{$activeCertsColumn} ?? []);
                @endphp

                @if(!empty($certs) && count($certs) > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                        @foreach($certs as $idx => $certPath)
                            <div class="relative group border border-gray-100 rounded-xl overflow-hidden bg-gray-50 h-24">
                                <img src="{{ asset('storage/' . $certPath) }}" class="w-full h-full object-contain p-2" alt="Certificate">
                                <button type="button" onclick="event.preventDefault(); document.getElementById('delete-cert-form-{{ $idx }}').submit();" class="absolute inset-0 bg-rose-900/80 text-white font-black text-[10px] uppercase tracking-wider flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-150">
                                    Delete Image
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Upload New Certificate Document Image</label>
                    <input type="file" name="new_certificate" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-[10px] text-gray-400">Attach active licensing, trade permissions, or business awards.</p>
                </div>
            </div>

            {{-- Platform Actions Control Bar Footer Panel --}}
            <div class="flex items-center justify-end gap-3 pt-4">
                <button type="submit" class="px-6 py-3 bg-blue-800 text-white rounded-xl text-xs font-black uppercase tracking-wider hover:bg-blue-900 transition shadow-sm">
                    Save Changes & Update 
                    @if($type === 'wet_market') Wet Market @elseif($type === 'sari_sari') Sari-Sari @else Webstore @endif
                </button>
            </div>
        </form>

        {{-- Hidden Removal Actions (Matched to routing patterns in web.php) --}}
        @if(!empty($sliders))
            @foreach($sliders as $idx => $slidePath)
                <form id="delete-banner-form-{{ $idx }}" action="{{ route('vendor.webstore.banner.delete', $idx) }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="layout_type" value="{{ $type }}">
                </form>
            @endforeach
        @endif

        @if(!empty($certs))
            @foreach($certs as $idx => $certPath)
                <form id="delete-cert-form-{{ $idx }}" action="{{ route('vendor.webstore.cert.delete', $idx) }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="layout_type" value="{{ $type }}">
                </form>
            @endforeach
        @endif

    </div>
</div>

<script>
    document.getElementById('btn-add-rep').addEventListener('click', function() {
        const container = document.getElementById('reps-form-container');
        const newRow = document.createElement('div');
        newRow.className = 'flex items-center gap-2 rep-row';
        newRow.innerHTML = `
            <input type="text" name="rep_names[]" class="flex-1 rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Representative Name (e.g., Juan Carlo)">
            <button type="button" onclick="this.parentElement.remove()" class="p-2.5 bg-gray-50 text-rose-600 rounded-xl hover:bg-rose-50 transition text-xs font-bold">Remove</button>
        `;
        container.appendChild(newRow);
    });
</script>
@endsection