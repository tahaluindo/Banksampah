<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class RegisterModel extends Model
{
    protected $primaryKey    = 'id';
    protected $table         = 'users';
    protected $allowedFields = ['id','email','username','password','nama_lengkap','notelp', 'nik','alamat','tgl_lahir','kelamin','token','is_active','last_active','otp','is_verify','created_at','privilege'];

    // GET Last nasabah
    public function getLastNasabah(string $kodepos): array
    {
        try {
            $lastNasabah = $this->db->table($this->table)->select('id')->like('id', $kodepos, 'after')->limit(1)->orderBy('created_at','DESC')->get()->getResultArray();

            if (empty($lastNasabah)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "nasabah notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $lastNasabah[0],
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

    // GET Last admin
    public function getLastAdmin(): array
    {
        try {
            $lastAdmin = $this->db->table($this->table)->select('id')->whereIn('privilege',['admin','superadmin'])->limit(1)->orderBy('created_at','DESC')->get()->getResultArray();

            if (empty($lastAdmin)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "admin notfound",
                ];
            }
            else {
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $lastAdmin[0],
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

    // INSERT New nasabah
    public function addNasabah(array $data): array
    {
        try {
            $wilayah = $data['wilayah'];
            unset($data['wilayah']);

            $this->db->transBegin();
            $this->db->table($this->table)->insert($data);
            $this->db->table("dompet")->insert(['id_user' => $data['id']]);
            $this->db->table("wilayah")->insert($wilayah);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return [
                    'status'   => 500,
                    'error'    => true,
                    'messages' => "register failed",
                ];
            } 
            else {
                return [
                    'status'   => 201,
                    "error"    => false,
                    'messages' => 'register success. please check your email',
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

    // INSERT New admin
    public function addAdmin(array $data): array
    {
        try {
            $query = $this->db->table($this->table)->insert($data);
            $query = $query ? true : false;
            
            return [
                'status'   => $query ? 201   : 500,
                'error'    => $query ? false : true,
                'messages' => $query ? "register success" : "register failed",
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
