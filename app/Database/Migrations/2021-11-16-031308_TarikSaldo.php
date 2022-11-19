<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TarikSaldo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'no' => [
                // 'type'           => 'serial', // postgre,
                'type'           => 'int', // mysql
                'auto_increment' => true,     // mysql
                'null'           => false,
            ],
            'id_transaksi' => [
                // 'type'       => 'text', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,       // mysql
                'null'       => false,
            ],
            'jenis_saldo' => [
                // 'type'       => 'character varying', // postgre
                'type'       => "ENUM", // mysql
                'constraint' => ['uang', 'ubs', 'antam', 'galery24'], // mysql
                'null'       => false,
            ],
            'jumlah_tarik' => [
                // 'type'    => 'numeric', // postgre
                'type'       => 'DECIMAL', // mysql
                'constraint' => '65,4',   // mysql
                'null'       => false,
            ],
            'description' => [
                'type'       => 'text', // mysql
                'null'       => true,
            ],
        ]);

        $this->forge->addPrimaryKey('no');
        $this->forge->addForeignKey('id_transaksi','transaksi','id','CASCADE','CASCADE');
        $this->forge->createTable('tarik_saldo');
    }

    public function down()
    {
        $this->forge->dropTable('tarik_saldo');
    }
}
