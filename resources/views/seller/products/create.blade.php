@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f6f8fb] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="mb-6 pb-6 border-b border-gray-100">
                <h2 class="text-xl font-black text-gray-900 tracking-tight">Add New Catalog Product</h2>
                <p class="mt-2 text-sm text-gray-500">
                    Fill out the required details below. If something is missing, the exact error will appear before the form.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-bold text-green-700">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-check-circle-fill mt-0.5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-bold text-red-700">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
                    <div class="mb-3 flex items-center gap-2">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                        <h3 class="m-0 text-sm font-black uppercase tracking-wider">
                            Product was not added
                        </h3>
                    </div>

                    <p class="mb-3 text-xs font-semibold">
                        Please fix the following:
                    </p>

                    <ul class="mb-0 list-disc space-y-1 pl-5 text-sm font-semibold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('seller.products.store', ['channel' => $channel]) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <input type="hidden" name="channel" value="{{ $channel }}">

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2">
                        Product Thumbnail Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        required
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-wider file:bg-blue-50 file:text-blue-800 hover:file:bg-blue-100 transition duration-150 @error('image') border border-red-500 rounded-xl p-2 @enderror"
                    >

                    @error('image')
                        <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2">
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-2.5 bg-gray-50 border rounded-xl text-sm focus:outline-none focus:border-blue-500 font-medium {{ $errors->has('name') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                    >

                    @error('name')
                        <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if($channel == 'oppa_mall')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2">
                                Category
                            </label>

                            <select
                                id="categorySelect"
                                name="category"
                                required
                                class="w-full px-4 py-2.5 bg-gray-50 border rounded-xl text-sm {{ $errors->has('category') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                            >
                                <option value="">Select Category</option>

                                @foreach($categories as $cat)
                                    <option value="{{ $cat['name'] }}" {{ old('category') === $cat['name'] ? 'selected' : '' }}>
                                        {{ $cat['name'] }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2">
                                Subcategory
                            </label>

                            <select
                                id="subcategorySelect"
                                name="subcategory"
                                required
                                data-old-value="{{ old('subcategory') }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border rounded-xl text-sm {{ $errors->has('subcategory') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                            >
                                <option value="">Select Category First</option>
                            </select>

                            @error('subcategory')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif

                @if($channel === 'green_market')
                    <div class="space-y-5 rounded-2xl border border-green-100 bg-green-50/50 p-5">
                        <div>
                            <label class="block text-xs font-black text-green-700 uppercase tracking-wider mb-2">
                                Green Market Catalog
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="border rounded-xl p-4 bg-white cursor-pointer {{ old('green_market_type') === 'wet_market' ? 'border-green-600 ring-1 ring-green-600' : 'border-gray-200' }}">
                                    <input
                                        type="radio"
                                        name="green_market_type"
                                        value="wet_market"
                                        required
                                        {{ old('green_market_type') === 'wet_market' ? 'checked' : '' }}
                                        class="mr-2"
                                    >
                                    <span class="font-bold">Wet Market</span>
                                </label>

                                <label class="border rounded-xl p-4 bg-white cursor-pointer {{ old('green_market_type') === 'sari_sari' ? 'border-green-600 ring-1 ring-green-600' : 'border-gray-200' }}">
                                    <input
                                        type="radio"
                                        name="green_market_type"
                                        value="sari_sari"
                                        required
                                        {{ old('green_market_type') === 'sari_sari' ? 'checked' : '' }}
                                        class="mr-2"
                                    >
                                    <span class="font-bold">Sari-Sari Store</span>
                                </label>
                            </div>

                            @error('green_market_type')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-green-700 uppercase tracking-wider mb-2">
                                Category
                            </label>

                            <select
                                name="category"
                                required
                                class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm {{ $errors->has('category') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                            >
                                <option value="">Select Category</option>

                                <optgroup label="Wet Market">
                                    @foreach(['Vegetables', 'Fruits', 'Meat', 'Seafood', 'Poultry'] as $greenCategory)
                                        <option value="{{ $greenCategory }}" {{ old('category') === $greenCategory ? 'selected' : '' }}>
                                            {{ $greenCategory }}
                                        </option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="Sari-Sari Store">
                                    @foreach(['Snacks', 'Beverages', 'Canned Goods', 'Instant Food', 'Household Items'] as $greenCategory)
                                        <option value="{{ $greenCategory }}" {{ old('category') === $greenCategory ? 'selected' : '' }}>
                                            {{ $greenCategory }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>

                            @error('category')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif

                @if($channel === 'own_webstore')
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2">
                            Webstore Category
                        </label>

                        <input
                            type="text"
                            name="category"
                            value="{{ old('category') }}"
                            placeholder="Example: Electronics, Clothing, Home & Living"
                            required
                            class="w-full px-4 py-2.5 bg-gray-50 border rounded-xl text-sm {{ $errors->has('category') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                        >

                        @error('category')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                @if($channel == 'personal_care')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-purple-800 uppercase tracking-wider mb-2">
                                Service Category
                            </label>

                            <select
                                id="pcCategorySelect"
                                name="category"
                                required
                                class="w-full px-4 py-2.5 bg-gray-50 border rounded-xl text-sm {{ $errors->has('category') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                            >
                                <option value="">Select Category</option>

                                @foreach($categories as $parent => $subs)
                                    <option value="{{ $parent }}" {{ old('category') === $parent ? 'selected' : '' }}>
                                        {{ $parent }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-purple-800 uppercase tracking-wider mb-2">
                                Specific Service
                            </label>

                            <select
                                id="pcSubcategorySelect"
                                name="subcategory"
                                required
                                data-old-value="{{ old('subcategory') }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border rounded-xl text-sm {{ $errors->has('subcategory') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                            >
                                <option value="">Select Category First</option>
                            </select>

                            @error('subcategory')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2">
                            Price (PHP)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="price"
                            value="{{ old('price') }}"
                            {{ $channel === 'personal_care' ? '' : 'required' }}
                            class="w-full px-4 py-2.5 bg-gray-50 border rounded-xl text-sm {{ $errors->has('price') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                        >

                        @error('price')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2">
                            Stock
                        </label>

                        <input
                            type="number"
                            min="0"
                            name="stock"
                            value="{{ old('stock', 1) }}"
                            {{ $channel === 'personal_care' ? '' : 'required' }}
                            class="w-full px-4 py-2.5 bg-gray-50 border rounded-xl text-sm {{ $errors->has('stock') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                        >

                        @error('stock')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if($channel === 'personal_care')
                    <div class="space-y-4 p-5 bg-purple-50/50 border border-purple-100 rounded-2xl">
                        <div>
                            <label class="block text-xs font-black text-purple-800 uppercase tracking-wider mb-2">
                                Service Address
                            </label>

                            <input
                                type="text"
                                name="service_location"
                                value="{{ old('service_location') }}"
                                class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm {{ $errors->has('service_location') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                            >

                            @error('service_location')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-purple-800 uppercase tracking-wider mb-2">
                                Duration (Minutes)
                            </label>

                            <input
                                type="number"
                                min="1"
                                name="duration"
                                value="{{ old('duration') }}"
                                class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm {{ $errors->has('duration') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                            >

                            @error('duration')
                                <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2.5 bg-gray-50 border rounded-xl text-sm {{ $errors->has('description') ? 'border-red-500 bg-red-50' : 'border-gray-200' }}"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full py-3 bg-blue-800 hover:bg-black text-white font-black uppercase rounded-xl transition"
                >
                    Publish Catalog Item
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    @if($channel == 'oppa_mall')
        const categoriesData = @json($categories);
        const catSelect = document.getElementById('categorySelect');
        const subSelect = document.getElementById('subcategorySelect');

        function populateOppaMallSubcategories() {
            if (!catSelect || !subSelect) {
                return;
            }

            const oldValue = subSelect.dataset.oldValue || '';
            subSelect.innerHTML = '<option value="">Select Subcategory</option>';

            const category = categoriesData.find(item => item.name === catSelect.value);

            if (category && category.subcategories) {
                category.subcategories.forEach(subcategory => {
                    const option = document.createElement('option');
                    option.value = subcategory;
                    option.textContent = subcategory;
                    option.selected = oldValue === subcategory;
                    subSelect.appendChild(option);
                });
            }
        }

        catSelect.addEventListener('change', populateOppaMallSubcategories);
        populateOppaMallSubcategories();
    @endif

    @if($channel == 'personal_care')
        const pcCategoriesData = @json($categories);
        const pcCatSelect = document.getElementById('pcCategorySelect');
        const pcSubSelect = document.getElementById('pcSubcategorySelect');

        function populatePersonalCareSubcategories() {
            if (!pcCatSelect || !pcSubSelect) {
                return;
            }

            const oldValue = pcSubSelect.dataset.oldValue || '';
            pcSubSelect.innerHTML = '<option value="">Select Service</option>';

            const items = pcCategoriesData[pcCatSelect.value] || [];

            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item.name;
                option.textContent = item.name;
                option.selected = oldValue === item.name;
                pcSubSelect.appendChild(option);
            });
        }

        pcCatSelect.addEventListener('change', populatePersonalCareSubcategories);
        populatePersonalCareSubcategories();
    @endif
</script>
@endsection
