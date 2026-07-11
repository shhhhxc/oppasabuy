@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <section class="promo-banner shadow-sm">
        <img src="{{ asset('oppamall.png') }}" alt="Banner" class="w-100" style="object-fit: cover; max-height: 400px;">
    </section>

    <section class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h4 class="fw-bolder text-uppercase text-dark" style="letter-spacing: 2px;">
                {{ $selectedCategory ?? 'Shop by Categories' }}
            </h4>
            <button class="btn btn-dark rounded-pill px-4 py-2 shadow-sm" id="seeAllBtn" onclick="showAllCategories()">View All Categories</button>
        </div>
        
        <div class="row g-4" id="categoryContainer">
            @foreach($categories as $index => $category)
                <div class="col-6 col-md-4 col-lg-3 category-item {{ $index >= 8 ? 'd-none' : '' }}">
                    {{-- Updated route to redirect to a dedicated category page --}}
                    <a href="{{ route('oppamall.category', ['categoryName' => $category['name']]) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 rounded-4 shadow-sm p-4 text-center transition-card bg-white position-relative overflow-hidden">
                            <div class="mb-3">
                                <i class="bi {{ $category['icon'] ?? 'bi-grid-3x3-gap' }} text-primary" style="font-size: 2.5rem;"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-3">{{ $category['name'] }}</h6>
                            
                            <div class="small text-muted text-start border-top pt-3">
                                @foreach(array_slice($category['subcategories'], 0, 5) as $sub)
                                    <div class="py-1 d-flex align-items-center">
                                        <i class="bi bi-chevron-right me-2 text-primary" style="font-size: 0.7rem;"></i> {{ $sub }}
                                    </div>
                                @endforeach
                                @if(count($category['subcategories']) > 5)
                                    <div class="text-primary fw-bold mt-2 small">+ {{ count($category['subcategories']) - 5 }} more</div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="container my-5">
        <h4 class="fw-bold text-uppercase mb-4 text-dark" style="letter-spacing: 1px;">Upcoming Events</h4>
        <div class="row g-4">
            @foreach($events as $event)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100 mx-auto transition-card" style="max-width: 400px; transition: transform 0.3s ease; border-top: 4px solid #0d6efd;">
                        <div style="height: 350px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 1rem 1rem 0 0;">
                            <img src="{{ asset('storage/' . $event->image_path) }}" class="card-img-top" alt="{{ $event->title }}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        </div>
                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bold mb-3 text-dark">{{ $event->title }}</h5>
                            <button type="button" class="btn btn-primary w-100 rounded-pill py-2 text-white border-0" style="background-color: #0d6efd;" data-bs-toggle="modal" data-bs-target="#eventModal{{ $event->id }}">
                                VIEW DETAILS
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="eventModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 shadow-lg">
                            <div class="modal-header border-0 bg-primary text-white rounded-top-4">
                                <h5 class="modal-title fw-bold">{{ $event->title }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center p-4">
                                <img src="{{ asset('storage/' . $event->image_path) }}" class="img-fluid rounded-3 mb-4">
                                <div class="bg-light p-3 rounded-3 border">
                                    <p class="mb-0 text-dark"><strong>Event Date:</strong> {{ $event->event_date ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>

<script>
    function showAllCategories() {
        document.querySelectorAll('.category-item.d-none').forEach(el => el.classList.remove('d-none'));
        document.getElementById('seeAllBtn').classList.add('d-none');
    }
</script>

<style>
    .transition-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .transition-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }
    .card { background: #ffffff; }
</style>
@endsection 