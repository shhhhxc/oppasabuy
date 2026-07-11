@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto px-6 pb-20">
    <div class="mb-10">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Registration & Site Curation</h1>
        <p class="text-gray-500 mt-2 text-lg">Review pending user profiles and update live marketing assets for Oppasabuy.</p>
    </div>

    {{-- Error & Success Alert Notification Block --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-semibold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm">
            <p class="font-bold mb-1">Please correct the following errors:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Seller Section --}}
    <div class="mb-12">
        <div class="flex items-center space-x-3 mb-6">
            <span class="bg-red-100 text-[#A31D1D] px-3 py-1 rounded-full text-xs font-black uppercase">Sellers</span>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Pending Store Applications</h2>
        </div>
        
        <div class="grid grid-cols-1 gap-8">
            @forelse($sellerVerifications as $v)
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
                    <div class="p-8">
                        <div class="flex flex-wrap justify-between items-start border-b border-gray-50 pb-6 mb-6 gap-4">
                            <div class="flex items-center space-x-5">
                                <div class="h-16 w-16 bg-[#1D4ED8] rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100 text-white text-2xl font-black">
                                    {{ substr($v->store_name ?? $v->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $v->store_name }}</h3>
                                    <p class="text-sm text-gray-500 font-medium">Applicant: {{ $v->user->name }} • {{ $v->user->email }}</p>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Selected Plan</span>
                                <span class="px-5 py-2 rounded-xl text-sm font-black border 
                                    {{ $v->plan == 'premium' ? 'bg-purple-50 text-purple-700 border-purple-100' : '' }}
                                    {{ $v->plan == 'pro' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                    {{ $v->plan == 'basic' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                                    {{ $v->plan == 'free' ? 'bg-gray-50 text-gray-600 border-gray-100' : '' }}">
                                    {{ strtoupper($v->plan) }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h4 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Store Description</h4>
                            <p class="text-gray-700 leading-relaxed">{{ $v->store_description ?? 'No description provided.' }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 rounded-3xl p-5 border border-gray-100">
                                <span class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Valid ID ({{ $v->id_type }})</span>
                                <a href="{{ asset('storage/' . $v->document_path) }}" target="_blank" class="block w-full text-center py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                                    View ID Document
                                </a>
                            </div>

                            <div class="bg-gray-50 rounded-3xl p-5 border border-gray-100">
                                <span class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Identity Video Proof</span>
                                @if($v->video_path)
                                    <a href="{{ asset('storage/' . $v->video_path) }}" target="_blank" class="block w-full text-center py-3 bg-[#1D4ED8] rounded-xl text-sm font-bold text-white hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                                        ▶ Play Video Proof
                                    </a>
                                @else
                                    <div class="text-center py-3 text-xs text-gray-400 italic">No video provided</div>
                                @endif
                            </div>

                            <div class="bg-gray-50 rounded-3xl p-5 border border-gray-100">
                                <span class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Store Media / Logo</span>
                                <a href="{{ asset('storage/' . $v->logo_path) }}" target="_blank" class="block w-full text-center py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                                    Open Media File
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-8 py-5 flex items-center justify-between border-t border-gray-100">
                        <span class="text-xs text-gray-400 font-medium italic">Submitted {{ $v->created_at->diffForHumans() }}</span>
                        <div class="flex space-x-3">
                            <button type="button" 
                                onclick="openRejectionModal('{{ $v->id }}', 'seller', '{{ $v->store_name }}')"
                                class="px-6 py-3 text-gray-400 font-extrabold hover:text-red-600 transition uppercase tracking-widest text-[10px]">
                                Decline
                            </button>

                            <form action="{{ route('admin.verify.update', [$v->id, 'seller']) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="px-10 py-3 bg-[#A31D1D] text-white font-black rounded-xl shadow-xl shadow-red-100 hover:bg-[#821717] transition transform active:scale-95 uppercase tracking-widest text-xs">
                                    Approve Registration
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[3rem] p-12 text-center border-2 border-dashed border-gray-100 text-gray-400">No pending seller registrations.</div>
            @endforelse
        </div>
    </div>

    {{-- Buyer Section --}}
    <div class="mb-16">
        <div class="flex items-center space-x-3 mb-6">
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-black uppercase">Buyers</span>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Pending Identity Verifications</h2>
        </div>

        <div class="grid grid-cols-1 gap-8">
            @forelse($buyerVerifications as $b)
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden p-8 hover:shadow-md transition duration-300">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center space-x-5">
                            <div class="h-16 w-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-2xl font-black shadow-inner">
                                {{ substr($b->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $b->user->name }}</h3>
                                <p class="text-sm text-gray-500 font-medium">{{ $b->user->email }}</p>
                                <div class="mt-1 flex items-center space-x-2">
                                    <span class="text-[10px] font-black uppercase tracking-tighter text-gray-400">ID Type:</span>
                                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">{{ $b->id_type }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-4">
                            <a href="{{ asset('storage/' . ($b->id_path ?? $b->document_path)) }}" target="_blank" class="px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                View Valid ID
                            </a>
                            
                            <button type="button" 
                                onclick="openRejectionModal('{{ $b->id }}', 'buyer', '{{ $b->user->name }}')"
                                class="px-4 py-3 text-gray-400 hover:text-red-600 font-bold text-[10px] uppercase tracking-wider transition">
                                Decline
                            </button>

                            <form action="{{ route('admin.verify.update', [$b->id, 'buyer']) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="px-8 py-3 bg-[#1D4ED8] text-white font-black rounded-xl text-[10px] uppercase tracking-wider shadow-lg shadow-blue-100 hover:bg-blue-800 transition transform active:scale-95">
                                    Approve Buyer
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-50 flex justify-start">
                         <span class="text-[10px] text-gray-400 font-medium italic">Requested {{ $b->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[3rem] p-12 text-center border-2 border-dashed border-gray-100 text-gray-400">No pending buyer registrations.</div>
            @endforelse
        </div>
    </div>

    <div class="mb-16">
        <div class="flex items-center space-x-3 mb-6">
            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-black uppercase">Riders</span>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Pending Rider Applications</h2>
        </div>

        <div class="grid grid-cols-1 gap-8">
            @forelse($riderVerifications as $rider)
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
                    <div class="p-8">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6 border-b border-gray-100 pb-6 mb-6">
                            <div class="flex items-center space-x-5">
                                <div class="h-16 w-16 bg-green-600 rounded-2xl flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-green-100">
                                    {{ strtoupper(substr($rider->user->full_name ?? $rider->user->name ?? 'R', 0, 1)) }}
                                </div>

                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">
                                        {{ $rider->user->full_name ?? $rider->user->name ?? 'Rider Applicant' }}
                                    </h3>
                                    <p class="text-sm text-gray-500 font-medium">
                                        {{ $rider->user->email ?? 'No email available' }}
                                    </p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-green-50 text-green-700">
                                            {{ $rider->vehicle_type ?? 'Vehicle not specified' }}
                                        </span>
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-600">
                                            {{ $rider->vehicle_plate ?? 'No plate number' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:text-right">
                                <span class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Application Status</span>
                                <span class="inline-flex px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-yellow-50 text-yellow-700 border border-yellow-100">
                                    {{ strtoupper($rider->status ?? 'pending') }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Phone Number</span>
                                <p class="font-bold text-gray-800 break-words">{{ $rider->phone ?? 'Not provided' }}</p>
                            </div>

                            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Birth Date</span>
                                <p class="font-bold text-gray-800">
                                    {{ $rider->birth_date ? $rider->birth_date->format('F d, Y') : 'Not provided' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">License Number</span>
                                <p class="font-bold text-gray-800 break-words">{{ $rider->license_number ?? 'Not provided' }}</p>
                            </div>

                            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">License Expiration</span>
                                <p class="font-bold text-gray-800">
                                    {{ $rider->license_expiration ? $rider->license_expiration->format('F d, Y') : 'Not provided' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100">
                                <h4 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-5">Vehicle Information</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">Type</span>
                                        <p class="mt-1 font-bold text-gray-800">{{ $rider->vehicle_type ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">Brand</span>
                                        <p class="mt-1 font-bold text-gray-800">{{ $rider->vehicle_brand ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">Model</span>
                                        <p class="mt-1 font-bold text-gray-800">{{ $rider->vehicle_model ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">Color</span>
                                        <p class="mt-1 font-bold text-gray-800">{{ $rider->vehicle_color ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">Plate Number</span>
                                        <p class="mt-1 font-bold text-gray-800">{{ $rider->vehicle_plate ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100">
                                <h4 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-5">Contact and Address</h4>
                                <div class="space-y-4">
                                    <div>
                                        <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">Complete Address</span>
                                        <p class="mt-1 font-bold text-gray-800 leading-relaxed">{{ $rider->address ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-black uppercase tracking-wider text-gray-400">Emergency Contact</span>
                                        <p class="mt-1 font-bold text-gray-800">
                                            {{ $rider->emergency_contact_name ?? 'Not provided' }}
                                            @if($rider->emergency_contact_number)
                                                <span class="text-gray-500 font-medium">• {{ $rider->emergency_contact_number }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                            <div class="bg-gray-50 rounded-3xl p-5 border border-gray-100">
                                <span class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Driver License</span>
                                @if($rider->license_img)
                                    <a href="{{ asset('storage/' . $rider->license_img) }}" target="_blank" class="block w-full text-center py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-100 transition">
                                        View License
                                    </a>
                                @else
                                    <div class="text-center py-3 text-xs text-gray-400 italic">No file uploaded</div>
                                @endif
                            </div>

                            <div class="bg-gray-50 rounded-3xl p-5 border border-gray-100">
                                <span class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-4">OR/CR Document</span>
                                @if($rider->orcr_img)
                                    <a href="{{ asset('storage/' . $rider->orcr_img) }}" target="_blank" class="block w-full text-center py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-100 transition">
                                        View OR/CR
                                    </a>
                                @else
                                    <div class="text-center py-3 text-xs text-gray-400 italic">No file uploaded</div>
                                @endif
                            </div>

                            <div class="bg-gray-50 rounded-3xl p-5 border border-gray-100">
                                <span class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Vehicle Photo</span>
                                @if($rider->vehicle_photo)
                                    <a href="{{ asset('storage/' . $rider->vehicle_photo) }}" target="_blank" class="block w-full text-center py-3 bg-green-600 rounded-xl text-sm font-bold text-white hover:bg-green-700 transition shadow-lg shadow-green-100">
                                        View Vehicle
                                    </a>
                                @else
                                    <div class="text-center py-3 text-xs text-gray-400 italic">No file uploaded</div>
                                @endif
                            </div>

                            <div class="bg-gray-50 rounded-3xl p-5 border border-gray-100">
                                <span class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-4">Selfie With License</span>
                                @if($rider->selfie_license)
                                    <a href="{{ asset('storage/' . $rider->selfie_license) }}" target="_blank" class="block w-full text-center py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-100 transition">
                                        View Selfie
                                    </a>
                                @else
                                    <div class="text-center py-3 text-xs text-gray-400 italic">No file uploaded</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-8 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-gray-100">
                        <span class="text-xs text-gray-400 font-medium italic">
                            Submitted {{ $rider->created_at ? $rider->created_at->diffForHumans() : 'recently' }}
                        </span>

                        <div class="flex flex-wrap items-center gap-3">
                            <button
                                type="button"
                                onclick='openRejectionModal(@json($rider->id), "rider", @json($rider->user->full_name ?? $rider->user->name ?? "Rider Applicant"))'
                                class="px-6 py-3 text-gray-400 font-extrabold hover:text-red-600 transition uppercase tracking-widest text-[10px]"
                            >
                                Decline
                            </button>

                            <form action="{{ route('admin.verify.update', [$rider->id, 'rider']) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="px-10 py-3 bg-green-600 text-white font-black rounded-xl shadow-xl shadow-green-100 hover:bg-green-700 transition transform active:scale-95 uppercase tracking-widest text-xs">
                                    Approve Rider
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[3rem] p-12 text-center border-2 border-dashed border-gray-100 text-gray-400">
                    No pending rider registrations.
                </div>
            @endforelse
        </div>
    </div>

    {{-- INTEGRATED AD, EVENT, & BIBLE VERSE ENGINE LAYERS --}}
    <div class="border-t border-gray-200 pt-12">
        <div class="flex items-center space-x-3 mb-8">
            <span class="bg-gray-800 text-white px-3 py-1 rounded-full text-xs font-black uppercase">Curation</span>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Homepage Content Management</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- Promotional Ads Core --}}
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="h-10 w-10 bg-blue-50 text-[#1D4ED8] rounded-xl flex items-center justify-center text-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Promotional Assets</h3>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Update Banner or Video independently</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2">Banner Layout Image</label>
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-100 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-200">
                                <div class="flex flex-col items-center justify-center pt-4 pb-4 px-4 text-center">
                                    <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <p id="ad-file-name" class="text-sm text-gray-600 font-bold">Select Banner File</p>
                                </div>
                                <input type="file" name="ad_image" class="hidden" onchange="updateFileName(this, 'ad-file-name')" />
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2">Promotional Video File</label>
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-100 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-200">
                                <div class="flex flex-col items-center justify-center pt-4 pb-4 px-4 text-center">
                                    <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <p id="video-file-name" class="text-sm text-gray-600 font-bold">Select Promo Video</p>
                                </div>
                                <input type="file" name="ad_video" class="hidden" onchange="updateFileName(this, 'video-file-name')" />
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-1">Ad Target Title</label>
                                <input type="text" name="title" placeholder="e.g., K-Style Festival" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#1D4ED8]">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-1">Redirect Route/URL</label>
                                <input type="url" name="target_url" placeholder="https://Oppasabuy.com/..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#1D4ED8]">
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-2 py-3.5 bg-gray-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-black transition transform active:scale-[0.98]">
                            Publish Updates
                        </button>
                    </form>
                </div>
            </div>

            {{-- Upcoming Events Core --}}
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-between">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="h-10 w-10 bg-green-50 text-green-700 rounded-xl flex items-center justify-center text-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">OppaMall Events</h3>
                </div>
                <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="text" name="title" placeholder="Event Title" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm" required>
                    <input type="date" name="event_date" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm" required>
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-100 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                        <span id="event-file-name" class="text-sm font-bold text-gray-600">Upload Event Banner</span>
                        <input type="file" name="image" class="hidden" onchange="updateFileName(this, 'event-file-name')" required />
                    </label>
                    <button type="submit" class="w-full py-4 bg-green-700 text-white text-xs font-black uppercase rounded-xl hover:bg-green-800 transition">Add Event</button>
                </form>
            </div>

            {{-- Scripture Layer Core --}}
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-between lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-red-50 text-[#A31D1D] rounded-xl flex items-center justify-center text-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Verse of the Day</h3>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Supports Text or Full Graphic Layouts</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4 bg-gray-50 p-1.5 rounded-xl flex space-x-2">
                    <button type="button" id="btn-verse-text" class="flex-1 py-2 text-center text-xs font-black uppercase tracking-wider rounded-lg bg-white text-gray-900 shadow-sm transition border border-gray-100">Plain Text Mode</button>
                    <button type="button" id="btn-verse-img" class="flex-1 py-2 text-center text-xs font-black uppercase tracking-wider rounded-lg text-gray-400 hover:bg-gray-100 transition">Full Graphic Card Mode</button>
                </div>

                <form action="{{ route('admin.verse.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="display_type" id="verse_display_type" value="text">

                    <div id="wrapper-verse-text" class="space-y-4">
                        <textarea name="verse_text" id="verse_text_field" rows="3" placeholder="Write out the scripture text clearly..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#A31D1D]" required></textarea>
                        <input type="text" name="reference" id="verse_ref_field" placeholder="Citation (e.g., Proverbs 3:5-6)" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-[#A31D1D]" required>
                    </div>

                    <div id="wrapper-verse-img" class="hidden">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-100 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <p id="verse-file-name" class="text-sm font-bold text-gray-600">Upload Custom Verse Graphic</p>
                            <input type="file" id="verse_image_input" name="verse_image" class="hidden" onchange="updateFileName(this, 'verse-file-name')" />
                        </label>
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#A31D1D] text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-[#821717] transition shadow-lg shadow-red-100">
                        Synchronize Verse Feed
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Rejection Modal --}}
<div id="rejectionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="relative flex min-h-screen items-center justify-center px-4 py-10">
        <div
            class="fixed inset-0 z-0 bg-gray-900/75"
            onclick="closeRejectionModal()"
        ></div>

        <div class="relative z-10 w-full max-w-lg overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl">
            <form id="rejectionForm" method="POST">
                @csrf
                @method('PATCH')

                <input type="hidden" name="status" value="rejected">

                <div class="bg-white px-8 pb-6 pt-8">
                    <h3 class="mb-2 text-2xl font-black text-gray-900">
                        Decline Registration
                    </h3>

                    <p class="mb-6 text-gray-500">
                        Why are you rejecting
                        <span id="rejectionTargetName" class="font-bold text-gray-800"></span>?
                    </p>

                    <textarea
                        name="rejection_reason"
                        required
                        rows="4"
                        class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 text-gray-700 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-200"
                        placeholder="e.g. ID photo is too blurry or expired."
                    ></textarea>
                </div>

                <div class="flex justify-end space-x-3 bg-gray-50 px-8 py-6">
                    <button
                        type="button"
                        onclick="closeRejectionModal()"
                        class="px-6 py-3 text-xs font-bold uppercase tracking-widest text-gray-400 transition hover:text-gray-700"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-red-600 px-8 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-red-100 transition hover:bg-red-700"
                    >
                        Confirm Decline
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRejectionModal(id, type, name) {
        const modal = document.getElementById('rejectionModal');
        const form = document.getElementById('rejectionForm');
        const targetName = document.getElementById('rejectionTargetName');
        const reasonField = form.querySelector('textarea[name="rejection_reason"]');

        form.action = "{{ url('/admin/verify') }}/" + id + "/" + type;
        targetName.textContent = name;

        if (reasonField) {
            reasonField.value = '';
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeRejectionModal() {
        const modal = document.getElementById('rejectionModal');

        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function updateFileName(input, targetId) {
        const textLabel = document.getElementById(targetId);

        if (input.files && input.files[0]) {
            textLabel.innerText = "Selected: " + input.files[0].name;
        }
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeRejectionModal();
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const textTab = document.getElementById('btn-verse-text');
        const imgTab = document.getElementById('btn-verse-img');
        const textWrapper = document.getElementById('wrapper-verse-text');
        const imgWrapper = document.getElementById('wrapper-verse-img');
        const typeInput = document.getElementById('verse_display_type');
        const textField = document.getElementById('verse_text_field');
        const refField = document.getElementById('verse_ref_field');
        const imgField = document.querySelector('input[name="verse_image"]');

        if (
            textTab &&
            imgTab &&
            textWrapper &&
            imgWrapper &&
            typeInput &&
            textField &&
            refField &&
            imgField
        ) {
            textTab.addEventListener('click', function () {
                typeInput.value = 'text';
                textWrapper.classList.remove('hidden');
                imgWrapper.classList.add('hidden');
                textField.setAttribute('required', 'required');
                refField.setAttribute('required', 'required');
                imgField.removeAttribute('required');
                textTab.className = "flex-1 py-2 text-center text-xs font-black uppercase tracking-wider rounded-lg bg-white text-gray-900 shadow-sm transition border border-gray-100";
                imgTab.className = "flex-1 py-2 text-center text-xs font-black uppercase tracking-wider rounded-lg text-gray-400 hover:bg-gray-100 transition";
            });

            imgTab.addEventListener('click', function () {
                typeInput.value = 'image';
                imgWrapper.classList.remove('hidden');
                textWrapper.classList.add('hidden');
                textField.removeAttribute('required');
                refField.removeAttribute('required');
                imgField.setAttribute('required', 'required');
                imgTab.className = "flex-1 py-2 text-center text-xs font-black uppercase tracking-wider rounded-lg bg-white text-gray-900 shadow-sm transition border border-gray-100";
                textTab.className = "flex-1 py-2 text-center text-xs font-black uppercase tracking-wider rounded-lg text-gray-400 hover:bg-gray-100 transition";
            });
        }
    });
</script>
@endsection