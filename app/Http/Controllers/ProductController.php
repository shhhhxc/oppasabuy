<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\SellerVerification;
use App\Models\User;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private function getCategories()
    {
        return collect([
            ['name' => 'Health & Personal Care', 'subcategories' => ['Medical Supplies', 'Men\'s Grooming', 'Health Supplements', 'Slimming', 'Sun-care', 'Whitening', 'Personal Care', 'Bath & Body', 'Hair Care', 'Skin Care', 'Others']],
            ['name' => 'Mobiles & Gadgets', 'subcategories' => ['Wearables', 'E-Cigarettes', 'Tablets', 'Mobile', 'Mobile & Gadgets Accessories']],
            ['name' => 'Laptops & Computer', 'subcategories' => ['USB Gadgets', 'Computer Hardware', 'Software', 'Printer and Inks', 'Storage', 'Computer Accessories', 'Network Components', 'Laptops', 'Desktops', 'Others']],
            ['name' => 'Babies & Kids', 'subcategories' => ['Baby Detergent', 'Babies\' Fashion', 'Rain Gear', 'Nursery', 'Moms & Maternity', 'Baby Gear', 'Health & Safety', 'Bath & Skin Care', 'Boys\' Fashion', 'Girls\' Fashion', 'Feeding & Nursing', 'Feeding', 'Diapers & Wipes', 'Others']],
            ['name' => 'Men\'s Apparel', 'subcategories' => ['Tops', 'Shorts', 'Pants', 'Jeans', 'Underwear', 'Socks', 'Hoodies & Sweatshirts', 'Jackets & Sweaters', 'Sleepwear', 'Suits', 'Sets', 'Occupational Attire', 'Traditional Wear', 'Costumes', 'Others']],
            ['name' => 'Home & Living', 'subcategories' => ['Hand Warmers, Hot water bags & Ice Bags', 'Home Maintenance', 'Furniture', 'Lighting', 'Party Supplies', 'Beddings', 'Bath', 'Glassware & Drinkware', 'Bakeware', 'Kitchenware', 'Sinkware', 'Power Tools', 'Home Improvement', 'Storage & Organization', 'Home Decor', 'Garden Decor', 'Outdoor & Garden', 'Others']],
            ['name' => 'Home Appliances', 'subcategories' => ['Small Household Appliances', 'Home Appliances Parts & Accessories', 'Large Appliances', 'Vacuum Cleaners & Floor Care', 'Humidifier & Air Purifier', 'Cooling & Heating', 'Specialty Appliances', 'Small Kitchen Appliances', 'Garment Care']],
            ['name' => 'Sports & Travel', 'subcategories' => ['Travel Bags', 'Travel Accessories', 'Travel Organizer', 'Kid\'s Activewear', 'Boxing & MMA', 'Weather Protection', 'Winter Sports Gear', 'Outdoor Recreation', 'Leisure Sports & Game Room', 'Golf', 'Racket Sports', 'Sports Bags', 'Women\'s Activewear', 'Men\'s Activewear', 'Cycling, Skates & Scooters', 'Team Sports', 'Water Sports', 'Camping & Hiking', 'Weightlifting', 'Fitness Accessory', 'Yoga']],
            ['name' => 'Groceries', 'subcategories' => ['Seasoning, Staple Foods & Baking Ingredients', 'Gift Set & Hampers', 'Dairy & Eggs', 'Cigarettes', 'Superfoods & Healthy Foods', 'Breakfast Food', 'Snack & Sweets', 'Frozen & Fresh Foods', 'Alcoholic Beverages', 'Laundry & Household Care', 'Beverages', 'Others']],
            ['name' => 'Hobbies & Stationery', 'subcategories' => ['Books and Magazines', 'Paper Supplies', 'Writing Materials', 'Religious Artifacts', 'Packaging & Wrapping', 'Arts & Crafts', 'School & Office Supplies', 'Musical Instruments', 'Others']],
            ['name' => 'Women Accessories', 'subcategories' => ['Jewelry', 'Watches', 'Hair Accessories', 'Eyewear', 'Wallets & Pouches', 'Hats & Caps', 'Belts & Scarves', 'Gloves', 'Accessories Sets & Packages', 'Additional Accessories', 'Watch & Jewelry Organizers', 'Others']],
            ['name' => 'Makeup & Fragrances', 'subcategories' => ['Palettes & Makeup Sets', 'Tools & Accessories', 'Nails', 'Fragrances', 'Face Makeup', 'Lip Makeup', 'Eye Makeup', 'Others']],
            ['name' => 'Motor', 'subcategories' => ['Car Care & Detailing', 'Automotive Parts', 'Engine Parts', 'Ignition', 'Exterior Car Accessories', 'Oils, Coolants, & Fluids', 'Car Electronics', 'Moto Riding & Protective Gear', 'Tools & Garage', 'Motorcycle Accessories', 'Motorcycle & ATV Parts', 'Interior Car Accessories', 'Others']],
            ['name' => 'Women\'s Apparel', 'subcategories' => ['Dresses', 'Tops', 'Tees', 'Shorts', 'Pants', 'Jeans', 'Skirts', 'Jumpsuits & Rompers', 'Lingerie & Nightwear', 'Sets', 'Swimsuit', 'Jackets & Outerwear', 'Plus Size', 'Sweater & Cardigans', 'Maternity Wear', 'Socks & Stockings', 'Traditional Wear', 'Fabric']],
            ['name' => 'Toys, Games & Collectibles', 'subcategories' => ['Celebrity Merchandise', 'Dress Up & Pretend', 'Blasters & Toy', 'Sports & Outdoor Toys', 'Dolls', 'Educational Toys', 'Electronic Toys', 'Boards & Family Games', 'Collectibles', 'Character', 'Action Figure', 'Others']],
            ['name' => 'Home Entertainment', 'subcategories' => ['Projectors', 'TV Accessories', 'Television', 'Others']],
            ['name' => 'Men\'s Bags & Accessories', 'subcategories' => ['Hats & Caps', 'Wallets', 'Eyewear', 'Men\'s Accessories', 'Jewelry', 'Watches', 'Men\'s Bags', 'Accessories Sets & Packages']],
            ['name' => 'Pet Care', 'subcategories' => ['Toys & Accessories', 'Litter & Toilet', 'Pet Essentials', 'Pet Clothing & Accessories', 'Pet Grooming Supplies', 'Pet Toys & Accessories', 'Pet Food & Treats', 'Others']],
            ['name' => 'Gaming', 'subcategories' => ['Computer Gaming', 'Mobile Gaming', 'Console Gaming', 'Others']],
            ['name' => 'Women\'s Shoes', 'subcategories' => ['Flats', 'Heels', 'Flip Flops', 'Sneakers', 'Wedges & Platforms', 'Boots', 'Shoe Care & Accessories', 'Others']],
            ['name' => 'Cameras', 'subcategories' => ['Car/Dash Camera', 'Drones', 'CCTV / IP Camera', 'Action Camera', 'Camera Accessories', 'Digital Camera', 'Others']],
            ['name' => 'Men\'s Shoes', 'subcategories' => ['Loafer & Boat Shoes', 'Sneakers', 'Sandals & Flip Flops', 'Boots', 'Formal', 'Shoe Care & Accessories', 'Others']],
            ['name' => 'Women\'s Bags', 'subcategories' => ['Shoulder Bags', 'Tote Bags', 'HandBags', 'Clutches', 'Backpacks', 'Drawstrings', 'Accessories', 'Others']],
            ['name' => 'Audio', 'subcategories' => ['Audio & Video Cables', 'Converters', 'Amplifiers & Mixers', 'Speakers & Karaoke', 'Home Audio & Speakers', 'Media Players']],
        ]);
    }

    public function index()
{
    $userId = Auth::id();

    $store = SellerVerification::where('user_id', $userId)->first();

    $products = Product::where('seller_id', $userId)
        ->latest()
        ->get();

    $userRooms = Room::where(function ($query) use ($userId) {
        $query->where('creator_id', $userId)
            ->orWhereHas('users', function ($userQuery) use ($userId) {
                $userQuery->where('users.id', $userId);
            });
    })
        ->with(['users'])
        ->withCount('users')
        ->latest()
        ->get();

    $userOrders = Order::where(function ($query) use ($userId) {
    $query->where('seller_id', $userId)
        ->orWhere('buyer_id', $userId);
})
    ->with([
        'seller.sellerVerification',
        'buyer',
        'items.product',
        'messages'
    ])
    ->withCount([
        'messages as unread_messages_count' => function ($query) use ($userId) {
            $query->where('user_id', '!=', $userId);
        }
    ])
    ->latest()
    ->get();

    $availableUsers = User::where('id', '!=', $userId)
        ->orderBy('name')
        ->get();

    $messages = collect();

    $broadcastOrders = Order::where('seller_id', $userId)
        ->whereNotNull('buyer_id')
        ->with(['buyer', 'messages'])
        ->oldest()
        ->get()
        ->unique('buyer_id');

    $broadcastReadBuyers = collect();
    $broadcastUnreadBuyers = collect();

    foreach ($broadcastOrders as $order) {
        if (!$order->buyer) {
            continue;
        }

        $lastBuyerMessage = $order->messages
            ->where('user_id', $order->buyer_id)
            ->sortByDesc('created_at')
            ->first();

        $hasUnread = $lastBuyerMessage && (
            !$order->seller_last_read_at ||
            $lastBuyerMessage->created_at->gt($order->seller_last_read_at)
        );

        if ($hasUnread) {
            $broadcastUnreadBuyers->push($order->buyer);
        } else {
            $broadcastReadBuyers->push($order->buyer);
        }
    }

    $broadcastBuyers = $broadcastReadBuyers
        ->merge($broadcastUnreadBuyers)
        ->unique('id')
        ->values();

    $lifestyleCategories = \App\Models\LifestyleCategory::all()
        ->groupBy('parent_category');

    $oppaMallCategories = $this->getCategories();

    return view('seller.products.index', compact(
        'store',
        'products',
        'userRooms',
        'userOrders',
        'availableUsers',
        'messages',
        'lifestyleCategories',
        'oppaMallCategories',
        'broadcastBuyers',
        'broadcastReadBuyers',
        'broadcastUnreadBuyers'
    ));
}

    public function create($channel = 'oppa_mall')
{
    $allowedChannels = [
        'oppa_mall',
        'green_market',
        'own_webstore',
        'personal_care',
    ];

    if (!in_array($channel, $allowedChannels)) {
        $channel = 'oppa_mall';
    }

    $categories = [];

    if ($channel === 'oppa_mall') {
        $categories = $this->getCategories();
    }

    if ($channel === 'personal_care') {
        $categories = \App\Models\LifestyleCategory::all()
            ->groupBy('parent_category');
    }

    return view('seller.products.create', compact(
        'channel',
        'categories'
    ));
}
    public function store(Request $request, $channel = 'oppa_mall')
    {
        $rules = [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category' => 'required|string',
            'description' => 'nullable|string',
        ];

        if ($channel === 'oppa_mall') {
            $rules['subcategory'] = 'required|string';
        }

        if ($channel === 'green_market') {
            $rules['green_market_type'] = 'required|in:wet_market,sari_sari';
        }

        if ($channel === 'personal_care') {
            $rules += [
                'subcategory' => 'required|string',
                'price' => 'nullable|numeric',
                'stock' => 'nullable|integer',
                'service_location' => 'nullable|string',
                'duration' => 'nullable|integer'
            ];
        } else {
            $rules += [
                'price' => 'required|numeric',
                'stock' => 'required|integer'
            ];
        }

        $validated = $request->validate($rules);
        $imagePath = $request->file('image')->store('products', 'public');

        $metaData = [];

        if ($channel === 'oppa_mall') {
            $metaData['subcategory'] = $request->subcategory;
        }

        if ($channel === 'green_market') {
            $metaData['green_market_type'] = $request->green_market_type;
        }

        if ($channel === 'personal_care') {
            $metaData['subcategory'] = $request->subcategory;
            $metaData['service_location'] = $request->service_location;
            $metaData['duration'] = $request->duration;
        }

        Product::create([
            'user_id' => Auth::id(),
            'seller_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $request->price ?? null,
            'stock' => $request->stock ?? 1,
            'category' => $validated['category'],
            'channel' => $channel,
            'meta_data' => $metaData,
            'image_path' => $imagePath,
            'is_featured' => 0,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Product listed successfully!');
    }

    public function uploadVideo(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $path = $request->file('video')->store('order_videos', 'public');
        $order->update(['video_proof' => $path, 'status' => 'video_uploaded']);

        return back()->with('success', 'Video proof uploaded successfully!');
    }

    public function destroy($id)
    {
        $product = Product::where('id', $id)->where('seller_id', Auth::id())->firstOrFail();

        if ($product->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Product deleted successfully!');
    }

    public function express()
    {
        $products = Product::where('channel', 'own_webstore')
            ->latest()
            ->paginate(24);

        $randomBanners = SellerVerification::whereNotNull('banner_path')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('frontend.store.express', compact('products', 'randomBanners'));
    }

    public function add(Product $product)
    {
        $cart = session()->get('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + 1;
        session()->put('cart', $cart);

        return response()->json(['success' => true, 'message' => 'Added to cart']);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $meta = $product->meta_data;

        if (is_string($meta)) {
            $meta = json_decode($meta, true) ?? [];
        } elseif (!is_array($meta)) {
            $meta = [];
        }

        $meta['subcategory'] = $request->input('subcategory');

        $product->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => ($product->channel !== 'personal_care') ? $request->price : $product->price,
            'stock' => ($product->channel !== 'personal_care') ? $request->stock : $product->stock,
            'meta_data' => $meta,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Product updated successfully.');
    }

    public function toggleSale(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->discount_value = $request->discount_value;
        $product->discount_type = $request->discount_type;
        $product->on_sale = !$product->on_sale;
        $product->save();

        return redirect()->back()->with('success', 'Sale configuration updated.');
    }

    public function getFinalPriceAttribute()
    {
        if (!$this->on_sale) {
            return $this->price;
        }

        if ($this->discount_type === 'percent') {
            return $this->price - ($this->price * ($this->discount_value / 100));
        }

        return $this->price - $this->discount_value;
    }

    public function broadcast(Request $request, Product $product)
    {
        $request->validate([
            'buyer_ids' => 'required|array',
            'buyer_ids.*' => 'exists:users,id',
        ]);

        $product = Product::where('id', $product->id)
            ->where('seller_id', Auth::id())
            ->firstOrFail();

        $orders = Order::where('seller_id', Auth::id())
            ->whereIn('buyer_id', $request->buyer_ids)
            ->whereNotNull('buyer_id')
            ->oldest()
            ->get()
            ->unique('buyer_id');

        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'No selected buyer order chats found.');
        }

        $priceText = $product->price ? '₱' . number_format($product->price, 2) : 'Quote-Based';
        $stockText = $product->channel === 'personal_care' ? 'Service Available' : ($product->stock ?? 0);
        $description = $product->description ? "\n\n{$product->description}" : '';

        foreach ($orders as $order) {
            $order->messages()->create([
                'user_id' => Auth::id(),
                'image_path' => $product->image_path,
                'message' => "📢 NEW PRODUCT AVAILABLE!\n\n{$product->name}\n\n💰 Price: {$priceText}\n📦 Stock: {$stockText}{$description}\n\nMessage us here if you want to order."
            ]);
        }

        return redirect()->back()->with('success', 'Product broadcast sent to selected buyer order chats.');
    }
}