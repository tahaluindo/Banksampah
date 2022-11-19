<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KategoriArtikel extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                // 'type'       => 'text', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,       // mysql KA0999
                'null'       => false,
            ],
            'icon' => [
                'type' => 'text',
                'null' => false,
            ],
            'name' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 100,
                'unique'     => true,
                'null'       => false,
            ],
            'description' => [
                'type' => 'text',
                'null' => false,
            ],
            'kategori_utama' => [
                'type'    => 'BOOLEAN',
                'null'    => false,
            ],
            'created_at' => [
                'type' => 'bigint',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        // $this->forge->addUniqueKey('name'); // postgre
        $this->forge->createTable('kategori_artikel');
    }

    public function down()
    {
        $this->forge->dropTable('kategori_artikel');
    }
}
