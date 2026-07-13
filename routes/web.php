<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// Import Controllers
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Buyer\DashboardController as BuyerDashboard;
use App\Http\Controllers\Frontend\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\OrderChatController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Seller\SlotController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorRegistrationController;
use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\WebstoreSettingsController;
use App\Http\Controllers\VendorGreenMartController; // Inimport ang bagong Green Mart Controller
use App\Http\Controllers\Frontend\GreenMartController;
use App\Http\Controllers\PaskaayController;

/*
|--------------------------------------------------------------------------
| Public / Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [StoreController::class, 'home'])->name('welcome');

// Aggregated Multi-Vendor Discovery Marketplace Hub Layout
Route::get('/store', [StoreController::class, 'index'])->name('store'); 

Route::get('/verified-sellers', function () {
    $sellers = User::whereHas('sellerVerification', function($query) {
        $query->where('status', 'approved');
    })->with('sellerVerification')->get();
    return view('frontend.verified-sellers', compact('sellers'));
})->name('verified.sellers');

// Isolated Custom Individual Vendor Storefront Space
Route::get('/seller-store/{id}', [StoreController::class, 'show'])->name('store.show');
Route::get('/about', [StoreController::class, 'about'])->name('about');
Route::get('/contact', fn() => "Contact Page Coming Soon")->name('contact');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    $authRoute = "/auth";
    Route::get("{$authRoute}/login", [AuthController::class, 'showLoginForm'])->name('login');
    Route::get("{$authRoute}/register", [RegisterController::class, 'showRegistrationForm'])->name('register');
});

Route::post('/auth/login', [AuthController::class, 'authenticate']);
Route::post('/auth/register', [RegisterController::class, 'register']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/registration-success', [RegisterController::class, 'loginSuccess'])->name('login.success')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
 // Change the closure to use the controller method
Route::get('/home', [\App\Http\Controllers\Frontend\StoreController::class, 'home'])->name('home');
    /*
    |--------------------------------------------------------------------------
    | Community Feed Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('feed')->name('feed.')->group(function () {
        $store = 'store'; // Variable used only to isolate semantic naming
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::post('/', [PostController::class, $store])->name('store');
        Route::post('/{post}/like', [PostController::class, 'toggleLike'])->name('like');
        Route::post('/{post}/comment', [PostController::class, 'storeComment'])->name('comment');
        Route::post('/comment/{comment}/reply', [PostController::class, 'storeReply'])->name('comment.reply');
    });
    
    // Buyer Dashboard
    Route::get('/dashboard', [BuyerDashboard::class, 'index'])->name('buyer.dashboard');
    Route::put('/user/update-address', [BuyerDashboard::class, 'updateAddress'])->name('user.update-address');
    
    // FIXED: Tinanggal ang lumang SellerController endpoint dito para hindi mag-conflict 
    // at itinuro sa iisang universal VendorGreenMartController endpoint sa ibaba.
    Route::get('/store/{id}', [VendorGreenMartController::class, 'showStore'])->name('seller-store');

    /*
    |--------------------------------------------------------------------------
    | Reservation & Cart Systems
    |--------------------------------------------------------------------------
    */
 Route::prefix('reserve')->name('reservations.')->group(function () {
    Route::get('/calendar', [ReservationController::class, 'index'])->name('index');
    Route::post('/store', [ReservationController::class, 'store'])->name('store');
    
    // CHANGE THIS: Remove the leading 'reserve/'
    Route::get('/api/slots/{seller_id}', [ReservationController::class, 'getSlots'])
         ->name('slots'); 
         
    Route::get('/create/{service_id}', [ReservationController::class, 'create'])->name('booking.create');
});

    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{id}', [CartController::class, 'add'])->name('add');
        Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    });
    
    /*
    |--------------------------------------------------------------------------
    | SELLER CENTRAL (oppasabuy Vendor Control Platform)
    |--------------------------------------------------------------------------
    */
    Route::prefix('seller')->name('seller.')->group(function () {
        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
        Route::post('/upload-intro-video', [SellerDashboardController::class, 'uploadIntroVideo'])->name('upload-intro');

        // --- Reports & Goals Management ---
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/daily', [SellerDashboardController::class, 'dailyReport'])->name('daily');
            Route::get('/financial', [SellerDashboardController::class, 'financialStatement'])->name('financial');
            Route::post('/financial/store', [SellerDashboardController::class, 'storeFinancial'])->name('financial.store');
            Route::delete('/financial/{id}/delete', [SellerDashboardController::class, 'destroyFinancial'])->name('financial.destroy');
            Route::get('/goals', [SellerDashboardController::class, 'targetGoals'])->name('goals');
        });

        // Goal Actions
        Route::post('/goals', [SellerDashboardController::class, 'storeGoal'])->name('goals.store');
        Route::patch('/goals/{id}', [SellerDashboardController::class, 'update'])->name('goals.update');

        // --- VERIFIED ONLY ACTIONS ---
        Route::middleware(['verified.seller'])->group(function () {
            Route::get('/orders', [OrderChatController::class, 'sellerIndex'])->name('orders.index');
            Route::get('/orders/{order}', [OrderChatController::class, 'show'])->name('orders.show');
            
            Route::middleware(['ensure.video.intro'])->group(function () {
                Route::get('/products/create/{channel?}', [ProductController::class, 'create'])
    ->name('products.create');

Route::post('/products/store/{channel?}', [ProductController::class, 'store'])
    ->name('products.store');
                // Keep standard resource fallback controls intact for listings, edits, and updates
                Route::resource('products', ProductController::class)->except(['create', 'store']);
                Route::post('/products/{product}/broadcast', [ProductController::class, 'broadcast'])->name('products.broadcast');
                // Route endpoints for custom tracking mechanics
                Route::post('/order/{id}/upload-video', [ProductController::class, 'uploadVideo'])->name('order.video');
                Route::get('/slots', [SlotController::class, 'index'])->name('slots.index');
                Route::post('/slots', [SlotController::class, 'store'])->name('slots.store');
                
                // Invoices Generation
                Route::get('/invoices', [SellerDashboardController::class, 'invoices'])->name('invoices.index');
                Route::get('/invoices/{order}/generate', [SellerDashboardController::class, 'generateInvoice'])->name('invoice.generate');
            });
        });

        // Upgrade Routes
        Route::get('/upgrade', [SellerDashboardController::class, 'upgradePage'])->name('upgrade');
        Route::post('/upgrade', [SellerDashboardController::class, 'updatePlan'])->name('upgrade.update');
    });

    /*
    |--------------------------------------------------------------------------
    | CHAT SYSTEMS & ADMIN MANAGEMENT
    |--------------------------------------------------------------------------
    */
    // Group Rooms 
    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/{id}', [RoomController::class, 'show'])->name('show');
        Route::post('/create', [RoomController::class, 'store'])->name('store');
        Route::post('/{id}/send', [RoomController::class, 'sendMessage'])->name('send');
        Route::post('/{id}/rename', [RoomController::class, 'rename'])->name('rename');
    });

    // Order One-on-One Chats
    Route::get('/chat', [OrderChatController::class, 'index'])->name('chat.index');
    Route::get('/order-chat/{order}', [OrderChatController::class, 'show'])->name('chat.order');
    Route::post('/order-chat/{order}/send', [OrderChatController::class, 'sendMessage'])->name('chat.order.send');
    Route::post('/order-chat/{order}/status', [OrderChatController::class, 'updateStatus'])->name('chat.order.status');
    Route::post('/order-chat/{order}/complete', [OrderChatController::class, 'completeOrder'])->name('chat.order.complete');
    Route::post('/chat/order/{order}/rename', [OrderChatController::class, 'renameRoom'])->name('chat.order.rename');

    // Admin Panel
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('index');
        Route::get('/verify', [AdminController::class, 'verify'])->name('verify');
        Route::patch('/verify/{id}/{type}', [VerificationController::class, 'update'])->name('verify.update');
        Route::get('/users', [AdminController::class, 'userList'])->name('users');
        Route::get('/chats', [AdminController::class, 'chatLogs'])->name('chats');
        Route::get('/chats/{id}', [AdminController::class, 'viewConversation'])->name('chats.show');
        Route::get('/rooms/{id}', [AdminController::class, 'viewRoomConversation'])->name('rooms.show');
        Route::get('/analytics/{seller_id?}', [AdminController::class, 'sellerAnalytics'])->name('analytics');
        Route::get('/inventory', [ProductController::class, 'index'])->name('inventory');

        // --- HOMEPAGE ASSETS AND CURATION ENGINE PIPELINES ---
        Route::post('/ads/store', [AdminController::class, 'storeAd'])->name('ads.store');
        Route::post('/verse/store', [AdminController::class, 'storeVerse'])->name('verse.store');
        Route::post('/events/store', [AdminController::class, 'storeEvent'])->name('events.store');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])
        ->name('users.show');
        Route::patch('/users/{user}/lock', [AdminController::class, 'lockUser'])
            ->name('users.lock');

        Route::patch('/users/{user}/disable', [AdminController::class, 'disableUser'])
            ->name('users.disable');

        Route::patch('/users/{user}/activate', [AdminController::class, 'activateUser'])
            ->name('users.activate');
    });

    /*
    |--------------------------------------------------------------------------
    | Vendor Business Portal Enrollment & Dashboard Setup
    |--------------------------------------------------------------------------
    */
    // Registration handles are kept unprotected so they can apply initially
    Route::get('/vendor/register', [VendorRegistrationController::class, 'create'])->name('vendor.register');
    Route::post('/vendor/register', [VendorRegistrationController::class, 'store'])->name('vendor.register.store');
 Route::get('/oppamall', [App\Http\Controllers\Frontend\StoreController::class, 'indexOppamall'])->name('oppamall.index');
    // These dashboards and settings sub-modules are protected by verification checkpoints
    Route::middleware(['seller.verified'])->prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

        
        // --- Webstore Customization Studio Routes ---
        Route::get('/webstore', [WebstoreSettingsController::class, 'edit'])->name('webstore.edit');
        
        // Changed to PUT to natively resolve unified form submission updates securely
        Route::put('/webstore', [WebstoreSettingsController::class, 'update'])->name('webstore.update');
        
        // Dynamic Array Asset Mutation Pipelines (using POST instead of hidden routing wrappers)
        Route::post('/webstore/banner/{index}', [WebstoreSettingsController::class, 'removeBanner'])->name('webstore.banner.delete');
        Route::post('/webstore/certificate/{index}', [WebstoreSettingsController::class, 'removeCertificate'])->name('webstore.cert.delete');
    });
});

/*
|--------------------------------------------------------------------------
| Utilities & Profile Configurations
|--------------------------------------------------------------------------
*/
Route::get('/clear-session-cart', function () {
    session()->forget('cart');
    return redirect()->route('store')->with('success', 'Cart refreshed!');
})->name('cart.clear');

Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');

Route::middleware(['auth'])->prefix('seller')->group(function () {
    // Show creation form page interface layout
    Route::get('/products/create', [ProductController::class, 'create'])->name('seller.products.create');
    
    // Save execution row process block
    Route::post('/products', [ProductController::class, 'store'])->name('seller.products.store');

});
Route::get('/express-shipping', [ProductController::class, 'express'])->name('products.express');
Route::post('/cart/add/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');

/*
|--------------------------------------------------------------------------
| Green Mart Sub-module Hub & Profiling System
|--------------------------------------------------------------------------
*/

Route::prefix('green-mart')->group(function () {
    Route::get('/', [GreenMartController::class, 'index'])->name('greenmart.index');
});

   Route::post('/admin/ads/video', [AdminController::class, 'storePromoVideo'])->name('admin.storePromoVideo');
 

/*
|--------------------------------------------------------------------------
| OppaMall Specific Routes (Corrected Order)
|--------------------------------------------------------------------------
*/
Route::get('/oppamall', [App\Http\Controllers\Frontend\StoreController::class, 'indexOppamall'])->name('oppamall.index');
// Change from POST to GET
Route::get('/rooms/chat-with/{vendor_id}', [App\Http\Controllers\RoomController::class, 'storeDirect'])->name('rooms.store');
Route::post('/store/{id}/follow', [StoreController::class, 'toggleFollow'])->name('store.follow');

// 1. Specific route MUST come before the wildcard
Route::get('/oppamall/search', [App\Http\Controllers\Frontend\StoreController::class, 'searchOppamall'])->name('oppamall.search');

// 2. Wildcard route comes last
Route::get('/oppamall/{categoryName}', [App\Http\Controllers\Frontend\StoreController::class, 'showCategory'])->name('oppamall.category');

Route::get('/personal-care-lifestyle', [StoreController::class, 'lifestyleHub'])->name('lifestyle.hub');
// Route for clicking a specific category
Route::get('/lifestyle/category/{categoryName}', [App\Http\Controllers\Frontend\StoreController::class, 'showLifestyleCategory'])
     ->name('lifestyle.category');
Route::put('/seller/products/{product}', [ProductController::class, 'update'])->name('seller.products.update');
Route::post('/request-pasabuy', [App\Http\Controllers\Admin\InquiryController::class, 'store'])->name('pasabuy.request.submit');
Route::get('/admin/inquiries', [App\Http\Controllers\Admin\InquiryController::class, 'index'])->name('admin.inquiries');
Route::post('/service-request', [App\Http\Controllers\Admin\InquiryController::class, 'store'])->name('service.request.submit');
Route::get('/chat/{order}', [OrderChatController::class, 'show'])->name('chat.show');


/*
|--------------------------------------------------------------------------
| Rider Specific Routes (Corrected Order)
|--------------------------------------------------------------------------
*/
// Route para ipakita ang form (GET)
Route::get('/register/rider', [RiderController::class, 'showRiderRegistrationForm'])->name('register.rider');

// Route para i-process ang submit (POST)
Route::post('/register/rider', [RiderController::class, 'registerRider'])->name('register.rider.submit');

Route::get('/rider/dashboard', [RiderController::class, 'index'])
    ->name('rider.dashboard')
    ->middleware('auth');

    Route::get('/book-rider', function () {
    return view('book-rider'); // Or return a response
})->name('book.rider.page');
// Add this for the Paskaay booking submission
Route::middleware(['auth'])->group(function () {
    Route::post('/rider/book', [App\Http\Controllers\Admin\InquiryController::class, 'storeRiderBooking'])
         ->name('rider.book.submit');
});
// Idagdag mo ito:
    Route::post('/rider/accept-booking/{id}', [RiderController::class, 'acceptBooking'])->name('rider.accept.booking');


Route::post('/paskaay/store', [PaskaayController::class, 'store'])->name('paskaay.store');
Route::get('/paskaay/searching/{id}', [PaskaayController::class, 'searching'])->name('paskaay.searching');
Route::get('/paskaay/check-status/{id}', [PaskaayController::class, 'checkStatus'])->name('paskaay.check.status');
Route::get('/paskaay/tracking/{id}', [PaskaayController::class, 'tracking'])->name('paskaay.tracking');
Route::get('/paskaay/get-location/{id}', [PaskaayController::class, 'getLocation'])->name('paskaay.get-location');
Route::post('/paskaay/update-location/{id}', [PaskaayController::class, 'updateLocation'])->name('paskaay.update-location');
Route::post('/rider/update-status/{id}', [RiderController::class, 'updateStatus'])->name('rider.update.status');// Ensure this matches your existing authentication group or add it near your other product routes
Route::middleware(['auth'])->group(function () {
    Route::patch('/admin/products/{id}/toggle-sale', [ProductController::class, 'toggleSale'])
         ->name('admin.products.toggleSale');
});
Route::get('/hatid-express', [PaskaayController::class, 'index'])->name('hatid.express');
Route::get('/search-location', function(Request $request){

    $query = $request->query('q');

    $response = Http::withHeaders([
        'User-Agent' => 'Oppasabuy Hatid Express'
    ])->get('https://nominatim.openstreetmap.org/search',[
        'format'=>'json',
        'q'=>$query,
        'countrycodes'=>'ph',
        'limit'=>5
    ]);

    return response()->json($response->json());

});

Route::get('/reverse-location', function(Request $request){
    // Prevent hitting the API too fast (rate limiting)
    usleep(500000); // 0.5 second delay

    $response = Http::withHeaders([
        'User-Agent' => 'Oppasabuy-Hatid-Express-App'
    ])->get('https://nominatim.openstreetmap.org/reverse', [
        'format' => 'json',
        'lat' => $request->lat,
        'lon' => $request->lng,
        'zoom' => 18,
        'addressdetails' => 1
    ]);

    $data = $response->json();

    // Return the display_name, or a fallback if the API fails
    return response()->json([
        'display_name' => $data['display_name'] ?? 'Location Selected'
    ]);
});
Route::post('/paskaay/update-location/{id}', [App\Http\Controllers\PaskaayController::class, 'updateLocation'])
     ->name('paskaay.update-location');
     Route::get('/paskaay/receipt/{id}', [App\Http\Controllers\PaskaayController::class, 'showReceipt'])->name('customer.receipt');
Route::post('/paskaay/rate-rider/{id}', [App\Http\Controllers\PaskaayController::class, 'rateRider'])->name('customer.rate');
Route::delete('/paskaay/cancel/{id}', [App\Http\Controllers\PaskaayController::class, 'cancel'])->name('paskaay.cancel');


Route::middleware('auth')->group(function () {
    Route::get('/membership', function () {
        return view('membership.index');
    })->name('membership.index');
});


Route::get('/create-render-admin', function () {
    $admin = User::updateOrCreate(
        ['email' => 'admin@oppasabuy.com'],
        [
            'full_name' => 'Administrator',
            'name' => 'Administrator',
            'password' => Hash::make('Admin123!'),
            'role' => 'admin',
            'status' => 'active',
            'is_verified' => 1,
            'verification_status' => 'approved',
        ]
    );

    return 'Render admin created successfully.';
});