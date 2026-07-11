<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Store;
use App\Models\SellerVerification;
use App\Models\Ad;
use App\Models\BibleVerse;
use App\Models\Event;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Shared method to provide categories to views
     */
    public function getCategories()
    {
        return collect([
            ['name' => 'Health & Personal Care', 'icon' => 'bi-heart-pulse', 'subcategories' => ['Medical Supplies', 'Men\'s Grooming', 'Health Supplements', 'Slimming', 'Sun-care', 'Whitening', 'Personal Care', 'Bath & Body', 'Hair Care', 'Skin Care', 'Others']],
            ['name' => 'Mobiles & Gadgets', 'icon' => 'bi-phone', 'subcategories' => ['Wearables', 'E-Cigarettes', 'Tablets', 'Mobile', 'Mobile & Gadgets Accessories']],
            ['name' => 'Laptops & Computer', 'icon' => 'bi-laptop', 'subcategories' => ['USB Gadgets', 'Computer Hardware', 'Software', 'Printer and Inks', 'Storage', 'Computer Accessories', 'Network Components', 'Laptops', 'Desktops', 'Others']],
            ['name' => 'Babies & Kids', 'icon' => 'bi-balloon', 'subcategories' => ['Baby Detergent', 'Babies\' Fashion', 'Rain Gear', 'Nursery', 'Moms & Maternity', 'Baby Gear', 'Health & Safety', 'Bath & Skin Care', 'Boys\' Fashion', 'Girls\' Fashion', 'Feeding & Nursing', 'Feeding', 'Diapers & Wipes', 'Others']],
            ['name' => 'Men\'s Apparel', 'icon' => 'bi-person-badge', 'subcategories' => ['Tops', 'Shorts', 'Pants', 'Jeans', 'Underwear', 'Socks', 'Hoodies & Sweatshirts', 'Jackets & Sweaters', 'Sleepwear', 'Suits', 'Sets', 'Occupational Attire', 'Traditional Wear', 'Costumes', 'Others']],
            ['name' => 'Home & Living', 'icon' => 'bi-house-door', 'subcategories' => ['Hand Warmers, Hot water bags & Ice Bags', 'Home Maintenance', 'Furniture', 'Lighting', 'Party Supplies', 'Beddings', 'Bath', 'Glassware & Drinkware', 'Bakeware', 'Kitchenware', 'Sinkware', 'Power Tools', 'Home Improvement', 'Storage & Organization', 'Home Decor', 'Garden Decor', 'Outdoor & Garden', 'Others']],
            ['name' => 'Home Appliances', 'icon' => 'bi-cpu', 'subcategories' => ['Small Household Appliances', 'Home Appliances Parts & Accessories', 'Large Appliances', 'Vacuum Cleaners & Floor Care', 'Humidifier & Air Purifier', 'Cooling & Heating', 'Specialty Appliances', 'Small Kitchen Appliances', 'Garment Care']],
            ['name' => 'Sports & Travel', 'icon' => 'bi-bicycle', 'subcategories' => ['Travel Bags', 'Travel Accessories', 'Travel Organizer', 'Kid\'s Activewear', 'Boxing & MMA', 'Weather Protection', 'Winter Sports Gear', 'Outdoor Recreation', 'Leisure Sports & Game Room', 'Golf', 'Racket Sports', 'Sports Bags', 'Women\'s Activewear', 'Men\'s Activewear', 'Cycling, Skates & Scooters', 'Team Sports', 'Water Sports', 'Camping & Hiking', 'Weightlifting', 'Fitness Accessory', 'Yoga']],
            ['name' => 'Groceries', 'icon' => 'bi-cart', 'subcategories' => ['Seasoning, Staple Foods & Baking Ingredients', 'Gift Set & Hampers', 'Dairy & Eggs', 'Cigarettes', 'Superfoods & Healthy Foods', 'Breakfast Food', 'Snack & Sweets', 'Frozen & Fresh Foods', 'Alcoholic Beverages', 'Laundry & Household Care', 'Beverages', 'Others']],
            ['name' => 'Hobbies & Stationery', 'icon' => 'bi-book', 'subcategories' => ['Books and Magazines', 'Paper Supplies', 'Writing Materials', 'Religious Artifacts', 'Packaging & Wrapping', 'Arts & Crafts', 'School & Office Supplies', 'Musical Instruments', 'Others']],
            ['name' => 'Women Accessories', 'icon' => 'bi-gem', 'subcategories' => ['Jewelry', 'Watches', 'Hair Accessories', 'Eyewear', 'Wallets & Pouches', 'Hats & Caps', 'Belts & Scarves', 'Gloves', 'Accessories Sets & Packages', 'Additional Accessories', 'Watch & Jewelry Organizers', 'Others']],
            ['name' => 'Makeup & Fragrances', 'icon' => 'bi-palette', 'subcategories' => ['Palettes & Makeup Sets', 'Tools & Accessories', 'Nails', 'Fragrances', 'Face Makeup', 'Lip Makeup', 'Eye Makeup', 'Others']],
            ['name' => 'Motor', 'icon' => 'bi-car-front', 'subcategories' => ['Car Care & Detailing', 'Automotive Parts', 'Engine Parts', 'Ignition', 'Exterior Car Accessories', 'Oils, Coolants, & Fluids', 'Car Electronics', 'Moto Riding & Protective Gear', 'Tools & Garage', 'Motorcycle Accessories', 'Motorcycle & ATV Parts', 'Interior Car Accessories', 'Others']],
            ['name' => 'Women\'s Apparel', 'icon' => 'bi-handbag', 'subcategories' => ['Dresses', 'Tops', 'Tees', 'Shorts', 'Pants', 'Jeans', 'Skirts', 'Jumpsuits & Rompers', 'Lingerie & Nightwear', 'Sets', 'Swimsuit', 'Jackets & Outerwear', 'Plus Size', 'Sweater & Cardigans', 'Maternity Wear', 'Socks & Stockings', 'Traditional Wear', 'Fabric']],
            ['name' => 'Toys, Games & Collectibles', 'icon' => 'bi-controller', 'subcategories' => ['Celebrity Merchandise', 'Dress Up & Pretend', 'Blasters & Toy', 'Sports & Outdoor Toys', 'Dolls', 'Educational Toys', 'Electronic Toys', 'Boards & Family Games', 'Collectibles', 'Character', 'Action Figure', 'Others']],
            ['name' => 'Home Entertainment', 'icon' => 'bi-tv', 'subcategories' => ['Projectors', 'TV Accessories', 'Television', 'Others']],
            ['name' => 'Men\'s Bags & Accessories', 'icon' => 'bi-briefcase', 'subcategories' => ['Hats & Caps', 'Wallets', 'Eyewear', 'Men\'s Accessories', 'Jewelry', 'Watches', 'Men\'s Bags', 'Accessories Sets & Packages']],
            ['name' => 'Pet Care', 'icon' => 'bi-egg-fried', 'subcategories' => ['Toys & Accessories', 'Litter & Toilet', 'Pet Essentials', 'Pet Clothing & Accessories', 'Pet Grooming Supplies', 'Pet Toys & Accessories', 'Pet Food & Treats', 'Others']],
            ['name' => 'Gaming', 'icon' => 'bi-joystick', 'subcategories' => ['Computer Gaming', 'Mobile Gaming', 'Console Gaming', 'Others']],
            ['name' => 'Women\'s Shoes', 'icon' => 'bi-highlighter', 'subcategories' => ['Flats', 'Heels', 'Flip Flops', 'Sneakers', 'Wedges & Platforms', 'Boots', 'Shoe Care & Accessories', 'Others']],
            ['name' => 'Cameras', 'icon' => 'bi-camera', 'subcategories' => ['Car/Dash Camera', 'Drones', 'CCTV / IP Camera', 'Action Camera', 'Camera Accessories', 'Digital Camera', 'Others']],
            ['name' => 'Men\'s Shoes', 'icon' => 'bi-bootstrap-reboot', 'subcategories' => ['Loafer & Boat Shoes', 'Sneakers', 'Sandals & Flip Flops', 'Boots', 'Formal', 'Shoe Care & Accessories', 'Others']],
            ['name' => 'Women\'s Bags', 'icon' => 'bi-handbag-fill', 'subcategories' => ['Shoulder Bags', 'Tote Bags', 'HandBags', 'Clutches', 'Backpacks', 'Drawstrings', 'Accessories', 'Others']],
            ['name' => 'Audio', 'icon' => 'bi-speaker', 'subcategories' => ['Audio & Video Cables', 'Converters', 'Amplifiers & Mixers', 'Speakers & Karaoke', 'Home Audio & Speakers', 'Media Players']],
        ]);
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $meta = json_decode($product->meta_data, true);
        
        $cartItem = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image_path,
            'category' => $product->category,
            'subcategory' => $meta['subcategory'] ?? 'N/A'
        ];
    }

    public function indexOppamall(Request $request)
    {
        $events = Event::latest()->get();
        $categories = $this->getCategories();
        $selectedCategory = $request->query('category');

        $query = Product::where('channel', 'oppa_mall');
        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }
        $products = $query->latest()->get();

        return view('frontend.oppamall.index', compact('events', 'categories', 'products', 'selectedCategory'));
    }

    public function home()
    {
        // UPDATED: Fetches only oppa_mall products for the home page
        $products = Product::where('channel', 'oppa_mall')->latest()->take(6)->get(); 
        $activeAd = Ad::whereIn('is_active', [1, '1', true])->latest()->first();
        $publishedVerse = BibleVerse::whereIn('is_published', [1, '1', true])->latest()->first();
        $categories = $this->getCategories();

        $topProducts = Product::where('on_sale', true)
                          ->take(6)
                          ->get();

        return view('frontend.home', compact('products', 'activeAd', 'publishedVerse', 'categories','topProducts'));
    }
 
   public function index(Request $request)
{
    // Eager load with filters
    $products = Product::with(['seller.sellerVerification'])
        ->when($request->category, fn($q) => $q->where('category', $request->category))
        ->latest()
        ->paginate(12);

    // Optimized: Only fetch what you need for banners/promos
    $randomBanners = SellerVerification::whereNotNull('banner_path')
        ->inRandomOrder()
        ->limit(5)
        ->get();

    $randomPromotions = SellerVerification::whereNotNull('promotional_text')
        ->inRandomOrder()
        ->limit(3)
        ->get();

    $vendorsList = Store::whereJsonContains('store_types', 'own_webstore')
        ->latest()
        ->paginate(12, ['*'], 'vendors_page');

    $fastShippingProducts = Product::where('channel', 'own_webstore')
        ->latest()
        ->take(8) // Increased to 8 to fill the grid better
        ->get();

    $activeAd = Ad::where('is_active', true)->latest()->first();
    $publishedVerse = BibleVerse::where('is_published', true)->latest()->first();

    return view('frontend.store.index', compact(
        'products', 'randomBanners', 'randomPromotions', 'vendorsList', 
        'fastShippingProducts', 'activeAd', 'publishedVerse'
    ));
}

 public function show(Request $request, $id)
{
    // Fetch the vendor user and their associated store
    $vendor = User::with(['sellerVerification', 'store'])->findOrFail($id);
    
    // Assign the store instance (will be null if the user hasn't created a store)
    $store = $vendor->store;
    
    $search = $request->input('search');
    $query = Product::where('seller_id', $id);

    if ($search) {
        $query->where(function($q) use ($search) {
            if (strlen($search) <= 2) {
                $q->where('name', 'LIKE', "{$search}%")->orWhere('category', 'LIKE', "{$search}%");
            } else {
                $q->where('name', 'LIKE', "%{$search}%")->orWhere('category', 'LIKE', "%{$search}%");
            }
        });
    }
   $productsByChannel = Product::where('seller_id', $id)
                                ->where('channel', 'oppa_mall')
                                ->latest()
                                ->get()
                                ->groupBy('channel');
    $products = $query->latest()->get();
    $storeConfig = $vendor->sellerVerification;

    if (!$storeConfig) {
        $storeConfig = new SellerVerification();
        $storeConfig->user_id = $vendor->id;
        $storeConfig->promotional_text = 'Welcome to our webstore showcase arena!';
    } else {
        $storeConfig->banner_slider_paths = is_string($storeConfig->banner_slider_paths) ? json_decode($storeConfig->banner_slider_paths, true) : [];
        $storeConfig->contact_representatives = is_string($storeConfig->contact_representatives) ? json_decode($storeConfig->contact_representatives, true) : [];
        $storeConfig->certificates_data = is_string($storeConfig->certificates_data) ? json_decode($storeConfig->certificates_data, true) : [];
    }

    // Pass 'store' to the view
    return view('frontend.store.show', compact('vendor', 'products', 'search', 'storeConfig', 'store','productsByChannel')); 
}

    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'store_types' => 'required|array',
            'business_email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:50',
            'shop_description' => 'required|string',
            'agree_terms' => 'required|accepted',
        ]);

        $logoPath = $request->hasFile('store_logo') ? $request->file('store_logo')->store('logos', 'public') : null;
        $address = $request->has('no_physical_store') ? 'Online Only' : $request->store_address;

        Store::create([
            'user_id' => auth()->id(),
            'name' => $request->shop_name,
            'logo' => $logoPath,
            'address' => $address,
            'business_email' => $request->business_email,
            'phone_number' => $request->phone_number,
            'description' => $request->shop_description,
            'store_types' => $request->store_types,
            'green_market_type' => $request->green_market_type,
        ]);

        return redirect()->route('store')->with('success', 'Your shop has been successfully launched!');
    }

    public function orderIndex()
    {
        $store = SellerVerification::where('user_id', auth()->id())->first();
        $allOrders = \App\Models\Order::where('seller_id', auth()->id())
            ->with(['buyer', 'items.product']) 
            ->latest()
            ->get();

        return view('seller.orders.index', compact('store', 'allOrders'));
    }

    public function about()
    {
        $team = [
            ['name' => 'Ms. Edeza', 'role' => 'Founder & CEO', 'image' => 'edeza.jpg', 'bio' => 'Visionary behind Oppasabuy.'],
            ['name' => 'Ms. Roxy', 'role' => 'Co-Founder', 'image' => 'roxy.jpg', 'bio' => 'Ensuring seamless logistics.']
        ];
        return view('frontend.about', compact('team'));
    }

    public function showCategory(Request $request, $categoryName)
    {
        $allCats = $this->getCategories(); 
        $catData = $allCats->firstWhere('name', $categoryName);
        $subcategories = $catData ? $catData['subcategories'] : [];

        $query = Product::where('category', $categoryName)->where('channel', 'oppa_mall');

        if ($request->filled('subcategory')) {
            $query->where(function($q) use ($request) {
                foreach ($request->subcategory as $sub) {
                    $q->orWhere('meta_data', 'LIKE', '%"subcategory":"' . $sub . '"%')
                      ->orWhere('meta_data', 'LIKE', '%' . $sub . '%');
                }
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
        }

        $products = $query->latest()->get();
        return view('frontend.oppamall.oppamall_category', compact('products', 'categoryName', 'subcategories'));
    }

    public function searchOppamall(Request $request)
    {
        $searchTerm = $request->query('search');
        $query = Product::where('channel', 'oppa_mall');

        if ($searchTerm) {
            $term = trim($searchTerm);
            $query->where('name', 'LIKE', '%' . $term . '%');
        }

        $products = $query->get();
        return view('frontend.oppamall.oppamall_category', [
            'products' => $products,
            'categoryName' => 'Search Results: ' . $searchTerm,
            'subcategories' => []
        ]);
    }

public function lifestyleHub(Request $request)
{
    // Fetch products
    $products = Product::where('channel', 'personal_care')->latest()->get();

    // Fetch categories grouped by parent
    // This allows you to loop through them in your Blade view
    $categories = \App\Models\LifestyleCategory::all()->groupBy('parent_category');

    return view('frontend.lifestyle.index', compact('products', 'categories'));
}

public function showLifestyleCategory(Request $request, $categoryName)
{
    // 1. Get subcategories for the filter list (Keep this as is)
    $subcategories = \App\Models\LifestyleCategory::where('parent_category', $categoryName)
        ->pluck('name')
        ->toArray();

    // 2. Query products by filtering the 'category' column 
    // and the JSON 'meta_data' column
    $query = \App\Models\Product::where('channel', 'personal_care')
        ->where('category', $categoryName);

    // 3. Filter by Subcategory 
    // Since it's in the meta_data JSON, we use a WHERE clause on that column
    if ($request->filled('subcategory')) {
        $query->where(function($q) use ($request) {
            foreach ($request->subcategory as $sub) {
                // Adjust the string to match how it is saved in your DB
                // Example: meta_data = {"subcategory": "Nail Care"}
                $q->orWhere('meta_data', 'LIKE', '%"subcategory":"' . $sub . '"%');
            }
        });
    }

    // 4. Filter by Price
    if ($request->filled('min_price')) {
        $query->where('price', '>=', (float)$request->min_price);
    }
    if ($request->filled('max_price')) {
        $query->where('price', '<=', (float)$request->max_price);
    }

    $services = $query->latest()->get();

    return view('frontend.lifestyle.lifestyle_category', compact(
        'services', 
        'categoryName', 
        'subcategories'
    ));
}

public function toggleFollow($id) {
    $store = \App\Models\Store::findOrFail($id);
    $userId = auth()->id();
    
    $followers = $store->followers ?? [];
    
    if (in_array($userId, $followers)) {
        // Unfollow: Remove ID from array
        $followers = array_diff($followers, [$userId]);
    } else {
        // Follow: Add ID to array
        $followers[] = $userId;
    }
    
    $store->followers = array_values($followers); // Re-index array
    $store->save();
    
    return back();
}
}