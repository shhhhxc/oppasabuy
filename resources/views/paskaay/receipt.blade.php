@extends('layouts.app')

@section('content')
@php
    $rider = $paskaay->rider;
    $riderUser = $rider?->user;

    $riderName = $riderUser?->full_name
        ?? $riderUser?->name
        ?? 'Your OppaDriver';

    $vehicleName = trim(
        ($rider?->vehicle_brand ?? '') . ' ' . ($rider?->vehicle_model ?? '')
    );

    $vehicleName = $vehicleName !== ''
        ? $vehicleName
        : ($rider?->vehicle_type ?? 'OppaDriver Vehicle');

    $vehiclePlate = $rider?->vehicle_plate ?? 'Not specified';
@endphp

<div class="min-h-screen bg-[#eef3f8] py-10 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-2xl">

        <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl">

            <div class="relative overflow-hidden bg-gradient-to-br from-[#0d47a1] via-blue-700 to-indigo-900 px-6 py-10 text-center text-white sm:px-10">
                <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-20 left-10 h-52 w-52 rounded-full bg-cyan-300/10"></div>

                <div class="relative">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full border-4 border-white/25 bg-white text-5xl text-green-500 shadow-xl">
                        <i class="bi bi-check2-circle"></i>
                    </div>

                    <p class="mt-6 text-[11px] font-black uppercase tracking-[0.25em] text-blue-100">
                        Trip Successfully Completed
                    </p>

                    <h1 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">
                        Ride Completed!
                    </h1>

                    <p class="mt-3 text-sm font-medium text-blue-100">
                        Thank you for riding with Oppasabuy.
                    </p>
                </div>
            </div>

            <div class="p-6 sm:p-8">

                <div class="rounded-[1.75rem] border border-blue-100 bg-blue-50/70 p-6">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white text-2xl text-[#0d47a1] shadow-sm">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>

                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                    Your OppaDriver
                                </p>

                                <h2 class="mt-1 text-xl font-black text-slate-900">
                                    {{ $riderName }}
                                </h2>

                                <p class="mt-1 text-sm font-semibold text-slate-500">
                                    {{ $vehicleName }}
                                </p>

                                <p class="mt-1 text-xs font-bold uppercase tracking-wider text-slate-400">
                                    {{ $vehiclePlate }}
                                </p>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white px-5 py-4 text-left shadow-sm sm:text-right">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                Total Fare
                            </p>

                            <p class="mt-1 text-3xl font-black text-[#0d47a1]">
                                ₱{{ number_format($paskaay->fare ?? 0, 2) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="text-center">
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-slate-400">
                            Rate Your Experience
                        </p>

                        <h3 class="mt-2 text-2xl font-black text-slate-900">
                            How was your OppaDriver?
                        </h3>

                        <p class="mt-2 text-sm font-medium text-slate-500">
                            Your feedback helps us improve future rides.
                        </p>
                    </div>

                    <form action="{{ route('customer.rate', $paskaay->id) }}" method="POST" class="mt-7">
                        @csrf

                        <div class="rating-row flex flex-row-reverse justify-center gap-2 sm:gap-3">
                            @for($i = 5; $i >= 1; $i--)
                                <input
                                    type="radio"
                                    name="rating"
                                    value="{{ $i }}"
                                    id="star{{ $i }}"
                                    class="peer hidden"
                                    required
                                >

                                <label
                                    for="star{{ $i }}"
                                    title="{{ $i }} star{{ $i > 1 ? 's' : '' }}"
                                    class="rating-star flex h-12 w-12 cursor-pointer items-center justify-center rounded-full border-2 border-amber-300 bg-white text-2xl text-amber-400 transition hover:-translate-y-1 hover:border-amber-400 hover:bg-amber-400 hover:text-white sm:h-14 sm:w-14"
                                >
                                    ★
                                </label>
                            @endfor
                        </div>

                        @error('rating')
                            <p class="mt-4 text-center text-sm font-bold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2 text-sm font-bold text-slate-500">
                                <i class="bi bi-shield-check text-green-600"></i>
                                Your rating will be added to the OppaDriver's profile.
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="mt-6 flex w-full items-center justify-center gap-2 rounded-2xl bg-[#0d47a1] px-6 py-4 text-sm font-black uppercase tracking-[0.12em] text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-slate-950"
                        >
                            <i class="bi bi-star-fill"></i>
                            Submit Rating & Return Home
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
    .rating-row input:checked ~ label,
    .rating-row label:hover,
    .rating-row label:hover ~ label {
        background: #f59e0b;
        border-color: #f59e0b;
        color: #ffffff;
        transform: translateY(-4px);
    }

    .rating-row label {
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.08);
    }
</style>
@endsection
