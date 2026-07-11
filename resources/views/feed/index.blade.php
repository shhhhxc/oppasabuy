@extends('layouts.app')

@section('content')
{{-- Unique Oppasabuy Glass Header with Integrated Bento Creator --}}
<div class="bg-[#f0f2f5] min-h-screen pb-24 font-sans antialiased">
    
    <div class="bg-white/80 backdrop-blur-xl sticky top-0 z-40 border-b border-gray-100 shadow-sm">
        <div class="max-w-6xl mx-auto px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-10">
                {{-- Dynamic Typography Header --}}
                <div class="md:col-span-6 flex flex-col items-center md:items-start text-center md:text-left">
                    <h1 class="text-6xl font-[1000] text-[#163d78] tracking-tighter leading-none mb-1">
                        COMMUNITY <span class="relative inline-block text-[#9e1b18] hover:skew-y-1 transition-transform">
                            FEED
                            {{-- Unique stylized "live" indicator --}}
                            <span class="absolute -top-1 -right-4 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#9e1b18] opacity-60"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-[#9e1b18]"></span>
                            </span>
                        </span>
                    </h1>
                    <p class="text-gray-400 font-bold uppercase text-[11px] tracking-[0.3em] ml-1 mt-2">
                        Official Updates from Verified Oppasabuy Sellers
                    </p>
                </div>

                {{-- Unique Bento-Styled Quick Creator --}}
                <div class="md:col-span-6">
                    <div class="bg-[#f8fafc] rounded-full p-2 flex items-center shadow-inner border border-gray-100 hover:shadow-lg transition-all group cursor-pointer" 
                         data-bs-toggle="modal" data-bs-target="#createPostModal">
                        <div class="h-10 w-10 rounded-full bg-[#163d78] flex items-center justify-center text-white font-black italic shadow-md ml-1">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <input type="text" 
                               readonly
                               class="flex-1 bg-transparent border-none text-gray-700 placeholder-gray-400 px-5 text-sm font-semibold outline-none cursor-pointer"
                               placeholder="What's happening in your shop, {{ auth()->user()->name }}?">
                        <button class="w-10 h-10 rounded-full flex items-center justify-center text-gray-400 group-hover:text-[#9e1b18] transition">
                            <i class="bi bi-camera-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Feed Area --}}
    <div class="max-w-4xl mx-auto px-6 pt-12">
        <div class="space-y-12">
            @forelse($posts as $post)
                @php
                    $postSeller = \DB::table('seller_verifications')->where('user_id', $post->user_id)->first();
                    $postDisplayName = ($postSeller && $postSeller->store_name) ? $postSeller->store_name : $post->user->name;
                    // FIX: Always use the User ID for routing to the store
                    $sellerId = $post->user_id; 
                @endphp

                {{-- Unique Bento Post Block with Aggressive Radius --}}
                <article class="bg-white rounded-[3.5rem] shadow-[0_25px_60px_-15px_rgba(22,61,120,0.08)] transition-all duration-700 hover:shadow-[0_40px_80px_-15px_rgba(158,27,24,0.12)] hover:-translate-y-1 group">
                    
                    {{-- Unique Signature Header --}}
                    <div class="relative pt-10 px-10 pb-6 flex items-start justify-between gap-5">
                        <div class="flex items-start gap-5">
                            <div class="absolute -top-6 left-10 h-20 w-20 rounded-3xl border-[6px] border-[#f0f2f5] p-1.5 bg-white shadow-xl overflow-hidden group-hover:scale-105 transition-transform">
                                @if($postSeller && $postSeller->logo_path)
                                    <img src="{{ asset('storage/' . $postSeller->logo_path) }}" class="h-full w-full rounded-xl object-cover">
                                @elseif($post->user->profile_picture)
                                    <img src="{{ asset('storage/' . $post->user->profile_picture) }}" class="h-full w-full rounded-xl object-cover">
                                @else
                                    <div class="h-full w-full rounded-xl bg-gradient-to-br from-[#163d78] to-[#0d264d] flex items-center justify-center text-xl font-black text-white italic">
                                        {{ substr($postDisplayName, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="w-20"></div>

                            <div>
                                <h3 class="text-xl font-[1000] text-[#163d78] leading-tight flex items-center gap-2">
                                    {{ $postDisplayName }}
                                    @if($postSeller)
                                        <i class="bi bi-patch-check-fill text-blue-500 text-base"></i>
                                    @endif
                                </h3>
                                <p class="text-[11px] text-[#9e1b18] font-bold uppercase tracking-[0.2em] mt-1">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Fixed Store Link: Uses user_id --}}
                        @if($postSeller)
                        <div class="hidden md:block">
                            <a href="{{ route('seller-store', $sellerId) }}" 
                               class="py-3 px-6 bg-gray-900 hover:bg-[#9e1b18] text-white text-[10px] font-black uppercase tracking-widest rounded-2xl transition-all shadow-lg no-underline inline-block">
                                View Store Page
                            </a>
                        </div>
                        @endif
                    </div>

                    {{-- Post Content --}}
                    <div class="px-10 pb-8">
                        <p class="text-[#163d78] text-2xl leading-snug font-semibold transition-colors group-hover:text-black">
                            {{ $post->content }}
                        </p>
                    </div>

                    {{-- Post Image --}}
                    @if($post->image)
                    <div class="p-3">
                        <div class="rounded-[2.8rem] overflow-hidden bg-gray-50 border border-gray-100 group-hover:border-[#9e1b18]/10 cursor-pointer transition-colors" 
                             data-bs-toggle="modal" data-bs-target="#postModal{{ $post->id }}">
                            <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-auto max-h-[650px] object-cover transition-transform duration-[2.5s] group-hover:scale-105">
                        </div>
                    </div>
                    @endif

                    {{-- Interaction Bento Bar --}}
                    <div class="p-6 bg-[#f8fafc] rounded-b-[3.5rem] border-t border-gray-100 flex items-center gap-4">
                        <form action="{{ route('feed.like', $post->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-12 h-12 rounded-full flex items-center justify-center transition-all bg-white hover:bg-red-50 group/like">
                                <i class="bi {{ $post->isLikedBy(auth()->user()) ? 'bi-heart-fill text-[#9e1b18]' : 'bi-heart text-gray-300' }} text-xl"></i>
                            </button>
                        </form>
                        
                        <div class="h-12 px-6 rounded-full bg-white flex items-center gap-2 border border-gray-100">
                            <span class="text-base font-black text-[#163d78]">{{ $post->likes_count ?? 0 }}</span>
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Interactions</span>
                        </div>

                        <button class="w-12 h-12 rounded-full flex items-center justify-center ml-auto bg-white hover:bg-blue-50 transition" 
                                data-bs-toggle="modal" data-bs-target="#postModal{{ $post->id }}">
                            <i class="bi bi-chat-text text-gray-300 hover:text-[#163d78] text-xl"></i>
                        </button>
                    </div>
                </article>
            @empty
                <div class="text-center py-32 bg-white rounded-[4rem] shadow-sm border-2 border-dashed border-gray-100 relative overflow-hidden">
                    <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-gray-50 rounded-full opacity-60"></div>
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8 relative">
                        <i class="bi bi-journal-richtext text-5xl text-gray-200"></i>
                    </div>
                    <h2 class="text-[#163d78] font-[1000] text-3xl tracking-tight relative">Silence is Golden...</h2>
                    <p class="text-gray-400 font-bold uppercase tracking-[0.3em] text-[10px] mt-3 relative">...but new stories are better. Verified sellers, share your update!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- MODALS FOR EACH POST --}}
@foreach($posts as $post)
@php
    $postSeller = \DB::table('seller_verifications')->where('user_id', $post->user_id)->first();
    $postDisplayName = ($postSeller && $postSeller->store_name) ? $postSeller->store_name : $post->user->name;
    $sellerId = $post->user_id;
@endphp
<div class="modal fade" id="postModal{{ $post->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-[3.5rem] border-0 overflow-hidden bg-white/90 backdrop-blur-xl shadow-2xl">
            <div class="modal-body p-0">
                <div class="flex flex-col md:flex-row h-full">
                    @if($post->image)
                    <div class="w-full md:w-3/5 bg-gray-50 p-4">
                        <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-full object-contain rounded-3xl">
                    </div>
                    @endif
                    <div class="w-full {{ $post->image ? 'md:w-2/5' : '' }} flex flex-col bg-white">
                        <div class="p-8 border-b flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-black text-[#163d78] uppercase tracking-tight">{{ $postDisplayName }}</span>
                                <p class="text-[9px] text-[#9e1b18] font-bold uppercase tracking-widest">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        
                        <div class="p-8 flex-1 overflow-y-auto max-h-[500px]">
                            <p class="text-gray-800 mb-8 text-xl font-semibold italic leading-relaxed">"{{ $post->content }}"</p>
                            
                            {{-- Fixed Modal Store Link: Uses user_id --}}
                            @if($postSeller)
                            <div class="mb-8">
                                <a href="{{ route('seller-store', $sellerId) }}" 
                                   class="block w-full py-4 bg-gray-900 hover:bg-[#9e1b18] text-white text-center font-bold rounded-2xl transition-all shadow-lg no-underline uppercase tracking-widest text-[11px]">
                                    Visit {{ $postSeller->store_name }}
                                </a>
                            </div>
                            @endif

                            <div class="space-y-6">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Comments Transcript</h4>
                                @forelse($post->comments as $comment)
                                    @php
                                        $commentSeller = \DB::table('seller_verifications')->where('user_id', $comment->user_id)->first();
                                        $commentName = ($commentSeller && $commentSeller->store_name) ? $commentSeller->store_name : $comment->user->name;
                                        $commentSellerId = $comment->user_id;
                                    @endphp
                                    <div class="flex gap-3 items-start group/comment">
                                        @if($commentSeller)
                                        <a href="{{ route('seller-store', $commentSellerId) }}" class="h-10 w-10 rounded-xl overflow-hidden shadow-sm flex-shrink-0 border border-gray-100 hover:opacity-80 transition-opacity">
                                            @if($commentSeller->logo_path)
                                                <img src="{{ asset('storage/' . $commentSeller->logo_path) }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full bg-[#163d78] flex items-center justify-center text-xs font-black text-white italic">
                                                    {{ substr($commentName, 0, 1) }}
                                                </div>
                                            @endif
                                        </a>
                                        @else
                                        <div class="h-10 w-10 rounded-xl overflow-hidden shadow-sm flex-shrink-0 border border-gray-100">
                                            @if($comment->user->profile_picture)
                                                <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full bg-[#163d78] flex items-center justify-center text-xs font-black text-white italic">
                                                    {{ substr($commentName, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        @endif
                                        
                                        <div class="flex-1 bg-[#f8fafc] p-4 rounded-2xl rounded-tl-none group-hover/comment:bg-gray-100 transition-colors">
                                            <div class="flex justify-between items-center mb-1">
                                                @if($commentSeller)
                                                <a href="{{ route('seller-store', $commentSellerId) }}" class="text-xs font-black text-[#163d78] flex items-center gap-1 hover:underline">
                                                    {{ $commentName }}
                                                    <i class="bi bi-patch-check-fill text-blue-500 text-[10px]"></i>
                                                </a>
                                                @else
                                                <p class="text-xs font-black text-[#163d78] flex items-center gap-1">
                                                    {{ $commentName }}
                                                </p>
                                                @endif
                                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[10px] text-gray-400 italic">No comments in transcript.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="p-8 border-t bg-gray-50/50 rounded-b-[3.5rem]">
                            <form action="{{ route('feed.comment', $post->id) }}" method="POST">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="text" name="content" class="flex-1 bg-white border border-gray-100 rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#9e1b18]" placeholder="Write an official response..." required>
                                    <button type="submit" class="bg-[#9e1b18] text-white px-5 py-3 rounded-xl text-xs font-black uppercase">Post</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- Separate Post Creator Modal --}}
<div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-[3.5rem] border-0 overflow-hidden shadow-2xl bg-white/95 backdrop-blur-lg">
            <div class="modal-body p-0">
                <div class="p-10">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-3xl font-[1000] text-[#163d78] tracking-tighter">Share an Official Store Update</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('feed.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6 relative">
                            <textarea name="content" rows="6" 
                                class="w-full bg-[#f8fafc] border border-gray-100 rounded-3xl focus:ring-4 focus:ring-[#163d78]/5 text-[#163d78] placeholder-gray-400 p-6 resize-none transition-all outline-none text-lg font-semibold" 
                                placeholder="What's new in your shop? Announce new products, share stories, or update hours..." required></textarea>
                            
                            <div class="flex items-center gap-3">
                                <label class="flex-1 flex items-center justify-center gap-3 cursor-pointer bg-[#f8fafc] hover:bg-gray-100 py-4 rounded-2xl transition-all border border-dashed border-gray-200">
                                    <i class="bi bi-image text-[#9e1b18] text-xl"></i>
                                    <span class="text-xs font-black text-gray-500 uppercase tracking-widest">Attach Media (Images)</span>
                                    <input type="file" name="image" class="hidden" accept="image/*">
                                </label>
                                
                                <button type="submit" class="bg-[#163d78] text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-[#9e1b18] transition-colors shadow-lg shadow-blue-900/20">
                                    Publish Post
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection