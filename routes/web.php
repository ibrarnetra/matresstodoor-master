<?php

use Illuminate\Support\Facades\Route;
use App\Models\Admin\Order;
use App\Mail\OrderPlaced;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\SubscriberController;
use App\Http\Controllers\Frontend\StoreController as FrontendStoreController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\OrderController as FrontendOrderController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\FaqController as FrontendFaqController;
use App\Http\Controllers\Frontend\DashboardController as FrontendDashboardController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\CmsController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;
use App\Http\Controllers\Frontend\AddressController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\Admin\WeightClassController;
use App\Http\Controllers\Admin\WebNotificationController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TaxRateController;
use App\Http\Controllers\Admin\TaxClassController;
use App\Http\Controllers\Admin\SubscriberController as AdminSubscriberController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\StockStatusController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\RouteLocationController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\OrderStatusController;
use App\Http\Controllers\Admin\OrderManagementCommentController;
use App\Http\Controllers\Admin\OrderHistoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\LoadingSheetController;
use App\Http\Controllers\Admin\LengthClassController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\GeozoneController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerGroupController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuthorizeNetController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AttributeGroupController;
use App\Http\Controllers\Admin\AttributeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

### DASHBOARD ###
Route::get('/admin', function () {
    return redirect()->route('dashboard.index');
});

### AUTH ####
Route::prefix('admin')->group(function () {
    Route::get('sign-in', [AuthController::class, 'index'])->name('admin.signIn');
    Route::post('sign-in/authenticate', [AuthController::class, 'checkSignIn'])->name('admin.auth');
    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
});

### GET ZONES ###
Route::get('zones/get-zones', [ZoneController::class, 'getZones'])->name('zones.getZones');

### ADMIN ROUTES ###
Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
    ### dashboard ###
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('dashboard/get-data', [DashboardController::class, 'getData'])->name('dashboard.getData');

    ### admin my profile ###
    Route::get('my-profile', [AuthController::class, 'myProfile'])->name('admin.myProfile');
    Route::post('handle-profile-update', [AuthController::class, 'handleProfileUpdate'])->name('admin.handleProfileUpdate');

    ### admin change password ###
    Route::get('change-password', [AuthController::class, 'changePassword'])->name('admin.changePassword');
    Route::post('handle-change-password', [AuthController::class, 'handleChangePassword'])->name('admin.handleChangePassword');

    ### users ###
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::post('users/update-status/{id}', [UserController::class, 'updateStatus'])->name('users.update-status');
    Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');
    Route::get('users/show/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/get-data', [UserController::class, 'dataTable'])->name('users.dataTable');
    Route::post('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulkDelete');
    Route::get('users/manage-team/{id}', [UserController::class, 'manageTeam'])->name('users.manageTeam');
    Route::post('users/assign-unassign-team-lead', [UserController::class, 'assignUnassignTeamLead'])->name('users.assignUnassignTeamLead');

    ### roles ###
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::post('roles/update-status/{id}', [RoleController::class, 'updateStatus'])->name('roles.update-status');
    Route::get('roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('roles/update/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::get('roles/delete/{id}', [RoleController::class, 'destroy'])->name('roles.delete');
    Route::get('roles/show/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('roles/get-data', [RoleController::class, 'dataTable'])->name('roles.dataTable');
    Route::post('roles/bulk-delete', [RoleController::class, 'bulkDelete'])->name('roles.bulkDelete');

    ### categories ###
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('categories/update-status/{id}', [CategoryController::class, 'updateStatus'])->name('categories.update-status');
    Route::get('categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');
    Route::get('categories/search', [CategoryController::class, 'search'])->name('categories.search');
    Route::get('categories/get-data', [CategoryController::class, 'dataTable'])->name('categories.dataTable');
    Route::post('categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
    Route::get('categories/edit-slug/{id}', [CategoryController::class, 'editSlug'])->name('categories.editSlug');
    Route::post('categories/update-slug/{id}', [CategoryController::class, 'updateSlug'])->name('categories.updateSlug');

    ### manufacturers ###
    Route::get('manufacturers', [ManufacturerController::class, 'index'])->name('manufacturers.index');
    Route::get('manufacturers/create', [ManufacturerController::class, 'create'])->name('manufacturers.create');
    Route::post('manufacturers/store', [ManufacturerController::class, 'store'])->name('manufacturers.store');
    Route::post('manufacturers/update-status/{id}', [ManufacturerController::class, 'updateStatus'])->name('manufacturers.update-status');
    Route::get('manufacturers/edit/{id}', [ManufacturerController::class, 'edit'])->name('manufacturers.edit');
    Route::post('manufacturers/update/{id}', [ManufacturerController::class, 'update'])->name('manufacturers.update');
    Route::get('manufacturers/delete/{id}', [ManufacturerController::class, 'destroy'])->name('manufacturers.delete');
    Route::get('manufacturers/search', [ManufacturerController::class, 'search'])->name('manufacturers.search');
    Route::get('manufacturers/get-data', [ManufacturerController::class, 'dataTable'])->name('manufacturers.dataTable');
    Route::post('manufacturers/bulk-delete', [ManufacturerController::class, 'bulkDelete'])->name('manufacturers.bulkDelete');
    Route::get('manufacturers/edit-slug/{id}', [ManufacturerController::class, 'editSlug'])->name('manufacturers.editSlug');
    Route::post('manufacturers/update-slug/{id}', [ManufacturerController::class, 'updateSlug'])->name('manufacturers.updateSlug');

    ### attributes ###
    Route::get('attributes', [AttributeController::class, 'index'])->name('attributes.index');
    Route::get('attributes/create', [AttributeController::class, 'create'])->name('attributes.create');
    Route::post('attributes/store', [AttributeController::class, 'store'])->name('attributes.store');
    Route::post('attributes/update-status/{id}', [AttributeController::class, 'updateStatus'])->name('attributes.update-status');
    Route::get('attributes/edit/{id}', [AttributeController::class, 'edit'])->name('attributes.edit');
    Route::post('attributes/update/{id}', [AttributeController::class, 'update'])->name('attributes.update');
    Route::get('attributes/delete/{id}', [AttributeController::class, 'destroy'])->name('attributes.delete');
    Route::get('attributes/get-data', [AttributeController::class, 'dataTable'])->name('attributes.dataTable');
    Route::post('attributes/bulk-delete', [AttributeController::class, 'bulkDelete'])->name('attributes.bulkDelete');

    ### attribute-groups ###
    Route::get('attribute-groups', [AttributeGroupController::class, 'index'])->name('attribute-groups.index');
    Route::get('attribute-groups/create', [AttributeGroupController::class, 'create'])->name('attribute-groups.create');
    Route::post('attribute-groups/store', [AttributeGroupController::class, 'store'])->name('attribute-groups.store');
    Route::post('attribute-groups/update-status/{id}', [AttributeGroupController::class, 'updateStatus'])->name('attribute-groups.update-status');
    Route::get('attribute-groups/edit/{id}', [AttributeGroupController::class, 'edit'])->name('attribute-groups.edit');
    Route::post('attribute-groups/update/{id}', [AttributeGroupController::class, 'update'])->name('attribute-groups.update');
    Route::get('attribute-groups/delete/{id}', [AttributeGroupController::class, 'destroy'])->name('attribute-groups.delete');
    Route::get('attribute-groups/get-data', [AttributeGroupController::class, 'dataTable'])->name('attribute-groups.dataTable');
    Route::post('attribute-groups/bulk-delete', [AttributeGroupController::class, 'bulkDelete'])->name('attribute-groups.bulkDelete');

    ### options ###
    Route::get('options', [OptionController::class, 'index'])->name('options.index');
    Route::get('options/create', [OptionController::class, 'create'])->name('options.create');
    Route::post('options/store', [OptionController::class, 'store'])->name('options.store');
    Route::post('options/update-status/{id}', [OptionController::class, 'updateStatus'])->name('options.update-status');
    Route::get('options/edit/{id}', [OptionController::class, 'edit'])->name('options.edit');
    Route::post('options/update/{id}', [OptionController::class, 'update'])->name('options.update');
    Route::get('options/delete/{id}', [OptionController::class, 'destroy'])->name('options.delete');
    Route::get('options/get-data', [OptionController::class, 'dataTable'])->name('options.dataTable');
    Route::post('options/bulk-delete', [OptionController::class, 'bulkDelete'])->name('options.bulkDelete');
    Route::get('options/delete-option-value/{id}', [OptionController::class, 'deleteOptionValue'])->name('options.deleteOptionValue');

    ### languages ###
    Route::get('languages', [LanguageController::class, 'index'])->name('languages.index');
    Route::get('languages/create', [LanguageController::class, 'create'])->name('languages.create');
    Route::post('languages/store', [LanguageController::class, 'store'])->name('languages.store');
    Route::post('languages/update-status/{id}', [LanguageController::class, 'updateStatus'])->name('languages.update-status');
    Route::get('languages/edit/{id}', [LanguageController::class, 'edit'])->name('languages.edit');
    Route::post('languages/update/{id}', [LanguageController::class, 'update'])->name('languages.update');
    Route::get('languages/delete/{id}', [LanguageController::class, 'destroy'])->name('languages.delete');
    Route::get('languages/get-data', [LanguageController::class, 'dataTable'])->name('languages.dataTable');
    Route::post('languages/bulk-delete', [LanguageController::class, 'bulkDelete'])->name('languages.bulkDelete');

    ### currencies ###
    Route::get('currencies', [CurrencyController::class, 'index'])->name('currencies.index');
    Route::get('currencies/create', [CurrencyController::class, 'create'])->name('currencies.create');
    Route::post('currencies/store', [CurrencyController::class, 'store'])->name('currencies.store');
    Route::post('currencies/update-status/{id}', [CurrencyController::class, 'updateStatus'])->name('currencies.update-status');
    Route::get('currencies/edit/{id}', [CurrencyController::class, 'edit'])->name('currencies.edit');
    Route::post('currencies/update/{id}', [CurrencyController::class, 'update'])->name('currencies.update');
    Route::get('currencies/delete/{id}', [CurrencyController::class, 'destroy'])->name('currencies.delete');
    Route::get('currencies/get-data', [CurrencyController::class, 'dataTable'])->name('currencies.dataTable');
    Route::post('currencies/bulk-delete', [CurrencyController::class, 'bulkDelete'])->name('currencies.bulkDelete');

    ### stock-statuses ###
    Route::get('stock-statuses', [StockStatusController::class, 'index'])->name('stock-statuses.index');
    Route::get('stock-statuses/create', [StockStatusController::class, 'create'])->name('stock-statuses.create');
    Route::post('stock-statuses/store', [StockStatusController::class, 'store'])->name('stock-statuses.store');
    Route::post('stock-statuses/update-status/{id}', [StockStatusController::class, 'updateStatus'])->name('stock-statuses.update-status');
    Route::get('stock-statuses/edit/{id}', [StockStatusController::class, 'edit'])->name('stock-statuses.edit');
    Route::post('stock-statuses/update/{id}', [StockStatusController::class, 'update'])->name('stock-statuses.update');
    Route::get('stock-statuses/delete/{id}', [StockStatusController::class, 'destroy'])->name('stock-statuses.delete');
    Route::get('stock-statuses/get-data', [StockStatusController::class, 'dataTable'])->name('stock-statuses.dataTable');
    Route::post('stock-statuses/bulk-delete', [StockStatusController::class, 'bulkDelete'])->name('stock-statuses.bulkDelete');

    ### order-statuses ###
    Route::get('order-statuses', [OrderStatusController::class, 'index'])->name('order-statuses.index');
    Route::get('order-statuses/create', [OrderStatusController::class, 'create'])->name('order-statuses.create');
    Route::post('order-statuses/store', [OrderStatusController::class, 'store'])->name('order-statuses.store');
    Route::post('order-statuses/update-status/{id}', [OrderStatusController::class, 'updateStatus'])->name('order-statuses.update-status');
    Route::get('order-statuses/edit/{id}', [OrderStatusController::class, 'edit'])->name('order-statuses.edit');
    Route::post('order-statuses/update/{id}', [OrderStatusController::class, 'update'])->name('order-statuses.update');
    Route::get('order-statuses/delete/{id}', [OrderStatusController::class, 'destroy'])->name('order-statuses.delete');
    Route::get('order-statuses/get-data', [OrderStatusController::class, 'dataTable'])->name('order-statuses.dataTable');
    Route::post('order-statuses/bulk-delete', [OrderStatusController::class, 'bulkDelete'])->name('order-statuses.bulkDelete');
    

    ### tax-classes ###
    Route::get('tax-classes', [TaxClassController::class, 'index'])->name('tax-classes.index');
    Route::get('tax-classes/create', [TaxClassController::class, 'create'])->name('tax-classes.create');
    Route::post('tax-classes/store', [TaxClassController::class, 'store'])->name('tax-classes.store');
    Route::post('tax-classes/update-status/{id}', [TaxClassController::class, 'updateStatus'])->name('tax-classes.update-status');
    Route::get('tax-classes/edit/{id}', [TaxClassController::class, 'edit'])->name('tax-classes.edit');
    Route::post('tax-classes/update/{id}', [TaxClassController::class, 'update'])->name('tax-classes.update');
    Route::get('tax-classes/delete/{id}', [TaxClassController::class, 'destroy'])->name('tax-classes.delete');
    Route::get('tax-classes/get-data', [TaxClassController::class, 'dataTable'])->name('tax-classes.dataTable');
    Route::post('tax-classes/bulk-delete', [TaxClassController::class, 'bulkDelete'])->name('tax-classes.bulkDelete');

    ### tax-rates ###
    Route::get('tax-rates', [TaxRateController::class, 'index'])->name('tax-rates.index');
    Route::get('tax-rates/create', [TaxRateController::class, 'create'])->name('tax-rates.create');
    Route::post('tax-rates/store', [TaxRateController::class, 'store'])->name('tax-rates.store');
    Route::post('tax-rates/update-status/{id}', [TaxRateController::class, 'updateStatus'])->name('tax-rates.update-status');
    Route::get('tax-rates/edit/{id}', [TaxRateController::class, 'edit'])->name('tax-rates.edit');
    Route::post('tax-rates/update/{id}', [TaxRateController::class, 'update'])->name('tax-rates.update');
    Route::get('tax-rates/delete/{id}', [TaxRateController::class, 'destroy'])->name('tax-rates.delete');
    Route::get('tax-rates/get-data', [TaxRateController::class, 'dataTable'])->name('tax-rates.dataTable');
    Route::post('tax-rates/bulk-delete', [TaxRateController::class, 'bulkDelete'])->name('tax-rates.bulkDelete');

    ### weight-classes ###
    Route::get('weight-classes', [WeightClassController::class, 'index'])->name('weight-classes.index');
    Route::get('weight-classes/create', [WeightClassController::class, 'create'])->name('weight-classes.create');
    Route::post('weight-classes/store', [WeightClassController::class, 'store'])->name('weight-classes.store');
    Route::post('weight-classes/update-status/{id}', [WeightClassController::class, 'updateStatus'])->name('weight-classes.update-status');
    Route::get('weight-classes/edit/{id}', [WeightClassController::class, 'edit'])->name('weight-classes.edit');
    Route::post('weight-classes/update/{id}', [WeightClassController::class, 'update'])->name('weight-classes.update');
    Route::get('weight-classes/delete/{id}', [WeightClassController::class, 'destroy'])->name('weight-classes.delete');
    Route::get('weight-classes/get-data', [WeightClassController::class, 'dataTable'])->name('weight-classes.dataTable');
    Route::post('weight-classes/bulk-delete', [WeightClassController::class, 'bulkDelete'])->name('weight-classes.bulkDelete');

    ### length-classes ###
    Route::get('length-classes', [LengthClassController::class, 'index'])->name('length-classes.index');
    Route::get('length-classes/create', [LengthClassController::class, 'create'])->name('length-classes.create');
    Route::post('length-classes/store', [LengthClassController::class, 'store'])->name('length-classes.store');
    Route::post('length-classes/update-status/{id}', [LengthClassController::class, 'updateStatus'])->name('length-classes.update-status');
    Route::get('length-classes/edit/{id}', [LengthClassController::class, 'edit'])->name('length-classes.edit');
    Route::post('length-classes/update/{id}', [LengthClassController::class, 'update'])->name('length-classes.update');
    Route::get('length-classes/delete/{id}', [LengthClassController::class, 'destroy'])->name('length-classes.delete');
    Route::get('length-classes/get-data', [LengthClassController::class, 'dataTable'])->name('length-classes.dataTable');
    Route::post('length-classes/bulk-delete', [LengthClassController::class, 'bulkDelete'])->name('length-classes.bulkDelete');

    ### countries ###
    Route::get('countries', [CountryController::class, 'index'])->name('countries.index');
    Route::get('countries/create', [CountryController::class, 'create'])->name('countries.create');
    Route::post('countries/store', [CountryController::class, 'store'])->name('countries.store');
    Route::post('countries/update-status/{id}', [CountryController::class, 'updateStatus'])->name('countries.update-status');
    Route::get('countries/edit/{id}', [CountryController::class, 'edit'])->name('countries.edit');
    Route::post('countries/update/{id}', [CountryController::class, 'update'])->name('countries.update');
    Route::get('countries/delete/{id}', [CountryController::class, 'destroy'])->name('countries.delete');
    Route::get('countries/get-data', [CountryController::class, 'dataTable'])->name('countries.dataTable');
    Route::get('countries/search', [CountryController::class, 'search'])->name('countries.search');
    Route::post('countries/bulk-delete', [CountryController::class, 'bulkDelete'])->name('countries.bulkDelete');

      ### warehouses ###
      Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
      Route::get('warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
      Route::post('warehouses/store', [WarehouseController::class, 'store'])->name('warehouses.store');
      Route::post('warehouses/update-status/{id}', [WarehouseController::class, 'updateStatus'])->name('warehouses.update-status');
      Route::get('warehouses/edit/{id}', [WarehouseController::class, 'edit'])->name('warehouses.edit');
      Route::post('warehouses/update/{id}', [WarehouseController::class, 'update'])->name('warehouses.update');
      Route::get('warehouses/delete/{id}', [WarehouseController::class, 'destroy'])->name('warehouses.delete');
      Route::get('warehouses/search', [WarehouseController::class, 'search'])->name('warehouses.search');
      Route::get('warehouses/get-data', [WarehouseController::class, 'dataTable'])->name('warehouses.dataTable');
      Route::post('warehouses/bulk-delete', [WarehouseController::class, 'bulkDelete'])->name('warehouses.bulkDelete');
      

    ### zones ###
    Route::get('zones', [ZoneController::class, 'index'])->name('zones.index');
    Route::get('zones/create', [ZoneController::class, 'create'])->name('zones.create');
    Route::post('zones/store', [ZoneController::class, 'store'])->name('zones.store');
    Route::post('zones/update-status/{id}', [ZoneController::class, 'updateStatus'])->name('zones.update-status');
    Route::get('zones/edit/{id}', [ZoneController::class, 'edit'])->name('zones.edit');
    Route::post('zones/update/{id}', [ZoneController::class, 'update'])->name('zones.update');
    Route::get('zones/delete/{id}', [ZoneController::class, 'destroy'])->name('zones.delete');
    Route::get('zones/get-data', [ZoneController::class, 'dataTable'])->name('zones.dataTable');
    Route::get('zones/search', [ZoneController::class, 'search'])->name('zones.search');
    Route::post('zones/bulk-delete', [ZoneController::class, 'bulkDelete'])->name('zones.bulkDelete');

    ### geozones ###
    Route::get('geo-zones', [GeozoneController::class, 'index'])->name('geozones.index');
    Route::get('geo-zones/create', [GeozoneController::class, 'create'])->name('geozones.create');
    Route::post('geo-zones/store', [GeozoneController::class, 'store'])->name('geozones.store');
    Route::post('geo-zones/update-status/{id}', [GeozoneController::class, 'updateStatus'])->name('geozones.update-status');
    Route::get('geo-zones/edit/{id}', [GeozoneController::class, 'edit'])->name('geozones.edit');
    Route::post('geo-zones/update/{id}', [GeozoneController::class, 'update'])->name('geozones.update');
    Route::get('geo-zones/delete/{id}', [GeozoneController::class, 'destroy'])->name('geozones.delete');
    Route::get('geo-zones/get-data', [GeozoneController::class, 'dataTable'])->name('geozones.dataTable');
    Route::post('geo-zones/bulk-delete', [GeozoneController::class, 'bulkDelete'])->name('geozones.bulkDelete');

    ### products ###
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [ProductController::class, 'store'])->name('products.store');
    Route::post('products/update-status/{id}', [ProductController::class, 'updateStatus'])->name('products.update-status');
    Route::get('products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('products/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::get('products/delete/{id}', [ProductController::class, 'destroy'])->name('products.delete');
    Route::get('products/get-data', [ProductController::class, 'dataTable'])->name('products.dataTable');
    Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulkDelete');
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('products/load-option-values', [ProductController::class, 'loadOptionValue'])->name('products.loadOptionValue');
    Route::get('products/delete-product-option', [ProductController::class, 'deleteOptionValue'])->name('products.deleteOptionValue');
    Route::get('products/get-options', [ProductController::class, 'getOptions'])->name('products.getOptions');
    Route::get('products/delete-image', [ProductController::class, 'deleteImage'])->name('products.deleteImage');
    Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulkDelete');
    Route::post('products/create-product-for-admin-panel', [ProductController::class, 'createProductForAdminPanel'])->name('products.createProductForAdminPanel');
    Route::get('products/edit-slug/{id}', [ProductController::class, 'editSlug'])->name('products.editSlug');
    Route::post('products/update-slug/{id}', [ProductController::class, 'updateSlug'])->name('products.updateSlug');
    Route::get('products/purchase-history/{id}', [PurchaseController::class, 'productPurchaseHistory'])->name('products.purchaseHistory');
    Route::get('products/purchase-history-table', [PurchaseController::class, 'productPurchaseDataTable'])->name('products.purchaseDataTable');

    ### customer-groups ###
    Route::get('customer-groups', [CustomerGroupController::class, 'index'])->name('customer-groups.index');
    Route::get('customer-groups/create', [CustomerGroupController::class, 'create'])->name('customer-groups.create');
    Route::post('customer-groups/store', [CustomerGroupController::class, 'store'])->name('customer-groups.store');
    Route::post('customer-groups/update-status/{id}', [CustomerGroupController::class, 'updateStatus'])->name('customer-groups.update-status');
    Route::get('customer-groups/edit/{id}', [CustomerGroupController::class, 'edit'])->name('customer-groups.edit');
    Route::post('customer-groups/update/{id}', [CustomerGroupController::class, 'update'])->name('customer-groups.update');
    Route::get('customer-groups/delete/{id}', [CustomerGroupController::class, 'destroy'])->name('customer-groups.delete');
    Route::get('customer-groups/get-data', [CustomerGroupController::class, 'dataTable'])->name('customer-groups.dataTable');
    Route::post('customer-groups/bulk-delete', [CustomerGroupController::class, 'bulkDelete'])->name('customer-groups.bulkDelete');

    ### customers ###
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::post('customers/update-status/{id}', [CustomerController::class, 'updateStatus'])->name('customers.update-status');
    Route::get('customers/edit/{id}', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::post('customers/update/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::get('customers/delete/{id}', [CustomerController::class, 'destroy'])->name('customers.delete');
    Route::get('customers/get-data', [CustomerController::class, 'dataTable'])->name('customers.dataTable');
    Route::post('customers/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customers.bulkDelete');
    Route::get('customers/load-addresses', [CustomerController::class, 'loadAddresses'])->name('customers.loadAddresses');
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::post('customers/ajax-submit', [CustomerController::class, 'ajaxSubmit'])->name('customers.ajaxSubmit');

    ### orders ###
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders/store', [OrderController::class, 'store'])->name('orders.store');
    Route::post('orders/update-status/{id}', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/edit/{id}', [OrderController::class, 'edit'])->name('orders.edit');
    Route::post('orders/update/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::get('orders/delete/{id}', [OrderController::class, 'destroy'])->name('orders.delete');
    Route::get('orders/get-data', [OrderController::class, 'dataTable'])->name('orders.dataTable');
    Route::post('orders/bulk-delete', [OrderController::class, 'bulkDelete'])->name('orders.bulkDelete');
    Route::post('orders/add-to-cart', [OrderController::class, 'addToCart'])->name('orders.addToCart');
    Route::post('orders/validate-purchase-qty', [OrderController::class, 'validatePurchaseQty'])->name('orders.validatePurchaseQty');
    Route::get('orders/remove-cart-item', [OrderController::class, 'removeCartItem'])->name('orders.removeCartItem');
    Route::get('orders/cart-total', [OrderController::class, 'cartTotal'])->name('orders.cartTotal');
    Route::get('orders/generate-invoice', [OrderController::class, 'generateInvoice'])->name('orders.generateInvoice');
    Route::get('orders/detail/{id}', [OrderController::class, 'detail'])->name('orders.detail');
    Route::get('orders/generate-cart-for-edit', [OrderController::class, 'generateCartForEdit'])->name('orders.generateCartForEdit');
    Route::get('orders/export-excel', [OrderController::class, 'exportExcel'])->name('orders.exportExcel');
    Route::post('orders/clear-cart', [OrderController::class, 'clearCart'])->name('orders.clearCart');
    Route::post('orders/assign-unassign-order', [OrderController::class, 'assignUnassignOrder'])->name('orders.assignUnassignOrder');
    Route::get('orders/export-loading-sheet', [OrderController::class, 'exportLoadingSheet'])->name('orders.exportLoadingSheet');
    Route::post('orders/is-cart-valid', [OrderController::class, 'isCartValid'])->name('orders.isCartValid');
    Route::post('orders/get-uncal-order-total', [OrderController::class, 'getUncalOrderTotal'])->name('orders.getUncalOrderTotal');
    Route::get('orders/search', [OrderController::class, 'search'])->name('orders.search');
    Route::post('orders/get-lat-lng/{id}', [OrderController::class, 'getLatLng'])->name('orders.getLatLng');
    Route::get('orders/order-search', [OrderController::class, 'orderSearch'])->name('orders.orderSearch');
    Route::get('orders/order-summary/{id}', [OrderController::class, 'orderSummary'])->name('orders.orderSummary');

    ### purchase ###
    Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('purchases/store', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::post('purchases/update-status/{id}', [PurchaseController::class, 'updateStatus'])->name('purchases.update-status');
    Route::get('purchases/edit/{id}', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::post('purchases/update/{id}', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::get('purchases/delete/{id}', [PurchaseController::class, 'destroy'])->name('purchases.delete');
    Route::get('purchases/get-data', [PurchaseController::class, 'dataTable'])->name('purchases.dataTable');
    Route::post('purchases/bulk-delete', [PurchaseController::class, 'bulkDelete'])->name('purchases.bulkDelete');
    Route::post('purchases/add-to-cart', [PurchaseController::class, 'addToCart'])->name('purchases.addToCart');
    Route::post('purchases/validate-purchase-qty', [PurchaseController::class, 'validatePurchaseQty'])->name('purchases.validatePurchaseQty');
    Route::get('purchases/remove-cart-item', [PurchaseController::class, 'removeCartItem'])->name('purchases.removeCartItem');
    Route::get('purchases/detail/{id}', [PurchaseController::class, 'detail'])->name('purchases.detail');
    Route::get('purchases/generate-invoice', [PurchaseController::class, 'generateInvoice'])->name('purchases.generateInvoice');
    Route::get('purchases/generate-cart-for-edit', [PurchaseController::class, 'generateCartForEdit'])->name('purchases.generateCartForEdit');

    ### coupons ###
    Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::get('coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('coupons/store', [CouponController::class, 'store'])->name('coupons.store');
    Route::post('coupons/update-status/{id}', [CouponController::class, 'updateStatus'])->name('coupons.update-status');
    Route::get('coupons/edit/{id}', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::post('coupons/update/{id}', [CouponController::class, 'update'])->name('coupons.update');
    Route::get('coupons/delete/{id}', [CouponController::class, 'destroy'])->name('coupons.delete');
    Route::get('coupons/get-data', [CouponController::class, 'dataTable'])->name('coupons.dataTable');
    Route::post('coupons/bulk-delete', [CouponController::class, 'bulkDelete'])->name('coupons.bulkDelete');
    Route::get('coupons/search', [CouponController::class, 'search'])->name('coupons.search');

    ### vouchers ###
    Route::get('vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('vouchers/store', [VoucherController::class, 'store'])->name('vouchers.store');
    Route::post('vouchers/update-status/{id}', [VoucherController::class, 'updateStatus'])->name('vouchers.update-status');
    Route::get('vouchers/edit/{id}', [VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::post('vouchers/update/{id}', [VoucherController::class, 'update'])->name('vouchers.update');
    Route::get('vouchers/delete/{id}', [VoucherController::class, 'destroy'])->name('vouchers.delete');
    Route::get('vouchers/get-data', [VoucherController::class, 'dataTable'])->name('vouchers.dataTable');
    Route::post('vouchers/bulk-delete', [VoucherController::class, 'bulkDelete'])->name('vouchers.bulkDelete');
    Route::get('vouchers/search', [VoucherController::class, 'search'])->name('vouchers.search');

    ### settings ###
    Route::get('settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::post('settings/update', [SettingController::class, 'update'])->name('settings.update');

    ### store-locations ###
    Route::get('store-locations', [LocationController::class, 'index'])->name('store-locations.index');
    Route::get('store-locations/create', [LocationController::class, 'create'])->name('store-locations.create');
    Route::post('store-locations/store', [LocationController::class, 'store'])->name('store-locations.store');
    Route::post('store-locations/update-status/{id}', [LocationController::class, 'updateStatus'])->name('store-locations.update-status');
    Route::get('store-locations/edit/{id}', [LocationController::class, 'edit'])->name('store-locations.edit');
    Route::post('store-locations/update/{id}', [LocationController::class, 'update'])->name('store-locations.update');
    Route::get('store-locations/delete/{id}', [LocationController::class, 'destroy'])->name('store-locations.delete');
    Route::get('store-locations/get-data', [LocationController::class, 'dataTable'])->name('store-locations.dataTable');
    Route::post('store-locations/bulk-delete', [LocationController::class, 'bulkDelete'])->name('store-locations.bulkDelete');
    Route::get('store-locations/search', [LocationController::class, 'search'])->name('store-locations.search');

    ### pages ###
    Route::get('pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('pages/store', [PageController::class, 'store'])->name('pages.store');
    Route::post('pages/update-status/{id}', [PageController::class, 'updateStatus'])->name('pages.update-status');
    Route::get('pages/edit/{id}', [PageController::class, 'edit'])->name('pages.edit');
    Route::post('pages/update/{id}', [PageController::class, 'update'])->name('pages.update');
    Route::get('pages/delete/{id}', [PageController::class, 'destroy'])->name('pages.delete');
    Route::get('pages/get-data', [PageController::class, 'dataTable'])->name('pages.dataTable');
    Route::post('pages/bulk-delete', [PageController::class, 'bulkDelete'])->name('pages.bulkDelete');

    ### stores ###
    Route::get('stores', [StoreController::class, 'index'])->name('stores.index');
    Route::get('stores/create', [StoreController::class, 'create'])->name('stores.create');
    Route::post('stores/store', [StoreController::class, 'store'])->name('stores.store');
    Route::post('stores/update-status/{id}', [StoreController::class, 'updateStatus'])->name('stores.update-status');
    Route::get('stores/edit/{id}', [StoreController::class, 'edit'])->name('stores.edit');
    Route::post('stores/update/{id}', [StoreController::class, 'update'])->name('stores.update');
    Route::get('stores/delete/{id}', [StoreController::class, 'destroy'])->name('stores.delete');
    Route::get('stores/get-data', [StoreController::class, 'dataTable'])->name('stores.dataTable');
    Route::post('stores/bulk-delete', [StoreController::class, 'bulkDelete'])->name('stores.bulkDelete');
    Route::get('stores/search', [StoreController::class, 'search'])->name('stores.search');

    ### order-histories ###
    Route::post('order-histories/store', [OrderHistoryController::class, 'store'])->name('order-histories.store');

    ### order-management-comments ###
    Route::post('order-management-comments/store', [OrderManagementCommentController::class, 'store'])->name('order-management-comments.store');

    ### IMPORT URLS ###
    Route::get('countries/import-view', function () {
        return view('admin.countries.import');
    })->name('countries.import');
    Route::get('zones/import-view', function () {
        return view('admin.zones.import');
    })->name('zones.import');
    Route::post('countries/import-excel', [CountryController::class, 'importExcel'])->name('countries.importExcel');
    Route::post('zones/import-excel', [ZoneController::class, 'importExcel'])->name('zones.importExcel');

    ### routes ###
    Route::get('routes', [RouteController::class, 'index'])->name('routes.index');
    Route::get('routes/create', [RouteController::class, 'create'])->name('routes.create');
    Route::post('routes/store', [RouteController::class, 'store'])->name('routes.store');
    Route::post('routes/update-status/{id}', [RouteController::class, 'updateStatus'])->name('routes.update-status');
    Route::get('routes/edit/{id}', [RouteController::class, 'edit'])->name('routes.edit');
    Route::post('routes/update/{id}', [RouteController::class, 'update'])->name('routes.update');
    Route::get('routes/delete/{id}', [RouteController::class, 'destroy'])->name('routes.delete');
    Route::get('routes/get-data', [RouteController::class, 'dataTable'])->name('routes.dataTable');
    Route::post('routes/bulk-delete', [RouteController::class, 'bulkDelete'])->name('routes.bulkDelete');
    Route::get('routes/search', [RouteController::class, 'search'])->name('routes.search');
    Route::get('routes/detail/{id}', [RouteController::class, 'detail'])->name('routes.detail');
    Route::post('routes/assign-delivery-rep', [RouteController::class, 'assignDeliveryRep'])->name('routes.assignDeliveryRep');
    Route::post('routes/check-orders-routes', [RouteController::class, 'checkOrdersRoutes'])->name('routes.checkOrdersRoutes');
    Route::post('routes/get-optimized-routes/{id}', [RouteController::class, 'getOptimizedRoutes'])->name('routes.getOptimizedRoutes');
    Route::post('routes/optimize-routes/{id}', [RouteController::class, 'optimizeRoutes'])->name('routes.optimizeRoutes');
    Route::get('routes/update-order/{order_id}', [RouteController::class, 'updateOrder'])->name('routes.updateOrder');
    Route::get('routes/get-orders', [RouteController::class, 'getOrders'])->name('routes.getOrders');
    Route::get('routes/truck-loaded/{id}', [RouteController::class, 'truckLoaded'])->name('routes.truck-loaded');
    Route::get('routes/route-cash-summary/{route_id}', [RouteController::class, 'getRouteSummary'])->name('routes.getRouteSummary');
    Route::get('routes/route-order-cash-summary/{order_id}/{route_id}', [RouteController::class, 'routeOrderCashSummary'])->name('routes.routeOrderCashSummary');

    ### route-locations ###
    Route::post('route-locations/check-orders-routes', [RouteLocationController::class, 'checkValidityOfOrderList'])->name('route-locations.checkValidityOfOrderList');
    Route::get('route-locations/delete/{id}', [RouteLocationController::class, 'destroy'])->name('route-locations.delete');

    ### loading-sheets ###
    Route::get('loading-sheets', [LoadingSheetController::class, 'index'])->name('loading-sheets.index');
    Route::post('loading-sheets/store', [LoadingSheetController::class, 'store'])->name('loading-sheets.store');
    Route::get('loading-sheets/detail/{id}', [LoadingSheetController::class, 'detail'])->name('loading-sheets.detail');
    Route::get('route/loading-sheets/detail/{route_id}', [LoadingSheetController::class, 'routeDetail'])->name('route.loading-sheets.detail');
    Route::post('loading-sheets/add-item', [LoadingSheetController::class, 'addItem'])->name('loading-sheets.addItem');
    Route::get('loading-sheets/delete/{id}', [LoadingSheetController::class, 'destroy'])->name('loading-sheets.delete');
    Route::get('loading-sheets/delete-single-loading-sheet-item/{item_id}', [LoadingSheetController::class, 'deleteSingleLoadingSheetItem'])->name('loading-sheets.deleteSingleLoadingSheetItem');
    Route::get('route/loading-sheets-inventory/{route_id}', [LoadingSheetController::class, 'routeInventory'])->name('loading-sheets-route.inventory');
    Route::get('loading-sheets/manual-add-loading-sheet/{loading_sheet_id}', [LoadingSheetController::class, 'manualAddLoadingSheet'])->name('loading-sheets.manual-add-loading-sheet');

    ### faqs ###
    Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::get('faqs/create', [FaqController::class, 'create'])->name('faqs.create');
    Route::post('faqs/store', [FaqController::class, 'store'])->name('faqs.store');
    Route::post('faqs/update-status/{id}', [FaqController::class, 'updateStatus'])->name('faqs.update-status');
    Route::get('faqs/edit/{id}', [FaqController::class, 'edit'])->name('faqs.edit');
    Route::post('faqs/update/{id}', [FaqController::class, 'update'])->name('faqs.update');
    Route::get('faqs/delete/{id}', [FaqController::class, 'destroy'])->name('faqs.delete');
    Route::get('faqs/get-data', [FaqController::class, 'dataTable'])->name('faqs.dataTable');
    Route::post('faqs/bulk-delete', [FaqController::class, 'bulkDelete'])->name('faqs.bulkDelete');

    ### sliders ###
    Route::get('sliders', [SliderController::class, 'index'])->name('sliders.index');
    Route::get('sliders/create', [SliderController::class, 'create'])->name('sliders.create');
    Route::post('sliders/store', [SliderController::class, 'store'])->name('sliders.store');
    Route::post('sliders/update-status/{id}', [SliderController::class, 'updateStatus'])->name('sliders.update-status');
    Route::get('sliders/edit/{id}', [SliderController::class, 'edit'])->name('sliders.edit');
    Route::post('sliders/update/{id}', [SliderController::class, 'update'])->name('sliders.update');
    Route::get('sliders/delete/{id}', [SliderController::class, 'destroy'])->name('sliders.delete');
    Route::get('sliders/get-data', [SliderController::class, 'dataTable'])->name('sliders.dataTable');
    Route::post('sliders/bulk-delete', [SliderController::class, 'bulkDelete'])->name('sliders.bulkDelete');
    Route::get('sliders/delete-slide/{id}', [SliderController::class, 'deleteSlide'])->name('sliders.deleteSlide');

    ### subscribers ###
    Route::get('subscribers', [AdminSubscriberController::class, 'index'])->name('subscribers.index');
    Route::get('subscribers/get-data', [AdminSubscriberController::class, 'dataTable'])->name('subscribers.dataTable');
    Route::post('subscribers/update-status/{id}', [AdminSubscriberController::class, 'updateStatus'])->name('subscribers.update-status');
    Route::post('subscribers/bulk-delete', [AdminSubscriberController::class, 'bulkDelete'])->name('subscribers.bulkDelete');
    Route::get('subscribers/export-excel', [AdminSubscriberController::class, 'exportExcel'])->name('subscribers.exportExcel');

    ### web-notifications ###
    Route::get('web-notifications', [WebNotificationController::class, 'index'])->name('web-notifications.index');
    Route::get('web-notifications/create', [WebNotificationController::class, 'create'])->name('web-notifications.create');
    Route::post('web-notifications/store', [WebNotificationController::class, 'store'])->name('web-notifications.store');
    Route::post('web-notifications/update-status/{id}', [WebNotificationController::class, 'updateStatus'])->name('web-notifications.update-status');
    Route::get('web-notifications/edit/{id}', [WebNotificationController::class, 'edit'])->name('web-notifications.edit');
    Route::post('web-notifications/update/{id}', [WebNotificationController::class, 'update'])->name('web-notifications.update');
    Route::get('web-notifications/delete/{id}', [WebNotificationController::class, 'destroy'])->name('web-notifications.delete');
    Route::get('web-notifications/get-data', [WebNotificationController::class, 'dataTable'])->name('web-notifications.dataTable');
    Route::post('web-notifications/bulk-delete', [WebNotificationController::class, 'bulkDelete'])->name('web-notifications.bulkDelete');

    ### reviews ###
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/get-data', [AdminReviewController::class, 'dataTable'])->name('reviews.dataTable');
    Route::post('reviews/update-status/{id}', [AdminReviewController::class, 'updateStatus'])->name('reviews.update-status');
    Route::post('reviews/bulk-delete', [AdminReviewController::class, 'bulkDelete'])->name('reviews.bulkDelete');
});

### GET APPLICABLE TAX ###
Route::post('tax-classes/get-applicable-tax-class', [TaxClassController::class, 'getApplicableTaxClass'])->name('tax-classes.getApplicableTaxClass');

### GET CUSTOMER ADDRESSES ###
Route::get('customers/get-customer-addresses', [CustomerController::class, 'getCustomerAddresses'])->name('customers.getCustomerAddresses');

### FRONTEND Home Page ROUTE ###
Route::get('/', [HomeController::class, 'index'])->name('frontend.home');
Route::get('/home', function () {
    return redirect()->route('frontend.index');
});

### FRONTEND AUTH ROUTE ###
Route::get('sign-in', [FrontendAuthController::class, 'signIn'])->name('frontend.signIn');
Route::post('sign-in/authenticate', [FrontendAuthController::class, 'handleSignIn'])->name('frontend.handleSignIn');
Route::post('sign-up', [FrontendAuthController::class, 'handleSignUp'])->name('frontend.handleSignUp');
Route::get('logout', [FrontendAuthController::class, 'logout'])->name('frontend.logout');
Route::get('forgot-password', [FrontendAuthController::class, 'forgotPassword'])->name('frontend.forgotPassword');
Route::post('forgot-password/handle', [FrontendAuthController::class, 'handleForgotPassword'])->name('frontend.handleForgotPassword');

### product ###
Route::get('product-detail/{slug}', [FrontendProductController::class, 'productDetail'])->name('frontend.productDetail');
Route::get('quick-view/{slug}', [FrontendProductController::class, 'quickView'])->name('frontend.quickView');

### shop ###
Route::get('shop', [ShopController::class, 'shop'])->name('frontend.shop');

### cart ###
Route::get('cart', [CartController::class, 'cart'])->name('frontend.cart');
Route::get('mini-cart', [CartController::class, 'miniCart'])->name('frontend.miniCart');
Route::post('add-to-cart/{slug}', [CartController::class, 'addToCart'])->name('frontend.addToCart');
Route::post('update-cart/{slug}', [CartController::class, 'update'])->name('frontend.update');
Route::post('remove-from-cart/{slug}', [CartController::class, 'remove'])->name('frontend.remove');
Route::get('clear-cart', [CartController::class, 'clearCart'])->name('frontend.clearCart');

### CHECKOUT ###
Route::get('checkout', [CartController::class, 'checkoutView'])->name('frontend.checkoutView');
Route::post('checkout-success', [CartController::class, 'checkout'])->name('frontend.checkout');

### FRONTEND AUTHENTICATED ROUTE ###
Route::group(['middleware' => ['customer']], function () {
    ### dashboard ###
    Route::get('/dashboard', [FrontendDashboardController::class, 'index'])->name('frontend.dashboard');

    ### customer ###
    Route::post('customers-handle-update/{id}', [FrontendAuthController::class, 'handleUpdate'])->name('frontend.handleUpdate');

    ### addresses ###
    Route::get('addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('addresses/store', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('addresses/edit/{id}', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::post('addresses/update/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::get('addresses/delete/{id}', [AddressController::class, 'destroy'])->name('addresses.delete');
    Route::get('addresses/load-addresses', [AddressController::class, 'loadAddresses'])->name('addresses.loadAddresses');

    ### wishlist ###
    Route::get('wishlist', [WishlistController::class, 'wishlist'])->name('frontend.wishlist');
    Route::post('add-to-wishlist/{id}', [WishlistController::class, 'addToWishlist'])->name('frontend.addToWishlist');
    Route::post('remove-from-wishlist/{id}', [WishlistController::class, 'removeFromWishlist'])->name('frontend.removeFromWishlist');

    ### order ###
    Route::get('order-detail/{id}', [FrontendOrderController::class, 'orderDetail'])->name('frontend.orderDetail');
});

### SYNC / GENERATE PRODUCT AND CATEGORY SLUG ###
Route::get('sync-slugs', function () {
    return syncForSlugs($_GET['type']);
});

### MAILABLE HTML VIEW ###
// Route::get('/mailable', function () {
// $order = (new Order())->_getOrderDetail('1');
// return new OrderPlaced($order);
// });

// Route::get('testAuthorizeNet', function () {
### CHECK FOR COMMUNICATION WITH `authorize.net` ###
// $ch = curl_init('https://apitest.authorize.net/xml/v1/request.api');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_VERBOSE, true);
// $data = curl_exec($ch);
// curl_close($ch);

// $json = json_decode($data);
// echo "Connection uses " . $json . "\n";
// });

// Route::get('checkSystemTls', function () {
### CHECK FOR SYSTEM TLS ###
// $ch = curl_init('https://www.howsmyssl.com/a/check');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $data = curl_exec($ch);
// curl_close($ch);

// $json = json_decode($data);
// echo "Connection uses " . $json->tls_version . "\n";
// });

### RUN `authorize.net` IN SANDBOX ###
Route::get('authorize-capture', [AuthorizeNetController::class, 'authorizeAndCapture'])->name('admin.authorizeAndCapture');

// Route::get('test', function () {
//     return view('custom');
// });

// Route::get('success', function () {
//     return view('frontend.cart.success');
// });

Route::get('contact-us', [ContactUsController::class, 'showContactUs'])->name('frontend.showContactUs');
Route::post('contact-us', [ContactUsController::class, 'handleContactUs'])->name('frontend.handleContactUs');

### CMS PAGES ###
Route::get('/about-us', [CmsController::class, 'loadCmsPage'])->name('frontend.aboutUs');
Route::get('/terms-and-conditions', [CmsController::class, 'loadCmsPage'])->name('frontend.termsAndConditions');
Route::get('/privacy-policy', [CmsController::class, 'loadCmsPage'])->name('frontend.privacyPolicy');
Route::get('/faq', [FrontendFaqController::class, 'index'])->name('frontend.faq');
Route::get('/why-us', [CmsController::class, 'loadCmsPage'])->name('frontend.whyUs');
Route::get('/shipping-unboxing', [CmsController::class, 'loadCmsPage'])->name('frontend.shippingUnboxing');
Route::get('/stores', [FrontendStoreController::class, 'index'])->name('frontend.stores');
Route::get('/sales-cancellation-policy', [CmsController::class, 'loadCmsPage'])->name('frontend.salesCancellationPolicy');
Route::get('/warranties', [CmsController::class, 'loadCmsPage'])->name('frontend.warranties');

### PAYMENTS ###
Route::get('/payments/{encrypted_id}', [PaymentController::class, 'orderPayment'])->name('payments.index');
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
Route::get('payment-success', [PaymentController::class, 'paymentSuccess'])->name('payments.success');

### NEWSLETTER ###
Route::post('/newsletter/store', [SubscriberController::class, 'store'])->name('frontend.newsletter');
Route::post('/newsletter/sub-unsub', [SubscriberController::class, 'subUnsubToNewsletter'])->name('frontend.subUnsubToNewsletter');

### REVIEWS ###
Route::post('/reviews/store', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('test', function () {
    return payBrightAutoCapture();
});
