<?php

namespace App\Controllers;

use App\Models\FrontendModel;
use App\Models\PencakerModel;
use App\Models\PendidikanModel;
use App\Models\SettingsModel;
use App\Models\PelatihanModel;
use App\Helpers\QrCodeHelper;

class Frontend extends BaseController
{
    public function index(): string
    {
        $pencakerModel = new PencakerModel();
        $pendidikanModel = new PendidikanModel();
        $frontendModel = new FrontendModel();

        $sliderData =  $frontendModel->getSliderData();
        $q_umur = $pencakerModel->getUmurStatistik();

        $q_pendidikan_terakhir = $pendidikanModel->getPendidikanStatistik();

        $pencaker_count = $pencakerModel->countPencaker();
        // Prepare data for view
        $data['c_umur'] = $q_umur;
        $data['c_pendidikan_terakhir'] = $q_pendidikan_terakhir;
        $data['max_umur'] = $pencaker_count;

        $data['sliders'] = $this->getSliders();
        $data['galleries'] = $this->getHomeGalleries();
        $data['title'] = 'Beranda - Disnakertrans Manokwari';
        $data['sliderData'] = $sliderData;
        return $this->loadView('frontend/home', $data);
    }

    // Ambil data galeri dinamis dari folder uploads
    private function getHomeGalleries(): array
    {
        $path = WRITEPATH . '../public/uploads/tenagakerja/';
        $directories = array_filter(glob($path . '*'), 'is_dir');
        $galleries = [];

        foreach ($directories as $directory) {
            $category = basename($directory);
            $images = array_filter(glob($directory . '/*'), 'is_file');

            foreach ($images as $image) {
                $galleries[$category][] = [
                    'url' => base_url('uploads/tenagakerja/' . $category . '/' . basename($image)),
                    'name' => pathinfo($image, PATHINFO_FILENAME)
                ];
            }
        }

        return $galleries;
    }

    // Dinamiskan SLider
    private function getSliders(): array
    {
        $path = WRITEPATH . '../public/uploads/sliders/';
        $images = array_filter(glob($path . '*'), 'is_file');
        $sliders = [];

        foreach ($images as $image) {
            $sliders[] = [
                'url' => base_url('uploads/sliders/' . basename($image)),
                'name' => pathinfo($image, PATHINFO_FILENAME)
            ];
        }

        return $sliders;
    }


    public function profil(): string
    {
        $data['title'] = 'Profil - Disnakertrans Manokwari';

        return $this->loadView('frontend/profil', $data);
    }

    public function transmigrasi(): string
    {
        $data['galleries'] = $this->getTransmigrasiGalleries();
        $data['title'] = 'Urusan Transmigrasi - Disnakertrans Manokwari';
        return $this->loadView('frontend/transmigrasi', $data);
    }

    private function getTransmigrasiGalleries(): array
    {
        $path = WRITEPATH . '../public/uploads/transmigrasi/';
        $directories = array_filter(glob($path . '*'), 'is_dir');
        $galleries = [];

        foreach ($directories as $directory) {
            $category = basename($directory);
            $images = array_filter(glob($directory . '/*'), 'is_file');

            foreach ($images as $image) {
                $galleries[$category][] = [
                    'url' => base_url('uploads/transmigrasi/' . $category . '/' . basename($image)),
                    'name' => pathinfo($image, PATHINFO_FILENAME)
                ];
            }
        }

        return $galleries;
    }

    public function tenaga_kerja(): string
    {
        $data['galleries'] = $this->getTenagakerjaGalleries();
        $data['title'] = 'Urusan Tenaga Kerja - Disnakertrans Manokwari';
        return $this->loadView('frontend/tenaga_kerja', $data);
    }

    private function getTenagakerjaGalleries(): array
    {
        $path = WRITEPATH . '../public/uploads/tenagakerja/';
        $directories = array_filter(glob($path . '*'), 'is_dir');
        $galleries = [];

        foreach ($directories as $directory) {
            $category = basename($directory);
            $images = array_filter(glob($directory . '/*'), 'is_file');

            foreach ($images as $image) {
                $galleries[$category][] = [
                    'url' => base_url('uploads/tenagakerja/' . $category . '/' . basename($image)),
                    'name' => pathinfo($image, PATHINFO_FILENAME)
                ];
            }
        }

        return $galleries;
    }

    // public function berita(): string
    // {
    //     $informasiModel = new FrontendModel();

    //     // Pagination configuration
    //     $perPage = 3;
    //     $currentPage = $this->request->getVar('page') ? (int)$this->request->getVar('page') : 1;

    //     // Ambil data informasi berita
    //     $kategori = 'Berita'; // Kategori yang ingin ditampilkan
    //     $totalRows = $informasiModel->countInformasiByKategori($kategori);

    //     // Pagination setup
    //     $data['informasi'] = $informasiModel->getInformasiByKategori($kategori, $perPage, ($currentPage - 1) * $perPage);
    //     $data['kategori'] = $informasiModel->getKategoriCount();

    //     // Ambil berita terbaru berdasarkan kategori
    //     $data['recentPosts'] = $informasiModel->getRecentPostsByKategori($kategori);

    //     // Ambil daftar tags dari semua informasi
    //     $tagsArray = [];
    //     foreach ($data['informasi'] as $info) {
    //         $tagsArray = array_merge($tagsArray, explode(',', $info['tags']));
    //     }
    //     $data['uniqueTags'] = array_unique($tagsArray);

    //     $data['title'] = 'Pengumuman- Disnakertrans Manokwari';

    //     // Set pager jika ada data
    //     if (!empty($data['informasi'])) {
    //         $data['pager'] = $informasiModel->pager;
    //     }
    //     return $this->loadView('frontend/beritama', $data);
    // }

    public function berita(): string
    {
        $informasiModel = new FrontendModel();

        $perPage = 6;
        $currentPage = $this->request->getVar('page') ? (int)$this->request->getVar('page') : 1;

        $kategori = 'berita'; // Kategori yang ingin ditampilkan

        $data['informasi'] = $informasiModel->getInformasiByKategoriFront($kategori, $perPage, $currentPage);
        $data['kategori'] = $informasiModel->getKategoriCount();

        // Menggunakan metode yang telah diubah untuk mengambil nama user
        $data['recentPosts'] = $informasiModel->getRecentPostsByKategori($kategori);

        $tagsArray = [];
        foreach ($data['informasi'] as $info) {
            $tagsArray = array_merge($tagsArray, explode(',', $info['tags']));
        }
        $data['uniqueTags'] = array_unique($tagsArray);

        $data['title'] = 'Pengumuman - Disnakertrans Manokwari';
        $data['pager'] = $informasiModel->pager;

        return $this->loadView('frontend/beritama', $data);
    }



    public function detail_berita($slug): string
    {
        $informasiModel = new FrontendModel();

        // Ambil data detail berita berdasarkan slug
        $berita = $informasiModel->where('slug', $slug)->first();

        if (!$berita) {
            echo ('Berita tidak ditemukan');
        }

        $data['berita'] = $berita;
        $data['title'] = $berita['judul'] . ' - Disnakertrans Manokwari';

        // Ambil berita terbaru berdasarkan kategori yang sama
        $kategori = 'berita'; // Asumsi kategori 'Berita'
        $data['recentPosts'] = $informasiModel->getRecentPostsByKategori($kategori);

        // Ambil daftar tags dari berita ini
        $tagsArray = explode(',', $berita['tags']);
        $data['uniqueTags'] = array_unique($tagsArray);

        return $this->loadView('frontend/detail_berita', $data);
    }

    public function pengumuman(): string
    {
        $informasiModel = new FrontendModel();

        $perPage = 6;
        $currentPage = $this->request->getVar('page') ? (int)$this->request->getVar('page') : 1;

        $kategori = 'pengumuman'; // Kategori yang ingin ditampilkan

        $data['informasi'] = $informasiModel->getInformasiByKategoriFront($kategori, $perPage, $currentPage);
        $data['kategori'] = $informasiModel->getKategoriCount();

        // Menggunakan metode yang telah diubah untuk mengambil nama user
        $data['recentPosts'] = $informasiModel->getRecentPostsByKategori($kategori);

        $tagsArray = [];
        foreach ($data['informasi'] as $info) {
            $tagsArray = array_merge($tagsArray, explode(',', $info['tags']));
        }
        $data['uniqueTags'] = array_unique($tagsArray);

        $data['title'] = 'Pengumuman - Disnakertrans Manokwari';
        $data['pager'] = $informasiModel->pager;

        return $this->loadView('frontend/pengumuman', $data);
    }

    public function detail_pengumuman($slug): string
    {
        $informasiModel = new FrontendModel();

        $pengumuman = $informasiModel->where('slug', $slug)->first();

        if (!$pengumuman) {
            echo ('pengumuman tidak ditemukan');
        }

        $data['pengumuman'] = $pengumuman;
        $data['title'] = $pengumuman['judul'] . ' - Disnakertrans Manokwari';

        $kategori = 'pengumuman';
        $data['recentPosts'] = $informasiModel->getRecentPostsByKategori($kategori);

        // Ambil daftar tags dari pengumuman ini
        $tagsArray = explode(',', $pengumuman['tags']);
        $data['uniqueTags'] = array_unique($tagsArray);

        return $this->loadView('frontend/detail_pengumuman', $data);
    }

    public function pelatihan(): string
    {
        $pelatihanModel = new PelatihanModel();

        $pelatihan = $pelatihanModel->get_all_pelatihan_by_penulis();


        $data['title'] = 'Pelatihan - Disnakertrans Manokwari';
        $data['pelatihan'] = $pelatihan;

        return $this->loadView('frontend/pelatihan', $data);
    }

    public function detail_pelatihan($slug): string
    {
        $pelatihanModel = new PelatihanModel();

        // Ambil detail pelatihan berdasarkan slug
        $pelatihan = $pelatihanModel->get_pelatihan_by_slug($slug);

        // Ambil pelatihan lain yang memiliki jenis_pelatihan_kode yang sama, kecuali pelatihan yang sedang ditampilkan
        $recentPosts = $pelatihanModel->get_pelatihan_by_jenis($pelatihan['jenis_pelatihan_kode'], $pelatihan['id']);

        $data['pelatihan'] = $pelatihan;
        $data['recentPosts'] = $recentPosts;
        $data['title'] = $pelatihan['judul'] . ' - Disnakertrans Manokwari';

        return $this->loadView('frontend/detail_pelatihan', $data);
    }




    public function kartu_ak1(): string
    {
        $data['title'] = 'Kartu Pencaker - Disnakertrans Manokwari';
        return $this->loadView('frontend/kartu_ak1', $data);
    }

    public function registrasi_pencaker(): string
    {
        $data['title'] = 'Registrasi Pencaker - Disnakertrans Manokwari';
        return $this->loadView('frontend/registrasi_pencaker', $data);
    }

    public function kontak(): string
    {
        $settingsModel = new SettingsModel();
        $webSettings = $settingsModel->whereIn('key', [
            'company_phone1',
            'company_phone2',
            'company_address',
            'company_email',
            'company_whatsapp',
            'maps'
        ])->findAll();

        // Transform the settings into a key-value array
        $settings = [];
        foreach ($webSettings as $setting) {
            $settings[$setting['key']] = $setting['value'];
        }

        $data['title'] = 'Kontak - Disnakertrans Manokwari';
        $data['settings'] = $settings;
        return $this->loadView('frontend/kontak', $data);
    }

    public function login(): string
    {
        $data['title'] = 'Kontak - Disnakertrans Manokwari';
        return $this->loadView('frontend/login', $data);
    }

    public function kartu($id)
    {
        log_message('debug', 'Metode kartu dipanggil dengan ID: ' . $id);
        $model = new PencakerModel();
        $data['pencari_kerja'] = $model->find($id);

        if (empty($data['pencari_kerja'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pencari Kerja tidak ditemukan');
        }

        $url = base_url('pencari_kerja/detail/' . $id);
        $data['qr_code'] = QrCodeHelper::generate($url);

        return view('frontend/kartu', $data); // Pastikan view path sesuai dengan struktur direktori Anda
    }


    private function loadView(string $viewName, array $data = []): string
    {
        $uri = service('uri');
        $segments = $uri->getSegments();

        $data['current_uris'] = [
            'segment_1' => isset($segments[0]) ? $segments[0] : '',
            'segment_2' => isset($segments[1]) ? $segments[1] : '',
            'segment_3' => isset($segments[2]) ? $segments[2] : '',
        ];

        // Handle root URL
        if ($uri->getPath() === '' || $uri->getPath() === '/') {
            $data['current_uris']['segment_1'] = 'home';
        }

        return view($viewName, $data);
    }
}
