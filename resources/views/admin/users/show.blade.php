@extends('layouts.admin')

@section('content')
@php
    $displayName = $user->full_name ?? $user->name ?? 'Unknown User';
    $role = strtolower($user->role ?? 'buyer');
    $accountStatus = strtolower($user->account_status ?? 'active');
@endphp

<div class="max-w-6xl mx-auto pb-20">
    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a
                href="{{ route('admin.users') }}"
                class="mb-3 inline-flex items-center text-xs font-black uppercase tracking-wider text-gray-400 hover:text-gray-700"
            >
                <i class="bi bi-arrow-left mr-2"></i>
                Back to users
            </a>

            <h1 class="text-3xl font-black text-gray-900">
                {{ $displayName }}
            </h1>

            <p class="mt-1 text-sm font-medium text-gray-500">
                {{ $user->email }}
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <span class="rounded-xl bg-gray-100 px-4 py-2 text-xs font-black uppercase text-gray-600">
                {{ $role }}
            </span>

            <span class="rounded-xl px-4 py-2 text-xs font-black uppercase
                {{ $accountStatus === 'active' ? 'bg-green-50 text-green-700' : '' }}
                {{ $accountStatus === 'locked' ? 'bg-amber-50 text-amber-700' : '' }}
                {{ $accountStatus === 'disabled' ? 'bg-red-50 text-red-700' : '' }}
            ">
                {{ $accountStatus }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="space-y-8 lg:col-span-2">
            <div class="rounded-[2rem] border border-gray-100 bg-white p-8 shadow-sm">
                <h2 class="mb-6 text-xl font-black text-gray-900">
                    Account Information
                </h2>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="rounded-2xl bg-gray-50 p-5">
                        <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                            Full Name
                        </span>
                        <p class="mt-2 font-bold text-gray-800">
                            {{ $displayName }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-gray-50 p-5">
                        <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                            Email
                        </span>
                        <p class="mt-2 break-words font-bold text-gray-800">
                            {{ $user->email }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-gray-50 p-5">
                        <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                            Registered
                        </span>
                        <p class="mt-2 font-bold text-gray-800">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y h:i A') }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-gray-50 p-5">
                        <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                            Verification Status
                        </span>
                        <p class="mt-2 font-bold text-gray-800">
                            {{ strtoupper($user->verification_status ?? 'Not verified') }}
                        </p>
                    </div>
                </div>
            </div>

            @if($role === 'seller' && $user->sellerVerification)
                <div class="rounded-[2rem] border border-gray-100 bg-white p-8 shadow-sm">
                    <h2 class="mb-6 text-xl font-black text-gray-900">
                        Seller Information and Documents
                    </h2>

                    <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="rounded-2xl bg-gray-50 p-5">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Store Name
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ $user->sellerVerification->store_name ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-5">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Plan
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ strtoupper($user->sellerVerification->plan ?? 'N/A') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        @if($user->sellerVerification->document_path)
                            <a
                                href="{{ asset('storage/' . $user->sellerVerification->document_path) }}"
                                target="_blank"
                                class="rounded-2xl border border-gray-100 bg-gray-50 p-5 text-center text-sm font-black text-gray-700 hover:bg-gray-100"
                            >
                                View Valid ID
                            </a>
                        @endif

                        @if($user->sellerVerification->video_path)
                            <a
                                href="{{ asset('storage/' . $user->sellerVerification->video_path) }}"
                                target="_blank"
                                class="rounded-2xl bg-blue-600 p-5 text-center text-sm font-black text-white hover:bg-blue-700"
                            >
                                View Video Proof
                            </a>
                        @endif

                        @if($user->sellerVerification->logo_path)
                            <a
                                href="{{ asset('storage/' . $user->sellerVerification->logo_path) }}"
                                target="_blank"
                                class="rounded-2xl border border-gray-100 bg-gray-50 p-5 text-center text-sm font-black text-gray-700 hover:bg-gray-100"
                            >
                                View Store Media
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            @if($role === 'buyer')
                <div class="rounded-[2rem] border border-gray-100 bg-white p-8 shadow-sm">
                    <h2 class="mb-6 text-xl font-black text-gray-900">
                        Buyer Documents
                    </h2>

                    @php
                        $buyerVerification = $user->buyerVerification;
                        $buyerDocument = $buyerVerification->id_path
                            ?? $buyerVerification->document_path
                            ?? null;
                    @endphp

                    @if($buyerVerification)
                        <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div class="rounded-2xl bg-gray-50 p-5">
                                <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                    ID Type
                                </span>
                                <p class="mt-2 font-bold text-gray-800">
                                    {{ $buyerVerification->id_type ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-gray-50 p-5">
                                <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                    Status
                                </span>
                                <p class="mt-2 font-bold text-gray-800">
                                    {{ strtoupper($buyerVerification->status ?? 'pending') }}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($buyerDocument)
                        <a
                            href="{{ asset('storage/' . $buyerDocument) }}"
                            target="_blank"
                            class="inline-block rounded-2xl bg-blue-600 px-6 py-4 text-sm font-black text-white hover:bg-blue-700"
                        >
                            View Valid ID
                        </a>
                    @else
                        <p class="text-sm font-medium text-gray-400">
                            No buyer document is available.
                        </p>
                    @endif
                </div>
            @endif

            @if($role === 'rider' && $user->rider)
                <div class="rounded-[2rem] border border-gray-100 bg-white p-8 shadow-sm">
                    <h2 class="mb-6 text-xl font-black text-gray-900">
                        Rider and Vehicle Information
                    </h2>

                    <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="rounded-2xl bg-gray-50 p-5">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Vehicle Type
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ $user->rider->vehicle_type ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-5">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Vehicle
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ trim(($user->rider->vehicle_brand ?? '') . ' ' . ($user->rider->vehicle_model ?? '')) ?: 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-5">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Vehicle Color
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ $user->rider->vehicle_color ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-5">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Plate Number
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ $user->rider->vehicle_plate ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-5">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                License Number
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ $user->rider->license_number ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-5">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                License Expiration
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ $user->rider->license_expiration ? \Carbon\Carbon::parse($user->rider->license_expiration)->format('F d, Y') : 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-5">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Phone
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ $user->rider->phone ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-5">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Birth Date
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ $user->rider->birth_date ? \Carbon\Carbon::parse($user->rider->birth_date)->format('F d, Y') : 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-5 sm:col-span-2">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Address
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ $user->rider->address ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-5 sm:col-span-2">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Emergency Contact
                            </span>
                            <p class="mt-2 font-bold text-gray-800">
                                {{ $user->rider->emergency_contact_name ?? 'N/A' }}
                                @if($user->rider->emergency_contact_number)
                                    • {{ $user->rider->emergency_contact_number }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        @if($user->rider->license_img)
                            <a
                                href="{{ asset('storage/' . $user->rider->license_img) }}"
                                target="_blank"
                                class="rounded-2xl border border-gray-100 bg-gray-50 p-5 text-center text-sm font-black text-gray-700 hover:bg-gray-100"
                            >
                                Driver License
                            </a>
                        @endif

                        @if($user->rider->orcr_img)
                            <a
                                href="{{ asset('storage/' . $user->rider->orcr_img) }}"
                                target="_blank"
                                class="rounded-2xl border border-gray-100 bg-gray-50 p-5 text-center text-sm font-black text-gray-700 hover:bg-gray-100"
                            >
                                OR/CR
                            </a>
                        @endif

                        @if($user->rider->vehicle_photo)
                            <a
                                href="{{ asset('storage/' . $user->rider->vehicle_photo) }}"
                                target="_blank"
                                class="rounded-2xl bg-green-600 p-5 text-center text-sm font-black text-white hover:bg-green-700"
                            >
                                Vehicle Photo
                            </a>
                        @endif

                        @if($user->rider->selfie_license)
                            <a
                                href="{{ asset('storage/' . $user->rider->selfie_license) }}"
                                target="_blank"
                                class="rounded-2xl border border-gray-100 bg-gray-50 p-5 text-center text-sm font-black text-gray-700 hover:bg-gray-100"
                            >
                                Selfie With License
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div>
            <div class="sticky top-8 rounded-[2rem] border border-gray-100 bg-white p-8 shadow-sm">
                <h2 class="mb-6 text-xl font-black text-gray-900">
                    Account Controls
                </h2>

                @if($role === 'admin')
                    <div class="rounded-2xl border border-red-100 bg-red-50 p-5 text-sm font-bold text-red-700">
                        Administrator accounts cannot be locked or disabled.
                    </div>
                @elseif($accountStatus === 'active')
                    <div class="space-y-4">
                        <form action="{{ route('admin.users.lock', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="w-full rounded-xl bg-amber-500 px-5 py-4 text-xs font-black uppercase tracking-wider text-white hover:bg-amber-600"
                                onclick="return confirm('Lock this account?')"
                            >
                                Lock Account
                            </button>
                        </form>

                        <form action="{{ route('admin.users.disable', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="w-full rounded-xl bg-red-600 px-5 py-4 text-xs font-black uppercase tracking-wider text-white hover:bg-red-700"
                                onclick="return confirm('Disable this account?')"
                            >
                                Disable Account
                            </button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full rounded-xl bg-green-600 px-5 py-4 text-xs font-black uppercase tracking-wider text-white hover:bg-green-700"
                            onclick="return confirm('Reactivate this account?')"
                        >
                            Reactivate Account
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
