@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 bg-light-subtle">
    {{-- Hero Section --}}
    <header class="promo-banner shadow-sm overflow-hidden mb-5">
        <img src="{{ asset('oppamall.png') }}" alt="Banner" class="w-100 object-fit-cover" style="max-height: 350px;">
    </header>

    <main class="container-lg px-3 px-md-5 mb-5">
        <div class="row g-5">
            
           {{-- SIDEBAR FILTERS --}}
<aside class="col-md-3">
    <div class="bg-white p-4 rounded-4 shadow-sm">
        <h5 class="fw-bold mb-4"><i class="bi bi-funnel me-2"></i>Filters</h5>
        
        <form action="{{ route('oppamall.category', ['categoryName' => $categoryName]) }}" method="GET">
            {{-- Category Filter (Auto-submits) --}}
            <div class="mb-4">
                <h6 class="text-uppercase fw-bold text-secondary mb-3 small">Categories</h6>
                @foreach($subcategories as $sub)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="subcategory[]" value="{{ $sub }}" id="sub_{{ $loop->index }}"
                               {{ (is_array(request('subcategory')) && in_array($sub, request('subcategory'))) ? 'checked' : '' }}
                               onchange="this.form.submit()"> {{-- Added auto-submit here --}}
                        <label class="form-check-label" for="sub_{{ $loop->index }}">{{ $sub }}</label>
                    </div>
                @endforeach
            </div>

            {{-- Price Range Filter (Manual Apply) --}}
            <div class="mb-4">
                <h6 class="text-uppercase fw-bold text-secondary mb-3 small">Price Range</h6>
                <div class="row g-2">
                    <div class="col-5">
                        <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                    </div>
                    <div class="col-2 text-center align-self-center">—</div>
                    <div class="col-5">
                        <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                    </div>
                </div>
                {{-- Submit button only triggers when clicked --}}
                <button type="submit" class="btn btn-primary btn-sm w-100 mt-3 rounded-pill fw-bold">APPLY</button>
            </div>

            {{-- Clear All Button --}}
            <a href="{{ route('oppamall.category', ['categoryName' => $categoryName]) }}" class="btn btn-outline-danger w-100 rounded-pill fw-bold mt-2">
                CLEAR ALL
            </a>
        </form>
    </div>
</aside>
            {{-- MAIN PRODUCT GRID --}}
            <section class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 fw-bold text-dark text-uppercase">{{ $categoryName }}</h1>
                    <span class="text-muted">{{ count($products) }} Products Found</span>
                </div>

                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-6 col-md-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 product-card overflow-hidden">
                                <div class="position-relative p-3 bg-white">
                                    <div class="ratio ratio-1x1 rounded-3 overflow-hidden bg-light">
                                        <img src="{{ asset('storage/' . $product->image_path) }}" class="object-fit-cover" alt="{{ $product->name }}">
                                    </div>
                                </div>
                                
                                <div class="card-body px-4 pb-4 pt-0 text-center">
                                    <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $product->name }}</h6>
                                    <p class="text-primary fw-bolder fs-5 mb-3">₱{{ number_format($product->price, 2) }}</p>
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark w-100 rounded-pill fw-bold">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 py-5 text-center">
                            <h4 class="text-secondary">No items match your selection.</h4>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</div>

<style>
    .product-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
</style>
@endsection