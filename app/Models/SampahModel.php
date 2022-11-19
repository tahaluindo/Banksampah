<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class SampahModel extends Model
{
    protected $table         = 'sampah';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['id','id_kategori','jenis','harga'];

    public function getLastSampah(): array
    {
        try {
            $lastSampah = $this->db->table($this->table)->select('id')->limit(1)->orderBy('id','DESC')->get()->getResultArray();

            if (!empty($lastSampah)) { 
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $lastSampah[0],
                ];
            }
            else {   
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => 'not found',
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

    public function addSampah(array $data): array
    {
        try {
            $query = $this->db->table($this->table)->insert($data);

            if ($query == true) {
                return [
                    'status'   => 201,
                    'error'    => false,
                    'messages' => 'add sampah is success',
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

    public function editSampah(array $data): array
    {
        try {
            $this->db->table($this->table)->where('id',$data['id'])->update($data);

            return [
                'status'   => 201,
                'error'    => false,
                'messages' => ($this->db->affectedRows()>0) ? 'edit sampah is success' : 'nothing updated'
            ];  
        } 
        catch (Exception $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function checkTransaksi(string $idsampah): bool
    {
        $transaction = $this->db->table('setor_sampah')->select('id_sampah')->where('id_sampah',$idsampah)->limit(1)->get()->getResultArray();

        if (empty($transaction)) {    
            return true;
        }
        else {
            return false;
        }
    }

    public function deleteSampah(string $id): array
    {
        try {
            if ($this->checkTransaksi($id)) {
                $this->db->table($this->table)->where('id', $id)->delete();
                $affectedRows = $this->db->affectedRows();

                return [
                    'status'   => ($affectedRows>0) ? 201   : 404,
                    'error'    => ($affectedRows>0) ? false : true,
                    'messages' => ($affectedRows>0) ? "delete sampah with id $id is success" : "sampah with id $id is not found"
                ];  
            } 
            else {
                return [
                    'status'   => 400,
                    'error'    => true,
                    'messages' => "sampah ini sudah pernah dipakai dalam transaksi"
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

    public function getSampah(array $get): array
    {
        try {
            $orderby = (isset($get['orderby']) && $get['orderby']=='terbaru')? 'DESC': 'ASC';

            if (isset($get['kategori'])) {
                $sampah  = $this->db->table($this->table)->select('sampah.id,sampah.id_kategori,kategori_sampah.name AS kategori,sampah.jenis,sampah.harga,sampah.harga_pusat,sampah.jumlah')
                ->join('kategori_sampah', 'kategori_sampah.id = sampah.id_kategori')
                ->where('kategori_sampah.name',$get['kategori'])
                ->orderBy('sampah.id',$orderby)->get()->getResultArray();
            } 
            else {
                $sampah  = $this->db->table($this->table)->select('sampah.id,sampah.id_kategori,kategori_sampah.name AS kategori,sampah.jenis,sampah.harga,sampah.harga_pusat,sampah.jumlah')
                ->join('kategori_sampah', 'kategori_sampah.id = sampah.id_kategori')
                ->orderBy("sampah.id",$orderby)->get()->getResultArray();
            }
            
            if (empty($sampah)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "sampah notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $sampah
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

    public function totalItem(?string $idnasabah = null): array
    {
        try {
            $totalSampah = [];
            $kategori    = $this->db->table('kategori_sampah')->get()->getResultArray();

            // var_dump($kategori);die;

            if ($idnasabah) {
                $setorSampah = $this->db->table('setor_sampah')->select('kategori_sampah.name AS kategori,sampah.jenis AS jenis,SUM(setor_sampah.jumlah) AS jumlah')->join('sampah', 'setor_sampah.jenis_sampah = sampah.jenis')->join('transaksi', 'setor_sampah.id_transaksi = transaksi.id')->join('kategori_sampah', 'sampah.kategori = kategori_sampah.name')->where('transaksi.id_nasabah=',$idnasabah)->groupBy(["kategori_sampah.name", "sampah.jenis"])->get()->getResultArray();
            } 
            else {
                $setorSampah = $this->db->table('setor_sampah')->select('kategori_sampah.name AS kategori,sampah.jenis AS jenis,SUM(setor_sampah.jumlah) AS jumlah')->join('sampah', 'setor_sampah.jenis_sampah = sampah.jenis')->join('kategori_sampah', 'sampah.kategori = kategori_sampah.name')->groupBy(["kategori_sampah.name", "sampah.jenis"])->get()->getResultArray();
            }
            
            foreach ($kategori as $k) {
                $totalSampah[$k['name']] = [
                    'title'  => $k['name'],
                    'total'  => 0,
                    'detail' => [],
                ];
            }

            foreach ($setorSampah as $s) {
                $totalSampah[$s['kategori']]['total'] = round($totalSampah[$s['kategori']]['total']+(float)$s['jumlah'],4);
                $totalSampah[$s['kategori']]['detail'][] = [
                    'jenis'  => $s['jenis'],
                    'jumlah' => $s['jumlah'],
                ];
            }
            
            return [
                'success' => true,
                'message' => $totalSampah
            ];
        } 
        catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code'    => 500
            ];
        }
    }
}
