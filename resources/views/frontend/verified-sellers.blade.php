@extends('layouts.app')

@section('content')
<div class="bg-[#f6f8fb] min-h-screen pb-20">
    
    <div class="bg-gradient-to-r from-[#0d47a1] to-[#163d78] py-12 mb-10 text-white">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-black mb-2">Verified Oppasabuy Sellers</h1>
            <p class="text-blue-100 opacity-90">Authentic Korean products, pre-vetted for your peace of mind.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <aside class="w-full lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 sticky top-24">
                    <h5 class="font-bold text-gray-900 mb-6 flex items-center">
                        <i class="bi bi-filter-left me-2"></i> Filters
                    </h5>
                    
                    <div class="mb-8">
                        <p class="text-xs font-black uppercase text-gray-400 tracking-widest mb-4">Categories</p>
                        <div class="space-y-2">
                            <a href="{{ route('store') }}" class="block p-3 rounded-2xl text-gray-600 no-underline hover:bg-gray-50 transition">All Items</a>
                            <a href="#" class="block p-3 rounded-2xl bg-red-50 text-[#9e1b18] font-bold no-underline">Verified Only</a>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-black uppercase text-gray-400 tracking-widest mb-4">Trust Status</p>
                        <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-2xl">
                            <i class="bi bi-patch-check-fill text-[#0d47a1]"></i>
                            <span class="text-sm font-bold text-[#0d47a1]">Tier 1 Verified</span>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="flex-1">
                <div class="flex justify-between items-center mb-8 bg-white p-4 rounded-3xl border border-gray-100 shadow-sm">
                    <span class="text-sm font-bold text-gray-500 px-2">
                        Trusted Partners
                    </span>
                    <div class="text-gray-400 text-sm font-bold">
                        {{ count($sellers) }} Verified Sellers
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach($sellers as $seller)
                    {{-- Logic: Use the Store Name from the Verification model --}}
                    @php
                        $displayStoreName = $seller->sellerVerification->store_name ?? $seller->name;
                        $displayStoreDesc = $seller->sellerVerification->store_description ?? 'Authentic products from our verified store.';
                    @endphp

                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden group p-8 text-center">
                        <div class="relative w-24 h-24 mx-auto mb-6">
                            {{-- Avatar reflects the Store Name --}}
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($displayStoreName) }}&background=f6f8fb&color=9e1b18&size=100" 
                                 class="w-full h-full rounded-full border-4 border-white shadow-sm" alt="{{ $displayStoreName }}">
                            <div class="absolute bottom-0 right-0 bg-white rounded-full p-1 shadow-sm">
                                <i class="bi bi-patch-check-fill text-[#0d47a1] text-xl"></i>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#9e1b18] transition-colors">
                            {{ $displayStoreName }}
                        </h3>
                        
                        <p class="text-sm text-gray-400 mb-6 line-clamp-2">
                            {{ $displayStoreDesc }}
                        </p>
                        
                        {{-- Data attributes updated to pass Store Name to JS Modal --}}
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('store.show', $seller->id) }}" 
                               class="block w-full py-4 bg-gray-900 hover:bg-[#9e1b18] text-white font-bold rounded-2xl transition-all shadow-lg shadow-gray-200 no-underline">
                                View Store Page
                            </a>
                            <button type="button" 
                                    class="text-xs font-bold text-[#0d47a1] hover:underline bg-transparent border-0 mt-2"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#sellerModal"
                                    data-name="{{ $displayStoreName }}"
                                    data-desc="{{ $displayStoreDesc }}"
                                    data-id="{{ $seller->id }}">
                                Quick Preview
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SYSTEM: Correctly synced with Store Name --}}
<div class="modal fade" id="sellerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 2.5rem;">
            <div class="modal-header border-0 pb-0 px-8 pt-8">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-10 text-center">
                <div class="mb-6">
                    <img id="modal-img" src="" class="w-32 h-32 rounded-full mx-auto border-8 border-[#f6f8fb] shadow-sm" alt="Logo">
                </div>
                
                <h2 class="text-3xl font-black mb-2" id="modal-name" style="color: #0d47a1;">Store Name</h2>
                
                <div class="inline-flex items-center space-x-2 bg-blue-50 text-[#0d47a1] px-4 py-2 rounded-full mb-6">
                    <i class="bi bi-patch-check-fill"></i>
                    <span class="text-xs font-black uppercase tracking-widest">Verified Oppasabuy Seller</span>
                </div>

                <p class="text-gray-500 leading-relaxed mb-8" id="modal-desc">
                    Store description loading...
                </p>
                
                <div class="grid grid-cols-2 gap-4 mb-8 text-left">
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Response Rate</p>
                        <p class="font-black text-gray-900">99%</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Status</p>
                        <p class="font-black text-gray-900">Verified</p>
                    </div>
                </div>

                <a href="#" id="modal-store-link" class="block w-full py-4 bg-[#9e1b18] hover:bg-black text-white font-black rounded-2xl transition-all no-underline shadow-xl shadow-red-100">
                    BROWSE STORE PRODUCTS
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sellerModal = document.getElementById('sellerModal');
    if (sellerModal) {
        sellerModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            
            // Extracts data from the data-name and data-desc attributes
            const name = button.getAttribute('data-name');
            const desc = button.getAttribute('data-desc');
            const id = button.getAttribute('data-id');

            const modalName = sellerModal.querySelector('#modal-name');
            const modalDesc = sellerModal.querySelector('#modal-desc');
            const modalLink = sellerModal.querySelector('#modal-store-link');
            const modalImg = sellerModal.querySelector('#modal-img');

            modalName.textContent = name;
            modalDesc.textContent = desc;
            modalLink.href = `{{ url('/store?seller=') }}${id}`;
            modalImg.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=f6f8fb&color=9e1b18&size=150`;
        });
    }
});
</script>
@endsection