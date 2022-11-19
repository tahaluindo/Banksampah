<?php

namespace App\Controllers;
use App\Controllers\BaseController;

use App\Models\SampahModel;

class Sampah extends BaseController
{
    public $sampahModel;

	public function __construct()
    {
        $this->sampahModel    = new SampahModel;
    }
    
    // add sampah
	public function addSampah(): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $data   = $this->request->getPost(); 
        $this->validation->run($data,'addSampahValidate');
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
            $idSampah    = '';
            $lastSampah  = $this->sampahModel->getLastSampah();

            if ($lastSampah['error'] == false) {
                $lastID    = $lastSampah['data']['id'];
                $lastID    = (int)substr($lastID,2)+1;
                $lastID    = sprintf('%03d',$lastID);
                $idSampah  = 'S'.$lastID;
            }
            else if ($lastSampah['status'] == 404) {
                $idSampah = 'S001';
            } 
            else {
                return $this->respond($lastSampah,$lastSampah['status']);
            }

            $data = [
                "id"          => $idSampah,
                "id_kategori" => trim($data['id_kategori']),
                "jenis"       => strtolower(trim($data['jenis'])),
                "harga"       => trim($data['harga']),
                "harga_pusat" => trim($data['harga_pusat']),
            ];

            $dbresponse = $this->sampahModel->addSampah($data);

            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }

    // edit sampah
	public function editSampah(): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $this->_methodParser('data');
        global $data;

        $this->validation->run($data,'updateSampahValidate');
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
            $data = [
                "id"          => $data['id'],
                "id_kategori" => trim($data['id_kategori']),
                "jenis"       => strtolower(trim($data['jenis'])),
                "harga"       => trim($data['harga']),
                "harga_pusat" => trim($data['harga_pusat']),
            ];

            $dbresponse = $this->sampahModel->editSampah($data);

            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }

    // delete sampah
	public function deleteSampah(): object
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
            $dbresponse = $this->sampahModel->deleteSampah($this->request->getGet('id'));

            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }

    // get sampah
    public function getSampah(): object
    {
        $isAdmin    = false;
        $dbResponse = $this->sampahModel->getSampah($this->request->getGet());

        if ($this->request->getHeader('token')) {
            $result = $this->checkToken();

            if (in_array($result['data']['privilege'],['admin','superadmin'])) {
                $isAdmin = true;
            }
        }

        if ($isAdmin == false && isset($dbResponse["data"])) {
            $dataBaru = [];
            foreach ($dbResponse["data"] as $d) {
                unset($d["harga_pusat"]);
                unset($d["jumlah"]);
                $dataBaru[] = $d;
            }
            $dbResponse["data"] = $dataBaru;
        }
    
        return $this->respond($dbResponse,$dbResponse['status']);
    }
}
