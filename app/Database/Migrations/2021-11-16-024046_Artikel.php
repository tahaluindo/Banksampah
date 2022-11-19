<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Artikel extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                // 'type'       => 'text', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,       // mysql BA00999999
                'null'       => false,
            ],
            'title' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 255,
                'null'       => false,
            ],
            'slug' => [
                'type' => 'text',
                'null' => false,
            ],
            'thumbnail' => [
                'type' => 'text',
                'null' => false,
            ],
            'content' => [
                'type' => 'longtext',
                'null' => false,
            ],
            'id_kategori' => [
                // 'type'       => 'text', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,       // mysql
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'bigint',
                'null' => false,
            ],
            'published_at' => [
                'type' => 'bigint',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('title');
        $this->forge->addForeignKey('id_kategori','kategori_artikel','id','CASCADE','CASCADE');
        // $this->forge->addField("created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->forge->createTable('artikel');
    }

    public function down()
    {
        $this->forge->dropTable('artikel');
    }
}
