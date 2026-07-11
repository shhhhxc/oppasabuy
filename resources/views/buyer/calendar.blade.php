@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        {{-- Sidebar: Store Info --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; overflow: hidden;">
                <div class="card-body text-center p-4">
                    <div class="mb-4 text-start">
                        <label class="form-label fw-bold small text-uppercase">Select Store / Seller</label>
                        <select id="sellerSelect" class="form-select border-0 bg-light shadow-sm" style="border-radius: 12px; padding: 12px;">
                            @if($sellers->isEmpty())
                                <option value="">No Sellers Available</option>
                            @else
                                <option value="" disabled selected>-- Choose a Store --</option>
                              @foreach($sellers as $s)
    <option value="{{ $s->id }}" 
        data-store-name="{{ $s->sellerVerification->store_name ?? $s->full_name }}" 
        data-logo="{{ $s->sellerVerification && $s->sellerVerification->logo_path ? asset('storage/' . $s->sellerVerification->logo_path) : '' }}">
        {{ $s->sellerVerification->store_name ?? $s->full_name }}
    </option>
@endforeach
                            @endif
                        </select>
                    </div>

                    <hr class="my-4" style="opacity: 0.1;">

                    <div id="sellerInfoSection" class="d-flex flex-column align-items-center text-center">
                        <div class="mb-3 d-flex justify-content-center w-100">
                            <div style="width: 120px; height: 120px; overflow: hidden; display: flex; align-items: center; justify-content: center;" class="rounded-circle shadow-sm border bg-light">
                                <img id="sellerAvatar" src="https://ui-avatars.com/api/?name=SS&background=0f4c97&color=fff" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        
                        <h4 class="fw-bold mb-1" id="sellerNameDisplay">Select a Seller</h4>
                        <p class="text-muted small mb-2" id="sellerAddressDisplay"></p>
                        <div id="verificationBadgeContainer" class="mb-3">
                            <span class="badge bg-secondary rounded-pill px-3">No Seller Selected</span>
                        </div>
                        <p class="text-muted small px-3" id="instructionText">Choose a store to view their delivery schedule.</p>
                    </div>
                </div>
            </div>

            {{-- New Section: Distribution Details --}}
            <div id="distributionDetails" class="card border-0 shadow-sm d-none" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-uppercase small text-muted mb-3">Available Products for Delivery</h6>
                    <div id="productList" class="list-group list-group-flush">
                        <p class="text-muted small">Select a date on the calendar to see items available for delivery.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Calendar --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div id="buyerCalendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 22px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Confirm Delivery Slot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="reservationForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">
                    <input type="hidden" name="slot_id" id="modal_slot_id">
                    
                    <div class="p-3 mb-3 bg-light rounded-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Delivery Date:</span>
                            <span class="badge bg-primary rounded-pill" id="modal_remaining_badge"></span>
                        </div>
                        <h6 class="fw-bold mb-0" id="modal_display_date"></h6>
                    </div>

                    {{-- Product Selection --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Product for Delivery</label>
                        <select name="product_id" id="modalProductSelect" class="form-select border-0 bg-light p-3" style="border-radius: 12px;" required>
                            {{-- Options injected via JS --}}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Payment Proof (DP Receipt)</label>
                        <input type="file" name="payment_proof" class="form-control border-0 bg-light p-3" style="border-radius: 12px;" required accept="image/*">
                        <div class="form-text mt-2" style="font-size: 11px;">Upload your downpayment screenshot to secure this delivery date.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitBooking" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: #0f4c97;">Reserve Delivery</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('buyerCalendar');
    const sellerSelect = document.getElementById('sellerSelect');
    const productList = document.getElementById('productList');
    const modalProductSelect = document.getElementById('modalProductSelect');
    const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth' },
        height: 'auto',
        // This ensures the events are rendered as block elements which are easier to click
        eventDisplay: 'block', 
        
        eventClick: function(info) {
            const products = info.event.extendedProps.products || [];
            
            if (products.length > 0) {
                productList.innerHTML = products.map(p => `
                    <div class="list-group-item border-0 px-0 d-flex align-items-center">
                        <div class="bg-primary rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                        <span class="fw-bold text-dark">${p.name}</span>
                    </div>
                `).join('');
                
                modalProductSelect.innerHTML = '<option value="" disabled selected>-- Which product should be delivered? --</option>' + 
                    products.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
            } else {
                productList.innerHTML = '<p class="text-muted small">No items listed for delivery on this date.</p>';
                modalProductSelect.innerHTML = '<option value="">No products available</option>';
            }

            if (info.event.extendedProps.isFull) {
                alert("Delivery slots for this date are FULL.");
                return;
            }

            document.getElementById('modal_slot_id').value = info.event.id;
            document.getElementById('modal_display_date').innerText = info.event.start.toLocaleDateString('en-PH', { 
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
            });
            document.getElementById('modal_remaining_badge').innerText = info.event.extendedProps.remaining + " Slots Available";
            
            bookingModal.show();
        }
    });

    calendar.render();

    function updateSidebar() {
        const selectedOption = sellerSelect.options[sellerSelect.selectedIndex];
        
        if (!selectedOption || selectedOption.value === "") {
            document.getElementById('distributionDetails').classList.add('d-none');
            return;
        }

        document.getElementById('distributionDetails').classList.remove('d-none');
        
        // Use store-specific attributes from your dropdown
        const storeName = selectedOption.getAttribute('data-name'); // Ensure this matches your Blade attribute
        const logo = selectedOption.getAttribute('data-logo');
        
        document.getElementById('sellerNameDisplay').innerText = storeName;
        document.getElementById('sellerAvatar').src = logo ? logo : `https://ui-avatars.com/api/?name=${encodeURIComponent(storeName)}&background=0f4c97&color=fff`;

        // Clear existing events
        calendar.removeAllEventSources();
        
        // Fetch new slots
        const url = `/reserve/api/slots/${selectedOption.value}`;
        calendar.addEventSource(url);
    }

    sellerSelect.addEventListener('change', updateSidebar);

    // Auto-select if seller_id is in URL
    const urlParams = new URLSearchParams(window.location.search);
    const sellerIdFromUrl = urlParams.get('seller_id');
    if (sellerIdFromUrl) {
        sellerSelect.value = sellerIdFromUrl;
        updateSidebar();
    }

    // Reservation Form Submission
    document.getElementById('reservationForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('submitBooking');
        btn.disabled = true;
        btn.innerText = "Booking...";

        try {
            const response = await fetch("{{ route('reservations.store') }}", {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            if (data.success) {
                alert("Success! Your delivery is reserved.");
                calendar.refetchEvents();
                bookingModal.hide();
                this.reset();
            } else {
                alert("Error: " + data.message);
            }
        } catch (error) {
            alert("Connection error.");
        } finally {
            btn.disabled = false;
            btn.innerText = "Reserve Delivery";
        }
    });
});
</script>
<style>
    .fc-event { border-radius: 12px !important; padding: 4px 8px; border: none !important; transition: all 0.2s ease; cursor: pointer; }
    .fc-event:hover { filter: brightness(1.1); transform: translateY(-1px); }
    .fc-day-today { background-color: rgba(15, 76, 151, 0.05) !important; }
</style>
@endsection