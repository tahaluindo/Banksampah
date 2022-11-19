<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Wilayah extends Migration
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
            'id_user' => [
                // 'type'       => 'text', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,       // mysql
                'null'       => false,
            ],
            'kodepos' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 10,
                'null'       => false,
            ],
            'kelurahan' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,
                'null'       => false,
            ],
            'kecamatan' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,
                'null'       => false,
            ],
            'kota' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,
                'null'       => false,
            ],
            'provinsi' => [
                // 'type'       => 'character varying', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_user','users','id','CASCADE','CASCADE');
        $this->forge->createTable('wilayah');
    }

    public function down()
    {
        $this->forge->dropTable('wilayah');
    }
}
