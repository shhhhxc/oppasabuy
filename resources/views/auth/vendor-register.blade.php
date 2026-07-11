<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto w-full max-w-2xl">
        <h2 class="text-center text-3xl font-extrabold text-gray-900">
            Create Your OppaSabuy Vendor Account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Fill out the details below to apply as a platform merchant.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto w-full max-w-2xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form action="#" method="POST" class="space-y-6">
                @csrf

                <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Account Details</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Account ID (Auto-Generated)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" name="account_id" value="26-0001" readonly class="bg-gray-100 block w-full pr-10 border-gray-300 rounded-md focus:outline-none sm:text-sm text-gray-500 font-mono">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50/50 p-4 rounded-md border border-blue-200">
                    <h3 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-3">Selling Channel</h3>
                    <div>
                        <label for="shop_type" class="block text-sm font-medium text-gray-700">Where do you want to sell?</label>
                        <select id="shop_type" name="shop_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="oppmall">OppMall (General Multi-vendor Marketplace)</option>
                            <option value="web_store">Own Web Store (Exclusive Mini-site)</option>
                            <option value="green_mart">Green Mart (Vegetables Only)</option>
                            <option value="personal_care">Personal Care (Service Booking & Messaging)</option>
                        </select>
                    </div>
                    
                    <div class="mt-3">
                        <label for="shop_name" class="block text-sm font-medium text-gray-700">Shop / Business Name</label>
                        <input type="text" id="shop_name" name="shop_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name (As shown on Valid ID)</label>
                    <p class="text-xs text-gray-500 mb-1">Nicknames or aliases are strictly prohibited for verification.</p>
                    <input type="text" id="full_name" name="full_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>

                <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Mobile Phone Number</label>
                        <input type="text" id="phone_number" name="phone_number" placeholder="09XXXXXXXXX" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Complete Physical Address</h3>
                    
                    <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-3 sm:gap-x-4">
                        <div class="sm:col-span-1">
                            <label for="unit_no" class="block text-sm font-medium text-gray-700">House/Unit No.</label>
                            <input type="text" id="unit_no" name="unit_no" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="street" class="block text-sm font-medium text-gray-700">Street</label>
                            <input type="text" id="street" name="street" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-4 mt-4">
                        <div>
                            <label for="barangay" class="block text-sm font-medium text-gray-700">Barangay</label>
                            <input type="text" id="barangay" name="barangay" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City / Municipality</label>
                            <input type="text" id="city" name="city" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-4 mt-4">
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                            <input type="text" id="province" name="province" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                        <div>
                            <label for="zip_code" class="block text-sm font-medium text-gray-700">Zip Code</label>
                            <input type="text" id="zip_code" name="zip_code" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">
                            Password must contain at least 8 characters, including uppercase, lowercase, number, and special character.
                        </p>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6 bg-yellow-50/50 p-4 rounded-md border border-yellow-200">
                    <h3 class="text-sm font-semibold text-yellow-800 uppercase tracking-wider mb-3">Identity Verification Profile</h3>
                    <div>
                        <label for="id_type" class="block text-sm font-medium text-gray-700">Select Valid ID Type</label>
                        <select id="id_type" name="id_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 sm:text-sm rounded-md">
                            <option value="national_id">Philippine National ID</option>
                            <option value="passport">Passport</option>
                            <option value="drivers_license">Driver’s License</option>
                            <option value="umid">UMID</option>
                            <option value="sss">SSS ID</option>
                            <option value="prc">PRC ID</option>
                            <option value="postal">Postal ID</option>
                            <option value="voters">Voter’s ID</option>
                            <option value="senior">Senior Citizen ID</option>
                            <option value="philhealth">PhilHealth ID</option>
                            <option value="school_id">School ID</option>
                        </select>
                    </div>
                    <p class="mt-3 text-xs text-gray-500 italic">
                        Note: Physical document uploads via live camera interface and selfie video verification matching will follow upon submission setup.
                    </p>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2px px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 p-3">
                        Proceed with Registration Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>