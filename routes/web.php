<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DealBrowseController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Static pages routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/{category:slug}/products', [CategoryController::class, 'products'])->name('categories.products');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', function () {
    // Handle contact form submission
    return back()->with('success', 'Message sent successfully!');
})->name('contact.submit');

// Campaigns routes
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{campaign:slug}', [CampaignController::class, 'show'])->name('campaigns.show');

Route::get('/dashboard', function () {
    // If user is admin, redirect to admin dashboard
    if (auth()->check() && auth()->user()->hasRole(['super_admin', 'admin'])) {
        return redirect()->route('admin.dashboard.index');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Public Deals routes
Route::get('/deals', [DealBrowseController::class, 'index'])->name('deals.index');
Route::get('/deals/{deal}', [DealBrowseController::class, 'show'])->name('deals.show');

// Cart and Checkout routes
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout/coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
});

// Customer Dashboard routes
Route::middleware('auth')->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::patch('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Wishlist routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::patch('/wishlist/{item}', [WishlistController::class, 'update'])->name('wishlist.update');
    Route::delete('/wishlist/{item}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::delete('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
    Route::post('/wishlist/move-to-cart/{item}', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');
    Route::post('/wishlist/add-multiple', [WishlistController::class, 'addMultiple'])->name('wishlist.add-multiple');
    Route::get('/wishlist/count', [WishlistController::class, 'getCount'])->name('wishlist.count');
    
    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('orders/{order}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');
    
    Route::resource('addresses', AddressController::class);
    Route::post('addresses/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.set-default');
});

// Admin routes
Route::middleware(['auth', 'role:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', 'App\Http\Controllers\Admin\DashboardController@index')->name('dashboard.index');
    Route::get('/sales-report', 'App\Http\Controllers\Admin\DashboardController@salesReport')->name('dashboard.sales-report');
    Route::get('/inventory-report', 'App\Http\Controllers\Admin\DashboardController@inventoryReport')->name('dashboard.inventory-report');
    Route::get('/user-report', 'App\Http\Controllers\Admin\DashboardController@userReport')->name('dashboard.user-report');
    
    // Product Management
    Route::resource('products', 'App\Http\Controllers\Admin\ProductController');
    Route::post('products/bulk-delete', [App\Http\Controllers\Admin\ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
    Route::post('products/{product}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    
    // Category Management
    Route::resource('categories', 'App\Http\Controllers\Admin\CategoryController');
    Route::post('categories/bulk-delete', [App\Http\Controllers\Admin\CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
    Route::post('categories/{category}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    
    // Brand Management
    Route::resource('brands', 'App\Http\Controllers\Admin\BrandController');
    Route::post('brands/bulk-delete', [App\Http\Controllers\Admin\BrandController::class, 'bulkDelete'])->name('brands.bulk-delete');
    Route::post('brands/{brand}/toggle-status', [App\Http\Controllers\Admin\BrandController::class, 'toggleStatus'])->name('brands.toggle-status');
    
    // Order Management
    Route::resource('orders', 'App\Http\Controllers\Admin\OrderController');
    Route::get('orders/{order}/invoice', [App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('orders/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/process', [App\Http\Controllers\Admin\OrderController::class, 'process'])->name('orders.process');
    
    // User Management
    Route::resource('users', 'App\Http\Controllers\Admin\UserController');
    Route::post('users/bulk-delete', [App\Http\Controllers\Admin\UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::post('users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('users/{user}/verify-email', [App\Http\Controllers\Admin\UserController::class, 'verifyEmail'])->name('users.verify-email');
    Route::post('users/{user}/notes', [App\Http\Controllers\Admin\UserController::class, 'saveNotes'])->name('users.notes');
    Route::get('users/import', [App\Http\Controllers\Admin\UserController::class, 'import'])->name('users.import');
    Route::post('users/import', [App\Http\Controllers\Admin\UserController::class, 'processImport'])->name('users.process-import');
    Route::post('users/import/confirm', [App\Http\Controllers\Admin\UserController::class, 'confirmImport'])->name('users.import-confirm');
    Route::get('users/export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
    Route::get('users/download-template', [App\Http\Controllers\Admin\UserController::class, 'downloadTemplate'])->name('users.download-template');
    Route::post('users/bulk-activate', [App\Http\Controllers\Admin\UserController::class, 'bulkActivate'])->name('users.bulk-activate');
    Route::post('users/bulk-deactivate', [App\Http\Controllers\Admin\UserController::class, 'bulkDeactivate'])->name('users.bulk-deactivate');
    Route::post('users/bulk-block', [App\Http\Controllers\Admin\UserController::class, 'bulkBlock'])->name('users.bulk-block');
    
    // Inventory Management
    Route::resource('inventory', 'App\Http\Controllers\Admin\InventoryController');
    Route::get('inventory/adjust/{product}', [App\Http\Controllers\Admin\InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::post('inventory/adjust/{product}', [App\Http\Controllers\Admin\InventoryController::class, 'storeAdjustment'])->name('inventory.store-adjustment');
    Route::get('inventory/history/{product}', [App\Http\Controllers\Admin\InventoryController::class, 'history'])->name('inventory.history');
    Route::post('inventory/{product}/quick-update', [App\Http\Controllers\Admin\InventoryController::class, 'quickUpdate'])->name('inventory.quick-update');
    Route::get('inventory-export', [App\Http\Controllers\Admin\InventoryController::class, 'export'])->name('inventory.export');
    Route::get('inventory-bulk-update', [App\Http\Controllers\Admin\InventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');
    Route::post('inventory-bulk-update', [App\Http\Controllers\Admin\InventoryController::class, 'processBulkUpdate'])->name('inventory.process-bulk-update');
    
    // Coupon Management
    Route::resource('coupons', 'App\Http\Controllers\Admin\CouponController');
    Route::post('coupons/bulk-delete', [App\Http\Controllers\Admin\CouponController::class, 'bulkDelete'])->name('coupons.bulk-delete');
    Route::post('coupons/{coupon}/toggle-status', [App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::post('coupons/{coupon}/duplicate', [App\Http\Controllers\Admin\CouponController::class, 'duplicate'])->name('coupons.duplicate');
    Route::post('coupons/{coupon}/send-test', [App\Http\Controllers\Admin\CouponController::class, 'sendTestEmail'])->name('coupons.send-test');
    
    // Review Management
    Route::resource('reviews', 'App\Http\Controllers\Admin\ReviewController');
    Route::post('reviews/bulk-approve', [App\Http\Controllers\Admin\ReviewController::class, 'bulkApprove'])->name('reviews.bulk-approve');
    Route::post('reviews/bulk-reject', [App\Http\Controllers\Admin\ReviewController::class, 'bulkReject'])->name('reviews.bulk-reject');
    Route::post('reviews/bulk-delete', [App\Http\Controllers\Admin\ReviewController::class, 'bulkDelete'])->name('reviews.bulk-delete');
    Route::post('reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
    
    // CMS Management
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('pages', [App\Http\Controllers\Admin\CMSController::class, 'pages'])->name('pages.index');
        Route::get('pages/create', [App\Http\Controllers\Admin\CMSController::class, 'createPage'])->name('pages.create');
        Route::post('pages', [App\Http\Controllers\Admin\CMSController::class, 'storePage'])->name('pages.store');
        Route::get('pages/{page}/edit', [App\Http\Controllers\Admin\CMSController::class, 'editPage'])->name('pages.edit');
        Route::put('pages/{page}', [App\Http\Controllers\Admin\CMSController::class, 'updatePage'])->name('pages.update');
        Route::delete('pages/{page}', [App\Http\Controllers\Admin\CMSController::class, 'deletePage'])->name('pages.delete');
        
        Route::get('about', [App\Http\Controllers\Admin\CMSController::class, 'about'])->name('about');
        Route::put('about', [App\Http\Controllers\Admin\CMSController::class, 'updateAbout'])->name('about.update');
        
        Route::get('contact', [App\Http\Controllers\Admin\CMSController::class, 'contact'])->name('contact');
        Route::put('contact', [App\Http\Controllers\Admin\CMSController::class, 'updateContact'])->name('contact.update');
        
        // Ad Banner Management
        Route::prefix('ad-banners')->name('ad-banners.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AdBannerController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\AdBannerController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\AdBannerController::class, 'store'])->name('store');
            Route::get('/{adBanner}/edit', [App\Http\Controllers\Admin\AdBannerController::class, 'edit'])->name('edit');
            Route::put('/{adBanner}', [App\Http\Controllers\Admin\AdBannerController::class, 'update'])->name('update');
            Route::delete('/{adBanner}', [App\Http\Controllers\Admin\AdBannerController::class, 'destroy'])->name('destroy');
            Route::post('/{adBanner}/toggle-status', [App\Http\Controllers\Admin\AdBannerController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{adBanner}/duplicate', [App\Http\Controllers\Admin\AdBannerController::class, 'duplicate'])->name('duplicate');
            Route::get('/stats', [App\Http\Controllers\Admin\AdBannerController::class, 'stats'])->name('stats');
        });

        // Big Sale Event Management
        Route::prefix('big-sale-events')->name('big-sale-events.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BigSaleEventController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\BigSaleEventController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\BigSaleEventController::class, 'store'])->name('store');
            Route::get('/{bigSaleEvent}/edit', [App\Http\Controllers\Admin\BigSaleEventController::class, 'edit'])->name('edit');
            Route::put('/{bigSaleEvent}', [App\Http\Controllers\Admin\BigSaleEventController::class, 'update'])->name('update');
            Route::delete('/{bigSaleEvent}', [App\Http\Controllers\Admin\BigSaleEventController::class, 'destroy'])->name('destroy');
            Route::post('/{bigSaleEvent}/toggle-status', [App\Http\Controllers\Admin\BigSaleEventController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{bigSaleEvent}/toggle-featured', [App\Http\Controllers\Admin\BigSaleEventController::class, 'toggleFeatured'])->name('toggle-featured');
            Route::post('/{bigSaleEvent}/duplicate', [App\Http\Controllers\Admin\BigSaleEventController::class, 'duplicate'])->name('duplicate');
        });

        // Product of the Day Management
        Route::prefix('product-of-day')->name('product-of-day.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ProductOfDayController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\ProductOfDayController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\ProductOfDayController::class, 'store'])->name('store');
            Route::get('/{productOfDay}/edit', [App\Http\Controllers\Admin\ProductOfDayController::class, 'edit'])->name('edit');
            Route::put('/{productOfDay}', [App\Http\Controllers\Admin\ProductOfDayController::class, 'update'])->name('update');
            Route::delete('/{productOfDay}', [App\Http\Controllers\Admin\ProductOfDayController::class, 'destroy'])->name('destroy');
            Route::post('/{productOfDay}/toggle-status', [App\Http\Controllers\Admin\ProductOfDayController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{productOfDay}/duplicate', [App\Http\Controllers\Admin\ProductOfDayController::class, 'duplicate'])->name('duplicate');
        });
        
        Route::get('banners', [App\Http\Controllers\Admin\CMSController::class, 'banners'])->name('banners.index');
        Route::get('banners/create', [App\Http\Controllers\Admin\CMSController::class, 'createBanner'])->name('banners.create');
        Route::post('banners', [App\Http\Controllers\Admin\CMSController::class, 'storeBanner'])->name('banners.store');
        Route::post('banners/{banner}/toggle-status', [App\Http\Controllers\Admin\CMSController::class, 'toggleBannerStatus'])->name('banners.toggle-status');
        Route::get('banners/{banner}/edit', [App\Http\Controllers\Admin\CMSController::class, 'editBanner'])->name('banners.edit');
        Route::put('banners/{banner}', [App\Http\Controllers\Admin\CMSController::class, 'updateBanner'])->name('banners.update');
        Route::delete('banners/{banner}', [App\Http\Controllers\Admin\CMSController::class, 'deleteBanner'])->name('banners.delete');
        
        Route::get('blog', [App\Http\Controllers\Admin\CMSController::class, 'blog'])->name('blog.index');
        Route::get('blog/create', [App\Http\Controllers\Admin\CMSController::class, 'createPost'])->name('blog.create');
        Route::post('blog', [App\Http\Controllers\Admin\CMSController::class, 'storePost'])->name('blog.store');
        Route::get('blog/{post}/edit', [App\Http\Controllers\Admin\CMSController::class, 'editPost'])->name('blog.edit');
        Route::put('blog/{post}', [App\Http\Controllers\Admin\CMSController::class, 'updatePost'])->name('blog.update');
        Route::delete('blog/{post}', [App\Http\Controllers\Admin\CMSController::class, 'deletePost'])->name('blog.delete');
        Route::post('blog/{post}/toggle-status', [App\Http\Controllers\Admin\CMSController::class, 'togglePostStatus'])->name('blog.toggle-status');
    });
    
    // Deals Management
    Route::prefix('deals')->name('deals.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DealController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\DealController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\DealController::class, 'store'])->name('store');
        Route::get('/{deal}/edit', [App\Http\Controllers\Admin\DealController::class, 'edit'])->name('edit');
        Route::put('/{deal}', [App\Http\Controllers\Admin\DealController::class, 'update'])->name('update');
        Route::delete('/{deal}', [App\Http\Controllers\Admin\DealController::class, 'delete'])->name('delete');
        Route::post('/{deal}/toggle-status', [App\Http\Controllers\Admin\DealController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // Fashion Management
    Route::prefix('fashion')->name('fashion.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\FashionController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\FashionController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\FashionController::class, 'store'])->name('store');
        Route::get('/{fashion}/edit', [App\Http\Controllers\Admin\FashionController::class, 'edit'])->name('edit');
        Route::put('/{fashion}', [App\Http\Controllers\Admin\FashionController::class, 'update'])->name('update');
        Route::delete('/{fashion}', [App\Http\Controllers\Admin\FashionController::class, 'delete'])->name('delete');
        Route::post('/{fashion}/toggle-status', [App\Http\Controllers\Admin\FashionController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // Marketing
    Route::prefix('marketing')->name('marketing.')->group(function () {
        Route::get('coupons', [App\Http\Controllers\Admin\CouponController::class, 'index'])->name('coupons.index');
        Route::get('coupons/create', [App\Http\Controllers\Admin\CouponController::class, 'create'])->name('coupons.create');
        Route::post('coupons', [App\Http\Controllers\Admin\CouponController::class, 'store'])->name('coupons.store');
        Route::get('coupons/{coupon}/edit', [App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('coupons.edit');
        Route::put('coupons/{coupon}', [App\Http\Controllers\Admin\CouponController::class, 'update'])->name('coupons.update');
        Route::delete('coupons/{coupon}', [App\Http\Controllers\Admin\CouponController::class, 'delete'])->name('coupons.delete');
        Route::post('coupons/{coupon}/toggle-status', [App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
        
        Route::get('campaigns', [App\Http\Controllers\Admin\CampaignController::class, 'index'])->name('campaigns.index');
        Route::get('campaigns/create', [App\Http\Controllers\Admin\CampaignController::class, 'create'])->name('campaigns.create');
        Route::post('campaigns', [App\Http\Controllers\Admin\CampaignController::class, 'store'])->name('campaigns.store');
        Route::get('campaigns/{campaign}/edit', [App\Http\Controllers\Admin\CampaignController::class, 'edit'])->name('campaigns.edit');
        Route::put('campaigns/{campaign}', [App\Http\Controllers\Admin\CampaignController::class, 'update'])->name('campaigns.update');
        Route::delete('campaigns/{campaign}', [App\Http\Controllers\Admin\CampaignController::class, 'delete'])->name('campaigns.delete');
        Route::post('campaigns/{campaign}/toggle-status', [App\Http\Controllers\Admin\CampaignController::class, 'toggleStatus'])->name('campaigns.toggle-status');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('general', [App\Http\Controllers\Admin\SettingsController::class, 'general'])->name('general');
        Route::put('general', [App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('general.update');
        
        Route::get('payment', [App\Http\Controllers\Admin\SettingsController::class, 'payment'])->name('payment');
        Route::put('payment', [App\Http\Controllers\Admin\SettingsController::class, 'updatePayment'])->name('payment.update');
        Route::post('payment', [App\Http\Controllers\Admin\SettingsController::class, 'updatePayment'])->name('payment.update.post');
        
        Route::get('email', [App\Http\Controllers\Admin\SettingsController::class, 'email'])->name('email');
        Route::put('email', [App\Http\Controllers\Admin\SettingsController::class, 'updateEmail'])->name('email.update');
        Route::post('email/test', [App\Http\Controllers\Admin\SettingsController::class, 'testEmail'])->name('email.test');
        
        Route::get('shipping', [App\Http\Controllers\Admin\SettingsController::class, 'shipping'])->name('shipping');
        Route::put('shipping', [App\Http\Controllers\Admin\SettingsController::class, 'updateShipping'])->name('shipping.update');
        
        Route::get('ai', [App\Http\Controllers\Admin\SettingsController::class, 'ai'])->name('ai');
        Route::put('ai', [App\Http\Controllers\Admin\SettingsController::class, 'updateAi'])->name('ai.update');
        
        Route::get('sms', [App\Http\Controllers\Admin\SettingsController::class, 'sms'])->name('sms');
        Route::put('sms', [App\Http\Controllers\Admin\SettingsController::class, 'updateSms'])->name('sms.update');
    });
});

// Accountant routes
Route::middleware(['auth', 'role:super_admin,accountant'])->prefix('accountant')->name('accountant.')->group(function () {
    Route::get('/dashboard', 'App\Http\Controllers\Accountant\DashboardController@index')->name('dashboard.index');
    Route::get('/financial-report', 'App\Http\Controllers\Accountant\DashboardController@financialReport')->name('dashboard.financial-report');
    Route::get('/expense-report', 'App\Http\Controllers\Accountant\DashboardController@expenseReport')->name('dashboard.expense-report');
    Route::get('/expense/create', 'App\Http\Controllers\Accountant\DashboardController@createExpense')->name('dashboard.create-expense');
    Route::post('/expense/store', 'App\Http\Controllers\Accountant\DashboardController@storeExpense')->name('dashboard.store-expense');
    Route::get('/tax-report', 'App\Http\Controllers\Accountant\DashboardController@taxReport')->name('dashboard.tax-report');
});

require __DIR__.'/auth.php';
