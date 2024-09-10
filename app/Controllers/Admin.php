<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FrontendModel;
use App\Models\UsersModel;
use App\Models\PencakerModel;
use App\Models\ActivitylogsModel;
use App\Models\DatabaseModel;
use App\Models\SettingsModel;
use App\Models\VerifikasiModel;
use App\Models\TimelineuserModel;
use App\Models\PelatihanModel;
use App\Models\JenispelatihanModel;
use App\Models\GaleriModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

use App\Libraries\Pdf;

class Admin extends BaseController
{
    public function index()
    {
        $pencakerModel = new PencakerModel();
        $userModel = new UsersModel();
        $pendidikanData = $pencakerModel->countByPendidikan();
        $usiaData = $pencakerModel->countByUsia();

        $currentYear = date('Y');
        $pencakerData = $pencakerModel->getPencakerByGenderAndMonth($currentYear);

        $months = [];
        $lakiData = [];
        $perempuanData = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = $this->bulan($i);
            $lakiData[$i] = 0;
            $perempuanData[$i] = 0;
        }

        foreach ($pencakerData as $data) {
            if ($data->jenkel == 'Laki-laki') {
                $lakiData[$data->bulan] = $data->jumlah;
            } else if ($data->jenkel == 'Perempuan') {
                $perempuanData[$data->bulan] = $data->jumlah;
            }
        }

        // Mengambil user_id menggunakan user()->id
        $users = $userModel->getLatestUsers();
        $verifikasiusers = $pencakerModel->getLatestPencaker();

        $data = [
            'title' => 'Dashboard',
            'registrasi_count' => $pencakerModel->countByStatus('Registrasi'),
            'verifikasi_count' => $pencakerModel->countByStatus('Verifikasi'),
            'validasi_count' => $pencakerModel->countByStatus('Validasi'),
            'aktif_count' => $pencakerModel->countByStatus('Aktif'),
            'bekerja_count' => $pencakerModel->countByStatus('Bekerja'),
            'wajib_lapor_count' => $pencakerModel->countByStatus('Wajib Lapor'),
            'pendidikan_data' => $pencakerModel->countByPendidikan(),
            'usia_data' => $pencakerModel->countByUsia(),

            'pendidikan_data' => json_encode($pendidikanData),
            'usia_data' => json_encode($usiaData),

            'currentYear' => $currentYear,
            'months' => json_encode(array_values($months)),
            'laki_data' => json_encode(array_values($lakiData)),
            'perempuan_data' => json_encode(array_values($perempuanData)),
            'users' => $users,
            'verifikasiusers' => $verifikasiusers,
        ];

        return $this->loadView('admin/dashboard', $data);
    }

    public function redirectDashboard()
    {
        return redirect()->to('admin_v2/dashboard');
    }

    protected function bulan($bulan)
    {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $namaBulan[$bulan] ?? '';
    }

    public function pencaker()
    {
        $data['title'] = 'Manajemen Pencaker';
        return $this->loadView('admin/pencaker', $data);
    }

    public function pencakerajax()
    {
        $pencakerModel = new PencakerModel();
        $filter = $this->request->getPost('filter');

        // Ambil data pencaker dengan join ke tabel users
        $pencaker = $pencakerModel->getPencakerWithUser($filter);

        $data = [];

        foreach ($pencaker as $pc) {
            // Ambil data pas foto untuk pencaker
            $dokpasfoto = $pencakerModel->getpasfotopencaker($pc['id']);

            // Tentukan path gambar default
            $defaultImagePath = base_url('uploads/user/no-user.jpg');
            $gambar = '<img src="' . $defaultImagePath . '" alt="' . $pc['namalengkap'] . '" title="' . $pc['namalengkap'] . '" width="40">';

            // Periksa apakah dokpasfoto ada dan memiliki namadokumen
            if ($dokpasfoto && !empty($dokpasfoto['namadokumen'])) {
                $imagePath = base_url('uploads/dokumen_pencaker/' . $dokpasfoto['nik'] . '/' . $dokpasfoto['namadokumen']);
                if (file_exists(FCPATH . 'uploads/dokumen_pencaker/' . $dokpasfoto['nik'] . '/' . $dokpasfoto['namadokumen'])) {
                    $gambar = '<img src="' . $imagePath . '" alt="' . $pc['namalengkap'] . '" title="' . $pc['namalengkap'] . '" width="40">';
                }
            }

            // Tentukan kondisi tombol berdasarkan keterangan_status
            $vervalDisabled = 'disabled';
            $kartuDisabled = 'disabled-link';
            $detailDisabled = 'disabled-link';
            $deleteDisabled = 'disabled';

            switch ($pc['keterangan_status']) {
                case 'Registrasi':
                    // Semua tombol didisable
                    $vervalDisabled = 'disabled';
                    $kartuDisabled = 'disabled-link';
                    $detailDisabled = 'disabled-link';
                    $deleteDisabled = 'disabled';
                    break;
                case 'Verifikasi':
                case 'Re-Verifikasi':
                case 'Validasi':
                    // Enable Detail dan Hapus, Verifikasi enabled, Kartu AK1 disabled
                    $vervalDisabled = '';
                    $kartuDisabled = 'disabled-link';
                    $detailDisabled = '';
                    $deleteDisabled = '';
                    break;
                case 'Aktif':
                    // Enable semua kecuali Verifikasi yang didisable
                    $vervalDisabled = 'disabled';
                    $kartuDisabled = '';
                    $detailDisabled = '';
                    $deleteDisabled = '';
                    break;
            }

            // Tambahkan tombol ke dalam array $data
            $data[] = [
                "verval" => '<button class="btn btn-secondary btn-sm btn-verval" ' . $vervalDisabled . '
            title="Verifikasi/Validasi Pencaker"
             data-id="' . $pc['id'] . '" 
             data-namalengkap="' . $pc['namalengkap'] . '" 
             data-nopendaftaran="' . $pc['nopendaftaran'] . '" 
             data-toggle="modal" 
             data-target="#VerVal">
             <i class="bi bi-check-circle-fill"></i>
         </button>',
                "img" => $gambar,
                "namalengkap" => $pc['namalengkap'],
                "nopendaftaran" => $pc['nopendaftaran'],
                "nik" => $pc['nik'],
                "nohp" => $pc['nohp'],
                "email" =>  $pc['email'],
                "keterangan_status" => $pc['keterangan_status'],
                "aksi" => '<div class="btn-group" role="group" aria-label="Actions">
               <a href="' . base_url('admin_v2/detail_pencaker/' . $pc['id']) . '" target="_blank" class="btn btn-info btn-sm ' . $detailDisabled . '" title="Detail Pencaker">
                   <i class="bi bi-search"></i>
               </a>
               <a href="' . base_url('admin_v2/kartu_ak1/' . $pc['id']) . '" target="_blank" class="btn btn-success btn-sm ' . $kartuDisabled . '" title="Kartu AK/1">
                   <i class="bi bi-person-vcard-fill"></i>
               </a>
               <button class="btn btn-danger btn-sm btn-delete" data-id="' . $pc['id'] . '" ' . $deleteDisabled . ' title="Hapus Pencaker">
                   <i class="bi bi-trash"></i>
               </button>
           </div>'
            ];
        }

        echo json_encode(["data" => $data]);
    }


    public function detail_pencaker($id_pencaker)
    {
        $pencakerModel = new PencakerModel();
        $pencaker = $pencakerModel->find($id_pencaker);
        $pendidikan = $pencakerModel->getpendidikanpencaker($id_pencaker);
        $pengalaman = $pencakerModel->getpengalamankerjapencaker($id_pencaker);
        $alldokumen = $pencakerModel->getdokumenpencaker($id_pencaker);
        $dokpasfoto = $pencakerModel->getpasfotopencaker($id_pencaker);

        $users = new UsersModel();
        $user = $users->find($id_pencaker);

        $data = [
            'title' => 'Review Data dan Dokumen Pencari Kerja',
            'pencaker' => $pencaker,
            'user' => $user,
            'pendidikan' => $pendidikan,
            'pengalaman' => $pengalaman,
            'alldokumen' => $alldokumen,
            'dokpasfoto' => $dokpasfoto,
        ];

        // return view('admin/review_pencaker', $data);
        return $this->loadView('admin/review_pencaker', $data);
    }

    public function kartu_ak1($id_pencaker)
    {
        $pencakerModel = new PencakerModel();
        $pencaker = $pencakerModel->find($id_pencaker);
        $pendidikan = $pencakerModel->getpendidikanpencaker($id_pencaker);
        $pengalaman = $pencakerModel->getpengalamankerjapencaker($id_pencaker);
        $alldokumen = $pencakerModel->getdokumenpencaker($id_pencaker);
        $dokpasfoto = $pencakerModel->getpasfotopencaker($id_pencaker);
        $data = [
            'title' => 'Kartu AK/1',
            'pencaker' => $pencaker,
            'pendidikan' => $pendidikan,
            'pengalaman' => $pengalaman,
            'alldokumen' => $alldokumen,
            'dokpasfoto' => $dokpasfoto
        ];

        return view('admin/kartu_ak1', $data);
    }

    public function update_status_pencaker()
    {
        $id = $this->request->getJSON()->id;
        $status = $this->request->getJSON()->status;

        $model = new PencakerModel();
        $update = $model->update($id, ['status' => $status]);

        if ($update) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }

    public function hapus_pencaker()
    {
        $pencakerID = $this->request->getPost('pencakerID'); // Pastikan nama parameter sesuai dengan data yang dikirimkan dari AJAX
        $model = new PencakerModel();

        // Lakukan penghapusan dengan klausa where
        $deleted = $model->delete($pencakerID);

        if ($deleted) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Pencaker berhasil dihapus'])->setStatusCode(200);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus pencaker'])->setStatusCode(500);
        }
    }

    public function saveVerifikasi()
    {
        $pencakerModel = new PencakerModel();
        $verifikasiModel = new VerifikasiModel();
        $timelineModel = new TimelineuserModel(); // Tambahkan model timeline_user

        $id = $this->request->getPost('id');
        $statusverifikasi = $this->request->getPost('statusverifikasi');
        $pesan = $this->request->getPost('pesan');
        $usersid = $this->request->getPost('usersid');

        // Tentukan status keterangan berdasarkan opsi yang dipilih
        switch ($statusverifikasi) {
            case 'ver_tidaklengkap':
                $keterangan_status = 'Re-Verifikasi';
                break;
            case 'ver_lengkap':
                $keterangan_status = 'Validasi';
                break;
            case 'ver_valid':
                $keterangan_status = 'Aktif';
                break;
            default:
                $keterangan_status = '';
        }

        // Update keterangan_status di tabel pencaker
        $pencakerModel->update($id, ['keterangan_status' => $keterangan_status]);

        // Periksa apakah data verifikasi sudah ada untuk user ini
        $existingVerifikasi = $verifikasiModel->where('users_id', $usersid)->first();

        if ($existingVerifikasi) {
            // Update data verifikasi yang ada
            $verifikasiData = [
                'tglwaktu' => date('Y-m-d H:i:s'),
                'pesan' => $pesan,
                'status_pesan' => $keterangan_status
            ];
            $verifikasiModel->update($existingVerifikasi['id'], $verifikasiData);
        } else {
            // Insert data verifikasi baru
            $verifikasiData = [
                'tglwaktu' => date('Y-m-d H:i:s'),
                'pesan' => $pesan,
                'users_id' => $usersid,
                'status_pesan' => $keterangan_status
            ];
            $verifikasiModel->insert($verifikasiData);
        }

        // Kirim data ke tabel timeline_user jika statusnya "Telah Diverifikasi" atau "ver_lengkap"
        if ($statusverifikasi == 'ver_lengkap') {
            $timelineData = [
                'timeline_id' => 5,
                'description' => 'Silahkan datang ke kantor Disnakertrans Kab. Manokwari untuk mengambil Kartu Pencari Kerja (Kartu Kuning) dengan menunjukkan dokumen asli yang sebelumnya telah anda unggah di sistem disnakertransmkw.com',
                'tglwaktu' => date('Y-m-d H:i:s'), // Waktu saat data diubah
                'users_id' => $usersid
            ];
            $timelineModel->insert($timelineData);
        }

        if ($statusverifikasi == 'ver_valid') {
            $timelineData = [
                'timeline_id' => 6,
                'description' => 'Anda telah resmi terdaftar sebagai Pencari Kerja (Aktif) di Disnakertrans Manokwari. Kami mohon untuk kembali melapor setiap 6 (enam) bulan sekali melalui panel Pencaker pada website disnakertransmkw.com.',
                'tglwaktu' => date('Y-m-d H:i:s'), // Waktu saat data diubah
                'users_id' => $usersid
            ];
            $timelineModel->insert($timelineData);
        }

        return $this->response->setJSON(['success' => true]);
    }

    public function berita()
    {
        $data['title'] = 'Manajemen Berita';
        return $this->loadView('admin/berita', $data);
    }

    public function beritaajax()
    {
        $beritaModel = new FrontendModel();
        $berita = $beritaModel->getInformasiByKategori('berita', 10, 0);

        $data = [];
        $no = 1;
        foreach ($berita as $item) {
            $gambar = '<img src="' . base_url('uploads/berita/' . esc($item['gambar'])) . '" alt="' . esc($item['judul']) . '" width="100">';
            $gambar = htmlspecialchars_decode($gambar);

            $data[] = [
                "no" => $no++,
                "judul" => $item['judul'],
                "isi" => substr(strip_tags($item['isi']), 0, 250) . ' ...',
                "gambar" => $gambar,
                "status" => '<input type="checkbox" class="js-switch" data-id="' . $item['id'] . '" ' . ($item['status'] ? 'checked' : '') . '>',
                "aksi" =>
                '<div class="btn-group" role="group" aria-label="Aksi">
                        <a href="' . base_url('berita/detail_berita/' . $item['slug']) . '" target="_blank" class="btn btn-info btn-sm" title="Detail Berita">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button class="btn btn-primary btn-sm btn-edit" data-edit_id="' . $item['id'] . '"  data-edit_judul="' . htmlspecialchars($item['judul']) . '" data-edit_isi="' . htmlspecialchars($item['isi']) . '" data-edit_tags="' . $item['tags'] . '" data-edit_gambar="' . $item['gambar'] . '" data-toggle="modal"  data-toggle="modal" data-target="#ubahBeritaBaruModal" title="Ubah Berita" > <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="' . $item['id'] . '"  data-judul="' . $item['judul'] . '" title="Hapus Berita" >
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>'
            ];
        }

        echo json_encode(["data" => $data]);
    }

    public function update_status_berita()
    {
        $id = $this->request->getJSON()->id;
        $status = $this->request->getJSON()->status;

        $model = new FrontendModel();
        $update = $model->update($id, ['status' => $status]);

        if ($update) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }

    public function save_berita()
    {

        $validation = \Config\Services::validation();
        $validation->setRules([
            'kategori' => 'required|max_length[255]',
            'file' => 'uploaded[file]|is_image[file]',
            'judul' => 'required',
            'isi' => 'required',
            'tags' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
        }

        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/berita/', $newName);

            // Jika berhasil pindah, simpan ke database
            $categoryModel = new FrontendModel();
            $categoryModel->save([
                'kategori' => $this->request->getPost('kategori'),
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'tags' => $this->request->getPost('tags'),
                'tgl_publikasi' => date('Y-m-d H:i:s'),
                'gambar' => $newName,
                'status' => $this->request->getPost('status'),
                'slug' => url_title($this->request->getPost('judul'), '-', true),
                'users_id' => user()->id,
            ]);

            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'errors' => 'File upload failed.']);
        }
    }

    public function update_berita()
    {
        $model = new FrontendModel();

        $id = $this->request->getPost('id');
        $judul = $this->request->getPost('judul');
        $isi = $this->request->getPost('isi');
        $tags = $this->request->getPost('tags');
        $kategori = 'berita';
        $status = 1;
        $users_id = user()->id;

        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
            'isi' => 'required',
            'tags' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
        }

        $data = [
            'judul' => $judul,
            'isi' => $isi,
            'tags' => $tags,
            'kategori' => $kategori,
            'status' => $status,
            'users_id' => $users_id,
        ];

        $file = $this->request->getFile('file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Ambil data gambar lama
            $current_data = $model->find($id);
            $current_gambar = $current_data['gambar'];

            // Hapus gambar lama jika ada
            if (!empty($current_gambar)) {
                $path = FCPATH . 'uploads/berita/' . $current_gambar;
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Proses upload gambar baru
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/berita/', $newName);
            $data['gambar'] = $newName;
        }

        $model->update($id, $data);

        return $this->response->setJSON(['success' => true]);
    }

    public function hapus_berita()
    {
        $id = $this->request->getPost('id');
        $model = new FrontendModel();

        $berita = $model->find($id);
        if (!$berita) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Berita tidak ditemukan'])->setStatusCode(404);
        }

        // Hapus gambar dari direktori jika ada
        $gambar = $berita['gambar'];
        if (!empty($gambar)) {
            $path = FCPATH . 'uploads/berita/' . $gambar;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        if ($model->delete($id)) {
            $activityModel = new ActivitylogsModel();
            $activityMessage = sprintf(
                'User #%d menghapus berita dengan judul: "%s"',
                user_id(),
                $this->request->getPost('judul')
            );
            $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());

            return $this->response->setJSON(['status' => 'success'])->setStatusCode(200);
        } else {
            return $this->response->setJSON(['status' => 'error'])->setStatusCode(500);
        }
    }

    public function slider()
    {
        $usersModel = new UsersModel();
        $userId = user()->id;

        // Ambil data pengguna berdasarkan user ID
        $user = $usersModel->find($userId);

        $data = [
            'title' => 'Manajemen Slider',
            'user' => $user,
        ];

        return $this->loadView('admin/slider', $data);
    }

    public function sliderajax()
    {
        $sliderModel = new FrontendModel();
        $slider = $sliderModel->getInformasiByKategori('slider', 10, 0);

        $data = [];
        $no = 1;
        foreach ($slider as $item) {
            $gambar = '<img src="' . base_url('uploads/sliders/' . esc($item['gambar'])) . '" alt="' . esc($item['judul']) . '" width="100">';
            $gambar = htmlspecialchars_decode($gambar);

            $data[] = [
                "no" => $no++,
                "gambar" => $gambar,
                "judul" => $item['judul'],
                "status" => '<div class="text-center"><input type="checkbox" class="js-switch" data-id="' . $item['id'] . '" ' . ($item['status'] ? 'checked' : '') . '></div>',
                "aksi" =>
                '<div class="btn-group" role="group" aria-label="Aksi">
                        <button class="btn btn-primary btn-sm btn-edit" data-edit_id="' . $item['id'] . '"  data-edit_judul="' . htmlspecialchars($item['judul']) . '"  data-edit_gambar="' . $item['gambar'] . '" data-toggle="modal"  data-toggle="modal" data-target="#ubahSliderBaruModal" title="Ubah Slider"> <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="' . $item['id'] . '"  data-judul="' . $item['judul'] . '" title="Hapus Slider">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>'
            ];
        }

        echo json_encode(["data" => $data]);
    }

    public function update_status_slider()
    {
        $id = $this->request->getJSON()->id;
        $status = $this->request->getJSON()->status;

        $model = new FrontendModel();
        $update = $model->update($id, ['status' => $status]);

        if ($update) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }

    public function save_slider()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kategori' => 'required|max_length[255]',
            'file' => 'uploaded[file]|is_image[file]',
            'judul' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
        }

        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/sliders/', $newName);

            // Jika berhasil pindah, simpan ke database
            $categoryModel = new FrontendModel();
            $categoryModel->save([
                'kategori' => $this->request->getPost('kategori'),
                'judul' => $this->request->getPost('judul'),
                'isi' => '-',
                'tags' => '-',
                'tgl_publikasi' => date('Y-m-d H:i:s'), // Tanggal saat ini
                'gambar' => $newName,
                'status' => 1,
                'slug' => '-',
                'users_id' => user()->id,
            ]);

            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'errors' => 'File upload failed.']);
        }
    }

    public function update_slider()
    {
        $model = new FrontendModel();

        $id = $this->request->getPost('id');
        $judul = $this->request->getPost('judul');
        $kategori = 'slider';
        $status = 1;
        $users_id = user()->id;

        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
        }

        $data = [
            'judul' => $judul,
            'kategori' => $kategori,
            'status' => $status,
            'users_id' => $users_id,
        ];

        $file = $this->request->getFile('file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Ambil data gambar lama
            $current_data = $model->find($id);
            $current_gambar = $current_data['gambar'];

            // Hapus gambar lama jika ada
            if (!empty($current_gambar)) {
                $path = FCPATH . 'uploads/sliders/' . $current_gambar;
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Proses upload gambar baru
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/sliders/', $newName);
            $data['gambar'] = $newName;
        }

        $model->update($id, $data);

        return $this->response->setJSON(['success' => true]);
    }

    public function hapus_slider()
    {
        $id = $this->request->getPost('id');
        // $judul = $this->request->getPost('judul');
        $model = new FrontendModel();

        $slider = $model->find($id);
        if (!$slider) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Berita tidak ditemukan'])->setStatusCode(404);
        }

        // Hapus gambar dari direktori jika ada
        $gambar = $slider['gambar'];
        if (!empty($gambar)) {
            $path = FCPATH . 'uploads/sliders/' . $gambar;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        if ($model->delete($id)) {
            $activityModel = new ActivitylogsModel();
            $activityMessage = sprintf(
                'User #%d menghapus slider dengan judul: "%s"',
                user_id(),
                $this->request->getPost('judul')
            );
            $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());

            return $this->response->setJSON(['status' => 'success'])->setStatusCode(200);
        } else {
            return $this->response->setJSON(['status' => 'error'])->setStatusCode(500);
        }
    }

    public function pengumuman()
    {
        $data['title'] = 'Manajemen Pengumuman';
        return $this->loadView('admin/pengumuman', $data);
    }

    public function pengumumanajax()
    {
        $pengumumanModel = new FrontendModel();
        $pengumuman = $pengumumanModel->getInformasiByKategori('pengumuman', 10, 0);

        $data = [];
        $no = 1;
        foreach ($pengumuman as $item) {
            $gambar = '<img src="' . base_url('uploads/pengumuman/' . esc($item['gambar'])) . '" alt="' . esc($item['judul']) . '" width="100">';
            $gambar = htmlspecialchars_decode($gambar);

            $data[] = [
                "no" => $no++,
                "judul" => $item['judul'],
                // "isi" => $item['isi'],
                "isi" => substr(strip_tags($item['isi']), 0, 250) . ' ...',
                "gambar" => $gambar,
                "status" => '<input type="checkbox" class="js-switch" data-judul="' . $item['judul'] . '"  data-id="' . $item['id'] . '" ' . ($item['status'] ? 'checked' : '') . '>',
                "aksi" =>
                '<div class="btn-group" role="group" aria-label="Aksi">
                        <a href="' . base_url('pengumuman/detail_pengumuman/' . $item['slug']) . '" class="btn btn-info btn-sm" title="Detail Pengumuman">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button class="btn btn-primary btn-sm btn-edit" data-edit_id="' . $item['id'] . '"  data-edit_judul="' . htmlspecialchars($item['judul']) . '" data-edit_isi="' . htmlspecialchars($item['isi']) . '" data-edit_tags="' . $item['tags'] . '" data-edit_gambar="' . $item['gambar'] . '" data-toggle="modal"  data-toggle="modal" data-target="#ubahPengumumanModal"  title="Ubah Pengumuman"> <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="' . $item['id'] . '" data-judul="' . $item['judul'] . '" title="Hapus Pengumuman">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>'
            ];
        }

        echo json_encode(["data" => $data]);
    }

    public function update_status_pengumuman()
    {
        $id = $this->request->getJSON()->id;
        $status = $this->request->getJSON()->status;

        $model = new FrontendModel();
        $update = $model->update($id, ['status' => $status]);

        if ($update) {
            $activityModel = new ActivitylogsModel();
            $activityMessage = sprintf(
                'User #%d mengubah status pengumuman dengan judul: "%s"',
                user_id(),
                $this->request->getPost('judul')
            );
            $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());

            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }

    public function save_pengumuman()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kategori' => 'required|max_length[255]',
            'file' => 'uploaded[file]|is_image[file]',
            'judul' => 'required',
            'isi' => 'required',
            'tags' => 'required',
            'status' => 'required|in_list[0,1]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
        }

        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/pengumuman/', $newName);

            // Jika berhasil pindah, simpan ke database
            $categoryModel = new FrontendModel();
            $categoryModel->save([
                'kategori' => $this->request->getPost('kategori'),
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'tags' => $this->request->getPost('tags'),
                'tgl_publikasi' => date('Y-m-d H:i:s'), // Tanggal saat ini
                'gambar' => $newName,
                'status' => $this->request->getPost('status'),
                'slug' => url_title($this->request->getPost('judul'), '-', true),
                'users_id' => user()->id,
            ]);

            $activityModel = new ActivitylogsModel();
            $activityMessage = sprintf(
                'User #%d mengunggah pengumuman baru dengan judul: "%s"',
                user_id(),
                $this->request->getPost('judul')
            );
            $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());

            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'errors' => 'File upload failed.']);
        }
    }

    public function update_pengumuman()
    {
        $model = new FrontendModel();

        $id = $this->request->getPost('id');
        $judul = $this->request->getPost('judul');
        $isi = $this->request->getPost('isi');
        $tags = $this->request->getPost('tags');
        $kategori = 'pengumuman';
        $status = 1;
        $users_id = user()->id;

        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
            'isi' => 'required',
            'tags' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
        }

        $data = [
            'judul' => $judul,
            'isi' => $isi,
            'tags' => $tags,
            'kategori' => $kategori,
            'status' => $status,
            'users_id' => $users_id,
        ];

        $file = $this->request->getFile('file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Ambil data gambar lama
            $current_data = $model->find($id);
            $current_gambar = $current_data['gambar'];

            // Hapus gambar lama jika ada
            if (!empty($current_gambar)) {
                $path = FCPATH . 'uploads/pengumuman/' . $current_gambar;
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Proses upload gambar baru
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/pengumuman/', $newName);
            $data['gambar'] = $newName;
        }

        $model->update($id, $data);

        $activityModel = new ActivitylogsModel();
        $activityMessage = sprintf(
            'User #%d memperbarui pengumuman dengan judul: "%s"',
            user_id(),
            $this->request->getPost('judul')
        );
        $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());


        return $this->response->setJSON(['success' => true]);
    }



    public function hapus_pengumuman()
    {
        $id = $this->request->getPost('id');
        // $judul = $this->request->getPost('judul');
        $model = new FrontendModel();

        // Ambil nama file gambar yang akan dihapus
        $pengumuman = $model->find($id);
        $gambar = $pengumuman['gambar'];

        // Hapus gambar dari direktori
        if (!empty($gambar) && file_exists(FCPATH . 'uploads/pengumuman/' . $gambar)) {
            unlink(FCPATH . 'uploads/pengumuman/' . $gambar);
        }

        // Hapus pengumuman dari database
        if ($model->delete($id)) {
            // Rekam aktivitas
            $activityModel = new ActivitylogsModel();
            $activityMessage = sprintf(
                'User #%d menghapus pengumuman dengan judul: "%s"',
                user_id(),
                $this->request->getPost('judul')
            );
            $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());
            return $this->response->setJSON(['status' => 'success'])->setStatusCode(200);
        } else {
            // Gagal menghapus, kembalikan respons JSON error
            return $this->response->setJSON(['status' => 'error'])->setStatusCode(500);
        }
    }

    public function pelatihan()
    {
        $pelatihanModel = new JenispelatihanModel();

        if ($this->request->getMethod() === 'post') {
            $jenisPelatihanKode = $this->request->getPost('jenis_pelatihan_kode');
            $newJenisPelatihan = $this->request->getPost('new_jenis_pelatihan');

            // Jika ada input baru dari field teks
            if ($jenisPelatihanKode === 'lainnya' && !empty($newJenisPelatihan)) {
                $pelatihanModel->insert(['pelatihan' => $newJenisPelatihan]);
            }

            return redirect()->to(current_url())->with('message', 'Jenis pelatihan berhasil ditambahkan.');
        }

        $pelatihan = $pelatihanModel->findAll();

        $data['title'] = 'Manajemen Pelatihan';
        $data['jenis_pelatihan'] = $pelatihan;

        return $this->loadView('admin/pelatihan', $data);
    }

    public function pelatihanajax()
    {
        $pelatihanModel = new PelatihanModel();
        $pelatihan = $pelatihanModel->get_all_pelatihan();

        $data = [];
        $no = 1;
        foreach ($pelatihan as $item) {
            $gambar = '<img src="' . base_url('uploads/pelatihan/' . esc($item['gambar'])) . '" alt="' . esc($item['judul']) . '" width="100">';
            $gambar = htmlspecialchars_decode($gambar);

            $data[] = [
                "no" => $no++,
                "judul" => $item['judul'],
                "deskripsi" => substr(strip_tags($item['deskripsi']), 0, 150) . ' ...',
                "pelatihan" => $item['pelatihan'],
                "gambar" => $gambar,
                "status" => '<input type="checkbox" class="js-switch" data-id="' . $item['id'] . '" ' . ($item['status'] ? 'checked' : '') . '>',
                "aksi" =>
                '<div class="btn-group" role="group" aria-label="Aksi">
                    <a href="' . base_url('pelatihan/detail_pelatihan/' . $item['slug']) . '" class="btn btn-info btn-sm" title="Detail Pelatihan">
                        <i class="bi bi-eye"></i>
                    </a>
                    <button class="btn btn-primary btn-sm btn-edit" 
                        data-edit_id="' . $item['id'] . '"  
                        data-edit_judul="' . htmlspecialchars($item['judul']) . '" 
                        data-edit_deskripsi="' . htmlspecialchars($item['deskripsi']) . '" 
                        data-edit_materi="' . htmlspecialchars($item['materi']) . '" 
                        data-edit_gambar="' . $item['gambar'] . '" 
                        data-edit_kode="' . $item['kode'] . '"
                        data-edit_link="' . $item['link'] . '" 
                        data-edit_tgl_pelatihan="' . $item['tgl_pelatihan'] . '" 
                        data-toggle="modal" 
                        data-target="#ubahPelatihanModal" title="Ubah Pelatihan"> 
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-danger btn-sm btn-delete" data-id="' . $item['id'] . '" data-judul="' . $item['judul'] . '" title="Hapus Pelatihan">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>'
            ];
        }

        echo json_encode(["data" => $data]);
    }

    public function update_status_pelatihan()
    {
        $id = $this->request->getJSON()->id;
        $status = $this->request->getJSON()->status;

        $model = new PelatihanModel();
        $activityModel = new ActivitylogsModel();

        // Ambil pelatihan sebelum update
        $pelatihan = $model->find($id);
        if (!$pelatihan) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pelatihan tidak ditemukan']);
        }

        $oldStatus = $pelatihan['status'];

        // Update status
        $update = $model->update($id, ['status' => $status]);

        if ($update) {
            // Rekam aktivitas
            $activityMessage = sprintf(
                'User #%d mengubah status pelatihan dengan ID: %d dari %d ke %d',
                user_id(),
                $id,
                $oldStatus,
                $status
            );
            $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());

            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }

    public function save_pelatihan()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'file' => 'uploaded[file]|is_image[file]',
            'judul' => 'required',
            'deskripsi' => 'required',
            'tgl_pelatihan' => 'required',
            'materi' => 'required',
            'jenis_pelatihan_kode' => 'required',
            'link' => 'required',
            'status' => 'required|in_list[0,1]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
        }

        // Proses input jenis pelatihan baru jika ada
        $jenisPelatihanKode = $this->request->getPost('jenis_pelatihan_kode');
        if ($jenisPelatihanKode === 'lainnya') {
            $newJenisPelatihan = $this->request->getPost('new_jenis_pelatihan');
            if (!empty($newJenisPelatihan)) {
                // Simpan jenis pelatihan baru ke database
                $jenisPelatihanModel = new JenispelatihanModel();
                $jenisPelatihanModel->insert(['pelatihan' => $newJenisPelatihan]);
                $jenisPelatihanKode = $jenisPelatihanModel->getInsertID(); // Dapatkan kode yang baru diinsert
            } else {
                return $this->response->setJSON(['success' => false, 'errors' => ['new_jenis_pelatihan' => 'Jenis pelatihan baru harus diisi']]);
            }
        }

        // Proses file upload
        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/pelatihan/', $newName);

            // Simpan ke database
            $pelatihan = new PelatihanModel();
            $data = [
                'judul' => $this->request->getPost('judul'),
                'deskripsi' => $this->request->getPost('deskripsi'),
                'materi' => $this->request->getPost('materi'),
                'tgl_pelatihan' => $this->request->getPost('tgl_pelatihan'),
                'tanggal' => date('Y-m-d H:i:s'),
                'jenis_pelatihan_kode' => $jenisPelatihanKode,
                'gambar' => $newName,
                'status' => $this->request->getPost('status'),
                'link' => $this->request->getPost('link'),
                'slug' => url_title($this->request->getPost('judul'), '-', true),
                'users_id' => user()->id,
            ];
            $pelatihan->save($data);

            // Rekam aktivitas
            $activityModel = new ActivitylogsModel();
            $activityMessage = sprintf(
                'User #%d mengunggah pelatihan baru dengan judul: "%s"',
                user_id(),
                $this->request->getPost('judul')
            );
            $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());

            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'errors' => 'File upload failed.']);
        }
    }

    public function get_jenis_pelatihan()
    {
        $model = new JenispelatihanModel();
        $data = $model->findAll();

        return $this->response->setJSON($data);
    }

    public function update_pelatihan()
    {
        $model = new PelatihanModel();
        $activityModel = new ActivitylogsModel();

        $id = $this->request->getPost('id');
        $judul = $this->request->getPost('judul');
        $deskripsi = $this->request->getPost('deskripsi');
        $materi = $this->request->getPost('materi');
        $tgl_pelatihan = $this->request->getPost('tgl_pelatihan');
        $link = $this->request->getPost('link');
        $jenis_pelatihan_kode = $this->request->getPost('jenis_pelatihan_kode');
        $status = 1;
        $users_id = user()->id;

        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
            'deskripsi' => 'required',
            'materi' => 'required',
            'tgl_pelatihan' => 'required',
            'link' => 'required',
            'jenis_pelatihan_kode' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
        }

        // Ambil data lama
        $current_data = $model->find($id);
        $current_judul = $current_data['judul'];
        $current_gambar = $current_data['gambar'];

        $data = [
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'materi' => $materi,
            'tgl_pelatihan' => $tgl_pelatihan,
            'link' => $link,
            'jenis_pelatihan_kode' => $jenis_pelatihan_kode, // Simpan jenis pelatihan
            'status' => $status,
            'users_id' => $users_id,
        ];

        $file = $this->request->getFile('file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Hapus gambar lama jika ada
            if (!empty($current_gambar)) {
                $path = FCPATH . 'uploads/pelatihan/' . $current_gambar;
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Proses upload gambar baru
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/pelatihan/', $newName);
            $data['gambar'] = $newName;
        }

        $model->update($id, $data);

        $activityMessage = sprintf(
            'User #%d memperbarui pelatihan "%s"',
            user_id(),
            $this->request->getPost('judul')
        );
        $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());

        return $this->response->setJSON(['success' => true]);
    }

    public function hapus_pelatihan()
    {
        $id = $this->request->getPost('id');
        $judul = $this->request->getPost('judul');
        $model = new PelatihanModel();
        $activityModel = new ActivitylogsModel(); // Tambahkan ini

        // Ambil nama file gambar yang akan dihapus
        $pelatihan = $model->find($id);
        if (!$pelatihan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pelatihan tidak ditemukan'])->setStatusCode(404);
        }

        $gambar = $pelatihan['gambar'];

        // Hapus gambar dari direktori
        if (!empty($gambar) && file_exists(FCPATH . 'uploads/pelatihan/' . $gambar)) {
            unlink(FCPATH . 'uploads/pelatihan/' . $gambar);
        }

        // Hapus pelatihan dari database
        if ($model->delete($id)) {
            // Rekam aktivitas penghapusan pelatihan
            $activityMessage = "User #" . user()->username . " menghapus pelatihan $judul";
            $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());

            // Berhasil menghapus, kembalikan respons JSON sukses
            return $this->response->setJSON(['status' => 'success'])->setStatusCode(200);
        } else {
            // Gagal menghapus, kembalikan respons JSON error
            return $this->response->setJSON(['status' => 'error'])->setStatusCode(500);
        }
    }

    public function activitylogs()
    {
        $data['title'] = 'Aktivitas Pengguna';
        return $this->loadView('admin/activitylogs', $data);
    }

    public function activitylogsajax()
    {
        $activityModel = new ActivitylogsModel();
        $logs = $activityModel->getActivityLogs();

        $data = [];
        $no = 1;
        foreach ($logs as $item) {
            $data[] = [
                "id" => $no++,
                "title" => $item['title'],
                "user" => $item['user'],
                "ip_address" => $item['ip_address'],
                "updated_at" => $item['updated_at'],
                "aksi" => '<div class="btn-group text-center" role="group" aria-label="Aksi">
                <button class="btn btn-info btn-sm btn-detail-log" 
                       data-userid="' . $item['userid'] . '" 
                       data-ip_address="' . $item['ip_address'] . '" 
                       data-title="' . $item['title'] . '" 
                       data-name="' . $item['name'] . '" 
                       data-user="' . $item['user'] . '" 
                       data-namalengkap="' . $item['namalengkap'] . '" 
                       data-nik="' . $item['nik'] . '" 
                       data-email="' . $item['email'] . '" 
                       data-username="' . $item['username'] . '" 
                       data-nohp="' . $item['nohp'] . '" 
                       data-updated_at="' . $item['updated_at'] . '" 
                       data-toggle="modal" 
                       data-target="#detailLogModal" title="Detail Log" >
                       <i class="bi bi-eye-fill"></i>
                   </button>
            </div>'
            ];
        }

        echo json_encode(["data" => $data]);
    }

    public function getUsersFromLogs()
    {
        $activityModel = new ActivitylogsModel();
        $users = $activityModel->getDistinctUsers();
        return $this->response->setJSON($users);
    }

    public function users()
    {
        $data['title'] = 'Manajemen User';
        return $this->loadView('admin/users', $data);
    }

    public function usersajax()
    {
        $usersModel = new UsersModel();
        $pencakerModel = new PencakerModel();
        $users = $usersModel->ubah_status_user();

        $data = [];
        $no = 1;
        foreach ($users as $user) {
            $dokpasfoto = $pencakerModel->getpasfotopencaker($user->userid);

            $defaultImagePath = base_url('uploads/user/no-user.jpg');
            $gambar = $defaultImagePath;

            // Periksa apakah dokpasfoto ada dan memiliki namadokumen
            if ($dokpasfoto && !empty($dokpasfoto['namadokumen'])) {
                $imagePath = base_url('uploads/dokumen_pencaker/' . $dokpasfoto['nik'] . '/' . $dokpasfoto['namadokumen']);
                if (file_exists(FCPATH . 'uploads/dokumen_pencaker/' . $dokpasfoto['nik'] . '/' . $dokpasfoto['namadokumen'])) {
                    $gambar = $imagePath;
                }
            }

            // Format data lainnya sesuai kebutuhan
            $data[] = [
                "no" => $no++,
                "namalengkap" => $user->namalengkap,
                "email" => $user->email,
                "username" => $user->username,
                "updated_at" => $user->updated_at,
                "name" => $user->name,
                "active" => '<div class="text-center"><input type="checkbox" class="js-switch" data-id="' . $user->userid . '" ' . ($user->active ? 'checked' : '') . '></div>',
                "aksi" => '
                <div class="btn-group text-center" role="group" aria-label="Actions">
                    <button class="btn btn-info btn-sm btn-detail-user" 
                        data-id="' . $user->userid . '"  
                        data-email="' . htmlspecialchars($user->email) . '" 
                        data-namalengkap="' . $user->namalengkap . '" 
                        data-username="' . $user->username . '" 
                        data-nik="' . $user->nik . '" 
                        data-nohp="' . $user->nohp . '" 
                        data-name="' . $user->name . '" 
                        data-updated_at="' . $user->updated_at . '" 
                        data-gambar="' . $gambar . '" 
                        data-toggle="modal" 
                        data-target="#detailUserModal"
                        data-load-logs="true" 
                        title="Detail User">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-danger btn-sm btn-delete" data-id="' . $user->userid . '" title="Hapus User">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>'
            ];
        }

        echo json_encode(["data" => $data]);
    }

    public function update_status_user()
    {
        $id = $this->request->getJSON()->id;
        $active = $this->request->getJSON()->active;

        $model = new UsersModel();
        $update = $model->update($id, ['active' => $active]);

        if ($update) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }

    public function hapus_user()
    {
        $userId = $this->request->getPost('user_id'); // Sesuaikan dengan nama yang dikirimkan dari AJAX
        $model = new UsersModel();

        $user = $model->find($userId);
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User tidak ditemukan'])->setStatusCode(404);
        }

        // Hapus gambar profil dari direktori jika ada
        $imgType = isset($user['img_type']) ? $user['img_type'] : null;
        if (!empty($imgType)) {
            $path = FCPATH . 'uploads/user/' . $imgType;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // Lakukan penghapusan dengan klausa where
        $deleted = $model->where('id', $userId)->delete();

        if ($deleted) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'User berhasil dihapus'])->setStatusCode(200);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus user'])->setStatusCode(500);
        }
    }

    public function ubah_role_user()
    {
        $userId = $this->request->getPost('user_id');
        $groupId = $this->request->getPost('roles');

        // Debugging untuk memeriksa apakah data diterima
        if (!$userId || !$groupId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap.']);
        }

        $usersModel = new UsersModel();

        if ($usersModel->updateUserRole($userId, $groupId)) {
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Update gagal.']);
        }
    }

    public function settings()
    {
        $settingsModel = new SettingsModel();
        $webSettings = $settingsModel->whereIn('key', [
            'smtp_host',
            'smtp_user',
            'company_phone1',
            'company_phone2',
            'company_address',
            'company_name',
            'company_email',
            'company_whatsapp',
            'smtp_pass',
            'smtp_protocol',
            'smtp_port',
            'google_recaptcha_secretkey',
            'google_recaptcha_sitekey',
            'facebook',
            'x',
            'instagram',
            'youtube',
            'maps'
        ])->findAll();

        // Transform the settings into a key-value array
        $settings = [];
        foreach ($webSettings as $setting) {
            $settings[$setting['key']] = $setting['value'];
        }

        $data['title'] = 'Pengaturan';
        $data['settings'] = $settings;

        return $this->loadView('admin/settings', $data);
    }

    public function update_smtp()
    {
        $settingsModel = new SettingsModel();

        $settingsData = [
            'smtp_host' => $this->request->getPost('smtp_host'),
            'smtp_user' => $this->request->getPost('smtp_user'),
            'smtp_pass' => $this->request->getPost('smtp_pass'),
            'smtp_protocol' => $this->request->getPost('smtp_protocol'),
            'smtp_port' => $this->request->getPost('smtp_port'),
        ];

        foreach ($settingsData as $key => $value) {
            $settingsModel->where('key', $key)->set(['value' => $value])->update();
        }

        return redirect()->to('admin_v2/settings')->with('success', 'Pengaturan SMTP berhasil diperbarui');
    }

    public function update_mediasosial()
    {
        $settingsModel = new SettingsModel();

        $settingsData = [
            'facebook' => $this->request->getPost('facebook'),
            'x' => $this->request->getPost('x'),
            'instagram' => $this->request->getPost('instagram'),
            'youtube' => $this->request->getPost('youtube'),
        ];

        foreach ($settingsData as $key => $value) {
            $settingsModel->where('key', $key)->set(['value' => $value])->update();
        }

        return redirect()->to('admin_v2/settings')->with('success', 'Pengaturan berhasil diperbarui');
    }

    public function update_detailinstansi()
    {
        $settingsModel = new SettingsModel();

        $settingsData = [
            'company_phone1' => $this->request->getPost('company_phone1'),
            'company_phone2' => $this->request->getPost('company_phone2'),
            'company_address' => $this->request->getPost('company_address'),
            'company_name' => $this->request->getPost('company_name'),
            'company_email' => $this->request->getPost('company_email'),
            'company_whatsapp' => $this->request->getPost('company_whatsapp'),
            'maps' => $this->request->getPost('maps'),
        ];

        foreach ($settingsData as $key => $value) {
            $settingsModel->where('key', $key)->set(['value' => $value])->update();
        }

        return redirect()->to('admin_v2/settings')->with('success', 'Pengaturan detail instansi berhasil diperbarui');
    }

    public function update_captcha()
    {
        $settingsModel = new SettingsModel();

        $settingsData = [
            'google_recaptcha_secretkey' => $this->request->getPost('google_recaptcha_secretkey'),
            'google_recaptcha_sitekey' => $this->request->getPost('google_recaptcha_sitekey'),
        ];

        foreach ($settingsData as $key => $value) {
            $settingsModel->where('key', $key)->set(['value' => $value])->update();
        }

        return redirect()->to('admin_v2/settings')->with('success', 'Pengaturan Google Recaptcha berhasil diperbarui');
    }

    public function backup()
    {
        $data['title'] = 'Backup Database';
        return $this->loadView('admin/backup', $data);
    }

    public function download_db()
    {
        $model = new DatabaseModel();
        $databaseContent = $model->exportDatabase();

        $dbName = $model->db->getDatabase();
        $filename = $dbName . '.sql';

        return $this->response->download($filename, $databaseContent);
    }

    public function exportPDF()
    {
        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetTitle('DATA PENCARI KERJA');
        $pdf->SetHeaderMargin(20);
        $pdf->SetTopMargin(10);
        $pdf->setFooterMargin(15);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->SetMargins(5, 10, 5, true);

        // Add a page
        $pdf->AddPage('L', 'A4');

        // Load PencakerModel
        $pencakerModel = new PencakerModel();
        $pencari_kerja = $pencakerModel->findAll(); // Ambil semua data pencari kerja

        // Set some content to display
        $html = '<style>
                table {
                    font-size: 7px;
                    margin-left: auto;
                    margin-right: auto;
                }
                tr.center {
                    text-align: center;
                    background-color: #C0C0C0;
                    font-weight: bold;
                }
                div.heading {
                    text-align: center;
                    font-weight: bold;
                    font-size: 10px;
                }
                div.small {
                    font-size: 7px;
                    padding-bottom: 5px;
                }
            </style>
            <div class="heading">DATA PENCARI KERJA KABUPATEN MANOKWARI</div>
            <div class="small"></div><br>
            <table width="100%" border="1" cellpadding="5">
                <tr class="center">
                    <th width="22">No.</th>
                    <th width="80">Nama Lengkap</th>
                    <th width="19">JK</th>
                    <th width="82">Nomor Pendaftaran</th>
                    <th width="80">NIK</th>
                    <th width="40">Agama</th>
                    <th width="70">Alamat</th>
                    <th width="100">Tempat,<br> Tgl. Lahir</th>
                    <th width="40">Status<br> Menikah</th>
                    <th width="30">Kode Pos</th>
                    <th width="22">TB</th>
                    <th width="22">BB</th>
                    <th width="25">LJ</th>
                    <th width="60">Tujuan<br>Perusahaan</th>
                    <th width="60">Keterampilan<br>Bahasa</th>
                    <th width="60">Bahasa<br> Lainnya</th>
                </tr>';

        $no = 1;
        foreach ($pencari_kerja as $pk) {
            $html .= '<tr>
                    <td align="center">' . $no . '</td>
                    <td align="center">' . $pk['namalengkap'] . '</td>
                    <td align="center">' . $pk['jenkel'] . '</td>
                    <td align="center">' . $pk['nopendaftaran'] . '</td>
                    <td align="center">' . $pk['nik'] . '</td>
                    <td align="center">' . $pk['agama'] . '</td>
                    <td align="center">' . $pk['alamat'] . '</td>
                    <td align="center">' . $pk['tempatlahir'] . ', ' . date('d F Y', strtotime($pk['tgllahir'])) . '</td>
                    <td align="center">' . $pk['statusnikah'] . '</td>
                    <td align="center">' . $pk['kodepos'] . '</td>
                    <td align="center">' . $pk['tinggibadan']  . '</td>
                    <td align="center">' . $pk['beratbadan'] . '</td>
                    <td align="center">' . $pk['lokasi_jabatan'] . '</td>
                    <td align="center">' . $pk['tujuan_perusahaan'] . '</td>
                    <td align="center">' . $pk['keterampilan_bahasa'] . '</td>
                    <td align="center">' . $pk['bahasa_lainnya'] . '</td>
                </tr>';
            $no++;
        }

        $html .= '</table>';

        // Output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('DATA PENCARI KERJA KABUPATEN MANOKWARI.pdf', 'I');

        // Terminate the script after PDF output to prevent further execution
        exit();
    }


    public function exportExcel()
    {
        $pencakerModel = new PencakerModel();
        $dataPencaker = $pencakerModel->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set margin kertas
        $sheet->getPageMargins()->setTop(1.5);
        $sheet->getPageMargins()->setRight(1.5);
        $sheet->getPageMargins()->setBottom(1.5);
        $sheet->getPageMargins()->setLeft(1.5);

        // Set orientasi kertas dan ukuran
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);

        // Tambahkan logo
        $logoPath = FCPATH . 'frontend/assets/img/logodisnakertransmkw.png';
        if (file_exists($logoPath)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath($logoPath);
            $drawing->setHeight(50);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
        }

        // Set title
        $sheet->setCellValue('A2', 'DATA PENCARI KERJA KABUPATEN MANOKWARI');
        $sheet->mergeCells('A2:P2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->setSize(16);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Tambahkan header kolom
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Nama Lengkap');
        $sheet->setCellValue('C4', 'JK');
        $sheet->setCellValue('D4', 'Nomor Pendaftaran');
        $sheet->setCellValue('E4', 'NIK');
        $sheet->setCellValue('F4', 'Agama');
        $sheet->setCellValue('G4', 'Alamat');
        $sheet->setCellValue('H4', 'Tempat, Tgl. Lahir');
        $sheet->setCellValue('I4', 'Status Menikah');
        $sheet->setCellValue('J4', 'Kode Pos');
        $sheet->setCellValue('K4', 'TB');
        $sheet->setCellValue('L4', 'BB');
        $sheet->setCellValue('M4', 'LJ');
        $sheet->setCellValue('N4', 'Tujuan Perusahaan');
        $sheet->setCellValue('O4', 'Keterampilan');
        $sheet->setCellValue('P4', 'Bahasa Lainnya');

        // Format header kolom
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'ECF0F6']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']]],
        ];
        $sheet->getStyle('A4:P4')->applyFromArray($headerStyle);

        // Atur tinggi baris untuk header
        $sheet->getRowDimension(4)->setRowHeight(22);

        // Atur tinggi baris untuk data
        $row = 5;
        foreach ($dataPencaker as $pencaker) {
            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;
        }

        // Isi data
        $row = 5;
        $no = 1;
        foreach ($dataPencaker as $pencaker) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, ucwords(strtolower($pencaker['namalengkap'])));
            $sheet->setCellValue('C' . $row, $pencaker['jenkel']);
            $sheet->setCellValueExplicit('D' . $row, $pencaker['nopendaftaran'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $row, $pencaker['nik'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('F' . $row, $pencaker['agama']);
            $sheet->setCellValue('G' . $row, $pencaker['alamat']);
            $sheet->setCellValue('H' . $row, $pencaker['tempatlahir'] . ', ' . $pencaker['tgllahir']);
            $sheet->setCellValue('I' . $row, $pencaker['statusnikah']);
            $sheet->setCellValue('J' . $row, $pencaker['kodepos']);
            $sheet->setCellValue('K' . $row, $pencaker['tinggibadan']);
            $sheet->setCellValue('L' . $row, $pencaker['beratbadan']);
            $sheet->setCellValue('M' . $row, $pencaker['lokasi_jabatan']);
            $sheet->setCellValue('N' . $row, $pencaker['tujuan_perusahaan']);
            $sheet->setCellValue('O' . $row, $pencaker['keterampilan_bahasa']);
            $sheet->setCellValue('P' . $row, $pencaker['bahasa_lainnya']);

            // Atur alignment untuk data
            $sheet->getStyle('A' . $row . ':P' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $row . ':P' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // Wrap text di setiap sel
            foreach (range('A', 'P') as $col) {
                $sheet->getStyle($col . $row)->getAlignment()->setWrapText(true);
            }

            $row++;
        }

        // Atur lebar kolom
        $sheet->getColumnDimension('A')->setWidth(3); // No
        $sheet->getColumnDimension('B')->setWidth(25); // Nama Lengkap
        $sheet->getColumnDimension('C')->setWidth(3); // JK
        $sheet->getColumnDimension('D')->setWidth(20); // Nomor Pendaftaran
        $sheet->getColumnDimension('E')->setWidth(17); // NIK
        $sheet->getColumnDimension('F')->setWidth(7); // Agama
        $sheet->getColumnDimension('G')->setWidth(30); // Alamat
        $sheet->getColumnDimension('H')->setWidth(25); // Tempat, Tgl. Lahir
        $sheet->getColumnDimension('I')->setWidth(15); // Status Menikah
        $sheet->getColumnDimension('J')->setWidth(9); // Kode Pos
        $sheet->getColumnDimension('K')->setWidth(4); // TB
        $sheet->getColumnDimension('L')->setWidth(4); // BB
        $sheet->getColumnDimension('M')->setWidth(4); // LJ
        $sheet->getColumnDimension('N')->setWidth(30); // Tujuan Perusahaan
        $sheet->getColumnDimension('O')->setWidth(25); // Keterampilan
        $sheet->getColumnDimension('P')->setWidth(30); // Bahasa Lainnya

        // Set nama file dan atur header untuk mengunduh file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'DATA_PENCARI_KERJA_KABUPATEN_MANOKWARI.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
        $writer->save('php://output');
        exit;
    }

    private function loadView(string $viewName, array $data = []): string
    {
        $uri = service('uri');
        $data['current_uri'] = $uri->getSegment(2); // Ambil segmen kedua dari URI

        return view($viewName, $data);
    }

    public function galeri()
    {
        $galeriModel = new GaleriModel();

        $data['categories'] = $galeriModel->getUniqueCategories();
        $data['halaman'] = $galeriModel->getUniqueHalaman();
        $data['galeri'] = $galeriModel->findAll();

        $data['title'] = 'Manajemen Galeri';

        return $this->loadView('admin/galeri', $data);
    }

    public function save_galeri()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'deskripsi' => 'required',
            'file' => 'uploaded[file]|is_image[file]',
            'kategori' => 'required',
            'status' => 'required|in_list[0,1]',
            'halaman' => 'required',  // Validasi untuk halaman
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
        }

        // Proses file upload
        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/galeri/', $newName);

            // Simpan ke database
            $galeri = new GaleriModel();
            $kategori = $this->request->getPost('kategori');
            $kategoriBaru = $this->request->getPost('kategori_baru');

            if ($kategori === 'lainnya' && !empty($kategoriBaru)) {
                // Mengganti spasi dengan underscore pada kategori baru
                $kategori = str_replace(' ', '_', $kategoriBaru);
            }

            $data = [
                'deskripsi' => $this->request->getPost('deskripsi'),
                'kategori' => $kategori,
                'gambar' => $newName,
                'status' => $this->request->getPost('status'),
                'halaman' => $this->request->getPost('halaman'),  // Menyimpan nilai halaman
                'users_id' => user()->id,
            ];
            $galeri->save($data);

            // Rekam aktivitas
            $activityModel = new ActivitylogsModel();
            $activityMessage = sprintf(
                'User #%d mengunggah galeri baru dengan deskripsi: "%s"',
                user_id(),
                $this->request->getPost('deskripsi')
            );
            $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());

            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'errors' => 'File upload failed.']);
        }
    }

    public function update_status_galeri()
    {
        $id = $this->request->getJSON()->id;
        $status = $this->request->getJSON()->status;

        $model = new GaleriModel();
        $update = $model->update($id, ['status' => $status]);

        if ($update) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }

    public function hapus_galeri()
    {
        $id = $this->request->getPost('id');
        $deskripsi = $this->request->getPost('deskripsi');
        $model = new GaleriModel();
        $activityModel = new ActivitylogsModel(); // Tambahkan ini

        // Ambil nama file gambar yang akan dihapus
        $galeri = $model->find($id);
        if (!$galeri) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Galeri tidak ditemukan'])->setStatusCode(404);
        }

        $gambar = $galeri['gambar'];

        // Hapus gambar dari direktori
        if (!empty($gambar) && file_exists(FCPATH . 'uploads/galeri/' . $gambar)) {
            unlink(FCPATH . 'uploads/galeri/' . $gambar);
        }

        // Hapus galeri dari database
        if ($model->delete($id)) {
            // Rekam aktivitas penghapusan galeri
            $activityMessage = "User #" . user()->username . " menghapus galeri $deskripsi";
            $activityModel->add($activityMessage, user_id(), $this->request->getIPAddress());

            // Berhasil menghapus, kembalikan respons JSON sukses
            return $this->response->setJSON(['status' => 'success'])->setStatusCode(200);
        } else {
            // Gagal menghapus, kembalikan respons JSON error
            return $this->response->setJSON(['status' => 'error'])->setStatusCode(500);
        }
    }
}
