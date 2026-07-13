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
                                <button type="button"
                                        onclick="openChatCamera('video')"
                                        class="cursor-pointer p-2 text-gray-400 hover:text-[#0d47a1] transition-colors bg-transparent border-0"
                                        title="Record Video">
                                    <i class="bi bi-camera-video-fill text-2xl"></i>
                                </button>

                                <button type="button"
                                        onclick="openChatCamera('photo')"
                                        class="cursor-pointer p-2 text-gray-400 hover:text-[#0d47a1] transition-colors border-0 border-l border-gray-200 bg-transparent"
                                        title="Take Photo">
                                    <i class="bi bi-camera-fill text-2xl"></i>
                                </button>

                                <input type="file" name="attachment" id="chat-video" class="hidden" accept="video/*">
                                <input type="file" name="image" id="chat-img" class="hidden" accept="image/*">
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


{{-- CAMERA-ONLY CHAT MODAL --}}
<div id="chatCameraModal" class="fixed inset-0 z-[100] hidden bg-black/80 backdrop-blur-sm items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-2xl overflow-hidden shadow-2xl">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 id="chatCameraTitle" class="text-lg font-black text-gray-900">Camera</h3>
                <p id="chatCameraSubtitle" class="text-xs font-bold text-gray-400 mt-1">Select a camera and start.</p>
            </div>

            <button type="button"
                    onclick="closeChatCamera()"
                    class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 hover:bg-red-50 hover:text-red-500 border-0 transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="p-5">
            <div class="flex flex-col md:flex-row gap-3 mb-4">
                <select id="chatCameraSelect"
                        class="flex-1 rounded-xl border-gray-200 bg-gray-50 font-bold text-sm">
                    <option value="">Select camera</option>
                </select>

                <button type="button"
                        onclick="refreshChatCameras()"
                        class="px-4 py-3 rounded-xl bg-gray-100 text-gray-700 font-black text-xs border-0 hover:bg-gray-200 transition">
                    <i class="bi bi-arrow-clockwise me-1"></i> REFRESH
                </button>
            </div>

            <div class="relative bg-black rounded-[1.5rem] overflow-hidden aspect-video flex items-center justify-center">
                <div id="chatCameraPlaceholder" class="text-center text-gray-300 px-6">
                    <i class="bi bi-camera-video text-5xl block mb-3"></i>
                    <p class="text-sm font-bold">Allow camera access, select a camera, then press Start Camera.</p>
                </div>

                <video id="chatCameraVideo" autoplay playsinline muted class="hidden w-full h-full object-cover"></video>
                <img id="chatPhotoPreview" src="" alt="Captured photo" class="hidden w-full h-full object-contain bg-black">
                <video id="chatVideoPreview" controls playsinline class="hidden w-full h-full object-contain bg-black"></video>

                <div id="recordingIndicator"
                     class="hidden absolute top-4 left-4 bg-red-600 text-white px-3 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">
                    <span class="inline-block w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                    Recording <span id="recordingTimer">00:00</span>
                </div>
            </div>

            <div id="chatCameraError"
                 class="hidden mt-4 rounded-xl border border-red-200 bg-red-50 text-red-600 px-4 py-3 text-xs font-bold">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-5">
                <button type="button" id="startChatCameraBtn" onclick="startChatCamera()" class="camera-action-btn bg-[#0d47a1] text-white">
                    <i class="bi bi-camera-video-fill"></i> Start Camera
                </button>

                <button type="button" id="switchChatCameraBtn" onclick="switchChatCamera()" disabled class="camera-action-btn bg-gray-100 text-gray-700">
                    <i class="bi bi-arrow-repeat"></i> Switch Camera
                </button>

                <button type="button" id="captureChatBtn" onclick="captureChatMedia()" disabled class="camera-action-btn bg-red-600 text-white">
                    <i id="captureChatIcon" class="bi bi-camera-fill"></i>
                    <span id="captureChatLabel">Take Photo</span>
                </button>

                <button type="button" id="retakeChatBtn" onclick="retakeChatMedia()" disabled class="camera-action-btn bg-gray-100 text-gray-700">
                    <i class="bi bi-arrow-counterclockwise"></i> Retake
                </button>
            </div>

            <button type="button"
                    id="useChatMediaBtn"
                    onclick="useCapturedChatMedia()"
                    disabled
                    class="w-full mt-4 py-4 rounded-2xl bg-green-600 text-white font-black text-xs uppercase tracking-widest border-0 disabled:opacity-50">
                Use This Media
            </button>
        </div>
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
    let chatCameraStream = null;
    let chatCameraDevices = [];
    let chatCameraMode = 'photo';
    let chatActiveCameraIndex = 0;
    let chatCapturedBlob = null;
    let chatCapturedUrl = null;
    let chatMediaRecorder = null;
    let chatRecordedChunks = [];
    let chatRecordingSeconds = 0;
    let chatRecordingTimerInterval = null;

    function toggleModal(id) {
        const modal = document.getElementById(id);
        if(modal) modal.classList.toggle('hidden');
    }

    function showChatCameraError(message) {
        const box = document.getElementById('chatCameraError');
        box.textContent = message;
        box.classList.remove('hidden');
    }

    function clearChatCameraError() {
        const box = document.getElementById('chatCameraError');
        box.textContent = '';
        box.classList.add('hidden');
    }

    function stopChatCameraStream() {
        if(chatCameraStream) {
            chatCameraStream.getTracks().forEach(track => track.stop());
            chatCameraStream = null;
        }

        const video = document.getElementById('chatCameraVideo');
        video.srcObject = null;
    }

    function resetChatCapturedMedia() {
        chatCapturedBlob = null;

        if(chatCapturedUrl) {
            URL.revokeObjectURL(chatCapturedUrl);
            chatCapturedUrl = null;
        }

        const photoPreview = document.getElementById('chatPhotoPreview');
        const videoPreview = document.getElementById('chatVideoPreview');

        photoPreview.src = '';
        photoPreview.classList.add('hidden');

        videoPreview.pause();
        videoPreview.removeAttribute('src');
        videoPreview.load();
        videoPreview.classList.add('hidden');

        document.getElementById('retakeChatBtn').disabled = true;
        document.getElementById('useChatMediaBtn').disabled = true;
    }

    async function openChatCamera(mode) {
        chatCameraMode = mode;
        clearChatCameraError();
        resetChatCapturedMedia();

        const modal = document.getElementById('chatCameraModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('chatCameraTitle').textContent =
            mode === 'photo' ? 'Take a Photo' : 'Record a Video';

        document.getElementById('chatCameraSubtitle').textContent =
            mode === 'photo'
                ? 'Use your phone camera or computer webcam to take a photo.'
                : 'Use your phone camera or computer webcam to record a video.';

        document.getElementById('captureChatIcon').className =
            mode === 'photo' ? 'bi bi-camera-fill' : 'bi bi-record-circle-fill';

        document.getElementById('captureChatLabel').textContent =
            mode === 'photo' ? 'Take Photo' : 'Start Recording';

        await refreshChatCameras();
    }

    function closeChatCamera() {
        if(chatMediaRecorder && chatMediaRecorder.state === 'recording') {
            chatMediaRecorder.stop();
        }

        stopChatRecordingTimer();
        stopChatCameraStream();
        resetChatCapturedMedia();

        const modal = document.getElementById('chatCameraModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.getElementById('chatCameraVideo').classList.add('hidden');
        document.getElementById('chatCameraPlaceholder').classList.remove('hidden');
        document.getElementById('captureChatBtn').disabled = true;
        document.getElementById('retakeChatBtn').disabled = true;
        document.getElementById('useChatMediaBtn').disabled = true;
        document.getElementById('recordingIndicator').classList.add('hidden');
    }

    async function requestChatCameraPermission() {
        if(!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            throw new Error('Camera access is not supported by this browser.');
        }

        const permissionStream = await navigator.mediaDevices.getUserMedia({
            video: true,
            audio: chatCameraMode === 'video'
        });

        permissionStream.getTracks().forEach(track => track.stop());
    }

    async function refreshChatCameras() {
        clearChatCameraError();

        try {
            await requestChatCameraPermission();

            const devices = await navigator.mediaDevices.enumerateDevices();
            chatCameraDevices = devices.filter(device => device.kind === 'videoinput');

            const select = document.getElementById('chatCameraSelect');
            select.innerHTML = '';

            if(chatCameraDevices.length === 0) {
                select.innerHTML = '<option value="">No camera found</option>';
                throw new Error('No camera was detected on this device.');
            }

            chatCameraDevices.forEach((device, index) => {
                const option = document.createElement('option');
                option.value = device.deviceId;

                const label = device.label || `Camera ${index + 1}`;
                const lower = label.toLowerCase();

                if(lower.includes('front') || lower.includes('user')) {
                    option.textContent = `${label} (Front)`;
                } else if(lower.includes('rear') || lower.includes('back') || lower.includes('environment')) {
                    option.textContent = `${label} (Rear)`;
                } else {
                    option.textContent = label;
                }

                select.appendChild(option);
            });

            chatActiveCameraIndex = 0;
            select.selectedIndex = 0;
            document.getElementById('switchChatCameraBtn').disabled = chatCameraDevices.length < 2;

        } catch(error) {
            showChatCameraError(
                error.name === 'NotAllowedError'
                    ? 'Camera permission was denied. Please allow camera and microphone access in your browser settings.'
                    : error.message
            );
        }
    }

    async function startChatCamera() {
        clearChatCameraError();
        resetChatCapturedMedia();
        stopChatCameraStream();

        try {
            if(chatCameraDevices.length === 0) {
                await refreshChatCameras();
            }

            const select = document.getElementById('chatCameraSelect');
            const selectedDeviceId = select.value;

            if(!selectedDeviceId) {
                throw new Error('Please select a camera first.');
            }

            chatCameraStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    deviceId: { exact: selectedDeviceId },
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: chatCameraMode === 'video'
            });

            const video = document.getElementById('chatCameraVideo');
            video.srcObject = chatCameraStream;
            await video.play();

            document.getElementById('chatCameraPlaceholder').classList.add('hidden');
            video.classList.remove('hidden');

            chatActiveCameraIndex = Math.max(
                0,
                chatCameraDevices.findIndex(device => device.deviceId === selectedDeviceId)
            );

            document.getElementById('captureChatBtn').disabled = false;
            document.getElementById('switchChatCameraBtn').disabled = chatCameraDevices.length < 2;

        } catch(error) {
            showChatCameraError(
                error.name === 'NotAllowedError'
                    ? 'Camera permission was denied. Please allow access and try again.'
                    : `Unable to start the selected camera: ${error.message}`
            );
        }
    }

    async function switchChatCamera() {
        if(chatCameraDevices.length < 2) {
            showChatCameraError('Only one camera is available on this device.');
            return;
        }

        chatActiveCameraIndex = (chatActiveCameraIndex + 1) % chatCameraDevices.length;
        document.getElementById('chatCameraSelect').selectedIndex = chatActiveCameraIndex;
        await startChatCamera();
    }

    function captureChatMedia() {
        if(chatCameraMode === 'photo') {
            captureChatPhoto();
            return;
        }

        if(chatMediaRecorder && chatMediaRecorder.state === 'recording') {
            stopChatVideoRecording();
        } else {
            startChatVideoRecording();
        }
    }

    function captureChatPhoto() {
        clearChatCameraError();

        const video = document.getElementById('chatCameraVideo');

        if(!chatCameraStream || video.readyState < 2) {
            showChatCameraError('Start the camera before taking a photo.');
            return;
        }

        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob(blob => {
            if(!blob) {
                showChatCameraError('Could not capture the photo. Please try again.');
                return;
            }

            chatCapturedBlob = blob;
            chatCapturedUrl = URL.createObjectURL(blob);

            const preview = document.getElementById('chatPhotoPreview');
            preview.src = chatCapturedUrl;
            preview.classList.remove('hidden');

            video.classList.add('hidden');
            stopChatCameraStream();

            document.getElementById('captureChatBtn').disabled = true;
            document.getElementById('retakeChatBtn').disabled = false;
            document.getElementById('useChatMediaBtn').disabled = false;
        }, 'image/jpeg', 0.92);
    }

    function getSupportedVideoMimeType() {
        if(typeof MediaRecorder === 'undefined') return '';

        const types = [
            'video/webm;codecs=vp9,opus',
            'video/webm;codecs=vp8,opus',
            'video/webm',
            'video/mp4'
        ];

        return types.find(type => MediaRecorder.isTypeSupported(type)) || '';
    }

    function startChatVideoRecording() {
        clearChatCameraError();

        if(!chatCameraStream) {
            showChatCameraError('Start the camera before recording a video.');
            return;
        }

        if(typeof MediaRecorder === 'undefined') {
            showChatCameraError('Video recording is not supported by this browser.');
            return;
        }

        chatRecordedChunks = [];
        const mimeType = getSupportedVideoMimeType();

        try {
            chatMediaRecorder = mimeType
                ? new MediaRecorder(chatCameraStream, { mimeType })
                : new MediaRecorder(chatCameraStream);

            chatMediaRecorder.ondataavailable = event => {
                if(event.data && event.data.size > 0) {
                    chatRecordedChunks.push(event.data);
                }
            };

            chatMediaRecorder.onstop = () => {
                const finalType = chatMediaRecorder.mimeType || mimeType || 'video/webm';
                chatCapturedBlob = new Blob(chatRecordedChunks, { type: finalType });
                chatCapturedUrl = URL.createObjectURL(chatCapturedBlob);

                const preview = document.getElementById('chatVideoPreview');
                preview.src = chatCapturedUrl;
                preview.classList.remove('hidden');

                document.getElementById('chatCameraVideo').classList.add('hidden');
                document.getElementById('recordingIndicator').classList.add('hidden');
                document.getElementById('captureChatLabel').textContent = 'Start Recording';
                document.getElementById('captureChatIcon').className = 'bi bi-record-circle-fill';

                stopChatRecordingTimer();
                stopChatCameraStream();

                document.getElementById('captureChatBtn').disabled = true;
                document.getElementById('retakeChatBtn').disabled = false;
                document.getElementById('useChatMediaBtn').disabled = false;
            };

            chatMediaRecorder.start(250);
            startChatRecordingTimer();

            document.getElementById('recordingIndicator').classList.remove('hidden');
            document.getElementById('captureChatLabel').textContent = 'Stop Recording';
            document.getElementById('captureChatIcon').className = 'bi bi-stop-circle-fill';

        } catch(error) {
            showChatCameraError(`Unable to start video recording: ${error.message}`);
        }
    }

    function stopChatVideoRecording() {
        if(chatMediaRecorder && chatMediaRecorder.state === 'recording') {
            chatMediaRecorder.stop();
        }
    }

    function startChatRecordingTimer() {
        chatRecordingSeconds = 0;
        updateChatRecordingTimer();

        chatRecordingTimerInterval = setInterval(() => {
            chatRecordingSeconds++;
            updateChatRecordingTimer();
        }, 1000);
    }

    function stopChatRecordingTimer() {
        if(chatRecordingTimerInterval) {
            clearInterval(chatRecordingTimerInterval);
            chatRecordingTimerInterval = null;
        }
    }

    function updateChatRecordingTimer() {
        const minutes = String(Math.floor(chatRecordingSeconds / 60)).padStart(2, '0');
        const seconds = String(chatRecordingSeconds % 60).padStart(2, '0');
        document.getElementById('recordingTimer').textContent = `${minutes}:${seconds}`;
    }

    async function retakeChatMedia() {
        resetChatCapturedMedia();

        document.getElementById('captureChatIcon').className =
            chatCameraMode === 'photo' ? 'bi bi-camera-fill' : 'bi bi-record-circle-fill';

        document.getElementById('captureChatLabel').textContent =
            chatCameraMode === 'photo' ? 'Take Photo' : 'Start Recording';

        await startChatCamera();
    }

    function useCapturedChatMedia() {
        if(!chatCapturedBlob) {
            showChatCameraError('No captured media is available.');
            return;
        }

        const dataTransfer = new DataTransfer();

        if(chatCameraMode === 'photo') {
            const file = new File(
                [chatCapturedBlob],
                `chat-photo-${Date.now()}.jpg`,
                { type: chatCapturedBlob.type || 'image/jpeg' }
            );

            dataTransfer.items.add(file);
            document.getElementById('chat-img').files = dataTransfer.files;
            document.getElementById('chat-video').value = '';
            previewFileName(document.getElementById('chat-img'));
        } else {
            const mime = chatCapturedBlob.type || 'video/webm';
            const extension = mime.includes('mp4') ? 'mp4' : 'webm';

            const file = new File(
                [chatCapturedBlob],
                `chat-video-${Date.now()}.${extension}`,
                { type: mime }
            );

            dataTransfer.items.add(file);
            document.getElementById('chat-video').files = dataTransfer.files;
            document.getElementById('chat-img').value = '';
            previewFileName(document.getElementById('chat-video'));
        }

        closeChatCamera();
    }

    function previewFileName(input) {
        const preview = document.getElementById('file-name-preview');
        const text = document.getElementById('file-name-text');
        const icon = document.getElementById('file-icon');
        const img = document.getElementById('img-preview');

        if(input.files && input.files[0]) {
            const file = input.files[0];

            text.innerText = file.type.startsWith('image/')
                ? 'Captured photo'
                : 'Recorded video';

            preview.classList.remove('hidden');
            preview.classList.add('flex');

            if(file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = event => {
                    img.src = event.target.result;
                    img.classList.remove('hidden');
                    icon.classList.add('hidden');
                };

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
        document.getElementById('chat-video').value = '';
        document.getElementById('chat-img').value = '';

        const img = document.getElementById('img-preview');
        img.src = '';
        img.classList.add('hidden');

        const icon = document.getElementById('file-icon');
        icon.classList.remove('hidden');
        icon.className = 'bi bi-file-earmark-arrow-up text-xl';
    }

    document.getElementById('chatCameraSelect')?.addEventListener('change', event => {
        chatActiveCameraIndex = event.target.selectedIndex;
    });

    window.addEventListener('beforeunload', stopChatCameraStream);

    const chatBox = document.getElementById('chat-container');
    if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
</script>

<style>
    @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    #chat-container::-webkit-scrollbar { width: 5px; }
    #chat-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    aside::-webkit-scrollbar { width: 0px; }

    .camera-action-btn {
        min-height:48px;
        border:0;
        border-radius:14px;
        font-size:11px;
        font-weight:900;
        text-transform:uppercase;
        letter-spacing:.08em;
        display:flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        transition:.2s ease;
    }

    .camera-action-btn:hover:not(:disabled) {
        transform:translateY(-1px);
    }

    .camera-action-btn:disabled {
        opacity:.45;
        cursor:not-allowed;
    }
</style>
@endsection