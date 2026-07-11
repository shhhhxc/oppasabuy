@extends('layouts.app')

@section('content')
<div class="bg-[#f6f8fb] min-h-screen pb-20 relative">
    
    @php
        // --- SEARCH FILTER LOGIC ---
        $searchTerm = request('search');
        if ($searchTerm) {
            $filteredProducts = $products->getCollection()->filter(function($product) use ($searchTerm) {
                $name = strtolower($product->name);
                $category = strtolower($product->category);
                $term = strtolower($searchTerm);

                // Strict Firewall: If term is short (<= 2 chars), it MUST start with the term
                if (strlen($term) <= 2) {
                    return str_starts_with($name, $term) || str_starts_with($category, $term);
                }

                // For longer terms, allow normal partial matching
                return str_contains($name, $term) || str_contains($category, $term);
            });
            $products->setCollection($filteredProducts);
        }
        // --- END SEARCH FILTER LOGIC ---
    @endphp

    <a href="{{ route('cart.index') }}" class="fixed bottom-10 right-10 z-50 bg-[#0d47a1] text-white p-5 rounded-full shadow-2xl hover:scale-110 transition transform active:scale-95 no-underline flex items-center justify-center">
        <i class="bi bi-cart3 text-2xl"></i>
        @if(session('cart') && count(session('cart')) > 0)
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center border-4 border-[#f6f8fb]">
                {{ count(session('cart')) }}
            </span>
        @endif
    </a>

    <div class="bg-gradient-to-r from-[#0d47a1] to-[#163d78] py-12 mb-10 text-white">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-black mb-2">Find Your Oppa's Favorites</h1>
            <p class="text-blue-100 opacity-90">Authentic Korean products, verified for quality.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-bold flex items-center">
                <i class="bi bi-check-circle-fill mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            
            <aside class="w-full lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 sticky top-24">
                    <h5 class="font-bold text-gray-900 mb-6 flex items-center">
                        <i class="bi bi-filter-left me-2"></i> Filters
                    </h5>
                    
                    <div class="mb-8">
                        <p class="text-xs font-black uppercase text-gray-400 tracking-widest mb-4">Categories</p>
                        <div class="space-y-2">
                            <a href="{{ route('store') }}" class="block p-3 rounded-2xl {{ !request('category') ? 'bg-red-50 text-[#9e1b18] font-bold' : 'text-gray-600' }} no-underline">All Items</a>
                            <a href="{{ route('store', ['category' => 'K-Beauty']) }}" class="block p-3 rounded-2xl {{ request('category') == 'K-Beauty' ? 'bg-red-50 text-[#9e1b18] font-bold' : 'text-gray-600 hover:bg-gray-50' }} no-underline transition">K-Beauty</a>
                            <a href="{{ route('store', ['category' => 'K-Fashion']) }}" class="block p-3 rounded-2xl {{ request('category') == 'K-Fashion' ? 'bg-red-50 text-[#9e1b18] font-bold' : 'text-gray-600 hover:bg-gray-50' }} no-underline transition">K-Fashion</a>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-black uppercase text-gray-400 tracking-widest mb-4">Trust Level</p>
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input type="checkbox" onchange="window.location.href='{{ route('store', array_merge(request()->query(), ['verified_only' => request('verified_only') ? 0 : 1])) }}'" {{ request('verified_only') ? 'checked' : '' }} class="w-5 h-5 rounded-lg border-gray-300 text-[#0d47a1] focus:ring-0">
                            <span class="text-sm font-bold text-gray-700 group-hover:text-[#0d47a1] transition">Verified Sellers Only</span>
                        </label>
                    </div>
                </div>
            </aside>

            <div class="flex-1">
                <div class="flex justify-between items-center mb-8 bg-white p-4 rounded-3xl border border-gray-100 shadow-sm">
                    <span class="text-sm font-bold text-gray-500 px-2">
                        @if(request('category'))
                            Category: {{ request('category') }}
                        @elseif(request('search'))
                            Search: "{{ request('search') }}"
                        @else
                            Showing All Products
                        @endif
                    </span>
                    <div class="text-gray-400 text-sm font-bold">
                        {{ $products->count() }} items found
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @forelse($products as $product)
                        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden group cursor-pointer"
                             onclick="openProductModal({{ json_encode([
                                 'name' => $product->name,
                                 'price' => number_format($product->price, 2),
                                 'category' => $product->category,
                                 'description' => $product->description ?? 'No description provided.',
                                 'image' => asset('storage/' . $product->image_path),
                                 'add_url' => route('cart.add', $product->id),
                                 'seller' => $product->seller->name ?? 'Oppasabuy Seller'
                             ]) }})">
                            
                            <div class="relative aspect-[4/5] overflow-hidden bg-gray-100">
                                <img src="{{ asset('storage/' . $product->image_path) }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                     onerror="this.src='https://placehold.co/400x500?text=No+Image'">
                                
                                @if($product->seller && $product->seller->is_verified)
                                <div class="absolute top-5 left-5">
                                    <span class="bg-white/90 backdrop-blur-sm text-[#0d47a1] text-[10px] font-black px-3 py-1.5 rounded-full shadow-sm uppercase">
                                        Verified Seller
                                    </span>
                                </div>
                                @endif
                            </div>

                            <div class="p-8">
                                <h3 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-[#9e1b18] transition-colors line-clamp-1">
                                    {{ $product->name }}
                                </h3>
                                <p class="text-sm text-gray-400 mb-6">{{ $product->category }}</p>
                                
                                <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                    <div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter mb-0">Price</p>
                                        <span class="text-2xl font-black text-[#0d47a1]">₱{{ number_format($product->price, 0) }}</span>
                                    </div>

                                    <div class="bg-gray-50 text-gray-400 w-12 h-12 rounded-2xl flex items-center justify-center transition-all group-hover:bg-[#163d78] group-hover:text-white">
                                        <i class="bi bi-eye text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-dashed border-gray-200">
                            <i class="bi bi-search text-4xl text-gray-200 mb-4 block"></i>
                            <h3 class="text-gray-500 font-black uppercase tracking-widest">Product Unavailable</h3>
                            <p class="text-gray-400 font-bold mt-2">No products found matching your criteria.</p>
                            @if(request('search'))
                                <a href="{{ route('store') }}" class="mt-4 inline-block bg-[#0d47a1] text-white px-6 py-2 rounded-xl font-bold text-[10px] uppercase">View All Products</a>
                            @endif
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div id="productModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 md:p-6">
    <div class="absolute inset-0 bg-[#0d47a1]/20 backdrop-blur-md" onclick="closeProductModal()"></div>
    
    <div class="bg-white w-full max-w-5xl rounded-[2.5rem] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.1)] relative z-10 flex flex-col md:flex-row animate__animated animate__zoomIn animate__faster">
        
        <button onclick="closeProductModal()" class="absolute top-6 right-6 z-30 bg-gray-100/80 hover:bg-red-500 hover:text-white w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="w-full md:w-[45%] bg-[#f8fafc] p-8 flex flex-col items-center justify-center">
            <div class="relative group w-full aspect-square rounded-3xl overflow-hidden shadow-inner bg-white flex items-center justify-center">
                <img id="modalImage" src="" class="max-w-[85%] max-h-[85%] object-contain transition-transform duration-700 group-hover:scale-110">
            </div>
            <div class="mt-6">
                <span class="bg-white px-4 py-2 rounded-xl shadow-sm text-[10px] font-black uppercase tracking-tighter text-[#0d47a1] border border-gray-100">
                    Official Merchandise
                </span>
            </div>
        </div>

        <div class="w-full md:w-[55%] p-8 md:p-12 flex flex-col">
            <div class="mb-auto">
                <div class="flex items-center gap-2 mb-4">
                    <span id="modalCategory" class="px-3 py-1 bg-red-50 text-[#9e1b18] text-[11px] font-black rounded-lg uppercase tracking-widest"></span>
                    <span class="text-gray-300">•</span>
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-patch-check-fill text-blue-500 text-sm"></i>
                        <span id="modalSeller" class="text-xs font-bold text-gray-500"></span>
                    </div>
                </div>

                <h2 id="modalName" class="text-4xl font-black text-gray-900 mb-6 tracking-tight leading-tight"></h2>
                
                <div class="space-y-4">
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Product Details</p>
                    <div class="bg-gray-50/80 border border-gray-100 rounded-[1.5rem] p-6">
                        <p id="modalDescription" class="text-gray-600 text-sm leading-relaxed min-h-[80px]"></p>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex items-center justify-between border-t border-gray-100 pt-8">
                <div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">Investment</p>
                    <span class="text-3xl font-black text-[#0d47a1]" id="modalPrice"></span>
                </div>

                <form id="modalForm" action="" method="POST">
                    @csrf
                    <button type="submit" class="group relative bg-[#9e1b18] text-white pl-6 pr-14 py-3.5 rounded-xl font-black uppercase text-xs tracking-tighter hover:bg-[#0d47a1] transition-all duration-500 shadow-lg overflow-hidden">
                        <span class="relative z-10">Add To Cart</span>
                        <div class="absolute right-0 top-0 bottom-0 w-12 bg-black/10 flex items-center justify-center">
                            <i class="bi bi-bag-plus-fill text-lg"></i>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openProductModal(product) {
    const modal = document.getElementById('productModal');
    
    document.getElementById('modalImage').src = product.image;
    document.getElementById('modalName').innerText = product.name;
    document.getElementById('modalCategory').innerText = product.category;
    document.getElementById('modalSeller').innerText = 'Sold by: ' + product.seller;
    document.getElementById('modalDescription').innerText = product.description;
    document.getElementById('modalPrice').innerText = '₱' + product.price;
    document.getElementById('modalForm').action = product.add_url;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden'; 
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto'; 
}

window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeProductModal();
});
</script>

@endsection