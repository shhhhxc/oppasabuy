@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12 font-sans text-gray-900">

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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 space-y-16">
        
      

      {{-- 3. Featured Products: Elevated Marketplace Style --}}
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-16">
            <div class="space-y-4">
                <span class="text-[#0052cc] font-black tracking-[0.2em] uppercase text-[11px]">Shop Now</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900">Featured Products</h2>
                <div class="h-1 w-20 bg-[#0052cc] rounded-full"></div>
            </div>
            <a href="{{ route('products.express') }}" class="group flex items-center gap-2 text-sm font-black text-slate-900 hover:text-[#0052cc] transition-colors uppercase tracking-widest">
                See All Items
                <div class="bg-slate-900 text-white p-1 rounded-full group-hover:bg-[#0052cc] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        </div>
    
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @forelse($fastShippingProducts as $product)
                {{-- Curved "Bubble" Container with soft shadow --}}
                <div class="group bg-white rounded-[2rem] p-3 border border-slate-100 shadow-xl shadow-slate-200/40 hover:-translate-y-2 hover:border-[#0052cc]/20 transition-all duration-500">
                    
                    {{-- Image with extra rounded corners --}}
                    <div class="w-full h-64 bg-slate-100 rounded-[1.5rem] overflow-hidden relative">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $product->name }}">
                        @endif
                        {{-- Floating Badge --}}
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest text-[#0052cc] shadow-sm">
                            Express
                        </div>
                    </div>

                    {{-- Product Info --}}
                    <div class="p-4">
                        <h5 class="text-sm font-bold text-slate-900 truncate mb-1">{{ $product->name }}</h5>
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-black text-slate-950">₱{{ number_format($product->price, 2) }}</p>
                          {{-- Change your button to this --}}
<button 
    onclick="addToCart({{ $product->id }})" 
    class="bg-slate-50 hover:bg-[#0052cc] hover:text-white text-slate-900 p-2 rounded-full transition-all"
    id="add-btn-{{ $product->id }}">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
    </svg>
</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-400">
                    <p>No express items available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
<section class="py-20 /50 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16 space-y-4">
            <span class="text-[#0052cc] font-black tracking-[0.2em] uppercase text-[11px]">Elite Marketplace</span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900">Verified Partner Webstores</h2>
            <div class="h-1 w-20 bg-[#0052cc] mx-auto rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @forelse($vendorsList as $store)

                <a href="{{ route('store.show', $store->user_id) }}" 
                   class="group relative bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 hover:-translate-y-2 transition-all duration-500 flex flex-col items-center text-center overflow-hidden">
                    
                    {{-- Decorative Background Glow --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="relative w-24 h-24 mb-5 p-1 bg-gradient-to-tr from-[#0052cc] to-blue-400 rounded-full">
                        <div class="w-full h-full rounded-full overflow-hidden bg-white">
                            @if($store->logo)
                                <img src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover" alt="{{ $store->name }}">
                            @else
                                <div class="w-full h-full bg-slate-50 flex items-center justify-center text-[#0052cc] font-black text-2xl">
                                    {{ strtoupper(substr($store->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <h4 class="relative text-sm font-black text-slate-900 w-full truncate mb-2">{{ $store->name }}</h4>
                    
                    {{-- Verified Badge with Glow --}}
                    <div class="relative flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-100 shadow-sm">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                        <span class="text-[9px] font-black uppercase tracking-widest">Verified</span>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-12 text-center text-slate-400">No partner webstores found.</div>
            @endforelse
        </div>
        
        <div class="flex justify-center mt-16">
            {{ $vendorsList->links('pagination::tailwind') }}
        </div>
    </div>
</section>
    </div>

   

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.carousel-slide');
        const indicators = document.querySelectorAll('.indicator');
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.opacity = i === index ? '1' : '0';
                indicators[i].className = i === index ? 'indicator w-8 h-1.5 rounded-full bg-white transition-all duration-300' : 'indicator w-8 h-1.5 rounded-full bg-white/40 transition-all duration-300';
            });
        }

        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }, 5000);
    });
</script>

<script>
function addToCart(productId) {
    const btn = document.getElementById(`add-btn-${productId}`);
    
    // 1. Optional: Client-side check (fastest)
    @guest
        window.location.href = '{{ route('login') }}';
        return;
    @endguest

    // 2. Perform the request
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
    })
    .then(response => {
        // 3. Handle Unauthorized (if guest somehow bypassed the check)
        if (response.status === 401) {
            window.location.href = '{{ route('login') }}';
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Product added to cart!');
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection