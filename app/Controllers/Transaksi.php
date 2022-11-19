<?php

namespace App\Controllers;
use App\Controllers\BaseController;

use App\Models\TransaksiModel;
use App\Models\UserModel;

class Transaksi extends BaseController
{
    public $transaksiModel;

	public function __construct()
    {
        $this->transaksiModel = new TransaksiModel;
    }

    public function cetakTransaksi(string $id)
    {
        $token = (isset($_COOKIE['token'])) ? $_COOKIE['token'] : null;

        if ($this->request->getGet('token')) {
            $token = $this->request->getGet('token');
        }
        else{
            $token = (isset($_COOKIE['token'])) ? $_COOKIE['token'] : null;
        }

        $result    = $this->checkToken($token, false);
        $privilege = (isset($result['data']['privilege'])) ? $result['data']['privilege'] : null;

        if ($token == null || $result['success'] == false || !in_array($privilege,['nasabah','admin','superadmin'])) {
            setcookie('token', null, -1, '/');
            unset($_COOKIE['token']);
            return redirect()->to(base_url().'/login');
        }

        $transaksiModel = new TransaksiModel;
        $dbresponse     = $transaksiModel->getData(['id_transaksi' => $id],'');
        
        if ($dbresponse['error'] == true) {
            return redirect()->to(base_url().'/login');
        }
        
        $mpdf           = new \Mpdf\Mpdf();
        $jenisTransaksi = $dbresponse['data']['jenis_transaksi'];
        
        if ($jenisTransaksi == 'penarikan saldo') {
            $jenisSaldo = ($dbresponse['data']['jenis_saldo'] == 'uang')? 'uang' : 'emas '.$dbresponse['data']['jenis_saldo'];
            $keterangan = $dbresponse['data']['description'];
            $jumlah     = ($jenisSaldo == 'uang')? 'Rp '.number_format($dbresponse['data']['jumlah_tarik'] , 0, ',', ',') : round((float)$dbresponse['data']['jumlah_tarik'], 4).' gram';
            $displayKeterangan = ($keterangan==null) ? 'none' : 'block';

            $result = "<div style='padding: 20px;width: 100%;background-color: rgb(131, 146, 171);border-radius: 6px;'>
                <table>
                    <tr>
                        <td style='font-family: sans;'>
                            <h4>Jenis saldo&nbsp;</h4>
                        </td>
                        <td style='font-family: sans;'>
                            <h4>
                            : &nbsp;&nbsp;$jenisSaldo
                            </h4>
                        </td>
                    </tr>
                    <tr>
                        <td style='font-family: sans;'>
                            <h4>Jumlah</h4>
                        </td>
                        <td style='font-family: sans;'>
                            <h4>: &nbsp;&nbsp;$jumlah</h4>
                        </td>
                    </tr>
                </table>
                <div style='display:$displayKeterangan;'>
                    <div style='border-bottom: 1px solid #67748e;margin-top:20px;margin-bottom:20px';></div>
                    <div style='text-align:center;'>
                        <i>$keterangan</i>
                    </div>
                </div>
            </div>";
        } 
        if ($jenisTransaksi == 'konversi saldo') {
            $result = "<div style='padding: 20px;width: 100%;background-color: rgb(131, 146, 171);border-radius: 6px;'>
                <table>
                    <tr>
                        <td style='font-size: 2em;font-family: sans;'>
                            Jumlah&nbsp;&nbsp;&nbsp;
                        </td>
                        <td style='font-size: 2em;font-family: sans;'>
                            : Rp ".number_format($dbresponse['data']['jumlah'] , 0, ',', ',')."
                        </td>
                    </tr>
                    <tr>
                        <td style='font-size: 2em;font-family: sans;'>
                            Harga emas&nbsp;&nbsp;&nbsp;
                        </td>
                        <td style='font-size: 2em;font-family: sans;'>
                            : Rp ".number_format($dbresponse['data']['harga_emas'] , 0, ',', ',')."
                        </td>
                    </tr>
                    <tr>
                        <td style='font-size: 2em;font-family: sans;'>
                            Hasil konversi&nbsp;&nbsp;&nbsp;
                        </td>
                        <td style='font-size: 2em;font-family: sans;'>
                            : ".round((float)$dbresponse['data']['hasil_konversi'], 4)." gram
                        </td>
                    </tr>
                </table>
            </div>";
        } 
        if ($jenisTransaksi == 'penyetoran sampah') {
            $barang = $dbresponse['data']['barang'];
            $trBody = "";
            $no     = 1;
            $totalRp= 0;
            $totalKg= 0;

            foreach ($barang as $key) {
                $totalRp += $key['jumlah_rp'];
                $totalKg += $key['jumlah_kg'];
                $bg       = ($no % 2 == 0) ? "style='background: rgb(230, 230, 230);'" : "style='background: rgb(255, 255, 255);'";

                $trBody .= "<tr $bg>
                    <td style='font-family: sans;text-align: center;'>
                        ".$no++."
                    </td>
                    <td style='font-family: sans;text-align: center;'>
                        ".$key['jenis']."
                    </td>
                    <td style='font-family: sans;text-align: center;'>
                        ".round($key['jumlah_kg'],2)."
                    </td>
                    <td style='font-family: sans;text-align: right;'>
                        Rp ".number_format($key['jumlah_rp'] , 2, '.', ',')."
                    </td>
                </tr>";
            }
            
            $result = "<table border='0' width='100%' cellpadding='5'>
                <thead>
                    <tr>
                        <th style='border: 1px solid black;font-family: sans;'>
                            #
                        </th>
                        <th style='border: 1px solid black;font-family: sans;'>
                            Jenis sampah
                        </th>
                        <th style='border: 1px solid black;font-family: sans;'>
                            Kg
                        </th>
                        <th style='border: 1px solid black;font-family: sans;'>
                            Harga
                        </th>
                    </tr>
                <thead>
                <tbody>
                    $trBody
                    <tr style='background: rgb(230, 230, 230);'>
                        <th style='font-family: sans;text-align: right;' colspan='2'>
                            Total :
                        </th>
                        <td style='font-family: sans;text-align: center;'>
                            $totalKg
                        </td>
                        <td style='font-family: sans;text-align: right;'>
                            Rp ".number_format($totalRp , 2, '.', ',')."
                        </td>
                    </tr>
                </tbody>
            </table>";
        }
        if ($jenisTransaksi == 'penjualan sampah') {
            $barang = $dbresponse['data']['barang'];
            $trBody = "";
            $no     = 1;
            $totalKg   = 0;
            $totalHJual= 0;
            $totalHBeli= 0;
            $totalSelisih= 0;

            foreach ($barang as $key) {
                $totalKg    += $key['jumlah_kg'];
                $totalHJual += $key['jumlah_rp'];
                $totalHBeli += $key['harga_nasabah'];

                $selisih       = (float)$key['jumlah_rp'] - (float)$key['harga_nasabah'];
                $totalSelisih += $selisih;

                $bg       = ($no % 2 == 0) ? "style='background: rgb(230, 230, 230);'" : "style='background: rgb(255, 255, 255);'";

                $trBody .= "<tr $bg>
                    <td style='font-family: sans;text-align: center;'>
                        ".$no++."
                    </td>
                    <td style='font-family: sans;text-align: center;'>
                        ".$key['jenis']."
                    </td>
                    <td style='font-family: sans;text-align: center;'>
                        ".round($key['jumlah_kg'],2)."
                    </td>
                    <td style='font-family: sans;text-align: right;'>
                        Rp ".number_format($key['jumlah_rp'] , 2, '.', ',')."
                    </td>
                    <td style='font-family: sans;text-align: right;'>
                        Rp ".number_format($key['harga_nasabah'] , 2, '.', ',')."
                    </td>
                    <td style='font-family: sans;text-align: right;'>
                        Rp ".number_format($selisih , 2, '.', ',')."
                    </td>
                </tr>";
            }
            
            $result = "<table border='0' width='100%' cellpadding='5'>
                <thead>
                    <tr>
                        <th style='border: 1px solid black;font-family: sans;'>
                            #
                        </th>
                        <th style='border: 1px solid black;font-family: sans;'>
                            Jenis sampah
                        </th>
                        <th style='border: 1px solid black;font-family: sans;'>
                            Kg
                        </th>
                        <th style='border: 1px solid black;font-family: sans;'>
                            Harga Jual
                        </th>
                        <th style='border: 1px solid black;font-family: sans;'>
                            Harga Beli
                        </th>
                        <th style='border: 1px solid black;font-family: sans;'>
                            Selisih
                        </th>
                    </tr>
                <thead>
                <tbody>
                    $trBody
                    <tr style='background: rgb(230, 230, 230);'>
                        <th style='font-family: sans;text-align: center;' colspan='2'>
                            Total
                        </th>
                        <td style='font-family: sans;text-align: center;'>
                            ".round($totalKg,2)."
                        </td>
                        <td style='font-family: sans;text-align: right;'>
                            Rp ".number_format($totalHJual , 2, '.', ',')."
                        </td>
                        <td style='font-family: sans;text-align: right;'>
                            Rp ".number_format($totalHBeli , 2, '.', ',')."
                        </td>
                        <td style='font-family: sans;text-align: right;'>
                            Rp ".number_format($totalSelisih , 2, '.', ',')."
                        </td>
                    </tr>
                </tbody>
            </table>";
        }

        $userType = ($jenisTransaksi == 'penjualan sampah')? 'ID.ADMIN' : 'ID.NASABAH';

        $mpdf->WriteHTML("
        <!DOCTYPE html>
        <html lang='en'>
        
        <head>
            <meta charset='utf-8'>
            <title>bsbl | cetak transaksi</title>
        </head>
        
        <body>
            <div style='border-bottom: 2px solid black;padding-bottom: 20px;'>
                <table border='0' width='100%'>
                   <tr>
                        <th style='text-align: left;'>
                            <img src='".base_url()."/assets/images/banksampah-logo.png' style='width: 160px;'>
                        </th>
                        <th style='text-align: right;'>
                            <h1 style='font-size: 2em;'>
                                BUKTI TRANSAKSI
                            </h1>
                            <span style='font-size: 1.2em;font-style: italic;font-family: sans;'>
                                $jenisTransaksi
                            </span>
                        </th>
                    </tr>';
                </table>
            </div>

            <div style='padding-top: 30px;margin-bottom: 40px;'>
                <table>
                    <tr>
                        <td style='font-size: 1.4em;font-family: sans;'>
                            TANGGAL
                        </td>
                        <td style='font-size: 1.4em;font-family: sans;'>
                            :&nbsp;&nbsp;&nbsp; ".date("d/m/Y h:i A",$dbresponse['data']['date'])."
                        </td>
                    </tr>
                    <tr>
                        <td style='font-size: 1.4em;font-family: sans;'>
                            NAMA
                        </td>
                        <td style='font-size: 1.4em;font-family: sans;text-transform: uppercase;'>
                            :&nbsp;&nbsp;&nbsp; ".$dbresponse['data']['nama_lengkap']."
                        </td>
                    </tr>
                    <tr>
                        <td style='font-size: 1.4em;font-family: sans;'>
                            $userType
                        </td>
                        <td style='font-size: 1.4em;font-family: sans;'>
                            :&nbsp;&nbsp;&nbsp; ".$dbresponse['data']['id_user']."
                        </td>
                    </tr>
                    <tr>
                        <td style='font-size: 1.4em;font-family: sans;'>
                            ID.TRANSAKSI&nbsp;
                        </td>
                        <td style='font-size: 1.4em;font-family: sans;'>
                            :&nbsp;&nbsp;&nbsp; ".$dbresponse['data']['id_transaksi']."
                        </td>
                    </tr>
                </table>
            </div>

            ".$result."
        </body>
        
        </html>");

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('transaksi#'.$id.".pdf", 'I');
    }

    /**
     * Cetak Penimbangan Sampah
     */
    public function cetakLaporanPenimbanganSampah()
    {
        $token     = (isset($_COOKIE['token'])) ? $_COOKIE['token'] : null;
        $result    = $this->checkToken($token, false);
        $privilege = (isset($result['data']['privilege'])) ? $result['data']['privilege'] : null;

        if ($token == null || $result['success'] == false || !in_array($privilege,['superadmin','admin'])) {
            setcookie('token', null, -1, '/');
            unset($_COOKIE['token']);

            if ($privilege == 'nasabah') {
                return redirect()->to(base_url().'/login');
            }
            else{
                return redirect()->to(base_url().'/login/admin');
            }
        }

        $transaksiModel = new TransaksiModel;

        $judulFile  = "";
        $jenis      = "penimbangan-sampah";
        $headDate   = "";
        $keteranganFileTr = "";
        $keteranganFileTable = "";

        $wilayah    = $this->request->getGet('wilayah');
        $date       = $this->request->getGet('date');
        $datePatern = '/^[0-1][0-9][-][2-9][0-9][0-9][0-9]$/';
        $start      = $this->request->getGet('start');
        $end        = $this->request->getGet('end');
        $startEndPatern = '/^[0-3][0-9][-][0-1][0-9][-][2-9][0-9][0-9][0-9]$/';

        if ($this->request->getGet()) {
            if ($date != null) {
                if ($date == "") {
                    return redirect()->to(base_url().'/admin/transaksi');
                }
                else if(preg_match($datePatern, $date) == false) {
                    return redirect()->to(base_url().'/admin/transaksi');
                }

                $judulFile  = $jenis.'#'.date("Y-F", strtotime("01-".$date)).".pdf";
                $headDate   = "<span  style='font-size: 1em;font-family: sans;'>
                    ".date("F, Y", strtotime("01-".$date))."
                </span>";

                if ($wilayah == "true" || $start != null && $end != null) {
                    $headDate = "";
                    $keteranganFileTr .= "<tr>
                        <td>Tanggal</td>
                        <td>: ".date("F, Y", strtotime("01-".$date))."</td>
                    </tr>";
                }
            }
            if ($start != null && $end != null) {
                if(preg_match($startEndPatern, $start) == false || preg_match($startEndPatern, $end) == false) {
                    return redirect()->to(base_url().'/admin/transaksi');
                }

                $judulFile  = $jenis.'#'.date("Y_F_d", strtotime($start))."-".date("d_F_Y", strtotime($end)).".pdf";
                $keteranganFileTr .= "<tr>
                    <td>Tangal dimulai</td>
                    <td>: ".date("d F, Y", strtotime($start))."</td>
                </tr>
                <tr>
                    <td>Tangal berakhir</td>
                    <td>: ".date("d F, Y", strtotime($end))."</td>
                </tr>";
            }
            if ($wilayah == "true") {
                $strWilayah = "";
                $strWilayahFileName = "";

                foreach ($this->request->getGet() as $key => $value) {
                    if (in_array($key,['provinsi','kota','kecamatan','kelurahan'])) {
                        $strWilayah .= ucfirst($value).', ';
                        $strWilayahFileName .= ucfirst($value).'_';
                    }
                }

                $judulFile  = $jenis.'#'.trim($strWilayahFileName,"_").".pdf";
                $keteranganFileTr .= "<tr>
                    <td>Wilayah</td>
                    <td>: ".trim($strWilayah,", ")."</td>
                </tr>";
            }

            $keteranganFileTable = "<table style='margin-top: 20px;'>
                $keteranganFileTr
            </table>";
        }
        else {
            return redirect()->to(base_url().'/admin/transaksi');
        }

        $get = $this->request->getGet();
        $get['jenis'] = $jenis;
        
        $dbresponse = $transaksiModel->rekapData($get);
        $data       = $dbresponse["data"];
        
        if (isset($get['idnasabah'])) {
            if (count($data['nasabah']) == 0) {
               return redirect()->to(base_url().'/admin/listnasabah');
            } 
            else {
                $nasabah      = $data['nasabah'][0];
                $keteranganFileTr .= "<tr>
                    <td>Nama lengkap&nbsp;</td>
                    <td style='text-transform:capitalize'>:&nbsp;&nbsp;".$nasabah['nama_lengkap']."</td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td>:&nbsp;&nbsp;".$nasabah['username']."</td>
                </tr>
                <tr>
                    <td>ID nasabah</td>
                    <td>:&nbsp;&nbsp;".$nasabah['id']."</td>
                </tr>";

                $keteranganFileTable = "<table style='margin-top: 20px;'>
                    $keteranganFileTr
                </table>";
            }
        }

        /*
        / Slip Setor Sampah
        */
        $noSlipMaker   = 1;
        $thTambahan    = "";
        $trSlipSetoran = "";
        $dataSlipMaker = [];
        $totKgSlip   = 0;
        $totUangSlip = 0;
        $tdHargaPengepul = "";
        $tdHargaNasabah  = "";
        $thHargaPengepul = "";
        $thHargaNasabah  = "";

        foreach ($data['tss'] as $value) {
            $thTambahan = "<th></th>";
            if (!isset($dataSlipMaker[$value['id_sampah']])) {
                $dataSlipMaker[$value['id_sampah']] = [
                    'jenis_sampah' => $value['jenis_sampah'],
                    'harga'        => $value['harga'],
                    'harga_pusat'  => $value['harga_pusat'],
                    'jumlah_kg'    => floatval($value['jumlah_kg']),
                    'jumlah_rp'    => floatval($value['jumlah_rp']),
                ];
            } else {
                $dataSlipMaker[$value['id_sampah']] = [
                    'jenis_sampah' => $value['jenis_sampah'],
                    'harga'        => $value['harga'],
                    'harga_pusat'  => $value['harga_pusat'],
                    'jumlah_kg'    => floatval($value['jumlah_kg']) + $dataSlipMaker[$value['id_sampah']]['jumlah_kg'],
                    'jumlah_rp'    => floatval($value['jumlah_rp']) + $dataSlipMaker[$value['id_sampah']]['jumlah_rp'],
                ];
            }
            
        }

        foreach ($dataSlipMaker as $value) {
            if ($value['harga_pusat'] == 0) {
                $hargaNasabah = intval($value["jumlah_rp"])/floatval($value["jumlah_kg"]);
                
                $hargaPengepul = intval($value["jumlah_rp"])/floatval($value["jumlah_kg"]);
                $hargaPengepul = $hargaPengepul+((10/100)*$hargaPengepul);
            }
            else{
                $hargaNasabah = (int)$value['harga'];
                $hargaPengepul = (int)$value['harga_pusat'];
            }

            $jumlahRp    = !isset($get['idnasabah']) ? $hargaPengepul*(float)$value['jumlah_kg'] : $hargaNasabah*(float)$value['jumlah_kg'];
            $totKgSlip   = $totKgSlip+(float)$value['jumlah_kg'];
            $totUangSlip = $totUangSlip+$jumlahRp;

            if (!isset($get['idnasabah'])) {
                $tdHargaPengepul = "<td style='text-align: center;font-size: 0.7em;font-family: sans;'>
                    ".number_format($hargaPengepul , 2, '.', ',')."
                </td>";
                $thHargaPengepul = "<th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>harga satuan pengepul</th>";
            }
            else {
                $tdHargaNasabah = "<td style='text-align: center;font-size: 0.7em;font-family: sans;'>
                    ".number_format($hargaNasabah , 2, '.', ',')."
                </td>";
                $thHargaNasabah = "<th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>harga satuan nasabah</th>";
            }

            $bg = ($noSlipMaker % 2 == 0) ? "style='background: rgb(230, 230, 230);'" : "style='background: rgb(255, 255, 255);'";

            $trSlipSetoran .= "<tr $bg>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$noSlipMaker++."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$value["jenis_sampah"]."
                </td>
                <td style='text-align: left;font-size: 0.7em;font-family: sans;'>
                    ".$value["jumlah_kg"]."
                </td>
                $tdHargaPengepul
                $tdHargaNasabah
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".number_format($jumlahRp , 2, '.', ',')."
                </td>
            </tr>";
        }

        $trSlipSetoran .= "<tr style='background: rgb(230, 230, 230);'>
            <th>
                
            </th>
            <th style='text-align: center;font-size: 0.8em;font-family: sans;'>
                jumlah
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;'>
                ".$totKgSlip."
            </th>
            $thTambahan
            <th style='text-align: left;font-size: 0.8em;font-family: sans;'>
                ".number_format($totUangSlip , 2, '.', ',')."
            </th>
        </tr>";

        /*
        / Saldo Masuk
        */
        $noSaldoMasukMaker   = 1;
        $trSaldoMasukSetoran = "";
        $dataSaldoMasukMaker = [];
        $totKgMasuk   = 0;
        $totUangMasuk = 0;

        foreach ($data['tss'] as $value) {
            if (!isset($dataSaldoMasukMaker[$value['id_user']])) {
                $dataSaldoMasukMaker[$value['id_user']] = [
                    'id_user'      => $value['id_user'],
                    'nama_lengkap' => $value['nama_lengkap'],
                    'jumlah_kg'    => floatval($value['jumlah_kg']),
                    'jumlah_rp'    => floatval($value['jumlah_rp']),
                ];
            } else {
                $dataSaldoMasukMaker[$value['id_user']] = [
                    'id_user'      => $value['id_user'],
                    'nama_lengkap' => $value['nama_lengkap'],
                    'jumlah_kg'    => floatval($value['jumlah_kg']) + $dataSaldoMasukMaker[$value['id_user']]['jumlah_kg'],
                    'jumlah_rp'    => floatval($value['jumlah_rp']) + $dataSaldoMasukMaker[$value['id_user']]['jumlah_rp'],
                ];
            }
            
        }

        foreach ($dataSaldoMasukMaker as $value) {
            $totKgMasuk   = $totKgMasuk+(float)$value['jumlah_kg'];
            $totUangMasuk = $totUangMasuk+(float)$value['jumlah_rp'];

            $bg = ($noSaldoMasukMaker % 2 == 0) ? "style='background: rgb(230, 230, 230);'" : "style='background: rgb(255, 255, 255);'";

            $trSaldoMasukSetoran .= "<tr $bg>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$noSaldoMasukMaker++."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$value["nama_lengkap"]."
                </td>
                <td style='text-align: left;font-size: 0.7em;font-family: sans;'>
                    ".$value["id_user"]."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".number_format($value["jumlah_rp"] , 2, '.', ',')."
                </td>
                <td style='text-align: left;font-size: 0.7em;font-family: sans;'>
                    ".$value["jumlah_kg"]."
                </td>
            </tr>";
        }

        $trSaldoMasukSetoran .= "<tr style='background: rgb(230, 230, 230);'>
            <th colspan='3' style='text-align: center;font-size: 0.8em;font-family: sans;'>
                jumlah
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;'>
                ".number_format($totUangMasuk , 2, '.', ',')."
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;'>
                ".$totKgMasuk."
            </th>
        </tr>";

        /*
        / Setor Sampah
        */
        $tss   = $data['tss'];
        $trTss = "";
        $noTss = 1;
        $totKgSetor   = 0;
        $totUangSetor = 0;
        $colspan      = 6;
        $idTransaksiNow  = "";
        $idTransaksiNext = "";

        foreach ($tss as $key) {
            $totKgSetor   = $totKgSetor+(float)$key['jumlah_kg'];
            $totUangSetor = $totUangSetor+(float)$key['jumlah_rp'];


            if ($idTransaksiNow == $key['id_transaksi']) {
                $idTransaksiNext = "-- || --";
            }
            else{
                $idTransaksiNow  = $key['id_transaksi'];
                $idTransaksiNext = $key['id_transaksi'];
            }

            $bg     = ($noTss % 2 == 0) ? "style='background: rgb(230, 230, 230);'" : "style='background: rgb(255, 255, 255);'";

            $trTss .= "<tr $bg>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$noTss++."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".date("d/m/Y", $key['date'])."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".$idTransaksiNext."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$key['id_user']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$key['nama_lengkap']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$key['jenis_sampah']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                    ".round((float)$key['jumlah_kg'],2)."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                    ".number_format($key['jumlah_rp'] , 2, '.', ',')."
                </td>
            </tr>";
        }

        $trTss .= "<tr style='background: rgb(230, 230, 230);'>
            <th colspan='$colspan' style='text-align: center;font-size: 0.8em;font-family: sans;'>
                total
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: right;'>
                ".$totKgSetor."
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: right;'>
                ".number_format($totUangSetor , 2, '.', ',')."
            </th>
        </tr>";

        $mpdf = new \Mpdf\Mpdf();
        
        $mpdf->WriteHTML("
        <!DOCTYPE html>
        <html lang='en'>
        
        <head>
            <meta charset='utf-8'>
            <title>bst | penimbangan sampah</title>
        </head>
        
        <body>
            <div style='border-bottom: 2px solid black;padding-bottom: 0px;'>
                <table border='0' width='100%'>
                   <tr>
                        <th style='text-align: left;'>
                            <img src='".base_url()."/assets/images/banksampah-logo.png' style='width: 160px;'>
                        </th>
                        <th style='text-align: right;'>
                            <h1  style='font-size: 2em;'>
                                PENIMBANGAN SAMPAH
                            </h1>
                            $headDate
                        </th>
                    </tr>';
                </table>
            </div>

            $keteranganFileTable

            <table border='0' width='100%' cellpadding='5'>
                <caption style='text-align:left;font-family:sans;caption-side:top;margin-top:40px;margin-bottom:10px;font-size: 1em;'>
                    # Slip Setoran Penimbangan
                </caption>
                <thead>
                    <tr style='font-size: 0.8em;'>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>No</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Jenis Sampah</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Jumlah (kg)</th>
                        $thHargaPengepul
                        $thHargaNasabah
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Jumlah (rp)</th>
                    </tr>
                <thead>
                <tbody>
                    $trSlipSetoran
                </tbody>
            </table>

            <div style='display:".(!isset($get['idnasabah']) ? "block; " : "none;")."'>
                <table border='0' width='100%' cellpadding='5' >
                    <caption style='text-align:left;font-family:sans;caption-side:top;margin-top:40px;margin-bottom:10px;font-size: 1em;'>
                        # Rekap Saldo Masuk
                    </caption>
                    <thead>
                        <tr style='font-size: 0.8em;'>
                            <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>No</th>
                            <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Nama Nasabah</th>
                            <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>No Rekening</th>
                            <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Hasil Penimbangan <br> (rp)</th>
                            <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Hasil Penimbangan <br> (kg)</th>
                        </tr>
                    <thead>
                    <tbody>
                        $trSaldoMasukSetoran
                    </tbody>
                </table>
            </div>

            <table border='0' width='100%' cellpadding='5'>
                <caption style='text-align:left;font-family:sans;caption-side:top;margin-top:40px;margin-bottom:10px;font-size: 1em;'>
                    # Daftar Transaksi Penimbangan Sampah
                </caption>
                <thead>
                    <tr style='font-size: 0.8em;'>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>#</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Tanggal</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>ID Transaksi</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>no rekening</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Nama Nasabah</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Jenis sampah</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Jumlah(Kg)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Jumlah(Rp)</th>
                    </tr>
                <thead>
                <tbody>
                    $trTss
                </tbody>
            </table>
        </body>
        
        </html>");

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output($judulFile, 'I');
    }

    /**
     * Cetak Penjualan Sampah
     */

    public function cetakLaporanPenjualanSampah()
    {
        $token     = (isset($_COOKIE['token'])) ? $_COOKIE['token'] : null;
        $result    = $this->checkToken($token, false);
        $privilege = (isset($result['data']['privilege'])) ? $result['data']['privilege'] : null;

        if ($token == null || $result['success'] == false || !in_array($privilege,['superadmin','admin'])) {
            setcookie('token', null, -1, '/');
            unset($_COOKIE['token']);

            if ($privilege == 'nasabah') {
                return redirect()->to(base_url().'/login');
            }
            else{
                return redirect()->to(base_url().'/login/admin');
            }
        }

        $transaksiModel = new TransaksiModel;

        $judulFile  = "";
        $jenis      = "penjualan-sampah";
        $headDate   = "";
        $keteranganFileTr = "";
        $keteranganFileTable = "";

        $wilayah    = $this->request->getGet('wilayah');
        $date       = $this->request->getGet('date');
        $datePatern = '/^[0-1][0-9][-][2-9][0-9][0-9][0-9]$/';
        $start      = $this->request->getGet('start');
        $end        = $this->request->getGet('end');
        $startEndPatern = '/^[0-3][0-9][-][0-1][0-9][-][2-9][0-9][0-9][0-9]$/';

        if ($this->request->getGet()) {
            if ($date != null) {
                if ($date == "") {
                    return redirect()->to(base_url().'/admin/transaksi');
                }
                else if(preg_match($datePatern, $date) == false) {
                    return redirect()->to(base_url().'/admin/transaksi');
                }

                $judulFile  = $jenis.'#'.date("Y-F", strtotime("01-".$date)).".pdf";
                $headDate   = "<span  style='font-size: 1em;font-family: sans;'>
                    ".date("F, Y", strtotime("01-".$date))."
                </span>";

                if ($start != null && $end != null) {
                    $headDate = "";
                    $keteranganFileTr .= "<tr>
                        <td>Tanggal</td>
                        <td>: ".date("F, Y", strtotime("01-".$date))."</td>
                    </tr>";
                }
            }
            if ($start != null && $end != null) {
                if(preg_match($startEndPatern, $start) == false || preg_match($startEndPatern, $end) == false) {
                    return redirect()->to(base_url().'/admin/transaksi');
                }

                $judulFile  = $jenis.'#'.date("Y_F_d", strtotime($start))."-".date("d_F_Y", strtotime($end)).".pdf";
                $keteranganFileTr .= "<tr>
                    <td>Tangal dimulai</td>
                    <td>: ".date("d F, Y", strtotime($start))."</td>
                </tr>
                <tr>
                    <td>Tangal berakhir</td>
                    <td>: ".date("d F, Y", strtotime($end))."</td>
                </tr>";
            }

            $keteranganFileTable = "<table style='margin-top: 20px;'>
                $keteranganFileTr
            </table>";
        }
        else {
            return redirect()->to(base_url().'/admin/transaksi');
        }

        $get = $this->request->getGet();
        $get['jenis'] = $jenis;

        if ($wilayah == "true") {
            foreach ($this->request->getGet() as $key => $value) {
                if (in_array($key,['provinsi','kota','kecamatan','kelurahan'])) {
                    unset($get[$key]);
                }
            }
        }
        
        $dbresponse = $transaksiModel->rekapData($get);
        $data       = $dbresponse["data"];

        /*
        / Jual Sampah
        */
        $tjs   = $data['tjs'];
        $trTjs = "";
        $noTjs = 1;
        $totKgSetor   = 0;
        $totUangSetor = 0;
        $colspan      = 5;
        $idTransaksiNow  = "";
        $idTransaksiNext = "";

        foreach ($tjs as $value) {
            if ($value['harga_pusat'] == 0) {
                $hargaPengepul = intval($value["jumlah_rp"])/floatval($value["jumlah_kg"]);
                $hargaPengepul = $hargaPengepul+((10/100)*$hargaPengepul);
            }
            else{
                $hargaPengepul = (int)$value['harga_pusat'];
            }

            $totKgSetor   = $totKgSetor+(float)$value['jumlah_kg'];
            $totUangSetor = $totUangSetor+(float)$value['jumlah_rp'];

            if ($idTransaksiNow == $value['id_transaksi']) {
                $idTransaksiNext = "-- || --";
            }
            else{
                $idTransaksiNow  = $value['id_transaksi'];
                $idTransaksiNext = $value['id_transaksi'];
            }

            $bg     = ($noTjs % 2 == 0) ? "style='background: rgb(230, 230, 230);'" : "style='background: rgb(255, 255, 255);'";

            $trTjs .= "<tr $bg>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$noTjs++."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".date("d/m/Y", $value['date'])."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".$idTransaksiNext."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$value['nama_lengkap']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$value['jenis_sampah']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".number_format($hargaPengepul , 2, '.', ',')."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                    ".round((float)$value['jumlah_kg'],2)."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                    ".number_format($value['jumlah_rp'] , 2, '.', ',')."
                </td>
            </tr>";
        }

        $trTjs .= "<tr style='background: rgb(230, 230, 230);'>
            <th colspan='$colspan' style='text-align: center;font-size: 0.8em;font-family: sans;'>
                total
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: right;'>
                
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: right;'>
                ".$totKgSetor."
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: right;'>
                ".number_format($totUangSetor , 2, '.', ',')."
            </th>
        </tr>";

        $mpdf = new \Mpdf\Mpdf();
        
        $mpdf->WriteHTML("
        <!DOCTYPE html>
        <html lang='en'>
        
        <head>
            <meta charset='utf-8'>
            <title>bsbl | penjualan sampah</title>
        </head>
        
        <body>
            <div style='border-bottom: 2px solid black;padding-bottom: 0px;'>
                <table border='0' width='100%'>
                   <tr>
                        <th style='text-align: left;'>
                            <img src='".base_url()."/assets/images/banksampah-logo.png' style='width: 160px;'>
                        </th>
                        <th style='text-align: right;'>
                            <h1  style='font-size: 2em;'>
                                PENJUALAN SAMPAH
                            </h1>
                            $headDate
                        </th>
                    </tr>';
                </table>
            </div>

            $keteranganFileTable

            <table border='0' width='100%' cellpadding='5'>
                <caption style='text-align:left;font-family:sans;caption-side:top;margin-top:40px;margin-bottom:10px;font-size: 1em;'>
                    # Daftar Transaksi Penjualan Sampah
                </caption>
                <thead>
                    <tr style='font-size: 0.8em;'>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>#</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Tanggal</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>ID Transaksi</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Nama Admin</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Jenis sampah</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Harga Satuan Pengepul</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Jumlah(Kg)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Jumlah(Rp)</th>
                    </tr>
                <thead>
                <tbody>
                    $trTjs
                </tbody>
            </table>
        </body>
        
        </html>");

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output($judulFile, 'I');
    }

    /**
     * Cetak Penarikan Saldo
     */
    public function cetakLaporanPenarikanSaldo()
    {
        $token     = (isset($_COOKIE['token'])) ? $_COOKIE['token'] : null;
        $result    = $this->checkToken($token, false);
        $privilege = (isset($result['data']['privilege'])) ? $result['data']['privilege'] : null;

        if ($token == null || $result['success'] == false || !in_array($privilege,['superadmin','admin'])) {
            setcookie('token', null, -1, '/');
            unset($_COOKIE['token']);

            if ($privilege == 'nasabah') {
                return redirect()->to(base_url().'/login');
            }
            else{
                return redirect()->to(base_url().'/login/admin');
            }
        }

        $transaksiModel = new TransaksiModel;

        $judulFile  = "";
        $jenis      = "penarikan-saldo";
        $headDate   = "";
        $keteranganFileTr = "";
        $keteranganFileTable = "";

        $wilayah    = $this->request->getGet('wilayah');
        $date       = $this->request->getGet('date');
        $datePatern = '/^[0-1][0-9][-][2-9][0-9][0-9][0-9]$/';
        $start      = $this->request->getGet('start');
        $end        = $this->request->getGet('end');
        $startEndPatern = '/^[0-3][0-9][-][0-1][0-9][-][2-9][0-9][0-9][0-9]$/';

        if ($this->request->getGet()) {
            if ($date != null) {
                if ($date == "") {
                    return redirect()->to(base_url().'/admin/transaksi');
                }
                else if(preg_match($datePatern, $date) == false) {
                    return redirect()->to(base_url().'/admin/transaksi');
                }

                $judulFile  = $jenis.'#'.date("Y-F", strtotime("01-".$date)).".pdf";
                $headDate   = "<span  style='font-size: 1em;font-family: sans;'>
                    ".date("F, Y", strtotime("01-".$date))."
                </span>";

                if ($wilayah == "true" || $start != null && $end != null) {
                    $headDate = "";
                    $keteranganFileTr .= "<tr>
                        <td>Tanggal</td>
                        <td>: ".date("F, Y", strtotime("01-".$date))."</td>
                    </tr>";
                }
            }
            if ($start != null && $end != null) {
                if(preg_match($startEndPatern, $start) == false || preg_match($startEndPatern, $end) == false) {
                    return redirect()->to(base_url().'/admin/transaksi');
                }

                $judulFile  = $jenis.'#'.date("Y_F_d", strtotime($start))."-".date("d_F_Y", strtotime($end)).".pdf";
                $keteranganFileTr .= "<tr>
                    <td>Tangal dimulai</td>
                    <td>: ".date("d F, Y", strtotime($start))."</td>
                </tr>
                <tr>
                    <td>Tangal berakhir</td>
                    <td>: ".date("d F, Y", strtotime($end))."</td>
                </tr>";
            }
            if ($wilayah == "true") {
                $strWilayah = "";
                $strWilayahFileName = "";

                foreach ($this->request->getGet() as $key => $value) {
                    if (in_array($key,['provinsi','kota','kecamatan','kelurahan'])) {
                        $strWilayah .= ucfirst($value).', ';
                        $strWilayahFileName .= ucfirst($value).'_';
                    }
                }

                $judulFile  = $jenis.'#'.trim($strWilayahFileName,"_").".pdf";
                $keteranganFileTr .= "<tr>
                    <td>Wilayah</td>
                    <td>: ".trim($strWilayah,", ")."</td>
                </tr>";
            }

            $keteranganFileTable = "<table style='margin-top: 20px;'>
                $keteranganFileTr
            </table>";
        }
        else {
            return redirect()->to(base_url().'/admin/transaksi');
        }

        $get = $this->request->getGet();
        $get['jenis'] = $jenis;
        
        $dbresponse = $transaksiModel->rekapData($get);
        $data       = $dbresponse["data"];

        /*
        / Penarikan Saldo Nasabah
        */
        $tts   = $data['tts'];
        $trTts = "";
        $noTts = 1;
        $totUangTarik     = 0;
        $totUbsTarik      = 0;
        $totAntamTarik    = 0;
        $totGalery24Tarik = 0;
        $colspan          = 4;

        foreach ($tts as $key) {
            $uang     = ($key['jenis_saldo'] == 'uang')     ? (int)$key['jumlah_tarik']   : 0;
            $ubs      = ($key['jenis_saldo'] == 'ubs')      ? round((float)$key['jumlah_tarik'],4) : 0;
            $antam    = ($key['jenis_saldo'] == 'antam')    ? round((float)$key['jumlah_tarik'],4) : 0;
            $galery24 = ($key['jenis_saldo'] == 'galery24') ? round((float)$key['jumlah_tarik'],4) : 0;

            $totUangTarik     = ($key['jenis_saldo'] == 'uang')     ? $totUangTarik+$uang         : $totUangTarik;
            $totUbsTarik      = ($key['jenis_saldo'] == 'ubs')      ? $totUbsTarik+$ubs           : $totUbsTarik;
            $totAntamTarik    = ($key['jenis_saldo'] == 'antam')    ? $totAntamTarik+$antam       : $totAntamTarik;
            $totGalery24Tarik = ($key['jenis_saldo'] == 'galery24') ? $totGalery24Tarik+$galery24 : $totGalery24Tarik;

            $bg     = ($noTts % 2 == 0) ? "style='background: rgb(230, 230, 230);'" : "style='background: rgb(255, 255, 255);'";

            $trTts .= "<tr $bg>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".$noTts++."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".date("d/m/Y", $key['date'])."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".$key['id_transaksi']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".$key['nama_lengkap']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                    ".number_format((int)$uang , 0, ',', ',')."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: left;'>
                    ".$ubs."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: left;'>
                    ".$antam."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: left;'>
                    ".$galery24."
                </td>
            </tr>";
        }

        $trTts .= "<tr style='background: rgb(230, 230, 230);'>
            <th colspan='$colspan' style='text-align: center;font-size: 0.8em;font-family: sans;'>
                total
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: right;'>
                ".number_format($totUangTarik , 0, ',', ',')."
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: left;'>
                ".$totUbsTarik."
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: left;'>
                ".$totAntamTarik."
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: left;'>
                ".$totGalery24Tarik."
            </th>
        </tr>";  

        /*
        / Penarikan Saldo Admin
        */
        $ttsBst   = $data['ttsBst'];
        $trTtsBst = "";
        $noTtsBst = 1;
        $totUangTarik = 0;
        $colspan      = 4;

        foreach ($ttsBst as $key) {
            $uang = $key['jumlah_tarik'];
            $totUangTarik = $totUangTarik+(float)$key['jumlah_tarik'];

            $bg     = ($noTtsBst % 2 == 0) ? "style='background: rgb(230, 230, 230);'" : "style='background: rgb(255, 255, 255);'";

            $trTtsBst .= "<tr $bg>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".$noTtsBst++."
                </td>
                <td style='font-size: 0.7em;font-family: sans;;text-align: center;'>
                    ".date("d/m/Y", $key['date'])."
                </td>
                <td style='font-size: 0.7em;font-family: sans;;text-align: center;'>
                    ".$key['id_transaksi']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;;text-align: center;'>
                    ".$key['nama_lengkap']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                    ".number_format((int)$uang , 0, ',', ',')."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".$key['description']."
                </td>
            </tr>";
        }

        $trTtsBst .= "<tr style='background: rgb(230, 230, 230);'>
            <th colspan='$colspan' style='text-align: center;font-size: 0.8em;font-family: sans;'>
                total
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: right;'>
                ".number_format($totUangTarik , 0, ',', ',')."
            </th>
            <th style='text-align: center;font-size: 0.8em;font-family: sans;'>
                
            </th>
        </tr>";  

        $mpdf = new \Mpdf\Mpdf();
        
        $mpdf->WriteHTML("
        <!DOCTYPE html>
        <html lang='en'>
        
        <head>
            <meta charset='utf-8'>
            <title>bsbl | penarikan saldo</title>
        </head>
        
        <body>
            <div style='border-bottom: 2px solid black;padding-bottom: 0px;'>
                <table border='0' width='100%'>
                   <tr>
                        <th style='text-align: left;'>
                            <img src='".base_url()."/assets/images/banksampah-logo.png' style='width: 160px;'>
                        </th>
                        <th style='text-align: right;'>
                            <h1  style='font-size: 2em;'>
                                PENARIKAN SALDO
                            </h1>
                            $headDate
                        </th>
                    </tr>';
                </table>
            </div>

            $keteranganFileTable

            <table border='0' width='100%' cellpadding='5'>
                <caption style='text-align:left;font-family:sans;caption-side:top;margin-top:40px;margin-bottom:10px;font-size: 1em;'>
                    # Penarikan Saldo Nasabah
                </caption>
                <thead>
                    <tr>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>#</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Tanggal</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>ID Transaksi</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Nama Lengkap</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Uang(Rp)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>UBS(g)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Antam(g)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Galery24(g)</th>
                    </tr>
                <thead>
                <tbody>
                    $trTts
                </tbody>
            </table>

            <table border='0' width='100%' cellpadding='5'>
                <caption style='text-align:left;font-family:sans;caption-side:top;margin-top:40px;margin-bottom:10px;font-size: 1em;'>
                    # Penarikan Saldo Kas Bank Sampah
                </caption>
                <thead>
                    <tr>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>#</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Tanggal</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>ID Transaksi</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Nama Admin</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Uang(Rp)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Keterangan</th>
                    </tr>
                <thead>
                <tbody>
                    $trTtsBst
                </tbody>
            </table>
        </body>
        
        </html>");

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output($judulFile, 'I');
    }

    /**
     * Cetak Konversi Saldo
     */
    public function cetakLaporanKonversiSaldo()
    {
        $token     = (isset($_COOKIE['token'])) ? $_COOKIE['token'] : null;
        $result    = $this->checkToken($token, false);
        $privilege = (isset($result['data']['privilege'])) ? $result['data']['privilege'] : null;

        if ($token == null || $result['success'] == false || !in_array($privilege,['superadmin','admin'])) {
            setcookie('token', null, -1, '/');
            unset($_COOKIE['token']);

            if ($privilege == 'nasabah') {
                return redirect()->to(base_url().'/login');
            }
            else{
                return redirect()->to(base_url().'/login/admin');
            }
        }

        $transaksiModel = new TransaksiModel;

        $judulFile  = "";
        $jenis      = "konversi-saldo";
        $headDate   = "";
        $keteranganFileTr = "";
        $keteranganFileTable = "";

        $wilayah    = $this->request->getGet('wilayah');
        $date       = $this->request->getGet('date');
        $datePatern = '/^[0-1][0-9][-][2-9][0-9][0-9][0-9]$/';
        $start      = $this->request->getGet('start');
        $end        = $this->request->getGet('end');
        $startEndPatern = '/^[0-3][0-9][-][0-1][0-9][-][2-9][0-9][0-9][0-9]$/';

        if ($this->request->getGet()) {
            if ($date != null) {
                if ($date == "") {
                    return redirect()->to(base_url().'/admin/transaksi');
                }
                else if(preg_match($datePatern, $date) == false) {
                    return redirect()->to(base_url().'/admin/transaksi');
                }

                $judulFile  = $jenis.'#'.date("Y-F", strtotime("01-".$date)).".pdf";
                $headDate   = "<span  style='font-size: 1em;font-family: sans;'>
                    ".date("F, Y", strtotime("01-".$date))."
                </span>";

                if ($wilayah == "true" || $start != null && $end != null) {
                    $headDate = "";
                    $keteranganFileTr .= "<tr>
                        <td>Tanggal</td>
                        <td>: ".date("F, Y", strtotime("01-".$date))."</td>
                    </tr>";
                }
            }
            if ($start != null && $end != null) {
                if(preg_match($startEndPatern, $start) == false || preg_match($startEndPatern, $end) == false) {
                    return redirect()->to(base_url().'/admin/transaksi');
                }

                $judulFile  = $jenis.'#'.date("Y_F_d", strtotime($start))."-".date("d_F_Y", strtotime($end)).".pdf";
                $keteranganFileTr .= "<tr>
                    <td>Tangal dimulai</td>
                    <td>: ".date("d F, Y", strtotime($start))."</td>
                </tr>
                <tr>
                    <td>Tangal berakhir</td>
                    <td>: ".date("d F, Y", strtotime($end))."</td>
                </tr>";
            }
            if ($wilayah == "true") {
                $strWilayah = "";
                $strWilayahFileName = "";

                foreach ($this->request->getGet() as $key => $value) {
                    if (in_array($key,['provinsi','kota','kecamatan','kelurahan'])) {
                        $strWilayah .= ucfirst($value).', ';
                        $strWilayahFileName .= ucfirst($value).'_';
                    }
                }

                $judulFile  = $jenis.'#'.trim($strWilayahFileName,"_").".pdf";
                $keteranganFileTr .= "<tr>
                    <td>Wilayah</td>
                    <td>: ".trim($strWilayah,", ")."</td>
                </tr>";
            }

            $keteranganFileTable = "<table style='margin-top: 20px;'>
                $keteranganFileTr
            </table>";
        }
        else {
            return redirect()->to(base_url().'/admin/transaksi');
        }

        $get = $this->request->getGet();
        $get['jenis'] = $jenis;
        
        $dbresponse = $transaksiModel->rekapData($get);
        $data       = $dbresponse["data"];

        /*
        / Penarikan Saldo Nasabah
        */
        $tps   = $data['tps'];
        $trTps = "";
        $noTps = 1;
        $totEmasKonversi = 0;
        $totUangKonversi = 0;

        foreach ($tps as $key) {
            $jumlah = (int)$key['jumlah'];
            $hargaEmas = (int)$key['harga_emas'];
            $hasilKonversi = (float)$key['hasil_konversi'];

            $totEmasKonversi = $hasilKonversi+$hasilKonversi;
            $totUangKonversi = $jumlah+$jumlah;

            $bg     = ($noTps % 2 == 0) ? "style='background: rgb(230, 230, 230);'" : "style='background: rgb(255, 255, 255);'";

            $trTps .= "<tr $bg>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$noTps++."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".date("d/m/Y", $key['date'])."
                </td>
                <td style='font-size: 0.7em;font-family: sans;'>
                    ".$key['id_transaksi']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".$key['nama_lengkap']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                    ".number_format($jumlah , 0, ',', ',')."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                    ".number_format($hargaEmas , 0, ',', ',')."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                    ".round($hasilKonversi,2)."
                </td>
            </tr>";
        }

        $trTps .= "<tr style='background: rgb(230, 230, 230);'>
            <th colspan='4' style='text-align: center;font-size: 0.8em;font-family: sans;'>
                total
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: right;'>
                ".number_format($totUangKonversi , 0, ',', ',')."
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: right;'>
                
            </th>
            <th style='text-align: left;font-size: 0.8em;font-family: sans;text-align: right;'>
                ".round($totEmasKonversi,2)."
            </th>
        </tr>";  

        $mpdf = new \Mpdf\Mpdf();
        
        $mpdf->WriteHTML("
        <!DOCTYPE html>
        <html lang='en'>
        
        <head>
            <meta charset='utf-8'>
            <title>bsbl | konversi saldo</title>
        </head>
        
        <body>
            <div style='border-bottom: 2px solid black;padding-bottom: 0px;'>
                <table border='0' width='100%'>
                   <tr>
                        <th style='text-align: left;'>
                            <img src='".base_url()."/assets/images/banksampah-logo.png' style='width: 160px;'>
                        </th>
                        <th style='text-align: right;'>
                            <h1  style='font-size: 2em;'>
                                KONVERSI SALDO
                            </h1>
                            $headDate
                        </th>
                    </tr>';
                </table>
            </div>

            $keteranganFileTable

            <table border='0' width='100%' cellpadding='5'>
                <caption style='text-align:left;font-family:sans;caption-side:top;margin-top:40px;margin-bottom:10px;font-size: 1em;'>
                    # Konversi Saldo Uang ke Emas
                </caption>
                <thead>
                    <tr>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>#</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Tanggal</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>ID Transaksi</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Nama Lengkap</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Uang(Rp)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Harga emas per-kg</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Hasil Konversi(Kg)</th>
                    </tr>
                <thead>
                <tbody>
                    $trTps
                </tbody>
            </table>
        </body>
        
        </html>");

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output($judulFile, 'I');
    }

    /**
     * Cetak Buku Tabungan
     */
    public function cetakLaporanBukuTabungan()
    {
        $token     = (isset($_COOKIE['token'])) ? $_COOKIE['token'] : null;
        $result    = $this->checkToken($token, false);
        $privilege = (isset($result['data']['privilege'])) ? $result['data']['privilege'] : null;

        if ($token == null || $result['success'] == false || !in_array($privilege,['superadmin','admin'])) {
            setcookie('token', null, -1, '/');
            unset($_COOKIE['token']);

            if ($privilege == 'nasabah') {
                return redirect()->to(base_url().'/login');
            }
            else{
                return redirect()->to(base_url().'/login/admin');
            }
        }

        $transaksiModel = new TransaksiModel;
        $userModel      = new UserModel;

        $nasabah = "";
        $saldoUang  = 0;
        $saldoEmas  = 0;
        $saldoUangStart = 0;
        $saldoEmasStart = 0;
        $noTransaksi= 0;
        $transaksi           = "";
        $keteranganFileTr    = "";
        $keteranganFileTable = "";

        if (!$this->request->getGet('idnasabah')) {
            return redirect()->to(base_url().'/admin/transaksi');
        }

        $get['id'] = $this->request->getGet('idnasabah');
        
        $dbNasabah   = $userModel->getNasabah($get);
        $dbSaldo     = $transaksiModel->getAllJenisSaldo($get['id']);
        $dbTransaksi = $transaksiModel->getData($get,$get['id']);
        
        if ($dbNasabah['status'] == 404) {
            return redirect()->to(base_url().'/admin/listnasabah');
        } 
        else {
            $nasabah   = $dbNasabah['data'][0];
            $transaksi = $dbTransaksi['data'];

            $saldoUang      = (float)$dbSaldo['data']->uang;
            $saldoUangStart = $saldoUang;
            $saldoEmas      = (float)$dbSaldo['data']->emas;
            $saldoEmasStart = $saldoEmas;

            $keteranganFileTr .= "<tr>
                <td>Nama lengkap&nbsp;</td>
                <td style='text-transform:capitalize'>:&nbsp;&nbsp;".$nasabah['nama_lengkap']."</td>
            </tr>
            <tr>
                <td>Username</td>
                <td>:&nbsp;&nbsp;".$nasabah['username']."</td>
            </tr>
            <tr>
                <td>ID nasabah</td>
                <td>:&nbsp;&nbsp;".$nasabah['id']."</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:&nbsp;&nbsp;".$nasabah['alamat']."</td>
            </tr>";

            $keteranganFileTable = "<table style='margin-top: 20px;'>
                $keteranganFileTr
            </table>";
        }

        $keteranganFileTable = "<table style='margin-top: 20px;'>
            $keteranganFileTr
        </table>";

        foreach ($transaksi as $value) {
            $jenisTransaksi = $value['jenis_transaksi'];

            if ($jenisTransaksi == 'penyetoran sampah') {
                $saldoUangStart = $saldoUangStart-(float)$value['total_uang_setor'];
            } 
            else if ($jenisTransaksi == 'konversi saldo') {
                $saldoUangStart = $saldoUangStart+(float)$value['total_pindah'];
                $saldoEmasStart -= (float)$value['hasil_konversi'];
            }
            else {
                if ($value['jenis_saldo'] == 'uang') {
                    $saldoUangStart = $saldoUangStart+(float)$value['total_tarik'];
                } 
                else {
                    var_dump((float)$value['total_tarik']);
                    $saldoEmasStart += (float)$value['total_tarik'];
                }
            }
        }

        $saldoEmasStart = ($saldoEmasStart < 0) ? 0 : $saldoEmasStart;

        $trTransaksi = "<tr>
            <td colspan='6'>
            </td>
            <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                ".number_format($saldoUangStart < 0 ? 0 : $saldoUangStart , 2, '.', ',')."
            </td>
            <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                ".$saldoEmasStart."
            </td>
        </tr>";

        foreach ($transaksi as $value) {
            $debitUang  = 0;
            $debitEmas  = 0;
            $kreditUang = 0;
            $kreditEmas = 0;
            $jenisTransaksi = $value['jenis_transaksi'];

            if ($jenisTransaksi == 'penyetoran sampah') {
                $debitUang  = "--";
                $debitEmas  = "--";
                $kreditUang = number_format((float)$value['total_uang_setor'],2,'.',',');
                $kreditEmas = "--";
                $saldoUangStart = $saldoUangStart+(float)$value['total_uang_setor'];
            } 
            else if ($jenisTransaksi == 'konversi saldo') {
                $debitUang  = number_format((float)$value['total_pindah'],2,'.',',');
                $debitEmas  = "--";
                $kreditUang = "--";
                $kreditEmas = (float)$value['hasil_konversi'];
                $saldoUangStart = $saldoUangStart-(float)$value['total_pindah'];
                $saldoEmasStart = $saldoEmasStart+(float)$value['hasil_konversi'];
            }
            else {
                if ($value['jenis_saldo'] == 'uang') {
                    $debitUang  = number_format((float)$value['total_tarik'],2,'.',',');
                    $debitEmas  = "--";
                    $kreditUang = "--";
                    $kreditEmas = "--";
                    $saldoUangStart = $saldoUangStart-(float)$value['total_tarik'];
                } 
                else {
                    $debitUang  = "--";
                    $debitEmas  = (float)$value['total_tarik'];
                    $kreditUang = "--";
                    $kreditEmas = "--";
                    $saldoEmasStart -= (float)$value['total_tarik'];
                }
            }

            $bg     = ($noTransaksi % 2 == 0) ? "style='background: rgb(230, 230, 230);'" : "style='background: rgb(255, 255, 255);'";
            ++$noTransaksi;

            $trTransaksi .= "<tr $bg>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".date("d/m/Y", $value['date'])."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    ".$value['id_transaksi']."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    $debitUang
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    $debitEmas
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    $kreditUang
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: center;'>
                    $kreditEmas
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                    ".number_format($saldoUangStart , 2, '.', ',')."
                </td>
                <td style='font-size: 0.7em;font-family: sans;text-align: right;'>
                    ".$saldoEmasStart."
                </td>
            </tr>";
        }

        $mpdf = new \Mpdf\Mpdf();
        
        $mpdf->WriteHTML("
        <!DOCTYPE html>
        <html lang='en'>
        
        <head>
            <meta charset='utf-8'>
            <title>bsbl | buku tabungan</title>
        </head>
        
        <body>
            <div style='border-bottom: 2px solid black;padding-bottom: 0px;'>
                <table border='0' width='100%'>
                   <tr>
                        <th style='text-align: left;'>
                            <img src='".base_url()."/assets/images/banksampah-logo.png' style='width: 160px;'>
                        </th>
                        <th style='text-align: right;'>
                            <h1  style='font-size: 2em;'>
                                BUKU TABUNGAN
                            </h1>
                        </th>
                    </tr>';
                </table>
            </div>

            $keteranganFileTable

            <table border='0' width='100%' cellpadding='5' style='margin-top:40px;'>
                <thead>
                    <tr style='font-size: 0.8em;'>
                        <th rowspan='2' style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Tanggal</th>
                        <th rowspan='2' style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>ID Transaksi</th>
                        <th colspan='2' style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Debit</th>
                        <th colspan='2' style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Kredit</th>
                        <th colspan='2' style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Saldo</th>
                    </tr>
                    <tr style='font-size: 0.8em;'>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Uang(rp)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Emas(g)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Uang(rp)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Emas(g)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Uang(rp)</th>
                        <th style='border: 0.5px solid black;font-size: 0.8em;font-family: sans;'>Emas(g)</th>
                    </tr>
                <thead>
                <tbody>
                    $trTransaksi
                </tbody>
            </table>
        </body>
        
        </html>");

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output("buku-tabungan#".$nasabah['nama_lengkap'], 'I');
    }

    /**
     * Setor sampah
     *   url    : domain.com/transaksi/setorsampah
     *   method : POST
     */
    public function setorSampah()
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $data   = $this->request->getPost();
        $this->validation->run($data,'setorSampah1');
        $errors = $this->validation->getErrors();

        if($errors) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => $errors,
            ];
    
            return $this->respond($response,400);
        } 
        else {
            foreach ($data['transaksi'] as $t) {
                $this->validation->run($t,'setorSampah2');
                $errors = $this->validation->getErrors();
    
                if($errors) {
                    $response = [
                        'status'   => 400,
                        'error'    => true,
                        'messages' => $errors,
                    ];
            
                    return $this->respond($response,400);
                } 
            }

            $data['idtransaksi'] = 'TSS'.$this->generateOTP(9);
            $dbresponse          = $this->transaksiModel->setorSampah($data);

            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }

    /**
     * Setor sampah
     *   url    : domain.com/transaksi/setorsampah
     *   method : PUT
     */
    public function editSetorSampah()
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $data   = $this->request->getPost();
        $this->validation->run($data,'editSetorSampah1');
        $errors = $this->validation->getErrors();

        if($errors) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => $errors,
            ];
    
            return $this->respond($response,400);
        } 
        else {
            foreach ($data['transaksi'] as $t) {
                $this->validation->run($t,'setorSampah2');
                $errors = $this->validation->getErrors();
    
                if($errors) {
                    $response = [
                        'status'   => 400,
                        'error'    => true,
                        'messages' => $errors,
                    ];
            
                    return $this->respond($response,400);
                } 
            }

            $data['idtransaksi'] = $data["id_transaksi"];
            $dbresponse          = $this->transaksiModel->editSetorSampah($data);

            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }

    /**
     * Tarik saldo
     *   url    : domain.com/transaksi/tariksaldo
     *   method : POST
     */
    public function tarikSaldo($pemilik = "nasabah")
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $data    = $this->request->getPost();
        $isAdmin = false;
        $data["pemilik"] = $pemilik;

        if ($pemilik == "bsbl") {
            $isAdmin = true;
            $data["id_nasabah"]  = $result['data']['userid'];
            $data['jenis_saldo'] = 'uang';
            $this->validation->run($data,'tarikSaldoBst');
        }
        else {
            $this->validation->run($data,'tarikSaldo');
        }

        $errors = $this->validation->getErrors();

        if($errors) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => $errors,
            ];
    
            return $this->respond($response,400);
        } 
        else {
            $valid  = true;
            $msg    = '';
            $saldoX = $this->transaksiModel->getSaldoJenisX($data['id_nasabah'],$data['jenis_saldo'],$isAdmin);

            if ((float)$saldoX < (float)$data['jumlah']) {
                $valid      = false;
                $jenisSaldo = ($data['jenis_saldo'] == 'uang') ? 'uang' : 'emas';
                
                $msg   = [
                    'jumlah' => 'saldo '.$jenisSaldo.' anda tidak cukup'
                ];
            }
            else {
                if ($data['jenis_saldo'] !== 'uang') {
                    if (round((float)$saldoX-(float)$data['jumlah'],4) < 0.1000) {
                        $valid = false;
                        $msg   = [
                            'jumlah' => 'minimal saldo yang mengendap adalah 0.1 gram',
                            'saldo'  => (float)$saldoX, 
                            'tarik'  => (float)$data['jumlah'],
                            'hasil'  => round((float)$saldoX-(float)$data['jumlah'],4)
                        ];
                    }
                }
            }
            
            if (!$valid) {
                $response = [
                    'status'   => 400,
                    'error'    => true,
                    'messages' => $msg,
                ];
        
                return $this->respond($response,400);
            }

            $data['idtransaksi'] = 'TTS'.$this->generateOTP(9);
            $dbresponse          = $this->transaksiModel->tarikSaldo($data);

            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }

    /**
     * Pindah saldo
     *   url    : domain.com/transaksi/pindahsaldo
     *   method : POST
     */
    public function pindahSaldo(): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $data   = $this->request->getPost();
        $this->validation->run($data,'pindahSaldo');
        $errors = $this->validation->getErrors();

        if ($errors) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => $errors,
            ];
    
            return $this->respond($response,400);
        } 
        else {
            $valid  = true;
            $msg    = '';
            $jumlahPindah    = (float)$data['jumlah'];
            $jumlahSaldoAsal = (float)$this->transaksiModel->getSaldoJenisX($data['id_nasabah'],'uang');

            if ($jumlahSaldoAsal < $jumlahPindah ) {
                $valid = false;
                $msg   = [
                    'jumlah' => 'saldo uang tidak cukup',
                ];
            }
            else {
                $jumlahTps = $this->transaksiModel->JumlahTps($data['id_nasabah']);

                if ($jumlahTps == 0) {
                    if ((float)$data['jumlah'] < 50000) {
                        $valid = false;
                        $msg   = [
                            'jumlah' => 'minimal pindah pada transaksi pertama adalah Rp50.000'
                        ];
                    }
                } 
                else {
                    if ((float)$data['jumlah'] < 10000) {
                        $valid = false;
                        $msg   = [
                            'jumlah' => 'minimal pindah Rp10.000'
                        ];
                    }
                }
            }

            if (!$valid) {
                $response = [
                    'status'   => 400,
                    'error'    => true,
                    'messages' => $msg,
                ];
        
                return $this->respond($response,400);
            }

            $newdata = [
                'idnasabah'     => $data['id_nasabah'],
                'date'          => $data['date'],
                'idtransaksi'   => 'TPS'.$this->generateOTP(9),
                'jumlahPindah'  => $jumlahPindah,
                'hasilKonversi' => (float)$data['jumlah']/$data['harga_emas'],
                'hargaemas'     => $data['harga_emas'],
                'saldo_dompet_asal' => $jumlahSaldoAsal-$jumlahPindah,
            ];
            
            $dbresponse = $this->transaksiModel->pindahSaldo($newdata);
            
            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }

    public function getHargaEmas(): float
    {
        $output = $this->curlGetData("https://www.goldapi.io/api/XAU/USD/",array('Content-Type:application/json','x-access-token:goldapi-s79zgtkugd4m5s-io'));

        return round(((float)$output['price']/31.1)*$this->getHargaDolar());
    }

    public function getHargaDolar(): float
    {
        $output = $this->curlGetData("https://free.currconv.com/api/v7/convert?q=USD_IDR&compact=ultra&apiKey=c94ee0cbe358dc63dce9",array('Content-Type:application/json'));

        return round((float)$output['USD_IDR']);
    }

    /**
     * Jual sampah
     *   url    : domain.com/transaksi/jualsampah
     *   method : POST
     */
    public function jualSampah()
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $data  = $this->request->getPost();

        $this->validation->run($data,'jualSampah');
        $errors = $this->validation->getErrors();

        if($errors) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => $errors,
            ];
    
            return $this->respond($response,400);
        } 
        else {
            foreach ($data['transaksi'] as $t) {
                $this->validation->run($t,'setorSampah2');
                $errors = $this->validation->getErrors();
    
                if($errors) {
                    $response = [
                        'status'   => 400,
                        'error'    => true,
                        'messages' => $errors,
                    ];
            
                    return $this->respond($response,400);
                } 
            }

            $data['idtransaksi'] = 'TJS'.$this->generateOTP(9);
            $dbresponse          = $this->transaksiModel->jualSampah($data);

            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }

    /**
     * Get data transaction
     *   url    : domain.com/transaksi/sampahmasuk
     *   method : GET
     */
    public function getSampahMasuk()
    {
        $isAdmin   = false;
        $idNasabah = '';

        if ($this->request->getHeader('token')) {
            $result     = $this->checkToken();
            $isAdmin    = (in_array($result['data']['privilege'],['admin','superadmin'])) ? true : false ;
            $idNasabah  = ($isAdmin==false) ? $result['data']['userid'] : '' ;
        }

        if ($isAdmin) {
            if ($this->request->getGet('idnasabah')) {
                $idNasabah = $this->request->getGet('idnasabah');
            }
        }

        $dbresponse = $this->transaksiModel->getSampahMasuk($this->request->getGet(),$idNasabah);

        return $this->respond($dbresponse,$dbresponse['status']);
    }

    /**
     * Get data transaction
     *   url    : domain.com/transaksi/getsaldo
     *   method : GET
     */
    public function getSaldo()
    {
        $result     = $this->checkToken();
        $isAdmin    = (in_array($result['data']['privilege'],['admin','superadmin'])) ? true : false ;
        $idNasabah  = ($isAdmin==false) ? $result['data']['userid'] : '' ;

        if ($isAdmin) {
            if ($this->request->getGet('idnasabah')) {
                $idNasabah = $this->request->getGet('idnasabah');
            }
        }

        $dbresponse = $this->transaksiModel->getAllJenisSaldo($idNasabah);

        return $this->respond($dbresponse,$dbresponse['status']);
    }

    /**
     * Get data transaction
     *   url    : domain.com/transaksi/getdata
     *   method : GET
     */
    public function getData()
    {
        $result    = $this->checkToken();
        $isAdmin   = (in_array($result['data']['privilege'],['admin','superadmin'])) ? true : false ;
        $idNasabah = ($isAdmin==false) ? $result['data']['userid'] : '' ;

        if ($this->request->getGet('start') && $this->request->getGet('end')) {
            $this->validation->run($this->request->getGet(),'dateForFilterTransaksi');
            $errors = $this->validation->getErrors();

            if($errors) {
                $response = [
                    'status'   => 400,
                    'error'    => true,
                    'messages' => $errors,
                ];
        
                return $this->respond($response,400);
            } 
        }

        if ($isAdmin) {
            if ($this->request->getGet('idnasabah')) {
                $idNasabah = $this->request->getGet('idnasabah');
            }
        }

        $dbresponse = $this->transaksiModel->getData($this->request->getGet(),$idNasabah);

        return $this->respond($dbresponse,$dbresponse['status']);
    }

    /**
     * Get data transaction
     *   url    : domain.com/transaksi/rekapdata
     *   method : GET
     */
    public function rekapData()
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $errors = null;
        if ($this->request->getGet('year')) {
            $this->validation->run($this->request->getGet(),'rekapDataYear');
            $errors = $this->validation->getErrors();

        }
        if ($this->request->getGet('date')) {
            $this->validation->run($this->request->getGet(),'rekapDataDate');
            $errors = $this->validation->getErrors();

        }

        if($errors) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => $errors,
            ];
    
            return $this->respond($response,400);
        } 
        
        $dbresponse = $this->transaksiModel->rekapData($this->request->getGet());

        return $this->respond($dbresponse,$dbresponse['status']);
    }

    /**
     * Get data transaction
     *   url    : domain.com/transaksi/grafikssampah
     *   method : GET
     */
    public function grafikSetorSampah()
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin','nasabah']);

        $errors = null;
        $get    = $this->request->getGet();

        if ($this->request->getGet('year')) {
            $this->validation->run($get,'rekapDataYear');
            $errors = $this->validation->getErrors();
        }

        if($errors) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => $errors,
            ];
    
            return $this->respond($response,400);
        } 

        if ($result['data']['privilege'] == 'nasabah') {
            $get['idnasabah'] = $result['data']['userid'];
            $_GET['tampilan'] ='per-bulan';
        }

        if (isset($_GET['tampilan']) && $_GET['tampilan']=='per-daerah') {
            $dbresponse = $this->transaksiModel->grafikSetorSampahPerdaerah($get);
        }
        else{
            $dbresponse = $this->transaksiModel->grafikSetorSampahPerbulan($get);
        } 

        return $this->respond($dbresponse,$dbresponse['status']);
    }

    /**
     * Delete transaksi
     *   url    : domain.com/transaksi/deletedata?id=:id
     *   method : DELETE
     */
	public function deleteData(): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $this->validation->run($this->request->getGet(),'deleteTransaksi');
        $errors = $this->validation->getErrors();

        if($errors) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => $errors,
            ];
    
            return $this->respond($response,400);
        } 

        $dbresponse = $this->transaksiModel->deleteData($this->request->getGet('id'));
        return $this->respond($dbresponse,$dbresponse['status']);
    }
}
