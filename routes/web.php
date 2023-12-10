<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MasterPenggunaController;
use App\Http\Controllers\MasterRoleController;
use App\Http\Controllers\MasterBalitaController;
use App\Http\Controllers\MasterPendudukController;
use App\Http\Controllers\MasterRemajaController;
use App\Http\Controllers\MasterCatinController;
use App\Http\Controllers\MasterHamilController;
use App\Http\Controllers\MasterPendudukAsuhController;
use App\Http\Controllers\MasterEppgbmController;
use App\Http\Controllers\MasterKontenController;
use App\Http\Controllers\MasterBantuanController;
use App\Http\Controllers\MasterSanitasiController;
use App\Http\Controllers\MasterImportController;
use App\Http\Controllers\MainController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

/*Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});*/

Route::get('/', [MainController::class, 'index'])->name('main');

/*Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::get('/dashboard', [MainController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('allowed')->group(function () {
        //balita
        Route::prefix('balita')->group(function () {
            Route::get('/', [MasterBalitaController::class, 'index'])->name('balita');
            Route::get('/create', [MasterBalitaController::class, 'create'])->name('balita.create');
            Route::get('/show', [MasterBalitaController::class, 'show'])->name('balita.show');
            Route::post('/store', [MasterBalitaController::class, 'store'])->name('balita.store');
            Route::get('/edit/{id}', [MasterBalitaController::class, 'edit'])->name('balita.edit');
            Route::post('/update/{id}', [MasterBalitaController::class, 'update'])->name('balita.update');
        });

        //pengguna
        Route::prefix('pengguna')->group(function () {
            Route::get('/', [MasterPenggunaController::class, 'index'])->name('pengguna');
            Route::get('/create', [MasterPenggunaController::class, 'create'])->name('pengguna.create');
            Route::post('/store', [MasterPenggunaController::class, 'store'])->name('pengguna.store');
            Route::get('/edit/{id}', [MasterPenggunaController::class, 'edit'])->name('pengguna.edit');
            Route::post('/update/{id}', [MasterPenggunaController::class, 'update'])->name('pengguna.update');
            Route::delete('/destroy/{id}', [MasterPenggunaController::class, 'destroy'])->name('pengguna.destroy');
        });

        //Role / Hak Akses
        Route::prefix('akses')->group(function () {
            Route::get('/', [MasterRoleController::class, 'index'])->name('akses');
            Route::get('/create', [MasterRoleController::class, 'create'])->name('akses.create');
            Route::post('/store', [MasterRoleController::class, 'store'])->name('akses.store');
            Route::get('/edit/{id}', [MasterRoleController::class, 'edit'])->name('akses.edit');
            Route::post('/update/{id}', [MasterRoleController::class, 'update'])->name('akses.update');
            Route::delete('/destroy/{id}', [MasterRoleController::class, 'destroy'])->name('akses.destroy');
        });

        //penduduk
        Route::prefix('penduduk')->group(function () {
            Route::get('/', [MasterPendudukController::class, 'index'])->name('penduduk');
            Route::get('/create', [MasterPendudukController::class, 'create'])->name('penduduk.create');
            Route::post('/store', [MasterPendudukController::class, 'store'])->name('penduduk.store');
            Route::get('/edit/{id}', [MasterPendudukController::class, 'edit'])->name('penduduk.edit');
            Route::post('/update/{id}', [MasterPendudukController::class, 'update'])->name('penduduk.update');
            Route::delete('/destroy/{id}', [MasterPendudukController::class, 'destroy'])->name('penduduk.destroy');
        });

        //remaja putri
        Route::prefix('remaja')->group(function () {
            Route::get('/', [MasterRemajaController::class, 'index'])->name('remaja');
            Route::get('/create', [MasterRemajaController::class, 'create'])->name('remaja.create');
            Route::post('/store', [MasterRemajaController::class, 'store'])->name('remaja.store');
            Route::get('/edit/{id}', [MasterRemajaController::class, 'edit'])->name('remaja.edit');
            Route::post('/update/{id}', [MasterRemajaController::class, 'update'])->name('remaja.update');
            Route::delete('/destroy/{id}', [MasterRemajaController::class, 'destroy'])->name('remaja.destroy');
        });

        //ibu hamil
        Route::prefix('hamil')->group(function () {
            Route::get('/', [MasterHamilController::class, 'index'])->name('hamil');
            Route::get('/create', [MasterHamilController::class, 'create'])->name('hamil.create');
            Route::post('/store', [MasterHamilController::class, 'store'])->name('hamil.store');
            Route::get('/edit/{id}', [MasterHamilController::class, 'edit'])->name('hamil.edit');
            Route::post('/update/{id}', [MasterHamilController::class, 'update'])->name('hamil.update');
            Route::delete('/destroy/{id}', [MasterHamilController::class, 'destroy'])->name('hamil.destroy');
        });

        //Catin
        Route::prefix('catin')->group(function () {
            Route::get('/', [MasterCatinController::class, 'index'])->name('catin');
            Route::get('/create', [MasterCatinController::class, 'create'])->name('catin.create');
            Route::post('/store', [MasterCatinController::class, 'store'])->name('catin.store');
            Route::get('/edit/{id}', [MasterCatinController::class, 'edit'])->name('catin.edit');
            Route::post('/update/{id}', [MasterCatinController::class, 'update'])->name('catin.update');
            Route::delete('/destroy/{id}', [MasterCatinController::class, 'destroy'])->name('catin.destroy');
        });

        //orang tua asuh
        Route::prefix('asuh')->group(function () {
            Route::get('/', [MasterPendudukAsuhController::class, 'index'])->name('asuh');
            Route::get('/create', [MasterPendudukAsuhController::class, 'create'])->name('asuh.create');
            Route::post('/store', [MasterPendudukAsuhController::class, 'store'])->name('asuh.store');
            Route::get('/edit/{id}', [MasterPendudukAsuhController::class, 'edit'])->name('asuh.edit');
            Route::post('/update/{id}', [MasterPendudukAsuhController::class, 'update'])->name('asuh.update');
            Route::delete('/destroy/{id}', [MasterPendudukAsuhController::class, 'destroy'])->name('asuh.destroy');
        });

         //bantuan
        Route::prefix('bantuan')->group(function () {
            Route::get('/', [MasterBantuanController::class, 'index'])->name('bantuan');
            Route::get('/create', [MasterBantuanController::class, 'create'])->name('bantuan.create');
            Route::post('/store', [MasterBantuanController::class, 'store'])->name('bantuan.store');
            Route::get('/edit/{id}', [MasterBantuanController::class, 'edit'])->name('bantuan.edit');
            Route::post('/update/{id}', [MasterBantuanController::class, 'update'])->name('bantuan.update');
            Route::delete('/destroy/{id}', [MasterBantuanController::class, 'destroy'])->name('bantuan.destroy');
        });
        
        //balita
        Route::prefix('eppgbm')->group(function () {
            Route::get('/', [MasterEppgbmController::class, 'index'])->name('eppgbm');
            Route::get('/create', [MasterEppgbmController::class, 'create'])->name('eppgbm.create');
            Route::post('/store', [MasterEppgbmController::class, 'store'])->name('eppgbm.store');
            Route::get('/edit/{id}', [MasterEppgbmController::class, 'edit'])->name('eppgbm.edit');
            Route::post('/update/{id}', [MasterEppgbmController::class, 'update'])->name('eppgbm.update');
            Route::delete('/destroy/{id}', [MasterEppgbmController::class, 'destroy'])->name('eppgbm.destroy');
        });

        //sanitasi
        Route::prefix('sanitasi')->group(function () {
            Route::get('/', [MasterSanitasiController::class, 'index'])->name('sanitasi');
            //Route::get('/create', [MasterSanitasiController::class, 'create'])->name('sanitasi.create');
            Route::post('/store', [MasterSanitasiController::class, 'store'])->name('sanitasi.store');
            //Route::get('/edit/{id}', [MasterSanitasiController::class, 'edit'])->name('sanitasi.edit');
            Route::post('/update/{id}', [MasterSanitasiController::class, 'update'])->name('sanitasi.update');
            //Route::delete('/destroy/{id}', [MasterSanitasiController::class, 'destroy'])->name('sanitasi.destroy');
        });

        //master
        Route::get('/master', [ProfileController::class, 'edit'])->name('master');

        //konten
        Route::prefix('konten')->group(function () {
            Route::get('/', [MasterKontenController::class, 'index'])->name('konten');
            Route::get('/create', [MasterKontenController::class, 'create'])->name('konten.create');
            Route::get('/edit/{id}', [MasterKontenController::class, 'edit'])->name('konten.edit');
            //Route::post('/update/{id}', [MasterKontenController::class, 'update'])->name('konten.update');
        });

        //import
        Route::prefix('import')->group(function () {
            Route::get('/', [MasterImportController::class, 'index'])->name('import');
            Route::get('/create', [MasterImportController::class, 'create'])->name('import.create');
            Route::get('/show', [MasterImportController::class, 'show'])->name('import.show');
            Route::post('/store', [MasterImportController::class, 'store'])->name('import.store');
            Route::get('/edit/{id}', [MasterImportController::class, 'edit'])->name('import.edit');
            Route::post('/update/{id}', [MasterImportController::class, 'update'])->name('import.update');
            Route::delete('/destroy/{id}', [MasterImportController::class, 'destroy'])->name('import.destroy');
        });
    });
});

require __DIR__.'/auth.php';
