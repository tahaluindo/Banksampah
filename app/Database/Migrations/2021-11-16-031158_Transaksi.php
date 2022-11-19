<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Transaksi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'no' => [
                // 'type' => 'integer', // postgre,
                'type'           => 'int', // mysql
                'auto_increment' => true,  // mysql
                'null'           => false,
            ],
            'id' => [
                // 'type'       => 'text', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,       // mysql
                'null'       => false,
            ],
            'id_user' => [
                // 'type'       => 'text', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,       // mysql
                'null'       => false,
            ],
            'jenis_transaksi' => [
                // 'type'       => 'text', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 50,        // mysql
                'null'       => false,
            ],
            'date' => [
                'type' => 'bigint',
                'null' => false,
            ],
        ]);

        $this->forge->addKey("no");
        $this->forge->addPrimaryKey("id");
        $this->forge->addForeignKey('id_user','users','id','CASCADE','CASCADE');
        // $this->forge->addField("date TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->forge->createTable('transaksi');
    }

    public function down()
    {
        $this->forge->dropTable('transaksi');
    }
}
