<?php

namespace App\Controllers;
use App\Controllers\BaseController;

use App\Models\LoginModel;

class Login extends BaseController
{
    public $loginModel;

	public function __construct()
    {
        $this->loginModel = new LoginModel;
    }

    /**
     * Nasabah Login Page
     */
    public function nasabahLoginView()
    {
        $data = [
            'title'   => 'Nasabah | login',
            'lasturl' => (isset($_COOKIE['lasturl'])) ? $_COOKIE['lasturl'] : '',
        ];
        
        $token     = (isset($_COOKIE['token'])) ? $_COOKIE['token'] : null;
        $result    = $this->checkToken($token, false);
        $privilege = (isset($result['data']['privilege'])) ? $result['data']['privilege'] : null;

        if ($token == null || $token == 'null') {
            if ($data['lasturl'] == 'null') {
                setcookie('lasturl', null, -1, '/'); 
                unset($_COOKIE['lasturl']);
            }

            setcookie('token', null, -1, '/'); 
            unset($_COOKIE['token']);

            return view('LoginPage/index',$data);
        } 
        else {
            if ($privilege == 'nasabah') {
                return redirect()->to(base_url().'/nasabah');
            } 
            else {
                return redirect()->to(base_url().'/admin');
            }
        }
    }

    /**
     * Admin Login Page
     */
    public function adminLoginView()
    {
        $data = [
            'title'   => 'Admin | login',
            'lasturl' => (isset($_COOKIE['lasturl'])) ? $_COOKIE['lasturl'] : '',
        ];

        $token     = (isset($_COOKIE['token'])) ? $_COOKIE['token'] : null;
        $result    = $this->checkToken($token, false);
        $privilege = (isset($result['data']['privilege'])) ? $result['data']['privilege'] : null;
        
        if ($token == null || $token == 'null') {
            if ($data['lasturl'] == 'null') {
                setcookie('lasturl', null, -1, '/'); 
                unset($_COOKIE['lasturl']);
            }

            setcookie('token', null, -1, '/'); 
            unset($_COOKIE['token']);

            return view('LoginPage/loginAdmin',$data);
        } 
        else {
            if ($privilege == 'nasabah') {
                return redirect()->to(base_url().'/nasabah');
            } 
            else {
                return redirect()->to(base_url().'/admin');
            }
        }
    }

    /**
     * Forgot password
     *   url    : domain.com/login/forgotpass
     *   method : POST
     */
    public function forgotPassword(): object
    {
        $this->validation->run($_POST,'forgotPasswordValidate');
        $errors = $this->validation->getErrors();

        if($errors) {
            $response = [
                'status'   => 404,
                'error'    => true,
                'messages' => $errors['email'],
            ];
    
            return $this->respond($response,404);
        } 
        else {
            $email         = $this->request->getPost("email");
            $nasabahData   = $this->loginModel->checkNasabah($email);
            $database_pass = $this->decrypt($nasabahData['messages']['password']);
            $sendEmail     = $this->sendPassToEmail($email,$database_pass);

            $response = [
                'status'   => ($sendEmail == true) ? 200   : 500,
                "error"    => ($sendEmail == true) ? false : true,
                'messages' => ($sendEmail == true) ? 'password telah terkirim' : $sendEmail,
            ];

            return $this->respond($response,$response['status']);
        }
        
    }

    /**
     * Login
     *   url    : domain.com/login/nasabah
     *   method : POST
     */
    public function nasabahLogin(): object
    {
		$data   = $this->request->getPost();
        $this->validation->run($data,'nasabahLoginValidate');
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
            $username_or_email = $this->request->getPost("username_or_email");
            $nasabahData = $this->loginModel->checkNasabah($username_or_email);

            if ($nasabahData['error'] == false) {
                $login_pass    = $this->request->getPost("password");
                $database_pass = $this->decrypt($nasabahData['messages']['password']);
                
                if ($login_pass === $database_pass) {
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
                                    'status'   => 401,
                                    'error'    => true,
                                    'messages' => 'account is not verify',
                                ];
                        
                                return $this->respond($response,401);
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
                        // database row id
                        $id           = $nasabahData['messages']['id'];
                        // rememberMe check
                        $rememberme   = ($this->request->getPost("rememberme") == '1') ? true : false;
                        // generate new token
                        $token        = $this->generateToken(
                            $id,
                            $rememberme,
                            $nasabahData['messages']['password'],
                            'nasabah'
                        );

                        // edit nasabah in database
                        $dataLogin = [
                            'id'          => $id,
                            'token'       => $token,
                            'last_active' => (int)time(),
                        ];
                        
                        $dbrespond = $this->loginModel->updateUserLogin($dataLogin);

                        return $this->respond($dbrespond,$dbrespond['status']);
                    } 
                } 
                else {
                    $response = [
                        'status'   => 404,
                        'error'    => true,
                        'messages' => [
                            'password' => "password not match",
                        ],
                    ];
            
                    return $this->respond($response,404);
                }
            } 
            else {
                return $this->respond($nasabahData,$nasabahData['status']);
            }
        }
    }

    /**
     * Login
     *   url    : domain.com/login/admin
     *   method : POST
     */
    public function adminLogin(): object
    {
        $data   = $this->request->getPost();
        $this->validation->run($data,'adminLoginValidate');
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
            // get admin data from DB by username
            $adminData  = $this->loginModel->getAdminByUsername($this->request->getPost("username"));

            if ($adminData['error'] == false) {
                $login_pass    = $this->request->getPost("password");
                $database_pass = $adminData['messages']['password'];

                // verify password
                if (password_verify($login_pass,$database_pass)) {

                    // is admin active or not
                    $is_active   = $adminData['messages']['is_active'];
                    $last_active = (int)$adminData['messages']['last_active'];
                    $timeNow     = time();
                    $batasTime   = (int)$timeNow - (86400*30);
                    $privilege   = $adminData['messages']['privilege'];

                    // if ($last_active <  $batasTime && $is_active == false) {
                    if ($last_active <  $batasTime && $privilege != 'superadmin' || $is_active == 'f') {
                        $response = [
                            'status'   => 401,
                            'error'    => true,
                            'messages' => 'akun tidak aktif',
                        ];
                
                        return $this->respond($response,401);
                    } 
                    else {
                        $id    = $adminData['messages']['id'];
                        $token = $this->generateToken(
                            $id,
                            false,
                            $database_pass,
                            $privilege,
                        );

                        // edit admin in database
                        $dataLogin = [
                            'id'          => $id,
                            'token'       => $token,
                            'last_active' => (int)time(),
                        ];

                        $dbrespond = $this->loginModel->updateUserLogin($dataLogin);

                        return $this->respond($dbrespond,$dbrespond['status']);
                    } 
                } 
                else {
                    $response = [
                        'status'   => 404,
                        'error'    => true,
                        'messages' => [
                            'password' => "password not match",
                        ],
                    ];
            
                    return $this->respond($response,404);
                }
            } 
            else {
                return $this->respond($adminData,$adminData['status']);
            }
        }
    }
}
