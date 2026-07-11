@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e9e9e9; /* Gray background base mula sa mockup */
    }

    /* ---------- BANNER CAROUSEL ---------- */
    .store-banner-container {
        position: relative;
        width: 100%;
        background: #fff;
    }

    .store-banner-slider img {
        width: 100%;
        height: 380px;
        object-fit: cover;
    }

    /* ---------- STORE PROFILE HEADER ---------- */
    .profile-header-section {
        background: #fff;
        padding: 20px 0;
        border-bottom: 1px solid #dbdbdb;
    }

    .store-logo-wrapper {
        width: 90px;
        height: 90px;
        background: #fff;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
    }

    .store-logo-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .verified-badge {
        color: #2e7d32;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* ---------- METADATA LIST ---------- */
    .meta-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px 24px;
        font-size: 13px;
        color: #555;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .meta-item i {
        font-size: 18px;
        color: #444;
        width: 20px;
        text-align: center;
    }

    .meta-label {
        font-weight: 600;
        color: #333;
    }

    /* ---------- PRODUCT GRID CARD ---------- */
    .product-section-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #555;
    }

    .product-grid-card {
        background: #fff;
        border: 1px solid #e2e2e2;
        border-radius: 4px;
        aspect-ratio: 1/1; /* Perfektong parisukat gaya ng nasa mockup */
        transition: transform 0.2s;
    }

    .product-grid-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* ---------- BOTTOM REUSABLE FOOTER BRANDING ---------- */
    .feature-icon-box {
        font-size: 18px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .feature-title {
        font-size: 11px;
        font-weight: 700;
        color: #222;
        margin: 0;
    }
    .feature-desc {
        font-size: 10px;
        color: #777;
        margin: 0;
        line-height: 1.2;
    }

    @media (max-width: 768px) {
        .meta-info-grid {
            grid-template-columns: 1fr;
        }
        .store-banner-slider img {
            height: 200px;
        }
    }
</style>

<div class="store-banner-container shadow-sm">
    <div id="storeBannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @forelse($bannerPaths as $index => $path)
                <button type="button" data-bs-target="#storeBannerCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
            @empty
                <button type="button" data-bs-target="#storeBannerCarousel" data-bs-slide-to="0" class="active"></button>
            @endforelse
        </div>
        <div class="carousel-inner store-banner-slider">
            @forelse($bannerPaths as $index => $path)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $path) }}" alt="Store Banner">
                </div>
            @empty
                {{-- Fallback default cover photo depende sa uri ng store kung walang nakaupload --}}
                <div class="carousel-item active">
                    @if($type === 'sari-sari')
                        <img src="https://images.unsplash.com/photo-1578916171728-46686eac8d58?q=80&w=1200" alt="Sari Sari Default Banner">
                    @else
                        <img src="https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?q=80&w=1200" alt="Wet Market Default Banner">
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="profile-header-section mb-4 shadow-sm">
    <div class="container">
        <div class="row align-items-center py-2">
            
            {{-- Left Side: Profile Identity --}}
            <div class="col-md-5 d-flex align-items-center gap-3 border-end-md">
                <div class="store-logo-wrapper shadow-sm">
                    <img
    src="{{ $logoPath
        ? asset('storage/'.$logoPath)
        : 'https://ui-avatars.com/api/?name='.urlencode($storeName).'&background=4caf50&color=fff'
    }}"
    alt="{{ $storeName }}"
>
                </div>
                <div>
                    <h1 class="fs-4 fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        {{ $storeName }}
                    </h1>
                    @if($store->verification && $store->verification->status === 'approved')
                        <div class="verified-badge my-1">
                            <i class="bi bi-check-circle-fill"></i> Verified Seller
                        </div>
                    @endif
                    <p class="text-secondary small m-0">BIO: {{ $store->description ?? 'Welcome to our friendly neighborhood store!' }}</p>
                </div>
            </div>

            {{-- Right Side: Dynamic Store Specifications Data --}}
            <div class="col-md-7 ps-md-5 mt-3 mt-md-0">
                @php
    $followersCount = $store->followers_count ?? 0;
    $followingCount = $store->following_count ?? 0;
    $storeRating = $store->rating ?? 0;
@endphp

<div class="meta-info-grid">
    <div class="meta-item">
        <i class="bi bi-people-fill"></i>
        <div>
            <span class="meta-label">Followers:</span>
            {{ number_format($followersCount) }}
        </div>
    </div>

    <div class="meta-item">
        <i class="bi bi-door-open-fill"></i>
        <div>
            <span class="meta-label">Open since:</span>
            {{ $establishedYear }}
        </div>
    </div>

    <div class="meta-item">
        <i class="bi bi-person-plus-fill"></i>
        <div>
            <span class="meta-label">Following:</span>
            {{ number_format($followingCount) }}
        </div>
    </div>

    <div class="meta-item">
        <i class="bi bi-clock-history"></i>
        <div>
            <span class="meta-label">Store hours:</span>
            {{ $storeHours }}
        </div>
    </div>

    <div class="meta-item">
        <i class="bi bi-star-fill text-warning"></i>
        <div>
            <span class="meta-label">Rating:</span>
            {{ number_format((float)$storeRating,1) }}
        </div>
    </div>

    <div class="meta-item">
        <i class="bi bi-chat-dots-fill text-success"></i>
        <div>
            <a href="#" class="text-decoration-none text-success fw-bold">
                Messages
            </a>
        </div>
    </div>
</div>
</div>

<div class="container mb-5">
    <div class="bg-white p-4 shadow-sm border-0 rounded-0">
        <h2 class="product-section-title mb-4">My Products</h2>
        
        <div class="row row-cols-2 row-cols-md-5 g-3 mb-4">
            {{-- Loop through the paginated products --}}
            @forelse($products as $product)
                <div class="col">
                    <div class="product-grid-card shadow-sm p-2 d-flex flex-column justify-content-between">
                        {{-- Added image and price for a complete look --}}
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="img-fluid mb-2" alt="{{ $product->name }}">
                        <div class="text-center fw-bold text-dark small">{{ $product->name }}</div>
                        <div class="text-center text-primary fw-bold small">₱{{ number_format($product->price, 2) }}</div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No products found for this store.</p>
                </div>
            @endforelse
        </div>

        {{-- Laravel Pagination links replace the manual mockup block --}}
        <div class="d-flex justify-content-center border-top pt-3 mt-4">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
<div class="container text-center py-2 mb-4">
    <p class="fw-bold text-dark mb-3" style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#666;">Why Choose Oppa Green Mart?</p>
    <div class="row g-3 justify-content-center text-start">
        <div class="col-md-2 col-6">
            <div class="feature-icon-box">
                <i class="bi bi-shop"></i>
                <div>
                    <p class="feature-title">Wide Selection</p>
                    <p class="feature-desc">Wet market & Sari-sari in One Site</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="feature-icon-box">
                <i class="bi bi-shield-check"></i>
                <div>
                    <p class="feature-title">Trusted Sellers</p>
                    <p class="feature-desc">Verified Stores Only</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="feature-icon-box">
                <i class="bi bi-truck"></i>
                <div>
                    <p class="feature-title">Instant Delivery</p>
                    <p class="feature-desc">Quick & reliable right to your door</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="feature-icon-box">
                <i class="bi bi-geo-alt"></i>
                <div>
                    <p class="feature-title">Pick Up Later</p>
                    <p class="feature-desc">Shop at home. Pay & pick up on the go.</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="feature-icon-box">
                <i class="bi bi-basket3"></i>
                <div>
                    <p class="feature-title">Pabili Service</p>
                    <p class="feature-desc">We shop for you. Just message book your shopper</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection     