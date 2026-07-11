@extends('layouts.app')

@section('content')
<div class="bg-[#f6f8fb] min-h-screen flex">
    {{-- Sidebar --}}
    @include('seller.partials.sidebar')

    <main class="flex-1 p-6 lg:p-12">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-[#0d47a1]">Sales Analytics</h1>
                <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">Performance for {{ now()->format('M d, Y') }}</p>
            </div>
            
            {{-- Date Selector Display --}}
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex gap-4">
                <div class="text-xs uppercase font-black text-gray-400">
                    Year <br> <span class="text-[#0d47a1] text-lg">2026</span>
                </div>
                <div class="border-l border-gray-100 mx-2"></div>
                <div class="text-xs uppercase font-black text-gray-400">
                    Month <br> <span class="text-[#ef4444] text-lg">{{ now()->format('F') }}</span>
                </div>
            </div>
        </div>

        {{-- Stat Cards Bar (Restricted to Blue and Red) --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-0 mb-10 rounded-2xl overflow-hidden shadow-lg border border-gray-200">
            {{-- Units - Changed to Red --}}
            <div class="bg-[#ef4444] p-6 text-center border-r border-red-500/30">
                <p class="text-[10px] font-black uppercase text-red-100 mb-1">Today's Units</p>
                <h3 class="text-2xl font-black text-white italic">{{ number_format($totalOrders) }}</h3>
            </div>
            {{-- Revenue - Changed to Blue --}}
            <div class="bg-[#0d47a1] p-6 text-center border-r border-blue-400/30">
                <p class="text-[10px] font-black uppercase text-blue-100 mb-1">Total Revenue</p>
                <h3 class="text-2xl font-black text-white italic">₱{{ number_format($totalProfit, 2) }}</h3>
            </div>
            {{-- Expenses - Red --}}
            <div class="bg-[#ef4444] p-6 text-center border-r border-red-500/30">
                <p class="text-[10px] font-black uppercase text-red-100 mb-1">Expenses</p>
                <h3 class="text-2xl font-black text-white italic">₱0.00</h3>
            </div>
            {{-- Gross Profit - Blue --}}
            <div class="bg-[#0d47a1] p-6 text-center">
                <p class="text-[10px] font-black uppercase text-blue-100 mb-1">Gross Profit</p>
                <h3 class="text-2xl font-black text-white italic">₱{{ number_format($totalProfit, 2) }}</h3>
            </div>
        </div>

        {{-- Functional Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            {{-- Daily Chart --}}
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                <h4 class="text-center font-black text-[#0d47a1] uppercase text-xs mb-4">Daily Sales Trend (Last 30 Days)</h4>
                <div class="h-64">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            {{-- Monthly Chart --}}
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                <h4 class="text-center font-black text-[#ef4444] uppercase text-xs mb-4">Monthly Revenue Overview</h4>
                <div class="h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Data Tables Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Daily Breakdown (Green themed elements changed to Blue/Red) --}}
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                {{-- Header - Changed to Blue --}}
                <div class="bg-[#0d47a1] p-4 text-center">
                    <h3 class="text-white font-black text-sm uppercase tracking-widest">Daily Sales Breakdown</h3>
                </div>
                <table class="w-full text-[11px]">
                    {{-- Table Head - Changed to Blue background/text --}}
                    <thead class="bg-blue-50 text-[#0d47a1] font-black uppercase border-b border-blue-100">
                        <tr>
                            <th class="px-6 py-3 text-left">Day</th>
                            <th class="px-6 py-3 text-center">Items</th>
                            <th class="px-6 py-3 text-right">Sales</th>
                            <th class="px-6 py-3 text-right">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($productPerformance as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-600">{{ now()->format('d M') }}</td>
                            <td class="px-6 py-4 text-center font-bold text-gray-900">{{ $item->total_qty }}</td>
                            <td class="px-6 py-4 text-right font-black text-gray-900">₱{{ number_format($item->total_revenue, 2) }}</td>
                            {{-- Profit text - Changed to Red for accent --}}
                            <td class="px-6 py-4 text-right font-black text-[#ef4444]">₱{{ number_format($item->total_revenue, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-400 font-bold uppercase tracking-widest">No daily sales data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Monthly Breakdown (Yellow themed icon in screenshot not in code, ensured table matches) --}}
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="bg-[#ef4444] p-4 text-center">
                    <h3 class="text-white font-black text-sm uppercase tracking-widest">Monthly Sales Breakdown</h3>
                </div>
                <table class="w-full text-[11px]">
                    <thead class="bg-red-50 text-[#ef4444] font-black uppercase border-b border-red-100">
                        <tr>
                            <th class="px-6 py-3 text-left">Month</th>
                            <th class="px-6 py-3 text-center">Items</th>
                            <th class="px-6 py-3 text-right">Sales</th>
                            <th class="px-6 py-3 text-right">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        {{-- Current Month Row --}}
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-600">{{ now()->format('F') }}</td>
                            <td class="px-6 py-4 text-center font-bold text-gray-900">{{ $totalOrders }}</td>
                            <td class="px-6 py-4 text-right font-black text-gray-900">₱{{ number_format($totalProfit, 2) }}</td>
                            {{-- Profit text - Blue accent --}}
                            <td class="px-6 py-4 text-right font-black text-[#0d47a1]">₱{{ number_format($totalProfit, 2) }}</td>
                        </tr>
                        {{-- Mock Data --}}
                        <tr class="opacity-30">
                            <td class="px-6 py-4">Previous Month</td>
                            <td class="px-6 py-4 text-center">0</td>
                            <td class="px-6 py-4 text-right">₱0.00</td>
                            <td class="px-6 py-4 text-right">₱0.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

{{-- Scripts for Functional Charts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: { font: { size: 10, weight: '700' } }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: '700' } }
                }
            }
        };

        // Daily Trend - Using Blue
        new Chart(document.getElementById('dailyChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyLabels) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($dailySales) !!},
                    borderColor: '#0d47a1', // Blue
                    backgroundColor: 'rgba(13, 71, 161, 0.1)', // Blue transparent
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#0d47a1' // Blue
                }]
            },
            options: commonOptions
        });

        // Monthly Trend - Using Red
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyLabels) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($monthlySales) !!},
                    backgroundColor: '#ef4444', // Red
                    borderRadius: 6,
                    barThickness: 25
                }]
            },
            options: commonOptions
        });
    });
</script>
@endsection