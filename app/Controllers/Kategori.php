<?php

namespace App\Controllers;
use App\Controllers\BaseController;

use App\Models\KategoriModel;

class Kategori extends BaseController
{
    public $kategoriModel;

	public function __construct()
    {
        $this->kategoriModel  = new KategoriModel;
    }

    // add kategori
	public function addKategori(string $tableName = ""): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $data   = $this->request->getPost(); 

        if ($tableName == 'kategori_sampah') {
            $this->validation->run($data,'kategoriSampahValidate');
        } 
        else if ($tableName == 'kategori_artikel') {
            $data['icon'] = $this->request->getFile('icon'); 
            $this->validation->run($data,'kategoriArtikelValidate');
        }
        else {
            die;
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
            $idKategori    = '';
            $lastKategori  = $this->kategoriModel->getLastKategori($tableName);

            if ($lastKategori['error'] == false) {
                $lastID     = $lastKategori['data']['id'];
                $lastID     = (int)substr($lastID,2)+1;
                $lastID     = sprintf('%02d',$lastID);
                $idKategori = ($tableName=='kategori_sampah') ? 'KS'.$lastID : 'KA'.$lastID;
            }
            else if ($lastKategori['status'] == 404) {
                $idKategori = ($tableName=='kategori_sampah') ? 'KS01' : 'KA01';
            } 
            else {
                return $this->respond($lastKategori,$lastKategori['status']);
            }

            if ($tableName == 'kategori_sampah') {
                $data = [
                    "id"         => $idKategori,
                    "name"       => trim($data['kategori_name']),
                    "created_at" => (int)time(),
                ];

                $dbresponse = $this->kategoriModel->addKategori($data,$tableName);
                return $this->respond($dbresponse,$dbresponse['status']);
            } 
            else {
                $file        = $data['icon'];
                $typeFile    = explode('/',$file->getClientMimeType());
                $newFileName = uniqid().'.'.end($typeFile);
                $dbFileName  = $newFileName;

                $data = [
                    "id"             => $idKategori,
                    "icon"           => $dbFileName,
                    "name"           => trim($data['kategori_name']),
                    "description"    => trim($data['description']),
                    "kategori_utama" => (trim($data['kategori_utama']) == '1') ? true : false,
                    "created_at"     => (int)time(),
                ];

                if ($data['kategori_utama'] == true) {
                    $totKategoriUtama = $this->kategoriModel->countKategoriUtama();

                    if((int)$totKategoriUtama > 2){
                        $response = [
                            'status'   => 400,
                            'error'    => true,
                            'messages' => "kategori utama maksimal 3",
                        ];
                
                        return $this->respond($response,400);
                    }
                }

                if ($file->move('assets/images/icon-kategori-artikel/',$newFileName)) {
                    $dbresponse = $this->kategoriModel->addKategori($data,$tableName);
    
                    if ($dbresponse['error'] == true) {
                        unlink('./assets/images/icon-kategori-artikel/'.$newFileName);
                    } 
    
                    return $this->respond($dbresponse,$dbresponse['status']);
                } 
                else {
                    $response = [
                        'status'   => 500,
                        'error'    => false,
                        'messages' => 'storage thumbnail berita penuh',
                    ];
                    
                    unlink('./assets/images/icon-kategori-artikel/'.$newFileName);
                    return $this->respond($response,500);
                }
            }
        }
    }

    public function editKategoriArtikel(): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $this->_methodParser('data');
        global $data;

        $this->validation->run($data,'editKategoriArtikelValidate');
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
            $dataInDb = $this->kategoriModel->getDetilKategoriArtikel($data['id']);
            
            $data = [
                "id"             => $data['id'],
                "name"           => trim($data['kategori_name']),
                "description"    => trim($data['description']),
                "kategori_utama" => (trim($data['kategori_utama']) == '1') ? true : false,
            ];
            
            if ($data['kategori_utama'] == true && $dataInDb['kategori_utama'] == '0') {
                $totKategoriUtama = $this->kategoriModel->countKategoriUtama();

                if((int)$totKategoriUtama > 2){
                    $response = [
                        'status'   => 400,
                        'error'    => true,
                        'messages' => "kategori utama maksimal 3",
                    ];
            
                    return $this->respond($response,400);
                }
            }

            if ($this->request->getFile('icon')) {
                $xx['icon'] = $this->request->getFile('icon');

                $this->validation->run($xx,'newIconKategoriArtikel');
                $errors = $this->validation->getErrors();

                if($errors) {
                    $response = [
                        'status'   => 400,
                        'error'    => true,
                        'messages' => $errors,
                    ];
            
                    return $this->respond($response,400);
                }  

                $file          = $xx['icon'];
                $typeFile      = explode('/',$file->getClientMimeType());
                $newFileName   = uniqid().'.'.end($typeFile);
                $dbFileName    = $newFileName;
                $old_thumbnail = $this->kategoriModel->getOldIcon($data['id']);
                $data['icon']  = $dbFileName;
            }

            $unlinkOldIcon = false;
            if (isset($xx['icon'])) {
                if (rename($file->getRealPath(),'./assets/images/icon-kategori-artikel/'.$newFileName)) {
                    $unlinkOldIcon = true;
                    chmod("./assets/images/icon-kategori-artikel/".$newFileName, 644);
                } 
                else {
                    $response = [
                        'status'   => 500,
                        'error'    => false,
                        'messages' => 'storage icoin kategori penuh',
                    ];
                    
                    return $this->respond($response,500);
                }
            }

            $dbresponse = $this->kategoriModel->editKategoriArtikel($data);

            if ($dbresponse['error'] == false) {
                if ($unlinkOldIcon) {
                    unlink('./assets/images/icon-kategori-artikel/'.$old_thumbnail);
                }
            } 
            else {
                if ($unlinkOldIcon) {
                    unlink('./assets/images/icon-kategori-artikel/'.$newFileName);
                }
            }

            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }

    // delete kategori
	public function deleteKategori(string $tableName): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        if($this->request->getGet('id') == null) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => 'required parameter id',
            ];
    
            return $this->respond($response,400);
        } 
        else {
            if ($tableName == 'kategori_artikel') {
                $old_icon = $this->kategoriModel->getOldIcon($this->request->getGet('id'));
            }

            $dbresponse = $this->kategoriModel->deleteKategori($this->request->getGet('id'),$tableName);
            
            if ($tableName == 'kategori_artikel') {
                if ($dbresponse['error'] == false) {
                    // delete local icon
                    unlink('./assets/images/icon-kategori-artikel/'.$old_icon);
                } 
            } 

            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }

    // get kategori
	public function getkategori(string $tableName): object
    {
        $dbresponse = $this->kategoriModel->getKategori($tableName);

        return $this->respond($dbresponse,$dbresponse['status']);
    }
}
    