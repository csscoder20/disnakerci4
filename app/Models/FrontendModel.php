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

    public function getRecentPostsByKategori($kategori, $limit = 5)
    {
        return $this->where('kategori', $kategori)
            ->orderBy('tgl_publikasi', 'DESC')
            ->findAll($limit);
    }

    // public function getInformasiByKategori($kategori, $limit, $offset)
    // {
    //     return $this->where('kategori', $kategori)
    //         ->orderBy('tgl_publikasi', 'DESC')
    //         ->findAll($limit, $offset);
    // }

    public function getInformasiByKategori($kategori, $limit, $offset)
    {
        return $this->db->table('informasi')
            ->where('kategori', $kategori)
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
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
}
