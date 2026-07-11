@extends('layouts.app')

@section('content')
<div class="bg-[#f6f8fb] min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-6">
        
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('store') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-900 shadow-sm border border-gray-100 hover:bg-gray-50 no-underline transition">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Your Shopping Bag</h1>
            </div>
            <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">
                {{ count($cart) }} Items Selected
            </span>
        </div>

        @if(count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-4">
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $details)
                        @php $total += $details['price'] * $details['quantity']; @endphp
                        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm flex items-center gap-6 group hover:border-[#0d47a1]/20 transition-all">
                            
                            <div class="w-24 h-24 rounded-[1.5rem] overflow-hidden bg-gray-50 flex-shrink-0 border border-gray-50">
                                <img src="{{ asset('storage/' . $details['image']) }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                     onerror="this.src='https://placehold.co/200x200?text=Item'">
                            </div>

                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $details['name'] }}</h3>
                                
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center bg-gray-50 rounded-xl p-1 border border-gray-200">
                                        <button onclick="updateQty('{{ $id }}', -1)" class="w-8 h-8 flex items-center justify-center hover:bg-white rounded-lg transition text-gray-500 shadow-sm">
                                            <i class="bi bi-dash-lg"></i>
                                        </button>
                                        
                                        <input type="text" id="qty-{{ $id }}" readonly
                                               class="w-10 text-center bg-transparent font-black text-sm text-[#0d47a1]" 
                                               value="{{ $details['quantity'] }}">
                                        
                                        <button onclick="updateQty('{{ $id }}', 1)" class="w-8 h-8 flex items-center justify-center hover:bg-white rounded-lg transition text-gray-500 shadow-sm">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                    
                                    <span class="text-[#0d47a1] font-black">₱{{ number_format($details['price'], 0) }}</span>
                                </div>
                            </div>

                            <form action="{{ route('cart.remove', $id) }}" method="POST" onsubmit="return confirm('Remove this item from your bag?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-12 h-12 rounded-full text-gray-300 hover:text-red-500 hover:bg-red-50 transition flex items-center justify-center border border-transparent hover:border-red-100">
                                    <i class="bi bi-trash3 text-xl"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white p-8 rounded-[3rem] border border-gray-100 shadow-xl shadow-gray-200/50 sticky top-28">
                        <h2 class="text-xl font-bold mb-6 text-gray-900 flex items-center gap-2">
                            <i class="bi bi-receipt text-[#0d47a1]"></i> Summary
                        </h2>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-gray-500 font-semibold">
                                <span>Subtotal</span>
                                <span>₱{{ number_format($total, 0) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-500 font-semibold">
                                <span>Verification Fee</span>
                                <span class="text-green-600 font-bold uppercase text-[10px] bg-green-50 px-2 py-1 rounded">Free</span>
                            </div>
                            <div class="pt-6 border-t border-gray-50 flex justify-between items-center">
                                <span class="font-black text-gray-900 text-lg">Total</span>
                                <span class="text-3xl font-black text-[#0d47a1]">₱{{ number_format($total, 0) }}</span>
                            </div>
                        </div>

                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-[#0d47a1] hover:bg-[#9e1b18] text-white py-5 rounded-[1.5rem] font-black text-lg transition-all shadow-lg shadow-blue-100 hover:shadow-[#9e1b18]/20 flex items-center justify-center gap-3 group">
                                Request Video Proof 
                                <i class="bi bi-camera-video-fill group-hover:scale-125 transition-transform"></i>
                            </button>
                        </form>
                        
                        <div class="mt-6 p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                            <p class="text-[11px] text-[#163d78] font-bold leading-relaxed text-center italic">
                                "According to the protocol: No payment is required until the seller sends video proof of your items."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-[4rem] py-24 text-center border border-dashed border-gray-200">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="bi bi-bag-plus text-4xl text-gray-200"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-2">Your bag is empty!</h2>
                <p class="text-gray-400 font-medium mb-10 max-w-sm mx-auto">Don't let your Oppa wait. Find the best Korean products in our store.</p>
                <a href="{{ route('store') }}" class="inline-flex items-center gap-2 bg-[#0d47a1] text-white px-10 py-4 rounded-2xl font-black no-underline hover:bg-black transition-all">
                    Go to Shop <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function updateQty(id, change) {
    let input = document.getElementById('qty-' + id);
    let currentVal = parseInt(input.value);
    let newVal = currentVal + change;

    if (newVal < 1) return;

    input.value = newVal;

    fetch(`/cart/update/${id}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: newVal })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.reload(); 
        }
    });
}
</script>
@endsection