<?php

namespace App\Models;

use CodeIgniter\Model;

class JabatanModel extends Model
{
    protected $table = 'minat_jabatan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_jabatan', 'lokasi_jabatan', 'pencaker_id'];
}
