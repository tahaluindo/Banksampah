<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class KategoriModel extends Model
{
    // protected $primaryKey    = 'id';
    // protected $allowedFields = ['id','name'];

    public function getLastKategori(string $tableName): array
    {
        try {
            $lastKategori = $this->db->table($tableName)->select('id')->limit(1)->orderBy('id','DESC')->get()->getResultArray();

            if (!empty($lastKategori)) { 
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $lastKategori[0],
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

    public function countKategoriUtama(): string
    {
        $total = $this->db->table("kategori_artikel")->select('count(id) AS total')->where('kategori_utama =',"1")->get()->getResultArray()[0]['total'];

        return $total;
    }

    public function addKategori(array $data,string $tableName): array
    {
        try {
            $query = $this->db->table($tableName)->insert($data);
            
            if ($query == true) {
                return [
                    'status'   => 201,
                    'error'    => false,
                    'messages' => 'add kategori artikel is success',
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

    public function editKategoriArtikel(array $data): array
    {
        try {
            $this->db->table("kategori_artikel")->where('id',$data['id'])->update($data);
            
            return [
                'status'   => 201,
                'error'    => false,
                'messages' => ($this->db->affectedRows()>0) ? 'edit kategori artikel is success' : 'nothing updated'
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

    public function checkTransaksi(string $idkategori): bool
    {
        $transaction = $this->db->table('setor_sampah')->select('sampah.id_kategori')->join('sampah', 'sampah.id = setor_sampah.id_sampah')->where('sampah.id_kategori',$idkategori)->limit(1)->get()->getResultArray();

        if (empty($transaction)) {    
            return true;
        }
        else {
            return false;
        }
    }

    public function getOldIcon(string $id): string
    {
        $oldIcon = $this->db->table("kategori_artikel")->select('icon')->where('id',$id)->get()->getResultArray()[0]['icon'];

        if (!empty($oldIcon)) {    
            return $oldIcon;
        } 
    }

    public function deleteKategori(string $id,string $tableName): array
    {
        try {
            if ($tableName == 'kategori_sampah') {
                if ($this->checkTransaksi($id) == false) {
                    return [
                        'status'   => 400,
                        'error'    => true,
                        'messages' => "kategori ini sudah pernah dipakai dalam transaksi"
                    ];  
                }
            }

            $this->db->table($tableName)->where('id', $id)->delete();
            $affectedRows = $this->db->affectedRows();

            return [
                'status'   => ($affectedRows>0) ? 201   : 404,
                'error'    => ($affectedRows>0) ? false : true,
                'messages' => ($affectedRows>0) ? "delete kategori with id $id is success" : "kategori with id $id is not found"
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

    public function getKategori(string $tableName): array
    {
        try {
            $kategori = $this->db->table($tableName)->orderBy("created_at","desc")->get()->getResultArray();
            
            $kategoriName = ($tableName == 'kategori_sampah') ? "kategori sampah" : "kategori artikel";
            
            if ($kategoriName == 'kategori artikel') {
                $kategori = $this->modifImgPath($kategori);
            }
            
            if (empty($kategori)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "$kategoriName notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $kategori
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

    public function getDetilKategoriArtikel(string $id): array
    {
        $berita = $this->db->table('kategori_artikel')->where("id",$id)->get()->getFirstRow();

        return (array)$berita;
    }

    public function modifImgPath($data): array
    {
        $newData = [];

        foreach ($data as $array) {
            foreach ($array as $key => $value) {
                if($key === 'icon'){
                    $array[$key] = base_url()."/assets/images/icon-kategori-artikel/".$array['icon'];
                    $newData[]   = $array; 
                }
            }
        }

        return $newData;
    }
}
