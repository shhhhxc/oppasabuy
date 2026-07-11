@extends('layouts.app')

@section('content')
{{-- 1. STYLE TO HIDE THE MODAL BEFORE ALPINE LOADS --}}
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="bg-[#f6f8fb] min-h-screen flex" x-data="{ showModal: false, modalType: 'goal' }">
    @include('seller.partials.sidebar')

    <main class="flex-1 p-6 lg:p-12">
        <div class="flex justify-between items-start mb-10">
            <div>
                <h1 class="text-3xl font-black text-[#0d47a1] mb-1 uppercase italic tracking-tighter">Target Goals</h1>
                <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">Personalized Business Milestones</p>
            </div>
            <div class="flex gap-3">
                {{-- Button 1: Add Milestone --}}
                <button @click="showModal = true; modalType = 'milestone'" class="bg-white text-[#0d47a1] border-2 border-[#0d47a1] px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-50 transition">
                    + Add Milestone
                </button>
                {{-- Button 2: Set Goal --}}
                <button @click="showModal = true; modalType = 'goal'" class="bg-[#0d47a1] text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-xl hover:bg-[#0a367a] transition">
                    + Set New Goal
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Metrics Tables --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Monthly Metrics --}}
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                    <div class="bg-[#0d47a1] p-4 text-center">
                        <h3 class="text-white font-black text-xs uppercase tracking-[0.2em]">Active Monthly Targets</h3>
                    </div>
                    <table class="w-full text-[11px]">
                        <thead class="bg-blue-50 text-[#0d47a1] font-black uppercase">
                            <tr>
                                <th class="px-6 py-4 text-left">Metrics</th>
                                <th class="px-4 py-4 text-center">Current</th>
                                <th class="px-4 py-4 text-center">Target</th>
                                <th class="px-4 py-4 text-right">Progress</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($monthlyTargets->where('type', '!=', 'Task') as $target)
                            @php $progress = min(100, ($target->target_value > 0 ? ($target->current_value / $target->target_value) * 100 : 0)); @endphp
                            <tr>
                                <td class="px-6 py-4 font-black text-gray-700">{{ $target->type }}</td>
                                <td class="px-4 py-4 text-center font-bold text-gray-900">{{ number_format($target->current_value) }}</td>
                                <td class="px-4 py-4 text-center font-bold text-gray-400">{{ number_format($target->target_value) }}</td>
                                <td class="px-4 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <span class="font-black text-[#0d47a1]">{{ round($progress) }}%</span>
                                        <div class="w-12 bg-gray-100 rounded-full h-1.5">
                                            <div class="bg-[#0d47a1] h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-10 text-center text-gray-400 font-bold uppercase italic">No active numeric targets.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Annual Goals --}}
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                    <div class="bg-[#ef4444] p-4 text-center">
                        <h3 class="text-white font-black text-xs uppercase tracking-[0.2em]">Long-Term Annual Goals</h3>
                    </div>
                    <table class="w-full text-[11px]">
                        <thead class="bg-red-50 text-[#ef4444] font-black uppercase">
                            <tr>
                                <th class="px-6 py-4 text-left">Goal Name</th>
                                <th class="px-4 py-4 text-center">Current</th>
                                <th class="px-4 py-4 text-center">Target</th>
                                <th class="px-4 py-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($annualTargets as $target)
                            @php $progress = min(100, ($target->target_value > 0 ? ($target->current_value / $target->target_value) * 100 : 0)); @endphp
                            <tr>
                                <td class="px-6 py-4 font-black text-gray-700">{{ $target->type }}</td>
                                <td class="px-4 py-4 text-center font-bold text-gray-900">₱{{ number_format($target->current_value, 2) }}</td>
                                <td class="px-4 py-4 text-center font-bold text-gray-400">₱{{ number_format($target->target_value, 2) }}</td>
                                <td class="px-4 py-4 text-right">
                                    <span class="{{ $progress >= 100 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-[#ef4444]' }} px-3 py-1 rounded-full font-black uppercase text-[9px]">
                                        {{ $progress >= 100 ? 'Achieved' : round($progress) . '%' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-10 text-center text-gray-400 font-bold uppercase italic">No annual goals set.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Operational Milestones Checklist --}}
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="bg-[#0d47a1] p-4 text-center">
                    <h3 class="text-white font-black text-xs uppercase tracking-[0.2em]">Operational Milestones</h3>
                </div>
                <div class="p-6 flex-1 space-y-4 overflow-y-auto max-h-[500px]">
                    @forelse($monthlyTargets->where('type', 'Task') as $task)
                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 group transition hover:bg-blue-50">
                        <form action="{{ route('seller.goals.update', $task->id) }}" method="POST" class="flex items-center w-full">
                            @csrf
                            @method('PATCH')
                            <input type="checkbox" onchange="this.form.submit()" {{ $task->current_value >= 1 ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-[#0d47a1] focus:ring-[#0d47a1]">
                            {{-- Display the Milestone Description from the period column --}}
                            <label class="ml-4 text-sm font-bold {{ $task->current_value >= 1 ? 'text-gray-300 line-through' : 'text-gray-600' }} italic">
                                {{ $task->period }}
                            </label>
                        </form>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <p class="text-gray-400 text-xs font-bold uppercase">No Milestones Found</p>
                        <button @click="showModal = true; modalType = 'milestone'" class="mt-2 text-[#0d47a1] text-[10px] font-black uppercase underline">+ Add One Now</button>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- UNIFIED MODAL --}}
        <div x-show="showModal" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            
            <div class="bg-white w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl mx-4" @click.away="showModal = false">
                <div class="bg-[#0d47a1] p-6 text-center relative">
                    <h2 class="text-white font-black uppercase italic tracking-tighter text-xl" x-text="modalType === 'goal' ? 'Define New Target' : 'New Milestone'"></h2>
                    <button @click="showModal = false" class="absolute top-6 right-6 text-white/50 hover:text-white">&times;</button>
                </div>
                
                <form action="{{ route('seller.goals.store') }}" method="POST" class="p-8 space-y-4">
                    @csrf
                    <input type="hidden" name="is_milestone" :value="modalType === 'milestone' ? '1' : '0'">
                    
                    {{-- 1. Goal Name (Numeric Targets) --}}
                    <div x-show="modalType === 'goal'">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Goal Metric Name</label>
                        <input type="text" name="type" :required="modalType === 'goal'" placeholder="e.g. Total Orders" class="w-full mt-1 px-5 py-3 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-[#0d47a1] font-bold text-sm">
                    </div>

                    {{-- 2. Milestone Description (Tasks) --}}
                    {{-- UPDATED: Changed name to 'period' to store description directly --}}
                    <div x-show="modalType === 'milestone'">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Milestone Description</label>
                        <input type="text" name="period" :required="modalType === 'milestone'" :disabled="modalType !== 'milestone'" placeholder="e.g. Complete Store Setup" class="w-full mt-1 px-5 py-3 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-[#0d47a1] font-bold text-sm">
                    </div>

                    <div x-show="modalType === 'goal'" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Target Value</label>
                                <input type="number" name="target_value" :required="modalType === 'goal'" placeholder="1000" class="w-full mt-1 px-5 py-3 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-[#0d47a1] font-bold text-sm">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Period</label>
                                {{-- UPDATED: Disabled when in Milestone mode to let the text input win --}}
                                <select name="period" :disabled="modalType === 'milestone'" class="w-full mt-1 px-5 py-3 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-[#0d47a1] font-bold text-sm">
                                    <option value="monthly">Monthly</option>
                                    <option value="annual">Annual</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showModal = false" class="flex-1 py-4 font-black text-xs uppercase text-gray-400 hover:text-gray-600 transition">Cancel</button>
                        <button type="submit" class="flex-1 bg-[#ef4444] text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-red-100 hover:bg-red-600 transition">
                            Save Entry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

@endsection