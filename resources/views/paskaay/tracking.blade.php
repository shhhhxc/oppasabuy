@extends('layouts.app')

@section('content')

@php
    $rider = $paskaay->rider;
    $riderUser = $rider?->user;

    $riderName = $riderUser?->name
        ?? $riderUser?->full_name
        ?? 'Your OppaDriver';

    $vehicleType = $rider?->vehicle_type ?? 'Not specified';
    $vehicleBrand = $rider?->vehicle_brand ?? 'Not specified';
    $vehicleModel = $rider?->vehicle_model ?? 'Not specified';
    $vehicleColor = $rider?->vehicle_color ?? 'Not specified';

    $vehiclePlate = $rider?->plate_number
        ?? $rider?->vehicle_plate
        ?? $rider?->plate_no
        ?? 'Not specified';
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css">

<style>
    .tracking-page {
        min-height: 100vh;
        background:
            radial-gradient(circle at top left, rgba(13, 71, 161, 0.08), transparent 28%),
            linear-gradient(180deg, #f4f7fb 0%, #eef3f8 100%);
        padding: 32px 0 48px;
    }

    .tracking-shell {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .tracking-hero {
        background: linear-gradient(135deg, #0d47a1, #1976d2);
        border-radius: 28px;
        padding: 28px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(13, 71, 161, 0.18);
        margin-bottom: 24px;
    }

    .tracking-hero::before,
    .tracking-hero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.08);
    }

    .tracking-hero::before {
        width: 220px;
        height: 220px;
        right: -70px;
        top: -100px;
    }

    .tracking-hero::after {
        width: 140px;
        height: 140px;
        left: 35%;
        bottom: -90px;
    }

    .tracking-title {
        font-size: clamp(1.7rem, 3vw, 2.5rem);
        font-weight: 900;
        margin-bottom: 8px;
    }

    .tracking-subtitle {
        color: rgba(255,255,255,.78);
        margin-bottom: 0;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.25);
        padding: 10px 16px;
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 900;
        letter-spacing: .08em;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
    }

    .tracking-card {
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 24px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .tracking-card-header {
        padding: 22px 24px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tracking-card-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 900;
        color: #0f172a;
    }

    .driver-badge {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #eaf2ff, #f8fbff);
        color: #0d47a1;
        font-size: 1.5rem;
        border: 1px solid #dbeafe;
    }

    .detail-label {
        display: block;
        font-size: .68rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: #94a3b8;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: .95rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.45;
    }

    .detail-box {
        padding: 16px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
    }

    #map {
        height: 520px;
        width: 100%;
    }

    .map-overlay {
        position: absolute;
        top: 18px;
        left: 18px;
        z-index: 500;
        background: rgba(255,255,255,.95);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 12px 14px;
        box-shadow: 0 8px 24px rgba(15,23,42,.12);
        backdrop-filter: blur(10px);
    }

    .map-wrapper {
        position: relative;
    }

    .live-dot {
        width: 9px;
        height: 9px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 0 6px rgba(34,197,94,.12);
        animation: pulseLive 1.8s infinite;
    }

    @keyframes pulseLive {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.15); opacity: .7; }
    }

    .route-step {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .route-icon {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .route-icon.pickup {
        background: #e8f0ff;
        color: #0d47a1;
    }

    .route-icon.dropoff {
        background: #ffecec;
        color: #dc2626;
    }

    .route-line {
        width: 2px;
        height: 34px;
        background: #dbe3ee;
        margin: 4px 0 4px 16px;
    }

    @media (max-width: 991px) {
        #map {
            height: 420px;
        }
    }
</style>

<div class="tracking-page">
    <div class="tracking-shell">

        <div class="tracking-hero">
            <div class="position-relative d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="text-uppercase fw-bold mb-2" style="font-size:.75rem; letter-spacing:.18em; color:#dbeafe;">
                        OppaExpress Live Tracking
                    </div>
                    <h1 class="tracking-title">Your OppaDriver is on the way</h1>
                    <p class="tracking-subtitle">
                        Follow your driver in real time and stay updated until arrival.
                    </p>
                </div>

                <div class="status-pill">
                    <span class="live-dot"></span>
                    <span id="hero-status">{{ ucfirst(str_replace('_', ' ', $paskaay->status)) }}</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="tracking-card mb-4">
                    <div class="tracking-card-header">
                        <div>
                            <h5 class="tracking-card-title">OppaDriver Details</h5>
                            <small class="text-muted">Assigned rider and vehicle information</small>
                        </div>

                        <div class="driver-badge">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="driver-badge flex-shrink-0">
                                <i class="bi bi-person-fill"></i>
                            </div>

                            <div class="min-w-0">
                                <span class="detail-label">OppaDriver Name</span>
                                <div class="detail-value fs-5 text-truncate">
                                    {{ $riderName }}
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="detail-box">
                                    <span class="detail-label">
                                        <i class="bi bi-truck-front-fill me-1 text-primary"></i>
                                        Vehicle Type
                                    </span>
                                    <div class="detail-value">{{ $vehicleType }}</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="detail-box h-100">
                                    <span class="detail-label">
                                        <i class="bi bi-building me-1 text-primary"></i>
                                        Vehicle Brand
                                    </span>
                                    <div class="detail-value">{{ $vehicleBrand }}</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="detail-box h-100">
                                    <span class="detail-label">
                                        <i class="bi bi-car-front-fill me-1 text-primary"></i>
                                        Vehicle Model
                                    </span>
                                    <div class="detail-value">{{ $vehicleModel }}</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="detail-box h-100">
                                    <span class="detail-label">
                                        <i class="bi bi-palette-fill me-1 text-primary"></i>
                                        Vehicle Color
                                    </span>
                                    <div class="detail-value">{{ $vehicleColor }}</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="detail-box h-100">
                                    <span class="detail-label">
                                        <i class="bi bi-card-text me-1 text-primary"></i>
                                        Vehicle Plate
                                    </span>
                                    <div class="detail-value text-uppercase">{{ $vehiclePlate }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="border-top mt-4 pt-4">
                            <div class="detail-box mb-3">
                                <span class="detail-label">Booking Status</span>
                                <span id="status-badge" class="badge rounded-pill px-3 py-2 bg-success">
                                    {{ ucfirst(str_replace('_', ' ', $paskaay->status)) }}
                                </span>
                            </div>

                            <div class="row g-3">
                                <div class="col-7">
                                    <div class="detail-box h-100">
                                        <span class="detail-label">Total Fare</span>
                                        <div class="detail-value fs-5 text-primary">
                                            ₱{{ number_format($paskaay->fare ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-5">
                                    <div class="detail-box h-100">
                                        <span class="detail-label">Booking ID</span>
                                        <div class="detail-value">#{{ $paskaay->id }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <h5 class="tracking-card-title">Route Information</h5>
                        <i class="bi bi-signpost-split-fill text-primary fs-5"></i>
                    </div>

                    <div class="p-4">
                        <div class="route-step">
                            <div class="route-icon pickup">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <span class="detail-label">Pickup</span>
                                <div class="detail-value">
                                    {{ $paskaay->pickup_address ?? 'Pickup location' }}
                                </div>
                            </div>
                        </div>

                        <div class="route-line"></div>

                        <div class="route-step">
                            <div class="route-icon dropoff">
                                <i class="bi bi-flag-fill"></i>
                            </div>
                            <div>
                                <span class="detail-label">Destination</span>
                                <div class="detail-value">
                                    {{ $paskaay->destination_address }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <div>
                            <h5 class="tracking-card-title">Live OppaDriver Tracking</h5>
                            <small class="text-muted">Location refreshes every 3 seconds</small>
                        </div>

                        <div class="d-flex align-items-center gap-2 text-success fw-bold small">
                            <span class="live-dot"></span>
                            Live
                        </div>
                    </div>

                    <div class="map-wrapper">
                        <div class="map-overlay">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-broadcast-pin text-primary"></i>
                                <div>
                                    <div class="fw-bold text-dark small">Tracking active</div>
                                    <div class="text-muted" style="font-size:.72rem;">Waiting for latest location</div>
                                </div>
                            </div>
                        </div>

                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<script>
    const map = L.map('map').setView([14.35, 121.05], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const pickupPos = L.latLng(
        {{ $paskaay->pickup_lat ?? 14.35 }},
        {{ $paskaay->pickup_lng ?? 121.05 }}
    );

    const dropoffPos = L.latLng(
        {{ $paskaay->dest_lat ?? 14.35 }},
        {{ $paskaay->dest_lng ?? 121.05 }}
    );

    const routingControl = L.Routing.control({
        waypoints: [],
        createMarker: function () {
            return null;
        },
        lineOptions: {
            styles: [
                {
                    color: '#0d47a1',
                    weight: 6,
                    opacity: 0.85
                }
            ]
        },
        addWaypoints: false,
        draggableWaypoints: false,
        fitSelectedRoutes: true,
        show: false
    }).addTo(map);

    const riderIcon = L.divIcon({
        className: '',
        html: `
            <div style="
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background: #0d47a1;
                color: white;
                border: 4px solid white;
                box-shadow: 0 8px 20px rgba(13,71,161,.35);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
            ">
                <i class="bi bi-car-front-fill"></i>
            </div>
        `,
        iconSize: [48, 48],
        iconAnchor: [24, 24]
    });

    const riderMarker = L.marker(
        [14.35, 121.05],
        { icon: riderIcon }
    )
    .addTo(map)
    .bindPopup(`
        <div style="min-width: 190px;">
            <strong style="color:#0d47a1;">{{ addslashes($riderName) }}</strong><br>
            <span>{{ addslashes($vehicleBrand) }} {{ addslashes($vehicleModel) }}</span><br>
            <small>{{ addslashes($vehicleColor) }} · {{ addslashes($vehiclePlate) }}</small>
        </div>
    `);

    function updateStatusUI(status) {
        const readableStatus = status
            .replaceAll('_', ' ')
            .toUpperCase();

        const statusBadge = document.getElementById('status-badge');
        const heroStatus = document.getElementById('hero-status');

        if (statusBadge) {
            statusBadge.innerText = readableStatus;

            statusBadge.classList.remove(
                'bg-success',
                'bg-warning',
                'bg-primary',
                'bg-secondary'
            );

            if (status === 'accepted') {
                statusBadge.classList.add('bg-primary');
            } else if (status === 'picked_up') {
                statusBadge.classList.add('bg-warning', 'text-dark');
            } else if (status === 'completed') {
                statusBadge.classList.add('bg-success');
            } else {
                statusBadge.classList.add('bg-secondary');
            }
        }

        if (heroStatus) {
            heroStatus.innerText = readableStatus;
        }
    }

    function fetchRiderLocation() {
        fetch("{{ route('paskaay.get-location', $paskaay->id) }}")
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch rider location');
                }

                return response.json();
            })
            .then(data => {
                updateStatusUI(data.status);

                if (data.status === 'completed') {
                    window.location.href =
                        "{{ route('customer.receipt', $paskaay->id) }}";
                    return;
                }

                if (data.lat && data.lng) {
                    const riderPos = L.latLng(
                        parseFloat(data.lat),
                        parseFloat(data.lng)
                    );

                    riderMarker.setLatLng(riderPos);

                    if (data.status === 'accepted') {
                        routingControl.setWaypoints([
                            riderPos,
                            pickupPos
                        ]);
                    } else if (data.status === 'picked_up') {
                        routingControl.setWaypoints([
                            riderPos,
                            dropoffPos
                        ]);
                    }

                    const bounds = L.latLngBounds([
                        riderPos,
                        data.status === 'picked_up'
                            ? dropoffPos
                            : pickupPos
                    ]);

                    map.fitBounds(bounds, {
                        padding: [50, 50]
                    });
                }
            })
            .catch(error => {
                console.error(
                    'Error fetching rider location:',
                    error
                );
            });
    }

    setInterval(fetchRiderLocation, 3000);
    fetchRiderLocation();
</script>
@endsection
