<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Mitra extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                // 'type'           => 'serial', // postgre,
                'type'           => 'int', // mysql
                'auto_increment' => true,  // mysql
                'null'           => false,
            ],
            'icon' => [
                'type' => 'text',
                'null' => false,
            ],
            'name' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 255,
                'unique'     => true,
                'null'       => false,
            ],
            'description' => [
                'type' => 'text',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        // $this->forge->addUniqueKey('name'); // postgre
        $this->forge->createTable('mitra');
    }

    public function down()
    {
        $this->forge->dropTable('mitra');
    }
}
