@extends('layouts.app')

@section('content') 
<div class = "container py-4"> 
<div class="row justify-content-center">
    <div class="col-md-5">

        <div class="hatid-header text-center mb-4">

            <div class="hatid-icon-wrapper mx-auto mb-3">
                <img
                    src="{{ asset('oppaexpress.png') }}"
                    alt="OppaExpress Logo"
                    class="hatid-logo"></div>

                <!-- Service Badges -->
                <div class="d-flex justify-content-center gap-2">

                    <span class="service-pill">
                        <i class="bi bi-lightning-fill"></i>
                        Fast Delivery
                    </span>

                    <span class="service-pill">
                        <i class="bi bi-shield-check"></i>
                        Safe & Secure
                    </span>

                </div>

            </div>

        </div>
    </div>

    <div class="card border-0 shadow-lg p-4 rounded-4">
        <ul
            class="nav nav-pills nav-justified mb-4 p-1 bg-light rounded-pill"
            id="hatidTabs"
            role="tablist">
            <li class="nav-item">
                <button
                    class="nav-link active rounded-pill fw-bold"
                    data-bs-toggle="pill"
                    data-bs-target="#pasabuy-tab"
                    type="button">
                    <i class="bi bi-box-seam me-1"></i>
                    Pasabuy
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link rounded-pill fw-bold"
                    id="pasakay-tab-btn"
                    data-bs-toggle="pill"
                    data-bs-target="#pasakay-tab"
                    type="button">
                    <i class="bi bi-car-front-fill me-1"></i>
                    Pasakay
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="pasabuy-tab">
                <form action="{{ route('pasabuy.request.submit') }}" method="POST">
                    @csrf

                    <input type="hidden" name="service_type" value="pasabuy">

                    @if($errors->any())
                        <div class="alert alert-danger rounded-4 border-0 mb-4">
                            <div class="fw-bold mb-2">
                                <i class="bi bi-exclamation-circle-fill me-1"></i>
                                Please fix the following:
                            </div>

                            <ul class="mb-0 ps-3 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Product Name</label>
                        <input
                            type="text"
                            name="product_name"
                            value="{{ old('product_name') }}"
                            class="form-control form-control-lg bg-light border-0 rounded-3"
                            required="required"></div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Product Type</label>
                            <input
                                type="text"
                                name="product_type"
                                class="form-control form-control-lg bg-light border-0 rounded-3"
                                value="{{ old('product_type') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Quantity</label>
                            <input
                                type="number"
                                name="quantity"
                                min="1"
                                value="{{ old('quantity', 1) }}"
                                class="form-control form-control-lg bg-light border-0 rounded-3"
                                required>
                        </div>

                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1">Delivery Address</label>
                                <textarea
                                    name="address"
                                    class="form-control bg-light border-0 rounded-3"
                                    style="height: 80px"
                                    required>{{ old('address') }}</textarea>
                            </div>
                            <button
                                type="submit"
                                class="btn btn-primary w-100 py-3 rounded-pill fw-bold"
                                style="background: var(--oppa-blue);">Inquire Now</button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="pasakay-tab">
                        <form id="paskaayForm" action="{{ route('rider.book.submit') }}" method="POST">
                            @csrf

                            <input type="hidden" id="pickup_lat" name="pickup_lat">
                                <input type="hidden" id="pickup_lng" name="pickup_lng">

                                    <input type="hidden" id="dest_lat" name="dest_lat">
                                        <input type="hidden" id="dest_lng" name="dest_lng">

                                            <div
                                                id="map"
                                                class="mb-3 shadow-sm"
                                                style="height: 300px; width: 100%; border-radius: 20px;"></div>

                                            <div class="input-group mb-2 position-relative">
                                                <span class="input-group-text bg-white border-0">
                                                    <i class="bi bi-geo-alt-fill text-primary"></i>
                                                </span>
                                                <input
                                                    type="text"
                                                    id="pickup_address"
                                                    name="pickup_address"
                                                    class="form-control bg-light border-0 rounded-3"
                                                    placeholder="Pickup Location"
                                                    autocomplete="off"></div>

                                                <div class="input-group mb-2 position-relative">
                                                    <span class="input-group-text bg-white border-0">
                                                        <i class="bi bi-flag-fill text-danger"></i>
                                                    </span>
                                                    <input
                                                        type="text"
                                                        id="destination_address"
                                                        name="destination_address"
                                                        class="form-control bg-light border-0 rounded-3"
                                                        placeholder="Destination"
                                                        autocomplete="off"></div>

                                                    <div class="d-flex justify-content-end mb-2">
                                                        <button
                                                            type="button"
                                                            id="clearMapBtn"
                                                            class="btn btn-sm btn-outline-danger rounded-pill">
                                                            <i class="bi bi-trash"></i>
                                                            Reset Locations
                                                        </button>
                                                    </div>

                                                    <h6 class="fw-bold mb-3">Select Vehicle</h6>

                                                    <div class="d-flex gap-3 mb-4">
                                                        <label
                                                            class="flex-fill p-3 border rounded-4 text-center cursor-pointer vehicle-card">
                                                            <input
                                                                type="radio"
                                                                name="vehicle_type"
                                                                value="motorcycle"
                                                                class="d-none"
                                                                required="required">
                                                                <i class="bi bi-bicycle d-block fs-3"></i>
                                                                <small class="fw-bold">Motorcycle</small>
                                                            </label>

                                                            <label
                                                                class="flex-fill p-3 border rounded-4 text-center cursor-pointer vehicle-card">
                                                                <input
                                                                    type="radio"
                                                                    name="vehicle_type"
                                                                    value="car"
                                                                    class="d-none"
                                                                    required="required">
                                                                    <i class="bi bi-car-front-fill d-block fs-3"></i>
                                                                    <small class="fw-bold">4-Seater</small>
                                                                </label>
                                                            </div>

                                                            <div
                                                                id="fareContainer"
                                                                class="card border-0 shadow-sm p-3 mb-4"
                                                                style="background: #f0f7ff; border-radius: 16px;">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <small
                                                                            class="text-primary fw-bold text-uppercase"
                                                                            style="letter-spacing: 1px; font-size: 0.7rem;">Estimated Fare</small>
                                                                        <h4 id="fareDisplay" class="m-0 fw-bold" style="color: var(--oppa-blue);">₱0</h4>
                                                                    </div>
                                                                    <div class="text-end">
                                                                        <span
                                                                            id="distDisplay"
                                                                            class="badge rounded-pill bg-white text-primary border"
                                                                            style="font-size: 0.75rem;">0 km</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="fare" id="final_fare">
                                                                <button
                                                                    id="submitBtn"
                                                                    type="submit"
                                                                    class="btn btn-primary w-100 py-3 rounded-pill fw-bold"
                                                                    style="background: var(--oppa-blue);">

                                                                    <span id="btnText">Confirm Booking</span>

                                                                    <span
                                                                        id="loadingSpinner"
                                                                        class="spinner-border spinner-border-sm d-none"
                                                                        role="status"
                                                                        aria-hidden="true"></span>
                                                                </button>
                                                            </form>
                                                                                                    <div id="searchingOverlay"
     class="{{ isset($paskaay) ? 'd-flex' : 'd-none' }} position-fixed top-0 start-0 w-100 h-100 flex-column align-items-center justify-content-center"
     style="z-index: 9999; background: rgba(246, 248, 251, 0.88); backdrop-filter: blur(6px);">

    <div class="searching-driver-card text-center bg-white shadow-lg border border-light"
         style="width: min(420px, 92vw); border-radius: 28px; overflow: hidden;">

        <div class="px-4 py-5 text-white"
             style="background: linear-gradient(135deg, #0d47a1, #1976d2);">
            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-white text-primary shadow"
                 style="width: 82px; height: 82px; font-size: 2rem;">
                <i class="bi bi-search"></i>
            </div>

            <p class="text-uppercase fw-bold mb-2"
               style="font-size: .72rem; letter-spacing: .18em; color: #dbeafe;">
                OppaExpress
            </p>

            <h3 class="fw-black mb-2">Finding your OppaDriver</h3>

            <p class="mb-0 text-white-50 small">
                Please keep this page open while we search nearby riders.
            </p>
        </div>

        <div class="p-4 p-md-5">
            <div class="spinner-border text-primary mb-3"
                 style="width: 3rem; height: 3rem;"
                 role="status"></div>

            <h5 class="fw-bold text-dark mb-2">
                Searching for nearby OppaDrivers...
            </h5>

            <p class="text-muted small mb-3">
                This will continue automatically even after a page refresh.
            </p>

            <div class="rounded-3 bg-light border px-3 py-3 mb-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1">
                    Booking ID
                </small>

                <span id="searchingBookingId"
                      class="fw-black text-primary">
                    {{ isset($paskaay) ? '#' . $paskaay->id : 'Creating booking...' }}
                </span>
            </div>

            <form id="cancelBookingForm"
                  action="{{ isset($paskaay) ? route('paskaay.cancel', $paskaay->id) : '#' }}"
                  method="POST">
                @csrf
                @method('DELETE')

                <button id="cancelBookingBtn"
                        type="submit"
                        onclick="return confirm('Sigurado ka bang gusto mong i-cancel ang booking na ito?')"
                        class="btn btn-outline-danger w-100 py-3 rounded-pill fw-bold"
                        {{ isset($paskaay) ? '' : 'disabled' }}>
                    <i class="bi bi-x-circle me-2"></i>
                    Cancel Booking
                </button>
            </form>
        </div>
    </div>
</div>

                                             <div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden pasabuy-modal">

            <div class="pasabuy-header"></div>

           <div class="text-center pt-4 pb-2">
    <img src="{{ asset('oppaexpress.png') }}" 
         alt="OppaExpress Logo" 
         style="width: 220px; height: auto; display: block; margin: 0 auto; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
</div>

            <div class="modal-body text-center px-4 py-3">
                <h5 class="fw-bold mb-2 text-dark">Login Required</h5>
                <p class="text-muted mb-0 small">
                    Please log in to continue using Pasabuy services and booking your delivery.
                </p>
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center pb-4 pt-0 gap-2">
                <a href="{{ route('login') }}" 
   class="btn px-4 rounded-pill fw-bold text-white" 
   style="background-color: var(--oppa-blue); border-color: var(--oppa-blue);">
   Login
</a>
                <button type="button" 
                        class="btn btn-outline-secondary rounded-pill px-4" 
                        data-bs-dismiss="modal">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
<div class="modal fade" id="oppaDriverFoundModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <div class="text-center text-white px-4 py-5" style="background: linear-gradient(135deg, #0d47a1, #1976d2);">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-white text-primary shadow" style="width: 84px; height: 84px; font-size: 2.5rem;">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <p class="text-uppercase fw-bold mb-2" style="font-size: .75rem; letter-spacing: .18em; color: #dbeafe;">Booking Accepted</p>
                <h2 class="fw-black mb-2">OppaDriver Found!</h2>
                <p class="mb-0 text-white-50">Your OppaDriver has accepted your booking.</p>
            </div>

            <div class="modal-body p-4">
                <div class="rounded-4 border bg-light p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white" style="width: 54px; height: 54px; font-size: 1.4rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-bold text-uppercase">Your OppaDriver</small>
                            <h5 id="oppaDriverName" class="fw-black text-dark mb-0">Your OppaDriver</h5>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <div class="bg-white rounded-3 border p-3 h-100">
                                <small class="text-muted fw-bold text-uppercase d-block mb-1">
                                    <i class="bi bi-car-front-fill me-1 text-primary"></i> Vehicle
                                </small>
                                <span id="oppaDriverVehicle" class="fw-bold text-dark">OppaDriver Vehicle</span>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="bg-white rounded-3 border p-3 h-100">
                                <small class="text-muted fw-bold text-uppercase d-block mb-1">
                                    <i class="bi bi-card-text me-1 text-primary"></i> Plate Number
                                </small>
                                <span id="oppaDriverPlate" class="fw-bold text-dark">To be confirmed</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
                    <p class="small fw-bold text-muted mb-1">Preparing live tracking...</p>
                    <small class="text-muted">Redirecting in <span id="oppaDriverCountdown">3</span> seconds</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
                                      .vehicle-card {
    transition: all 0.25s ease;
    cursor: pointer;
    border: 2px solid #e9ecef;
    border-radius: 16px;
    background: #fff;
}

/* Hover effect (important for modern feel) */
.vehicle-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
    border-color: #d0e6ff;
}

/* Selected state (your original idea, improved) */
.vehicle-card:has(input:checked) {
    border-color: var(--oppa-blue) !important;
    background: linear-gradient(180deg, #eaf3ff, #ffffff);
    color: var(--oppa-blue);
    box-shadow: 0 10px 22px rgba(13, 110, 253, 0.15);
}

/* Icon + text color sync when selected */
.vehicle-card:has(input:checked) i,
.vehicle-card:has(input:checked) small {
    color: var(--oppa-blue);
}

/* Optional: make icon smoother */
.vehicle-card i {
    transition: 0.2s ease;
}

/* NAV PILLS (keep yours but cleaner) */
.nav-pills .nav-link.active {
    background-color: var(--oppa-blue) !important;
    color: #fff !important;
    border-radius: 12px;
    box-shadow: 0 6px 14px rgba(13,110,253,0.25);
}
                                        .cursor-pointer {
                                            cursor: pointer;
                                        }
                                        .location-suggestions {
                                            position: absolute;
                                            background: white;
                                            width: 100%;
                                            z-index: 9999;
                                            border-radius: 10px;
                                            box-shadow: 0 5px 20px rgba(0,0,0,.15);
                                            max-height: 200px;
                                            overflow-y: auto;
                                        }

                                        .location-suggestions div {
                                            padding: 10px;
                                            cursor: pointer;
                                            font-size: 14px;
                                        }

                                        .location-suggestions div:hover {
                                            background: #f1f5ff;
                                        }

                                        #fareContainer {
                                            transition: all 0.3s ease;
                                            border: 2px solid transparent;
                                        }

                                        #fareContainer:hover {
                                            border-color: var(--oppa-blue);
                                            transform: translateY(-2px);
                                        }

                                        .hatid-header {
                                            position: relative;
                                        }

                                        .hatid-icon-wrapper {

                                            width: 500px;
                                            height: 180px;

                                            display: flex;
                                            align-items: center;
                                            justify-content: center;

                                            background: transparent;

                                            box-shadow: none;

                                            animation: floatTruck 3s ease-in-out infinite;
                                        }

                                        .hatid-logo {

                                            width: 900px;
                                            max-width: none;
                                            height: auto;

                                            object-fit: contain;

                                        }

                                        @keyframes floatTruck {

                                            0%,
                                            100% {
                                                transform: translateY(0);
                                            }

                                            50% {
                                                transform: translateY(-6px);
                                            }

                                        }

                                        /* Title */

                                        .hatid-title {

                                            font-size: 2rem;

                                            font-weight: 900;

                                            letter-spacing: -1px;

                                            background: linear-gradient(90deg, var(--oppa-blue), #00d2ff);

                                            -webkit-background-clip: text;

                                            -webkit-text-fill-color: transparent;

                                        }

                                        /* Subtitle */

                                        .hatid-subtitle {

                                            color: #6c757d;

                                            font-size: 0.95rem;

                                            font-weight: 500;

                                        }

                                        /* Pills */

                                        .service-pill {

                                            display: flex;

                                            align-items: center;

                                            gap: 6px;

                                            padding: 8px 14px;

                                            border-radius: 50px;

                                            background: white;

                                            border: 1px solid #e8edf5;

                                            font-size: 0.75rem;

                                            font-weight: 700;

                                            color: #555;

                                            box-shadow: 0 5px 15px rgba(0,0,0,.05);

                                        }

                                        .service-pill i {

                                            color: var(--oppa-blue);

                                        }
                                    </style>
                                    @endsection @push('scripts')
                                    <script
                                        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

                                    <script>
                                        // Define global map and routing variables
                                        let map,
                                            routingControl;
                                        let pickupMarker = null;
                                        let destinationMarker = null;
                                        let selectingPickup = true;

                                        document.addEventListener('DOMContentLoaded', function () {
                                            // Initialize Map
                                            map = L
                                                .map('map')
                                                .setView([
                                                    14.5995, 120.9842
                                                ], 13);

                                            L
                                                .tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                    maxZoom: 19,
                                                    attribution: '&copy; OpenStreetMap'
                                                })
                                                .addTo(map);

                                            // Initialize Routing
                                            routingControl = L
                                                .Routing
                                                .control({
                                                    waypoints: [],
                                                    createMarker: () => null
                                                })
                                                .addTo(map);

                                            // Initialize Input Suggestion Boxes
                                            createSuggestionBox(document.getElementById('pickup_address'), "pickup");
                                            createSuggestionBox(
                                                document.getElementById('destination_address'),
                                                "destination"
                                            );

                                            // Idagdag ito sa loob ng DOMContentLoaded para mag-recalculate kapag nagpalit
                                            // ng vehicle
                                            document
                                                .querySelectorAll('input[name="vehicle_type"]')
                                                .forEach(radio => {
                                                    radio.addEventListener('change', () => {
                                                        // I-re-run ang routing calculation kung may waypoints na
                                                        updateRouting();
                                                    });
                                                });
                                            // Ilagay ito sa loob ng DOMContentLoaded
                                            document
                                                .getElementById('clearMapBtn')
                                                .addEventListener('click', function () {
                                                    // 1. Tanggalin ang mga markers sa mapa
                                                    if (pickupMarker) 
                                                        map.removeLayer(pickupMarker);
                                                    if (destinationMarker) 
                                                        map.removeLayer(destinationMarker);
                                                    
                                                    // 2. I-reset ang variables
                                                    pickupMarker = null;
                                                    destinationMarker = null;
                                                    selectingPickup = true;

                                                    // 3. I-clear ang inputs
                                                    document
                                                        .getElementById('pickup_address')
                                                        .value = "";
                                                    document
                                                        .getElementById('destination_address')
                                                        .value = "";
                                                    document
                                                        .getElementById('pickup_lat')
                                                        .value = "";
                                                    document
                                                        .getElementById('pickup_lng')
                                                        .value = "";
                                                    document
                                                        .getElementById('dest_lat')
                                                        .value = "";
                                                    document
                                                        .getElementById('dest_lng')
                                                        .value = "";

                                                    // 4. I-clear ang routing (i-set ang waypoints sa empty array)
                                                    routingControl.setWaypoints([]);

                                                    // 5. I-reset ang fare display
                                                    document
                                                        .getElementById('fareDisplay')
                                                        .innerText = "Total Fare: ₱0";
                                                    document
                                                        .getElementById('final_fare')
                                                        .value = "";
                                                });

                                            routingControl.on('routesfound', function (e) {
                                                let routes = e.routes;
                                                let distanceKm = routes[0].summary.totalDistance / 1000;
                                                let travelTimeMinutes = routes[0].summary.totalTime / 60;

                                                let selectedVehicle = document.querySelector('input[name="vehicle_type"]:checked')
                                                    ?.value;
                                                let totalFare = 0;

                                                if (selectedVehicle === 'motorcycle') {
                                                    totalFare = 50 + (Math.max(0, distanceKm - 2) * 10);
                                                } else if (selectedVehicle === 'car') {
                                                    totalFare = 80 + (Math.max(0, distanceKm - 2) * 15) + (travelTimeMinutes * 2);
                                                }

                                                let finalPrice = Math.round(totalFare);

                                                // I-update ang UI
                                                document
                                                    .getElementById('fareDisplay')
                                                    .innerText = `₱${finalPrice}`;
                                                document
                                                    .getElementById('distDisplay')
                                                    .innerText = `${distanceKm.toFixed(1)} km`;
                                                document
                                                    .getElementById('final_fare')
                                                    .value = finalPrice;
                                            });
                                            // Map click handler
                                            map.on('click', async function (e) {
                                                const {lat, lng} = e.latlng;
                                                const address = await getAddress(lat, lng);

                                                if (selectingPickup) {
                                                    updateLocationInputs('pickup', lat, lng, address);
                                                    updateMarker('pickup', lat, lng);
                                                    selectingPickup = false;
                                                } else {
                                                    updateLocationInputs('destination', lat, lng, address);
                                                    updateMarker('destination', lat, lng);
                                                    updateRouting();
                                                    selectingPickup = true;
                                                }
                                            });

                                            // Tab fix for Leaflet
                                            document
                                                .getElementById('pasakay-tab-btn')
                                                .addEventListener('shown.bs.tab', () => {
                                                    setTimeout(() => map.invalidateSize(true), 300);
                                                });

                                           document.getElementById('paskaayForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // 1. I-check ang authentication status mula sa server/Blade
    const isAuthenticated = @json(auth()->check());

    if (!isAuthenticated) {
        // Ipakita ang Login Required Modal (Gamit ang Bootstrap 5)
        var myModal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
        myModal.show();
        return; // Pigilan ang pag-submit
    }

    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const overlay = document.getElementById('searchingOverlay');

    // 2. I-disable ang button at ipakita ang loading state
    submitBtn.disabled = true;
    btnText.innerText = "Searching...";
    overlay.style.display = '';
    overlay.classList.remove('d-none');
    overlay.classList.add('d-flex');

    let formData = new FormData(this);

    fetch("{{ route('rider.book.submit') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        // I-handle ang 401 Unauthorized (kung biglang na-expire ang session)
        if (response.status === 401) {
            window.location.href = "{{ route('login') }}";
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.status === 'success') {
            const bookingIdElement = document.getElementById('searchingBookingId');
            const cancelForm = document.getElementById('cancelBookingForm');
            const cancelButton = document.getElementById('cancelBookingBtn');

            if (bookingIdElement) {
                bookingIdElement.textContent = '#' + data.booking_id;
            }

            if (cancelForm) {
                const cancelUrl = "{{ route('paskaay.cancel', ['id' => ':id']) }}"
                    .replace(':id', data.booking_id);

                cancelForm.action = cancelUrl;
            }

            if (cancelButton) {
                cancelButton.disabled = false;
            }

            checkRiderStatus(data.booking_id);
        } else {
            handleErrorState(submitBtn, btnText, overlay);
            alert("Booking failed: " + (data?.message || "Please try again."));
        }
    })
    .catch(error => {
        handleErrorState(submitBtn, btnText, overlay);
        alert("Server error. Please try again.");
    });
});
                                            // Helper function para ibalik ang button sa normal kapag nag-error
                                            function handleErrorState(btn, text, overlay) {
                                                btn.disabled = false;
                                                text.innerText = "Confirm Booking";
                                                overlay
                                                    .classList
                                                    .add('d-none');
                                                overlay.classList.remove('d-flex');
                                            }

                                     
let oppaDriverModalAlreadyShown = false;

function showOppaDriverFound(data, trackingUrl) {
    if (oppaDriverModalAlreadyShown) {
        return;
    }

    oppaDriverModalAlreadyShown = true;

    document.getElementById('oppaDriverName').textContent =
        data.rider_name || 'Your OppaDriver';

    document.getElementById('oppaDriverVehicle').textContent =
        data.vehicle || 'OppaDriver Vehicle';

    document.getElementById('oppaDriverPlate').textContent =
        data.plate_number || 'To be confirmed';

    let secondsRemaining = 3;
    const countdownElement = document.getElementById('oppaDriverCountdown');
    countdownElement.textContent = secondsRemaining;

    const modalElement = document.getElementById('oppaDriverFoundModal');
    const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
    modalInstance.show();

    const countdownInterval = setInterval(() => {
        secondsRemaining -= 1;
        countdownElement.textContent = Math.max(secondsRemaining, 0);

        if (secondsRemaining <= 0) {
            clearInterval(countdownInterval);
            window.location.href = trackingUrl;
        }
    }, 1000);
}

let activeStatusPollingBookingId = null;

function checkRiderStatus(bookingId) {
    if (activeStatusPollingBookingId === bookingId) {
        return;
    }

    activeStatusPollingBookingId = bookingId;

    console.log("Checking status for booking: " + bookingId);

    // Gamitin ang Laravel route helper para siguradong tama ang URL path
    const statusUrl = "{{ route('paskaay.check.status', ['id' => ':id']) }}".replace(':id', bookingId);
    const trackingUrl = "{{ route('paskaay.tracking', ['id' => ':id']) }}".replace(':id', bookingId);

    const interval = setInterval(() => {
        fetch(statusUrl)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                console.log("Current status:", data.status);

                if (data.status === 'accepted') {
                    clearInterval(interval);
                    activeStatusPollingBookingId = null;

                    const overlay = document.getElementById('searchingOverlay');
                    if (overlay) {
                        overlay.classList.remove('d-flex');
                        overlay.classList.add('d-none');
                        overlay.style.display = 'none';
                    }

                    showOppaDriverFound(data, trackingUrl);
                }
            })
            .catch(err => {
                console.error("Error checking status:", err);
            });
    }, 3000); 
}
                                        });

                                        // Helper Functions Update this helper function
                                        function updateLocationInputs(type, lat, lng, addressName) {
                                            // 1. Update the Hidden Fields (Coordinates)
                                            const latField = document.getElementById(
                                                `${type === 'pickup'
                                                    ? 'pickup'
                                                    : 'dest'}_lat`
                                            );
                                            const lngField = document.getElementById(
                                                `${type === 'pickup'
                                                    ? 'pickup'
                                                    : 'dest'}_lng`
                                            );

                                            if (latField) 
                                                latField.value = lat;
                                            if (lngField) 
                                                lngField.value = lng;
                                            
                                            // 2. Update the Text Input (The human-readable name)
                                            const addressField = document.getElementById(`${type}_address`);
                                            if (addressField) {
                                                addressField.value = addressName; // This puts the name, not the numbers
                                            }
                                        }

                                        // Update your Map Click Handler
                                        map.on('click', async function (e) {
                                            const {lat, lng} = e.latlng;

                                            // Use a loading state or placeholder while fetching
                                            const type = selectingPickup
                                                ? 'pickup'
                                                : 'destination';
                                            document
                                                .getElementById(`${type}_address`)
                                                .value = "Fetching address...";

                                            const addressName = await getAddress(lat, lng);

                                            updateLocationInputs(type, lat, lng, addressName);
                                            updateMarker(type, lat, lng);

                                            if (!selectingPickup) {
                                                updateRouting();
                                            }

                                            selectingPickup = !selectingPickup;
                                        });

                                        function updateMarker(type, lat, lng) {
                                            if (type === 'pickup') {
                                                if (pickupMarker) 
                                                    map.removeLayer(pickupMarker);
                                                pickupMarker = L
                                                    .marker([lat, lng])
                                                    .addTo(map)
                                                    .bindPopup("Pickup")
                                                    .openPopup();
                                            } else {
                                                if (destinationMarker) 
                                                    map.removeLayer(destinationMarker);
                                                destinationMarker = L
                                                    .marker([lat, lng])
                                                    .addTo(map)
                                                    .bindPopup("Destination")
                                                    .openPopup();
                                            }
                                        }

                                        function updateRouting() {
                                            if (pickupMarker && destinationMarker) {
                                                routingControl.setWaypoints([
                                                    pickupMarker.getLatLng(),
                                                    destinationMarker.getLatLng()
                                                ]);
                                            }
                                        }

                                        async function searchLocation(query) {
                                            if (query.length < 3) 
                                                return [];
                                            const response = await fetch(`/search-location?q=${encodeURIComponent(query)}`);
                                            return await response.json();
                                        }

                                        // 3. Geocoding Utility (Fixed)
                                        async function getAddress(lat, lng) {
                                            try {
                                                // Nagdagdag tayo ng zoom at addressdetails para mas detalyado ang makuha
                                                let res = await fetch(
                                                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
                                                );
                                                let data = await res.json();

                                                // Dito natin chinecheck kung may address name, kung wala, ipakita ang "Location
                                                // Selected" sa halip na numbers
                                                if (data.display_name) {
                                                    return data.display_name;
                                                } else {
                                                    return "Location Selected";
                                                }
                                            } catch (err) {
                                                return "Location Selected";
                                            }
                                        }
                                        function createSuggestionBox(input, type) {
                                            let box = document.createElement('div');
                                            box.className = "location-suggestions";
                                            input
                                                .parentElement
                                                .appendChild(box);

                                            input.addEventListener('input', async function () {
                                                box.innerHTML = "";
                                                let results = await searchLocation(this.value);
                                                results.forEach(place => {
                                                    let item = document.createElement('div');
                                                    item.textContent = place.display_name;
                                                    item.onclick = function () {
                                                        input.value = place.display_name;
                                                        let lat = parseFloat(place.lat);
                                                        let lng = parseFloat(place.lon);
                                                        map.setView([
                                                            lat, lng
                                                        ], 16);
                                                        updateLocationInputs(type, lat, lng, place.display_name);
                                                        updateMarker(type, lat, lng);
                                                        if (type === 'destination') 
                                                            updateRouting();
                                                        box.innerHTML = "";
                                                    };
                                                    box.appendChild(item);
                                                });
    
                                        @isset($paskaay)
                                            checkRiderStatus({{ $paskaay->id }});
                                        @endisset
                                        });
                                        }
                                    </script>
                                    @endpush