@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 bg-light-subtle">
    {{-- Hero Section --}}
    <header class="promo-banner shadow-sm overflow-hidden mb-5 bg-white">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <a href="{{ route('lifestyle.hub') }}" class="btn btn-outline-secondary btn-sm mb-3 rounded-pill">
                        <i class="bi bi-arrow-left"></i> Back to Lifestyle Hub
                    </a>
                    {{-- Updated Header Style: Black and Green --}}
                    <h1 class="fw-bold" style="font-size: 2.8rem; color: #222;">
                        {{-- Splits name to make the last word Green --}}
                        @php 
                            $words = explode(' ', $categoryName);
                            $lastWord = array_pop($words);
                            $firstPart = implode(' ', $words);
                        @endphp
                        {{ $firstPart }} <span class="text-success">{{ $lastWord }}</span>
                    </h1>
                    <p class="text-muted fs-5">Explore our professional services tailored for your needs.</p>
                </div>
                <div class="col-md-6 hero-collage d-none d-md-grid">
                    @for($i = 1; $i <= 6; $i++)
                        <img src="https://picsum.photos/400/300?random={{ $i }}" alt="Service">
                    @endfor
                </div>
            </div>
        </div>
    </header>

    <main class="container-lg px-3 px-md-5 mb-5">
        <div class="row g-5">
            
            {{-- SIDEBAR FILTERS --}}
            <aside class="col-md-3">
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <h5 class="fw-bold mb-4"><i class="bi bi-funnel me-2"></i>Filters</h5>
                    
                    <form action="{{ route('lifestyle.category', ['categoryName' => $categoryName]) }}" method="GET">
                        
                        <div class="mb-4">
                            <h6 class="text-uppercase fw-bold text-secondary mb-3 small">Subcategories</h6>
                            @foreach($subcategories as $sub)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="subcategory[]" value="{{ $sub }}" id="sub_{{ $loop->index }}"
                                           {{ (is_array(request('subcategory')) && in_array($sub, request('subcategory'))) ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <label class="form-check-label" for="sub_{{ $loop->index }}">{{ $sub }}</label>
                                </div>
                            @endforeach
                        </div>

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
                            <button type="submit" class="btn btn-success btn-sm w-100 mt-3 rounded-pill fw-bold">APPLY</button>
                        </div>

                        <a href="{{ route('lifestyle.category', ['categoryName' => $categoryName]) }}" class="btn btn-outline-danger w-100 rounded-pill fw-bold mt-2">
                            CLEAR ALL
                        </a>
                    </form>
                </div>
            </aside>

            {{-- MAIN SERVICE GRID --}}
            <section class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 fw-bold text-dark text-uppercase">{{ $categoryName }}</h1>
                    <span class="text-muted">{{ $services->count() }} Services Found</span>
                </div>

                <div class="row g-4">
                    @forelse($services as $service)
                        <div class="col-6 col-md-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 product-card overflow-hidden">
                                <div class="position-relative p-3 bg-white">
                                    <div class="ratio ratio-1x1 rounded-3 overflow-hidden bg-light">
                                        <img src="{{ asset('storage/' . $service->image_path) }}" class="object-fit-cover" alt="{{ $service->name }}">
                                    </div>
                                </div>
                                
                                <div class="card-body px-4 pb-4 pt-0 text-center">
                                    <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $service->name }}</h6>
                                    <p class="text-success fw-bolder fs-5 mb-3">₱{{ number_format($service->price, 2) }}</p>
                                    
                                    <a href="{{ route('reservations.index', ['service_id' => $service->id]) }}" class="btn btn-outline-success w-100 rounded-pill fw-bold">Book Now</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 py-5 text-center">
                            <h4 class="text-secondary">No services match your criteria.</h4>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</div>

<style>
    /* Hero Collage Styles */
    .hero-collage {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding: 20px;
    }
    .hero-collage img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        transition: transform 0.3s;
    }
    .hero-collage img:hover { transform: scale(1.05); }

    /* Product Card Styles */
    .product-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
</style>
@endsection