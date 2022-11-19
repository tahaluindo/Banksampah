<?php

namespace App\Controllers;
use App\Controllers\BaseController;

use App\Models\OtpModel;
use App\Models\LoginModel;

class Otp extends BaseController
{
    public $otpModel;
    public $loginModel;

	public function __construct()
    {
        $this->otpModel   = new OtpModel;
        $this->loginModel = new LoginModel;
    }

    /**
     * Page for OTP Verification
     */
    public function otpView()
    {
        $data = [
            'title'    => 'Nasabah | verifikasi akun',
            'password' => (isset($_POST['password'])) ? $_POST['password'] : '',
            'username_or_email' => (isset($_POST['username_or_email']))    ? $_POST['username_or_email']    : '' ,
        ];

        return view('OtpPage/index',$data);
    }

    /**
     * Verifikasi akun
     *   url    : domain.com/otp/verify
     *   method : POST
     */
    public function verifyOtp(): object
    {
		$data   = $this->request->getPost();
        $this->validation->run($data,'verifyOtpValidate');
        $errors = $this->validation->getErrors();

        if($errors) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => $errors['code_otp'],
            ];
    
            return $this->respond($response,400);
        } 
        else {
            
            $codeOtp    = $this->request->getPost('code_otp');
            $dbrespond  = $this->otpModel->verifyOtp($codeOtp);
    
            return $this->respond($dbrespond,$dbrespond['status']);
        }    
    }
    
    /**
     * Resend OTP
     *   url    : domain.com/otp/resend
     *   method : POST
     */
    public function resendOtp(): object
    {
		$data   = $this->request->getPost();
        $this->validation->run($data,'resendOtpValidate');
        $errors = $this->validation->getErrors();

        if($errors) {
            $response = [
                'status'   => 400,
                'error'    => true,
                'messages' => $errors['username_or_email'],
            ];
    
            return $this->respond($response,400);
        } 
        else {
            
            $username_or_email = $this->request->getPost("username_or_email");
            $nasabahData = $this->loginModel->checkNasabah($username_or_email);

            if ($nasabahData['error'] == false) {
                $email     = $nasabahData['messages']['email'];
                $is_verify = $nasabahData['messages']['is_verify'];
                
                if ($is_verify == '0' || $is_verify == 'f') {
                    $sendEmail = '';
                    $otp       = $this->generateOTP(6);
                    $dbrespond = $this->loginModel->updateNasabahOtp($email,$otp);

                    if ($dbrespond['error'] == false) {
                        $sendEmail = $this->sendOtpToEmail($email,$otp);

                        if ($sendEmail == true) {
                            $response = [
                                'status'   => 200,
                                'error'    => false,
                                'messages' => 'otp berhasil dikirim',
                            ];
                    
                            return $this->respond($response,200);
                        }
                    } 

                    $response = [
                        'status'   => 500,
                        'error'    => true,
                        'messages' => ($sendEmail != '') ? $sendEmail : $dbrespond['message'],
                    ];
            
                    return $this->respond($response,500);
                } 
                else {
                    $response = [
                        'status'   => 400,
                        'error'    => true,
                        'messages' => "akun sudah terverifikasi",
                    ];
            
                    return $this->respond($response,400);
                }
            } 
            else {
                return $this->respond($nasabahData,$nasabahData['status']);
            }
        }    
    }
}
