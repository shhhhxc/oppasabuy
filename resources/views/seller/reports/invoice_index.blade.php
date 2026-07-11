@extends('layouts.app')

@section('content')
<div class="bg-[#f6f8fb] min-h-screen flex" x-data="{ showModal: false, modalType: 'goal' }">
    {{-- Sidebar --}}
    @include('seller.partials.sidebar')

    {{-- Main Content Area --}}
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-[#0d47a1] italic tracking-tighter uppercase">Receipt Generator</h1>
            <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Generate a quick receipt for any transaction</p>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-white flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Recent Transactions</h3>
                <span class="bg-blue-50 text-[#0d47a1] text-[10px] font-black px-3 py-1 rounded-full uppercase">
                    {{ $orders->count() }} Total Orders
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Buyer Name</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-right">Total Amount</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                            <tr class="hover:bg-blue-50/30 transition group">
                                <td class="px-6 py-4 font-bold text-[#0d47a1]">
                                    #{{ $order->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-700">{{ $order->buyer->name }}</div>
                                    <div class="text-[10px] text-gray-400 uppercase font-medium">{{ $order->buyer->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-sm">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right font-black text-gray-800">
                                    ₱{{ number_format($order->total_price, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('seller.invoice.generate', $order->id) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-[#0d47a1] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-800 transition no-underline shadow-sm hover:shadow-md">
                                        <i class="bi bi-file-earmark-text mr-2"></i> Generate
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="bi bi-inbox text-4xl text-gray-200 mb-2"></i>
                                        <p class="text-gray-400 italic font-medium">No orders found to generate receipts for.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection