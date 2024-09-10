<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/**
 * --------------------------------------------------------------------------
 * ROUTES UNTUK FRONTEND
 * --------------------------------------------------------------------------
 */
$routes->get('/', 'Frontend::index');
$routes->get('profil', 'Frontend::profil');
$routes->get('urusan_transmigrasi', 'Frontend::transmigrasi');
$routes->get('urusan_tenaga_kerja', 'Frontend::tenaga_kerja');
$routes->get('berita', 'Frontend::berita');
$routes->get('berita/detail_berita/(:segment)', 'Frontend::detail_berita/$1');
$routes->get('berita/tag_berita/(:any)', 'Frontend::tag_berita/$1');

$routes->get('pengumuman', 'Frontend::pengumuman');
$routes->get('pengumuman/detail_pengumuman/(:segment)', 'Frontend::detail_pengumuman/$1');
$routes->get('pengumuman/tag_pengumuman/(:any)', 'Frontend::tag_pengumuman/$1');

$routes->get('pelatihan', 'Frontend::pelatihan');
$routes->get('pelatihan/detail_pelatihan/(:segment)', 'Frontend::detail_pelatihan/$1');
$routes->get('kartu_ak1', 'Frontend::kartu_ak1');
$routes->get('kontak', 'Frontend::kontak');
$routes->get('/', 'Settings::index');

/**
 * --------------------------------------------------------------------------
 * ROUTES UNTUK ADMIN
 * --------------------------------------------------------------------------
 */
$routes->get('admin_v2', 'Admin::index', ['filter' => 'role:administrator']);
$routes->get('admin_v2', 'Admin::redirectDashboard');
$routes->get('admin_v2/dashboard', 'Admin::index', ['filter' => 'role:administrator']);

$routes->get('admin_v2/pencaker', 'Admin::pencaker', ['filter' => 'role:administrator']);
$routes->get('admin_v2/pencakerajax', 'Admin::pencakerajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/pencakerajax', 'Admin::pencakerajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_status_pencaker', 'Admin::update_status_pencaker', ['filter' => 'role:administrator']);
$routes->post('admin_v2/saveVerifikasi', 'Admin::saveVerifikasi', ['filter' => 'role:administrator']);

$routes->post('admin_v2/hapus_pencaker', 'Admin::hapus_pencaker', ['filter' => 'role:administrator']);
$routes->get('admin_v2/hapus_pencaker', 'Admin::hapus_pencaker', ['filter' => 'role:administrator']);
$routes->get('admin_v2/detail_pencaker/(:num)', 'Admin::detail_pencaker/$1', ['filter' => 'role:administrator']);
$routes->get('admin_v2/kartu_ak1/(:num)', 'Admin::kartu_ak1/$1', ['filter' => 'role:administrator']);

// Profil Pencaker
$routes->get('admin_v2/update1', 'Admin::update1', ['filter' => 'role:administrator']);
$routes->get('admin_v2/getTujuan', 'Admin::getTujuan', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update1', 'Admin::update1', ['filter' => 'role:administrator']);


// Routes Berita
$routes->get('admin_v2/berita', 'Admin::berita', ['filter' => 'role:administrator']);
$routes->get('admin_v2/beritaajax', 'Admin::beritaajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/beritaajax', 'Admin::beritaajax', ['filter' => 'role:administrator']);
$routes->get('admin_v2/save_berita', 'Admin::save_berita', ['filter' => 'role:administrator']);
$routes->post('admin_v2/save_berita', 'Admin::save_berita', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_berita', 'Admin::update_berita', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_status_berita', 'Admin::update_status_berita', ['filter' => 'role:administrator']);
$routes->post('admin_v2/hapus_berita', 'Admin::hapus_berita', ['filter' => 'role:administrator']);

// Routes Slider
$routes->get('admin_v2/slider', 'Admin::slider', ['filter' => 'role:administrator']);
$routes->get('admin_v2/sliderajax', 'Admin::sliderajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/sliderajax', 'Admin::sliderajax', ['filter' => 'role:administrator']);
$routes->get('admin_v2/save_slider', 'Admin::save_slider', ['filter' => 'role:administrator']);
$routes->post('admin_v2/save_slider', 'Admin::save_slider', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_slider', 'Admin::update_slider', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_status_slider', 'Admin::update_status_slider', ['filter' => 'role:administrator']);
$routes->post('admin_v2/hapus_slider', 'Admin::hapus_slider', ['filter' => 'role:administrator']);

// Routes Pengumuman
$routes->get('admin_v2/pengumuman', 'Admin::pengumuman', ['filter' => 'role:administrator']);
$routes->get('admin_v2/pengumumanajax', 'Admin::pengumumanajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/pengumumanajax', 'Admin::pengumumanajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/save_pengumuman', 'Admin::save_pengumuman', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_pengumuman', 'Admin::update_pengumuman', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_status_pengumuman', 'Admin::update_status_pengumuman', ['filter' => 'role:administrator']);
$routes->post('admin_v2/hapus_pengumuman', 'Admin::hapus_pengumuman', ['filter' => 'role:administrator']);

// Routes Pelatihan
$routes->get('admin_v2/pelatihan', 'Admin::pelatihan', ['filter' => 'role:administrator']);
$routes->get('admin_v2/pelatihanajax', 'Admin::pelatihanajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/pelatihanajax', 'Admin::pelatihanajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/save_pelatihan', 'Admin::save_pelatihan', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_pelatihan', 'Admin::update_pelatihan', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_status_pelatihan', 'Admin::update_status_pelatihan', ['filter' => 'role:administrator']);
$routes->post('admin_v2/hapus_pelatihan', 'Admin::hapus_pelatihan', ['filter' => 'role:administrator']);
$routes->get('admin_v2/get_jenis_pelatihan', 'Admin::get_jenis_pelatihan', ['filter' => 'role:administrator']);

$routes->get('admin_v2/activitylogs', 'Admin::activitylogs', ['filter' => 'role:administrator']);
$routes->get('admin_v2/activitylogsajax', 'Admin::activitylogsajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/activitylogsajax', 'Admin::activitylogsajax', ['filter' => 'role:administrator']);
// $routes->get('admin_v2/getUsers', 'Admin::getUsers', ['filter' => 'role:administrator']);
$routes->get('admin_v2/getUsersFromLogs', 'Admin::getUsersFromLogs', ['filter' => 'role:administrator']);

// Gallery
$routes->get('admin_v2/galeri', 'Admin::galeri', ['filter' => 'role:administrator']);
$routes->get('admin_v2/galeriajax', 'Admin::galeriajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/galeriajax', 'Admin::galeriajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/save_galeri', 'Admin::save_galeri', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_galeri', 'Admin::update_galeri', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_status_galeri', 'Admin::update_status_galeri', ['filter' => 'role:administrator']);
$routes->post('admin_v2/hapus_galeri', 'Admin::hapus_galeri', ['filter' => 'role:administrator']);
$routes->get('admin_v2/get_jenis_galeri', 'Admin::get_jenis_galeri', ['filter' => 'role:administrator']);


$routes->post('admin_v2/updateGaleri', 'Admin::updateGaleri', ['filter' => 'role:administrator']);
$routes->post('admin_v2/deleteGaleri', 'Admin::deleteGaleri', ['filter' => 'role:administrator']);


// Users
$routes->get('admin_v2/users', 'Admin::users', ['filter' => 'role:administrator']);
$routes->get('admin_v2/usersajax', 'Admin::usersajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/usersajax', 'Admin::usersajax', ['filter' => 'role:administrator']);
$routes->post('admin_v2/update_status_user', 'Admin::update_status_user', ['filter' => 'role:administrator']);
$routes->post('admin_v2/hapus_user', 'Admin::hapus_user', ['filter' => 'role:administrator']);
$routes->get('admin_v2/hapus_user', 'Admin::hapus_user', ['filter' => 'role:administrator']);

$routes->get('admin_v2/settings', 'Admin::settings', ['filter' => 'role:administrator']);
$routes->get('admin_v2/backup', 'Admin::backup', ['filter' => 'role:administrator']);

// Export Data
$routes->get('admin_v2/downloadexcel', 'Admin::exportExcel', ['filter' => 'role:administrator']);
$routes->get('admin_v2/downloadpdf', 'Admin::exportPDF', ['filter' => 'role:administrator']);

$routes->get('admin_v2/profil_pencaker', 'Admin::profil_pencaker', ['filter' => 'role:administrator']);

$routes->get('admin_v2/backup', 'Admin::backup', ['filter' => 'role:administrator']);
$routes->post('admin_v2/download_db', 'Admin::download_db', ['filter' => 'role:administrator']);

$routes->get('admin_v2', 'Admin::redirectDashboard');
$routes->get('admin_v2/dashboard', 'Admin::index');

// Pengaturan Admin
$routes->post('admin_v2/update_smtp', 'Admin::update_smtp');
$routes->post('admin_v2/update_mediasosial', 'Admin::update_mediasosial');
$routes->post('admin_v2/update_detailinstansi', 'Admin::update_detailinstansi');
$routes->post('admin_v2/update_captcha', 'Admin::update_captcha');


/**
 * --------------------------------------------------------------------------
 * ROUTES UNTUK PENCAKER
 * --------------------------------------------------------------------------
 */

$routes->get('pencaker', 'Pencaker::index');
$routes->get('pencaker/dashboard', 'Pencaker::index');

$routes->get('pencaker', 'Pencaker::index', ['filter' => 'role:pencaker']);
$routes->get('pencaker/dashboard', 'Pencaker::index', ['filter' => 'role:pencaker']);
$routes->get('pencaker/profil_pencaker', 'Pencaker::profil_pencaker', ['filter' => 'role:pencaker']);

// Dokumen Pencaker
$routes->get('pencaker/dokumen_pencaker', 'Pencaker::dokumen_pencaker', ['filter' => 'role:pencaker']);
$routes->post('pencaker/dokajax', 'Pencaker::dokajax', ['filter' => 'role:pencaker']);
$routes->get('pencaker/upload_dokumen', 'Pencaker::upload_dokumen', ['filter' => 'role:pencaker']);
$routes->post('pencaker/upload_dokumen', 'Pencaker::upload_dokumen', ['filter' => 'role:pencaker']);
$routes->post('pencaker/hapus_dokumen', 'Pencaker::hapus_dokumen', ['filter' => 'role:pencaker']);

// Update Profil Pencaker
// tujuan pencaker
$routes->post('pencaker/save_data_tujuan', 'Pencaker::save_data_tujuan', ['filter' => 'role:pencaker']);
$routes->get('pencaker/get_data_tujuan/(:num)', 'Pencaker::get_data_tujuan/$1');

// keterangan umum
$routes->post('pencaker/save_data_keterangan_umum', 'Pencaker::save_data_keterangan_umum', ['filter' => 'role:pencaker']);
$routes->get('pencaker/get_data_keterangan_umum/(:num)', 'Pencaker::get_data_keterangan_umum/$1');

// data pendidikan
$routes->post('pencaker/save_data_pendidikan', 'Pencaker::save_data_pendidikan', ['filter' => 'role:pencaker']);
$routes->get('pencaker/save_data_pendidikan/(:num)', 'Pencaker::save_data_pendidikan/$1');
$routes->post('pencaker/fetch_data_pendidikan', 'Pencaker::fetch_data_pendidikan');
$routes->post('pencaker/hapus_data_pendidikan', 'Pencaker::hapus_data_pendidikan', ['filter' => 'role:pencaker']);
$routes->get('pencaker/update_data_pendidikan', 'Pencaker::update_data_pendidikan', ['filter' => 'role:pencaker']);
$routes->post('pencaker/update_data_pendidikan', 'Pencaker::update_data_pendidikan', ['filter' => 'role:pencaker']);
$routes->post('pencaker/get_pendidikan_by_id', 'Pencaker::get_pendidikan_by_id', ['filter' => 'role:pencaker']);

$routes->get('pencaker/getPencakerData', 'Pencaker::getPencakerData', ['filter' => 'role:pencaker']);


$routes->post('pencaker/save_data_bahasa', 'Pencaker::save_data_bahasa', ['filter' => 'role:pencaker']);
$routes->get('pencaker/save_data_bahasa', 'Pencaker::save_data_bahasa', ['filter' => 'role:pencaker']);

// Data Pengalaman Kerja
$routes->post('pencaker/save_data_pengalaman_kerja', 'Pencaker::save_data_pengalaman_kerja', ['filter' => 'role:pencaker']);
$routes->get('pencaker/save_data_pengalaman_kerja/(:num)', 'Pencaker::save_data_pengalaman_kerja/$1');
$routes->post('pencaker/fetch_data_pengalaman_kerja', 'Pencaker::fetch_data_pengalaman_kerja');
$routes->post('pencaker/hapus_data_pengalaman_kerja', 'Pencaker::hapus_data_pengalaman_kerja', ['filter' => 'role:pencaker']);
$routes->get('pencaker/update_data_pengalaman_kerja', 'Pencaker::update_data_pengalaman_kerja', ['filter' => 'role:pencaker']);
$routes->post('pencaker/update_data_pengalaman_kerja', 'Pencaker::update_data_pengalaman_kerja', ['filter' => 'role:pencaker']);
$routes->post('pencaker/get_pengalaman_kerja_by_id', 'Pencaker::get_pengalaman_kerja_by_id', ['filter' => 'role:pencaker']);

// Minat Jabatan
$routes->post('pencaker/save_data_minat_jabatan', 'Pencaker::save_data_minat_jabatan', ['filter' => 'role:pencaker']);
$routes->get('pencaker/get_data_minat_jabatan/(:num)', 'Pencaker::get_data_minat_jabatan/$1');

// Perusahaan Tujuan
$routes->post('pencaker/save_data_perusahaan_tujuan', 'Pencaker::save_data_perusahaan_tujuan', ['filter' => 'role:pencaker']);
$routes->get('pencaker/save_data_perusahaan_tujuan/(:num)', 'Pencaker::save_data_perusahaan_tujuan/$1');
$routes->post('pencaker/get_perusahaan_tujuan_by_id', 'Pencaker::get_perusahaan_tujuan_by_id', ['filter' => 'role:pencaker']);
$routes->get('pencaker/get_perusahaan_tujuan_by_id/(:num)', 'Pencaker::get_perusahaan_tujuan_by_id/$1', ['filter' => 'role:pencaker']);

// Catatan tambahan
$routes->post('pencaker/save_catatan_pengantar', 'Pencaker::save_catatan_pengantar', ['filter' => 'role:pencaker']);
$routes->get('pencaker/save_catatan_pengantar/(:num)', 'Pencaker::save_catatan_pengantar/$1');
$routes->post('pencaker/get_catatan_pengantar_by_id', 'Pencaker::get_catatan_pengantar_by_id', ['filter' => 'role:pencaker']);
$routes->get('pencaker/get_catatan_pengantar_by_id/(:num)', 'Pencaker::get_catatan_pengantar_by_id/$1', ['filter' => 'role:pencaker']);


// Bahasa
$routes->post('pencaker/save_bahasa', 'Pencaker::save_bahasa');
$routes->get('pencaker/get_bahasa_pencaker_by_id/(:num)', 'Pencaker::get_bahasa_pencaker_by_id/$1');

// Sidebar pengaturan
$routes->get('pencaker/pengaturan', 'Pencaker::pengaturan', ['filter' => 'role:pencaker']);

$routes->get('kartu/(:num)', 'Frontend::kartu/$1');

$routes->post('pencaker/minta_verifikasi', 'Pencaker::minta_verifikasi');
$routes->get('pencaker/minta_verifikasi', 'Pencaker::minta_verifikasi');
$routes->post('pencaker/lapor_pencari_kerja', 'Pencaker::lapor_pencari_kerja');
$routes->get('pencaker/get_lapor_pencaker', 'Pencaker::get_lapor_pencaker');
// $routes->get('pencaker/detail_lapor_kerja', 'Pencaker::detail_lapor_kerja');
$routes->get('pencaker/detail_lapor_kerja/(:num)', 'Pencaker::detail_lapor_kerja/$1');
$routes->get('pencaker/check_usia_laporan', 'Pencaker::check_usia_laporan');
