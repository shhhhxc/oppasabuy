@extends('layouts.app')

@section('content')
<div class="bg-[#f6f8fb] min-h-screen">
    <div class="container py-5">
        <div class="row g-4">
            {{-- Left Side: Creation Form --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 25px;">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary-subtle p-2 rounded-3 me-3">
                            <i class="bi bi-calendar-plus text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Schedule Distribution</h5>
                    </div>

                    <form action="{{ route('seller.slots.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-black text-uppercase tracking-wide text-muted">Distribution Date</label>
                            <input type="date" name="date" class="form-control border-0 bg-light p-3"
                                   min="{{ date('Y-m-d') }}" style="border-radius: 12px;" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-black text-uppercase tracking-wide text-muted">Available Products</label>
                            <div class="bg-light p-3 rounded-3" style="max-height: 200px; overflow-y: auto; border-radius: 12px;">
                                @forelse($products as $product)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="product_ids[]" value="{{ $product->id }}" id="prod_{{ $product->id }}">
                                        <label class="form-check-label small fw-bold" for="prod_{{ $product->id }}">
                                            {{ $product->name }}
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-muted small mb-0">
                                        No products found.
                                        <a href="{{ route('seller.products.create', ['channel' => 'oppa_mall']) }}">
                                            Add one?
                                        </a>
                                    </p>
                                @endforelse
                            </div>
                            <div class="form-text mt-2" style="font-size: 11px;">Which items will you be distributing on this date?</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-black text-uppercase tracking-wide text-muted">Max Capacity</label>
                            <input type="number" name="available_slots" class="form-control border-0 bg-light p-3"
                                   value="20" min="1" style="border-radius: 12px;" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-black text-uppercase tracking-wide text-muted">Required Downpayment (₱)</label>
                            <div class="input-group bg-light rounded-3" style="border-radius: 12px; overflow: hidden;">
                                <span class="input-group-text border-0 bg-light fw-bold text-muted">₱</span>
                                <input type="number" name="dp_amount" class="form-control border-0 bg-light p-3"
                                       placeholder="0.00" step="0.01" style="box-shadow: none;" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-black py-3 shadow-sm"
                                style="background: #0d47a1; border-radius: 15px; border: none;">
                            ADD TO CALENDAR
                        </button>
                    </form>
                </div>
            </div>

            {{-- Right Side: Slot List --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 25px;">
                    <h5 class="fw-bold mb-4">Active Distribution Slots</h5>

                    @forelse($groupedSlots as $channel => $storeSlots)
                        <div class="d-flex align-items-center mt-4 mb-3">
                            <h6 class="text-primary fw-black text-uppercase tracking-wider small mb-0">
                                <i class="bi bi-shop me-2"></i> {{ str_replace('_', ' ', $channel) }}
                            </h6>
                            <div class="flex-grow-1 border-bottom border-primary ms-3 opacity-25"></div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-hover border-top">
                                <thead>
                                <tr class="text-muted small text-uppercase fw-black">
                                    <th class="py-3 px-3">Date & Products</th>
                                    <th class="py-3">Available</th>
                                    <th class="py-3 text-center">Downpayment</th>
                                    <th class="py-3 text-end">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($storeSlots as $slot)
                                    <tr class="align-middle">
                                        <td class="py-4 px-3">
                                            <div class="fw-black text-gray-900">{{ \Carbon\Carbon::parse($slot->date)->format('M d, Y') }}</div>
                                            <div class="d-flex flex-wrap gap-1 mt-1">
                                                @foreach($slot->products as $p)
                                                    <span class="badge bg-primary-subtle text-primary border-0 rounded-pill" style="font-size:9px;">{{ $p->name }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <span class="badge {{ $slot->available_slots > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-pill px-3 py-2 fw-black uppercase" style="font-size:10px;">
                                                {{ $slot->available_slots }} Left
                                            </span>
                                        </td>
                                        <td class="py-4 text-center fw-bold text-gray-900">₱{{ number_format($slot->dp_amount,2) }}</td>
                                        <td class="py-4 text-end">
                                            @if($slot->available_slots==0)
                                                <span class="text-danger small fw-black tracking-widest text-uppercase">FULL</span>
                                            @else
                                                <span class="text-success small fw-black tracking-widest text-uppercase">OPEN</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <p class="text-muted">No active distribution slots found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
