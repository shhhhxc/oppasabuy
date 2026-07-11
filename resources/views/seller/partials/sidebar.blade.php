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

            {{-- Target Goals: Accessible to help with setup tracking --}}
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

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center space-x-3 p-4 w-full text-red-500 hover:bg-red-50 rounded-2xl font-bold transition border-0 bg-transparent cursor-pointer">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>