@extends('layouts.app')

@section('content')
<div class="bg-[#e5e5e5] min-h-screen pb-12">

    {{-- 1. Dynamic Banner Sliders Matrix Structure --}}
    <div class="w-full bg-gray-900 h-[260px] md:h-[420px] overflow-hidden relative group">
        @php
            $sliders = is_string($storeConfig->banner_slider_paths)
                ? json_decode($storeConfig->banner_slider_paths, true)
                : ($storeConfig->banner_slider_paths ?? []);
        @endphp

        <div id="webstore-carousel-viewport" class="w-full h-full relative">
            @if(!empty($sliders))
                @foreach($sliders as $idx => $slideImg)
                    <div class="webstore-slide absolute inset-0 w-full h-full transition-opacity duration-700 ease-in-out {{ $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-slide-index="{{ $idx }}">
                        <img src="{{ asset('storage/' . $slideImg) }}" class="w-full h-full object-cover" alt="Slide Banner Mini">
                    </div>
                @endforeach
            @else
                {{-- Fallback matching baseline layout wireframes --}}
                <div class="w-full h-full bg-gradient-to-r from-blue-900 to-indigo-900 flex items-center justify-center text-white/20 font-black text-2xl tracking-widest uppercase">
                    Welcome to Our Webstore
                </div>
            @endif
        </div>
        
        {{-- Slide Indicators/Dots Row Configuration --}}
        @if(count($sliders) > 1)
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
                @foreach($sliders as $idx => $slideImg)
                    <span class="carousel-bullet w-3 h-3 rounded-full bg-white transition cursor-pointer shadow-sm {{ $idx === 0 ? 'opacity-100 scale-110' : 'opacity-40' }}" onclick="navigateToSlide({{ $idx }})"></span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 space-y-6">

     {{-- 2. Vendor Business Profiler Badge Bar --}}
<div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-6 items-center">

    {{-- Left Column: Identity Info --}}
    <div class="flex items-center gap-4">
        @if($storeConfig && !empty($storeConfig->logo_path))
            <img src="{{ asset('storage/' . $storeConfig->logo_path) }}" class="w-16 h-16 rounded-full object-cover border border-gray-200 shadow-md" alt="{{ $vendor->name }} Logo">
        @elseif(isset($vendor->store) && $vendor->store->logo)
            <img src="{{ asset('storage/' . $vendor->store->logo) }}" class="w-16 h-16 rounded-full object-cover border border-gray-200 shadow-md" alt="{{ $vendor->name }} Logo">
        @else
            <div class="w-16 h-16 rounded-full bg-[#5fa8d3] flex items-center justify-center font-black text-white text-xl shadow-md">
                {{ strtoupper(substr($vendor->name, 0, 2)) }}
            </div>
        @endif

        <div>
            @php
                $webstoreName = ($storeConfig && !empty($storeConfig->webstore_name))
                    ? $storeConfig->webstore_name
                    : (($storeConfig && !empty($storeConfig->store_name))
                        ? $storeConfig->store_name
                        : (($vendor->store && !empty($vendor->store->name))
                            ? $vendor->store->name
                            : $vendor->name));

                $webstoreBio = ($storeConfig && !empty($storeConfig->store_description)) 
                    ? $storeConfig->store_description 
                    : ($vendor->description ?? 'No bio description set yet.');
            @endphp

            <h1 class="text-lg font-black text-gray-900 tracking-tight">{{ $webstoreName }}</h1>

            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 mt-0.5">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path>
                </svg>
                Verified Webstore Seller
            </span>

            <p class="text-xs font-medium text-gray-400 mt-0.5">
                BIO: {{ $webstoreBio }}
            </p>
        </div>
    </div>

    {{-- Center Column: Stats --}}
    <div class="grid grid-cols-2 gap-4 text-xs font-bold text-gray-500 border-x border-gray-100 px-6">
        <div class="flex items-center gap-1">
            👥 Followers:
            <span class="text-gray-900 font-black">
                {{ count($store->followers ?? []) }}
            </span>
        </div>

        <div class="flex items-center gap-1">
            📅 Open since:
            <span class="text-gray-900 font-black">
                {{ $storeConfig->webstore_established_year ?? 'Not specified' }}
            </span>
        </div>

        <div class="flex items-center gap-1">
            ⭐️ Rating:
            <span class="text-gray-900 font-black">4.9</span>
        </div>

        <div class="flex items-center gap-1">
            🕒 Store hours:
            <span class="text-gray-900 font-black">
                {{ $storeConfig->webstore_store_hours ?? 'Not specified' }}
            </span>
        </div>
    </div>

    {{-- Right Column: Actions --}}
    <div class="flex items-center justify-start md:justify-end gap-3">

       {{-- Chat Now: Uses storeDirect method in RoomController --}}
 <form action="{{ route('rooms.store') }}" method="POST">
    @csrf
    {{-- $vendor->id is the user_id of the store owner --}}
    <input type="hidden" name="user_id" value="{{ $vendor->id }}">

    <button type="submit" class="flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-gray-200 text-gray-600 font-bold text-xs rounded-xl shadow-sm hover:bg-gray-50 transition">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        Chat Now
    </button>
    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-xs">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</form>
        @php
            $followers = $store->followers ?? [];
            $isFollowing = in_array(auth()->id(), $followers);
        @endphp

        <form action="{{ route('store.follow', $store->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-6 py-2 {{ $isFollowing ? 'bg-gray-400' : 'bg-[#3a86c8]' }} text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-sm hover:opacity-90 transition">
                {{ $isFollowing ? 'Following' : '👤 Follow' }}
            </button>
        </form>

    </div>

</div>

        {{-- 3. Multimedia Showroom Row (Video Intro & Announcement Boards) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Video Presentation Component --}}
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between items-center min-h-[220px]">
                @if($storeConfig && $storeConfig->video_intro_path)
                    <div class="w-full h-full rounded-lg overflow-hidden">
                        <video src="{{ asset('storage/' . $storeConfig->video_intro_path) }}" controls class="w-full h-full object-contain"></video>
                    </div>
                @else
                    <div class="text-center my-auto">
                        <p class="text-sm font-black text-gray-400 uppercase tracking-wider">Introduction Video</p>
                        <p class="text-xs text-gray-400 uppercase tracking-widest mt-1">Of Your Business</p>
                    </div>
                @endif
            </div>

            {{-- Promotional Content Customization Matrix --}}
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between items-center text-center min-h-[220px]">
                <div class="my-auto">
                    <p class="text-sm font-black text-gray-400 uppercase tracking-wider">Promotional Materials</p>
                    <p class="text-xs text-gray-400 uppercase tracking-widest mt-1 mb-3">Or Announcements</p>
                    <p class="text-xs font-bold text-gray-600 max-w-sm px-4 leading-relaxed">
                        {{ $storeConfig->webstore_promotional_text ?? 'Welcome to our webstore storefront! Browse our active catalog options below.' }}
                    </p>
                </div>
            </div>
        </div>
    
@foreach($productsByChannel as $channelName => $channelProducts)
    <div class="lg:col-span-3 bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex flex-col mb-6">
        <h3 class="text-sm font-black text-gray-500 uppercase tracking-widest mb-6">
            {{ $labels[$channelName] ?? str_replace('_', ' ', $channelName) }}
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($channelProducts->take(8) as $product)
                <div class="border rounded-xl p-4 hover:shadow-md transition-shadow flex flex-col">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-48 object-cover rounded-lg">
                    @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center rounded-lg text-xs text-gray-400">No Image</div>
                    @endif

                    <p class="text-sm font-bold mt-3 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-600 font-semibold mt-1 mb-4">₱{{ number_format($product->price, 2) }}</p>
                    
                    {{-- ADD TO CART FORM --}}
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-auto">
                        @csrf
                        <button type="submit" class="w-full py-2 bg-[#3a86c8] text-white text-xs font-black uppercase rounded-lg hover:bg-blue-700 transition">
                            Add to Cart
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endforeach

<div class="flex flex-col items-center justify-center gap-2 mb-10 mt-12">
    {{-- Header Text --}}
    <div class="text-gray-900 font-black uppercase tracking-[0.2em] text-[10px]">
        Certificates & Honors
    </div>
    
    {{-- Decorative Underline --}}
    <div class="h-0.5 w-16 bg-gradient-to-r from-transparent via-amber-500 to-transparent rounded-full"></div>
</div>

{{-- Content: Removed full-width logic; added max-w-fit to prevent stretching --}}
<div class="flex flex-wrap justify-center gap-8 mx-auto max-w-fit">
    @php
        $webstoreCertificates = $storeConfig->webstore_certificates_data ?? [];

        if (is_string($webstoreCertificates)) {
            $webstoreCertificates = json_decode($webstoreCertificates, true) ?? [];
        }

        if (!is_array($webstoreCertificates)) {
            $webstoreCertificates = [];
        }
    @endphp

    @if(!empty($webstoreCertificates))
        @foreach($webstoreCertificates as $cert)
            {{-- Floating Glass Card --}}
            <div class="h-72 w-[500px] flex items-center justify-center overflow-hidden rounded-2xl shadow-lg border border-white/50 bg-white/50 backdrop-blur-sm transition-all duration-500 hover:border-amber-400 hover:shadow-amber-500/20 hover:scale-105">
                <img src="{{ asset('storage/' . $cert) }}" 
                     class="w-full h-full object-cover" 
                     alt="Certificate">
            </div>
        @endforeach
    @else
        <div class="text-[11px] font-black text-gray-300 uppercase tracking-[0.2em] py-12">
            No custom trust honors linked yet.
        </div>
    @endif
</div>
                </div>
            </div>


    </div>
</div>

{{-- 8. Banner Core Automatic Sliders Interactive Control Script --}}
<script>
    let currentSlideIndex = 0;
    const slides = document.querySelectorAll('.webstore-slide');
    const bullets = document.querySelectorAll('.carousel-bullet');
    const totalSlides = slides.length;

    function navigateToSlide(index) {
        if (totalSlides <= 1) return;
        
        // Boundaries sanitization check
        if (index >= totalSlides) currentSlideIndex = 0;
        else if (index < 0) currentSlideIndex = totalSlides - 1;
        else currentSlideIndex = index;

        slides.forEach((slide, i) => {
            if (i === currentSlideIndex) {
                slide.classList.remove('opacity-0', 'z-0');
                slide.classList.add('opacity-100', 'z-10');
            } else {
                slide.classList.remove('opacity-100', 'z-10');
                slide.classList.add('opacity-0', 'z-0');
            }
        });

        bullets.forEach((bullet, i) => {
            if (i === currentSlideIndex) {
                bullet.classList.add('opacity-100', 'scale-110');
                bullet.classList.remove('opacity-40');
            } else {
                bullet.classList.remove('opacity-100', 'scale-110');
                bullet.classList.add('opacity-40');
            }
        });
    }

    // Launch background interval ticks if multi-assets are available
    if (totalSlides > 1) {
        setInterval(() => {
            navigateToSlide(currentSlideIndex + 1);
        }, 5000); // 5 Seconds Rotation Tick Timer
    }
</script>
@endsection