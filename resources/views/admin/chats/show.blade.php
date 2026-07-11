@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('admin.chats') }}" class="flex items-center text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-[#A31D1D] transition">
            <i class="bi bi-arrow-left mr-2"></i> Back to Logs
        </a>
        <div class="text-right">
            <span class="block text-[10px] font-black text-[#A31D1D] uppercase">Audit Mode</span>
            <span class="block text-sm font-black text-gray-900">Order #{{ $order->id }}</span>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col h-[70vh]">
        <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="text-sm font-black text-gray-900">
                    {{ $order->buyer->name }} <span class="text-gray-300 font-normal mx-2">→</span> {{ $order->seller->name }}
                </div>
            </div>
            <span class="text-[9px] font-black bg-green-50 text-green-600 px-3 py-1 rounded-full uppercase tracking-tighter">
                Transcript Verified
            </span>
        </div>

        <div class="flex-1 overflow-y-auto p-8 space-y-6 bg-[#FDFDFD]">
            @foreach($messages as $msg)
                <div class="flex {{ $msg->user_id == $order->buyer_id ? 'justify-start' : 'justify-end' }}">
                    <div class="max-w-[70%]">
                        <div class="flex items-center mb-1 space-x-2 {{ $msg->user_id == $order->buyer_id ? '' : 'flex-row-reverse space-x-reverse' }}">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-tight">{{ $msg->user->name }}</span>
                            <span class="text-[9px] text-gray-300">{{ $msg->created_at->format('h:i A') }}</span>
                        </div>
                        
                        <div class="p-4 rounded-2xl text-sm font-semibold {{ $msg->user_id == $order->buyer_id ? 'bg-white border border-gray-100 text-gray-700 shadow-sm' : 'bg-gray-900 text-white' }}">
                            {{ $msg->message }}

                            @if($msg->image_path)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $msg->image_path) }}" class="rounded-lg max-h-60 w-full object-cover border border-gray-100">
                                </div>
                            @endif

                            @if($msg->video_path)
                                <div class="mt-3">
                                    <video controls class="rounded-lg max-h-60 w-full bg-black">
                                        <source src="{{ asset('storage/' . $msg->video_path) }}" type="video/mp4">
                                    </video>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="p-6 border-t border-gray-50 bg-gray-50/30 text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                End of Transcript — Oppasabuy Audit System
            </p>
        </div>
    </div>
</div>
@endsection