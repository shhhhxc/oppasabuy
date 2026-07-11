@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-7xl mx-auto px-6">
    <div class="mb-10 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-black text-[#163d78] mb-2">Seller Insights</h1>
            <p class="text-gray-500">Analyze performance, income, and customer engagement for Oppasabuy sellers.</p>
        </div>
        <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 flex gap-8">
            <div class="text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Platform Sales</p>
                <p class="font-black text-[#163d78]">₱{{ number_format($sellers->sum('total_income'), 2) }}</p>
            </div>
            <div class="text-center border-l pl-8">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Total Unique Chats</p>
                <p class="font-black text-[#163d78]">{{ $sellers->sum('unique_customers_count') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sellers as $seller)
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center font-bold text-[#163d78] text-xl">
                        {{ substr($seller->name, 0, 1) }}
                    </div>
                    <span class="bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                        Verified
                    </span>
                </div>

                <h3 class="text-xl font-black text-[#163d78] mb-1">{{ $seller->name }}</h3>
                <p class="text-sm text-gray-400 mb-4">{{ $seller->email }}</p>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Products Sold:</span>
                        <span class="font-bold text-[#163d78]">{{ $seller->products_sold_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Total Income:</span>
                        <span class="font-bold text-green-600">₱{{ number_format($seller->total_income ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Customer Chats:</span>
                        <span class="font-bold text-[#A31D1D]">{{ $seller->unique_customers_count ?? 0 }} Unique</span>
                    </div>
                </div>

                <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">7-Day Profit Trend</p>
                <div class="h-24 mb-6">
                    <canvas id="chart-{{ $seller->id }}"></canvas>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                    <button type="button" 
                       onclick="openChatModal('{{ addslashes($seller->name) }}', '{{ $seller->unique_customers_count }}')" 
                       class="text-xs font-black text-gray-400 hover:text-[#A31D1D] uppercase tracking-widest transition-colors">
                        View Chat Logs
                    </button>
                    
                </div>
            </div>

            <script>
                (function() {
                    const ctx = document.getElementById('chart-{{ $seller->id }}');
                    const trendData = {!! json_encode($seller->profit_trend ?? [0,0,0,0,0,0,0]) !!};
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['6d', '5d', '4d', '3d', '2d', '1d', 'Today'],
                            datasets: [{
                                data: trendData,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                fill: true,
                                borderWidth: 2,
                                tension: 0.4,
                                pointRadius: 2
                            }]
                        },
                        options: {
                            plugins: { legend: { display: false } },
                            scales: { x: { display: false }, y: { display: false, beginAtZero: true } },
                            maintainAspectRatio: false
                        }
                    });
                })();
            </script>
        @empty
            <div class="col-span-full bg-white rounded-[2.5rem] p-12 text-center border border-dashed border-gray-200">
                <p class="text-gray-400">No verified sellers found to analyze yet.</p>
            </div>
        @endforelse
    </div>
</div>

<div id="chatModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center transition-all duration-300">
    <div class="fixed inset-0 bg-[#163d78]/20 backdrop-blur-sm transition-opacity" onclick="closeChatModal()"></div>

    <div class="relative bg-white rounded-[3rem] w-full max-w-lg mx-4 shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] transform transition-all overflow-hidden border border-white/20">
        <div class="p-10">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-2xl font-black text-[#163d78]" id="modalTitle">Interaction Log</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Oppasabuy Analytics</p>
                </div>
                <button onclick="closeChatModal()" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <div id="modalChatContent">
                </div>

            <div class="mt-10">
                <button onclick="closeChatModal()" class="w-full bg-[#163d78] text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-[#1e4b8f] shadow-lg shadow-[#163d78]/20 transition-all">
                    Close Log
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openChatModal(name, count) {
        document.getElementById('modalTitle').innerText = name;
        const content = document.getElementById('modalChatContent');
        
        content.innerHTML = `
            <div class="p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 text-center">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4 border border-gray-50">
                    <i class="bi bi-chat-dots-fill text-2xl text-[#A31D1D]"></i>
                </div>
                <p class="text-[#163d78] font-black text-5xl mb-2">${count}</p>
                <p class="text-[10px] text-gray-400 mb-8 font-black uppercase tracking-[0.2em]">Unique Customer Threads</p>
                
                <a href="{{ route('admin.chats') }}" class="inline-block bg-white border border-gray-200 text-[#163d78] px-8 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all">
                    View Platform History
                </a>
            </div>
        `;
        
        const modal = document.getElementById('chatModal');
        modal.classList.remove('hidden');
        // Small delay to allow CSS transitions if you add them later
    }

    function closeChatModal() {
        document.getElementById('chatModal').classList.add('hidden');
    }
</script>
@endsection