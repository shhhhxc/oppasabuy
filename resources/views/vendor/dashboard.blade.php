@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f6f8fb] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-6">

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Top Header Branding Bar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl border border-gray-100 flex items-center justify-center overflow-hidden">
                    @if($store && $store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xl font-bold text-blue-800">{{ substr($store->name ?? 'ST', 0, 2) }}</span>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $store->name ?? 'My Shop' }}</h1>
                    <div class="flex flex-wrap gap-1.5 mt-1">
                        @if(isset($store->store_types) && is_array($store->store_types))
                            @foreach($store->store_types as $type)
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-800 text-[10px] font-black rounded-md uppercase tracking-wider border border-blue-100">
                                    {{ str_replace('_', ' ', $type) }}
                                </span>
                            @endforeach
                        @else
                            <span class="px-2 py-0.5 bg-gray-50 text-gray-600 text-[10px] font-black rounded-md uppercase tracking-wider border border-gray-100">
                                Standard Vendor
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="text-sm text-gray-500 sm:text-right">
                <p class="font-medium text-gray-800">{{ $store->business_email ?? auth()->user()->email }}</p>
                <p class="text-xs">{{ $store->address ?? 'Online Only' }}</p>
            </div>
        </div>

        {{-- Check first if 'green_market' exists in store_types channel array --}}
        @if(isset($store->store_types) && is_array($store->store_types) && in_array('green_market', $store->store_types))
            
            {{-- Now check the sub-types from the green_market_type column --}}
            @if(isset($store->green_market_type) && is_array($store->green_market_type))
                
                {{-- Wet Market Branding Studio --}}
                @if(in_array('wet_market', $store->green_market_type))
                    <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-gradient-to-r from-white to-emerald-50/30">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-emerald-600 text-white rounded-xl flex items-center justify-center shadow-md shadow-emerald-200 mt-1 sm:mt-0 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-gray-900">Wet Market Branding Studio</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Configure your wet market retail experience. Upload setup banners, layout configurations, and freshly-stocked marketplace asset displays.</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto shrink-0">
                            <a href="{{ route('seller-store', ['id' => $store->id, 'type' => 'wet-market']) }}" class="text-center px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-black uppercase tracking-wider rounded-xl transition no-underline">
                                Preview Storefront
                            </a>
                            <a href="{{ route('vendor.webstore.edit', ['layout_type' => 'wet_market']) }}" class="text-center px-5 py-3 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-sm transition no-underline">
                                Configure Wet Market &rarr;
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Sari-Sari Store Branding Studio --}}
                @if(in_array('sari_sari', $store->green_market_type))
                    <div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-gradient-to-r from-white to-amber-50/30">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-md shadow-amber-200 mt-1 sm:mt-0 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-gray-900">Sari-Sari Store Branding Studio</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Customize your local sari-sari neighborhood hub digital display. Add announcement boards, micro-retail identity banners, and operation schedules.</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto shrink-0">
                            <a href="{{ route('seller-store', ['id' => $store->id, 'type' => 'sari-sari']) }}" class="text-center px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-black uppercase tracking-wider rounded-xl transition no-underline">
                                Preview Storefront
                            </a>
                            <a href="{{ route('vendor.webstore.edit', ['layout_type' => 'sari_sari']) }}" class="text-center px-5 py-3 bg-amber-600 hover:bg-amber-700 text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-sm transition no-underline">
                                Configure Sari-Sari &rarr;
                            </a>
                        </div>
                    </div>
                @endif

            @endif
        @endif

        {{-- Custom Webstore Branding Studio --}}
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-gradient-to-r from-white to-blue-50/30">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-md shadow-blue-200 mt-1 sm:mt-0 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-gray-900">Custom Webstore Branding Studio</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Upload your store banners, introduction videos, official business certifications, and configure live chat customer representatives.</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto shrink-0">
                
                <a href="{{ route('vendor.webstore.edit', ['layout_type' => 'webstore']) }}" class="text-center px-5 py-3 bg-blue-800 hover:bg-blue-900 text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-sm transition no-underline">
                    Configure Layout Assets &rarr;
                </a>
            </div>
        </div>

        {{-- Section Divider --}}
        <div>
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Sales Channel Inventory Modules</h2>
            <p class="text-xs text-gray-500">Select an active marketplace channel module below to manage and upload matching product catalogs.</p>
        </div>

        {{-- Channel-Specific Upload Grid Modules --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            {{-- Module 1: Oppa Mall --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:border-blue-500 transition group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-800 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-800 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Oppa Mall</h3>
                    <p class="text-xs text-gray-500 mt-1">Upload global retail items into the central B2C marketplace directory feed shared with all sellers.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-50">
                    <a href="{{ route('seller.products.create', ['channel' => 'oppa_mall']) }}" class="inline-flex items-center text-xs font-black uppercase tracking-wider text-blue-800 group-hover:text-blue-600 transition no-underline">
                        + Upload Item &rarr;
                    </a>
                </div>
            </div>

            {{-- Module 2: Own Webstore --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:border-blue-500 transition group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-800 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-800 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Own Webstore</h3>
                    <p class="text-xs text-gray-500 mt-1">Publish exclusive customized products strictly isolated to your personal storefront layout route.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-50">
                    <a href="{{ route('seller.products.create', ['channel' => 'own_webstore']) }}" class="inline-flex items-center text-xs font-black uppercase tracking-wider text-blue-800 group-hover:text-blue-600 transition no-underline">
                        + Upload Web &rarr;
                    </a>
                </div>
            </div>

            {{-- Module 3: Green Market --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:border-blue-500 transition group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-800 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-800 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Green Market</h3>
                    <p class="text-xs text-gray-500 mt-1">List perishable organics, weight-based crops, and items with fresh harvest tracking.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-50">
                    <a href="{{ route('seller.products.create', ['channel' => 'green_market']) }}" class="inline-flex items-center text-xs font-black uppercase tracking-wider text-emerald-800 group-hover:text-emerald-600 transition no-underline">
                        + Add Fresh Harvest &rarr;
                    </a>
                </div>
            </div>

            {{-- Module 4: Personal Care & Services --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:border-blue-500 transition group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-purple-50 text-purple-800 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-800 group-hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Personal Care</h3>
                    <p class="text-xs text-gray-500 mt-1">Configure service menus, upload item brochures, mapping positions, and direct inquiries.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-50">
                    <a href="{{ route('seller.products.create', ['channel' => 'personal_care']) }}" class="inline-flex items-center text-xs font-black uppercase tracking-wider text-purple-800 group-hover:text-purple-600 transition no-underline">
                        + Setup Service &rarr;
                    </a>
                </div>
            </div>

        </div>

       
        </div>
    </div>
</div>
@endsection