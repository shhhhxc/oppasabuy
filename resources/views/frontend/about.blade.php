@extends('layouts.app')

@section('content')
<div class="bg-[#f1f5f9] min-h-screen pb-20 font-sans">
    
    <!-- PREMIUM HEADER (Matches image_3efddc.png style) -->
    <div class="bg-[#163d78] pt-20 pb-36 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#9e1b18] rounded-full translate-x-1/3 translate-y-1/3"></div>
        </div>

        <div class="relative z-10">
            <div class="inline-block bg-[#9e1b18] px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl mb-6 border border-white/20">
                OUR STORY
            </div>
            <h1 class="text-5xl md:text-6xl font-black tracking-tighter mb-3 drop-shadow-md">
                About <span class="text-blue-200">Oppasabuy</span>
            </h1>
            <div class="flex items-center justify-center gap-3">
                <span class="h-[1px] w-8 bg-blue-300/50"></span>
                <p class="text-blue-100 uppercase tracking-[0.4em] text-[11px] font-black opacity-90">
                    Driven by Passion, Built on Trust
                </p>
                <span class="h-[1px] w-8 bg-blue-300/50"></span>
            </div>
        </div>
    </div>

    <!-- MISSION & VISION SECTION -->
    <div class="max-w-7xl mx-auto px-6 -mt-24 relative z-20">
        <div class="bg-white rounded-[3rem] p-8 md:p-16 shadow-xl border border-gray-100 mb-16">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-black text-[#163d78] mb-6">Bridging Borders with <span class="text-[#9e1b18]">Authenticity</span></h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Oppasabuy started with a simple problem: the difficulty of finding genuine Korean products and services without the hassle of complicated shipping and unverified sources. We built this marketplace to empower local sellers and provide buyers with a "Safe Shopping" environment.
                    </p>
                    <div class="flex gap-4">
                        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                            <h4 class="font-black text-[#163d78] text-sm uppercase mb-1">Our Mission</h4>
                            <p class="text-xs text-gray-500">To be the #1 trusted bridge for K-culture enthusiasts in the Philippines.</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-2xl border border-red-100">
                            <h4 class="font-black text-[#9e1b18] text-sm uppercase mb-1">Our Vision</h4>
                            <p class="text-xs text-gray-500">Creating a borderless community of verified sellers and happy buyers.</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-video rounded-[2.5rem] bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden shadow-2xl flex items-center justify-center border border-gray-100">
                        <!-- SVG Placeholder for Brand Story -->
                        <div class="text-center group">
                            <div class="w-20 h-20 bg-white rounded-3xl shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-500">
                                <i class="bi bi-shop-window text-4xl text-[#163d78]"></i>
                            </div>
                            <p class="text-[#163d78] font-black text-[10px] uppercase tracking-widest opacity-40">Oppasabuy Heritage</p>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-[#163d78] text-white p-6 rounded-3xl shadow-xl hidden md:block">
                        <p class="text-3xl font-black italic">"Safe & Simple."</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- TEAM SECTION -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-black text-gray-900 mb-2">The <span class="text-[#9e1b18]">Leadership</span> Team</h2>
            <p class="text-gray-500 uppercase tracking-widest text-[10px] font-black">The Minds Behind the Marketplace</p>
        </div>

        <div class="grid md:grid-cols-2 gap-10 max-w-4xl mx-auto">
            @foreach($team as $member)
            <div class="group bg-white rounded-[2.5rem] p-4 shadow-sm border border-gray-100 hover:shadow-2xl transition-all duration-500">
                <div class="relative aspect-[4/5] overflow-hidden rounded-[2rem] mb-6 bg-gray-50">
                    @if(file_exists(public_path('storage/team/' . $member['image'])) && $member['image'])
                        <img src="{{ asset('storage/team/' . $member['image']) }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-700" 
                             alt="{{ $member['name'] }}">
                    @else
                        <!-- Avatar Placeholder if image is missing -->
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                            <i class="bi bi-person-bounding-box text-6xl mb-2"></i>
                            <span class="text-[10px] font-black uppercase tracking-tighter">Photo Pending</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-[#163d78]/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-8">
                        <p class="text-white text-sm italic">"{{ $member['bio'] }}"</p>
                    </div>
                </div>
                <div class="text-center pb-4">
                    <h3 class="text-2xl font-black text-gray-900">{{ $member['name'] }}</h3>
                    <p class="text-[#9e1b18] font-black text-xs uppercase tracking-widest">{{ $member['role'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- PROBLEM SOLVING / SUPPORT SECTION -->
        <div class="mt-24 bg-[#163d78] rounded-[3rem] p-10 md:p-16 text-center text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-3xl font-black mb-4">Facing any issues?</h2>
                <p class="text-blue-100 mb-10 max-w-2xl mx-auto opacity-80">
                    Our team is here to ensure your experience is smooth. Whether it's a technical bug, a seller dispute, or general inquiries, we've got your back.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('contact') }}" class="px-8 py-4 bg-[#9e1b18] rounded-2xl font-black uppercase tracking-widest text-sm hover:scale-105 transition-all">
                        Report a Problem
                    </a>
                    <a href="/faq" class="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-white/20 transition-all">
                        Visit Help Center
                    </a>
                </div>
            </div>
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full translate-x-1/2 -translate-y-1/2"></div>
        </div>
    </div>
</div>
@endsection