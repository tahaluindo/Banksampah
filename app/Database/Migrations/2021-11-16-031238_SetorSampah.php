<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SetorSampah extends Migration
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
            // alter table setor_sampah add column harga int(11) not null default 0 after id_sampah
            'harga' => [
                // 'type' => 'integer', // postgre
                'type' => 'int', // mysql
                'null' => false,
            ],
            // alter table setor_sampah add column harga_pusat int(11) not null default 0 after id_sampah
            'harga_pusat' => [
                // 'type' => 'integer', // postgre
                'type'    => 'int', // mysql
                'default' => 0,
            ],
            'id_sampah' => [
                // 'type'       => 'text', // postgre
                'type'       => 'varchar', // mysql
                'constraint' => 200,       // mysql
                'null'       => false,
            ],
            'jumlah_kg' => [
                // 'type'    => 'numeric', // postgre
                'type'       => 'DECIMAL', // mysql
                'constraint' => '65,2',   // mysql
                'null'       => false,
            ],
            'jumlah_rp' => [
                // 'type'    => 'integer', // postgre
                'type'       => 'DECIMAL', // mysql
                'constraint' => '11,2',   // mysql
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('no');
        $this->forge->addForeignKey('id_transaksi','transaksi','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('id_sampah','sampah','id','CASCADE','CASCADE');
        $this->forge->createTable('setor_sampah');
    }

    public function down()
    {
        $this->forge->dropTable('setor_sampah');
    }
}
