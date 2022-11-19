<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\PenghargaanModel;

class Penghargaan extends BaseController
{
    public $penghargaanModel;

	public function __construct()
    {
        $this->penghargaanModel  = new PenghargaanModel;
    }

    // add penghargaan
	public function addPenghargaan(): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $data   = $this->request->getPost(); 
        $data['icon'] = $this->request->getFile('icon'); 
        
        $this->validation->run($data,'penghargaanValidate');
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
            $file        = $data['icon'];
            $typeFile    = explode('/',$file->getClientMimeType());
            $newFileName = uniqid().'.'.end($typeFile);
            $dbFileName  = $newFileName;

            $data = [
                "icon"        => $dbFileName,
                "name"        => trim($data['penghargaan_name']),
                "description" => trim($data['description']),
            ];

            if ($file->move('assets/images/icon-penghargaan/',$newFileName)) {
                $dbresponse = $this->penghargaanModel->addPenghargaan($data);

                if ($dbresponse['error'] == true) {
                    unlink('./assets/images/icon-penghargaan/'.$newFileName);
                } 

                return $this->respond($dbresponse,$dbresponse['status']);
            } 
            else {
                $response = [
                    'status'   => 500,
                    'error'    => false,
                    'messages' => 'storage icon penghargaan penuh',
                ];
                
                unlink('./assets/images/icon-penghargaan/'.$newFileName);
                return $this->respond($response,500);
            }
        }
    }

    // get penghargaan
	public function getPenghargaan(): object
    {
        $dbresponse = $this->penghargaanModel->getPenghargaan();

        return $this->respond($dbresponse,$dbresponse['status']);
    }

    // delete penghargaan
	public function deletePenghargaan(): object
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
            $old_icon   = $this->penghargaanModel->getOldIcon($this->request->getGet('id'));
            $dbresponse = $this->penghargaanModel->deletePenghargaan($this->request->getGet('id'));
            
            if ($dbresponse['error'] == false) {
                // delete local icon
                unlink('./assets/images/icon-penghargaan/'.$old_icon);
            } 

            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }
}
