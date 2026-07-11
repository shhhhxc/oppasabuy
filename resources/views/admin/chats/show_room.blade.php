@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('admin.chats') }}" class="flex items-center text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-[#A31D1D] transition">
            <i class="bi bi-arrow-left mr-2"></i> Back to Logs
        </a>
        <div class="text-right">
            <span class="block text-[10px] font-black text-[#A31D1D] uppercase">Group Audit</span>
            <span class="block text-sm font-black text-gray-900">{{ $room->name }}</span>
        </div>
    </div>

    {{-- Chat Container --}}
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col h-[70vh]">
        
        {{-- Room Info Bar --}}
        <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="text-sm font-black text-gray-900">
                    <span class="text-gray-400 font-normal uppercase text-[10px] tracking-widest mr-2">Created By:</span> 
                    {{ $room->creator->name ?? 'System' }}
                </div>
            </div>
            <span class="text-[9px] font-black bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full uppercase tracking-tighter">
                Group Transcript
            </span>
        </div>

        {{-- Messages Feed --}}
        <div class="flex-1 overflow-y-auto p-8 space-y-6 bg-[#FDFDFD]">
            @foreach($messages as $msg)
                {{-- Align messages: Creator on the right, members on the left --}}
                <div class="flex {{ $msg->user_id == $room->creator_id ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%]">
                        {{-- Meta Info --}}
                        <div class="flex items-center mb-1 space-x-2 {{ $msg->user_id == $room->creator_id ? 'flex-row-reverse space-x-reverse' : '' }}">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-tight">{{ $msg->user->name }}</span>
                            <span class="text-[9px] text-gray-300">{{ $msg->created_at->format('h:i A') }}</span>
                        </div>
                        
                        {{-- Message Bubble --}}
                        <div class="p-4 rounded-2xl text-sm font-semibold {{ $msg->user_id == $room->creator_id ? 'bg-gray-900 text-white' : 'bg-white border border-gray-100 text-gray-700 shadow-sm' }}">
                            {{ $msg->message }}

                            {{-- Media Support --}}
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

        {{-- Footer --}}
        <div class="p-6 border-t border-gray-50 bg-gray-50/30 text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                End of Room Transcript — Oppasabuy Audit System
            </p>
        </div>
    </div>
</div>
@endsection