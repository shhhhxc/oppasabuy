@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f6f8fb] pt-12 pb-20" x-data="{ openTracking: false }">
    <div class="max-w-7xl mx-auto px-6">
        
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-bold flex items-center">
                <i class="bi bi-check-circle-fill mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-wrap justify-between items-end mb-10 gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-blue-700 font-bold text-lg m-0">My Space</h3>
                    
                    @if(auth()->user()->is_verified)
                        {{-- Verified State --}}
                        <a href="{{ route('vendor.dashboard') }}" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-black text-white rounded-full text-xs font-black no-underline transition shadow-sm uppercase tracking-wider">
                            <i class="bi bi-speedometer2 mr-1"></i> Go to Sellers Dashboard
                        </a>
                    @elseif(auth()->user()->sellerVerification && auth()->user()->sellerVerification->status === 'pending')
                        {{-- Pending State --}}
                        <button type="button" data-bs-toggle="modal" data-bs-target="#verificationPendingModal" class="inline-flex items-center px-3 py-1 bg-amber-500 hover:bg-black text-white rounded-full text-xs font-black transition shadow-sm uppercase tracking-wider border-0 cursor-pointer">
                            <i class="bi bi-hourglass-split mr-1"></i> Verification Pending
                        </button>
                    @else
                        {{-- Unapplied / Regular Buyer State --}}
                        <a href="{{ route('vendor.register') }}" class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-black text-white rounded-full text-xs font-black no-underline transition shadow-sm uppercase tracking-wider">
                            <i class="bi bi-shop mr-1"></i> Be a Seller today!
                        </a>
                    @endif
                </div>
                
                <h1 class="text-3xl font-extrabold text-gray-900 m-0">
                    Annyeong, {{ auth()->user()->name }} 
                </h1>
            </div>
            
            {{-- Top Right Context Status Card --}}
            <div class="bg-white border border-gray-100 px-4 py-2 rounded-2xl shadow-sm flex items-center space-x-3">
                @if(auth()->user()->is_verified)
                    <div class="h-3 w-3 rounded-full bg-green-500"></div>
                    <span class="text-sm font-bold text-gray-700">Verified Seller</span>
                @elseif(auth()->user()->sellerVerification && auth()->user()->sellerVerification->status === 'pending')
                    <div class="h-3 w-3 rounded-full bg-amber-500 animate-pulse"></div>
                    <span class="text-sm font-bold text-gray-700">Verification Pending</span>
                @else
                    <div class="h-3 w-3 rounded-full bg-gray-400"></div>
                    <span class="text-sm font-bold text-gray-700">Verified Buyer</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- Sidebar --}}
            <div class="lg:col-span-3">
                <div class="space-y-2 mb-8">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#shippingModal" class="block w-full text-left text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1 bg-transparent border-0 cursor-pointer">Address</button>
                       <a href="{{ route('membership.index') }}" class="block text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1">Membership</a>
                    <a href="{{ route('seller.upgrade') }}" class="block text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1">Upgrade Plan</a>
                {{-- Message Link With Real-Time Notification Counter Badge --}}
            <a href="{{ route('rooms.show', $room->id ?? 1) }}" class="inline-flex items-center text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1 relative">
                <span>Message</span>
                
                @php
                    $count = $globalUnreadCount ?? 0;
                @endphp
                
                <span id="sidebar-message-badge" 
                      @class([
                          'ml-1.5 bg-red-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full min-w-[16px] h-4 inline-flex items-center justify-center shadow-sm animate-pulse',
                          'hidden' => $count <= 0
                      ])>
                    {{ $count }}
                </span>
            </a>

                    <a href="{{ route('verified.sellers') }}" class="block text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1">Verified Pasabuy Shopper</a>
                    
                    @if(auth()->user()->role === 'seller')
                    {{-- Selling Channels --}}
                    <div class="pt-2 mt-2 border-t border-gray-100">
                        <small class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Selling Channels</small>
                        
                        @if(auth()->user()->is_verified)
                            {{-- Open Access --}}
                            <a href="{{ route('seller.dashboard') }}" class="block text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1">See Full Analytics</a>
                            <a href="{{ url('/seller-store/' . auth()->id()) }}" class="block text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1">My Web Store (Personal Site)</a>
                        @elseif(auth()->user()->sellerVerification && auth()->user()->sellerVerification->status === 'pending')
                            {{-- Lock behind Pending Modal --}}
                            <button type="button" data-bs-toggle="modal" data-bs-target="#verificationPendingModal" class="w-full text-left flex items-center justify-between text-gray-400 hover:text-blue-700 font-bold no-underline text-sm py-1 bg-transparent border-0 group cursor-pointer">
                                <span>See Analytics</span>
                                <i class="bi bi-lock-fill text-xs text-gray-400 group-hover:text-blue-700"></i>
                            </button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#verificationPendingModal" class="w-full text-left flex items-center justify-between text-gray-400 hover:text-blue-700 font-bold no-underline text-sm py-1 bg-transparent border-0 group cursor-pointer">
                                <span>My Web Store (Personal Site)</span>
                                <i class="bi bi-lock-fill text-xs text-gray-400 group-hover:text-blue-700"></i>
                            </button>
                        @else
                            {{-- Lock behind Registration Sign up Modal --}}
                            <button type="button" data-bs-toggle="modal" data-bs-target="#vendorRegistrationModal" class="w-full text-left flex items-center justify-between text-gray-400 hover:text-blue-700 font-bold no-underline text-sm py-1 bg-transparent border-0 group cursor-pointer">
                                <span>See Analytics</span>
                                <i class="bi bi-lock-fill text-xs text-gray-400 group-hover:text-blue-700"></i>
                            </button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#vendorRegistrationModal" class="w-full text-left flex items-center justify-between text-gray-400 hover:text-blue-700 font-bold no-underline text-sm py-1 bg-transparent border-0 group cursor-pointer">
                                <span>My Web Store (Personal Site)</span>
                                <i class="bi bi-lock-fill text-xs text-gray-400 group-hover:text-blue-700"></i>
                            </button>
                        @endif
                    </div>

                   {{-- Niche Markets Vendor Dashboards --}}
<div class="pt-2 mt-2 border-t border-gray-100">
    <small class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Niche Markets</small>
    
    @if(auth()->user()->is_verified)
        @php
            // Fetch the vendor record to check types
            $vendor = auth()->user()->vendor; // Adjust this if your relationship is different
            $marketTypes = $vendor ? json_decode($vendor->green_market_type, true) : [];
        @endphp

        @if(!empty($marketTypes))
            {{-- If they have specific market types, show them --}}
            @if(in_array('sari_sari', $marketTypes))
                <a href="{{ route('vendor.sarisari.dashboard') }}" class="block text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1">
                    View Sari-Sari Store
                </a>
            @endif

            @if(in_array('wet_market', $marketTypes))
                <a href="{{ route('vendor.wetmarket.dashboard') }}" class="block text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1">
                    View Wet Market
                </a>
            @endif
        @else
            {{-- Fallback: If no specific market type, just go to general Green Mart --}}
            <a href="{{ route('greenmart.index') }}" class="block text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1">
                Green Mart Vendor
            </a>
        @endif

        <a href="#" class="block text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1">Personal Care Services</a>

    @elseif(auth()->user()->sellerVerification && auth()->user()->sellerVerification->status === 'pending')
        {{-- Locked / Pending Logic --}}
        <button type="button" data-bs-toggle="modal" data-bs-target="#verificationPendingModal" class="w-full text-left flex items-center justify-between text-gray-400 hover:text-blue-700 font-bold no-underline text-sm py-1 bg-transparent border-0 group cursor-pointer">
            <span>Green Mart Vendor</span>
            <i class="bi bi-lock-fill text-xs text-gray-400 group-hover:text-blue-700"></i>
        </button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#verificationPendingModal" class="w-full text-left flex items-center justify-between text-gray-400 hover:text-blue-700 font-bold no-underline text-sm py-1 bg-transparent border-0 group cursor-pointer">
            <span>Personal Care Services</span>
            <i class="bi bi-lock-fill text-xs text-gray-400 group-hover:text-blue-700"></i>
        </button>
    @else
        {{-- Registration Required --}}
        <button type="button" data-bs-toggle="modal" data-bs-target="#vendorRegistrationModal" class="w-full text-left flex items-center justify-between text-gray-400 hover:text-blue-700 font-bold no-underline text-sm py-1 bg-transparent border-0 group cursor-pointer">
            <span>Green Mart Vendor</span>
            <i class="bi bi-lock-fill text-xs text-gray-400 group-hover:text-blue-700"></i>
        </button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#vendorRegistrationModal" class="w-full text-left flex items-center justify-between text-gray-400 hover:text-blue-700 font-bold no-underline text-sm py-1 bg-transparent border-0 group cursor-pointer">
            <span>Personal Care Services</span>
            <i class="bi bi-lock-fill text-xs text-gray-400 group-hover:text-blue-700"></i>
        </button>
    @endif
</div>

                    @endif

                    <div class="pt-2 mt-2 border-t border-gray-100">
                        <a href="{{ route('feed.index') }}" class="block text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1">Community Feed</a>
                        <a href="{{ route('buyer.dashboard') }}" class="block text-gray-600 hover:text-blue-700 font-bold no-underline text-sm py-1 border-l-4 border-blue-700 pl-2">Personal Dashboard</a>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-6">Account Settings</h3>
                <div class="space-y-4">
                    <a href="#" class="block p-5 bg-white rounded-3xl border border-gray-100 hover:border-blue-700 transition group no-underline shadow-sm hover:shadow-md">
                        <h4 class="font-bold text-gray-900 group-hover:text-blue-700 m-0">Payment Methods</h4>
                        <p class="text-xs text-gray-500 mt-1 mb-0">Manage your GCash and bank details.</p>
                    </a>
                    
                    <button type="button" data-bs-toggle="modal" data-bs-target="#shippingModal" class="w-full text-left block p-5 bg-white rounded-3xl border border-gray-100 hover:border-blue-700 transition group no-underline shadow-sm hover:shadow-md">
                        <h4 class="font-bold text-gray-900 group-hover:text-blue-700 m-0">Shipping Address</h4>
                        <p class="text-xs text-gray-500 mt-1 mb-0">
                            {{ auth()->user()->address ? 'Currently: ' . Str::limit(auth()->user()->address, 30) : 'Update your delivery address.' }}
                        </p>
                    </button>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-9">
                
                {{-- Profile Section --}}
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 mb-8">
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div class="flex-1 w-full">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="relative">
                                    @if(auth()->user()->profile_picture)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="w-16 h-16 rounded-full object-cover border-2 border-blue-100 shadow-sm">
                                    @else
                                        <div class="bg-gray-100 p-4 rounded-full">
                                            <i class="bi bi-person-fill text-3xl text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <h2 class="text-2xl font-black text-gray-800 m-0">Personal Dashboard</h2>
                            </div>
                            
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Account ID</label>
                                    <input type="text" class="w-full mt-1 px-4 py-2 bg-gray-50 border border-gray-100 rounded-lg font-bold" value="{{ auth()->user()->id }}" readonly>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Full Name</label>
                                    <input type="text" name="name" class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg font-bold" value="{{ auth()->user()->name }}">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</label>
                                    <input type="email" name="email" class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg font-bold" value="{{ auth()->user()->email }}">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mobile</label>
                                    <input type="text" name="phone" class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg font-bold" placeholder="+639..." value="{{ auth()->user()->phone ?? '' }}">
                                </div>
                                <div class="md:col-span-2 mt-4">
                                    <button type="submit" class="bg-blue-800 text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="w-full md:w-64 bg-blue-50 rounded-2xl p-6 border border-blue-100">
                            <h4 class="text-sm font-black text-blue-900 uppercase mb-3">Verification Info</h4>
                            <p class="text-[11px] text-blue-700 leading-relaxed mb-4">
                                Ensure your details match your Valid ID for faster verification on Oppasabuy.
                            </p>
                            <div class="flex items-center gap-2 text-blue-800">
                                <i class="bi bi-shield-lock-fill"></i>
                                <span class="text-[10px] font-bold">Protected by Oppasabuy</span>
                            </div>
                        </div>
                    </div>
                </div>

       {{-- Wishlist Section --}}
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800">My Wishlist</h3>
                        <a href="{{ route('cart.index') }}"><i class="bi bi-chevron-right text-gray-400"></i></a>
                    </div>
                    <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
                        @php
                            $itemsToDisplay = (isset($wishlistItems) && count($wishlistItems) > 0) ? $wishlistItems : (session('cart') ?? []);
                        @endphp

                        @if(count($itemsToDisplay) > 0)
                            @foreach($itemsToDisplay as $item)
                                @php
                                    $product = is_object($item) ? ($item->product ?? $item) : (object)$item;
                                    $name = $product->name ?? 'Product';
                                    $image = $product->image_path ?? $product->image ?? '';
                                    
                                    if ($image && !str_starts_with($image, 'http')) {
                                        $cleanPath = ltrim($image, '/');
                                        $finalImagePath = str_starts_with($cleanPath, 'storage/') 
                                            ? asset($cleanPath) 
                                            : asset('storage/' . $cleanPath);
                                    } else {
                                        $finalImagePath = $image;
                                    }
                                @endphp
                                <div class="min-w-[120px] group">
                                    <div class="h-32 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                                        <img src="{{ $finalImagePath }}" 
                                             class="w-full h-full object-cover" 
                                             alt="{{ $name }}"
                                             onerror="this.onerror=null; this.src='https://placehold.co/120x128?text=No+Image'">
                                    </div>
                                    <p class="text-[10px] font-bold mt-2 truncate text-gray-700">{{ $name }}</p>
                                </div>
                            @endforeach 
                        @else
                            @for($i=0; $i<6; $i++)
                                <div class="min-w-[120px] h-32 bg-gray-100 rounded-2xl border border-gray-100 animate-pulse"></div>
                            @endfor
                        @endif
                    </div>
                </div>

                {{-- Favorite Web Store --}}
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Favorite Web Store</h3>
                    </div>
                    <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide">
                        @forelse($favoriteSellers ?? [] as $seller)
                            @php
                                $verify = $seller->sellerVerification; 
                                $storeName = $verify->store_name ?? $seller->name;
                                $logoPath = $verify->logo_path ?? null;
                            @endphp
                            <div class="flex flex-col items-center min-w-[90px] text-center">
                                <div class="relative mb-2">
                                    <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-white shadow-sm bg-white">
                                        @if($logoPath)
                                            <img src="{{ asset('storage/' . $logoPath) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                                <i class="bi bi-shop text-gray-400 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-[10px] font-black text-gray-900 uppercase m-0 leading-tight truncate w-full">
                                    {{ $seller->name }}
                                </p>
                                <p class="text-[9px] font-bold text-blue-600 m-0 truncate w-full">
                                    {{ $storeName }}
                                </p>
                            </div>
                        @empty
                            <div class="flex flex-col items-center min-w-[90px] opacity-40">
                                <div class="w-16 h-16 rounded-full bg-gray-200 border-2 border-white shadow-sm flex items-center justify-center">
                                    <i class="bi bi-plus text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-[9px] font-black text-gray-400 mt-2 uppercase">No Store</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                {{-- Stats Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#ordersListModal" class="w-full bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center justify-between hover:border-blue-300 transition text-left group">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase m-0">Pasabuy Requests</p>
                            <h2 class="text-3xl font-black text-gray-900 mt-1">{{ count($orders) }}</h2>
                            <p class="text-[10px] text-blue-600 font-bold mt-1">Click to view all orders</p>
                        </div>
                        <div class="bg-blue-800 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold">
                            {{ count($orders) }}
                        </div>
                    </button>
                    
                    <button type="button" data-bs-toggle="modal" data-bs-target="#pasabuyModal" class="w-full bg-white p-6 rounded-3xl border border-gray-100 shadow-sm text-left hover:border-blue-300 transition">
                        <p class="text-[10px] font-black text-gray-400 uppercase m-0">Request a Shopper</p>
                        <div class="mt-3 flex items-center gap-2">
                             <div class="w-full bg-gray-100 border-0 rounded-full px-4 py-2 text-xs text-gray-400 flex justify-between items-center">
                                Search Available Shopper Near By...
                                <i class="bi bi-search text-blue-800"></i>
                             </div>
                        </div>
                    </button>
                </div>

                {{-- The Tracking Table (Alpine Controlled) --}}
                <div class="mb-4">
                    <button @click="openTracking = !openTracking" class="text-[10px] font-black text-blue-800 uppercase tracking-widest bg-blue-50 px-4 py-2 rounded-lg hover:bg-blue-100 transition">
                        <span x-text="openTracking ? 'Hide Tracking Table' : 'Show Tracking Table'"></span>
                    </button>
                </div>

                <div x-show="openTracking" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden mb-10">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <h3 class="text-xl font-bold text-gray-800 m-0">Quick Tracking</h3>
                        <a href="{{ route('store') }}" class="text-blue-800 text-sm font-bold no-underline">Find more items →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order Info</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Items</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Total</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50/30 transition">
                                        <td class="px-8 py-6">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-black text-gray-900">{{ $order->seller->name ?? 'Korean Seller' }}</span>
                                                <span class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">#{{ $order->id }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex -space-x-3">
                                                @foreach($order->items->take(3) as $oItem)
                                                    @php
                                                        $orderImg = $oItem->product->image_path ?? '';
                                                        $orderImgPath = $orderImg ? (str_starts_with($orderImg, 'http') ? $orderImg : asset('storage/' . $orderImg)) : '';
                                                    @endphp
                                                    <img src="{{ $orderImgPath }}" 
                                                         class="w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm bg-white"
                                                         onerror="this.src='https://placehold.co/100x100'">
                                                @endforeach
                                                @if($order->items->count() > 3)
                                                    <div class="w-10 h-10 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-[10px] font-black text-gray-400">
                                                        +{{ $order->items->count() - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="text-sm font-black text-[#0d47a1]">₱{{ number_format($order->total_price, 2) }}</span>
                                        </td>
                                        <td class="px-8 py-6">
                                            @if($order->status == 'paid' || $order->status == 'completed')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase">
                                                    <i class="bi bi-check-circle-fill"></i> Completed
                                                </span>
                                            @elseif($order->status == 'awaiting_video')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[9px] font-black uppercase">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-ping"></span> Pending Video
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 rounded-full text-[9px] font-black uppercase">
                                                    Video Ready
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <a href="{{ route('chat.order', $order->id) }}" class="inline-block bg-blue-800 text-white px-4 py-2 rounded-xl font-black text-[10px] uppercase no-underline hover:bg-black transition">View Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-20 text-center">
                                            <p class="text-gray-400 italic font-medium">No inquiries found.</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

{{-- Case 1: Account Not Yet Registered Modal --}}
<div class="modal fade" id="vendorRegistrationModal" tabindex="-1" aria-labelledby="vendorRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-[2.5rem] border-0 shadow-2xl overflow-hidden">
            <div class="p-8 text-center bg-gradient-to-b from-indigo-50 to-white relative">
                <div class="absolute top-4 right-4">
                    <button type="button" class="btn-close bg-white/80 p-2 rounded-full border shadow-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="w-16 h-16 bg-indigo-100 text-indigo-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-indigo-200 shadow-sm">
                    <i class="bi bi-shop text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2" id="vendorRegistrationModalLabel">Become a Vendor</h3>
                <p class="text-sm text-gray-500 max-w-sm mx-auto mb-6">
                    Unlock your multi-channel storefront modules and start selling. Register your vendor profile to gain full access to specialized seller features.
                </p>
                <div class="space-y-3">
                    <a href="{{ route('vendor.register') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3.5 rounded-2xl font-black text-xs uppercase tracking-wider border-0 transition text-center no-underline">
                        Register As A Vendor Now
                    </a>
                    <button type="button" data-bs-dismiss="modal" class="block w-full bg-gray-100 text-gray-600 py-3.5 rounded-2xl font-black text-xs uppercase tracking-wider border-0 hover:bg-gray-200 transition">
                        Explore Dashboard First
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Case 2: Registered but Verification Pending Modal --}}
<div class="modal fade" id="verificationPendingModal" tabindex="-1" aria-labelledby="verificationPendingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-[2.5rem] border-0 shadow-2xl overflow-hidden">
            <div class="p-8 text-center bg-gradient-to-b from-amber-50 to-white relative">
                <div class="absolute top-4 right-4">
                    <button type="button" class="btn-close bg-white/80 p-2 rounded-full border shadow-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="w-16 h-16 bg-amber-100 text-amber-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-200 shadow-sm animate-pulse">
                    <i class="bi bi-hourglass-split text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2" id="verificationPendingModalLabel">Application Pending</h3>
                <p class="text-sm text-gray-500 max-w-sm mx-auto mb-6">
                    Your vendor identity documentation is currently undergoing security validation. Once finalized, your multi-channel storefront modules will unlock automatically.
                </p>
                <div class="space-y-2">
                    <button type="button" data-bs-dismiss="modal" class="block w-full bg-amber-600 hover:bg-amber-700 text-white py-3.5 rounded-2xl font-black text-xs uppercase tracking-wider border-0 transition">
                        Understood, Return to Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log("Dashboard DOM fully loaded!");

        // --- 1. TARGET THE INDICATOR BADGE ELEMENT ---
        var badge = document.getElementById('sidebar-message-badge');
        if (!badge) {
            console.log("No notification badge found on this page layout.");
            return;
        }

        // --- 2. INITIALIZE COUNTER FROM BLADE DATA ---
        // We clean the text: if it's empty or hidden, we start at 0.
        var badgeText = badge.innerText.trim();
        var currentCount = (badgeText === "" || isNaN(parseInt(badgeText))) ? 0 : parseInt(badgeText);
        var currentUserId = parseInt("{{ auth()->id() }}");

        console.log("Starting badge count initialized to: " + currentCount);

        // --- 3. CONNECT DIRECTLY TO YOUR LOCAL REVERB WEB-SOCKET ---
        var socketUrl = "ws://127.0.0.1:8080/app/oppasabay-local-key?protocol=7&client=js&version=8.3.0&flash=false";
        var ws = new WebSocket(socketUrl);

        ws.onopen = function() {
            console.log("🚀 Connected directly to local Reverb WebSocket server!");
            
            // Reverb requires us to send a subscribe handshake
            var subscribeMessage = {
                event: "pusher:subscribe",
                data: { channel: "global-notifications" }
            };
            ws.send(JSON.stringify(subscribeMessage));
            console.log("📡 Listening on: global-notifications");
        };

        // --- 4. HANDLE INCOMING LIVE DATA PAYLOADS ---
        ws.onmessage = function(event) {
            var rawData = JSON.parse(event.data);
            
            if (rawData.event === "RoomChatUpdated") {
                var payload = JSON.parse(rawData.data);
                console.log("✉️ Live event received!", payload);

                // Rule 1: Skip if you were the sender
                if (parseInt(payload.sender_id) === currentUserId) return;

                // Rule 2: Skip if you are actively viewing this chat room
                if (typeof window.currentRoomId !== 'undefined' && window.currentRoomId === parseInt(payload.room_id)) {
                    console.log("Skipped: You are inside this room.");
                    return;
                }

                // Rule 3: Re-fetch current count from the DOM to ensure we don't have stale JS memory
                var latestBadgeText = badge.innerText.trim();
                currentCount = (latestBadgeText === "" || isNaN(parseInt(latestBadgeText))) ? 0 : parseInt(latestBadgeText);

                // Everything passes! Increment and show
                currentCount++;
                badge.innerText = currentCount;
                badge.classList.remove('hidden');
                console.log("Success: Badge incremented to " + currentCount);
            }
        };

        ws.onerror = function(error) { console.error("WebSocket error:", error); };
        ws.onclose = function() { console.log("WebSocket connection closed."); };
    });
</script>
@endsection