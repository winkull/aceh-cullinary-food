<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers'],function(){
    Route::get('/login', 'AdminController@index')->name('login');
    Route::post('/login', 'AdminController@Login')->name('proses.login');
    Route::get('/logout', 'AdminController@Logout')->name('logout');

    Route::group(['middleware' => 'admin'], function () {
        Route::get('/', 'MainController@index');
        Route::get('/dashboard', 'MainController@index')->name('dashboard');
        
        Route::post('/dashboard/merchant/jumlah', 'MainController@Jumlah')->name('dashboard.merchant.jumlah');
        Route::post('/dashboard/merchant/total', 'MainController@Total')->name('dashboard.merchant.total');
        Route::post('/dashboard/menu/jumlah', 'MainController@JumlahMenu')->name('dashboard.menu.jumlah');
        Route::post('/dashboard/Driver/jumlah', 'MainController@JumlahDriver')->name('dashboard.driver.jumlah');

        Route::post('/dashboard/merchant/jumlah/minggu', 'MainController@JumlahMinggu')->name('dashboard.merchant.jumlah.minggu');
        Route::post('/dashboard/merchant/total/minggu', 'MainController@TotalMinggu')->name('dashboard.merchant.total.minggu');
        Route::post('/dashboard/menu/jumlah/minggu', 'MainController@JumlahMenuMinggu')->name('dashboard.menu.jumlah.minggu');
        Route::post('/dashboard/Driver/jumlah/minggu', 'MainController@JumlahDriverMinggu')->name('dashboard.driver.jumlah.minggu');

        Route::post('/dashboard/merchant/jumlah/bulan', 'MainController@JumlahBulan')->name('dashboard.merchant.jumlah.bulan');
        Route::post('/dashboard/merchant/total/bulan', 'MainController@TotalBulan')->name('dashboard.merchant.total.bulan');
        Route::post('/dashboard/menu/jumlah/bulan', 'MainController@JumlahMenuBulan')->name('dashboard.menu.jumlah.bulan');
        Route::post('/dashboard/Driver/jumlah/bulan', 'MainController@JumlahDriverBulan')->name('dashboard.driver.jumlah.bulan');

        Route::get('/tenant', 'TokoController@index')->name('tenant');
        Route::get('/tenant/create', 'TokoController@tenantCreate')->name('tenant.create');
        Route::post('/tenant/add', 'TokoController@tenantInsert')->name('tenant.add');
        Route::get('/tenant/edit', 'TokoController@tenantEdit')->name('tenant.edit');
        Route::post('/tenant/update', 'TokoController@tenantUpdate')->name('tenant.update');
        Route::get('/tenant/delete', 'TokoController@tenantDelete')->name('tenant.delete');
        Route::post('/tenant/data', 'TokoController@tenantData')->name('tenant.data');
        Route::get('/tenant/buka', 'TokoController@Buka')->name('tenant.buka');
        Route::get('/tenant/tutup', 'TokoController@Tutup')->name('tenant.tutup');

        Route::get('/pameran', 'PameranController@index')->name('pameran');
        Route::get('/pameran/create', 'PameranController@Create')->name('pameran.create');
        Route::post('/pameran/add', 'PameranController@Insert')->name('pameran.add');
        Route::get('/pameran/edit', 'PameranController@Edit')->name('pameran.edit');
        Route::post('/pameran/update', 'PameranController@Update')->name('pameran.update');
        Route::get('/pameran/delete', 'PameranController@Delete')->name('pameran.delete');
        Route::post('/pameran/data', 'PameranController@Data')->name('pameran.data');

        Route::get('/menu', 'MenuController@index')->name('menu');
        Route::get('/menu/create', 'MenuController@menuCreate')->name('menu.create');
        Route::post('/menu/add', 'MenuController@menuInsert')->name('menu.add');
        Route::get('/menu/edit', 'MenuController@menuEdit')->name('menu.edit');
        Route::post('/menu/update', 'MenuController@menuUpdate')->name('menu.update');
        Route::get('/menu/delete', 'MenuController@menuDelete')->name('menu.delete');
        Route::post('/menu/data', 'MenuController@menuData')->name('menu.data');

        Route::get('/promo', 'PromoController@index')->name('promo');
        Route::get('/promo/create', 'PromoController@promoCreate')->name('promo.create');
        Route::post('/promo/add', 'PromoController@promoInsert')->name('promo.add');
        Route::get('/promo/edit', 'PromoController@promoEdit')->name('promo.edit');
        Route::post('/promo/update', 'PromoController@promoUpdate')->name('promo.update');
        Route::get('/promo/delete', 'PromoController@promoDelete')->name('promo.delete');
        Route::post('/promo/data', 'PromoController@promoData')->name('promo.data');

        Route::get('/promokhusus', 'PromoKhususController@index')->name('promokhusus');
        Route::get('/promokhusus/create', 'PromoKhususController@promoCreate')->name('promokhusus.create');
        Route::post('/promokhusus/add', 'PromoKhususController@promoInsert')->name('promokhusus.add');
        Route::get('/promokhusus/edit', 'PromoKhususController@promoEdit')->name('promokhusus.edit');
        Route::post('/promokhusus/update', 'PromoKhususController@promoUpdate')->name('promokhusus.update');
        Route::get('/promokhusus/delete', 'PromoKhususController@promoDelete')->name('promokhusus.delete');
        Route::post('/promokhusus/data', 'PromoKhususController@promoData')->name('promokhusus.data');

        Route::get('/konten', 'KontenController@index')->name('konten');
        Route::get('/konten/create', 'KontenController@kontenCreate')->name('konten.create');
        Route::post('/konten/add', 'KontenController@kontenInsert')->name('konten.add');
        Route::get('/konten/edit', 'KontenController@kontenEdit')->name('konten.edit');
        Route::post('/konten/update', 'KontenController@kontenUpdate')->name('konten.update');
        Route::get('/konten/delete', 'KontenController@kontenDelete')->name('konten.delete');
        Route::post('/konten/data', 'KontenController@kontenData')->name('konten.data');

        Route::get('/quiz', 'QuizController@index')->name('quiz');
        Route::get('/quiz/create', 'QuizController@Create')->name('quiz.create');
        Route::post('/quiz/add', 'QuizController@Insert')->name('quiz.add');
        Route::get('/quiz/edit', 'QuizController@Edit')->name('quiz.edit');
        Route::post('/quiz/update', 'QuizController@Update')->name('quiz.update');
        Route::get('/quiz/delete', 'QuizController@Delete')->name('quiz.delete');
        Route::post('/quiz/data', 'QuizController@Data')->name('quiz.data');

        Route::get('/ongkir', 'OngkosKirimController@index')->name('ongkir');
        Route::post('/ongkir/data', 'OngkosKirimController@data')->name('ongkir.data');
        Route::post('/ongkir/update', 'OngkosKirimController@update')->name('ongkir.update');

        Route::get('/laporan/pesanan', 'ReportController@indexPesanan')->name('laporan.pesanan');
        Route::post('/laporan/pesanan/tampil', 'ReportController@tampilPesanan')->name('laporan.pesanan.tampilan');
        Route::get('/laporan/pesanan/pdf', 'ReportController@laporanPesananPdf')->name('laporan.pesanan.pdf');
        Route::post('/laporan/pesanan/detail/tampil', 'ReportController@tampilDetailPesanan')->name('laporan.pesanan.detail.tampilan');
        Route::get('/laporan/pesanan/detail/pdf', 'ReportController@laporanPesananDetailPdf')->name('laporan.pesanan.detail.pdf');

        Route::get('/laporan/pengguna', 'ReportController@indexPengguna')->name('laporan.pengguna');
        Route::post('/laporan/pengguna/tampil', 'ReportController@tampilPengguna')->name('laporan.pengguna.tampilan');
        Route::get('/laporan/pengguna/pdf', 'ReportController@laporanPenggunaPdf')->name('laporan.pengguna.pdf');
        Route::post('/laporan/pengguna/detail/tampil', 'ReportController@tampilDetailPengguna')->name('laporan.pengguna.detail.tampilan');
        Route::get('/laporan/pengguna/detail/pdf', 'ReportController@laporanPenggunaDetailPdf')->name('laporan.pengguna.detail.pdf');
        
        Route::get('/profile', 'ProfileController@index')->name('profile');
        Route::post('/profile/edit', 'ProfileController@edit')->name('profile.edit');
        Route::post('/profile/resetpassword', 'ProfileController@resetPassword')->name('profile.reset.password');
    });    
});
