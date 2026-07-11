@extends('layouts.app')

@section('content')
<div class="bg-[#f6f8fb] min-h-screen flex">
    {{-- Passing variables to the sidebar to ensure it unlocks visually --}}
    @include('seller.partials.sidebar', ['isVerified' => $isVerified ?? true, 'hasVideo' => $hasVideo ?? true])

    <main class="flex-1 p-6 lg:p-12">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black text-gray-900 mb-2">Order History</h1>
                <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">Track all your transactions</p>
            </div>
            {{-- Optional: Status Badge --}}
            @if($isVerified ?? false)
                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-xs font-black uppercase">
                    Verified Seller
                </span>
            @endif
        </div>

        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex space-x-8">
                <a href="#" class="text-[#0d47a1] font-black border-b-2 border-[#0d47a1] pb-2 no-underline">All Orders</a>
                <a href="#" class="text-gray-400 font-bold hover:text-gray-900 transition no-underline">Completed</a>
                <a href="#" class="text-gray-400 font-bold hover:text-gray-900 transition no-underline">Disputed</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50/50">
                            <th class="px-8 py-4">Transaction</th>
                            <th class="px-8 py-4">Buyer</th>
                            <th class="px-8 py-4">Items</th>
                            <th class="px-8 py-4 text-center">Status</th>
                            <th class="px-8 py-4 text-right">Total Amount</th>
                            <th class="px-8 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($allOrders as $order)
                        <tr class="hover:bg-gray-50/30 transition">
                            {{-- ID --}}
                            <td class="px-8 py-6 font-black text-gray-900">#TRX-{{ $order->id }}</td>
                            
                            {{-- Buyer Name --}}
                            <td class="px-8 py-6">
                                <div class="font-bold text-gray-900">{{ $order->buyer->name ?? 'Guest User' }}</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $order->created_at->format('M d, Y') }}</div>
                            </td>

                            {{-- Items Bought --}}
                            <td class="px-8 py-6">
                                <div class="space-y-1">
                                    @foreach($order->items as $item)
                                        <div class="text-xs text-gray-600 font-bold flex items-center">
                                            <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                            {{ $item->product->name ?? 'Deleted Product' }} 
                                            <span class="text-gray-400 ml-1 font-black">x{{ $item->quantity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-8 py-6 text-center">
                                @php
                                    $statusClasses = [
                                        'paid' => 'bg-green-100 text-green-700',
                                        'pending' => 'bg-orange-100 text-orange-700',
                                        'completed' => 'bg-blue-100 text-blue-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        'video_uploaded' => 'bg-purple-100 text-purple-700',
                                    ];
                                    $class = $statusClasses[strtolower($order->status)] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $class }}">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </td>

                            {{-- Price --}}
                            <td class="px-8 py-6 text-right font-black text-gray-900">₱{{ number_format($order->total_price, 2) }}</td>

                            {{-- Action --}}
                            <td class="px-8 py-6 text-center">
                                <a href="{{ route('seller.orders.show', $order->id) }}" class="inline-block bg-gray-900 text-white text-[10px] font-black uppercase px-4 py-2 rounded-lg hover:bg-[#0d47a1] transition no-underline">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center text-gray-400 font-bold">
                                No transactions found yet.
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