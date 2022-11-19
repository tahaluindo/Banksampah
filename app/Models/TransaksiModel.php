<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class TransaksiModel extends Model
{
    protected $table         = 'transaksi';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['id','id_nasabah','type'];

    // GET Last nomor
    public function getLastNo(): int
    {
        $lastNomor = $this->db->table($this->table)->select('no')->limit(1)->orderBy('no','DESC')->get()->getResultArray();

        $lastNomor = ($lastNomor) ? (int)$lastNomor[0]['no'] : 0;
        
        return $lastNomor+1;
    }

    public function setorSampah(array $data): array
    {
        try {
            $date        = (int)strtotime($data['date']);
            $idtransaksi = $data['idtransaksi'];
            $idnasabah   = $data['id_nasabah'];
            $totalHarga  = 0;
            $queryDetilSetor = "INSERT INTO setor_sampah (id_transaksi,id_sampah,harga,harga_pusat,jumlah_kg,jumlah_rp) VALUES";

            foreach ($data['transaksi'] as $t) {
                $idSampah   = $t['id_sampah'];
                $jumlah     = (float)$t['jumlah'];
                $dataHarga  = $this->db->table('sampah')->select("harga,harga_pusat")->where("id",$idSampah)->get()->getResultArray();

                $hargaAsli = (float)$dataHarga[0]['harga'];
                $hargaAsliPengepul = (float)$dataHarga[0]['harga_pusat'];

                $harga      = $hargaAsli*(float)$jumlah;
                $totalHarga = $totalHarga+$harga;

                $this->db->query("UPDATE sampah SET jumlah=jumlah+$jumlah WHERE id = '$idSampah';");
                $queryDetilSetor.= "('$idtransaksi','$idSampah',$hargaAsli,$hargaAsliPengepul,$jumlah,$harga),";
            }

            $queryDetilSetor  = rtrim($queryDetilSetor, ",");
            $queryDetilSetor .= ';';
            $lastNomor        = $this->getLastNo();

            $this->db->transBegin();
            $this->db->query("INSERT INTO transaksi (no,id,id_user,jenis_transaksi,date) VALUES($lastNomor,'$idtransaksi','$idnasabah','penyetoran sampah',$date);");
            $this->db->query("UPDATE dompet SET uang=uang+$totalHarga WHERE id_user='$idnasabah';");
            
            $this->db->query($queryDetilSetor);

            $transStatus = $this->db->transStatus();

            if ($transStatus) {
                $this->db->transCommit();
            } 
            else {
                $this->db->transRollback();
            }

            return [
                'status'   => ($transStatus) ? 201   : 500,
                'error'    => ($transStatus) ? false : true,
                'messages' => ($transStatus) ? 'setor sampah is success' : "setor sampah is failed",
                'id_transaksi' => ($transStatus) ? $idtransaksi : null,
            ];
        } 
        catch (Exception $e) {
            $this->db->transRollback();
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function editSetorSampah(array $data): array
    {
        try {
            $this->db->transBegin();
            $date        = (int)strtotime($data['date']);
            $idtransaksi = $data['idtransaksi'];
            $idnasabah   = $data['id_nasabah'];

            $oldSaldo = $this->db->table('setor_sampah')->select("sum(jumlah_rp) as uang")->where("id_transaksi",$idtransaksi)->get()->getFirstRow();
            $oldSetorSampah = $this->db->table('setor_sampah')->select("*")->where("id_transaksi",$idtransaksi)->get()->getResultArray();

            $this->db->query("UPDATE dompet SET uang=uang-$oldSaldo->uang WHERE id_user='$idnasabah';");
            $this->db->query("DELETE FROM setor_sampah WHERE id_transaksi = '$idtransaksi';");

            foreach ($oldSetorSampah as $t) {
                $idSampah   = $t['id_sampah'];
                $jumlah     = (float)$t['jumlah_kg'];
                $this->db->query("UPDATE sampah SET jumlah=jumlah-$jumlah WHERE id = '$idSampah';");
            }

            $totalHarga  = 0;
            $queryDetilSetor = "INSERT INTO setor_sampah (id_transaksi,id_sampah,harga,harga_pusat,jumlah_kg,jumlah_rp) VALUES";

            foreach ($data['transaksi'] as $t) {
                $idSampah   = $t['id_sampah'];
                $jumlah     = (float)$t['jumlah'];
                $dataHarga  = $this->db->table('sampah')->select("harga,harga_pusat")->where("id",$idSampah)->get()->getResultArray();

                $hargaAsli = (float)$dataHarga[0]['harga'];
                $hargaAsliPengepul = (float)$dataHarga[0]['harga_pusat'];

                $harga      = $hargaAsli*(float)$jumlah;
                $totalHarga = $totalHarga+$harga;

                $this->db->query("UPDATE sampah SET jumlah=jumlah+$jumlah WHERE id = '$idSampah';");
                $queryDetilSetor.= "('$idtransaksi','$idSampah',$hargaAsli,$hargaAsliPengepul,$jumlah,$harga),";
            }

            $queryDetilSetor  = rtrim($queryDetilSetor, ",");
            $queryDetilSetor .= ';';

            $this->db->query("UPDATE transaksi SET date=$date WHERE id='$idtransaksi';");
            $this->db->query("UPDATE dompet SET uang=uang+$totalHarga WHERE id_user='$idnasabah';");
            
            $this->db->query($queryDetilSetor);

            $transStatus = $this->db->transStatus();

            if ($transStatus) {
                $this->db->transCommit();
            } 
            else {
                $this->db->transRollback();
            }

            return [
                'status'   => ($transStatus) ? 201   : 500,
                'error'    => ($transStatus) ? false : true,
                'messages' => ($transStatus) ? 'setor sampah is success' : "setor sampah is failed",
                'id_transaksi' => ($transStatus) ? $idtransaksi : null,
            ];
        } 
        catch (Exception $e) {
            $this->db->transRollback();
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getTrace(),
            ];
        }
    }

    public function getSaldoJenisX(string $idNasabah,string $jenisSaldo,bool $isAdmin = false): string
    {
        $this->db->transBegin();

        if ($isAdmin) {
            $idNasabah = null;
        }

        $jenisSaldo = ($jenisSaldo == 'uang') ? 'uang' : 'emas';
        $saldo = $this->db->table('dompet')->select($jenisSaldo)->where('id_user',$idNasabah)->get()->getResultArray();

        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $saldo[0][$jenisSaldo];
        }
        else {
            $this->db->transRollback();
        }
    }

    public function tarikSaldo(array $data): array
    {
        try {
            $date        = (int)strtotime($data['date']);
            $pemilik     = $data['pemilik'];
            $idtransaksi = $data['idtransaksi'];
            $idnasabah   = $data['id_nasabah'];
            $jenisSaldo  = $data['jenis_saldo'];
            $jenisDompet = ($data['jenis_saldo'] == 'uang') ? 'uang' : 'emas';
            $jumlahTarik = $data['jumlah'];
            $lastNomor   = $this->getLastNo();

            $this->db->transBegin();
            $this->db->query("INSERT INTO transaksi (no,id,id_user,jenis_transaksi,date) VALUES($lastNomor,'$idtransaksi','$idnasabah','penarikan saldo',$date);");
            
            if ($pemilik == "bsbl") {
                $description = $data['description'];
                $this->db->query("INSERT INTO tarik_saldo (id_transaksi,jenis_saldo,jumlah_tarik,description) VALUES('$idtransaksi','$jenisSaldo',$jumlahTarik,'$description')");
                $this->db->query("UPDATE dompet SET uang=uang-$jumlahTarik WHERE id_user IS NULL;");
            } 
            else {
                $this->db->query("INSERT INTO tarik_saldo (id_transaksi,jenis_saldo,jumlah_tarik) VALUES('$idtransaksi','$jenisSaldo',$jumlahTarik)");
                $this->db->query("UPDATE dompet SET $jenisDompet=$jenisDompet-$jumlahTarik WHERE id_user='$idnasabah';");
            }

            $transStatus = $this->db->transStatus();

            if ($transStatus) {
                $this->db->transCommit();
            } 
            else {
                $this->db->transRollback();
            }

            return [
                'status'   => ($transStatus) ? 201   : 500,
                'error'    => ($transStatus) ? false : true,
                'messages' => ($transStatus) ? 'tarik saldo is success' : "tarik saldo is failed",
            ];
        } 
        catch (Exception $e) {
            $this->db->transRollback();
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function pindahSaldo(array $data): array
    {
        try {
            $date            = (int)strtotime($data['date']);
            $idnasabah       = $data['idnasabah'];
            $idtransaksi     = $data['idtransaksi'];
            $jumlahPindah    = $data['jumlahPindah'];
            $hasilKonversi   = $data['hasilKonversi'];
            $hargaemas       = $data['hargaemas'];
            $saldoDompetAsal = $data['saldo_dompet_asal'];
            $lastNomor       = $this->getLastNo();

            // var_dump($hasilKonversi);die;
            
            $this->db->transBegin();
            $this->db->query("INSERT INTO transaksi (no,id,id_user,jenis_transaksi,date) VALUES($lastNomor,'$idtransaksi','$idnasabah','konversi saldo',$date);");
            $this->db->query("UPDATE dompet SET uang=$saldoDompetAsal,emas=emas+$hasilKonversi WHERE id_user='$idnasabah';");
            $this->db->query("INSERT INTO pindah_saldo (id_transaksi,jumlah,harga_emas,hasil_konversi) VALUES ('$idtransaksi',$jumlahPindah,$hargaemas,$hasilKonversi)");
            
            $transStatus = $this->db->transStatus();

            if ($transStatus) {
                $this->db->transCommit();
            } 
            else {
                $this->db->transRollback();
            }

            return [
                'status'   => ($transStatus) ? 201   : 500,
                'error'    => ($transStatus) ? false : true,
                'messages' => ($transStatus) ? 'pindah saldo is success' : "pindah saldo is failed",
            ];
        } 
        catch (Exception $e) {
            $this->db->transRollback();
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function jumlahTps(string $idNasabah): int
    {
        $transaction = $this->db->table($this->table)->select('count(id) AS jumlah')->where('id_user',$idNasabah)->where('jenis_transaksi','konversi saldo')->get()->getResultArray();

        return (int)$transaction[0]['jumlah'];
    }

    public function jualSampah(array $data): array
    {
        try {
            $date        = (int)strtotime($data['date']);
            $idtransaksi = $data['idtransaksi'];
            $idnasabah   = $data['id_admin'];
            $totalHarga  = 0;
            $queryJmlSampah = '';
            $queryDetilJual = "INSERT INTO jual_sampah (id_transaksi,id_sampah,jumlah_kg,harga_nasabah,jumlah_rp) VALUES";

            foreach ($data['transaksi'] as $t) {
                $idSampah    = $t['id_sampah'];
                $jumlah      = $t['jumlah'];
                $hargaAsli   = $this->db->table('sampah')->select("harga_pusat,harga")->where("id",$idSampah)->get()->getResultArray();
                $hargaNasabah= (int)$hargaAsli[0]['harga']*(float)$jumlah;
                $hargaPusat  = (int)$hargaAsli[0]['harga_pusat']*(float)$jumlah;
                $hargaUntung = ((int)$hargaAsli[0]['harga_pusat']-(int)$hargaAsli[0]['harga'])*(float)$jumlah;
                $totalHarga = $totalHarga+$hargaUntung;

                // $queryJmlSampah .= "UPDATE sampah SET jumlah=jumlah-$jumlah WHERE id = '$idSampah';"; // postgreSql

                $this->db->query("UPDATE sampah SET jumlah=jumlah-$jumlah WHERE id = '$idSampah';");
                $queryDetilJual.= "('$idtransaksi','$idSampah',$jumlah,$hargaNasabah,$hargaPusat),";
            }

            $queryDetilJual  = rtrim($queryDetilJual, ",");
            $queryDetilJual .= ';';
            $lastNomor       = $this->getLastNo();

            $this->db->transBegin();
            $this->db->query("INSERT INTO transaksi (no,id,id_user,jenis_transaksi,date) VALUES($lastNomor,'$idtransaksi','$idnasabah','penjualan sampah',$date);");
            $this->db->query("UPDATE dompet SET uang=uang+$totalHarga WHERE id_user IS NULL;");
            
            // $this->db->query($queryJmlSampah); // postgreSql

            $this->db->query($queryDetilJual);

            $transStatus = $this->db->transStatus();

            if ($transStatus) {
                $this->db->transCommit();
            } 
            else {
                $this->db->transRollback();
            }

            return [
                'status'   => ($transStatus) ? 201   : 500,
                'error'    => ($transStatus) ? false : true,
                'messages' => ($transStatus) ? 'jual sampah is success' : "jual sampah is failed",
            ];
        } 
        catch (Exception $e) {
            $this->db->transRollback();
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function getSampahMasuk(array $get,string $idNasabah): array
    {
        try {
            if (isset($get['kategori'])) {
                $query  = "SELECT SUM(setor_sampah.jumlah_kg) AS jumlah_kg,sampah.jenis
                FROM transaksi 
                JOIN setor_sampah    ON(setor_sampah.id_transaksi=transaksi.id)
                JOIN sampah          ON(setor_sampah.id_sampah=sampah.id) 
                JOIN kategori_sampah ON(sampah.id_kategori=kategori_sampah.id)";

                if ($idNasabah != '') {
                    $query .= " WHERE transaksi.id_user = '$idNasabah' AND kategori_sampah.name='".$get['kategori']."'";
                }
                else {
                    $query .= " WHERE kategori_sampah.name='".$get['kategori']."'";
                }

                $query      .= "  GROUP BY sampah.jenis;";
                $sampahMasuk = $this->db->query($query)->getResultArray();
            } 
            else {
                $data      = [];
                $katSampah = $this->db->query("SELECT name FROM kategori_sampah")->getResultArray();

                foreach ($katSampah as $key => $value) {
                    $query  = "SELECT SUM(setor_sampah.jumlah_kg) AS total
                    FROM transaksi 
                    JOIN setor_sampah    ON(setor_sampah.id_transaksi=transaksi.id)
                    JOIN sampah          ON(setor_sampah.id_sampah=sampah.id) 
                    JOIN kategori_sampah ON(sampah.id_kategori=kategori_sampah.id)
                    WHERE kategori_sampah.name = '".$value['name']."'";

                    if ($idNasabah != '') {
                        $query .= " AND transaksi.id_user = '$idNasabah'";
                    }

                    $total = (array)$this->db->query($query)->getFirstRow();

                    $data[$value['name']] = [
                        "kategori" => $value['name'],
                        "total"    => ($total['total']) ? $total['total'] : 0 
                    ];
                }

                $sampahMasuk = $data;
            }

            
            if (empty($sampahMasuk)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "data notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => true,
                    'data'   => $sampahMasuk
                ];
            }
        } 
        catch (Exception $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function getAllJenisSaldo(string $idNasabah): array
    {   
        try {
            $this->db->transBegin();

            if ($idNasabah != '') {
                $saldo = $this->db->table('dompet')->select('uang,emas')->where('id_user',$idNasabah)->get()->getFirstRow();
            }
            else {
                $saldoNasabah = $this->db->query("SELECT SUM(uang) AS uang,SUM(emas) AS emas FROM dompet WHERE id_user IS NOT NULL")->getFirstRow();
                $saldoBank    = $this->db->query("SELECT SUM(uang) AS uang FROM dompet WHERE id_user IS NULL")->getFirstRow();

                $saldo = [
                    "saldo_nasabah" => $saldoNasabah,
                    "saldo_bank"    => $saldoBank->uang,
                ];
            }

            if ($this->db->transStatus()) {
                $this->db->transCommit();

                if ($saldo) {
                    return [
                        'status' => 200,
                        'error'  => false,
                        'data'   => $saldo,
                    ];
                } 
                else {
                    return [
                        'status'   => 404,
                        'error'    => true,
                        'messages' => "nasabah not found",
                    ];
                }
            }
            else {
                $this->db->transRollback();
            }
        } 
        catch (Exception $e) {
            $this->db->transRollback();
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function getData(array $get,string $idNasabah): array
    {
        try {
            if (isset($get['id_transaksi']) && !isset($get['idnasabah'])) {
                $id_transaksi   = $get['id_transaksi'];
                $code_transaksi = substr($get['id_transaksi'],0,3);
                
                if ($code_transaksi == 'TSS') {
                    $transaction  = $this->db->query("SELECT transaksi.id AS id_transaksi,transaksi.id_user,transaksi.jenis_transaksi,users.nama_lengkap,kategori_sampah.name AS kategori,sampah.jenis,setor_sampah.jumlah_kg,setor_sampah.jumlah_rp,transaksi.date 
                    FROM transaksi 
                    JOIN users        ON (transaksi.id_user = users.id) 
                    JOIN setor_sampah ON (transaksi.id = setor_sampah.id_transaksi) 
                    JOIN sampah       ON (setor_sampah.id_sampah = sampah.id) 
                    JOIN kategori_sampah ON (sampah.id_kategori = kategori_sampah.id)
                    WHERE transaksi.id = '$id_transaksi';")->getResultArray();

                    $transaction = $this->makeDetilSetorJualSampah($transaction);
                } 
                else if ($code_transaksi == 'TTS') {
                    $transaction  = $this->db->query("SELECT transaksi.id AS id_transaksi,transaksi.id_user,transaksi.jenis_transaksi,users.privilege,users.nama_lengkap,tarik_saldo.jenis_saldo,tarik_saldo.jumlah_tarik,tarik_saldo.description,transaksi.date 
                    FROM transaksi 
                    JOIN users ON (transaksi.id_user = users.id) 
                    JOIN tarik_saldo ON (transaksi.id = tarik_saldo.id_transaksi) 
                    WHERE transaksi.id = '$id_transaksi';")->getResultArray()[0];
                }
                else if ($code_transaksi == 'TPS') {
                    $transaction  = $this->db->query("SELECT transaksi.id AS id_transaksi,transaksi.id_user,transaksi.jenis_transaksi,users.nama_lengkap,pindah_saldo.jumlah,pindah_saldo.hasil_konversi,pindah_saldo.harga_emas,transaksi.date 
                    FROM transaksi 
                    JOIN users ON (transaksi.id_user = users.id) 
                    JOIN pindah_saldo ON (transaksi.id = pindah_saldo.id_transaksi) 
                    WHERE transaksi.id = '$id_transaksi';")->getResultArray()[0];
                }
                else if ($code_transaksi == 'TJS') {
                    $transaction  = $this->db->query("SELECT transaksi.id AS id_transaksi,transaksi.id_user,transaksi.jenis_transaksi,users.nama_lengkap,sampah.jenis,jual_sampah.jumlah_kg,jual_sampah.harga_nasabah,jual_sampah.jumlah_rp,transaksi.date 
                    FROM transaksi 
                    JOIN users        ON (transaksi.id_user = users.id) 
                    JOIN jual_sampah  ON (transaksi.id = jual_sampah.id_transaksi) 
                    JOIN sampah       ON (jual_sampah.id_sampah = sampah.id) 
                    WHERE transaksi.id = '$id_transaksi';")->getResultArray();

                    $transaction = $this->makeDetilSetorJualSampah($transaction);
                } 
                else {
                    $transaction = false;
                }
            } 
            else {
                $query  = 'SELECT transaksi.id AS id_transaksi,transaksi.id_user,users.nama_lengkap,transaksi.date,transaksi.jenis_transaksi,
                (SELECT SUM(jumlah_rp) from setor_sampah WHERE setor_sampah.id_transaksi = transaksi.id) AS total_uang_setor,
                (SELECT SUM(jumlah_kg) from setor_sampah WHERE setor_sampah.id_transaksi = transaksi.id) AS total_kg_setor,
                (SELECT SUM(jumlah_rp) from jual_sampah WHERE jual_sampah.id_transaksi = transaksi.id) AS total_uang_jual,
                (SELECT SUM(jumlah_kg) from jual_sampah WHERE jual_sampah.id_transaksi = transaksi.id) AS total_kg_jual,
                (SELECT SUM(jumlah_tarik) from tarik_saldo WHERE tarik_saldo.id_transaksi = transaksi.id) AS total_tarik,
                (SELECT jenis_saldo from tarik_saldo WHERE tarik_saldo.id_transaksi = transaksi.id) AS jenis_saldo,
                (SELECT SUM(jumlah) from pindah_saldo WHERE pindah_saldo.id_transaksi = transaksi.id) AS total_pindah,
                (SELECT SUM(hasil_konversi) from pindah_saldo WHERE pindah_saldo.id_transaksi = transaksi.id) AS hasil_konversi 
                FROM transaksi
                JOIN users ON (transaksi.id_user = users.id)';

                if ($idNasabah != '') {
                    $query .= " WHERE transaksi.id_user = '$idNasabah'";
                }

                if (isset($get['start']) && isset($get['end'])) {
                    $start   = (int)strtotime($get['start'].' 01:00');
                    $end     = (int)strtotime($get['end'].' 23:59');

                    $query  .= ($idNasabah != '') ? ' AND' : ' WHERE' ;
                    $query  .= " transaksi.date BETWEEN $start AND $end";
                }

                $orderby     = (isset($get['orderby']) && $get['orderby']=='terbaru')? 'DESC': 'ASC';
                $query      .= " ORDER BY transaksi.no $orderby;";
                $transaction = $this->db->query($query)->getResultArray();
                $transaction = $this->filterAllTransaksi($transaction);
            } 

            if (empty($transaction)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "transaction notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $transaction
                ];
            }
        } 
        catch (Exception $e) {
            $this->db->transRollback();
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function makeDetilSetorJualSampah(array $data): array
    {
        $detil  = [];
        $barang = [];

        foreach ($data as $d) {
            $id_transaksi   = $d['id_transaksi'];
            $id_user        = $d['id_user'];
            $nama_lengkap   = $d['nama_lengkap'];
            $jenis_transaksi= $d['jenis_transaksi'];
            $date           = $d['date'];
            unset($d['id_transaksi']);
            unset($d['id_user']);
            unset($d['nama_lengkap']);
            unset($d['jenis_transaksi']);
            unset($d['date']);
            $barang[] = $d;
        }

        $detil['id_transaksi']   = $id_transaksi;
        $detil['id_user']        = $id_user;
        $detil['nama_lengkap']   = $nama_lengkap;
        $detil['jenis_transaksi']= $jenis_transaksi;
        $detil['date']           = $date;
        $detil['barang']         = $barang;

        return $detil;
    }

    public function filterAllTransaksi(array $data): array
    {
        $transaction = [];

        foreach ($data as $d) {
            if ($d['total_uang_setor'] == null) {
                unset($d['total_uang_setor']);
            }
            if ($d['total_kg_setor'] == null) {
                unset($d['total_kg_setor']);
            }
            if ($d['total_uang_jual'] == null) {
                unset($d['total_uang_jual']);
            }
            if ($d['total_kg_jual'] == null) {
                unset($d['total_kg_jual']);
            }
            if ($d['total_tarik'] == null) {
                unset($d['total_tarik']);
            }
            if ($d['jenis_saldo'] == null) {
                unset($d['jenis_saldo']);
            }
            if ($d['total_pindah'] == null) {
                unset($d['total_pindah']);
            }
            if ($d['hasil_konversi'] == null) {
                unset($d['hasil_konversi']);
            }

            $transaction[] = $d;
        }

        return $transaction;
    }

    public function rekapData(array $get): array
    {
        try {
            $transaction = [];

            if (isset($get['date']) || isset($get['start']) && isset($get['end'])) {
                if (isset($get['date'])) {
                    $start = (int)strtotime('01-'.$get['date'].' 00:01');
                    $end   = $start+(86400*30);
                    unset($get['date']);
                } 
                else {
                    $start = (int)strtotime($get['start'].' 00:01');
                    $end   = (int)strtotime($get['end'].' 23:59');
                    unset($get['start']);
                    unset($get['end']);
                }
                
                $filterWIlayah = '';
                $nasabahWhereClause = '';

                if (!isset($get['idnasabah'])) {
                    foreach ($get as $key => $value) {
                        if (in_array($key,['provinsi','kota','kecamatan','kelurahan'])) {
                            $filterWIlayah .= "AND wilayah.$key = '$value' ";
                        }
                    }
                }
                else {
                    $nasabahWhereClause = 'AND transaksi.id_user = '.$get['idnasabah'];
                }
                
                if ($get['jenis'] == 'penimbangan-sampah') {
                    $dataTss = $this->db->query("SELECT transaksi.id AS id_transaksi,transaksi.id_user,transaksi.jenis_transaksi,users.nama_lengkap,sampah.id AS id_sampah,sampah.jenis AS jenis_sampah,setor_sampah.harga,setor_sampah.harga_pusat,setor_sampah.jumlah_kg,setor_sampah.jumlah_rp,transaksi.date 
                    FROM transaksi 
                    JOIN users        ON (transaksi.id_user = users.id)
                    JOIN setor_sampah ON (transaksi.id = setor_sampah.id_transaksi) 
                    JOIN sampah       ON (setor_sampah.id_sampah = sampah.id) 
                    JOIN wilayah      ON (wilayah.id_user = users.id)
                    WHERE transaksi.date BETWEEN '$start' AND '$end' $filterWIlayah $nasabahWhereClause
                    ORDER BY date ASC;")->getResultArray();
                    $transaction['tss']  = $dataTss;
                }

                if ($get['jenis'] == 'penarikan-saldo') {
                    $dataTtsBst = $this->db->query("SELECT transaksi.id AS id_transaksi,transaksi.id_user,transaksi.jenis_transaksi,users.nama_lengkap,tarik_saldo.jumlah_tarik,tarik_saldo.description,transaksi.date 
                    FROM transaksi 
                    JOIN users        ON (transaksi.id_user = users.id)
                    JOIN tarik_saldo  ON (transaksi.id = tarik_saldo.id_transaksi) 
                    WHERE transaksi.date BETWEEN '$start' AND '$end' AND users.privilege != 'nasabah'
                    ORDER BY date ASC;")->getResultArray();
                    $transaction['ttsBst'] = $dataTtsBst;

                    $dataTts = $this->db->query("SELECT transaksi.id AS id_transaksi,transaksi.id_user,transaksi.jenis_transaksi,users.nama_lengkap,tarik_saldo.jenis_saldo,tarik_saldo.jumlah_tarik,transaksi.date 
                    FROM transaksi 
                    JOIN users        ON (transaksi.id_user = users.id)
                    JOIN tarik_saldo  ON (transaksi.id = tarik_saldo.id_transaksi) 
                    JOIN wilayah      ON (wilayah.id_user = users.id)
                    WHERE transaksi.date BETWEEN '$start' AND '$end' $filterWIlayah $nasabahWhereClause
                    ORDER BY date ASC;")->getResultArray();
                    $transaction['tts']  = $dataTts;
                }

                if ($get['jenis'] == 'penjualan-sampah') {
                    $dataTjs = $this->db->query("SELECT transaksi.id AS id_transaksi,transaksi.id_user,transaksi.jenis_transaksi,users.nama_lengkap,sampah.jenis AS jenis_sampah,jual_sampah.harga,jual_sampah.harga_pusat,jual_sampah.jumlah_kg,jual_sampah.jumlah_rp,transaksi.date 
                    FROM transaksi 
                    JOIN users        ON (transaksi.id_user = users.id)
                    JOIN jual_sampah  ON (transaksi.id = jual_sampah.id_transaksi) 
                    JOIN sampah       ON (jual_sampah.id_sampah = sampah.id) 
                    WHERE transaksi.date BETWEEN '$start' AND '$end'
                    ORDER BY date ASC;")->getResultArray();
                    $transaction['tjs']  = $dataTjs;
                }

                if ($get['jenis'] == 'konversi-saldo') {
                    $dataTps = $this->db->query("SELECT transaksi.id AS id_transaksi,transaksi.id_user,transaksi.jenis_transaksi,users.nama_lengkap,pindah_saldo.jumlah,pindah_saldo.harga_emas,pindah_saldo.hasil_konversi,transaksi.date 
                    FROM transaksi 
                    JOIN users        ON (transaksi.id_user = users.id)
                    JOIN pindah_saldo ON (transaksi.id = pindah_saldo.id_transaksi) 
                    JOIN wilayah      ON (wilayah.id_user = users.id)
                    WHERE transaksi.date BETWEEN '$start' AND '$end' $filterWIlayah $nasabahWhereClause
                    ORDER BY date ASC;")->getResultArray();
                    $transaction['tps']  = $dataTps;
                }

                $transaction['date'] = date('F, Y', $start);
                if (isset($get['idnasabah'])) {
                    $transaction['nasabah'] = $this->db->query("SELECT nama_lengkap,username,id FROM users WHERE id = ".$get['idnasabah'])->getResultArray();
                }

            } 
            else {
                // var_dump($get['year']);die;
                $query  = "SELECT transaksi.id,transaksi.date,
                (SELECT SUM(jumlah_kg) from setor_sampah WHERE setor_sampah.id_transaksi = transaksi.id) AS sampah_masuk,
                (SELECT SUM(jumlah_kg) from jual_sampah WHERE jual_sampah.id_transaksi = transaksi.id) AS sampah_keluar,
                (SELECT SUM(jumlah_rp) from setor_sampah WHERE setor_sampah.id_transaksi = transaksi.id) AS uang_masuk,
                (SELECT SUM(jumlah_tarik) from tarik_saldo WHERE tarik_saldo.id_transaksi = transaksi.id AND jenis_saldo = 'uang') AS uang_keluar,
                (SELECT SUM(hasil_konversi) from pindah_saldo WHERE pindah_saldo.id_transaksi = transaksi.id) AS emas_masuk,
                (SELECT SUM(jumlah_tarik) from tarik_saldo WHERE tarik_saldo.id_transaksi = transaksi.id AND jenis_saldo != 'uang') AS emas_keluar
                FROM transaksi";

                if (isset($get['provinsi'])) {
                    $query .= " JOIN wilayah ON (transaksi.id_user = wilayah.id_user)";
                }

                $year = null;
                if (isset($get['year'])) {
                    $year   = $get['year'];
                    $start  = (int)strtotime('01-01-'.$get['year']);
                    $end    = $start+(86400*365);
                    $query .= " WHERE transaksi.date BETWEEN '$start' AND '$end'";
                } 
                if (isset($get['kelurahan'])) {
                    $query .= " AND wilayah.kelurahan = '".$get['kelurahan']."'";
                }
    
                if (isset($get['kecamatan'])) {
                    $query .= " AND wilayah.kecamatan = '".$get['kecamatan']."'";
                }
    
                if (isset($get['kota'])) {
                    $query .= " AND wilayah.kota = '".$get['kota']."'";
                }
    
                if (isset($get['provinsi'])) {
                    $query .= " AND wilayah.provinsi = '".$get['provinsi']."'";
                }

                $query       .= " ORDER BY transaksi.date ASC;";
                $transaction = $this->db->query($query)->getResultArray();
                $transaction = $this->filterRekapData($transaction,$year);
            } 

            if (empty($transaction)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "transaction notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $transaction
                ];
            }
        } 
        catch (Exception $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function filterRekapData(array $data,?string $year = null): array
    {
        $transaction = [];
        // var_dump($data);die;

        foreach ($data as $d) {

            $newD = $this->removeNullRekap($d);
            // $id_transaksi = substr($newD['id'],0,3);

            if ($year) {
                $transaction[strtolower(date('F', $d['date']))][] = $newD;
            } 
            else {
                $transaction[strtolower(date('F-Y', $d['date']))][] = $newD;
            }
        }

        return $this->groupingEachRekap($transaction);
    }

    public function removeNullRekap(array $data): array
    {
        $newData = $data;

        if ($newData['sampah_masuk'] == null) {
            unset($newData['sampah_masuk']);
        }
        if ($newData['sampah_keluar'] == null) {
            unset($newData['sampah_keluar']);
        }
        if ($newData['uang_masuk'] == null) {
            unset($newData['uang_masuk']);
        }
        if ($newData['uang_keluar'] == null) {
            unset($newData['uang_keluar']);
        }
        if ($newData['emas_masuk'] == null) {
            unset($newData['emas_masuk']);
        }
        if ($newData['emas_keluar'] == null) {
            unset($newData['emas_keluar']);
        }

        return $newData;
    }

    public function groupingEachRekap(array $data): array
    {
        $newData = [];

        foreach ($data as $key => $value) {
            $date1           = '';
            $date2           = '';
            $totSampahMasuk  = 0;
            $totSampahKeluar = 0;
            $totUangMasuk    = 0;
            $totUangKeluar   = 0;
            $totEmasMasuk    = 0;
            $totEmasKeluar   = 0;
            
            foreach ($value as $v) {
                $date1        = date('m-Y', $v['date']);
                $date2        = date("F, Y", $v['date']);
                $id_transaksi = substr($v['id'],0,3);

                if ($id_transaksi == 'TSS') {
                    $totSampahMasuk = $totSampahMasuk + (float)$v['sampah_masuk'];
                    $totUangMasuk   = $totUangMasuk + (int)$v['uang_masuk'];
                }
                else if ($id_transaksi == 'TJS') {
                    $totSampahKeluar = $totSampahKeluar + (float)$v['sampah_keluar'];
                }
                else if ($id_transaksi == 'TPS') {
                    $totEmasMasuk = $totEmasMasuk + (float)$v['emas_masuk'];
                }
                else if ($id_transaksi == 'TTS') {
                    if (isset($v['uang_keluar'])) {
                        $totUangKeluar = $totUangKeluar + (int)$v['uang_keluar'];
                    } 
                    else if (isset($v['emas_keluar'])) {
                        $totEmasKeluar = $totEmasKeluar + (float)$v['emas_keluar'];
                    }
                }
            }

            $newData[$key] = [
                'date1'           => $date1,
                'date2'           => $date2,
                'totSampahMasuk'  => $totSampahMasuk,
                'totSampahKeluar' => $totSampahKeluar,
                'totUangMasuk'    => $totUangMasuk,
                'totUangKeluar'   => $totUangKeluar,
                'totEmasMasuk'    => $totEmasMasuk,
                'totEmasKeluar'   => $totEmasKeluar,
            ];
        }

        return $newData;
    }

    public function grafikSetorSampahPerbulan(array $get): array
    {
        try {
            $year = null;
            $transaction = [];
            
            $query  = "SELECT transaksi.id,transaksi.date,wilayah.provinsi,
            (SELECT SUM(jumlah_kg) from setor_sampah WHERE setor_sampah.id_transaksi = transaksi.id) AS sampah_masuk
            FROM transaksi
            JOIN wilayah ON (transaksi.id_user = wilayah.id_user)";

            $provinsi  = (isset($get['provinsi'])) ? $get['provinsi'] : "";
            $kota      = (isset($get['kota'])) ? $get['kota'] : "";
            $kecamatan = (isset($get['kecamatan'])) ? $get['kecamatan'] : "";
            $kelurahan = (isset($get['kelurahan'])) ? $get['kelurahan'] : "";

            if ($provinsi != "") {
                $query .= " WHERE wilayah.provinsi = '$provinsi'";
            }

            if ($kota != "") {
                $query .= " AND wilayah.kota = '$kota'";
            }

            if ($kecamatan != "") {
                $query .= " AND wilayah.kecamatan = '$kecamatan'";
            }
            
            if ($kelurahan != "") {
                $query .= " AND wilayah.kelurahan = '$kelurahan'";
            }

            if (isset($get['year'])) {
                $year   = $get['year'];
                $start  = (int)strtotime('01-01-'.$get['year']);
                $end    = $start+(86400*365);

                if (isset($get['provinsi'])) {
                    $query .= " AND transaksi.date BETWEEN '$start' AND '$end'";
                } 
                else {
                    $query .= " WHERE transaksi.date BETWEEN '$start' AND '$end'";
                }
            } 

            if (isset($get['idnasabah'])) {
                $idnasabah = $get['idnasabah'];
                $query .= "  AND transaksi.id_user='$idnasabah'";
            }

            $query      .= " ORDER BY transaksi.no ASC;";
            $transaction = $this->db->query($query)->getResultArray();
            $transaction = $this->removeNullGrafik($transaction);
            $transaction = $this->groupingGrafikPerbulan($transaction,$year);

            if (empty($transaction)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "transaction notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $transaction
                ];
            }
        } 
        catch (Exception $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function removeNullGrafik(array $data): array
    {
        $newData = [];

        foreach ($data as $d) {
            if ($d['sampah_masuk'] != NULL) {
                $newData[] = $d;
            }
        }

        return $newData;
    }

    public function groupingGrafikPerbulan(array $data,?string $year): array
    {
        $transaction = [];
        
        foreach ($data as $d) {
            if ($year) {
                $transaction[strtolower(date('F', $d['date']))][] = $d;
            } 
            else {
                $transaction[strtolower(date('F-Y', $d['date']))][] = $d;
            }
        }

        $newTransaction = [];

        foreach ($transaction as $key => $value) {
            $date1           = '';
            $date2           = '';
            $totSampahMasuk  = 0;
            
            foreach ($value as $v) {
                $date1        = date('m-Y', $v['date']);
                $date2        = date("F, Y", $v['date']);
                $id_transaksi = substr($v['id'],0,3);

                if ($id_transaksi == 'TSS') {
                    $totSampahMasuk = $totSampahMasuk + (float)$v['sampah_masuk'];
                }
            }

            $newTransaction[$key] = [
                'date1'           => $date1,
                'date2'           => $date2,
                'totSampahMasuk'  => $totSampahMasuk,
            ];
        }

        // var_dump($newTransaction);die;

        return $newTransaction;
    }

    public function grafikSetorSampahPerdaerah(array $get): array
    {
        try {
            if (isset($get['year'])) {
                $start  = (int)strtotime('01-01-'.$get['year']);
                $end    = $start+(86400*365);
            } 

            $provinsi  = (isset($get['provinsi'])) ? $get['provinsi'] : "";
            $kota      = (isset($get['kota'])) ? $get['kota'] : "";
            $kecamatan = (isset($get['kecamatan'])) ? $get['kecamatan'] : "";

            $label = "provinsi";
            $query = "SELECT sum(setor_sampah.jumlah_kg) AS jumlah_kg,wilayah.provinsi AS provinsi
            FROM transaksi
            JOIN setor_sampah ON (transaksi.id = setor_sampah.id_transaksi)
            JOIN wilayah      ON (transaksi.id_user = wilayah.id_user)
                WHERE transaksi.date BETWEEN '$start' AND '$end'
            GROUP BY wilayah.provinsi;";

            if ($provinsi != "") {
                $label = "kota";
                $query = "SELECT sum(setor_sampah.jumlah_kg) AS jumlah_kg,wilayah.kota
                FROM transaksi
                JOIN setor_sampah ON (transaksi.id = setor_sampah.id_transaksi)
                JOIN wilayah      ON (transaksi.id_user = wilayah.id_user)
                    WHERE transaksi.date BETWEEN '$start' AND '$end'
                    AND wilayah.provinsi = '$provinsi'
                GROUP BY wilayah.kota";
            }
            if ($kota != "") {
                $label = "kecamatan";
                $query = "SELECT sum(setor_sampah.jumlah_kg) AS jumlah_kg,wilayah.kecamatan
                FROM transaksi
                JOIN setor_sampah ON (transaksi.id = setor_sampah.id_transaksi)
                JOIN wilayah      ON (transaksi.id_user = wilayah.id_user)
                    WHERE transaksi.date BETWEEN '$start' AND '$end'
                    AND wilayah.provinsi = '$provinsi'
                    AND wilayah.kota     = '$kota'
                GROUP BY wilayah.kecamatan;";
            }
            if ($kecamatan != "") {
                $label = "kelurahan";
                $query = "SELECT sum(setor_sampah.jumlah_kg) AS jumlah_kg,wilayah.kelurahan
                FROM transaksi
                JOIN setor_sampah ON (transaksi.id = setor_sampah.id_transaksi)
                JOIN wilayah      ON (transaksi.id_user = wilayah.id_user)
                    WHERE transaksi.date BETWEEN '$start' AND '$end'
                    AND wilayah.provinsi   = '$provinsi'
                    AND wilayah.kota       = '$kota'
                    AND wilayah.kecamatan  = '$kecamatan'
                GROUP BY wilayah.kelurahan;";
            }

            $transaction = $this->db->query($query)->getResultArray();

            if (empty($transaction)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "transaction notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => [
                        'label' => $label,
                        'daerah'=> $transaction
                    ]
                ];
            }
        } 
        catch (Exception $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function deleteData(string $idtransaksi): array
    {
        try {
            $this->db->transBegin();
            $detailTs       = $this->getData(['id_transaksi'=>$idtransaksi],'');
            $detailTs       = $detailTs['data'];
            // var_dump($detailTs);die;
            $idnasabah      = $detailTs['id_user'];
            $code_transaksi = substr($idtransaksi,0,3);


            if ($code_transaksi == 'TSS') {
                $totalHarga      = 0;
                $queryJmlSampah  = '';

                foreach ($detailTs['barang'] as $t) {
                    $totalHarga      = $totalHarga+$t['jumlah_rp'];
                    
                    // $queryJmlSampah .= "UPDATE sampah SET jumlah=jumlah-".$t['jumlah_kg']." WHERE jenis = '".$t['jenis']."';"; // postgreSql

                    $this->db->query("UPDATE sampah SET jumlah=jumlah-".$t['jumlah_kg']." WHERE jenis = '".$t['jenis']."';");
                }

                // update jumlah sampah
                // $this->db->query($queryJmlSampah); // postgreSql
                
                // update saldo nasabah
                $this->db->query("UPDATE dompet SET uang=uang-$totalHarga WHERE id_user='$idnasabah';");
            } 
            else if ($code_transaksi == 'TTS') {
                $jenisSaldo  = ($detailTs['jenis_saldo'] !== 'uang') ? 'emas' : 'uang';
                $jumlahTarik = $detailTs['jumlah_tarik'];
                $privilege   = $detailTs['privilege'];

                // update saldo nasabah
                if ($privilege == "nasabah") {
                    $this->db->query("UPDATE dompet SET $jenisSaldo=$jenisSaldo+$jumlahTarik WHERE id_user='$idnasabah';");
                } 
                else {
                    $this->db->query("UPDATE dompet SET uang=uang+$jumlahTarik WHERE id_user IS NULL;");
                }
            }
            else if ($code_transaksi == 'TPS') {
                $jmlPindah     = $detailTs['jumlah'];
                $hasilKonversi = $detailTs['hasil_konversi'];
                
                // update saldo nasabah
                $this->db->query("UPDATE dompet SET uang=uang+$jmlPindah,emas=emas-$hasilKonversi WHERE id_user='$idnasabah';");
            }
            else if ($code_transaksi == 'TJS') {
                $totalHarga      = 0;
                $queryJmlSampah  = '';

                foreach ($detailTs['barang'] as $t) {
                    $totalHarga      = $totalHarga+($t['jumlah_rp']-$t['harga_nasabah']);
                    // $queryJmlSampah .= "UPDATE sampah SET jumlah=jumlah+".$t['jumlah_kg']." WHERE jenis = '".$t['jenis']."';"; // Postgre
                    $this->db->query("UPDATE sampah SET jumlah=jumlah+".$t['jumlah_kg']." WHERE jenis = '".$t['jenis']."';");
                }
                
                // update jumlah sampah
                // $this->db->query($queryJmlSampah); // Postgre

                // update saldo bst
                $this->db->query("UPDATE dompet SET uang=uang-$totalHarga WHERE id_user IS NULL;");
            } 

            $this->db->table($this->table)->where('id', $idtransaksi)->delete();
            $transStatus = $this->db->transStatus();

            if ($transStatus) {
                $this->db->transCommit();
            } 
            else {
                $this->db->transRollback();
            }

            return [
                'status'   => ($transStatus) ? 201   : 500,
                'error'    => ($transStatus) ? false : true,
                'messages' => ($transStatus) ? "delete transaksi with id $idtransaksi is success" : "delete transaksi with id $idtransaksi is failed",
            ];  
        } 
        catch (Exception $e) {
            $this->db->transRollback();
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

}
