<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'namalengkap',
        'nik',
        'nohp',
        'email',
        'username',
        'password_hash',
        'reset_hash',
        'reset_at',
        'reset_expires',
        'activate_hash',
        'status',
        'status_message',
        'active',
        'force_pass_reset',
        'permissions',
        'deleted_at',
    ];

    public function saveUser($data)
    {
        return $this->insert($data);
    }

    public function ubah_status_user()
    {
        $builder = $this->db->table('users');
        $builder->distinct();
        $builder->select('users.id as userid, username, nohp, nik, active, email, name, namalengkap, auth_groups.name as group_name, users.updated_at');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');

        $query = $builder->get();
        return $query->getResult();
    }
}
