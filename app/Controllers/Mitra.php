<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\MitraModel;

class Mitra extends BaseController
{
    public $mitraModel;

	public function __construct()
    {
        $this->mitraModel  = new MitraModel;
    }

    // add mitra
	public function addMitra(): object
    {
        $result = $this->checkToken();
        $this->checkPrivilege($result['data']['privilege'],['admin','superadmin']);

        $data   = $this->request->getPost(); 
        $data['icon'] = $this->request->getFile('icon'); 
        
        $this->validation->run($data,'mitraValidate');
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
                "name"        => trim($data['mitra_name']),
                "description" => trim($data['description']),
            ];

            if ($file->move('assets/images/icon-mitra/',$newFileName)) {
                $dbresponse = $this->mitraModel->addMitra($data);

                if ($dbresponse['error'] == true) {
                    unlink('./assets/images/icon-mitra/'.$newFileName);
                } 

                return $this->respond($dbresponse,$dbresponse['status']);
            } 
            else {
                $response = [
                    'status'   => 500,
                    'error'    => false,
                    'messages' => 'storage icon mitra penuh',
                ];
                
                unlink('./assets/images/icon-mitra/'.$newFileName);
                return $this->respond($response,500);
            }
        }
    }

    // get mitra
	public function getMitra(): object
    {
        $dbresponse = $this->mitraModel->getMitra();

        return $this->respond($dbresponse,$dbresponse['status']);
    }

    // delete mitra
	public function deleteMitra(): object
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
            $old_icon   = $this->mitraModel->getOldIcon($this->request->getGet('id'));
            $dbresponse = $this->mitraModel->deleteMitra($this->request->getGet('id'));
            
            if ($dbresponse['error'] == false) {
                // delete local icon
                unlink('./assets/images/icon-mitra/'.$old_icon);
            } 

            return $this->respond($dbresponse,$dbresponse['status']);
        }
    }
}
