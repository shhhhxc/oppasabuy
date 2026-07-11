<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Oppasabuy | OppaDriver Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .dashboard-bg {
            background:
                radial-gradient(circle at top left, rgba(13, 71, 161, 0.08), transparent 26%),
                linear-gradient(180deg, #f7f9fc 0%, #edf3f8 100%);
        }

        .soft-card {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
        }

        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.09);
        }

        .sidebar-link {
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: #eff6ff;
            color: #0d47a1;
            transform: translateX(3px);
        }

        .live-dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #22c55e;
            box-shadow: 0 0 0 6px rgba(34, 197, 94, 0.12);
            animation: pulseLive 1.8s infinite;
        }

        @keyframes pulseLive {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.15);
                opacity: 0.7;
            }
        }

        #map {
            min-height: 470px;
            width: 100%;
            border-radius: 24px;
        }

        .leaflet-routing-container {
            display: none !important;
        }

        .request-row {
            transition: background 0.2s ease;
        }

        .request-row:hover {
            background: #f8fbff;
        }

        @media (max-width: 1023px) {
            #map {
                min-height: 390px;
            }
        }
    </style>
</head>

<body class="dashboard-bg min-h-screen text-slate-900">

    <div class="min-h-screen flex">

        <aside class="hidden lg:flex w-72 bg-white/95 border-r border-slate-200 flex-col px-6 py-8 sticky top-0 h-screen backdrop-blur">
            <div class="flex items-center gap-3 mb-10">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0d47a1] to-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-100">
                    <i class="bi bi-car-front-fill text-xl"></i>
                </div>

                <div>
                    <h2 class="text-2xl font-black text-[#0d47a1] leading-none tracking-tight">
                        Oppasabuy
                    </h2>

                    <p class="mt-1 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                        OppaDriver Portal
                    </p>
                </div>
            </div>

            <nav class="space-y-2 flex-1">
                <a href="#"
                   class="sidebar-link flex items-center gap-3 px-4 py-4 bg-blue-50 text-[#0d47a1] rounded-2xl font-black">
                    <i class="bi bi-grid-fill"></i>
                    <span>Ride Pool</span>
                </a>
            </nav>

            <div class="rounded-3xl bg-slate-950 text-white p-5">
                <div class="flex items-center gap-2 text-emerald-400 text-xs font-black uppercase tracking-wider">
                    <span class="live-dot"></span>
                    Online
                </div>

                <p class="mt-3 text-sm font-semibold text-slate-300">
                    You are visible to nearby customers.
                </p>
            </div>
        </aside>

        <div class="flex-1 min-w-0">

            <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200">
                <div class="px-5 md:px-8 lg:px-12 py-5 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-blue-700 uppercase tracking-[0.25em]">
                            OppaDriver Central
                        </p>

                        <h1 class="mt-1 text-2xl font-black text-slate-900 tracking-tight">
                            Rider Dashboard
                        </h1>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-100 px-4 py-2 text-xs font-black text-emerald-700 uppercase tracking-wider">
                            <span class="live-dot"></span>
                            Online
                        </div>

                        <div class="w-11 h-11 rounded-2xl bg-blue-100 text-[#0d47a1] flex items-center justify-center font-black">
                            {{ strtoupper(substr(Auth::user()->name ?? 'R', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-5 md:p-8 lg:p-12">

                <section class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

                    <div class="stat-card soft-card rounded-[2rem] p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.18em]">
                                    Deliveries Today
                                </p>

                                <h2 class="mt-3 text-4xl font-black text-slate-900">
                                    {{ $deliveriesToday }}
                                </h2>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center">
                                <i class="bi bi-box-seam-fill text-xl"></i>
                            </div>
                        </div>

                        <p class="mt-4 text-xs font-semibold text-slate-500">
                            Completed and active delivery count
                        </p>
                    </div>

                    <div class="stat-card soft-card rounded-[2rem] p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.18em]">
                                    Earnings Today
                                </p>

                                <h2 class="mt-3 text-4xl font-black text-[#0d47a1]">
                                    ₱{{ number_format($earningsToday, 2) }}
                                </h2>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="bi bi-wallet2 text-xl"></i>
                            </div>
                        </div>

                        <p class="mt-4 text-xs font-semibold text-slate-500">
                            Current earnings from today's trips
                        </p>
                    </div>

                    <div class="stat-card rounded-[2rem] p-6 text-white shadow-xl shadow-blue-100 bg-gradient-to-br from-[#0d47a1] via-blue-700 to-indigo-800 relative overflow-hidden">
                        <div class="absolute -right-12 -top-12 w-36 h-36 rounded-full bg-white/10"></div>

                        <div class="relative flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-black text-blue-100 uppercase tracking-[0.18em]">
                                    Rider Rating
                                </p>

                                <h2 class="mt-3 text-4xl font-black">
                                    {{ Auth::user()->riderProfile->rating ?? '5.0' }}
                                    <span class="text-amber-300 text-2xl">★</span>
                                </h2>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center backdrop-blur">
                                <i class="bi bi-star-fill text-xl text-amber-300"></i>
                            </div>
                        </div>

                        <p class="relative mt-4 text-xs font-semibold text-blue-100">
                            Based on your completed customer ratings
                        </p>
                    </div>

                </section>

                @if(isset($activeBooking))
                    <section class="space-y-6">

                        <div class="soft-card rounded-[2.5rem] overflow-hidden">
                            <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <p class="text-[10px] font-black text-blue-700 uppercase tracking-[0.22em]">
                                        Active Assignment
                                    </p>

                                    <h3 class="mt-2 text-2xl font-black text-slate-900">
                                        Current Delivery
                                    </h3>

                                    <p class="mt-1 text-sm font-medium text-slate-500">
                                        Follow the route and update the delivery status when ready.
                                    </p>
                                </div>

                                <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 border border-emerald-100 px-4 py-2 text-xs font-black text-emerald-700 uppercase tracking-wider">
                                    <span class="live-dot"></span>
                                    {{ strtoupper(str_replace('_', ' ', $activeBooking->status)) }}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 xl:grid-cols-[1.5fr_0.7fr] gap-0">

                                <div class="p-5 md:p-6">
                                    <div class="relative">
                                        <div id="map"></div>

                                        <div class="absolute top-4 left-4 z-[500] bg-white/95 border border-slate-200 rounded-2xl px-4 py-3 shadow-lg backdrop-blur">
                                            <div class="flex items-center gap-2">
                                                <i class="bi bi-broadcast-pin text-blue-700"></i>

                                                <div>
                                                    <p class="text-xs font-black text-slate-900">
                                                        Live navigation
                                                    </p>

                                                    <p class="text-[10px] font-semibold text-slate-500">
                                                        Movement updates automatically
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t xl:border-t-0 xl:border-l border-slate-100 p-6 md:p-8 bg-slate-50/60">
                                    <div class="space-y-4">

                                        <div class="rounded-2xl bg-white border border-slate-200 p-4">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.16em]">
                                                Current Status
                                            </p>

                                            <p class="mt-2 font-black text-[#0d47a1]">
                                                {{ strtoupper(str_replace('_', ' ', $activeBooking->status)) }}
                                            </p>
                                        </div>

                                        <div class="rounded-2xl bg-white border border-slate-200 p-4">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.16em]">
                                                {{ $activeBooking->status == 'accepted' ? 'Pickup Location' : 'Destination' }}
                                            </p>

                                            <p class="mt-2 text-sm font-bold text-slate-700 leading-relaxed">
                                                {{ $activeBooking->status == 'accepted'
                                                    ? ($activeBooking->pickup_address ?? 'N/A')
                                                    : ($activeBooking->destination_address ?? 'N/A') }}
                                            </p>
                                        </div>

                                        <div class="rounded-2xl bg-white border border-slate-200 p-4">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.16em]">
                                                Fare
                                            </p>

                                            <p class="mt-2 text-2xl font-black text-emerald-600">
                                                ₱{{ number_format($activeBooking->fare ?? 0, 2) }}
                                            </p>
                                        </div>

                                        <form action="{{ route('rider.update.status', $activeBooking->id) }}?type={{ $activeBooking instanceof \App\Models\PaskaayRequest ? 'paskaay' : 'order' }}"
                                              method="POST">
                                            @csrf

                                            <button type="submit"
                                                    name="status"
                                                    value="{{ $activeBooking->status == 'accepted' ? 'picked_up' : 'completed' }}"
                                                    class="w-full rounded-2xl bg-[#0d47a1] text-white px-6 py-4 font-black text-sm uppercase tracking-wider hover:bg-slate-950 transition shadow-lg shadow-blue-100">
                                                <i class="bi {{ $activeBooking->status == 'accepted' ? 'bi-person-check-fill' : 'bi-check2-circle' }} me-2"></i>

                                                {{ $activeBooking->status == 'accepted'
                                                    ? 'Picked Up Customer'
                                                    : 'Complete Delivery' }}
                                            </button>
                                        </form>

                                        <button type="button"
                                                id="simulateBtn"
                                                data-id="{{ $activeBooking->id }}"
                                                class="w-full rounded-2xl border border-amber-200 bg-amber-50 text-amber-700 px-6 py-4 font-black text-sm uppercase tracking-wider hover:bg-amber-100 transition">
                                            <i class="bi bi-geo-alt-fill me-2"></i>
                                            Simulate Movement
                                        </button>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </section>

                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

                    <script>
                        var map = L.map('map').setView([14.35, 121.05], 15);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);

                        var routingControl = L.Routing.control({
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
                            show: false
                        }).addTo(map);

                        var riderTrail = L.polyline([], {
                            color: '#ef4444',
                            weight: 4,
                            dashArray: '10, 10'
                        }).addTo(map);

                        var riderIcon = L.divIcon({
                            className: '',
                            html: `
                                <div style="
                                    width: 48px;
                                    height: 48px;
                                    border-radius: 50%;
                                    background: #0d47a1;
                                    color: #ffffff;
                                    border: 4px solid #ffffff;
                                    box-shadow: 0 8px 20px rgba(13, 71, 161, 0.35);
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

                        var riderMarker = L.marker(
                            [14.35, 121.05],
                            { icon: riderIcon }
                        ).addTo(map);

                        var targetLat = {{ $activeBooking->status == 'accepted'
                            ? ($activeBooking->pickup_lat ?? 0)
                            : ($activeBooking->dest_lat ?? 0) }};

                        var targetLng = {{ $activeBooking->status == 'accepted'
                            ? ($activeBooking->pickup_lng ?? 0)
                            : ($activeBooking->dest_lng ?? 0) }};

                        var targetPos = L.latLng(targetLat, targetLng);

                        function updateMapUI(newLat, newLng) {
                            var startPos = riderMarker.getLatLng();
                            var endPos = L.latLng(newLat, newLng);

                            var steps = 20;
                            var i = 0;

                            var deltaLat = (endPos.lat - startPos.lat) / steps;
                            var deltaLng = (endPos.lng - startPos.lng) / steps;

                            var moveInterval = setInterval(function () {
                                if (i >= steps) {
                                    clearInterval(moveInterval);
                                    return;
                                }

                                var currentLat = startPos.lat + (deltaLat * i);
                                var currentLng = startPos.lng + (deltaLng * i);
                                var intermediatePos = L.latLng(currentLat, currentLng);

                                riderMarker.setLatLng(intermediatePos);
                                riderTrail.addLatLng(intermediatePos);

                                i++;
                            }, 50);

                            routingControl.setWaypoints([
                                endPos,
                                targetPos
                            ]);

                            map.fitBounds(
                                L.latLngBounds([
                                    endPos,
                                    targetPos
                                ]),
                                {
                                    padding: [50, 50]
                                }
                            );
                        }

                        document.getElementById('simulateBtn').addEventListener('click', function () {
                            let bookingId = this.getAttribute('data-id');

                            let lat = 14.59037005 + (Math.random() * 0.0005);
                            let lng = 120.94840050 + (Math.random() * 0.0005);

                            updateMapUI(lat, lng);

                            fetch(`/paskaay/update-location/${bookingId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    lat: lat,
                                    lng: lng
                                })
                            }).catch(error => {
                                console.error(error);
                            });
                        });

                        if (navigator.geolocation) {
                            navigator.geolocation.watchPosition(
                                function (position) {
                                    updateMapUI(
                                        position.coords.latitude,
                                        position.coords.longitude
                                    );

                                    fetch("{{ route('paskaay.update-location', $activeBooking->id) }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            lat: position.coords.latitude,
                                            lng: position.coords.longitude
                                        })
                                    }).catch(error => {
                                        console.error(error);
                                    });
                                },
                                function (error) {
                                    console.warn(
                                        'Geolocation unavailable:',
                                        error.message
                                    );
                                },
                                {
                                    enableHighAccuracy: true,
                                    timeout: 5000
                                }
                            );
                        }
                    </script>

                @else

                    <section class="soft-card rounded-[2.5rem] overflow-hidden">

                        <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-black text-blue-700 uppercase tracking-[0.22em]">
                                    Available Jobs
                                </p>

                                <h3 class="mt-2 text-2xl font-black text-slate-900">
                                    New Booking Requests
                                </h3>

                                <p class="mt-1 text-sm font-medium text-slate-500">
                                    Review nearby customer requests and accept the best trip.
                                </p>
                            </div>

                            <div class="rounded-full bg-slate-100 px-4 py-2 text-xs font-black text-slate-600 uppercase tracking-wider">
                                {{ count($allRequests) }} request{{ count($allRequests) === 1 ? '' : 's' }}
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[850px] text-left">

                                <thead>
                                    <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-[0.16em]">
                                        <th class="px-8 py-4">Customer</th>
                                        <th class="px-8 py-4">Service</th>
                                        <th class="px-8 py-4">Pickup Location</th>
                                        <th class="px-8 py-4">Fare</th>
                                        <th class="px-8 py-4 text-right">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">
                                    @forelse($allRequests as $item)
                                        @php
                                            $customerId = $item->buyer_id ?? $item->user_id;
                                            $customer = \App\Models\User::find($customerId);
                                        @endphp

                                        <tr class="request-row">
                                            <td class="px-8 py-6">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-11 h-11 rounded-2xl bg-blue-50 text-[#0d47a1] flex items-center justify-center font-black">
                                                        {{ strtoupper(substr($customer->full_name ?? $customer->name ?? 'U', 0, 1)) }}
                                                    </div>

                                                    <div>
                                                        <p class="font-black text-slate-900">
                                                            {{ $customer->full_name ?? $customer->name ?? 'Unknown User' }}
                                                        </p>

                                                        <p class="mt-1 text-xs font-semibold text-slate-400">
                                                            Customer request
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-8 py-6">
                                                <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3 py-2 text-xs font-black text-indigo-700">
                                                    <i class="bi {{ isset($item->product_name) ? 'bi-box-seam-fill' : 'bi-car-front-fill' }}"></i>

                                                    {{ isset($item->product_name) ? 'Pasabuy' : 'Pasakay' }}
                                                </span>
                                            </td>

                                            <td class="px-8 py-6">
                                                <div class="max-w-xs">
                                                    <p class="text-sm font-bold text-slate-700 leading-relaxed">
                                                        {{ $item->pickup_address ?? $item->address }}
                                                    </p>
                                                </div>
                                            </td>

                                            <td class="px-8 py-6">
                                                <span class="text-lg font-black text-emerald-600">
                                                    ₱{{ number_format($item->fare ?? 0, 2) }}
                                                </span>
                                            </td>

                                            <td class="px-8 py-6 text-right">
                                                <form action="{{ route('rider.accept.booking', $item->id) }}?type={{ $item instanceof \App\Models\PaskaayRequest ? 'paskaay' : 'order' }}"
                                                      method="POST">
                                                    @csrf

                                                    <button type="submit"
                                                            class="inline-flex items-center gap-2 bg-[#0d47a1] text-white px-5 py-3 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-slate-950 transition shadow-lg shadow-blue-100">
                                                        <i class="bi bi-check2-circle"></i>
                                                        Accept
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-24 text-center">
                                                <div class="w-20 h-20 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-3xl">
                                                    <i class="bi bi-inbox"></i>
                                                </div>

                                                <h4 class="mt-5 text-lg font-black text-slate-700">
                                                    No active requests
                                                </h4>

                                                <p class="mt-2 text-sm font-medium text-slate-400">
                                                    New nearby bookings will appear here automatically.
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </section>

                @endif

            </main>
        </div>
    </div>
</body>
</html>
