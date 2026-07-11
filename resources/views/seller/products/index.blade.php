@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 flex">
    {{-- Sidebar --}}
    <aside class="hidden lg:flex w-72 flex-shrink-0 bg-white border-r border-gray-100 p-8 flex-col sticky top-0 h-screen overflow-y-auto">
        {{-- Brand Section --}}
        <div class="mb-10">
            <h2 class="text-2xl font-black text-[#0d47a1] mb-0 tracking-tighter italic">Oppasabuy</h2>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Seller Central</p>
        </div>

        <nav class="space-y-2 flex-1">
            {{-- Dashboard: Always accessible --}}
            <a href="{{ route('seller.dashboard') }}" 
               class="flex items-center space-x-3 p-4 {{ request()->is('seller/dashboard') ? 'bg-blue-50 text-[#0d47a1]' : 'text-gray-500 hover:bg-gray-50' }} rounded-2xl font-bold no-underline transition">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>

            {{-- Verification Logic --}}
            @php
                $isAdmin = auth()->user()->role === 'admin';
                $isVerifiedSeller = isset($store) && $store->status === 'approved' && !empty($store->video_intro_path);
            @endphp

            @if($isAdmin || $isVerifiedSeller)
                {{-- UNLOCKED STATE --}}
                <a href="{{ route('seller.products.index') }}" 
                   class="flex items-center space-x-3 p-4 {{ request()->is('seller/products*') ? 'bg-blue-50 text-[#0d47a1]' : 'text-gray-500 hover:bg-gray-50' }} rounded-2xl font-bold no-underline transition">
                    <i class="bi bi-bag-check"></i>
                    <span>My Products</span>
                </a>

                <a href="{{ route('seller.orders.index') }}" 
                   class="flex items-center space-x-3 p-4 {{ request()->is('seller/orders*') ? 'bg-blue-50 text-[#0d47a1]' : 'text-gray-500 hover:bg-gray-50' }} rounded-2xl font-bold no-underline transition">
                    <i class="bi bi-cart"></i>
                    <span>Orders</span>
                </a>

                {{-- Invoices & Billing --}}
                <a href="{{ route('seller.invoices.index') }}" 
                   class="flex items-center space-x-3 p-4 {{ request()->is('seller/invoices*') ? 'bg-blue-50 text-[#0d47a1]' : 'text-gray-500 hover:bg-gray-50' }} rounded-2xl font-bold no-underline transition">
                    <i class="bi bi-receipt"></i>
                    <span>Invoices & Billing</span>
                </a>

                {{-- Financial Statement --}}
                <a href="{{ route('seller.reports.financial') }}" 
                   class="flex items-center space-x-3 p-4 {{ request()->is('seller/reports/financial*') ? 'bg-blue-50 text-[#0d47a1]' : 'text-gray-500 hover:bg-gray-50' }} rounded-2xl font-bold no-underline transition">
                    <i class="bi bi-wallet2"></i>
                    <span>Financial Statement</span>
                </a>

                <a href="{{ route('seller.reports.daily') }}" 
                   class="flex items-center space-x-3 p-4 {{ request()->is('seller/reports/daily*') ? 'bg-blue-50 text-[#0d47a1]' : 'text-gray-500 hover:bg-gray-50' }} rounded-2xl font-bold no-underline transition">
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Daily Report</span>
                </a>

                {{-- Target Goals --}}
                <a href="{{ route('seller.reports.goals') }}" 
                   class="flex items-center space-x-3 p-4 {{ request()->is('seller/goals*') || request()->is('seller/reports/goals') ? 'bg-blue-50 text-[#0d47a1]' : 'text-gray-500 hover:bg-gray-50' }} rounded-2xl font-bold no-underline transition">
                    <i class="bi bi-bullseye"></i>
                    <span>Target Goals</span>
                </a>

                <a href="{{ route('seller.slots.index') }}" 
                   class="flex items-center space-x-3 p-4 {{ request()->is('seller/slots*') ? 'bg-blue-50 text-[#0d47a1]' : 'text-gray-500 hover:bg-gray-50' }} rounded-2xl font-bold no-underline transition">
                    <i class="bi bi-calendar-event"></i>
                    <span>Slot Management</span>
                </a>
            @else
                {{-- LOCKED STATE --}}
                <div class="flex items-center space-x-3 p-4 text-gray-300 cursor-not-allowed italic">
                    <i class="bi bi-lock-fill"></i>
                    <span>Products (Video Req.)</span>
                </div>
                
                <div class="flex items-center space-x-3 p-4 text-gray-300 cursor-not-allowed italic">
                    <i class="bi bi-lock-fill"></i>
                    <span>Orders (Video Req.)</span>
                </div>

                <div class="flex items-center space-x-3 p-4 text-gray-300 cursor-not-allowed italic">
                    <i class="bi bi-lock-fill"></i>
                    <span>Invoices (Video Req.)</span>
                </div>

                <div class="flex items-center space-x-3 p-4 text-gray-300 cursor-not-allowed italic">
                    <i class="bi bi-lock-fill"></i>
                    <span>Financials (Video Req.)</span>
                </div>

                <div class="flex items-center space-x-3 p-4 text-gray-300 cursor-not-allowed italic">
                    <i class="bi bi-lock-fill"></i>
                    <span>Daily Report (Video Req.)</span>
                </div>

                <a href="{{ route('seller.reports.goals') }}" 
                   class="flex items-center space-x-3 p-4 {{ request()->is('seller/goals*') || request()->is('seller/reports/goals') ? 'bg-blue-50 text-[#0d47a1]' : 'text-gray-500 hover:bg-gray-50' }} rounded-2xl font-bold no-underline transition">
                    <i class="bi bi-bullseye"></i>
                    <span>Target Goals</span>
                </a>

                <div class="flex items-center space-x-3 p-4 text-gray-300 cursor-not-allowed italic">
                    <i class="bi bi-lock-fill"></i>
                    <span>Slot Mgmt (Video Req.)</span>
                </div>
            @endif
        </nav>

        
    {{-- Upgrade & Logout Section --}}
    <div class="pt-6 border-t border-gray-50 space-y-2">
        {{-- Upgrade Plan Link --}}
        <a href="{{ route('seller.upgrade') }}" 
           class="flex items-center space-x-3 p-4 {{ request()->is('seller/upgrade*') ? 'bg-amber-50 text-amber-600' : 'text-amber-500 hover:bg-amber-50' }} rounded-2xl font-bold no-underline transition">
            <i class="bi bi-star-fill"></i>
            <span>Upgrade Plan</span>
        </a>

        {{-- Logout Section --}}
        <div class="pt-6 border-t border-gray-50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-3 p-4 w-full text-red-500 hover:bg-red-50 rounded-2xl font-bold transition border-0 bg-transparent cursor-pointer">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

<main class="min-w-0 flex-1 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="mb-2 text-[10px] font-black uppercase tracking-[0.25em] text-blue-600">
                    Seller Central
                </p>

                <h1 class="text-3xl font-black tracking-tight text-slate-900">
                    Product Management
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Manage your products and broadcast items to buyers with existing order chats.
                </p>
            </div>

            <div class="relative" id="addProductDropdownWrapper">
                <button
                    type="button"
                    onclick="toggleAddProductDropdown(event)"
                    class="inline-flex items-center gap-3 rounded-xl bg-blue-600 px-6 py-3 text-xs font-black uppercase tracking-wider text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700"
                >
                    <i class="bi bi-plus-circle-fill"></i>
                    Add Product
                    <i class="bi bi-chevron-down text-[10px]"></i>
                </button>

                <div
                    id="addProductDropdown"
                    class="absolute right-0 z-50 mt-3 hidden w-72 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-2xl"
                >
                    <a
                        href="{{ route('seller.products.create', 'oppa_mall') }}"
                        class="flex items-center gap-4 border-b border-slate-100 px-5 py-4 text-slate-700 no-underline transition hover:bg-blue-50"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <i class="bi bi-shop"></i>
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-900">
                                OppaMall Product
                            </p>
                            <p class="mt-1 text-[10px] font-bold text-slate-400">
                                General marketplace listing
                            </p>
                        </div>
                    </a>

                    <a
                        href="{{ route('seller.products.create', 'green_market') }}"
                        class="flex items-center gap-4 border-b border-slate-100 px-5 py-4 text-slate-700 no-underline transition hover:bg-green-50"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-600">
                            <i class="bi bi-basket2-fill"></i>
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-900">
                                Green Mart Product
                            </p>
                            <p class="mt-1 text-[10px] font-bold text-slate-400">
                                Wet market or sari-sari item
                            </p>
                        </div>
                    </a>

                    <a
                        href="{{ route('seller.products.create', 'own_webstore') }}"
                        class="flex items-center gap-4 border-b border-slate-100 px-5 py-4 text-slate-700 no-underline transition hover:bg-slate-50"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                            <i class="bi bi-globe2"></i>
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-900">
                                Webstore Product
                            </p>
                            <p class="mt-1 text-[10px] font-bold text-slate-400">
                                Product for your own store
                            </p>
                        </div>
                    </a>

                    <a
                        href="{{ route('seller.products.create', 'personal_care') }}"
                        class="flex items-center gap-4 px-5 py-4 text-slate-700 no-underline transition hover:bg-pink-50"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-50 text-pink-600">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-900">
                                Personal Care
                            </p>
                            <p class="mt-1 text-[10px] font-bold text-slate-400">
                                Service or care listing
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-bold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-bold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                <p class="mb-2 font-black">Please correct the following:</p>
                <ul class="mb-0 list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-7 grid grid-cols-2 gap-4 md:grid-cols-4">
            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Products</p>
                <p class="mt-2 text-2xl font-black text-slate-900">{{ $products->count() }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">OppaMall</p>
                <p class="mt-2 text-2xl font-black text-blue-600">{{ $products->where('channel', 'oppa_mall')->count() }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Green Mart</p>
                <p class="mt-2 text-2xl font-black text-green-600">{{ $products->where('channel', 'green_market')->count() }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Lifestyle and Personal Care Services</p>
                <p class="mt-2 text-2xl font-black text-purple-600">{{ $broadcastBuyers->count() }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-slate-100 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-black text-slate-900">Your Listings</h2>
                    <p class="mt-1 text-xs font-medium text-slate-400">
                        Use Broadcast to send a product card to selected buyer order chats.
                    </p>
                </div>

                <div class="relative w-full md:w-72">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="productSearch" placeholder="Search products..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-semibold text-slate-700 outline-none focus:border-blue-500 focus:bg-white">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1050px] border-collapse text-left">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">
                            <th class="px-6 py-4">Product</th>
                            <th class="px-6 py-4">Channel</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Price</th>
                            <th class="px-6 py-4">Stock</th>
                            <th class="px-6 py-4">Sale</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($products as $product)
                            @php
                                $meta = $product->meta_data;
                                if (is_string($meta)) {
                                    $meta = json_decode($meta, true) ?? [];
                                }
                                if (!is_array($meta)) {
                                    $meta = [];
                                }

                                $subcategory = $meta['subcategory'] ?? 'N/A';

                                $channelLabel = match($product->channel) {
                                    'oppa_mall' => 'OppaMall',
                                    'own_webstore' => 'Own Webstore',
                                    'green_market' => 'Green Mart',
                                    'personal_care' => 'Personal Care',
                                    default => ucwords(str_replace('_', ' ', $product->channel ?? 'Unknown')),
                                };

                                $channelClass = match($product->channel) {
                                    'oppa_mall' => 'bg-blue-50 text-blue-700',
                                    'own_webstore' => 'bg-slate-100 text-slate-700',
                                    'green_market' => 'bg-green-50 text-green-700',
                                    'personal_care' => 'bg-pink-50 text-pink-700',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp

                            <tr class="product-row hover:bg-slate-50/60" data-search="{{ strtolower(($product->name ?? '') . ' ' . ($product->category ?? '') . ' ' . $subcategory . ' ' . $channelLabel) }}">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ asset('storage/' . str_replace('public/', '', $product->image_path)) }}" alt="{{ $product->name }}" class="h-14 w-14 rounded-xl border border-slate-100 object-cover shadow-sm" onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=Product';">
                                        <div class="min-w-0">
                                            <p class="max-w-[230px] truncate text-sm font-black text-slate-900">{{ $product->name }}</p>
                                            <p class="mt-1 max-w-[230px] truncate text-[11px] font-medium text-slate-400">{{ $product->description ?: 'No description' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="rounded-lg px-3 py-1 text-[10px] font-black uppercase {{ $channelClass }}">{{ $channelLabel }}</span>
                                </td>

                                <td class="px-6 py-5">
                                    <p class="text-xs font-bold text-slate-700">{{ $product->category ?? 'N/A' }}</p>
                                    <p class="mt-1 text-[10px] font-semibold text-slate-400">{{ $subcategory }}</p>
                                </td>

                                <td class="px-6 py-5">
                                    @if($product->channel === 'personal_care' && is_null($product->price))
                                        <span class="text-xs font-black text-purple-600">Quote-Based</span>
                                    @else
                                        <span class="text-sm font-black text-slate-900">₱{{ number_format((float) $product->price, 2) }}</span>
                                    @endif
                                </td>

                                <td class="px-6 py-5">
                                    @if($product->channel === 'personal_care')
                                        <span class="text-xs font-bold text-pink-600">Service</span>
                                    @else
                                        <span class="text-sm font-black {{ ($product->stock ?? 0) > 0 ? 'text-slate-800' : 'text-red-600' }}">{{ $product->stock ?? 0 }}</span>
                                    @endif
                                </td>

                                <td class="px-6 py-5">
                                    @if($product->on_sale)
                                        <span class="rounded-lg bg-orange-50 px-3 py-1 text-[10px] font-black uppercase text-orange-600">On Sale</span>
                                    @else
                                        <span class="text-[10px] font-black uppercase text-slate-300">Regular</span>
                                    @endif
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" onclick='openBroadcastModal(@json($product->id), @json($product->name))' class="rounded-xl bg-purple-600 px-4 py-2.5 text-[10px] font-black uppercase tracking-wider text-white shadow-md shadow-purple-100 hover:bg-purple-700">
                                            Broadcast
                                        </button>

                                        <button type="button" onclick='openEditModal(@json($product))' class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-2.5 text-[10px] font-black uppercase tracking-wider text-blue-700 hover:bg-blue-100">
                                            Edit
                                        </button>

                                        <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-xl border border-red-100 bg-red-50 px-4 py-2.5 text-[10px] font-black uppercase tracking-wider text-red-600 hover:bg-red-100">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center">
                                    <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">
                                        <i class="bi bi-box-seam text-3xl text-slate-400"></i>
                                    </div>
                                    <h3 class="text-lg font-black text-slate-800">No products yet</h3>
                                    <p class="mt-2 text-sm font-medium text-slate-400">Create your first product using one of the buttons above.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="broadcastModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="relative flex min-h-screen items-center justify-center px-4 py-10">
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm" onclick="closeBroadcastModal()"></div>

        <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-[2rem] bg-white shadow-2xl">
            <form id="broadcastForm" method="POST">
                @csrf

                <div class="border-b border-slate-100 px-8 py-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-purple-600">Product Broadcast</p>
                            <h3 class="mt-2 text-2xl font-black text-slate-900" id="broadcastProductName">Broadcast Product</h3>
                            <p class="mt-2 text-sm font-medium text-slate-500">Select buyers who already have an order chat with your store.</p>
                        </div>
                        <button type="button" onclick="closeBroadcastModal()" class="text-slate-400 hover:text-red-500">
                            <i class="bi bi-x-lg text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="max-h-[60vh] overflow-y-auto px-8 py-6">
                    @if($broadcastBuyers->isNotEmpty())
                        <div class="mb-5 flex items-center justify-between rounded-xl bg-purple-50 px-4 py-3">
                            <span class="text-xs font-black uppercase tracking-wider text-purple-700">Available Buyer Chats: {{ $broadcastBuyers->count() }}</span>
                            <label class="flex cursor-pointer items-center gap-2 text-xs font-black text-purple-700">
                                <input type="checkbox" id="selectAllBuyers" onchange="toggleAllBuyers(this)" class="rounded border-purple-300 text-purple-600 focus:ring-purple-500">
                                Select All
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach($broadcastBuyers as $buyer)
                                <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 hover:bg-white hover:shadow-sm">
                                    <input type="checkbox" name="buyer_ids[]" value="{{ $buyer->id }}" class="buyer-checkbox rounded border-slate-300 text-purple-600 focus:ring-purple-500">

                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-slate-700 text-xs font-black text-white">
                                        {{ strtoupper(substr($buyer->name ?? $buyer->full_name ?? 'B', 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-black text-slate-900">{{ $buyer->name ?? $buyer->full_name ?? 'Buyer' }}</p>
                                        <p class="truncate text-[10px] font-bold text-slate-400">{{ $buyer->email }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="py-14 text-center">
                            <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-purple-50">
                                <i class="bi bi-chat-square-text text-3xl text-purple-400"></i>
                            </div>
                            <h4 class="text-lg font-black text-slate-800">No buyer chats yet</h4>
                            <p class="mx-auto mt-2 max-w-sm text-sm font-medium text-slate-400">
                                A buyer must have an existing order chat before a product can be broadcast.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 bg-slate-50 px-8 py-6">
                    <button type="button" onclick="closeBroadcastModal()" class="rounded-xl px-6 py-3 text-xs font-black uppercase tracking-wider text-slate-400 hover:text-slate-700">Cancel</button>
                    <button type="submit" {{ $broadcastBuyers->isEmpty() ? 'disabled' : '' }} class="rounded-xl bg-purple-600 px-8 py-3 text-xs font-black uppercase tracking-wider text-white shadow-lg shadow-purple-100 hover:bg-purple-700 disabled:cursor-not-allowed disabled:opacity-40">
                        Send Broadcast
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="relative flex min-h-screen items-center justify-center px-4 py-10">
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm" onclick="closeEditModal()"></div>

        <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-[2rem] bg-white shadow-2xl">
            <form id="editProductForm" method="POST">
                @csrf
                @method('PUT')

                <div class="border-b border-slate-100 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600">Edit Listing</p>
                            <h3 class="mt-2 text-2xl font-black text-slate-900">Update Product</h3>
                        </div>
                        <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-red-500">
                            <i class="bi bi-x-lg text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 px-8 py-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-[10px] font-black uppercase tracking-wider text-slate-400">Product Name</label>
                        <input type="text" id="editName" name="name" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-blue-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-[10px] font-black uppercase tracking-wider text-slate-400">Category</label>
                        <input type="text" id="editCategory" name="category" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-blue-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-[10px] font-black uppercase tracking-wider text-slate-400">Subcategory</label>
                        <input type="text" id="editSubcategory" name="subcategory" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-blue-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-[10px] font-black uppercase tracking-wider text-slate-400">Price</label>
                        <input type="number" id="editPrice" name="price" step="0.01" min="0" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-blue-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-[10px] font-black uppercase tracking-wider text-slate-400">Stock</label>
                        <input type="number" id="editStock" name="stock" min="0" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-blue-500 focus:bg-white">
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-[10px] font-black uppercase tracking-wider text-slate-400">Description</label>
                        <textarea id="editDescription" name="description" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-blue-500 focus:bg-white"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 bg-slate-50 px-8 py-6">
                    <button type="button" onclick="closeEditModal()" class="rounded-xl px-6 py-3 text-xs font-black uppercase tracking-wider text-slate-400 hover:text-slate-700">Cancel</button>
                    <button type="submit" class="rounded-xl bg-blue-600 px-8 py-3 text-xs font-black uppercase tracking-wider text-white shadow-lg shadow-blue-100 hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const productBaseUrl = @json(url('/seller/products'));

    function openBroadcastModal(productId, productName) {
        const modal = document.getElementById('broadcastModal');
        const form = document.getElementById('broadcastForm');

        form.action = productBaseUrl + '/' + productId + '/broadcast';
        document.getElementById('broadcastProductName').textContent = 'Broadcast: ' + productName;

        document.querySelectorAll('.buyer-checkbox').forEach(function (checkbox) {
            checkbox.checked = false;
        });

        const selectAll = document.getElementById('selectAllBuyers');
        if (selectAll) {
            selectAll.checked = false;
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeBroadcastModal() {
        document.getElementById('broadcastModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function toggleAllBuyers(source) {
        document.querySelectorAll('.buyer-checkbox').forEach(function (checkbox) {
            checkbox.checked = source.checked;
        });
    }

    function openEditModal(product) {
        let meta = product.meta_data || {};

        if (typeof meta === 'string') {
            try {
                meta = JSON.parse(meta);
            } catch (error) {
                meta = {};
            }
        }

        document.getElementById('editProductForm').action = productBaseUrl + '/' + product.id;
        document.getElementById('editName').value = product.name || '';
        document.getElementById('editCategory').value = product.category || '';
        document.getElementById('editSubcategory').value = meta.subcategory || '';
        document.getElementById('editPrice').value = product.price ?? '';
        document.getElementById('editStock').value = product.stock ?? '';
        document.getElementById('editDescription').value = product.description || '';

        document.getElementById('editModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    document.getElementById('productSearch').addEventListener('input', function () {
        const search = this.value.toLowerCase().trim();

        document.querySelectorAll('.product-row').forEach(function (row) {
            const searchable = row.dataset.search || '';
            row.classList.toggle('hidden', !searchable.includes(search));
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeBroadcastModal();
            closeEditModal();
        }
    });
</script>
<script>
    function toggleAddProductDropdown(event) {
        event.stopPropagation();

        const dropdown = document.getElementById('addProductDropdown');

        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    document.addEventListener('click', function (event) {
        const wrapper = document.getElementById('addProductDropdownWrapper');
        const dropdown = document.getElementById('addProductDropdown');

        if (
            wrapper &&
            dropdown &&
            !wrapper.contains(event.target)
        ) {
            dropdown.classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            const dropdown = document.getElementById('addProductDropdown');

            if (dropdown) {
                dropdown.classList.add('hidden');
            }
        }
    });
</script>
    </main>
</div>
@endsection
