<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PindahSaldo extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'no' => [
                // 'type'           => 'serial', // postgre,
                'type'           => 'int', // mysql
                'auto_increment' => true,  // mysql
                'null'           => false,
            ],
            'id_transaksi' => [
                // 'type'       => 'text', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,       // mysql
                'null'       => false,
            ],
            'jumlah' => [
                // 'type'    => 'integer', // postgre
                'type'       => 'DECIMAL', // mysql
                'constraint' => '65,2',   // mysql
                'null'       => false,
            ],
            'harga_emas' => [
                // 'type'    => 'integer', // postgre
                'type'       => 'DECIMAL', // mysql
                'constraint' => '11,2',   // mysql
                'null'       => false,
            ],
            'hasil_konversi' => [
                // 'type'    => 'numeric', // postgre
                'type'       => 'DECIMAL', // mysql
                'constraint' => '65,4',   // mysql
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('no');
        $this->forge->addForeignKey('id_transaksi','transaksi','id','CASCADE','CASCADE');
        $this->forge->createTable('pindah_saldo');
    }

    public function down()
    {
        $this->forge->dropTable('pindah_saldo');
    }
}
