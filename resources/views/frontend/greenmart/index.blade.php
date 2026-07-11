@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 font-sans">

    {{-- Hero Section --}}
    <section class="relative bg-white overflow-hidden border-b border-gray-100">
        <div class="container mx-auto px-4 py-12 md:py-16">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="md:w-7/12 z-10">
                    <h1 class="text-4xl md:text-5xl font-black text-green-900 leading-tight">Fresh from the market.<br>Complete at home.</h1>
                    <p class="text-lg text-green-700 mt-4 mb-8">Wet market essentials and sari-sari products, delivered to you.</p>
                    <a href="#featured-products" class="inline-block bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-8 rounded-full transition-all shadow-lg hover:shadow-green-200">
                        BUY NOW
                    </a>
                </div>
                <div class="md:w-5/12">
                    <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?q=80&w=600&auto=format&fit=crop" alt="Fresh Produce" class="rounded-3xl shadow-2xl w-full h-[250px] object-cover rotate-2">
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 mt-8">

        {{-- Seller Sections Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            
            {{-- Wet Market Sellers --}}
            <section>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-black text-gray-800">Wet Market Sellers</h2>
                    <a href="{{ url('/green-mart/see-all?type=wet-market') }}" class="text-sm font-bold text-green-700 hover:text-green-900">View all</a>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    @forelse($wetMarkets as $market)
                        @php
                            $wetMarketName = ($market->verification && !empty($market->verification->wet_market_name)) ? $market->verification->wet_market_name : (($market->verification && !empty($market->verification->store_name)) ? $market->verification->store_name : $market->name);
                            $wetMarketAddress = ($market->verification && !empty($market->verification->store_address)) ? $market->verification->store_address : ($market->address ?? 'Nearby');
                        @endphp
                        <a href="{{ route('seller-store', ['id' => $market->id, 'type' => 'wet-market']) }}" class="group bg-white p-3 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                            <img src="{{ $market->logo ? asset('storage/' . $market->logo) : 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?q=80&w=200' }}" class="w-full aspect-square object-cover rounded-xl mb-3">
                            <p class="font-bold text-sm text-gray-900 truncate">{{ $wetMarketName }}</p>
                            <p class="text-[10px] text-gray-500 mt-1 truncate">{{ $wetMarketAddress }}</p>
                        </a>
                    @empty
                        <p class="col-span-3 text-sm text-gray-400">No sellers registered yet.</p>
                    @endforelse
                </div>
            </section>

            {{-- Sari-Sari Stores --}}
            <section>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-black text-gray-800">Sari-Sari Stores</h2>
                    <a href="{{ url('/green-mart/see-all?type=sari-sari') }}" class="text-sm font-bold text-green-700 hover:text-green-900">View all</a>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    @forelse($sariSariStores as $store)
                        @php
                            $sariName = ($store->verification && !empty($store->verification->sari_sari_name)) ? $store->verification->sari_sari_name : (($store->verification && !empty($store->verification->store_name)) ? $store->verification->store_name : $store->name);
                            $sariAddress = ($store->verification && !empty($store->verification->store_address)) ? $store->verification->store_address : ($store->address ?? 'Nearby');
                        @endphp
                        <a href="{{ route('seller-store', ['id' => $store->id, 'type' => 'sari-sari']) }}" class="group bg-white p-3 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                            <img src="{{ $store->logo ? asset('storage/' . $store->logo) : 'https://images.unsplash.com/photo-1578916171728-46686eac8d58?q=80&w=200' }}" class="w-full aspect-square object-cover rounded-xl mb-3">
                            <p class="font-bold text-sm text-gray-900 truncate">{{ $sariName }}</p>
                            <p class="text-[10px] text-gray-500 mt-1 truncate">{{ $sariAddress }}</p>
                        </a>
                    @empty
                        <p class="col-span-3 text-sm text-gray-400">No stores registered yet.</p>
                    @endforelse
                </div>
            </section>
        </div>

       {{-- Modernized Wrapper Section with Blue Buttons --}}
<section id="featured-products" class="bg-gray-50 p-6 md:p-10 rounded-[2rem] border border-gray-100 shadow-inner mb-12">
    
    <div class="flex items-center justify-center gap-4 text-gray-400 font-black text-[10px] uppercase tracking-[0.2em] mb-10">
        <span class="h-px bg-gray-300 w-16 block"></span>
        <span class="text-gray-900">Featured Products</span>
        <span class="h-px bg-gray-300 w-16 block"></span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-6 gap-6">
        @foreach($featuredProducts as $product)
            <div class="group bg-white p-4 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                <a href="{{ url('/product/' . $product->id) }}" class="block">
                    <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden mb-4">
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <p class="font-bold text-sm text-gray-900 truncate mb-1">{{ $product->name }}</p>
                    <p class="text-indigo-600 font-black text-xs mb-4">₱{{ number_format($product->price, 2) }}</p>
                </a>
                
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-auto">
                    @csrf
                    {{-- Modern Blue Button --}}
                    <button type="submit" class="w-full py-2.5 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-md shadow-blue-500/20">
                        Add to Cart
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</section>
        {{-- Features Grid --}}
        <section class="py-8">
            <h3 class="text-center font-bold text-gray-900 mb-8">Why Choose Oppa Green Mart?</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                @php
                    $features = [
                        ['icon' => 'bi-shop', 'title' => 'Wide Selection', 'desc' => 'Wet market & Sari-sari in one.'],
                        ['icon' => 'bi-shield-check', 'title' => 'Trusted Sellers', 'desc' => 'Verified stores only.'],
                        ['icon' => 'bi-truck', 'title' => 'Instant Delivery', 'desc' => 'Quick to your door.'],
                        ['icon' => 'bi-geo-alt', 'title' => 'Pick Up', 'desc' => 'Shop at home, pick up.'],
                        ['icon' => 'bi-basket3', 'title' => 'Pabili Service', 'desc' => 'We shop for you.']
                    ];
                @endphp
                @foreach($features as $feat)
                    <div class="text-center p-4">
                        <i class="bi {{ $feat['icon'] }} text-2xl text-green-700 mb-2"></i>
                        <p class="font-black text-xs text-gray-900 uppercase">{{ $feat['title'] }}</p>
                        <p class="text-[10px] text-gray-500 mt-1">{{ $feat['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection