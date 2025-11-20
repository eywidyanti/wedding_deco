<?php

use App\Http\Controllers\admin\DekorController;
use App\Http\Controllers\admin\GaleriController;
use App\Http\Controllers\admin\PaketController;
use App\Http\Controllers\admin\PembayaranController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\klien\BookingController;
use App\Http\Controllers\klien\CartController;
use App\Http\Controllers\klien\PaymentController;
use App\Http\Controllers\ProfilController;
use App\Models\Cart;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [DashboardController::class, 'welcome']);
Route::get('/paket/{slug}/detailwc', [DashboardController::class, 'detailwc'])->name('detailwc');

Auth::routes();

Route::middleware(['auth', 'user-access:user'])->group(function () {
  
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/profilKlien', [UserController::class, 'profilKlien'])->name('profilKlien');
    Route::post('/profile/update', [ProfilController::class, 'update'])->name('profile.update');
    Route::post('/update-profile-image', [ProfilController::class, 'updateProfileImage'])->name('profile.img.update');

    
    //cart
    Route::get('/paket/{slug}/detail', [HomeController::class, 'detail'])->name('detail');
    Route::get('add-to-cart/{dekor:id}', [CartController::class, 'addToCart'])->name('add.to.cart');
    Route::patch('update-cart', [CartController::class, 'update'])->name('update.cart');
    Route::delete('remove-from-cart', [CartController::class, 'remove'])->name('remove.from.cart');

    //booking
    Route::get('/booking', [BookingController::class, 'checkout'])->name('checkout');
    Route::post('/boooking/process', [BookingController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/booking-success', [BookingController::class, 'showSuccess'])->name('booking.success');
    Route::get('/booking.expired', [BookingController::class, 'showExpired'])->name('booking.expired');
    Route::get('/booking.pending', [BookingController::class, 'showPending'])->name('booking.pending');
    Route::get('/booking.kirim', [BookingController::class, 'showKirim'])->name('booking.kirim');
    Route::get('/booking.selesai', [BookingController::class, 'showSelesai'])->name('booking.selesai');
    Route::get('/booking.cancel', [BookingController::class, 'showCancel'])->name('booking.cancel');

    //payment
    Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('process.payment');
    Route::post('/process-dp', [PaymentController::class, 'processPaymentDp'])->name('process.dp');
    Route::get('/payment-status/{id}', [PaymentController::class, 'paymentStatus'])->name('payment.status');
    Route::get('/payment', [PaymentController::class, 'showPayment'])->name('payment');
    Route::post('/process-cancel', [PaymentController::class, 'cancel'])->name('process.cancel');
    Route::post('/detail.sewa', [PaymentController::class, 'detailPemesan'])->name('detail.sewa');

    //dragndrop
    Route::get('/dragndrop', [DashboardController::class, 'DragnDrop'])->name('DragnDrop');
    Route::get('/DragnDrop/simpan', [DashboardController::class, 'simpan']);
    Route::get('/dragndropcart', [DashboardController::class, 'cart_drag_n_drop'])->name('dragndropcart');

    //cari-tanggal
    Route::get('/caridekor', [DashboardController::class, 'cari'])->name('caridekor');

    Route::post('/cetakKwitansi', [DashboardController::class, 'kwitansi'])->name('cetakKwitansi');
    Route::post('/cetakBayar', [DashboardController::class, 'cetak'])->name('cetakBayar');

    // Route::post('/booking.success.ya', [PaymentController::class, 'bookingSuccess'])->name('booking.success');
    
    //Route::get('/DragnDrop', [weddingController::class, 'DragnDrop'])->name('DragnDrop');
});


  
/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:admin'])->group(function () {
  
    Route::get('/admin', [HomeController::class, 'adminHome'])->name('admin');
    Route::get('/profilAdmin', [UserController::class, 'profilAdmin'])->name('profilAdmin');
    Route::post('/admin/update', [ProfilController::class, 'updateAdmin'])->name('admin.update');
    Route::post('/update-profile-image-admin', [ProfilController::class, 'updateProfileAdmin'])->name('profile.imgadmin.update');

     //dekor
    Route::resource('dekor', DekorController::class);
    Route::get('/dekor/{dekor:slug}/edit', [DekorController::class, 'edit'])->name('dekor.edit');
    Route::get('/dekor/{dekor:slug}/show', [DekorController::class, 'show'])->name('dekor.show.slug');

    //paket
    Route::resource('paket', PaketController::class);
    Route::get('/paketDragndrop', [PaketController::class, 'DragnDropAdmin'])->name('paketDragndrop');
    Route::get('/DragnDropAdmin/simpanDragnDrop', [PaketController::class, 'simpanDragnDrop']);
    Route::get('/dragndroppaket', [PaketController::class, 'paket_drag_n_drop'])->name('dragndroppaket');
    Route::get('/paket/{paket:slug}/edit', [PaketController::class, 'edit'])->name('paket.edit');
    Route::get('/paket/{paket:slug}/show', [PaketController::class, 'show'])->name('paket.show.slug');

    //galeri
    Route::resource('galeri', GaleriController::class);
    Route::get('/galeri/{galeri:slug}/edit', [GaleriController::class, 'edit'])->name('galeri.edit');
    Route::get('/galeri/{galeri:slug}/show', [GaleriController::class, 'show'])->name('galeri.show.slug');

    Route::resource('user', UserController::class);
    // Route::get('/user/{user:slug}/edit', [UserController::class, 'edit'])->name('user.edit');
    // Route::get('/user/{user:slug}/show', [UserController::class, 'show'])->name('user.show');

    //tampilan
    Route::get('/pembayaran.pending', [PembayaranController::class, 'pending'])->name('pembayaran.pending');
    Route::get('/pembayaran.kirim', [PembayaranController::class, 'kirim'])->name('pembayaran.kirim');
    Route::get('/pembayaran.selesi', [PembayaranController::class, 'selesai'])->name('pembayaran.selesai');
    Route::get('/pembayaran.cancel', [PembayaranController::class, 'cancel'])->name('pembayaran.cancel');

    //proses kirim
    Route::post('/process-kirim', [PembayaranController::class, 'processKirim'])->name('process.kirim');
    Route::post('/process-selesai', [PembayaranController::class, 'processSelesai'])->name('process.selesai');
    
    //detailpesanan
    Route::post('/detail.pemesan', [PembayaranController::class, 'detailPemesan'])->name('detail.pemesan');
    //laporan sewa
    Route::get('/laporan', [PembayaranController::class, 'laporan'])->name('laporan');

    //cari-tanggal
    Route::get('/cari', [PembayaranController::class, 'laporan'])->name('cari');
    Route::get('/cetak', [PembayaranController::class, 'cetak'])->name('cetak');

    
});
