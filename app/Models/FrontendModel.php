<?php

namespace App\Models;

use CodeIgniter\Model;

class FrontendModel extends Model
{
    protected $table = 'informasi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kategori', 'judul', 'isi', 'tags', 'tgl_publikasi', 'gambar', 'status', 'slug', 'users_id'
    ];

    public function getKategoriCount()
    {
        return $this->select('kategori, COUNT(*) as count')
            ->groupBy('kategori')
            ->findAll();
    }

    public function getSliderData()
    {
        return $this->where('kategori', 'slider')
            ->findAll();
    }

    public function getRecentPosts($limit = 5)
    {
        return $this->orderBy('tgl_publikasi', 'DESC')
            ->findAll($limit);
    }

    public function getRecentPostsByKategori($kategori, $limit = 6)
    {
        return $this->select('informasi.*, users.namalengkap')
            ->join('users', 'users.id = informasi.users_id')
            ->where('kategori', $kategori)
            ->orderBy('tgl_publikasi', 'DESC')
            ->findAll($limit);
    }

    // Untuk tabel admin/backend
    public function getInformasiByKategori($kategori, $limit, $page)
    {
        return $this->select('informasi.*, users.namalengkap')
            ->join('users', 'users.id = informasi.users_id')
            ->where('informasi.kategori', $kategori)
            ->orderBy('informasi.tgl_publikasi', 'DESC')
            ->paginate($limit, 'default', $page);
    }

    // Untuk halaman depan/frontend
    public function getInformasiByKategoriFront($kategori, $limit, $page)
    {
        return $this->select('informasi.*, users.namalengkap')
            ->join('users', 'users.id = informasi.users_id')
            ->where('informasi.kategori', $kategori)
            ->where('informasi.status', 1) // Disambiguate the status column
            ->orderBy('informasi.tgl_publikasi', 'DESC')
            ->paginate($limit, 'default', $page);
    }

    public function countInformasiByKategori($kategori)
    {
        return $this->where('kategori', $kategori)
            ->countAllResults();
    }

    public function update($id = null, $data = null): bool
    {
        return $this->db->table('informasi')->where('id', $id)->update($data);
    }

    public function incrementViews($id)
    {
        $this->set('views', 'views + 1', FALSE)
            ->where('id', $id)
            ->update();
    }
}
