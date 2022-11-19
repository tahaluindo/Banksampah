<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->add('/',                  'HomePage::index');
$routes->add('/penghargaan',       'HomePage::listPenghargaan');
$routes->add('/homepage/(:any)',   'HomePage::listArtikel/$1');
$routes->add('/artikel/(:any)',    'HomePage::detilArtikel/$1');
$routes->get("/homepage/statistik","HomePage::getStatistik");
$routes->get("/privacy",function () {
    echo view("HomePage/privacy",[
        "title" => "Privacy Policy"
    ]);
});

$routes->group("register", function ($routes) {
    // VIEWS
    $routes->add('/',        'Register::registerNasabahView');
    // API
    $routes->post("nasabah", 'Register::nasabahRegister');
    $routes->post("admin",   'Register::adminRegister');
    $routes->add("(:any)",   "Notfound::PageNotFound");
});

$routes->group("otp", function ($routes) {
    // VIEWS
    $routes->add('/',       'Otp::otpView');
    // API
    $routes->post("verify", 'Otp::verifyOtp');
    $routes->post("resend",  'Otp::resendOtp');
    $routes->add("(:any)",  "Notfound::PageNotFound");
});

$routes->group("login", function ($routes) {
    // VIEWS
    $routes->add('/',           'Login::nasabahLoginView');
    $routes->add('admin',       'Login::adminLoginView');
    // API
    $routes->post("forgotpass", 'Login::forgotPassword');
    $routes->post("nasabah",    'Login::nasabahLogin');
    $routes->post("admin",      'Login::adminLogin');
    $routes->add("(:any)",      "Notfound::PageNotFound");
});

$routes->group("nasabah", function ($routes) {
    // VIEWS
    $routes->add('/',           'Nasabah::dashboardNasabah');
    $routes->add('profile',     'Nasabah::profileNasabah');
    // API
    $routes->get("sessioncheck","Nasabah::sessionCheck");
    $routes->get("getprofile",  "Nasabah::getProfile");
    $routes->put("editprofile", "Nasabah::editProfile");
    $routes->delete("logout",   "Nasabah::logout");
    $routes->get("getsaldo",    "Nasabah::getSaldo");
    $routes->get('wilayah',     'Nasabah::getWilayah');
    $routes->post('sendkritik', 'Nasabah::sendKritik');
    $routes->add("(:any)",      "Notfound::PageNotFound");
});

$routes->group("admin", function ($routes) {
    // VIEWS
    $routes->add('/',                  'Admin::dashboardAdmin');
    $routes->add('listsampah',         'Admin::listSampahView');
    $routes->add('transaksi',          'Admin::transaksiPage');
    $routes->add('listnasabah',        'Admin::listNasabahView');
    $routes->add('detilnasabah/(:any)','Admin::detilNasabahView/$1');
    $routes->add('listadmin',          'Admin::listAdminView');
    $routes->add('listpenghargaan',    'Admin::listPenghargaanView');
    $routes->add('listmitra',          'Admin::listMitraView');
    $routes->add('kategoriartikel',    'Admin::kategoriArtikelView');
    $routes->add('listartikel',        'Admin::listArtikelView');
    $routes->add('addartikel',         'Admin::addArtikelView');
    $routes->add('editartikel/(:any)', 'Admin::editArtikelView/$1');
    $routes->add('profile',            'Admin::profileAdmin');
    $routes->add('printlistnasabah',   'Admin::printListNasabah');
    // API
    // $routes->post("login",           "Admin::login");
    $routes->post("confirmdelete",   "Admin::confirmDelete");
    $routes->get("sessioncheck",     "Admin::sessionCheck");
    $routes->get("getprofile",       "Admin::getProfile");
    $routes->put("editprofile",      "Admin::editProfile");
    $routes->delete("logout",        "Admin::logout");
    $routes->get("totalakun",        "Admin::totalAkun");
    $routes->get("getnasabah",       "Admin::getNasabah");
    $routes->post("addnasabah",      "Admin::addNasabah");
    $routes->put("editnasabah",      "Admin::editNasabah");
    $routes->delete("deletenasabah", "Admin::deleteNasabah");
    $routes->get("getadmin",         "Admin::getAdmin");
    $routes->put("editadmin",        "Admin::editAdmin");
    $routes->delete("deleteadmin",   "Admin::deleteAdmin");
    $routes->add("(:any)",           "Notfound::PageNotFound");
});

$routes->group("artikel", function ($routes) {
    $routes->post("addkategori",      "Kategori::addKategori/kategori_artikel");
    $routes->put("editkategori",      "Kategori::editKategoriArtikel");
    $routes->delete("deletekategori", "Kategori::deleteKategori/kategori_artikel");
    $routes->get("getkategori",       "Kategori::getKategori/kategori_artikel");
    $routes->post("addartikel",       "Artikel::addArtikel");
    $routes->put("editartikel",       "Artikel::editArtikel");
    $routes->delete("deleteartikel",  "Artikel::deleteArtikel");
    $routes->get("getartikel",        "Artikel::getArtikel");
    $routes->get("relatedartikel",    "Artikel::getRelatedArtikel");
    $routes->add("(:any)",            "Notfound::PageNotFound");
});

$routes->group("sampah", function ($routes) {
    $routes->post("addkategori",      "Kategori::addKategori/kategori_sampah");
    $routes->delete("deletekategori", "Kategori::deleteKategori/kategori_sampah");
    $routes->get("getkategori",       "Kategori::getKategori/kategori_sampah");
    $routes->post("addsampah",        "Sampah::addSampah");
    $routes->put("editsampah",        "Sampah::editSampah");
    $routes->delete("deletesampah",   "Sampah::deleteSampah");
    $routes->get("getsampah",         "Sampah::getSampah");
    $routes->add("(:any)",            "Notfound::PageNotFound");
});

$routes->group("penghargaan", function ($routes) {
    $routes->post("addpenghargaan",      "Penghargaan::addPenghargaan");
    $routes->delete("deletepenghargaan", "Penghargaan::deletePenghargaan");
    $routes->get("getpenghargaan",       "Penghargaan::getPenghargaan");
    $routes->add("(:any)",               "Notfound::PageNotFound");
});

$routes->group("mitra", function ($routes) {
    $routes->post("addmitra",      "Mitra::addMitra");
    $routes->delete("deletemitra", "Mitra::deleteMitra");
    $routes->get("getmitra",       "Mitra::getMitra");
    $routes->add("(:any)",         "Notfound::PageNotFound");
});

$routes->group("transaksi", function ($routes) {
    $routes->add('cetaktransaksi/(:any)','Transaksi::cetakTransaksi/$1');
    $routes->add('cetakrekap',           'Transaksi::cetakRekap');
    $routes->add('cetakrekap/penimbangan-sampah', 'Transaksi::cetakLaporanPenimbanganSampah');
    $routes->add('cetakrekap/penarikan-saldo',    'Transaksi::cetakLaporanPenarikanSaldo');
    $routes->add('cetakrekap/penjualan-sampah',   'Transaksi::cetakLaporanPenjualanSampah');
    $routes->add('cetakrekap/konversi-saldo',     'Transaksi::cetakLaporanKonversiSaldo');
    $routes->add('cetakrekap/buku-tabungan',      'Transaksi::cetakLaporanBukuTabungan');
    //API
    $routes->post("setorsampah",       "Transaksi::setorSampah");
    $routes->post("editsetorsampah",   "Transaksi::editSetorSampah");
    $routes->post("tariksaldo",        "Transaksi::tarikSaldo");
    $routes->post("tariksaldobsbl",    "Transaksi::tarikSaldo/bsbl");
    $routes->post("pindahsaldo",       "Transaksi::pindahSaldo");
    $routes->post("jualsampah",        "Transaksi::jualSampah");
    $routes->get("sampahmasuk",        "Transaksi::getSampahMasuk");
    $routes->get("getsaldo",           "Transaksi::getSaldo");
    $routes->get("getdata",            "Transaksi::getData");
    $routes->get("rekapdata",          "Transaksi::rekapData");
    $routes->get("grafikssampah",      "Transaksi::grafikSetorSampah");
    $routes->delete("deletedata",      "Transaksi::deleteData");
    $routes->add("(:any)",             "Notfound::PageNotFound");
});

$routes->add('/(:any)', 'Notfound::PageNotFound');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
