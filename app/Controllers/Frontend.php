<?php

namespace App\Controllers;

use App\Models\FrontendModel;
use App\Models\PencakerModel;
use App\Models\PendidikanModel;
use App\Models\SettingsModel;
use App\Models\PelatihanModel;
use App\Models\GaleriModel;
use App\Helpers\QrCodeHelper;

class Frontend extends BaseController
{
    public function index(): string
    {
        $pencakerModel = new PencakerModel();
        $pendidikanModel = new PendidikanModel();
        $frontendModel = new FrontendModel();

        $sliderData = $frontendModel->getSliderData();
        $q_umur = $pencakerModel->getUmurStatistik();
        $q_pendidikan_terakhir = $pendidikanModel->getPendidikanStatistik();
        $pencaker_count = $pencakerModel->countPencaker();

        // Prepare data for view
        $data['c_umur'] = $q_umur;
        $data['c_pendidikan_terakhir'] = $q_pendidikan_terakhir;
        $data['max_umur'] = $pencaker_count;
        $data['sliders'] = $this->getSliders();
        $data['galleries'] = $this->getHomeGalleries(); // Menambahkan galeri

        $data['title'] = 'Beranda - Disnakertrans Manokwari';
        $data['sliderData'] = $sliderData;
        return $this->loadView('frontend/home', $data);
    }

    public function getHomeGalleries()
    {
        $galeriModel = new GaleriModel();
        $galleries = $galeriModel->getGalleriesForHome();

        // Mengelompokkan gambar berdasarkan kategori
        $groupedGalleries = [];
        foreach ($galleries as $galeri) {
            $category = $galeri['kategori'];
            $groupedGalleries[$category][] = [
                'url' => base_url('uploads/galeri/' . $galeri['gambar']),
                'name' => $galeri['deskripsi'],
            ];
        }

        return $groupedGalleries;
    }

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
        $data['galleries'] = $this->getTransmigrasiGalleries(); // Menambahkan galeri
        $data['title'] = 'Urusan Transmigrasi - Disnakertrans Manokwari';
        return $this->loadView('frontend/transmigrasi', $data);
    }

    public function getTransmigrasiGalleries()
    {
        $galeriModel = new GaleriModel();
        $galleries = $galeriModel->getGalleriesForTransmigrasi();

        // Mengelompokkan gambar berdasarkan kategori
        $groupedGalleries = [];
        foreach ($galleries as $galeri) {
            $category = $galeri['kategori'];
            $groupedGalleries[$category][] = [
                'url' => base_url('uploads/galeri/' . $galeri['gambar']),
                'name' => $galeri['deskripsi'],
            ];
        }

        return $groupedGalleries;
    }

    public function tenaga_kerja(): string
    {
        $data['galleries'] = $this->getTenagakerjaGalleries();
        $data['title'] = 'Urusan Tenaga Kerja - Disnakertrans Manokwari';
        return $this->loadView('frontend/tenaga_kerja', $data);
    }

    private function getTenagakerjaGalleries(): array
    {
        $galeriModel = new GaleriModel();
        $galleries = $galeriModel->getGalleriesForTenagakerja();

        // Mengelompokkan gambar berdasarkan kategori
        $groupedGalleries = [];
        foreach ($galleries as $galeri) {
            $category = $galeri['kategori'];
            $groupedGalleries[$category][] = [
                'url' => base_url('uploads/galeri/' . $galeri['gambar']),
                'name' => $galeri['deskripsi'],
            ];
        }

        return $groupedGalleries;
    }

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

    public function detail_berita($slug)
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

    public function tag_berita($tag)
    {
        $informasiModel = new FrontendModel();

        $data['title'] = 'tag: ' . $tag;

        // Mengambil semua informasi berdasarkan tag
        $data['informasi'] = $informasiModel->getInformasiByTag($tag);

        // Mengambil daftar tag unik
        $tagsArray = [];
        foreach ($data['informasi'] as $info) {
            $tagsArray = array_merge($tagsArray, explode(',', $info['tags']));
        }
        $data['uniqueTags'] = array_unique($tagsArray);

        // Mengambil berita terbaru
        $kategori = 'berita';
        $data['recentPosts'] = $informasiModel->getRecentPostsByKategori($kategori);

        return $this->loadView('frontend/tag_berita', $data);
    }

    public function tag_pengumuman($tag)
    {
        $informasiModel = new FrontendModel();

        $data['title'] = 'tag: ' . $tag;

        // Mengambil semua informasi berdasarkan tag
        $data['informasi'] = $informasiModel->getInformasiByTag($tag);

        // Mengambil daftar tag unik
        $tagsArray = [];
        foreach ($data['informasi'] as $info) {
            $tagsArray = array_merge($tagsArray, explode(',', $info['tags']));
        }
        $data['uniqueTags'] = array_unique($tagsArray);

        // Mengambil berita terbaru
        $kategori = 'pengumuman';
        $data['recentPosts'] = $informasiModel->getRecentPostsByKategori($kategori);

        return $this->loadView('frontend/tag_pengumuman', $data);
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
