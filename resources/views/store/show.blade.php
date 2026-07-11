@extends('layouts.app')

@section('content')
<div class="bg-[#f1f5f9] min-h-screen pb-20 font-sans">
    @php
        // Using null-safe operator or optional() to prevent crashes
        $storeName = $seller->sellerVerification->store_name ?? $seller->name;
        $storeDesc = $seller->sellerVerification->store_description ?? 'No description available.';
        
        $plan = $seller->sellerVerification->plan ?? 'FREE'; 
        $introVideo = $seller->sellerVerification->video_intro_path ?? null;
    @endphp

    <div class="bg-[#163d78] pt-20 pb-36 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#9e1b18] rounded-full translate-x-1/3 translate-y-1/3"></div>
        </div>

        <div class="relative z-10">
            <div class="inline-block bg-[#9e1b18] px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl mb-6 border border-white/20">
                {{ $plan }} MEMBER
            </div>
            <h1 class="text-5xl md:text-6xl font-black tracking-tighter mb-3 drop-shadow-md">
                {{ $storeName }}
            </h1>
            <div class="flex items-center justify-center gap-3">
                <span class="h-[1px] w-8 bg-blue-300/50"></span>
                <p class="text-blue-100 uppercase tracking-[0.4em] text-[11px] font-black opacity-90">
                    Safe Shopping Starts Here
                </p>
                <span class="h-[1px] w-8 bg-blue-300/50"></span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 -mt-24 relative z-20">
        <div class="flex flex-col lg:flex-row gap-10 items-start">

            <div class="w-full lg:w-[360px] shrink-0 lg:sticky lg:top-8">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-[#9e1b18] rounded-full animate-pulse"></span>
                            <h4 class="text-[#163d78] font-black text-[11px] uppercase tracking-widest">Presentation</h4>
                        </div>
                        <span class="text-[9px] font-black bg-blue-50 text-[#163d78] px-2 py-1 rounded-md border border-blue-100 uppercase">
                            {{ $plan }}
                        </span>
                    </div>

                    <div class="group relative aspect-video bg-black rounded-3xl overflow-hidden mb-6">
                        @if($introVideo)
                            <video class="w-full h-full object-cover" controls>
                                <source src="{{ asset('storage/' . $introVideo) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gray-900 text-gray-500">
                                <i class="bi bi-play-circle text-4xl mb-2"></i>
                                <span class="text-[10px] font-bold uppercase">No Introduction Video</span>
                            </div>
                        @endif
                    </div>

                    <h5 class="font-black text-gray-900 text-2xl mb-2">{{ $storeName }}</h5>
                    <p class="text-gray-500 text-sm mb-6">{{ $storeDesc }}</p>
                    
                </div>
            </div>

            <div class="flex-1 space-y-10 w-full">
                <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-end mb-10 border-b border-gray-50 pb-6">
                        <h2 class="text-3xl font-black text-gray-900">
                            Live <span class="text-[#9e1b18]">Products</span>
                        </h2>
                        <span class="text-sm font-black text-[#163d78] bg-gray-50 px-4 py-2 rounded-xl">
                            {{ $products->count() }} ITEMS
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                        @forelse($products as $product)
                            <div class="group flex flex-col bg-white rounded-[2rem] border border-gray-100 p-3 hover:shadow-2xl transition-all">
                                <div class="relative aspect-square bg-[#f8fafc] rounded-[1.5rem] mb-5 overflow-hidden">
                                    <img src="{{ asset('storage/' . $product->image_path) }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                         alt="{{ $product->name }}">
                                    <div class="absolute bottom-4 left-4">
                                        <span class="bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-xl font-black text-[#9e1b18] text-sm shadow-sm">
                                            ₱{{ number_format($product->price, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="px-3 pb-4">
                                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1 block">
                                        {{ $product->category }}
                                    </span>
                                    <h3 class="font-black text-gray-900 text-lg mb-4 line-clamp-1">
                                        {{ $product->name }}
                                    </h3>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-200">
                                @if(request('search'))
                                    <i class="bi bi-exclamation-circle text-5xl text-gray-200 mb-4 block"></i>
                                    <h3 class="text-gray-500 font-black uppercase tracking-widest">Product Unavailable</h3>
                                    <p class="text-gray-400 text-[11px] mt-2">We couldn't find any products matching "{{ request('search') }}"</p>
                                    <a href="{{ url()->current() }}" class="mt-4 inline-block bg-[#163d78] text-white px-4 py-2 rounded-lg font-bold text-[10px] uppercase tracking-tighter">View All Products</a>
                                @else
                                    <div class="animate-pulse">
                                        <i class="bi bi-hour-glass-split text-5xl text-gray-200 mb-4 block"></i>
                                    </div>
                                    <h3 class="text-gray-500 font-black uppercase tracking-widest">Products Coming Soon</h3>
                                    <p class="text-gray-400 text-[11px] mt-2 italic">Hang tight! {{ $storeName }} is currently updating their inventory.</p>
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-black text-gray-900 mb-8 tracking-tight">
                        Digital <span class="text-[#9e1b18]">Brochure</span>
                    </h2>
                    @php 
                        $docPath = $seller->sellerVerification->document_path ?? null; 
                    @endphp
                    @if($docPath)
                        <iframe src="{{ asset('storage/' . $docPath) }}"
                                class="w-full h-[650px] rounded-3xl border shadow-inner"></iframe>
                    @else
                        <div class="h-40 flex items-center justify-center bg-gray-50 rounded-3xl border-2 border-dashed">
                            <p class="text-gray-400 font-bold text-xs uppercase">No brochure found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection