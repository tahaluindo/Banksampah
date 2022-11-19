<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                // 'type'       => 'text',    // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,       // mysql 122600110119999999
                'null'       => false,
            ],
            'email' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,
                'unique'     => true,
                'null'       => true,
            ],
            'username' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 20,
                'unique'     => true,
                'null'       => false,
            ],
            'password'       => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 255,
                'null'       => false,
            ],
            'nama_lengkap' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 40,
                // 'unique'     => true,
                'null'       => false,
            ],
            'notelp' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 14,
                'unique'     => true,
                'null'       => true,
            ],
            'nik' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 16,
                'unique'     => true,
                'null'       => true,
            ],
            'alamat' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 255,
                'null'       => true,
            ],
            'tgl_lahir' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 10,
                'null'       => false,
                'default'    => '00-00-0000',
            ],
            'kelamin' => [
                // 'type'       => 'character varying', // postgre
                // 'constraint' => 9,                   // postgre
                'type'       => "ENUM",                     // mysql
                'constraint' => ['laki-laki', 'perempuan'], // mysql
                'null'       => false,
            ],
            'token' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
                'null'    => false,
            ],
            'last_active' => [
                'type'       => 'bigint',
                'default'    => 0,
                'null'       => false,
            ],
            'otp' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 6,
                'null'       => true,
            ],
            'is_verify' => [
                'type'    => 'BOOLEAN',
                'null'    => false,
                'default' => false,
            ],
            'privilege' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 10,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'bigint',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        // $this->forge->addUniqueKey(['email','username','notelp']);
        // $this->forge->addField("created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
