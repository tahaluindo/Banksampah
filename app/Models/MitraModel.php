<?php

namespace App\Models;

use CodeIgniter\Model;

class MitraModel extends Model
{
    protected $table         = 'mitra';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['icon','name','description'];

    // Add Mitra
    public function addMitra(array $data): array
    {
        try {
            $query = $this->db->table($this->table)->insert($data);
            
            if ($query == true) {
                return [
                    'status'   => 201,
                    'error'    => false,
                    'messages' => 'add mitra is success',
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

    // get mitra
    public function getMitra(): array
    {
        try {
            $mitra = $this->db->table($this->table)->orderBy("id","desc")->get()->getResultArray();
            $mitra = $this->modifImgPath($mitra);
            
            if (empty($mitra)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "mitra notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $mitra
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

    public function deleteMitra(string $id): array
    {
        try {
            $this->db->table($this->table)->where('id', $id)->delete();
            $affectedRows = $this->db->affectedRows();

            return [
                'status'   => ($affectedRows>0) ? 201   : 404,
                'error'    => ($affectedRows>0) ? false : true,
                'messages' => ($affectedRows>0) ? "delete mitra with id:$id is success" : "mitra with id:$id is not found"
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
                    $array[$key] = base_url()."/assets/images/icon-mitra/".$array['icon'];
                    $newData[]   = $array; 
                }
            }
        }

        return $newData;
    }
}
