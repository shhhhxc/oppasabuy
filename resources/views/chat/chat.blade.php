@extends('layouts.app')

@section('content')
<div class="bg-[#f6f8fb] min-h-screen flex flex-col lg:flex-row">
    
    {{-- Sidebar: Orders & Rooms --}}
    <aside class="w-full lg:w-80 bg-white border-r border-gray-100 p-6 flex flex-col overflow-y-auto h-screen lg:sticky lg:top-0">
        
        {{-- SECTION: SOCIAL ROOMS (Group Chats) --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Group Chats</h3>
                <button onclick="toggleModal('groupModal')" class="bg-blue-50 text-[#0d47a1] p-2 rounded-lg hover:bg-blue-100 transition shadow-sm">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>

            <div class="space-y-2">
                @foreach($userRooms as $uRoom)
                    <a href="{{ route('rooms.show', $uRoom->id) }}" 
                       class="flex items-center gap-3 p-3 rounded-2xl border transition-all no-underline {{ isset($room) && $room->id == $uRoom->id ? 'bg-blue-50 border-blue-100' : 'bg-gray-50 border-transparent hover:bg-white hover:shadow-sm' }}">
                        
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white text-xs font-black shadow-sm">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <div class="overflow-hidden">
                            <p class="text-sm font-black text-gray-900 truncate">
                                {{ $uRoom->name }}
                            </p>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">
                                {{ $uRoom->users_count ?? $uRoom->users->count() }} Members
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- SECTION: TRANSACTIONAL ORDERS --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Orders</h3>
            </div>

            <div class="space-y-2">
                @foreach($userOrders as $uOrder)
                    @php
                        $isSellerSide = Auth::id() == $uOrder->seller_id;
                        $conversationName = $uOrder->custom_name;

                        if (empty($conversationName)) {
                            if ($isSellerSide) {
                                $conversationName = $uOrder->buyer->name ?? 'Buyer';
                            } else {
                                $conversationName = $uOrder->seller->sellerVerification->store_name ?? $uOrder->seller->name ?? 'Seller';
                            }
                        }

                        $initials = collect(explode(' ', $conversationName))
                            ->filter()
                            ->take(2)
                            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                            ->implode('');

                        if (!$initials) {
                            $initials = '#' . $uOrder->id;
                        }
                    @endphp

                    <a href="{{ route('chat.order', $uOrder->id) }}" 
                       class="flex items-center gap-3 p-3 rounded-2xl border transition-all no-underline {{ isset($order) && $order->id == $uOrder->id ? 'bg-blue-50 border-blue-100' : 'bg-gray-50 border-transparent hover:bg-white hover:shadow-sm' }}">
                        
                        <div class="w-10 h-10 bg-[#0d47a1] rounded-xl flex items-center justify-center text-white text-xs font-black shadow-sm">
                            {{ $initials }}
                        </div>

                        <div class="overflow-hidden">
                            <p class="text-sm font-black text-gray-900 truncate">
                                {{ $conversationName }}
                            </p>

                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">
                                #{{ $uOrder->id }} • {{ str_replace('_', ' ', $uOrder->status) }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Transaction specific items (Only shows if an Order is selected) --}}
        @if(isset($order))
        <div class="mb-8 border-t border-gray-100 pt-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Order Items</h3>
                <span class="bg-blue-50 text-[#0d47a1] text-[10px] font-black px-2 py-1 rounded-md">
                    {{ $order->items->count() }} TOTAL
                </span>
            </div>
            
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-100 group hover:bg-white hover:shadow-sm transition-all">
                        <div class="w-12 h-12 flex-shrink-0">
                            <img src="{{ asset('storage/' . str_replace('public/', '', $item->product->image_path)) }}" 
                                 class="w-full h-full rounded-xl object-cover shadow-sm"
                                 onerror="this.onerror=null; this.src='https://placehold.co/100x100?text=Item'">
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-black text-gray-900 truncate leading-tight">{{ $item->product->name }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">₱{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 p-5 bg-gradient-to-br from-blue-600 to-[#0d47a1] rounded-[2rem] text-white shadow-xl shadow-blue-100">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-shield-lock-fill text-xl"></i>
                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-100">Escrow Active</p>
                </div>
                <p class="text-[11px] font-bold leading-relaxed opacity-90">
                    Payment is only released after you approve the seller's video proof.
                </p>
            </div>
        </div>
        @endif
    </aside>

    {{-- Main Chat Area --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        @php
            $isRoom = isset($room);
            $isOrder = isset($order);
            $activeChat = $isRoom ? $room : ($isOrder ? $order : null);
            
            if ($isRoom) {
                $chatTitle = $room->name;
                $sendRoute = route('rooms.send', $room->id);
            } elseif ($isOrder) {
                if (!empty($order->custom_name)) {
                    $chatTitle = $order->custom_name;
                } elseif (Auth::id() == $order->seller_id) {
                    $chatTitle = $order->buyer->name ?? "Order #{$order->id}";
                } else {
                    $chatTitle = $order->seller->sellerVerification->store_name ?? $order->seller->name ?? "Order #{$order->id}";
                }

                $sendRoute = route('chat.order.send', $order->id);
            } else {
                $chatTitle = null;
                $sendRoute = '#';
            }
        @endphp

        @if($activeChat)
            <header class="bg-white border-b border-gray-100 p-6 sticky top-0 z-10 shadow-sm">
                <div class="max-w-4xl mx-auto flex flex-col md:flex-row justify-between items-center w-full gap-4">
                    <div class="flex items-center space-x-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-xl font-black text-gray-900">
                                    {{ $chatTitle }}
                                </h2>
                                {{-- Rename Button (Creator or Seller) --}}
                                @if(($isRoom && Auth::id() === $room->creator_id) || ($isOrder && Auth::id() === $order->seller_id))
                                <button onclick="toggleModal('renameModal')" class="text-gray-400 hover:text-blue-600 transition">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $isRoom ? 'bg-indigo-500' : 'bg-green-500' }} animate-pulse"></span>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    {{ $isRoom ? 'Group Chat Room' : 'Order Transaction Log' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Transaction Buttons (Only for Orders) --}}
                    @if($isOrder)
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        @if(Auth::id() === $order->buyer_id && $order->status === 'video_approved')
                            <p class="text-[9px] font-black text-gray-400 uppercase mr-1">Select Payment:</p>
                            <form action="{{ route('chat.order.status', $order->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="awaiting_payment_qr">
                                <input type="hidden" name="payment_method" value="GCash">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl font-black text-[10px] hover:bg-black transition shadow-md">GCASH</button>
                            </form>
                            <form action="{{ route('chat.order.status', $order->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="awaiting_payment_qr">
                                <input type="hidden" name="payment_method" value="Maya">
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-xl font-black text-[10px] hover:bg-black transition shadow-md">MAYA</button>
                            </form>
                        @elseif(Auth::id() === $order->buyer_id && $order->status === 'video_uploaded')
                            <div class="bg-orange-100 text-orange-600 px-4 py-2 rounded-xl font-black text-[10px] animate-pulse">
                                WATCH VIDEO BELOW TO APPROVE
                            </div>
                        @endif

                        @if(Auth::id() === $order->seller_id && $order->status === 'receipt_uploaded')
                            <form action="{{ route('chat.order.complete', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-black text-white px-5 py-2 rounded-xl font-black text-[10px] shadow-lg animate-bounce" onclick="return confirm('Confirm payment received?')">
                                    CONFIRM PAYMENT
                                </button>
                            </form>
                        @endif
                    </div>
                    @endif
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-6 space-y-8 bg-white/50" id="chat-container">
                @foreach($messages as $msg)
                    @php
                        $isMe = $msg->user_id === Auth::id();
                        $sender = $msg->user; 
                        
                        $displayName = $sender->name;
                        $displayPhoto = null;

                        if ($isOrder && $msg->user_id === $order->seller_id) {
                            $verification = $sender->sellerVerification; 
                            if ($verification) {
                                $displayName = $verification->store_name;
                                $displayPhoto = asset('storage/' . $verification->logo_path);
                            }
                        } 
                        
                        if (!$displayPhoto) {
                            $displayPhoto = $sender->profile_picture ? asset('storage/' . $sender->profile_picture) : $sender->profile_photo_url;
                        }

                        if (!$displayPhoto || $displayPhoto == '') {
                            $displayPhoto = 'https://ui-avatars.com/api/?name='.urlencode($displayName).'&background=0d47a1&color=fff';
                        }
                    @endphp

                    <div class="flex items-end gap-3 {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                        <div class="flex-shrink-0 mb-1">
                            <img src="{{ $displayPhoto }}" 
                                 alt="{{ $displayName }}" 
                                 class="w-10 h-10 rounded-full object-cover border-2 {{ $isMe ? 'border-blue-400' : 'border-white shadow-md' }}"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($displayName) }}'">
                        </div>

                        <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }} max-w-[75%] lg:max-w-md">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 px-2">
                                {{ $isMe ? 'You' : $displayName }}
                            </span>

                            <div class="{{ $isMe ? 'bg-[#0d47a1] text-white' : 'bg-white text-gray-800 border border-gray-100 shadow-sm' }} p-4 rounded-[2rem] {{ $isMe ? 'rounded-br-none' : 'rounded-bl-none' }} shadow-lg relative">
                                
                                @if($msg->message)
                                    <p class="text-sm font-medium leading-relaxed">{{ $msg->message }}</p>
                                @endif

                                @if($msg->video_path)
                                    <div class="mt-3 rounded-[1.5rem] overflow-hidden border-2 {{ $isOrder && $order->status === 'video_approved' ? 'border-green-400' : 'border-orange-300' }} bg-black relative group">
                                        <video width="100%" controls class="block aspect-video">
                                            <source src="{{ asset('storage/' . str_replace('public/', '', $msg->video_path)) }}" type="video/mp4">
                                        </video>
                                    </div>
                                    @if($isOrder && Auth::id() === $order->buyer_id && $order->status === 'video_uploaded')
                                        <div class="mt-4 flex gap-2">
                                            <form action="{{ route('chat.order.status', $order->id) }}" method="POST" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="status" value="video_approved">
                                                <button type="submit" class="w-full bg-green-500 text-white text-[10px] font-black py-2 rounded-xl hover:bg-green-600 transition shadow-sm">APPROVE</button>
                                            </form>
                                            <form action="{{ route('chat.order.status', $order->id) }}" method="POST" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="status" value="awaiting_video">
                                                <button type="submit" class="w-full bg-red-50 text-red-500 text-[10px] font-black py-2 rounded-xl hover:bg-red-100 transition">REJECT</button>
                                            </form>
                                        </div>
                                    @endif
                                @endif

                                @if($msg->image_path)
                                    <div class="mt-3 rounded-[1.5rem] overflow-hidden border border-gray-100 bg-white shadow-sm">
                                        <a href="{{ asset('storage/' . str_replace('public/', '', $msg->image_path)) }}" target="_blank">
                                            <img src="{{ asset('storage/' . str_replace('public/', '', $msg->image_path)) }}" 
                                                 class="w-full h-auto max-h-72 object-cover transition-transform hover:scale-105">
                                        </a>
                                    </div>
                                @endif

                                <span class="block text-[9px] mt-2 opacity-40 font-black text-right uppercase tracking-tighter">
                                    {{ $msg->created_at->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <footer class="bg-white border-t border-gray-100 p-6">
                <div class="max-w-4xl mx-auto">
                    <form action="{{ $sendRoute }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-4">
                        @csrf
                        <div class="flex-1 bg-gray-50 rounded-[2.5rem] p-2 flex items-center border border-gray-100 focus-within:border-[#0d47a1] focus-within:bg-white transition-all shadow-inner">
                            
                            <div class="flex items-center px-2">
                                <label class="cursor-pointer p-2 text-gray-400 hover:text-[#0d47a1] transition-colors" title="Upload Video">
                                    <i class="bi bi-camera-video-fill text-2xl"></i>
                                    <input type="file" name="attachment" id="chat-video" class="hidden" accept="video/*" onchange="previewFileName(this)">
                                </label>
                                <label class="cursor-pointer p-2 text-gray-400 hover:text-[#0d47a1] transition-colors border-l border-gray-200" title="Upload Image">
                                    <i class="bi bi-image-fill text-2xl"></i>
                                    <input type="file" name="image" id="chat-img" class="hidden" accept="image/*" onchange="previewFileName(this)">
                                </label>
                            </div>

                            <input type="text" name="message" autocomplete="off" placeholder="Type a message..." 
                                   class="bg-transparent border-0 flex-1 px-4 py-2 text-sm font-bold focus:ring-0 text-gray-700">
                        </div>

                        <button type="submit" class="bg-[#0d47a1] w-14 h-14 rounded-full flex items-center justify-center text-white shadow-xl hover:scale-105 transition-all active:scale-95 border-0">
                            <i class="bi bi-send-fill text-xl"></i>
                        </button>
                    </form>
                    
                    {{-- File Preview UI --}}
                    <div id="file-name-preview" class="hidden bg-blue-50 p-4 rounded-2xl mt-4 items-center justify-between border border-blue-100 animate-fade-in shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#0d47a1] rounded-lg flex items-center justify-center text-white overflow-hidden shadow-sm">
                                <i id="file-icon" class="bi bi-file-earmark-arrow-up text-xl"></i>
                                <img id="img-preview" class="hidden w-full h-full object-cover">
                            </div>
                            <div>
                                <p id="file-name-text" class="text-[10px] font-black text-[#0d47a1] uppercase tracking-widest truncate max-w-[200px]"></p>
                                <p class="text-[8px] font-bold text-blue-400 uppercase">Ready to send</p>
                            </div>
                        </div>
                        <button type="button" onclick="cancelFile()" class="text-gray-400 hover:text-red-500 transition-colors">
                            <i class="bi bi-x-circle-fill text-2xl"></i>
                        </button>
                    </div>
                </div>
            </footer>
        @else
            <div class="flex-1 flex flex-col items-center justify-center h-full text-center bg-white">
                <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6 border border-blue-100 shadow-sm">
                    <i class="bi bi-chat-right-text text-4xl text-[#0d47a1]"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900">Your Communication Hub</h3>
                <p class="text-sm text-gray-400 font-bold mt-2">Select a transaction or a group chat room to start messaging.</p>
            </div>
        @endif
    </div>
</div>

{{-- MODAL: Create Group Chat (Social) --}}
<div id="groupModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-md overflow-hidden shadow-2xl animate-fade-in">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900">New Group Room</h3>
                <button type="button" onclick="toggleModal('groupModal')" class="text-gray-400 hover:text-red-500 transition"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Room Name</label>
                        <input type="text" name="name" required placeholder="e.g. Suppliers Discussion" class="w-full bg-gray-50 border-gray-100 rounded-xl font-bold text-sm focus:ring-[#0d47a1] focus:border-[#0d47a1]">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Add Members</label>
                        <div class="max-h-48 overflow-y-auto border border-gray-100 rounded-xl p-2 space-y-2">
                            @foreach($availableUsers as $user)
                            <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                                <input type="checkbox" name="members[]" value="{{ $user->id }}" class="rounded text-[#0d47a1] focus:ring-[#0d47a1]">
                                <span class="text-sm font-bold text-gray-700">{{ $user->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#0d47a1] text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg transition-all">Create Social Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: Rename Room (Works for both Room and Order) --}}
@if($activeChat)
<div id="renameModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-md overflow-hidden shadow-2xl animate-fade-in">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900">Rename Room</h3>
                <button type="button" onclick="toggleModal('renameModal')" class="text-gray-400 hover:text-red-500 transition"><i class="bi bi-x-lg"></i></button>
            </div>
            @php
                $renameRoute = $isRoom ? route('rooms.rename', $room->id) : route('chat.order.rename', $order->id);
                $oldName = $isRoom ? $room->name : $order->custom_name;
            @endphp
            <form action="{{ $renameRoute }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="{{ $isRoom ? 'name' : 'custom_name' }}" value="{{ $oldName }}" class="w-full bg-gray-50 border-gray-100 rounded-xl font-bold text-sm focus:ring-[#0d47a1] focus:border-[#0d47a1]">
                    <button type="submit" class="w-full bg-black text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">Update Title</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if(modal) modal.classList.toggle('hidden');
    }

    function previewFileName(input) {
        const preview = document.getElementById('file-name-preview');
        const text = document.getElementById('file-name-text');
        const icon = document.getElementById('file-icon');
        const img = document.getElementById('img-preview');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            text.innerText = file.name;
            preview.classList.remove('hidden');
            preview.classList.add('flex');

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => { 
                    img.src = e.target.result; 
                    img.classList.remove('hidden'); 
                    icon.classList.add('hidden'); 
                }
                reader.readAsDataURL(file);
            } else {
                img.classList.add('hidden'); 
                icon.classList.remove('hidden'); 
                icon.className = 'bi bi-play-fill text-2xl';
            }
        }
    }

    function cancelFile() {
        document.getElementById('file-name-preview').classList.add('hidden');
        document.getElementById('file-name-preview').classList.remove('flex');
        document.getElementById('chat-video').value = "";
        document.getElementById('chat-img').value = "";
    }

    const chatBox = document.getElementById('chat-container');
    if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
</script>

<style>
    @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    #chat-container::-webkit-scrollbar { width: 5px; }
    #chat-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    aside::-webkit-scrollbar { width: 0px; }
</style>
@endsection