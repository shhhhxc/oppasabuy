<?php

    // Existing queries
    if (!isset($activeAd) || !$activeAd) {
        $activeAd = \App\Models\Ad::whereIn('is_active', [1, '1', true])
            ->where('type', '!=', 'video')
            ->latest()
            ->first();
    }

    if (!isset($publishedVerse) || !$publishedVerse) {
        $publishedVerse = \App\Models\BibleVerse::whereIn('is_published', [1, '1', true])
            ->latest()
            ->first();
    }

    if (!isset($promotionalVideo) || !$promotionalVideo) {
        $promotionalVideo = \App\Models\Ad::where('type', 'video')
            ->whereIn('is_active', [1, '1', true])
            ->latest()
            ->first();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oppasabuy | Authentic Korean Products</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link class="rtl" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<style>
    #map { height: 300px; width: 100%; margin-bottom: 10px; border-radius: 8px; }
</style>

<style>
:root {
    --oppa-red: #cc2121;
    --oppa-blue: #0d47a1;
    --bg-gray: #e9e9e9;
    --text-dark: #333333;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: var(--bg-gray);
    color: var(--text-dark);
    margin: 0;
}

/* ---------- TOP UTILITY NAV ---------- */
.top-utility-nav {
    background: #fff;
    border-bottom: 1px solid #ddd;
    padding: 5px 0;
}

.top-utility-nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    gap: 25px;
}

.top-utility-nav a {
    text-decoration: none;
    color: #666;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

/* ---------- MAIN HEADER ---------- */
.main-header {
    background: #fff;
    padding: 15px 0;
    border-bottom: 1px solid #ddd;
}

.search-bar-wrap {
    position: relative;
    max-width: 600px;
    width: 100%;
}

.search-bar-wrap input {
    width: 100%;
    padding: 8px 15px;
    border: 2px solid var(--oppa-blue);
    border-radius: 4px;
}

.search-btn {
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    background: var(--oppa-blue);
    color: white;
    border: none;
    padding: 0 15px;
    border-radius: 0 2px 2px 0;
}

.header-icons {
    display: flex;
    align-items: center;
    gap: 20px;
}

.icon-btn {
    position: relative;
    font-size: 24px;
    color: var(--oppa-blue);
    text-decoration: none;
}

.cart-badge {
    position: absolute;
    top: -5px;
    right: -10px;
    background: var(--oppa-red);
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 50%;
    font-weight: bold;
}

/* ---------- BANNER ---------- */
.promo-banner {
    background: #0a1128;
    color: white;
    padding: 30px;
    border-radius: 0;
    position: relative;
    overflow: hidden;
}

.banner-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1100px;
    margin: 0 auto;
}

.banner-text h2 {
    font-weight: 800;
    font-size: 2.5rem;
    margin-bottom: 5px;
}

.shop-now-btn {
    background: var(--oppa-red);
    color: white;
    padding: 10px 25px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
    border: none;
}

/* ---------- CONTENT BLOCKS ---------- */
.content-block {
    background: #fff;
    padding: 20px;
    margin-bottom: 20px;
}

.block-title {
    font-weight: bold;
    font-size: 14px;
    color: #555;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.video-container {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    background: #000;
    overflow: hidden;
}

.video-container video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ---------- FLASH SALE ---------- */
.flash-header {
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.timer-box {
    background: #000;
    color: #fff;
    padding: 2px 6px;
    border-radius: 3px;
    font-weight: bold;
    margin: 0 2px;
}

/* ---------- PRODUCT GRID ---------- */
.grid-container {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 10px;
    width: 100%;
}

.placeholder-box {
    background: #fff;
    border: 1px solid #eee;
    aspect-ratio: 1/1;
    border-radius: 4px;
}

/* ---------- CATEGORY ---------- */
.category-item {
    background: #fff;
    border: 1px solid #ddd;
    min-height: 110px;
    border-radius: 4px;
    text-decoration: none;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 10px;
}

.category-item:hover {
    border-color: var(--oppa-blue);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* ---------- PRODUCT CARD (OLD STYLE) ---------- */
.product-card {
    background: #fff;
    border: 1px solid #eee;
    padding: 10px;
    border-radius: 6px;
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.product-card-link {
    text-decoration: none;
    display: block;
}

.product-card-link:hover .product-title {
    color: var(--oppa-blue) !important;
}

.product-image-wrap {
    aspect-ratio: 1/1;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #fdfdfd;
}

.product-image-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.add-to-cart-btn {
    background-color: var(--oppa-blue);
    color: white;
    border: none;
    width: 100%;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 6px 0;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.add-to-cart-btn:hover {
    background-color: #083375;
}

/* ---------- FOOTER ---------- */
.footer-main {
    background: #fff;
    padding: 40px 0;
    margin-top: 40px;
    border-top: 2px solid #ddd;
}

.footer-col h6 {
    font-weight: bold;
    margin-bottom: 15px;
    color: var(--oppa-blue);
}

.footer-col ul {
    list-style: none;
    padding: 0;
}

.footer-col ul li {
    margin-bottom: 8px;
    font-size: 13px;
}

.footer-col ul li a {
    text-decoration: none;
    color: #666;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-links a {
    font-size: 28px;
    color: #1877f2;
}

/* ---------- MODAL THEME ---------- */
.oppa-modal-content {
    border: 3px solid var(--oppa-blue);
    border-radius: 12px;
}

.oppa-modal-header {
    border-bottom: 1px solid #eee;
    background-color: #fff;
    border-radius: 12px 12px 0 0;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 992px) {
    .grid-container { grid-template-columns: repeat(3, 1fr); }
    .banner-content { flex-direction: column; text-align: center; }
}

@media (min-width: 992px) {
    .video-container {
        max-height: 450px;
        width: 100%;
    }
}

/* ---------- SERVICE CARD ---------- */
.service-card {
    cursor: pointer;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(13, 71, 161, 0.08);
    transition: all 0.3s ease;
    min-height: 150px;
    display: flex;
    justify-content: center;
}

.service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(13, 71, 161, 0.25) !important;
    background: linear-gradient(135deg, #ffffff, #eef5ff);
}

.icon-box {
    width: 70px;
    height: 70px;
    margin: auto;
    border-radius: 50%;
    background: linear-gradient(135deg, #0d47a1, #1976d2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(13, 71, 161, 0.35);
    transition: all 0.3s ease;
}

.service-card:hover .icon-box {
    transform: scale(1.1) rotate(8deg);
}

/* ---------- MODERN MODAL ---------- */
.modern-modal {
    border-radius: 18px;
    overflow: hidden;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    background: #fff;
}

.modern-header {
    background: linear-gradient(135deg, #0d47a1, #1976d2);
    color: white;
    border: none;
    padding: 18px;
}

.modern-input {
    border-radius: 12px !important;
    border: 1px solid rgba(13, 71, 161, 0.15) !important;
    transition: all 0.25s ease;
}

.modern-input:focus {
    border-color: #0d47a1 !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 71, 161, 0.15);
}

.btn-modern {
    background: linear-gradient(135deg, #0d47a1, #1976d2);
    color: white;
    border-radius: 12px;
    padding: 12px;
    font-weight: 600;
    border: none;
    transition: 0.3s;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(13, 71, 161, 0.3);
    color: white;
}

.modern-map {
    height: 300px;
    width: 100%;
    border-radius: 14px;
    border: 2px solid rgba(13, 71, 161, 0.15);
}

/* ---------- K-SERVICES HEADER BOX ---------- */
.ksvc-header-box {
    background: linear-gradient(135deg, #0d47a1 0%, #ffffff 50%, #c62828 130%);
    border-radius: 18px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    border: 1px solid rgba(255,255,255,0.3);
}

.header-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    backdrop-filter: blur(10px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

/* ---------- K PRODUCT MODERN ---------- */
.kproduct-container {
    padding: 15px;
}

.kproduct-header {
    padding: 10px 0;
}

.product-card-modern {
    background: #fff;
    border-radius: 18px;
    padding: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
}

.product-card-modern:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px rgba(13, 71, 161, 0.15);
}

.product-image {
    width: 100%;
    height: 140px;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 10px;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.3s ease;
}

.product-card-modern:hover .product-image img {
    transform: scale(1.05);
}

.product-title {
    font-size: 14px;
    font-weight: 600;
    color: #222;
    margin-bottom: 5px;
    height: 38px;
    overflow: hidden;
}

.product-price {
    font-weight: 700;
    color: #0d47a1;
    margin-bottom: 10px;
}

.product-link {
    text-decoration: none;
    color: inherit;
}

.cart-btn {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #0d47a1, #1976d2);
    color: white;
    font-weight: 600;
    transition: 0.3s;
}

.cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(13, 71, 161, 0.25);
}

.product-skeleton {
    height: 220px;
    border-radius: 18px;
    background: linear-gradient(90deg, #eee, #f5f5f5, #eee);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ---------- K CATEGORY ---------- */
.kcategory-container {
    background: #fff;
    border-radius: 18px;
    padding: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.06);
}

.category-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.category-item-modern {
    background: linear-gradient(135deg, #ffffff, #f7faff);
    border: 1px solid rgba(13, 71, 161, 0.08);
    border-radius: 16px;
    padding: 14px 10px;
    text-align: center;
    text-decoration: none;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.category-item-modern:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(13, 71, 161, 0.15);
    border-color: rgba(13, 71, 161, 0.25);
}

.category-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0d47a1, #1976d2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: 8px;
    box-shadow: 0 8px 18px rgba(13, 71, 161, 0.25);
}

.category-name {
    font-size: 11px;
    font-weight: 700;
    color: #333;
    line-height: 1.2;
}

@media (max-width: 992px) {
    .category-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 576px) {
    .category-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}
 /* =========================================================
   ✅ MODERN PROMOTION CARD (FIXED)
========================================================= */

.promotion-card {
    background: #fff;
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    border: 1px solid rgba(13, 71, 161, 0.1);
    transition: 0.3s ease;
}

.promotion-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(13, 71, 161, 0.18);
}

/* HEADER */
.promotion-header {
    background: linear-gradient(135deg, #0d47a1 0%, #1565c0 55%, #cc2121 130%);
    color: white;
    padding: 18px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.promotion-header h5 {
    font-size: 18px;
}

.promotion-header small {
    opacity: 0.85;
}

/* ICON */
.promotion-icon {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.25);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    backdrop-filter: blur(10px);
}

/* BODY */
.promotion-body {
    background: #fff;
    min-height: 240px;
}

.promotion-link {
    display: block;
    overflow: hidden;
}

.promotion-image {
    width: 100%;
    height: 240px;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.promotion-link:hover .promotion-image {
    transform: scale(1.05);
}

/* EMPTY */
.promotion-empty {
    min-height: 240px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #666;
    text-align: center;
    padding: 20px;
}

.empty-icon {
    width: 75px;
    height: 75px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0d47a1, #1976d2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    box-shadow: 0 10px 25px rgba(13, 71, 161, 0.25);
}

/* MOBILE FIX */
@media (max-width: 576px) {
    .promotion-header {
        padding: 15px;
    }

    .promotion-header h5 {
        font-size: 16px;
    }

    .promotion-icon {
        width: 42px;
        height: 42px;
        font-size: 18px;
    }

    .promotion-image,
    .promotion-body,
    .promotion-empty {
        height: 200px;
        min-height: 200px;
    }



}
</style>

</head>
<body>

    <nav class="top-utility-nav d-none d-md-block">
        <div class="container">
            <ul>
                <li><a href="#">PERSONAL CARE & LIFESTYLE HUB</a></li>
                <li><a href="{{ route('greenmart.index') }}">GREEN MART</a></li>
                <li><a href="{{ route('store') }}">WEB STORE</a></li>
                <li><a href="{{ route('oppamall.index') }}">OPPAMALL</a></li>
                <li> <a href="#">MEMBERSHIP</a></li>
                 <li> <a href="{{ route('hatid.express') }}">OPPAEXPRESS</a></li>
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Sign Up</a></li>
                @endguest
            </ul>
        </div>
    </nav>

    <header class="main-header">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ url('/') }}" id="home-link">
                <img src="{{ asset('oppa.png') }}" alt="Oppasabuy" height="50">
            </a>

      <div class="search-bar-wrap d-none d-md-block">
    {{-- Update the action to point to the route name you defined in web.php --}}
    <form action="{{ route('oppamall.search') }}" method="GET">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search OppaMall...">
        <button type="submit" class="search-btn">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>
            <div class="header-utilities d-flex align-items-center gap-3">
                @auth
                    @php
                        $user = auth()->user();
                        $dashboardRoute = match($user->role) {
                            'admin' => route('admin.verify'),
                            default => route('buyer.dashboard'),
                        };
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="icon-btn">
                        <i class="bi bi-person"></i>
                    </a>
                    <a href="{{ route('cart.index') }}" class="icon-btn">
                        <i class="bi bi-cart3"></i>
                        <span class="cart-badge">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                    </a>
                @endauth
                <a href="#" class="icon-btn"><i class="bi bi-list"></i></a>
            </div>
        </div>
    </header>

    <main class="container py-3">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 8px; font-size: 13px; font-weight: 600;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        

        <section class="promo-banner mb-4" style="padding: 0; margin: 0; border: none;">
            <div class="banner-image">
                <a href="{{ route('store') }}" style="display: block; border: none;">
                    <img src="{{ asset('oppasabuy.png') }}" 
                         alt="Promotional Banner" 
                         style="width: 100%; height: auto; display: block; border: none; outline: none;">
                </a>
            </div>
        </section>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="content-block p-0 overflow-hidden shadow-sm border-0">
                    <div class="video-container">
                        @if(isset($promotionalVideo) && $promotionalVideo && $promotionalVideo->video_path)
                            <video class="w-100 h-100" controls preload="metadata">
                                <source src="{{ asset('storage/' . $promotionalVideo->video_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted p-5 text-center">
                                <i class="bi bi-camera-reels mb-2" style="font-size: 2rem;"></i>
                                <p class="small">No promotional video uploaded.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

<div class="ksvc-container p-4 mb-5">
 <section class="content-block">
    <div class="flash-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <span class="fw-bold text-danger">Top 10 Sale</span>
            <div class="timer">
                <small>Ending in</small>
                <span class="timer-box">25</span>
                <span class="timer-box">24</span>
                <span class="timer-box">17</span>
            </div>
        </div>
        <a href="{{ url('/store') }}" class="text-danger text-decoration-none small fw-bold">
            See All <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    <div class="grid-container">
        @foreach($topProducts as $product)
            <div class="product-card">
            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                <h6>{{ $product->name }}</h6>
                
                @if($product->on_sale)
                    {{-- Display Original Price with Strikethrough --}}
                    <div class="price-container">
                        <small class="text-muted text-decoration-line-through" style="display: block; font-size: 0.75rem;">
                            ₱{{ number_format($product->price, 2) }}
                        </small>
                        {{-- Display Discounted Price --}}
                        <p class="text-danger fw-bold m-0">
                            ₱{{ number_format(
                                $product->discount_type === 'percent' 
                                    ? $product->price * (1 - ($product->discount_value / 100)) 
                                    : $product->price - $product->discount_value, 
                                2
                            ) }}
                        </p>
                    </div>
                @else
                    {{-- Regular Price Display --}}
                    <p class="text-danger fw-bold">₱{{ number_format($product->price, 2) }}</p>
                @endif
            </div>
        @endforeach
    </div>
</section>
        </section>

      <script>
    // 1. Check if an end time already exists in LocalStorage
    let endTime = localStorage.getItem('timerEndTime');

    // 2. If it doesn't exist, calculate it for the first time and save it
    if (!endTime) {
        endTime = new Date().getTime() + (24 * 60 * 60 * 1000);
        localStorage.setItem('timerEndTime', endTime);
    }

    function updateTimer() {
        const now = new Date().getTime();
        const diff = endTime - now;

        // If the timer runs out, clear the local storage
        if (diff <= 0) {
            localStorage.removeItem('timerEndTime');
            return;
        }

        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        const boxes = document.querySelectorAll('.timer-box');
        boxes[0].innerText = hours.toString().padStart(2, '0');
        boxes[1].innerText = minutes.toString().padStart(2, '0');
        boxes[2].innerText = seconds.toString().padStart(2, '0');
    }

    // Run immediately and then every second
    updateTimer();
    setInterval(updateTimer, 1000);
</script>

   <section class="content-block kcategory-container">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <p class="block-title mb-0">Categories</p>
        <i class="bi bi-grid-3x3-gap text-primary fs-5"></i>
    </div>

    <div class="grid-container category-grid">

        @foreach($categories as $category)

            <a href="{{ route('oppamall.category', ['categoryName' => $category['name']]) }}"
               class="category-item-modern">

                <div class="category-icon">
                    <i class="bi {{ $category['icon'] ?? 'bi-grid-3x3-gap' }}"></i>
                </div>

                <span class="category-name">
                    {{ $category['name'] }}
                </span>

            </a>

        @endforeach

    </div>

</section>
<script>
    // Siguraduhin na ang map object ay accessible
    // Ang logic na ito ay dapat nasa loob ng script kung saan mo ini-initialize ang mapa
    map.on('click', function(e) {
        if (selectingPickup) {
            document.getElementById('pickup_lat').value = e.latlng.lat;
            document.getElementById('pickup_lng').value = e.latlng.lng;
        } else {
            document.getElementById('dest_lat').value = e.latlng.lat;
            document.getElementById('dest_lng').value = e.latlng.lng;
        }
    });

    // Loading state handler
    document.getElementById('paskaayForm').addEventListener('submit', function() {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('btnText').classList.add('d-none');
        document.getElementById('loadingSpinner').classList.remove('d-none');
    });
</script>

 <section class="content-block">
    <div class="kproduct-header mb-3">
        <h5 class="fw-bold mb-0">K-Product</h5>
        <small class="text-muted">Discover items you may like</small>
    </div>

    <!-- Grid -->
    <div class="grid-container">

        @forelse($products as $product)

            <div class="product-card-modern">

                <a href="{{ route('store.show', $product->seller_id) }}" class="product-link">

                    <!-- Image -->
                    <div class="product-image">
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                    </div>

                    <!-- Title -->
                    <h6 class="product-title">
                        {{ $product->name }}
                    </h6>

                    <!-- Price -->
                    <div class="product-price">
                        ₱{{ number_format($product->price, 2) }}
                    </div>

                </a>

                <!-- Add to Cart -->
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">

                    <button type="submit" class="cart-btn">
                        <i class="bi bi-cart-plus"></i>
                        Add to Cart
                    </button>
                </form>

            </div>

        @empty

            @foreach(range(1, 6) as $i)
                <div class="product-skeleton"></div>
            @endforeach

        @endforelse

    </div>

</section>
</section>
<div class="row g-3 mb-5">
    <div class="col-md-6">
        <div class="promotion-card h-100">

            <!-- Header -->
            <div class="promotion-header">
                <div>
                    <h5 class="mb-0 fw-bold">Promotions</h5>
                    <small>Latest deals & announcements</small>
                </div>
                <div class="promotion-icon">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
            </div>

            <!-- Content -->
            <div class="promotion-body">

                @if(isset($activeAd) && $activeAd && ($activeAd->is_active == 1 || $activeAd->is_active == '1'))

                    <a href="{{ $activeAd->target_url ?? '#' }}" class="promotion-link">
                        <img 
                            src="{{ asset('storage/' . $activeAd->image_path) }}"
                            alt="{{ $activeAd->title ?? 'Promotional Banner' }}"
                            class="promotion-image">
                    </a>

                @else

                    <div class="promotion-empty">
                        <div class="empty-icon">
                            <i class="bi bi-megaphone"></i>
                        </div>

                        <h6 class="fw-bold mt-3 mb-1">
                            No Promotions Yet
                        </h6>

                        <small>
                            Stay tuned for upcoming deals, discounts, and announcements!
                        </small>
                    </div>

                @endif

            </div>
        </div>
    </div>

                <div class="col-md-6">
                <div class="verse-card h-100">
                    <div class="verse-header">
                        <span class="verse-title">Verse of the Day ⚡</span>
                        <i class="bi bi-book verse-icon"></i>
                    </div>
                    <div class="verse-body">
                        @if(isset($publishedVerse) && $publishedVerse)
                            <div class="verse-content">
                                <i class="bi bi-quote verse-quote"></i>
                                <p class="verse-text">
                                    "{{ $publishedVerse->verse_text }}"
                                </p>
                                <div class="verse-ref">
                                    {{ $publishedVerse->reference }}
                                </div>
                            </div>
                        @else
                            <div class="verse-empty">
                                <i class="bi bi-book verse-empty-icon"></i>
                                <p>Encouraging words arriving soon.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <style>

.verse-card {
    border-radius: 18px;
    background: #fff;
    border: 1px solid rgba(13, 71, 161, 0.08);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.verse-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 45px rgba(13, 71, 161, 0.12);
}

/* =========================
   HEADER
========================= */
.verse-header {
    background: linear-gradient(135deg, #0d47a1, #1976d2);
    color: #fff;
    padding: 14px 18px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    position: relative;
    overflow: hidden;
}

.verse-header::after {
    content: "";
    position: absolute;
    top: -40%;
    right: -20%;
    width: 120px;
    height: 120px;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 50%;
}

.verse-title {
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.verse-icon {
    font-size: 18px;
    opacity: 0.9;
}

/* =========================
   BODY (IMPORTANT FIX HERE)
========================= */
.verse-body {
    padding: 28px 22px;
    min-height: 220px;
    background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);

    display: flex;
    align-items: center;
    justify-content: center;
}

/* =========================
   CONTENT
========================= */
.verse-content {
    text-align: center;
    max-width: 95%;
}

.verse-quote {
    font-size: 30px;
    color: rgba(13, 71, 161, 0.15);
    display: block;
    margin-bottom: 10px;
}

.verse-text {
    font-size: 15px;
    font-style: italic;
    color: #333;
    line-height: 1.7;
    margin-bottom: 16px;
}

/* =========================
   REFERENCE BADGE
========================= */
.verse-ref {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    color: #0d47a1;
    border: 1px solid rgba(13, 71, 161, 0.2);
    padding: 6px 12px;
    border-radius: 999px;
    background: rgba(13, 71, 161, 0.05);
}

/* =========================
   EMPTY STATE
========================= */
.verse-empty {
    text-align: center;
    color: #777;
}

.verse-empty-icon {
    font-size: 34px;
    color: rgba(13, 71, 161, 0.35);
    margin-bottom: 10px;
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 576px) {
    .verse-body {
        padding: 22px 16px;
    }

    .verse-text {
        font-size: 14px;
    }
}
</style>
    </main>

    <footer class="footer-main">
        <div class="container">
            <div class="row">
                <div class="col-md-3 footer-col">
                    <h6>Customer Service</h6>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Returns Policy</a></li>
                        <li><a href="#">Shipping & Delivery</a></li>
                        <li><a href="#">Feedback</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-3 footer-col">
                    <h6>About Us</h6>
                    <ul>
                        <li><a href="#">Get to know Oppasabuy</a></li>
                        <li><a href="#">Charitable Contributions</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="col-md-3 footer-col">
                    <h6>Membership</h6>
                    <ul>
                        <li><a href="#">Membership Benefits</a></li>
                        <li><a href="#">Join Now</a></li>
                    </ul>
                </div>
                <div class="col-md-3 footer-col">
                    <h6>Follow Us</h6>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram" style="color: #e4405f;"></i></a>
                        <a href="#"><i class="bi bi-tiktok" style="color: #000;"></i></a>
                        <a href="#"><i class="bi bi-youtube" style="color: #ff0000;"></i></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5 pt-3 border-top">
                <small class="text-muted">&copy; 2026 - Oppasabuy All Rights Reserved</small>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="homeAlertModal" tabindex="-1" aria-labelledby="homeAlertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content oppa-modal-content">
                <div class="modal-header oppa-modal-header justify-content-center">
                    <img src="{{ asset('oppa.png') }}" alt="Oppasabuy" height="40">
                </div>
                <div class="modal-body text-center py-4">
                    <h5 class="fw-bold mb-3" style="color: var(--oppa-blue);">Notice</h5>
                    <p class="mb-0">You are already on the Homepage.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="shop-now-btn px-5" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('home-link').addEventListener('click', function(e) {
            const currentPath = window.location.pathname;
            
            if (currentPath === '/' || currentPath === '/index.php' || currentPath === '' || currentPath.endsWith('/')) {
                e.preventDefault(); 
                var myModal = new bootstrap.Modal(document.getElementById('homeAlertModal'));
                myModal.show();
            }
        });
    </script>


  <script>
    // 1. Initial Map Setup
    var map = L.map('map').setView([14.35, 121.05], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let pickupMarker = null;
    let destinationMarker = null;
    let selectingPickup = true;
    
    // 2. Routing Control para sa linya sa mapa
    let routingControl = L.Routing.control({
        waypoints: [],
        createMarker: function() { return null; }, 
        lineOptions: { styles: [{ color: '#0d47a1', weight: 6 }] }
    }).addTo(map);

    // 3. Reverse Geocoding function
    async function getAddress(lat, lng) {
        try {
            let response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
            let data = await response.json();
            return data.display_name || (lat + ", " + lng);
        } catch (err) { return lat + ", " + lng; }
    }

    // 4. Map Click Logic
    map.on('click', async function (e) {
        let address = await getAddress(e.latlng.lat, e.latlng.lng);

        if (selectingPickup) {
            // I-update ang UI at Hidden Inputs para sa Pickup
            document.getElementById('pickup_lat').value = e.latlng.lat;
            document.getElementById('pickup_lng').value = e.latlng.lng;
            document.getElementById('pickup_address').value = address;

            if (pickupMarker) map.removeLayer(pickupMarker);
            pickupMarker = L.marker(e.latlng).addTo(map).bindPopup("Pickup").openPopup();
            
            selectingPickup = false;
            routingControl.setWaypoints([]); // I-reset ang ruta
        } else {
            // I-update ang UI at Hidden Inputs para sa Destination
            document.getElementById('dest_lat').value = e.latlng.lat;
            document.getElementById('dest_lng').value = e.latlng.lng;
            document.getElementById('destination_address').value = address;

            if (destinationMarker) map.removeLayer(destinationMarker);
            destinationMarker = L.marker(e.latlng).addTo(map).bindPopup("Destination").openPopup();
            
            // Dito gumuhit ng ruta dahil may 2 points na
            routingControl.setWaypoints([pickupMarker.getLatLng(), e.latlng]);
            
            selectingPickup = true;
        }
    });

    // 5. Fix for modal rendering
    var bookRiderModal = document.getElementById('bookRiderModal');
    bookRiderModal.addEventListener('shown.bs.modal', function () {
        map.invalidateSize();
    });

    // 6. Loading state handler
    document.getElementById('paskaayForm').addEventListener('submit', function() {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('btnText').classList.add('d-none');
        document.getElementById('loadingSpinner').classList.remove('d-none');
    });
</script>
</html>