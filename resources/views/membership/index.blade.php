@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();

    $displayName = $user->full_name
        ?? $user->name
        ?? 'Oppasabuy Member';

    $memberId = 'OPPA-' . now()->format('Y') . '-' . str_pad((string) ($user->id ?? 0), 6, '0', STR_PAD_LEFT);

    $initials = collect(preg_split('/\s+/', trim($displayName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');

    $maskedCardNumber = 'XXXX-XXXX-' . str_pad((string) ($user->id ?? 0), 4, '0', STR_PAD_LEFT);
@endphp

<div class="min-h-screen bg-[#eef3f8] py-8 px-4">
    <div class="mx-auto max-w-6xl">

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6">
            <p class="text-xs font-black uppercase tracking-[0.25em] text-blue-700">Oppasabuy Membership</p>
            <h1 class="mt-2 text-3xl font-black text-slate-900">
                Welcome, {{ $displayName }}
            </h1>
            <p class="mt-2 text-sm font-medium text-slate-500">
                Your digital membership card is ready. Rewards and QR payment features are coming soon.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-[2rem] border border-blue-900/10 bg-white shadow-xl">
                    <div class="relative overflow-hidden bg-gradient-to-br from-[#0057b8] via-[#0d47a1] to-[#263b8f] p-8 text-white">
                        <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-20 left-20 h-56 w-56 rounded-full bg-cyan-300/10"></div>

                        <div class="relative flex flex-col gap-8 md:flex-row md:items-start md:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.25em] text-blue-100">
                                    Available Points
                                </p>

                                <div class="mt-3 flex items-end gap-2">
                                    <span class="text-5xl font-black">0</span>
                                    <span class="pb-1 text-xl font-black">Pts</span>
                                </div>

                                <div class="mt-5 inline-flex items-center rounded-full bg-white/15 px-4 py-2 text-xs font-bold backdrop-blur">
                                    {{ $maskedCardNumber }}
                                </div>
                            </div>

                            <div class="flex h-24 w-24 items-center justify-center rounded-full border-4 border-white/30 bg-white/15 text-3xl font-black shadow-lg backdrop-blur">
                                {{ $initials ?: 'OM' }}
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Member Name</p>
                                <h2 class="mt-2 text-2xl font-black text-[#0d47a1]">{{ $displayName }}</h2>

                                <p class="mt-5 text-xs font-black uppercase tracking-[0.2em] text-slate-400">Member ID</p>
                                <div class="mt-2 inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-3 font-mono text-sm font-black text-slate-700">
                                    <i class="bi bi-person-vcard-fill text-blue-700"></i>
                                    {{ $memberId }}
                                </div>
                            </div>

                            <div class="text-left md:text-right">
                                <div class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-4 py-2 text-xs font-black uppercase tracking-wider text-amber-700">
                                    <i class="bi bi-hourglass-split"></i>
                                    Rewards Coming Soon
                                </div>

                                <p class="mt-4 text-sm font-bold text-slate-500">
                                    Oppasabuy Digital Reward Card
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Membership QR</p>
                        <h3 class="mt-1 text-xl font-black text-slate-900">Scan Card</h3>
                    </div>

                    <i class="bi bi-qr-code-scan text-3xl text-blue-700"></i>
                </div>

                <div class="mt-6 rounded-2xl bg-slate-50 p-6">
                    <div class="mx-auto grid h-44 w-44 grid-cols-7 gap-1 rounded-xl bg-white p-4 shadow-inner">
                        @foreach([
                            1,1,1,0,1,1,1,
                            1,0,1,1,1,0,1,
                            1,1,1,0,1,1,1,
                            0,1,0,1,0,1,0,
                            1,1,1,0,1,0,1,
                            1,0,1,1,0,1,1,
                            1,1,1,0,1,1,1
                        ] as $pixel)
                            <span class="{{ $pixel ? 'bg-slate-950' : 'bg-white' }} rounded-[2px]"></span>
                        @endforeach
                    </div>
                </div>

                <button
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#comingSoonModal"
                    class="mt-6 w-full rounded-xl bg-blue-700 px-4 py-3 text-xs font-black uppercase tracking-wider text-white transition hover:bg-blue-800"
                >
                    <i class="bi bi-qr-code me-2"></i>
                    View QR Details
                </button>
            </div>

        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Activity</p>
                        <h3 class="mt-1 text-xl font-black text-slate-900">Points & Claims</h3>
                    </div>

                    <i class="bi bi-clock-history text-2xl text-blue-700"></i>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#comingSoonModal"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 text-left transition hover:border-blue-300 hover:bg-blue-50"
                    >
                        <i class="bi bi-stars text-xl text-blue-700"></i>
                        <span class="mt-2 block text-sm font-black text-slate-900">Points History</span>
                        <span class="mt-1 block text-xs font-medium text-slate-500">Coming soon</span>
                    </button>

                    <button
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#comingSoonModal"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 text-left transition hover:border-blue-300 hover:bg-blue-50"
                    >
                        <i class="bi bi-gift-fill text-xl text-blue-700"></i>
                        <span class="mt-2 block text-sm font-black text-slate-900">Claim History</span>
                        <span class="mt-1 block text-xs font-medium text-slate-500">Coming soon</span>
                    </button>
                </div>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Rewards</p>
                        <h3 class="mt-1 text-xl font-black text-slate-900">Rewards Redemption</h3>
                    </div>

                    <i class="bi bi-gift text-2xl text-blue-700"></i>
                </div>

                <div class="mt-6 grid grid-cols-3 gap-3">
                    @foreach([
                        ['icon' => 'bi-ticket-perforated-fill', 'label' => 'Voucher'],
                        ['icon' => 'bi-cup-straw', 'label' => 'Freebie'],
                        ['icon' => 'bi-bag-heart-fill', 'label' => 'Exclusive'],
                    ] as $reward)
                        <button
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#comingSoonModal"
                            class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center transition hover:border-blue-300 hover:bg-blue-50"
                        >
                            <i class="bi {{ $reward['icon'] }} text-2xl text-blue-700"></i>
                            <span class="mt-2 block text-xs font-black text-slate-800">{{ $reward['label'] }}</span>
                            <span class="mt-1 block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                Soon
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</div>

<div class="modal fade" id="comingSoonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden rounded-3xl border-0 shadow-2xl">
            <div class="bg-gradient-to-br from-blue-700 to-indigo-900 px-6 py-8 text-center text-white">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/15 text-3xl backdrop-blur">
                    <i class="bi bi-rocket-takeoff-fill"></i>
                </div>

                <h3 class="mt-4 text-2xl font-black">Coming Soon</h3>
                <p class="mt-2 text-sm font-medium text-blue-100">
                    Oppasabuy Membership Rewards is currently under development.
                </p>
            </div>

            <div class="p-6 text-center">
                <p class="text-sm font-medium leading-6 text-slate-600">
                    Stay tuned for points earning, QR payments, reward redemptions, exclusive member discounts, and claim history.
                </p>

                <div class="mt-5 rounded-xl bg-slate-100 px-4 py-3 font-mono text-sm font-black text-slate-700">
                    {{ $memberId }}
                </div>

                <button
                    type="button"
                    data-bs-dismiss="modal"
                    class="mt-6 w-full rounded-xl bg-slate-900 px-5 py-3 text-xs font-black uppercase tracking-wider text-white hover:bg-black"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
