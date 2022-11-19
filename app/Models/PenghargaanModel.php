<?php

namespace App\Models;

use CodeIgniter\Model;

class PenghargaanModel extends Model
{
    protected $table         = 'penghargaan';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['icon','name','description'];

    // Add Penghargaan
    public function addPenghargaan(array $data): array
    {
        try {
            $query = $this->db->table($this->table)->insert($data);
            
            if ($query == true) {
                return [
                    'status'   => 201,
                    'error'    => false,
                    'messages' => 'add penghargaan is success',
                ];
            } 
        } 
        catch (\Throwable $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    // get penghargaan
    public function getPenghargaan(): array
    {
        try {
            $penghargaan = $this->db->table($this->table)->orderBy("id","desc")->get()->getResultArray();
            $penghargaan = $this->modifImgPath($penghargaan);
            
            if (empty($penghargaan)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "penghargaan notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $penghargaan
                ];
            }
        } 
        catch (\Throwable $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function getOldIcon(string $id): string
    {
        $oldIcon = $this->db->table($this->table)->select('icon')->where('id',$id)->get()->getResultArray()[0]['icon'];

        if (!empty($oldIcon)) {    
            return $oldIcon;
        } 
    }

    public function deletePenghargaan(string $id): array
    {
        try {
            $this->db->table($this->table)->where('id', $id)->delete();
            $affectedRows = $this->db->affectedRows();

            return [
                'status'   => ($affectedRows>0) ? 201   : 404,
                'error'    => ($affectedRows>0) ? false : true,
                'messages' => ($affectedRows>0) ? "delete penghargaan with id:$id is success" : "penghargaan with id:$id is not found"
            ];  
        } 
        catch (\Throwable $e) {
            return [
                'status'   => 500,
                'error'    => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function modifImgPath($data): array
    {
        $newData = [];

        foreach ($data as $array) {
            foreach ($array as $key => $value) {
                if($key === 'icon'){
                    $array[$key] = base_url()."/assets/images/icon-penghargaan/".$array['icon'];
                    $newData[]   = $array; 
                }
            }
        }

        return $newData;
    }
}
