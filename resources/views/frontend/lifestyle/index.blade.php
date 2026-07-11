@extends('layouts.app')

@section('content')
<style>
    /* Hero Layout */
    .hero-section { background-color: #f8f9fa; padding: 60px 0; }
    .hero-collage { display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(2, 170px); gap: 12px; min-height: 352px; }
    .hero-collage img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    
    /* Category Section - WHITE BACKGROUND */
    .cat-section { background-color: #ffffff; padding: 50px 0; }
    .cat-item { text-align: center; color: #333; transition: 0.3s; width: 100px; cursor: pointer; }
    .cat-item:hover { transform: translateY(-5px); }
    .cat-icon-circle { 
        width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; 
        margin: 0 auto 10px; font-size: 1.5rem; background: #f8f9fa; border: 1px solid #eee;
    }
    .bg-beauty { background-color: #fff5f5; color: #dc3545; }
    .bg-health { background-color: #f0fff4; color: #198754; }
    .bg-lifestyle { background-color: #fffaf0; color: #fd7e14; }
    .bg-home { background-color: #f0f7ff; color: #0d6efd; }
    .bg-construction { background-color: #fffdf0; color: #ffc107; }
    .bg-fitness { background-color: #f4fff0; color: #51963e; }
    .bg-cleaning { background-color: #f5f0ff; color: #6f42c1; }
    .bg-more { background-color: #f8f9fa; color: #6c757d; }

    /* Featured Services - TRANSPARENT BACKGROUND */
    .featured-section { background-color: transparent; padding: 60px 0; }
    .featured-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }
    .service-card { border: 1px solid #e0e0e0; background: #ffffff; border-radius: 12px; transition: 0.3s; overflow: hidden; }
    .service-card:hover { box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .img-container { aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa; }
    
    /* How it works */
    .how-it-works { background: #f8f9fa; padding: 60px 0; }
    .step-box { text-align: center; padding: 20px; }
</style>
<style>
    /* Slider Container */
    .overflow-auto {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .overflow-auto::-webkit-scrollbar {
        display: none; /* Hide scrollbar for Chrome, Safari and Opera */
    }
    
    /* Ensure items don't get squished */
    .cat-item.flex-shrink-0 {
        width: 80px; 
    }
</style>

<style>
    /* Modal Card Enhancements */
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 10px 15px rgba(0,0,0,0.05) !important; 
    }
    
    .hover-text-success:hover { 
        color: #198754 !important; 
        font-weight: 500;
        padding-left: 5px;
        transition: 0.2s;
    }

    /* Make modal header cleaner */
    .modal-header { border-bottom: 1px solid #f0f0f0; background: #fafafa; }
</style>

<div class="lifestyle-hub">
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold" style="font-size: 3.5rem; color: #222;">All the services you need,<br><span class="text-success">ALL IN ONE PLACE</span></h1>
                    <p class="mt-4 text-muted fs-5">Find trusted professionals for your personal care, health, home and more.</p>
                </div>
                <div class="col-md-6 hero-collage">
                    @for($i = 1; $i <= 6; $i++)
                        <img src="https://picsum.photos/400/300?random={{ $i }}" alt="Lifestyle Service">
                    @endfor
                </div>
            </div>
        </div>
    </section>

   <section class="cat-section py-4">
        <div class="container position-relative">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold m-0">Browse by Category</h4>
                <a href="#" data-bs-toggle="modal" data-bs-target="#moreCategoriesModal" 
                   class="text-success text-decoration-none fw-bold">See more &rarr;</a>
            </div>

            <div class="position-relative">
                <button class="btn btn-light shadow-sm position-absolute start-0 top-50 translate-middle-y" 
                        onclick="scrollSlider('left')" style="border-radius: 50%; width: 40px; height: 40px; margin-left: -20px; z-index: 10;">
                    <i class="bi bi-chevron-left"></i>
                </button>

                @php
                    $categoryIcons = [
                        'Beauty & Personal Care Services' => ['icon' => 'bi-scissors', 'class' => 'bg-beauty'],
                        'Health & Medical Services' => ['icon' => 'bi-heart-pulse', 'class' => 'bg-health'],
                        'Wellness, Fitness & Coaching' => ['icon' => 'bi-person-arms-up', 'class' => 'bg-fitness'],
                        'Education & Training Services' => ['icon' => 'bi-mortarboard', 'class' => 'bg-lifestyle'],
                        'Pet Care & Veterinary Services' => ['icon' => 'bi-heart', 'class' => 'bg-beauty'],
                        'Construction, Engineering & Home Services' => ['icon' => 'bi-tools', 'class' => 'bg-construction'],
                        'Repair & Maintenance Services' => ['icon' => 'bi-wrench-adjustable', 'class' => 'bg-home'],
                        'Printing, Digital & Creative Services' => ['icon' => 'bi-printer', 'class' => 'bg-lifestyle'],
                        'Photography, Media & Production' => ['icon' => 'bi-camera', 'class' => 'bg-more'],
                        'Wedding & Events Services' => ['icon' => 'bi-balloon-heart', 'class' => 'bg-beauty'],
                        'Food & Hospitality Services' => ['icon' => 'bi-cup-hot', 'class' => 'bg-lifestyle'],
                        'Real Estate & Property Services' => ['icon' => 'bi-house-door', 'class' => 'bg-home'],
                        'Rental & Leasing Services' => ['icon' => 'bi-key', 'class' => 'bg-construction'],
                        'Transportation & Logistics' => ['icon' => 'bi-truck', 'class' => 'bg-home'],
                        'Financial, Legal & Government' => ['icon' => 'bi-bank', 'class' => 'bg-more'],
                        'Technology & IT Services' => ['icon' => 'bi-laptop', 'class' => 'bg-home'],
                        'Community & Lifestyle Services' => ['icon' => 'bi-people', 'class' => 'bg-health'],
                        'Retail & Commercial' => ['icon' => 'bi-shop', 'class' => 'bg-lifestyle'],
                        'Agricultural & Environmental' => ['icon' => 'bi-tree', 'class' => 'bg-fitness'],
                        'Specialized & Emerging Services' => ['icon' => 'bi-stars', 'class' => 'bg-more'],
                    ];
                @endphp

                <div id="categorySlider" class="d-flex flex-nowrap overflow-auto gap-4 px-2">
                    @foreach($categories as $parent => $subs)
                        @php
                            $categoryVisual = $categoryIcons[$parent] ?? [
                                'icon' => 'bi-grid',
                                'class' => 'bg-more',
                            ];
                        @endphp

                        <div class="cat-item flex-shrink-0" style="width: 100px;">
                            <a href="{{ route('lifestyle.category', ['categoryName' => $parent]) }}"
                               class="cat-item flex-shrink-0 text-decoration-none">
                                <div class="cat-icon-circle {{ $categoryVisual['class'] }}">
                                    <i class="bi {{ $categoryVisual['icon'] }}"></i>
                                </div>
                                <small class="fw-bold d-block">{{ Str::limit($parent, 18) }}</small>
                            </a>
                        </div>
                    @endforeach
                </div>

                <button class="btn btn-light shadow-sm position-absolute end-0 top-50 translate-middle-y" 
                        onclick="scrollSlider('right')" style="border-radius: 50%; width: 40px; height: 40px; margin-right: -20px; z-index: 10;">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <div class="modal fade" id="moreCategoriesModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold">All Service Categories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        @foreach($categories as $parent => $subs)
                            @php
                                $categoryVisual = $categoryIcons[$parent] ?? [
                                    'icon' => 'bi-grid',
                                    'class' => 'bg-more',
                                ];
                            @endphp

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card h-100 border-0 shadow-sm transition-hover">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="cat-icon-circle {{ $categoryVisual['class'] }} m-0"
                                                 style="width: 42px; height: 42px; font-size: 1rem;">
                                                <i class="bi {{ $categoryVisual['icon'] }}"></i>
                                            </div>
                                            <h6 class="fw-bold text-success text-uppercase mb-0"
                                                style="font-size: 0.8rem;">
                                                {{ $parent }}
                                            </h6>
                                        </div>

                                        <ul class="list-unstyled mb-0">
                                            @foreach($subs as $sub)
                                                <li class="mb-2">
                                                    <a href="{{ route('lifestyle.category', ['categoryName' => $parent, 'subcategory[]' => $sub->name]) }}"
                                                       class="text-decoration-none text-muted hover-text-success small d-flex align-items-center gap-2">
                                                        <i class="bi bi-chevron-right text-success"></i>
                                                        <span>{{ $sub->name }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="featured-section">
        <div class="container">
            <div class="text-center mb-4">
                <span class="text-success fw-bold text-uppercase" style="letter-spacing: 2px; font-size: 0.8rem;">Looking for Services?</span>
                <h2 class="display-5 fw-extrabold text-dark mt-2">Featured Services</h2>
                <div class="h-1 w-25 bg-success rounded-full mx-auto mt-3"></div>
            </div>
            
            <div class="featured-grid">
                @foreach($products as $product)
                    <div class="service-card shadow-sm">
                        <div class="img-container">
                            <img src="{{ asset('storage/' . $product->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="p-3">
                            <h6 class="fw-bold mb-1 text-truncate">{{ $product->name }}</h6>
                            <p class="text-success fw-bold mb-3">₱{{ number_format($product->price, 2) }}</p>
                            <div class="d-grid">
                                <a href="{{ route('reservations.index', ['product_id' => $product->id]) }}"
   class="btn btn-outline-success fw-bold d-flex align-items-center justify-content-center gap-2">
    <i class="bi bi-calendar-check"></i>
    <span>Book Now</span>
</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="how-it-works">
        <div class="container">
            <h3 class="text-center mb-5 fw-bold" style="color: #333;">How it Works?</h3>
            <div class="row text-center">
                <div class="col-md-3 mb-4"><div class="d-flex align-items-center justify-content-center mb-3"><i class="bi bi-search fs-1 text-success me-2"></i><span class="fs-3 fw-bold text-success">1</span></div><h5 class="fw-bold">Search</h5><p class="text-muted small">Find the service you need</p></div>
                <div class="col-md-3 mb-4"><div class="d-flex align-items-center justify-content-center mb-3"><i class="bi bi-calendar-check fs-1 text-success me-2"></i><span class="fs-3 fw-bold text-success">2</span></div><h5 class="fw-bold">Choose & Book</h5><p class="text-muted small">Pick your preferred provider and schedule</p></div>
                <div class="col-md-3 mb-4"><div class="d-flex align-items-center justify-content-center mb-3"><i class="bi bi-credit-card fs-1 text-success me-2"></i><span class="fs-3 fw-bold text-success">3</span></div><h5 class="fw-bold">Pay Securely</h5><p class="text-muted small">Enjoy safe and easy payment options</p></div>
                <div class="col-md-3 mb-4"><div class="d-flex align-items-center justify-content-center mb-3"><i class="bi bi-emoji-smile fs-1 text-success me-2"></i><span class="fs-3 fw-bold text-success">4</span></div><h5 class="fw-bold">Enjoy the Service</h5><p class="text-muted small">Sit back and enjoy quality service</p></div>
            </div>
        </div>
    </section>
</div>

<script>
    function scrollSlider(direction) {
        const slider = document.getElementById('categorySlider');
        const scrollAmount = 300; // How far to scroll per click
        if (direction === 'left') {
            slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
</script>
@endsection