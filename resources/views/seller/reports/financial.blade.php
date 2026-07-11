@extends('layouts.app')

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="bg-[#f6f8fb] min-h-screen flex" x-data="{ openModal: false }">
    @include('seller.partials.sidebar')

    <main class="flex-1 p-8 lg:p-12">
        {{-- Header Section --}}
        <div class="flex justify-between items-end mb-10">
            <div>
                <h1 class="text-3xl font-black text-[#0d47a1] mb-1 uppercase italic tracking-tighter">Financial Statement</h1>
                <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">Real-time Cash Flow & Profitability</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Ending Cash Balance</span>
                <span class="text-4xl font-black text-[#0d47a1]">₱{{ number_format($revenue - $expenses, 2) }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column --}}
            <div class="space-y-8">
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                    <div class="bg-[#0d47a1] p-4 text-center">
                        <h3 class="text-white font-black text-xs uppercase tracking-[0.2em]">Income Statement</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 font-bold text-xs uppercase">Total Revenue</span>
                            <span class="font-black text-gray-800">₱{{ number_format($revenue, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 font-bold text-xs uppercase">Total Expenses</span>
                            <span class="font-black text-red-500">(₱{{ number_format($expenses, 2) }})</span>
                        </div>
                        <div class="pt-4 border-t border-gray-50 flex justify-between items-center">
                            <span class="text-[#0d47a1] font-black text-sm uppercase italic">Gross Profit</span>
                            <span class="text-xl font-black text-[#0d47a1]">₱{{ number_format($revenue - $expenses, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- TRIGGER BUTTON --}}
                <button type="button" 
                    @click="openModal = true" 
                    class="w-full bg-gray-900 text-white p-6 rounded-[2rem] font-black uppercase italic tracking-tighter hover:bg-[#0d47a1] transition shadow-xl flex items-center justify-center space-x-3 group cursor-pointer">
                    <i class="bi bi-plus-circle-fill text-xl group-hover:scale-110 transition"></i>
                    <span>Record Transaction</span>
                </button>
            </div>

            {{-- Right Column: Cash Flow Log --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden h-full">
                    <div class="bg-white p-6 border-b border-gray-50 flex justify-between items-center">
                        <h3 class="font-black text-gray-700 uppercase italic tracking-tighter">Cash Flow Log</h3>
                        <button onclick="window.print()" class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-2 border-gray-100 px-4 py-2 rounded-full hover:bg-gray-50 transition">
                            <i class="bi bi-printer-fill mr-1"></i> Print Log
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black uppercase text-gray-400 tracking-widest bg-gray-50">
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Description</th>
                                    <th class="px-6 py-4">Type</th>
                                    <th class="px-6 py-4 text-right">Amount</th>
                                    <th class="px-6 py-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($cashFlow as $log)
                                <tr class="hover:bg-blue-50/30 transition group">
                                    <td class="px-6 py-4 text-xs font-bold text-gray-500">{{ $log->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-black text-gray-800 italic">{{ $log->description ?? 'Order #'.$log->id }}</div>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">{{ $log->reference ?? 'System Generated' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 {{ ($log->type == 'in' || !isset($log->type)) ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-full text-[9px] font-black uppercase tracking-tighter">
                                            {{ ($log->type == 'in' || !isset($log->type)) ? 'Cash In' : 'Cash Out' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black {{ ($log->type == 'out') ? 'text-red-500' : 'text-gray-800' }}">
                                        {{ ($log->type == 'out') ? '-' : '' }}₱{{ number_format($log->amount ?? $log->total_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(isset($log->is_manual) && $log->is_manual)
                                            <form action="{{ route('seller.reports.financial.destroy', $log->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        @else
                                            <i class="bi bi-shield-lock-fill text-gray-200"></i>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="py-20 text-center text-gray-300 font-black uppercase italic tracking-widest">No history found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- MODAL COMPONENT --}}
    <div 
        x-show="openModal" 
        class="fixed inset-0 flex items-center justify-center p-4" 
        style="z-index: 9999;" 
        x-cloak
    >
        {{-- DARK BACKDROP --}}
        <div 
            class="fixed inset-0 bg-black/60 backdrop-blur-sm" 
            x-show="openModal" 
            @click="openModal = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        ></div>

        {{-- MODAL BOX --}}
        <div 
            class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden"
            x-show="openModal"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="bg-[#0d47a1] p-8 text-white flex justify-between items-center">
                <h2 class="text-2xl font-black uppercase italic tracking-tighter">Record Entry</h2>
                <button @click="openModal = false" class="hover:rotate-90 transition duration-300">
                    <i class="bi bi-x-circle-fill text-2xl"></i>
                </button>
            </div>

            <form action="{{ route('seller.reports.financial.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <input type="hidden" name="is_manual" value="1">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Type</label>
                        <select name="type" class="w-full border-2 border-gray-100 rounded-2xl p-4 font-black text-gray-700 outline-none focus:border-[#0d47a1]">
                            <option value="in">Cash In</option>
                            <option value="out">Cash Out</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Amount (₱)</label>
                        <input type="number" name="amount" step="0.01" required class="w-full border-2 border-gray-100 rounded-2xl p-4 font-black text-gray-700 outline-none focus:border-[#0d47a1]">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Description</label>
                    <input type="text" name="description" required class="w-full border-2 border-gray-100 rounded-2xl p-4 font-black text-gray-700 outline-none focus:border-[#0d47a1]">
                </div>

                <button type="submit" class="w-full p-5 bg-[#0d47a1] text-white rounded-2xl font-black uppercase italic tracking-tighter hover:bg-blue-800 transition shadow-lg">
                    Save Transaction
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection