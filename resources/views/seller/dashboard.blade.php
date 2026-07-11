@extends('layouts.app')

@section('content')
<div class="bg-[#f6f8fb] min-h-screen flex">
    
    {{-- Sidebar --}}
    <aside class="w-72 bg-white border-r border-gray-100 hidden lg:block p-8 flex flex-col">
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

    <main class="flex-1 p-6 lg:p-12">
        
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-bold flex items-center">
                <i class="bi bi-check-circle-fill mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(auth()->user()->role !== 'admin')
            @if(isset($store) && $store->status === 'approved' && !$store->video_intro_path)
                <div class="mb-8 bg-amber-50 border-l-4 border-amber-500 p-6 rounded-2xl shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-amber-100 p-3 rounded-xl mr-4 text-amber-600">
                            <i class="bi bi-camera-reels-fill text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-amber-800 font-black">Final Step: Upload Introduction Video</h3>
                            <p class="text-amber-700 text-sm">Your documents are approved! To build trust with buyers, please upload a short video introduction to unlock your store features.</p>
                        </div>
                    </div>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#uploadIntroVideoModal" class="bg-amber-600 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-amber-700 transition">
                        Upload Now
                    </button>
                </div>
            @endif
        @endif

        <div class="flex justify-between items-center mb-10">
            <div class="flex items-center space-x-5">
                @if(isset($store) && $store->logo_path)
                    <img src="{{ asset('storage/' . $store->logo_path) }}" class="w-16 h-16 rounded-2xl object-cover shadow-sm border border-gray-100">
                @else
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-[#0d47a1]">
                        <i class="bi bi-shop text-2xl"></i>
                    </div>
                @endif
                
                <div>
                    <h1 class="text-3xl font-black text-gray-900">
                        {{ $store->store_name ?? 'Welcome Back!' }}
                    </h1>
                    <p class="text-gray-400 text-sm">Owner: {{ Auth::user()->name }} • <span class="text-[#0d47a1] font-bold">{{ ucfirst($store->plan ?? 'Basic') }} Plan</span></p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                @if(auth()->user()->role === 'admin' || (isset($store) && $store->status === 'approved' && $store->video_intro_path))
                    <div class="hidden md:flex items-center space-x-2 bg-green-50 text-green-700 px-4 py-2 rounded-xl mr-2 border border-green-100">
                        <i class="bi bi-patch-check-fill"></i>
                        <span class="text-[10px] font-black uppercase">Verified Merchant</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">All-time Gross Profit</p>
                <h2 class="text-3xl font-black text-gray-900">₱{{ number_format($totalIncome ?? 0, 2) }}</h2>
                <div class="flex items-center mt-2 text-green-500 text-[10px] font-bold">
                    <i class="bi bi-graph-up-arrow mr-1"></i>
                    <span>+12.5% vs last month</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Sales Today</p>
                <h2 class="text-3xl font-black text-gray-900">₱{{ number_format($salesToday ?? 0, 2) }}</h2>
                <p class="text-blue-500 text-[10px] font-bold mt-2 uppercase tracking-wider">Live Updates</p>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">All-time Expenses</p>
                <h2 class="text-3xl font-black text-red-500">₱{{ number_format($totalExpenses ?? 0, 2) }}</h2>
                <p class="text-gray-400 text-[10px] font-bold mt-2 uppercase">Operational Costs</p>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Inventory Status</p>
                <div class="flex items-end space-x-2 mt-1">
                    <h2 class="text-3xl font-black text-gray-900">{{ $inStockCount ?? 0 }}</h2>
                    <span class="text-gray-400 text-[10px] font-bold mb-2">Items In Stock</span>
                </div>
                <div class="w-full bg-gray-100 h-1.5 rounded-full mt-2">
                    <div class="bg-green-500 h-1.5 rounded-full" style="width: 75%"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-black text-gray-900">Monthly Sales Trend</h3>
                </div>
                <div class="h-64">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>

            {{-- Daily Revenue Column --}}
            <div class="space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0">Daily Revenue Flow</p>
                            <h2 class="text-xl font-black text-gray-900">₱{{ number_format($salesToday ?? 0, 2) }}</h2>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('seller.reports.daily') }}" class="text-xs text-blue-600 font-bold no-underline hover:underline">
                            View Detailed Report →
                        </a>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pending Requests</p>
                    <h2 class="text-2xl font-black text-gray-900">{{ count($pendingOrders ?? []) }}</h2>
                    <p class="text-amber-500 text-[10px] font-bold mt-1">Action Required</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
             <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-black text-gray-900">Active Reservations</h3>
                        <span class="bg-blue-50 text-[#0d47a1] text-[10px] font-black px-3 py-1 rounded-lg uppercase">Live Tracking</span>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($incomingReservations ?? [] as $res)
                        <div class="flex items-center justify-between p-5 rounded-3xl border border-gray-50 bg-gray-50/30 hover:bg-white hover:shadow-md transition group">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center text-[#0d47a1] overflow-hidden">
                                    @if(isset($res->product->image_path))
                                        <img src="{{ asset('storage/' . $res->product->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="bi bi-box-seam"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-gray-900">{{ $res->product->name ?? 'Unknown Product' }}</h4>
                                    <p class="text-[11px] text-gray-400 font-bold">
                                        Reserved by <span class="text-gray-700">{{ $res->buyer->name }}</span> • 
                                        <span class="text-[#0d47a1]">Slot #{{ $res->slot_number }}</span> • 
                                        {{ $res->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                @if($res->payment_proof)
                                <a href="{{ asset('storage/' . $res->payment_proof) }}" target="_blank" class="flex items-center space-x-2 bg-gray-100 text-gray-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase no-underline hover:bg-gray-200 transition">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                    <span>View Receipt</span>
                                </a>
                                @endif
                                
                                <a href="{{ route('chat.order', $res->id) }}" class="w-10 h-10 bg-white border border-gray-100 text-[#0d47a1] rounded-xl flex items-center justify-center shadow-sm hover:bg-[#0d47a1] hover:text-white transition">
                                    <i class="bi bi-chat-dots-fill"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-6 border-2 border-dashed border-gray-50 rounded-3xl">
                            <p class="text-xs text-gray-400 font-bold">No active item reservations at the moment.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
             </div>

             <div class="space-y-6">
                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Slot Occupancy</p>
                    <h2 class="text-2xl font-black text-gray-900">{{ count($incomingReservations ?? []) }}</h2>
                    <p class="text-blue-500 text-[10px] font-bold mt-1">Active Reservations</p>
                </div>
                <div class="bg-[#0d47a1] p-6 rounded-[2rem] shadow-lg shadow-blue-100 text-white">
                    <p class="text-[10px] font-black opacity-60 uppercase tracking-widest mb-1">Annual Target</p>
                    <h2 class="text-2xl font-black">46%</h2>
                    <div class="w-full bg-white/20 h-1.5 rounded-full mt-3">
                        <div class="bg-white h-1.5 rounded-full" style="width: 46%"></div>
                    </div>
                </div>
             </div>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-black text-gray-900">Incoming Buyer Requests</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Protocol: Video Proof Unlocks Payment</p>
                </div>
                <a href="{{ route('seller.orders.index') }}" class="text-[#0d47a1] font-bold text-sm no-underline hover:underline">View History</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50">
                            <th class="px-8 py-4">Order Details</th>
                            <th class="px-8 py-4 text-center">Items</th>
                            <th class="px-8 py-4 text-center">Total Price</th>
                            <th class="px-8 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pendingOrders ?? [] as $order)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-8 py-6">
                                <span class="block text-sm font-black text-gray-900">Order #{{ $order->id }}</span>
                                <span class="block text-[11px] text-gray-400 font-bold">Buyer: {{ $order->buyer->name }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex -space-x-3 justify-center">
                                    @foreach($order->items->take(3) as $item)
                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" 
                                             class="w-10 h-10 rounded-xl border-2 border-white object-cover shadow-sm"
                                             onerror="this.src='https://placehold.co/100x100'">
                                    @endforeach
                                    @if($order->items->count() > 3)
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 border-2 border-white flex items-center justify-center text-[10px] font-black text-gray-500 shadow-sm">
                                            +{{ $order->items->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-sm font-black text-gray-900">₱{{ number_format($order->total_price, 2) }}</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                @if($order->status === 'awaiting_video')
                                    <button onclick="prepareVideoUpload({{ $order->id }})" 
                                            class="bg-[#0d47a1] text-white px-5 py-2 rounded-xl text-xs font-black shadow-lg shadow-blue-50 hover:bg-black transition">
                                        <i class="bi bi-camera-video-fill mr-1"></i> UPLOAD PROOF
                                    </button>
                                @else
                                    <span class="inline-flex items-center text-green-600 bg-green-50 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                        <i class="bi bi-check-circle-fill mr-1"></i> Video Sent
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <i class="bi bi-inbox text-5xl text-gray-200 mb-4 block"></i>
                                <p class="text-gray-400 font-bold">No incoming requests yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

{{-- MODALS --}}
<div class="modal fade" id="uploadIntroVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-[2.5rem] border-0 shadow-2xl">
            <div class="modal-header p-8 border-0 pb-0 text-center">
                <h3 class="text-2xl font-black text-gray-900 w-full">Store Introduction Video</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-8">
                <form action="{{ route('seller.upload-intro') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6 p-8 border-2 border-dashed border-gray-100 rounded-[2rem] bg-gray-50 hover:bg-white transition text-center">
                        <i class="bi bi-camera-reels text-4xl text-[#0d47a1] mb-3 block"></i>
                        <input type="file" name="intro_video" accept="video/*" class="text-xs font-bold text-gray-400" required>
                    </div>
                    <button type="submit" class="w-full bg-[#0d47a1] text-white py-4 rounded-2xl font-black shadow-lg hover:bg-black transition transform active:scale-95">
                        CONFIRM & ACTIVATE STORE
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-[2.5rem] border-0 shadow-2xl">
            <div class="modal-header p-8 border-0 pb-0">
                <h3 class="text-2xl font-black text-gray-900">Upload Video Proof</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-8">
                <form id="videoUploadForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6 p-8 border-2 border-dashed border-gray-100 rounded-[2rem] bg-gray-50 text-center">
                        <i class="bi bi-cloud-arrow-up text-4xl text-[#0d47a1] mb-3 block"></i>
                        <input type="file" name="video" accept="video/*" class="text-xs font-bold text-gray-400" required>
                    </div>
                    <button type="submit" class="w-full bg-[#0d47a1] text-white py-4 rounded-2xl font-black shadow-lg hover:bg-black transition">
                        CONFIRM & NOTIFY BUYER
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Data Injection from Controller
        const months = {!! json_encode($months ?? $monthlyLabels ?? []) !!};
        const salesValues = {!! json_encode($salesValues ?? $monthlySales ?? []) !!};
        const dailyLabels = {!! json_encode($dailyLabels ?? []) !!};
        const dailySales = {!! json_encode($dailySales ?? []) !!};

        // --- Monthly Sales Trend Chart ---
        const monthlyCanvas = document.getElementById('salesTrendChart');
        if (monthlyCanvas) {
            const monthlyCtx = monthlyCanvas.getContext('2d');
            const gradient = monthlyCtx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(13, 71, 161, 0.2)');
            gradient.addColorStop(1, 'rgba(13, 71, 161, 0)');

            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: months.length ? months : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Monthly Sales',
                        data: salesValues.length ? salesValues : [0,0,0,0,0,0],
                        borderColor: '#0d47a1',
                        borderWidth: 3,
                        fill: true,
                        backgroundColor: gradient,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0d47a1',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f3f4f6' },
                            ticks: {
                                callback: function(value) { return '₱' + value.toLocaleString(); }
                            }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

  // --- 2. Daily Revenue Flow Chart (30-Day View) ---
const dailyCanvas = document.getElementById('dailyChart');

if (dailyCanvas) {
    const dailyCtx = dailyCanvas.getContext('2d');

    // Force safe arrays
    const labels = Array.isArray(dailyLabels) && dailyLabels.length
        ? dailyLabels
        : Array.from({ length: 7 }, (_, i) => `Day ${i + 1}`);

    const values = Array.isArray(dailySales) && dailySales.length
        ? dailySales
        : Array(labels.length).fill(0);

    new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Daily Revenue',
                data: values,
                backgroundColor: '#0d47a1',
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' Revenue: ₱' + Number(context.raw).toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        callback: function(value) {
                            return '₱' + Number(value).toLocaleString();
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}
    });

    // Helper for Video Upload Modal
    function prepareVideoUpload(orderId) {
        const form = document.getElementById('videoUploadForm');
        if (form) {
            form.action = `/seller/orders/${orderId}/upload-video`;
            const modalElement = document.getElementById('uploadVideoModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        }
    }
</script>
@endsection