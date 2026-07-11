@extends('layouts.admin')

@section('content')
<div class="bg-[#f1f5f9] min-h-screen pb-20 font-sans">
    <div class="max-w-7xl mx-auto px-6 pt-10">
        <h1 class="text-3xl font-black text-[#163d78] mb-2">Admin Dashboard</h1>
        <p class="text-gray-500 mb-8">Welcome back, Admin. Here is what's happening at Oppasabuy today.</p>

        <!-- STATS CARDS (Data Analyst View) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total Sellers</p>
                <h3 class="text-2xl font-black text-[#163d78]">24</h3>
            </div>
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Pending Verifications</p>
                <h3 class="text-2xl font-black text-[#9e1b18]">3</h3>
            </div>
            {{-- Add more cards for Products and Appointments --}}
        </div>

        <!-- QUICK ACTIONS -->
        <div class="grid lg:grid-cols-2 gap-8">
            <a href="{{ route('admin.verify') }}" class="group bg-white p-8 rounded-[3.5rem] shadow-sm border border-gray-100 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-black text-[#163d78] mb-2">Registration Requests</h2>
                        <p class="text-gray-500 text-sm">Review pending Buyer and Seller profiles.</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-2xl group-hover:bg-[#163d78] group-hover:text-white transition-colors">
                        <i class="bi bi-person-check text-2xl"></i>
                    </div>
                </div>
            </a>

            <a href="#" class="group bg-white p-8 rounded-[3.5rem] shadow-sm border border-gray-100 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-black text-[#163d78] mb-2">Chat & Media Logs</h2>
                        <p class="text-gray-500 text-sm">Monitor community conversations and attachments.</p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-2xl group-hover:bg-[#9e1b18] group-hover:text-white transition-colors">
                        <i class="bi bi-chat-dots text-2xl"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection