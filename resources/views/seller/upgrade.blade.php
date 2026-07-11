@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    @include('seller.partials.sidebar')

    <main class="flex-1 p-8">
        <div class="max-w-6xl mx-auto">

            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Upgrade Plan</h1>
                    <p class="text-gray-500 font-medium">Scale your business with better visibility and tools.</p>
                </div>

                <div class="text-right">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">
                        Current Plan
                    </span>

                    <span class="px-4 py-2 bg-blue-50 text-[#0d47a1] rounded-xl font-black italic">
                        {{ ucfirst($store->plan ?? 'Free') }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8">

                    <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-5">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-stars text-2xl text-amber-600"></i>

                            <div>
                                <h4 class="font-black text-amber-700">
                                    Subscription Plans Coming Soon
                                </h4>

                                <p class="text-sm text-amber-600 mt-1">
                                    Seller subscriptions are currently under development.
                                    You can preview the available plans below, but upgrades are
                                    not yet available.
                                </p>
                            </div>
                        </div>
                    </div>

                    @php
                        $plans = [
                            'free'=>[
                                'name'=>'Free Plan',
                                'price'=>'0',
                                'desc'=>'Entry option for testing',
                                'features'=>['Basic store setup','Direct chat access']
                            ],
                            'basic'=>[
                                'name'=>'Basic Plan',
                                'price'=>'99',
                                'desc'=>'Starter for small sellers',
                                'features'=>['Buyer request video','Basic tagging']
                            ],
                            'pro'=>[
                                'name'=>'Pro',
                                'price'=>'199',
                                'desc'=>'For more exposure',
                                'features'=>['Featured listing','Priority visibility']
                            ],
                            'premium'=>[
                                'name'=>'Premium',
                                'price'=>'299',
                                'desc'=>'Best visibility',
                                'features'=>['Verified top badge','Boosted exposure']
                            ]
                        ];

                        $currentPlan = $store->plan ?? 'free';
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                        @foreach($plans as $key=>$details)

                        <div class="relative">

                            @if($currentPlan==$key)
                            <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#0d47a1] text-white text-[10px] px-3 py-1 rounded-full font-black uppercase tracking-widest">
                                Current
                            </span>
                            @endif

                            <div class="h-full border-2 {{ $currentPlan==$key ? 'border-[#0d47a1] bg-blue-50':'border-gray-100' }} rounded-3xl p-6 flex flex-col">

                                <div class="text-lg font-black text-gray-800 mb-1">{{ $details['name'] }}</div>

                                <div class="text-2xl font-black text-[#0d47a1] mb-2">
                                    ₱{{ $details['price'] }}
                                    <span class="text-xs text-gray-400 font-bold">/mo</span>
                                </div>

                                <p class="text-[11px] text-gray-400 font-bold uppercase mb-4">
                                    {{ $details['desc'] }}
                                </p>

                                <ul class="space-y-3 flex-1">
                                    @foreach($details['features'] as $feature)
                                    <li class="flex items-start text-sm font-bold text-gray-500">
                                        <i class="bi bi-patch-check-fill text-blue-500 mr-2 mt-0.5"></i>
                                        {{ $feature }}
                                    </li>
                                    @endforeach
                                </ul>

                            </div>

                        </div>

                        @endforeach

                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end items-center space-x-6">

                        <span class="text-gray-400 text-sm italic">
                            Subscription upgrades will be available in a future update.
                        </span>

                        <button
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#comingSoonModal"
                            class="bg-[#0d47a1] text-white px-12 py-4 rounded-2xl font-black hover:bg-blue-800 transition">
                            Coming Soon
                        </button>

                    </div>

                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="comingSoonModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-body p-5 text-center">
                <i class="bi bi-stars text-primary" style="font-size:4rem;"></i>

                <h3 class="fw-black mt-3">
                    Coming Soon
                </h3>

                <p class="text-muted mt-3">
                    Oppasabuy Seller Subscription Plans are currently under development.
                    Soon you'll be able to upgrade your store for better visibility,
                    premium tools, and more customer reach.
                </p>

                <button
                    type="button"
                    class="btn btn-primary px-5 mt-3"
                    data-bs-dismiss="modal">
                    Got it
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
