<?php

namespace App\Controllers;
use App\Controllers\BaseController;

use App\Models\UserModel;
use App\Models\TransaksiModel;

class Nasabah extends BaseController
{
    public $userModel;

	public function __construct()
    {
        $this->userModel  = new UserModel;
    }

    /**
     * Dashboaard nasabah
     */
    public function dashboardNasabah()
    {
        $token  = (isset($_COOKIE['token'])) ? $_COOKIE['token'] : null;
        $result = $this->checkToken($token,false);
        
        $data   = [
            'title'     => 'Nasabah | dashboard',
            'token'     => $token,
            'privilege' => (isset($result['data']['privilege'])) ? $result['data']['privilege'] : null,
        ];

        if ($result['success'] == false) {
            setcookie('token', null, -1, '/'); 
            unset($_COOKIE['token']); 

            return redirect()->to(base_url().'/login');
        } 
        else if($data['privilege'] !== 'nasabah') {
            return redirect()->to(base_url().'/notfound');
        } 
        else {
            setcookie('token',$token,$this->cookieOps($result['data']['expired']));
            return view('Nasabah/index',$data);
        }
    }
    
    /**
     * Profile nasabah
     */
    public function profileNasabah()
    {
        $token  = (isset($_COOKIE['token'])) ? $_COOKIE['token'] : null;
        $result = $this->checkToken($token,false);

        $data = [
            'title'     => 'Nasabah | profile',
            'token'     => $token,
            'privilege' => (isset($result['data']['privilege'])) ? $result['data']['privilege'] : null,
        ];

        if ($result['success'] == false) {
            setcookie('token', null, -1, '/'); 
            unset($_COOKIE['token']); 
            return redirect()->to(base_url().'/login');
        } 
        else if($data['privilege'] !== 'nasabah') {
            return redirect()->to(base_url().'/notfound');
        } 
        else {
            setcookie('token',$token,$this->cookieOps($result['data']['expired']));
            return view('Nasabah/profilenasabah',$data);
        }

    }

    /**
     * Check nasabah session
     *   url    : domain.com/nasabah/sessioncheck
     *   method : GET
     */
    public function sessionCheck(): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],'nasabah');

        return $this->respond($result,200);
    }

    /**
     * Get data profile
     *   url    : domain.com/nasabah/getprofile
     *   method : GET
     */
    public function getProfile(): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],'nasabah');

        $id        = $result['data']['userid'];
        $dbrespond = $this->userModel->getProfileUser($id);

        return $this->respond($dbrespond,$dbrespond['status']);
    }

    /**
     * Edit profile
     *   url    : domain.com/nasabah/editprofile
     *   method : PUT
     */
    public function editProfile(): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],'nasabah');
        
        $this->_methodParser('data');
        global $data;
        $data['id'] = $result['data']['userid']; 
        
        $this->validation->run($data,'editProfileNasabah');
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
            $id           = $data['id'];
            $dataNasabah  = $this->userModel->db->table('users')->select('password')->where("id",$id)->get()->getResultArray();

            if (!empty($dataNasabah)) {
                $newpass = '';
                $oldpass = '';

                if (isset($data['new_password'])) {
                    $this->validation->run($data,'newPasswordWithOld');
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
                        $newpass = $data['new_password'];
                        $oldpass = $data['old_password'];
                    }
                }
        
                $data = [
                    "id"           => $data['id'],
                    "username"     => $this->stringSanetize($data['username'],false),
                    "email"        => $data['email'] ? $this->stringSanetize($data['email'],false) : null,
                    "nama_lengkap" => $this->stringSanetize($data['nama_lengkap'],true),
                    "nik"          => $data['nik'] ? $this->stringSanetize($data['nik'],false): null,
                    "notelp"       => $data['notelp'] ? $this->stringSanetize($data['notelp'],false): null,
                    "alamat"       => $data['alamat'] ? $this->stringSanetize($data['alamat'],false): null,
                    "tgl_lahir"    => $this->stringSanetize($data['tgl_lahir'],false),
                    "kelamin"      => $this->stringSanetize($data['kelamin'],true),
                ];

                if ($newpass != '') {
                    $dbPass = $this->decrypt($dataNasabah[0]['password']);
                    
                    if ($oldpass === $dbPass) {
                        $data['password'] = $this->encrypt($newpass);
                        unset($data['new_password']);
                        unset($data['old_password']);
                    } 
                    else {
                        return $this->fail(["old_password" => "password lama anda salah"],400,true);
                    }
                }

                $dbrespond = $this->userModel->editUser($data);
        
                return $this->respond($dbrespond,$dbrespond['status']);
            } 
            else {
                return $this->fail("nasabah with id $id not found",404,true);
            }
            
        }
    }

    /**
     * Logout
     *   url    : domain.com/nasabah/logout
     *   method : DELETE
     */
    public function logout(): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],'nasabah');

        $id        = $result['data']['userid'];
        $dbrespond = $this->userModel->setTokenNull($id);

        return $this->respond($dbrespond,$dbrespond['status']);
    }

    /**
     * Get wilayah
     *   url    : domain.com/nasabah/wilayah
     *   method : GET
     */
    public function getWilayah(): object
    {
        $dbrespond = $this->userModel->getWilayah();

        return $this->respond($dbrespond,$dbrespond['status']);
    }

    /**
     * Send kritik
     *   url    : domain.com/nasabah/sendkritik
     *   method : POST
     */
    public function sendKritik(): object
    {
        $this->validation->run($_POST,'sendKritikValidate');
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
            $sendEmail = $this->sendKritikSaran($_POST['name'],$_POST['email'],$_POST['message']);

            $response = [
                'status'   => ($sendEmail == true) ? 201   : 500,
                "error"    => ($sendEmail == true) ? false : true,
                'messages' => ($sendEmail == true) ? 'kritik dan saran successfully sent' : $sendEmail,
            ];

            return $this->respond($response,$response['status']);
        }
    }
}