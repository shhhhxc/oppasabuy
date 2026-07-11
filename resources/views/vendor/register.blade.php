@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#f6f8fb] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 text-blue-800 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Open Your Shop</h2>
                <p class="mt-2 text-sm text-gray-600">Submit your seller application. Your store will become available after administrator approval.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl">
                    <strong class="block font-bold mb-1 text-sm">Please verify the following fields:</strong>
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('vendor.register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="shop_name" class="block text-sm font-semibold text-gray-700">Shop Name</label>
                    <input type="text" name="shop_name" id="shop_name" value="{{ old('shop_name') }}" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-800 focus:ring-blue-800 text-sm @error('shop_name') border-red-500 @enderror">
                    @error('shop_name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="store_logo" class="block text-sm font-semibold text-gray-700">Store Logo</label>
                    <input type="file" name="store_logo" id="store_logo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-wider file:bg-blue-50 file:text-blue-800 hover:file:bg-blue-100 transition file:cursor-pointer @error('store_logo') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-400">Recommended: Square image (PNG or JPG).</p>
                    @error('store_logo')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="store_address" class="block text-sm font-semibold text-gray-700">Store Address</label>
                        <label class="inline-flex items-center text-xs text-gray-500 cursor-pointer">
                            <input type="checkbox" name="no_physical_store" id="no_physical_store" value="1" {{ old('no_physical_store') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-800 focus:ring-blue-800 mr-1.5">
                            No physical store (Online Only)
                        </label>
                    </div>
                    <textarea name="store_address" id="store_address" rows="2" required
                        class="mt-1 block w-full rounded-lg shadow-sm border-gray-300 focus:border-blue-800 focus:ring-blue-800 text-sm @error('store_address') border-red-500 @enderror" 
                        placeholder="Enter complete building/street address...">{{ old('store_address') }}</textarea>
                    @error('store_address')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="business_email" class="block text-sm font-semibold text-gray-700">Business Email</label>
                    <input type="email" name="business_email" id="business_email" value="{{ old('business_email', Auth::user()->email) }}" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-800 focus:ring-blue-800 text-sm @error('business_email') border-red-500 @enderror">
                    @error('business_email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-semibold text-gray-700">Contact Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-800 focus:ring-blue-800 text-sm @error('phone_number') border-red-500 @enderror">
                    @error('phone_number')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="shop_description" class="block text-sm font-semibold text-gray-700">Shop Description</label>
                    <textarea name="shop_description" id="shop_description" rows="3" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-800 focus:ring-blue-800 text-sm @error('shop_description') border-red-500 @enderror">{{ old('shop_description') }}</textarea>
                    @error('shop_description')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Where would you like to open your store?</label>
                    <p class="text-xs text-gray-400 mb-3">You can select multiple channels to expand your store reach.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <label class="store-card relative flex flex-col p-4 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 transition-all duration-150 group select-none">
                            <input type="checkbox" name="store_types[]" value="oppa_mall" {{ in_array('oppa_mall', old('store_types', [])) ? 'checked' : '' }} class="absolute opacity-0 pointer-events-none main-store-checkbox">
                            <div class="check-icon absolute top-4 right-4 text-blue-800 hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-900 group-hover:text-blue-800 transition-colors">Oppa Mall</span>
                            <span class="text-xs text-gray-500 mt-1">Premium brand placement and integrated mall benefits.</span>
                        </label>

                        <label class="store-card relative flex flex-col p-4 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 transition-all duration-150 group select-none">
                            <input type="checkbox" name="store_types[]" value="own_webstore" {{ in_array('own_webstore', old('store_types', [])) ? 'checked' : '' }} class="absolute opacity-0 pointer-events-none main-store-checkbox">
                            <div class="check-icon absolute top-4 right-4 text-blue-800 hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-900 group-hover:text-blue-800 transition-colors">Own Webstore</span>
                            <span class="text-xs text-gray-500 mt-1">A standalone digital storefront customized for your business.</span>
                        </label>

                        <label class="store-card relative flex flex-col p-4 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 transition-all duration-150 group select-none">
                            <input type="checkbox" name="store_types[]" id="green_market_checkbox" value="green_market" {{ in_array('green_market', old('store_types', [])) ? 'checked' : '' }} class="absolute opacity-0 pointer-events-none main-store-checkbox">
                            <div class="check-icon absolute top-4 right-4 text-blue-800 hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-900 group-hover:text-blue-800 transition-colors">Green Market</span>
                            <span class="text-xs text-gray-500 mt-1">Focused on organic foods, fresh produce, and eco-goods.</span>
                        </label>

                        <label class="store-card relative flex flex-col p-4 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 transition-all duration-150 group select-none">
                            <input type="checkbox" name="store_types[]" value="personal_care" {{ in_array('personal_care', old('store_types', [])) ? 'checked' : '' }} class="absolute opacity-0 pointer-events-none main-store-checkbox">
                            <div class="check-icon absolute top-4 right-4 text-blue-800 hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-900 group-hover:text-blue-800 transition-colors">Personal Care</span>
                            <span class="text-xs text-gray-500 mt-1">Dedicated to beauty, health, hygiene, and wellness items.</span>
                        </label>

                    </div>
                    @error('store_types')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div id="green_market_panel" class="p-5 bg-emerald-50/50 border border-emerald-100 rounded-2xl space-y-3 hidden">
                    <div>
                        <label class="block text-sm font-bold text-emerald-900">Green Market Classification</label>
                        <p class="text-xs text-emerald-700/80 mb-3">Please specify the market segment you operate in. You can select both fields if applicable.</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="sub-card relative flex flex-col p-4 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-emerald-400 transition-all duration-150 group select-none">
                                <input type="checkbox" name="green_market_type[]" value="sari_sari" {{ in_array('sari_sari', old('green_market_type', [])) ? 'checked' : '' }} class="absolute opacity-0 pointer-events-none sub-checkbox">
                                <div class="check-icon absolute top-4 right-4 text-emerald-700 hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-gray-900 group-hover:text-emerald-700 transition-colors">Sari-Sari Store</span>
                                <span class="text-xs text-gray-500 mt-1">Local neighborhood retail, convenience goods, and snacks.</span>
                            </label>

                            <label class="sub-card relative flex flex-col p-4 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-emerald-400 transition-all duration-150 group select-none">
                                <input type="checkbox" name="green_market_type[]" value="wet_market" {{ in_array('wet_market', old('green_market_type', [])) ? 'checked' : '' }} class="absolute opacity-0 pointer-events-none sub-checkbox">
                                <div class="check-icon absolute top-4 right-4 text-emerald-700 hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-gray-900 group-hover:text-emerald-700 transition-colors">Wet Market</span>
                                <span class="text-xs text-gray-500 mt-1">Fresh produce, meats, poultry, seafood, and raw goods.</span>
                            </label>
                        </div>
                        @error('green_market_type')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="p-5 bg-amber-50 border border-amber-200 rounded-2xl">
                    <h3 class="text-sm font-black text-amber-900 mb-2">Verification Requirements</h3>
                    <p class="text-xs text-amber-700 mb-4">
                        Your application will be reviewed by an administrator before your store becomes active.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Valid Government ID</label>
                            <input type="file" name="valid_id" accept="image/*,.pdf"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Store / Business Photo</label>
                            <input type="file" name="store_photo" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700">
                        </div>
                    </div>
                </div>


                <div class="flex items-start bg-blue-50/50 p-4 rounded-xl border border-blue-100/70">
                    <div class="flex items-center h-5">
                        <input id="agree_terms" name="agree_terms" type="checkbox" required
                            class="focus:ring-blue-800 h-4 w-4 text-blue-800 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="agree_terms" class="font-semibold text-gray-800">I agree to the Seller Terms</label>
                        <p class="text-gray-500 mt-0.5">By registering, you agree to OppaSabuy's merchant policies and platform fee structures.</p>
                        @error('agree_terms')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-800 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transition-colors duration-150 uppercase tracking-wider">
                        Launch Store
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Handle Store Address toggle
            const noPhysicalStoreCheckbox = document.getElementById('no_physical_store');
            const storeAddressTextarea = document.getElementById('store_address');

            function updateAddressState() {
                if (noPhysicalStoreCheckbox.checked) {
                    storeAddressTextarea.disabled = true;
                    storeAddressTextarea.required = false;
                    storeAddressTextarea.classList.add('bg-gray-50', 'text-gray-400', 'border-gray-200', 'cursor-not-allowed');
                    storeAddressTextarea.classList.remove('border-gray-300');
                } else {
                    storeAddressTextarea.disabled = false;
                    storeAddressTextarea.required = true;
                    storeAddressTextarea.classList.remove('bg-gray-50', 'text-gray-400', 'border-gray-200', 'cursor-not-allowed');
                    storeAddressTextarea.classList.add('border-gray-300');
                }
            }
            noPhysicalStoreCheckbox.addEventListener('change', updateAddressState);
            updateAddressState();

            // 2. Handle 4 Main Choices Cards Multi-select
            const storeCards = document.querySelectorAll('.store-card');
            const greenMarketCheckbox = document.getElementById('green_market_checkbox');
            const greenMarketPanel = document.getElementById('green_market_panel');

            function updateCardUI(card, checkbox) {
                const icon = card.querySelector('.check-icon');
                if (checkbox.checked) {
                    card.classList.add('border-blue-800', 'ring-1', 'ring-blue-800');
                    card.classList.remove('border-gray-200');
                    icon.classList.remove('hidden');
                } else {
                    card.classList.remove('border-blue-800', 'ring-1', 'ring-blue-800');
                    card.classList.add('border-gray-200');
                    icon.classList.add('hidden');
                }
            }

            function togglePanel() {
                if (greenMarketCheckbox.checked) {
                    greenMarketPanel.classList.remove('hidden');
                } else {
                    greenMarketPanel.classList.add('hidden');
                }
            }

            storeCards.forEach(card => {
                const checkbox = card.querySelector('.main-store-checkbox');
                
                // Set initial active styles based on old validation state
                updateCardUI(card, checkbox);

                card.addEventListener('click', function(e) {
                    // STOP the default label click propagation loop!
                    e.preventDefault();

                    // Manually toggle and synchronize state
                    checkbox.checked = !checkbox.checked;
                    updateCardUI(card, checkbox);
                    togglePanel();
                });
            });

            // Run right away to check initial state if validation fails
            togglePanel();

            // 3. Handle Sub-classification Choices (Sari-Sari / Wet Market) Multi-select
            const subCards = document.querySelectorAll('.sub-card');

            function updateSubCardUI(card, checkbox) {
                const icon = card.querySelector('.check-icon');
                if (checkbox.checked) {
                    card.classList.add('border-emerald-700', 'ring-1', 'ring-emerald-700');
                    card.classList.remove('border-gray-200');
                    icon.classList.remove('hidden');
                } else {
                    card.classList.remove('border-emerald-700', 'ring-1', 'ring-emerald-700');
                    card.classList.add('border-gray-200');
                    icon.classList.add('hidden');
                }
            }

            subCards.forEach(card => {
                const checkbox = card.querySelector('.sub-checkbox');
                
                updateSubCardUI(card, checkbox);

                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    checkbox.checked = !checkbox.checked;
                    updateSubCardUI(card, checkbox);
                });
            });
        });
    </script>
@endsection