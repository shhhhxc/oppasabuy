@extends('layouts.app')

@section('content')
<div class="bg-[#f8fafc] min-h-screen pb-20">
    <div class="bg-white border-b border-gray-200 py-12 mb-10 shadow-sm">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl font-[900] text-[#163d78] tracking-tighter uppercase">Account <span class="text-[#9e1b18]">Settings</span></h1>
            <p class="text-gray-500 mt-2 font-semibold uppercase text-xs tracking-widest">Manage your profile information and security</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-2xl font-bold text-sm border border-green-100">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-2xl font-bold text-sm border border-red-100">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="bi bi-exclamation-circle-fill me-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                {{-- Left: Avatars (Personal & Store) --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    {{-- PERSONAL PROFILE PICTURE (Everyone) --}}
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 text-center shadow-sm">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Personal Avatar</h3>
                        <div class="relative inline-block mb-6">
                            <div class="h-32 w-32 rounded-full border-4 border-gray-50 overflow-hidden shadow-xl bg-gray-100 mx-auto">
                                <img id="profile-preview" 
                                     src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=163d78&color=fff' }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <label class="absolute bottom-1 right-1 bg-[#163d78] text-white p-2.5 rounded-full cursor-pointer shadow-lg hover:scale-110 transition-all">
                                <i class="bi bi-camera-fill text-sm"></i>
                                <input type="file" name="profile_picture" class="hidden" accept="image/*" onchange="previewImage(this, 'profile-preview')">
                            </label>
                        </div>
                        <h2 class="text-xl font-black text-[#163d78]">{{ $user->name }}</h2>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $user->role ?? 'Member' }}</p>
                    </div>

                    {{-- STORE LOGO (Sellers Only) --}}
                    @php 
                        $seller = \DB::table('seller_verifications')->where('user_id', $user->id)->first();
                    @endphp

                    @if($seller)
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 text-center shadow-sm border-t-4 border-t-[#9e1b18]">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Store Logo</h3>
                        <div class="relative inline-block mb-4">
                            <div class="h-32 w-32 rounded-2xl border-4 border-gray-50 overflow-hidden shadow-xl bg-gray-100 mx-auto">
                                <img id="logo-preview" 
                                     src="{{ $seller->logo_path ? asset('storage/' . $seller->logo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($seller->store_name) . '&background=9e1b18&color=fff' }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <label class="absolute bottom-1 right-1 bg-[#9e1b18] text-white p-2.5 rounded-full cursor-pointer shadow-lg hover:scale-110 transition-all">
                                <i class="bi bi-shop text-sm"></i>
                                <input type="file" name="logo_path" class="hidden" accept="image/*" onchange="previewImage(this, 'logo-preview')">
                            </label>
                        </div>
                        <h2 class="text-xl font-black text-[#9e1b18]">{{ $seller->store_name }}</h2>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Business Identity</p>
                    </div>
                    @endif
                </div>

                {{-- Right: Forms --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- General Info --}}
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 p-10 shadow-sm">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-8">General Information</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black text-[#163d78] uppercase mb-2 ml-1">Display Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-gray-50 border-0 rounded-2xl p-4 text-sm font-bold text-[#163d78] focus:ring-4 focus:ring-blue-50 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#163d78] uppercase mb-2 ml-1">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-gray-50 border-0 rounded-2xl p-4 text-sm font-bold text-[#163d78] focus:ring-4 focus:ring-blue-50 outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Security --}}
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 p-10 shadow-sm">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Security & Password</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-[#163d78] uppercase mb-2 ml-1">New Password</label>
                                <input type="password" name="password" class="w-full bg-gray-50 border-0 rounded-2xl p-4 text-sm font-bold outline-none focus:ring-4 focus:ring-red-50 transition-all" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#163d78] uppercase mb-2 ml-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="w-full bg-gray-50 border-0 rounded-2xl p-4 text-sm font-bold outline-none focus:ring-4 focus:ring-red-50 transition-all" placeholder="••••••••">
                            </div>
                        </div>
                        <p class="text-[9px] text-gray-400 mt-4 font-bold uppercase tracking-tighter italic">Leave blank if you don't want to change your password.</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#163d78] text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#9e1b18] hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-blue-900/10">
                            Update All Settings
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- JavaScript for Instant Image Preview --}}
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection