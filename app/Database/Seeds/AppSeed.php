<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AppSeed extends Seeder
{
    public function run()
    {
        /**
         * Admin
         */
        $dataAdmin = [
            'id'           => 'A001',
            'email'        => null,
            'username'     => 'superadmin1',
            'password'     => password_hash(trim('superadmin1'), PASSWORD_DEFAULT),
            'nama_lengkap' => 'super admin 1',
            'notelp'       => null,
            'nik'          => null,
            'alamat'       => null,
            'tgl_lahir'    => date("d-m-Y", time()),
            'kelamin'      => 'laki-laki',
            'is_active'    => true,
            'last_active'  => time(),
            'is_verify'    => true,
            'privilege'    => 'superadmin',
            'created_at'   => time(),
        ];

        $this->db->table('users')->insert($dataAdmin);

        /**
         * Dompet Admin
         */
        $dataDompet = [
            'id_user' => null,
            'uang'    => 0,
            'emas'    => 0,
        ];

        $this->db->table('dompet')->insert($dataDompet);
    }
}
