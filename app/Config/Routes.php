<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//route untuk frontend
$routes->get('/', 'Frontend::index');
$routes->get('profil', 'Frontend::profil');
$routes->get('urusan_transmigrasi', 'Frontend::transmigrasi');
$routes->get('urusan_tenaga_kerja', 'Frontend::tenaga_kerja');
$routes->get('berita', 'Frontend::berita');
$routes->get('pengumuman', 'Frontend::pengumuman');
$routes->get('pelatihan', 'Frontend::pelatihan');
$routes->get('kartu_ak1', 'Frontend::kartu_ak1');
$routes->get('kontak', 'Frontend::kontak');
//proses registrasi akun bagi pencaker
$routes->get('/registrasi_pencaker', 'Frontend::registrasi_pencaker');
$routes->post('/registrasi_pencaker', 'Frontend::registrasi_pencaker');
$routes->post('/frontend/save_pencaker_data', 'Frontend::save_pencaker_data');
$routes->get('/masuk', 'Frontend::login');



// Routes untuk backend
$routes->get('admin/dashboard', 'Admin::index');
$routes->get('admin/pencaker', 'Admin::pencaker');

// Routes Berita
$routes->get('admin/berita', 'Admin::berita');
$routes->get('admin/beritaajax', 'Admin::beritaajax');
$routes->post('admin/beritaajax', 'Admin::beritaajax');
$routes->post('admin/save_berita', 'Admin::save_berita');
$routes->post('admin/update_berita', 'Admin::update_berita');
$routes->post('admin/update_status_berita', 'Admin::update_status_berita');
$routes->post('admin/hapus_berita', 'Admin::hapus_berita');

// Routes Pengumuman
$routes->get('admin/pengumuman', 'Admin::pengumuman');
$routes->get('admin/pengumumanajax', 'Admin::pengumumanajax');
$routes->post('admin/pengumumanajax', 'Admin::pengumumanajax');
$routes->post('admin/save_pengumuman', 'Admin::save_pengumuman');
$routes->post('admin/update_pengumuman', 'Admin::update_pengumuman');
$routes->post('admin/update_status_pengumuman', 'Admin::update_status_pengumuman');
$routes->post('admin/hapus_pengumuman', 'Admin::hapus_pengumuman');

// Routes Pelatihan
$routes->get('admin/pelatihan', 'Admin::pelatihan');
$routes->get('admin/pelatihanajax', 'Admin::pelatihanajax');
$routes->post('admin/pelatihanajax', 'Admin::pelatihanajax');
$routes->post('admin/save_pelatihan', 'Admin::save_pelatihan');
$routes->post('admin/update_pelatihan', 'Admin::update_pelatihan');
$routes->post('admin/update_status_pelatihan', 'Admin::update_status_pelatihan');
$routes->post('admin/hapus_pelatihan', 'Admin::hapus_pelatihan');


$routes->get('admin/userslog', 'Admin::userslog');
$routes->get('admin/users', 'Admin::users');
$routes->get('admin/settings', 'Admin::settings');
$routes->get('admin/backup', 'Admin::backup');
