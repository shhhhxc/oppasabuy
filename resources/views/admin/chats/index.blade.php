@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-[#163d78] mb-2">Audit Dashboard</h1>
        <p class="text-gray-500">Filter and monitor platform interactions by seller.</p>
    </div>

    <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 min-w-[300px]">
        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Filter by Seller</label>
        <form action="{{ route('admin.chats') }}" method="GET" id="filterForm">
            <select name="seller_id" onchange="document.getElementById('filterForm').submit()" 
                class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold text-[#163d78] focus:ring-2 focus:ring-[#A31D1D]">
                <option value="">All Sellers (Show Everything)</option>
                @foreach($sellers as $seller)
                    <option value="{{ $seller->id }}" {{ request('seller_id') == $seller->id ? 'selected' : '' }}>
                        {{ $seller->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

{{-- SECTION 1: ORDER CHATS --}}
<div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="p-8 border-b border-gray-50 bg-gray-50/50">
        <h3 class="text-xl font-black text-gray-900">Order Transaction Logs</h3>
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">One-on-one business communications</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50">
                    <th class="px-8 py-4">Order & Participants</th>
                    <th class="px-8 py-4">Last Message</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($orderChats as $order)
                @php $lastMsg = $order->messages->first(); @endphp
                <tr class="hover:bg-gray-50/50 transition group">
                    <td class="px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex -space-x-3">
                                <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500">
                                    {{ strtoupper(substr($order->buyer->name ?? 'B', 0, 1)) }}
                                </div>
                                <div class="w-8 h-8 rounded-full border-2 border-white bg-[#A31D1D] flex items-center justify-center text-[10px] font-bold text-white">
                                    {{ strtoupper(substr($order->seller->name ?? 'S', 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-[#A31D1D] uppercase">Order #{{ $order->id }}</span>
                                <span class="block text-sm font-black text-gray-900">{{ $order->buyer->name ?? 'User' }} & {{ $order->seller->name ?? 'Seller' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-[11px] text-gray-500 font-bold block italic">"{{ Str::limit($lastMsg->message ?? 'No text', 40) }}"</span>
                        <span class="text-[9px] text-gray-400 font-bold uppercase">{{ $lastMsg ? $lastMsg->created_at->diffForHumans() : '' }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <span class="inline-block px-2 py-0.5 rounded {{ $order->status === 'paid' ? 'bg-green-50 text-green-500' : 'bg-yellow-50 text-yellow-600' }} text-[9px] font-black uppercase">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <a href="{{ route('admin.chats.show', $order->id) }}" class="inline-flex items-center space-x-2 bg-white border border-gray-100 px-4 py-2 rounded-xl text-[10px] font-black text-gray-600 uppercase tracking-widest hover:bg-gray-900 hover:text-white transition">
                            <i class="bi bi-eye"></i> <span>Audit Order</span>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- SECTION 2: GROUP CHATS (SOCIAL) --}}
<div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b border-gray-50 bg-gray-50/50">
        <h3 class="text-xl font-black text-gray-900">Group Room Logs</h3>
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Social and community interactions</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50">
                    <th class="px-8 py-4">Room Name & Creator</th>
                    <th class="px-8 py-4">Latest Interaction</th>
                    <th class="px-8 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($socialChats as $room)
                @php $lastMsg = $room->messages->first(); @endphp
                <tr class="hover:bg-gray-50/50 transition group">
                    <td class="px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-black text-gray-900">{{ $room->name }}</span>
                                <span class="block text-[10px] text-gray-400 font-bold uppercase">Created by: {{ $room->creator->name ?? 'System' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-[11px] text-gray-500 font-bold block">"{{ Str::limit($lastMsg->message ?? 'No messages yet', 50) }}"</span>
                        <span class="text-[9px] text-gray-400 font-bold uppercase">{{ $lastMsg ? $lastMsg->created_at->diffForHumans() : '' }}</span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        {{-- CRITICAL FIX: Changed route to rooms.show --}}
                        <a href="{{ route('admin.rooms.show', $room->id) }}" class="inline-flex items-center space-x-2 bg-white border border-gray-100 px-4 py-2 rounded-xl text-[10px] font-black text-gray-600 uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition">
                            <i class="bi bi-eye"></i> <span>Audit Room</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-8 py-12 text-center text-gray-400 font-bold italic">No group rooms found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection