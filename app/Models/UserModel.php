<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['id','username','email','password','nama_lengkap','notelp','alamat','tgl_lahir','kelamin','is_active','last_active','created_at'];

    public function getProfileUser(string $iduser): array
    {
        try {
            $userData = $this->db->table($this->table)->select("id,email,username,nama_lengkap,alamat,notelp,nik,tgl_lahir,kelamin,privilege,last_active,created_at")->where("id",$iduser)->get()->getFirstRow();
            
            if (empty($userData)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "user notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $userData
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

    public function totalAkun(): array
    {
        try {
            $akunAdmin   = $this->db->query("SELECT count(privilege) AS total FROM users WHERE privilege IN('admin','superadmin')")->getResultArray()[0];
            $akunNasabah = $this->db->query("SELECT count(privilege) AS total FROM users WHERE privilege = 'nasabah'")->getResultArray()[0];
            
            $akun = [
                'jml_admin'   => $akunAdmin['total'],
                'jml_nasabah' => $akunNasabah['total'],
            ];

            if ($akun['jml_admin'] == 0 && $akun['jml_nasabah'] == 0) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "akun notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => true,
                    'data'   => $akun
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

    public function getNasabah(array $get): array
    {
        try {
            $query  = "SELECT users.id,users.nama_lengkap,users.alamat,users.last_active,users.is_verify,users.created_at 
            FROM users
            JOIN wilayah ON (users.id = wilayah.id_user) 
            WHERE users.privilege = 'nasabah'";

            if (isset($get['id'])) {
                $query  = "SELECT users.id,users.email,users.username,users.nama_lengkap,users.notelp,users.nik,users.alamat,users.tgl_lahir,users.kelamin,users.last_active,users.is_verify,users.created_at
                FROM users
                WHERE users.privilege = 'nasabah' 
                AND users.id = '".$get['id']."'";
            }
            else if (isset($get['key'])) {
                $query  = "SELECT users.id,users.email,users.username,users.nama_lengkap,users.notelp,users.nik,users.alamat,users.tgl_lahir,users.kelamin,users.last_active,users.is_verify,users.created_at
                FROM users
                WHERE users.privilege = 'nasabah' 
                AND users.id = '".$get['key']."'
                OR users.username = '".$get['key']."'
                OR users.nama_lengkap = '".$get['key']."'";
            }
            else {
                
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
    
                if (isset($get['orderby'])) {
                    if ($get['orderby'] == 'norek') {
                        $query    .= " ORDER BY id ASC";
                    }
                    else{
                        $ascOrDesc = ($get['orderby'] == 'terbaru') ? 'DESC' : 'ASC' ;
                        $query    .= " ORDER BY created_at $ascOrDesc";
                    }
                }
            }


            $query  .= ';';
            $nasabah = $this->db->query($query)->getResultArray();
            
            if (empty($nasabah)) {    
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
                    'data'   => $nasabah
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

    public function getAdmin(?string $idadmin = null,string $currentIdAdmin): array
    {
        try {
            if ($idadmin) {
                $admin = $this->db->table($this->table)->select("id,username,nama_lengkap,alamat,notelp,tgl_lahir,kelamin,privilege,is_active")->where("id",$idadmin)->where("id !=",$currentIdAdmin)->get()->getFirstRow();
            } 
            else {
                // auto non active admin
                $this->nonActiveAdmin();

                $admin = $this->db->table($this->table)->select("id,username,nama_lengkap,privilege,is_active,last_active,created_at")->where("id !=",$currentIdAdmin)->whereIn('privilege',['admin','superadmin'])->orderBy('created_at','DESC')->get()->getResultArray();
            }
            
            if (empty($admin)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "user notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => false,
                    'data'   => $admin
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

    public function nonActiveAdmin(): void
    {
        try {
            $timeNow   = time();
            $batasTime = (int)$timeNow - (86400*30);

            $this->db->query("UPDATE users SET is_active = false WHERE last_active <  $batasTime AND privilege = 'admin'");
        } 
        catch (Exception $e) {
            
        }
    }

    public function editUser(array $data): array
    {
        try {
            $this->db->table($this->table)->where('id',$data['id'])->update($data);
            
            return [
                'status'   => 201,
                'error'    => false,
                'messages' => ($this->db->affectedRows()>0) ? 'edit profile success' : 'nothing updated'
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

    public function setTokenNull(string $id): array
    {
        try {
            $this->db->table($this->table)->where('id',$id)->update(['token' => null]);
            $affectedRows = $this->db->affectedRows();

            return [
                'status'   => ($affectedRows>0) ? 201   : 404,
                'error'    => ($affectedRows>0) ? false : true,
                'messages' => ($affectedRows>0) ? 'logout success' : 'user notfound'
            ];   
        } 
        catch (Exception $e) {
            return [
                'status'  => 500,
                'error'   => true,
                'messages' => $e->getMessage(),
            ];
        }
    }

    public function checkTransaksi(string $iduser): bool
    {
        $transaction = $this->db->table('transaksi')->select('id_user')->where('id_user',$iduser)->limit(1)->get()->getResultArray();

        if (empty($transaction)) {    
            return true;
        }
        else {
            return false;
        }
    }

    public function deleteUser(string $id,string $currentIdAdmin): array
    {
        try {
            if ($this->checkTransaksi($id)) {
                $this->db->table($this->table)->where('id',$id)->where('id !=',$currentIdAdmin)->delete();
                $affectedRows = $this->db->affectedRows();

                return [
                    'status'   => ($affectedRows>0) ? 201   : 404,
                    'error'    => ($affectedRows>0) ? false : true,
                    'messages' => ($affectedRows>0) ?  "delete user with id $id is success" : "user with id '$id' is not found"
                ];  
            } 
            else {
                return [
                    'status'   => 400,
                    'error'    => true,
                    'messages' => "akun ini sudah pernah melakukan transaksi"
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

    public function getWilayah(): array
    {
        try {
            $wilayah = $this->db->query("SELECT distinct kelurahan, kecamatan, kota, provinsi FROM wilayah;")->getResultArray();
            
            if (empty($wilayah)) {    
                return [
                    'status'   => 404,
                    'error'    => true,
                    'messages' => "wilayah notfound",
                ];
            } 
            else {   
                return [
                    'status' => 200,
                    'error'  => true,
                    'data'   => $wilayah
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
}
