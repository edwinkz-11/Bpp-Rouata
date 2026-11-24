<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website publik
|--------------------------------------------------------------------------
*/
Route::middleware('throttle:public')->group(function () {
    Route::get('/', [Web\HomeController::class, 'index'])->name('home');
    Route::get('/profil-kecamatan', [Web\DistrictController::class, 'show'])->name('district');
    Route::get('/kegiatan', [Web\ActivityController::class, 'index'])->name('activities.index');
    Route::get('/kegiatan/{activity:slug}', [Web\ActivityController::class, 'show'])->name('activities.show');
    Route::get('/potensi-pertanian', [Web\CommodityController::class, 'index'])->name('commodities.index');
    Route::get('/peta-wilayah', [Web\MapController::class, 'index'])->name('map');
    Route::get('/peta-wilayah/data', [Web\MapController::class, 'data'])->name('map.data');
    Route::get('/galeri', [Web\GalleryController::class, 'index'])->name('gallery');
    Route::get('/berita', [Web\NewsController::class, 'index'])->name('news');
    Route::get('/berita/{post:slug}', [Web\PostController::class, 'show'])->name('posts.show');
    Route::get('/tentang-bpp', [Web\AboutController::class, 'index'])->name('about');
    Route::get('/dokumen', [Web\DocumentController::class, 'index'])->name('documents.index');
    Route::get('/dokumen/{document}/unduh', [Web\DocumentController::class, 'download'])->name('documents.download');
    Route::get('/ppid', [Web\PpidController::class, 'index'])->name('ppid');
    Route::get('/kontak', [Web\ContactController::class, 'create'])->name('contact');
    Route::post('/kontak', [Web\ContactController::class, 'store'])->middleware('throttle:contact')->name('contact.store');
    Route::get('/cari', [Web\SearchController::class, 'index'])->middleware('throttle:search')->name('search');
    Route::get('/kebijakan-privasi', [Web\PageController::class, 'privacy'])->name('privacy');
    Route::get('/sitemap.xml', [Web\SeoController::class, 'sitemap'])->name('sitemap');
    Route::get('/robots.txt', [Web\SeoController::class, 'robots'])->name('robots');
});

/*
|--------------------------------------------------------------------------
| Admin (tidak ada registrasi publik)
|--------------------------------------------------------------------------
*/
// Nama route "login" dibutuhkan middleware auth untuk mengarahkan tamu.
Route::redirect('/login', '/admin/login')->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');

    Route::middleware(['auth', 'auth.session'])->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('profil/{type}', [Admin\ProfileController::class, 'edit'])
            ->whereIn('type', ['bpp', 'kecamatan'])->name('profile.edit');
        Route::put('profil/{type}', [Admin\ProfileController::class, 'update'])
            ->whereIn('type', ['bpp', 'kecamatan'])->name('profile.update');

        Route::resource('penyuluh', Admin\WorkerController::class)
            ->parameters(['penyuluh' => 'worker'])->names('workers')->except('show');
        Route::resource('desa', Admin\VillageController::class)
            ->parameters(['desa' => 'village'])->names('villages')->except('show');
        Route::resource('kategori', Admin\CategoryController::class)
            ->parameters(['kategori' => 'category'])->names('categories')->except('show');
        Route::resource('kegiatan', Admin\ActivityController::class)
            ->parameters(['kegiatan' => 'activity'])->names('activities')->except('show');
        Route::resource('komoditas', Admin\CommodityController::class)
            ->parameters(['komoditas' => 'commodity'])->names('commodities')->except('show');
        Route::resource('lokasi', Admin\LocationController::class)
            ->parameters(['lokasi' => 'location'])->names('locations')->except('show');
        Route::resource('jalur', Admin\MapRouteController::class)
            ->parameters(['jalur' => 'mapRoute'])->names('routes')->except('show');

        Route::resource('dokumen', Admin\DocumentController::class)
            ->parameters(['dokumen' => 'document'])->names('documents')->except('show');
        Route::resource('berita', Admin\PostController::class)
            ->parameters(['berita' => 'post'])->names('posts')->except('show');
        Route::resource('pesan', Admin\ContactMessageController::class)
            ->parameters(['pesan' => 'message'])->names('messages')->only(['index', 'show', 'update', 'destroy']);

        Route::get('galeri', [Admin\GalleryController::class, 'index'])->name('gallery.index');
        Route::post('galeri', [Admin\GalleryController::class, 'store'])->name('gallery.store');
        Route::patch('galeri/{photo}', [Admin\GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('galeri/{photo}', [Admin\GalleryController::class, 'destroy'])->name('gallery.destroy');

        Route::get('akun', [Admin\AccountController::class, 'edit'])->name('account.edit');
        Route::put('akun', [Admin\AccountController::class, 'update'])->name('account.update');




        // Hanya Admin
        Route::middleware(EnsureRole::class . ':admin')->group(function () {
            Route::get('pengaturan', [Admin\SettingController::class, 'edit'])->name('settings.edit');
            Route::put('pengaturan', [Admin\SettingController::class, 'update'])->name('settings.update');
            Route::resource('pengguna', Admin\UserController::class)
                ->parameters(['pengguna' => 'user'])->names('users')->except('show');
            Route::get('log-aktivitas', [Admin\ActivityLogController::class, 'index'])->name('logs.index');
        });
    });
});
