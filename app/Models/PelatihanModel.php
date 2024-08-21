<?php

namespace App\Models;

use CodeIgniter\Model;

class PelatihanModel extends Model
{
    protected $table = 'pelatihan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul', 'deskripsi', 'materi', 'tanggal', 'gambar', 'tgl_pelatihan', 'status', 'slug', 'users_id', 'link', 'jenis_pelatihan_kode'
    ];

    public function get_all_pelatihan()
    {
        $builder = $this->db->table('pelatihan');
        $builder->select('id, judul, deskripsi, materi, tanggal, gambar, tgl_pelatihan, status, slug, users_id, link, kode, pelatihan');
        $builder->join('jenis_pelatihan', 'jenis_pelatihan.kode = pelatihan.jenis_pelatihan_kode');

        $query = $builder->get();
        return $query->getResultArray();
    }

    // Halaman Pelatihan Frontend
    public function get_all_pelatihan_by_penulis()
    {
        $builder = $this->db->table('pelatihan');
        $builder->select('pelatihan.id, judul, deskripsi, materi, tanggal, gambar, tgl_pelatihan, pelatihan.status, slug, users_id, link, kode, pelatihan, namalengkap');
        $builder->join('jenis_pelatihan', 'jenis_pelatihan.kode = pelatihan.jenis_pelatihan_kode');
        $builder->join('users', 'users.id = pelatihan.users_id');
        $builder->where('pelatihan.status', 1);
        $builder->orderBy('tanggal', 'DESC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_pelatihan_by_slug($slug)
    {
        $builder = $this->db->table('pelatihan');
        $builder->select('pelatihan.id, judul, deskripsi, materi, tanggal, gambar, tgl_pelatihan, pelatihan.status, slug, users_id, link, jenis_pelatihan_kode, kode, pelatihan, namalengkap');
        $builder->join('jenis_pelatihan', 'jenis_pelatihan.kode = pelatihan.jenis_pelatihan_kode');
        $builder->join('users', 'users.id = pelatihan.users_id');
        $builder->where('slug', $slug);

        $query = $builder->get();
        return $query->getRowArray();
    }

    public function get_pelatihan_by_jenis($jenis_pelatihan_kode, $excludeId = null)
    {
        $builder = $this->where('jenis_pelatihan_kode', $jenis_pelatihan_kode)
            ->where('status', 1) // Tambahkan kondisi status = 1
            ->orderBy('tanggal', 'DESC');

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->findAll();
    }
}
