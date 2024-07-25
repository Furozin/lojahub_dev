<?php

use App\Http\Controllers\{
    ContaController,
    CustoSkuController,
    EnterpriseController,
    EnterpriseSpecificityController,
    ProfileController,
    SkuController,
    SaleController
};
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(RouteServiceProvider::HOME);
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('is.enterprise.selected')->group(function () {

        Route::prefix('empresas')->name('enterprise.')->group(function () {
            Route::get('/lista', [EnterpriseSpecificityController::class, 'list'])->name('index');
            Route::get('/edit/{id}', [EnterpriseController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [EnterpriseController::class, 'update'])->name('update');
            Route::delete('/lista/{id}', [EnterpriseController::class, 'delete'])->name('delete');
        });

        Route::resource('contas', ContaController::class)->except(['show']);

        Route::resource('skus', SkuController::class)->except(['show']);

        Route::prefix('skus')->name('skus.')->group(function () {
            Route::put('{sku}/delete-image', [SkuController::class, 'deleteImage'])->name('image.destroy');
            Route::post('/importSku', [SkuController::class, 'importSku'])->name('import');
            Route::post('/exportSku', [SkuController::class, 'exportSku'])->name('export');
            Route::get('/getAccountsDropdown', [SkuController::class, 'getAccountsDropdown'])->name('accounts');
            Route::prefix('{sku}')->group(function () {
                Route::resource('custos', CustoSkuController::class)->except(['show']);
            });
        });

        Route::get('/sale', [SaleController::class, 'index'])->name('sale.index');
        Route::post('sale/importsale', [SaleController::class, 'importSale'])->name('sale.import');
        Route::get('sale/getAccountsDropdown', [SaleController::class, 'getAccountsDropdown'])->name('sale.accounts');


    });

    Route::post('/dashboard', [EnterpriseSpecificityController::class, 'update'])->name('dashboard.update');
    Route::get('/dashboard', [EnterpriseSpecificityController::class, 'index'])->name('dashboard');



    Route::prefix('empresas')->name('enterprise.')->group(function () {
        Route::get('/busca', [EnterpriseSpecificityController::class, 'search'])->name('search');
        Route::get('/create', [EnterpriseController::class, 'create'])->name('create');
        Route::post('/create', [EnterpriseController::class, 'store'])->name('create');
    });
});

require __DIR__ . '/auth.php';
