@extends('layouts.app')

@section('content')


 {{-- 1. Randomized Multi-Vendor Carousel Slider Banner --}}
    <div class="relative w-full bg-gray-900 h-[350px] md:h-[500px] overflow-hidden shadow-2xl">
        @if(isset($randomBanners) && $randomBanners->count() > 0)
            <div id="carouselContainer" class="w-full h-full relative">
                @foreach($randomBanners as $index => $banner)
                    <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                        <img src="{{ asset('storage/' . $banner->banner_path) }}" class="w-full h-full object-cover" alt="Featured Vendor Showcase">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-transparent to-transparent flex items-center p-12">
                            <div class="max-w-xl">
                                <span class="bg-[#0052cc] text-white text-[10px] font-extrabold uppercase px-4 py-1.5 rounded-full tracking-[0.2em] shadow-lg">
                                    Premium Partner Feature
                                </span>
                                <h1 class="text-white text-4xl md:text-5xl font-black mt-4 leading-tight">Explore Curated<br>Collections</h1>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Slide Indicators --}}
            <div class="absolute bottom-8 left-8 flex gap-3 z-10">
                @foreach($randomBanners as $index => $banner)
                    <span class="indicator w-8 h-1.5 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white/40' }} transition-all duration-300"></span>
                @endforeach
            </div>
        @else
            <div class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-950 flex flex-col items-center justify-center text-gray-500">
                <p class="text-sm font-bold uppercase tracking-widest text-gray-400">Discover Exclusive Storefronts Soon</p>
            </div>
        @endif
    </div>

    {{-- Main Container Content Hub --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 space-y-16
    
<div class="bg-slate-50 min-h-screen py-20">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-4xl font-black text-slate-900 mb-10">⚡ Express Delivery Items</h1>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @forelse($products as $product)
                {{-- Reuse your polished "Bubble" card design here --}}
                <div class="bg-white rounded-[2rem] p-3 border border-slate-100 shadow-xl shadow-slate-200/40">
                    <div class="w-full h-64 bg-slate-100 rounded-[1.5rem] overflow-hidden relative">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                        @endif
                    </div>
                    <div class="p-4">
                        <h5 class="text-sm font-bold text-slate-900 truncate">{{ $product->name }}</h5>
                        <p class="text-lg font-black text-slate-950 mt-1">₱{{ number_format($product->price, 2) }}</p>
                    </div>
                </div>
            @empty
                <p class="text-slate-500">No express items found.</p>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection