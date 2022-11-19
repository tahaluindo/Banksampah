<?php

namespace Config;

use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation
{
    //--------------------------------------------------------------------
    // Setup
    //--------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    //--------------------------------------------------------------------
    // Rules
    //--------------------------------------------------------------------

    // confirm delete 
    public $confirmDelete = [
		'hashedpass' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'hashed password is required',
            ],
		],
		'password' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'password is required',
            ],
		],
	];

    // new password with old
	public $newPasswordWithOld = [
		'new_password' => [
            'rules'  => 'min_length[8]|max_length[20]',
            'errors' => [
                'min_length'  => 'min 8 character',
                'max_length'  => 'max 20 character',
            ],
		],
		'old_password' => [
            'rules'  => 'required',
            'errors' => [
                'required'   => 'old password is required',
            ],
		],
	];

    // new password
	public $newPassword = [
		'new_password' => [
            'rules'  => 'min_length[8]|max_length[20]',
            'errors' => [
                'min_length'  => 'min 8 character',
                'max_length'  => 'max 20 character',
            ],
		],
	];

    // id nasabah validate
    public $idNasabahValidate = [
        'id' => [
            'rules'  => 'required|is_not_unique[users.id]',
            'errors' => [
                'required'      => 'id is required',
                'is_not_unique' => 'nasabah with id ({value}) is not found',
            ],
		],
	];

    // email validate
    public $emailValidate = [
		'email' => [
            'rules'  => 'required|is_unique[users.email]|valid_email',
            'errors' => [
                'required'     => 'email is required',
                'is_unique'    => 'email sudah terdaftar',
                'valid_email'  => 'Email is not in format',
            ],
		],
    ];

    public $emailValidateById = [
		'email' => [
            'rules'  => 'required|is_unique[users.email,users.id,{id}]|valid_email',
            'errors' => [
                'required'     => 'email is required',
                'is_unique'    => 'email sudah terdaftar',
                'valid_email'  => 'Email is not in format',
            ],
		],
    ];

    // tgl_lahir validate
    public $tglLahirValidate = [
		'tgl_lahir' => [
            'rules'  => 'regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'regex_match' => 'format must be dd-mm-yyyy',
            ],
		]
    ];

    // is_verify validate
    public $isVerifyValidate = [
		'is_verify' => [
            'rules'  => 'required|in_list[1,0]',
            'errors' => [
                'required' => 'is_verify is required',
                'in_list'  => "value must be '1' or '0'",
            ],
		]
    ];

    // forgot password
    public $forgotPasswordValidate = [
		'email' => [
            'rules'  => 'required|is_not_unique[users.email]',
            'errors' => [
                'required'      => 'email is required',
                'is_not_unique' => 'email tidak terdaftar',
            ],
		],
	];

    // Otp verification
	public $verifyOtpValidate = [
		'code_otp' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'code_otp is required',
            ],
		]
	];

    // Resend OTP
	public $resendOtpValidate = [
		'username_or_email' => [
            'rules'  => 'required',
            'errors' => [
                'required'    => 'username_or_email is required',
            ],
		],
	];

    /**
     * REGSITER VALIDATE
     * ================================
     */
    // nasabah
	public $nasabahRegisterValidate = [
		'email' => [
            'rules'  => 'required|is_unique[users.email]|valid_email',
            'errors' => [
                'required'     => 'email is required',
                'is_unique'    => 'email sudah terdaftar',
                'valid_email'  => 'Email is not in format',
            ],
		],
		'username' => [
            'rules'  => 'required|min_length[8]|max_length[20]|is_unique[users.username]',
            'errors' => [
                'required'    => 'username is required',
                'min_length'  => 'min 8 character',
                'max_length'  => 'max 20 character',
                'is_unique'   => 'username sudah terdaftar',
            ],
		],
		'password' => [
            'rules'  => 'required|min_length[8]|max_length[20]',
            'errors' => [
                'required'    => 'password is required',
                'min_length'  => 'min 8 character',
                'max_length'  => 'max 20 character',
            ],
		],
		'nama_lengkap' => [
            'rules'  => 'required|max_length[40]',
            'errors' => [
                'required'    => 'nama lengkap is required',
                'max_length'  => 'max 40 character',
            ],
		],
		'notelp' => [
            'rules'  => 'required|max_length[14]|is_unique[users.notelp]|is_natural',
            'errors' => [
                'required'    => 'nomor telepon is required',
                'max_length'  => 'max 14 character',
                'is_unique'   => 'no.telp sudah dipakai',
                'is_natural'  => 'only number allowed',
            ],
		],
		'nik' => [
            'rules'  => 'required|min_length[16]|max_length[16]|is_unique[users.nik]|is_natural',
            'errors' => [
                'required'    => 'nik is required',
                'min_length'  => 'min 16 character',
                'max_length'  => 'max 16 character',
                'is_unique'   => 'nik sudah dipakai',
                'is_natural'  => 'only number allowed',
            ],
		],
		'alamat' => [
            'rules'  => 'required|max_length[255]',
            'errors' => [
                'required'    => 'alamat is required',
                'max_length'  => 'max 255 character',
            ],
		],
		'tgl_lahir' => [
            'rules'  => 'required|regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'required'    => 'tgl lahir is required',
                'regex_match' => 'format must be dd-mm-yyyy',
            ],
		],
		'kelamin' => [
            'rules'  => 'required|in_list[laki-laki,perempuan]',
            'errors' => [
                'required'    => 'kelamin is required',
                'in_list'     => "value must be 'laki-laki' or 'perempuan'",
            ],
		],
		'rt' => [
            'rules'  => 'required|min_length[3]|max_length[3]|is_natural',
            'errors' => [
                'required'    => 'rt is required',
                'min_length'  => 'min 3 character',
                'max_length'  => 'max 3 character',
                'is_natural'  => 'only number allowed',
            ],
		],
		'rw' => [
            'rules'  => 'required|min_length[3]|max_length[3]|is_natural',
            'errors' => [
                'required'    => 'rw is required',
                'min_length'  => 'min 3 character',
                'max_length'  => 'max 3 character',
                'is_natural'  => 'only number allowed',
            ],
		],
		'kodepos' => [
            'rules'  => 'required|is_natural|max_length[5]',
            'errors' => [
                'required'    => 'kodepos is required',
                'is_natural'  => 'only number allowed',
                'max_length'  => 'max 5 character',
            ],
		],
		'kelurahan' => [
            'rules'  => 'required|max_length[200]',
            'errors' => [
                'required'    => 'kelurahan is required',
                'max_length'  => 'max 200 character',
            ],
		],
		'kecamatan' => [
            'rules'  => 'required|max_length[200]',
            'errors' => [
                'required'    => 'kecamatan is required',
                'max_length'  => 'max 200 character',
            ],
		],
		'kota' => [
            'rules'  => 'required|max_length[200]',
            'errors' => [
                'required'    => 'kota is required',
                'max_length'  => 'max 200 character',
            ],
		],
		'provinsi' => [
            'rules'  => 'required|max_length[200]',
            'errors' => [
                'required'    => 'provinsi is required',
                'max_length'  => 'max 200 character',
            ],
		],
	];

    public $nasabahRegisterValidateByAdmin = [
		'nama_lengkap' => [
            'rules'  => 'required|max_length[40]',
            'errors' => [
                'required'    => 'nama lengkap is required',
                'max_length'  => 'max 40 character',
            ],
		],
		'notelp' => [
            'rules'  => 'max_length[14]|is_unique[users.notelp]',
            'errors' => [
                'max_length'  => 'max 14 character',
                'is_unique'   => 'no.telp sudah dipakai',
                // 'is_natural'  => 'only number allowed',
            ],
		],
		'nik' => [
            'rules'  => 'max_length[16]|is_unique[users.nik]',
            'errors' => [
                'max_length'  => 'max 16 character',
                'is_unique'   => 'nik sudah dipakai',
                // 'is_natural'  => 'only number allowed',
            ],
		],
		'alamat' => [
            'rules'  => 'max_length[255]',
            'errors' => [
                'max_length'  => 'max 255 character',
            ],
		],
		'kelamin' => [
            'rules'  => 'required|in_list[laki-laki,perempuan]',
            'errors' => [
                'required'    => 'kelamin is required',
                'in_list'     => "value must be 'laki-laki' or 'perempuan'",
            ],
		],
		'rt' => [
            'rules'  => 'required|min_length[3]|max_length[3]|is_natural',
            'errors' => [
                'required'    => 'rt is required',
                'min_length'  => 'min 3 character',
                'max_length'  => 'max 3 character',
                'is_natural'  => 'only number allowed',
            ],
		],
		'rw' => [
            'rules'  => 'required|min_length[3]|max_length[3]|is_natural',
            'errors' => [
                'required'    => 'rw is required',
                'min_length'  => 'min 3 character',
                'max_length'  => 'max 3 character',
                'is_natural'  => 'only number allowed',
            ],
		],
		'kodepos' => [
            'rules'  => 'required|is_natural|max_length[5]',
            'errors' => [
                'required'    => 'kodepos is required',
                'is_natural'  => 'only number allowed',
                'max_length'  => 'max 5 character',
            ],
		],
		'kelurahan' => [
            'rules'  => 'required|max_length[200]',
            'errors' => [
                'required'    => 'kelurahan is required',
                'max_length'  => 'max 200 character',
            ],
		],
		'kecamatan' => [
            'rules'  => 'required|max_length[200]',
            'errors' => [
                'required'    => 'kecamatan is required',
                'max_length'  => 'max 200 character',
            ],
		],
		'kota' => [
            'rules'  => 'required|max_length[200]',
            'errors' => [
                'required'    => 'kota is required',
                'max_length'  => 'max 200 character',
            ],
		],
		'provinsi' => [
            'rules'  => 'required|max_length[200]',
            'errors' => [
                'required'    => 'provinsi is required',
                'max_length'  => 'max 200 character',
            ],
		],
	];
    
	public $adminRegisterValidate = [
		'username' => [
            'rules'  => 'required|min_length[8]|max_length[20]|is_unique[users.username]',
            'errors' => [
                'required'    => 'username is required',
                'min_length'  => 'min 8 character',
                'max_length'  => 'max 20 character',
                'is_unique'   => 'username sudah terdaftar',
            ],
		],
		'password' => [
            'rules'  => 'required|min_length[8]|max_length[20]',
            'errors' => [
                'required'    => 'password is required',
                'min_length'  => 'min 8 character',
                'max_length'  => 'max 20 character',
            ],
		],
		'nama_lengkap' => [
            'rules'  => 'required|max_length[40]',
            'errors' => [
                'required'    => 'nama lengkap is required',
                'max_length'  => 'max 40 character',
            ],
		],
		'notelp' => [
            'rules'  => 'max_length[14]|is_unique[users.notelp]',
            'errors' => [
                'max_length'  => 'max 14 character',
                'is_unique'   => 'no.telp sudah dipakai',
            ],
		],
		'alamat' => [
            'rules'  => 'max_length[255]',
            'errors' => [
                'max_length'  => 'max 255 character',
            ],
		],
		'tgl_lahir' => [
            'rules'  => 'required|regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'required'    => 'tgl lahir is required',
                'regex_match' => 'format must be dd-mm-yyyy',
            ],
		],
		'kelamin' => [
            'rules'  => 'required|in_list[laki-laki,perempuan]',
            'errors' => [
                'required'    => 'kelamin is required',
                'in_list'     => "value must be 'laki-laki' or 'perempuan'",
            ],
		],
		'privilege' => [
            'rules'  => 'required|in_list[superadmin,admin]',
            'errors' => [
                'required'    => 'kelamin is required',
                'in_list'     => "value must be 'superadmin' or 'admin'",
            ],
		]
	];

    /**
     * LOGIN Validate
     * =================================
     */
    // nasabah
	public $nasabahLoginValidate = [
		'username_or_email' => [
            'rules'  => 'required',
            'errors' => [
                'required'    => 'email is required',
            ],
		],
		'password' => [
            'rules'  => 'required',
            'errors' => [
                'required'    => 'password is required',
            ],
		],
	];

    // admin
    public $adminLoginValidate = [
		'username' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'username is required',
            ],
		],
		'password' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'password is required',
            ],
		],
	];

    /**
     * NASABAH Controller Validate
     * ================================
     */
    //  edit profile
	public $editProfileNasabah = [
		'username' => [
            'rules'  => 'required|min_length[8]|max_length[20]|is_unique[users.username,users.id,{id}]',
            'errors' => [
                'required'    => 'username is required',
                'min_length'  => 'min 8 character',
                'max_length'  => 'max 20 character',
                'is_unique'   => 'username sudah terdaftar',
            ],
		],
		'email' => [
            'rules'  => 'is_unique[users.email,users.id,{id}]',
            'errors' => [
                'is_unique'    => 'email sudah terdaftar',
            ],
		],
		'nama_lengkap' => [
            'rules'  => 'required|max_length[40]',
            'errors' => [
                'required'    => 'nama lengkap is required',
                'min_length'  => 'min 6 character',
                'max_length'  => 'max 40 character',
            ],
		],
		'nik' => [
            'rules'  => 'max_length[16]|is_unique[users.nik,users.id,{id}]',
            'errors' => [
                'max_length'  => 'max 16 character',
                'is_unique'   => 'nik sudah dipakai',
            ],
		],
		'notelp' => [
            'rules'  => 'max_length[14]|is_unique[users.notelp,users.id,{id}]',
            'errors' => [
                'max_length'  => 'max 14 character',
                'is_unique'   => 'no.telp sudah dipakai',
            ],
		],
		'alamat' => [
            'rules'  => 'max_length[255]',
            'errors' => [
                'required'    => 'alamat is required',
                'max_length'  => 'max 255 character',
            ],
		],
		'tgl_lahir' => [
            'rules'  => 'required|regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'required'    => 'tgl lahir is required',
                'regex_match' => 'format must be dd-mm-yyyy',
            ],
		],
		'kelamin' => [
            'rules'  => 'required|in_list[laki-laki,perempuan]',
            'errors' => [
                'required'    => 'kelamin is required',
                'in_list'     => "value must be 'laki-laki' or 'perempuan'",
            ],
		]
	];

	public $editProfileNasabahByAdmin = [
		'username' => [
            'rules'  => 'required|min_length[8]|max_length[20]|is_unique[users.username,users.id,{id}]',
            'errors' => [
                'required'    => 'username is required',
                'min_length'  => 'min 8 character',
                'max_length'  => 'max 20 character',
                'is_unique'   => 'username sudah terdaftar',
            ],
		],
		'nama_lengkap' => [
            'rules'  => 'required|max_length[40]',
            'errors' => [
                'required'    => 'nama lengkap is required',
                'min_length'  => 'min 6 character',
                'max_length'  => 'max 40 character',
            ],
		],
		'nik' => [
            'rules'  => 'max_length[16]|is_unique[users.nik,users.id,{id}]',
            'errors' => [
                'max_length'  => 'max 16 character',
                'is_unique'   => 'nik sudah dipakai',
            ],
		],
		'notelp' => [
            'rules'  => 'max_length[14]|is_unique[users.notelp,users.id,{id}]',
            'errors' => [
                'max_length'  => 'max 14 character',
                'is_unique'   => 'no.telp sudah dipakai',
            ],
		],
		'alamat' => [
            'rules'  => 'max_length[255]',
            'errors' => [
                'required'    => 'alamat is required',
                'max_length'  => 'max 255 character',
            ],
		],
		'kelamin' => [
            'rules'  => 'required|in_list[laki-laki,perempuan]',
            'errors' => [
                'required'    => 'kelamin is required',
                'in_list'     => "value must be 'laki-laki' or 'perempuan'",
            ],
		]
	];

    //  send kritik
    public $sendKritikValidate = [
		'name' => [
            'rules'  => 'required|max_length[20]',
            'errors' => [
                'required'   => 'name is required',
                'max_length' => 'max 20 character',
            ],
		],
		'email' => [
            'rules'  => 'required|valid_email',
            'errors' => [
                'required'    => 'email is required',
                'valid_email' => 'Email is not in format',
            ],
		],
		'message' => [
            'rules'  => 'required',
            'errors' => [
                'message' => 'message is required',
            ],
		],
	];
    
    /**
     * ADMIN Controller Validate
     * ================================
     */
    // edit profile
	public $editOwnProfileAdmin = [
		'username' => [
            'rules'  => 'required|min_length[8]|max_length[20]|is_unique[users.username,users.id,{id}]',
            'errors' => [
                'required'    => 'username is required',
                'min_length'  => 'min 8 character',
                'max_length'  => 'max 20 character',
                'is_unique'   => 'username sudah terdaftar',
            ],
		],
		'nama_lengkap' => [
            'rules'  => 'required|max_length[40]',
            'errors' => [
                'required'    => 'nama lengkap is required',
                'max_length'  => 'max 40 character',
            ],
		],
		'notelp' => [
            'rules'  => 'required|max_length[12]|is_unique[users.notelp,users.id,{id}]',
            'errors' => [
                'required'    => 'nomor telepon is required',
                'max_length'  => 'max 12 character',
                'is_unique'   => 'no.telp sudah dipakai',
            ],
		],
		'alamat' => [
            'rules'  => 'required|max_length[255]',
            'errors' => [
                'required'    => 'alamat is required',
                'max_length'  => 'max 255 character',
            ],
		],
		'tgl_lahir' => [
            'rules'  => 'required|regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'required'    => 'tgl lahir is required',
                'regex_match' => 'format must be dd-mm-yyyy',
            ],
		],
		'kelamin' => [
            'rules'  => 'required|in_list[laki-laki,perempuan]',
            'errors' => [
                'required'    => 'kelamin is required',
                'in_list'     => "value must be 'laki-laki' or 'perempuan'",
            ],
		]
	];

    // filter get nasabah
    public $filterGetNasabah = [
		'orderby' => [
            'rules'  => 'in_list[terbaru,terlama]',
            'errors' => [
                'in_list' => "value must be 'terbaru' or 'terlama'",
            ],
		],
		// 'kelurahan' => [
        //     'rules'  => 'is_not_unique[wilayah.kelurahan]',
        //     'errors' => [
        //         'is_not_unique' => 'kelurahan with value ({value}) is not in database',
        //     ],
		// ],
		// 'kecamatan' => [
        //     'rules'  => 'is_not_unique[wilayah.kecamatan]',
        //     'errors' => [
        //         'is_not_unique' => 'kecamatan with value ({value}) is not in database',
        //     ],
		// ],
		// 'kota' => [
        //     'rules'  => 'is_not_unique[wilayah.kota]',
        //     'errors' => [
        //         'is_not_unique' => 'kota with value ({value}) is not in database',
        //     ],
		// ],
		// 'provinsi' => [
        //     'rules'  => 'is_not_unique[wilayah.provinsi]',
        //     'errors' => [
        //         'is_not_unique' => 'provinsi with value ({value}) is not in database',
        //     ],
		// ],
	];
    
    // edit admin
	public $editAdminValidate = [
		'id' => [
            'rules'  => 'required|is_not_unique[users.id]',
            'errors' => [
                'required'      => 'id is required',
                'is_not_unique' => 'admin with id ({value}) is not found',
            ],
		],
		'username' => [
            'rules'  => 'required|min_length[8]|max_length[20]|is_unique[users.username,users.id,{id}]',
            'errors' => [
                'required'    => 'username is required',
                'min_length'  => 'min 8 character',
                'max_length'  => 'max 20 character',
                'is_unique'   => 'username sudah terdaftar',
            ],
		],
		'nama_lengkap' => [
            'rules'  => 'required|max_length[40]',
            'errors' => [
                'required'    => 'nama lengkap is required',
                'max_length'  => 'max 40 character',
            ],
		],
		'notelp' => [
            'rules'  => 'max_length[14]|is_unique[users.notelp,users.id,{id}]',
            'errors' => [
                'max_length'  => 'max 14 character',
                'is_unique'   => 'no.telp sudah dipakai',
            ],
		],
		'alamat' => [
            'rules'  => 'max_length[255]',
            'errors' => [
                'max_length'  => 'max 255 character',
            ],
		],
		'tgl_lahir' => [
            'rules'  => 'required|regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'required'    => 'tgl lahir is required',
                'regex_match' => 'format must be dd-mm-yyyy',
            ],
		],
		'kelamin' => [
            'rules'  => 'required|in_list[laki-laki,perempuan]',
            'errors' => [
                'required'    => 'kelamin is required',
                'in_list'     => "value must be 'laki-laki' or 'perempuan'",
            ],
		],
		'privilege' => [
            'rules'  => 'required|in_list[superadmin,admin]',
            'errors' => [
                'required'    => 'privilege is required',
                'in_list'     => "value must be 'superadmin' or 'admin'",
            ],
		],
		'is_active' => [
            'rules'  => 'required|in_list[1,0]',
            'errors' => [
                'required'    => 'is_active is required',
                'in_list'     => "value must be '1' or '0'",
            ],
		]
	];

    /**
     * PENGHARGAAN 
     */
	public $penghargaanValidate = [
        'icon' => [
            'rules'  => 'uploaded[icon]|max_size[icon,2000]|mime_in[icon,image/png,image/jpg,image/jpeg,image/webp]',
            'errors' => [
                'uploaded' => 'icon is required',
                'max_size' => 'max size is 2mb',
                // 'is_image' => 'your file is not image',
                'mime_in'  => 'your file is not in format(png/jpg/jpeg/webp)',
            ],
        ],
		'penghargaan_name' => [
            'rules'  => 'required|max_length[255]|is_unique[penghargaan.name]',
            'errors' => [
                'required'    => 'penghargaan name is required',
                'max_length'  => 'max 255 character',
                'is_unique'   => 'penghargaan is exist',
            ],
		],
        'description' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'description is required',
            ],
		],
	];

    /**
     * MITRA
     */
	public $mitraValidate = [
        'icon' => [
            'rules'  => 'uploaded[icon]|max_size[icon,2000]|mime_in[icon,image/png,image/jpg,image/jpeg,image/webp]',
            'errors' => [
                'uploaded' => 'icon is required',
                'max_size' => 'max size is 2mb',
                // 'is_image' => 'your file is not image',
                'mime_in'  => 'your file is not in format(png/jpg/jpeg/webp)',
            ],
        ],
		'mitra_name' => [
            'rules'  => 'required|max_length[255]|is_unique[mitra.name]',
            'errors' => [
                'required'    => 'mitra name is required',
                'max_length'  => 'max 255 character',
                'is_unique'   => 'mitra is exist',
            ],
		],
        'description' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'description is required',
            ],
		],
	];
    
    /**
     * ARTIKEL Controller Validate
     * ================================
     */
    // kategori artikel
	public $kategoriArtikelValidate = [
        'icon' => [
            'rules'  => 'uploaded[icon]|max_size[icon,2000]|mime_in[icon,image/png,image/jpg,image/jpeg,image/webp]',
            'errors' => [
                'uploaded' => 'icon is required',
                'max_size' => 'max size is 2mb',
                // 'is_image' => 'your file is not image',
                'mime_in'  => 'your file is not in format(png/jpg/jpeg/webp)',
            ],
        ],
		'kategori_name' => [
            'rules'  => 'required|max_length[100]|is_unique[kategori_artikel.name]',
            'errors' => [
                'required'    => 'kategori name is required',
                'max_length'  => 'max 100 character',
                'is_unique'   => 'kategori name is exist',
            ],
		],
        'description' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'description is required',
            ],
		],
		'kategori_utama' => [
            'rules'  => 'required|in_list[1,0]',
            'errors' => [
                'required'    => 'kategori_utama is required',
                'in_list'     => "value must be '1' or '0'",
            ],
		]
	];

    // edit kategori artikel
	public $editKategoriArtikelValidate = [
        'id' => [
            'rules'  => 'required|is_not_unique[kategori_artikel.id]',
            'errors' => [
                'required'      => 'id is required',
                'is_not_unique' => 'kategori with id ({value}) is not found',
            ],
		],
		'kategori_name' => [
            'rules'  => 'required|max_length[100]|is_unique[kategori_artikel.name,kategori_artikel.id,{id}]',
            'errors' => [
                'required'    => 'kategori name is required',
                'max_length'  => 'max 100 character',
                'is_unique'   => 'kategori name is exist',
            ],
		],
        'description' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'description is required',
            ],
		],
		'kategori_utama' => [
            'rules'  => 'required|in_list[1,0]',
            'errors' => [
                'required'    => 'kategori_utama is required',
                'in_list'     => "value must be '1' or '0'",
            ],
		]
	];

    // new thumbnail
	public $newIconKategoriArtikel = [
        'icon' => [
            'rules'  => 'max_size[icon,200]|mime_in[icon,image/png,image/jpg,image/jpeg,image/webp]',
            'errors' => [
                'max_size' => 'max size is 200kb',
                'mime_in'  => 'your file is not in format(png/jpg/jpeg/webp)',
            ],
        ],
	];

    // add artikel
	public $addArtikelValidate = [
		'title' => [
            'rules'  => 'required|max_length[250]|is_unique[artikel.title]',
            'errors' => [
                'required'    => 'title is required',
                'max_length'  => 'max 250 character',
                'is_unique'   => 'judul ({value}) sudah ada',
            ],
		],
		'thumbnail' => [
            'rules'  => 'uploaded[thumbnail]|max_size[thumbnail,2000]|mime_in[thumbnail,image/png,image/jpg,image/jpeg,image/webp]',
            'errors' => [
                'uploaded' => 'thumbnail is required',
                'max_size' => 'max size is 2mb',
                // 'is_image' => 'your file is not image',
                'mime_in'  => 'your file is not in format(png/jpg/jpeg/webp)',
            ],
		],
		'content' => [
            'rules'  => 'required',
            'errors' => [
                'required'    => 'content is required',
            ],
		],
		'id_kategori' => [
            'rules'  => 'required|is_not_unique[kategori_artikel.id]',
            'errors' => [
                'required'      => 'id_kategori is required',
                'is_not_unique' => 'id_kategori with value ({value}) is not found',
            ],
		]
	];

    // edit artikel
	public $editArtikelValidate = [
		'id' => [
            'rules'  => 'required|is_not_unique[artikel.id]',
            'errors' => [
                'required'      => 'id is required',
                'is_not_unique' => 'berita with id ({value}) is not found',
            ],
		],
		'title' => [
            'rules'  => 'required|max_length[250]|is_unique[artikel.title,artikel.id,{id}]',
            'errors' => [
                'required'    => 'title is required',
                'max_length'  => 'max 250 character',
                'is_unique'   => 'judul ({value}) sudah ada',
            ],
		],
		'content' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'content is required',
            ],
		],
		'id_kategori' => [
            'rules'  => 'required|is_not_unique[kategori_artikel.id]',
            'errors' => [
                'required'      => 'id_kategori is required',
                'is_not_unique' => 'id_kategori with value ({value}) is not found',
            ],
		]
	];

    // new thumbnail
	public $newArtikelThumbnail = [
        'new_thumbnail' => [
            'rules'  => 'max_size[new_thumbnail,2000]|mime_in[new_thumbnail,image/png,image/jpg,image/jpeg,image/webp]',
            'errors' => [
                'max_size' => 'max size is 2mb',
                // 'is_image' => 'your file is not image',
                'mime_in'  => 'your file is not in format(png/jpg/jpeg/webp)',
            ],
        ],
	];

    public $getRelatedArtikel = [
		'slug' => [
            'rules'  => 'required|is_not_unique[artikel.slug]',
            'errors' => [
                'required'      => 'slug is required',
                'is_not_unique' => 'artikel with id ({slug}) is not found',
            ],
		]
	];

    /**
     * SAMPAH Controller Validate
     * ================================
     */
    // kategori sampah
	public $kategoriSampahValidate = [
		'kategori_name' => [
            'rules'  => 'required|max_length[100]|is_unique[kategori_sampah.name]',
            'errors' => [
                'required'    => 'kategori_name is required',
                'max_length'  => 'max 100 character',
                'is_unique'   => 'kategori_name is exist',
            ],
		]
	];

    // add sampah
	public $addSampahValidate = [
		'id_kategori' => [
            'rules'  => 'required|is_not_unique[kategori_sampah.id]',
            'errors' => [
                'required'      => 'id_kategori is required',
                'is_not_unique' => 'id_kategori with value ({value}) is not found',
            ],
		],
		'jenis' => [
            'rules'  => 'required|max_length[40]|is_unique[sampah.jenis]',
            'errors' => [
                'required'    => 'jenis is required',
                'max_length'  => 'max 40 character',
                'is_unique'   => "jenis '{value}' sudah ada",
            ],
		],
		'harga' => [
            'rules'  => 'required|max_length[11]|is_natural_no_zero',
            'errors' => [
                'required'           => 'harga is required',
                'max_length'         => 'max 11 character',
                'is_natural_no_zero' => 'only number allowed',
            ],
		],
		'harga_pusat' => [
            'rules'  => 'required|max_length[11]|is_natural_no_zero',
            'errors' => [
                'required'           => 'harga pusat is required',
                'max_length'         => 'max 11 character',
                'is_natural_no_zero' => 'only number allowed',
            ],
		],
	];

    // edit sampah
	public $updateSampahValidate = [
		'id' => [
            'rules'  => 'required|is_not_unique[sampah.id]',
            'errors' => [
                'required'      => 'id is required',
                'is_not_unique' => 'sampah with id ({value}) is not found',
            ],
		],
		'id_kategori' => [
            'rules'  => 'required|is_not_unique[kategori_sampah.id]',
            'errors' => [
                'required'      => 'id_kategori is required',
                'is_not_unique' => 'id_kategori with value ({value}) is not found',
            ],
		],
		'jenis' => [
            'rules'  => 'required|max_length[40]|is_unique[sampah.jenis,sampah.id,{id}]',
            'errors' => [
                'required'    => 'jenis is required',
                'max_length'  => 'max 40 character',
                'is_unique'   => "jenis '{value}' sudah ada",
            ],
		],
		'harga' => [
            'rules'  => 'required|max_length[11]|is_natural_no_zero',
            'errors' => [
                'required'           => 'harga is required',
                'max_length'         => 'max 11 character',
                'is_natural_no_zero' => 'only number allowed',
            ],
		],
		'harga_pusat' => [
            'rules'  => 'required|max_length[11]|is_natural_no_zero',
            'errors' => [
                'required'           => 'harga pusat is required',
                'max_length'         => 'max 11 character',
                'is_natural_no_zero' => 'only number allowed',
            ],
		],
	];

    /**
     * TRANSAKSI Controller Validate
     * ================================
     */

    // setor sampah 
	public $setorSampah1 = [
		'id_nasabah' => [
            'rules'  => 'required|is_not_unique[users.id]',
            'errors' => [
                'required'      => 'id_nasabah is required',
                'is_not_unique' => 'nasabah dengan id ({value}) tidak ditemukan',
            ],
		],
		'transaksi' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'transaksi is required',
            ],
		],
		'date' => [
            'rules'  => 'required|regex_match[/^[0-3][0-9][-][0-1][0-9][-][2-9][0-9][0-9][0-9] [0-2][0-9][:][0-5][0-9]$/]',
            // 'rules'  => 'required|regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'required'    => 'date is required',
                'regex_match' => 'format must be dd-mm-yyyy hh:mm',
            ],
		],
	];

	public $setorSampah2 = [
		'id_sampah' => [
            'rules'  => 'required|is_not_unique[sampah.id]',
            'errors' => [
                'required'      => 'id_sampah is required',
                'is_not_unique' => 'id_sampah with value ({value}) is not found',
            ],
		],
		'jumlah' => [
            'rules'  => 'required|numeric',
            'errors' => [
                'required'   => 'jumlah is required',
                'numeric'    => 'only number allowed',
            ],
		],
	];

    public $editSetorSampah1 = [
		'id_nasabah' => [
            'rules'  => 'required|is_not_unique[users.id]',
            'errors' => [
                'required'      => 'id_nasabah is required',
                'is_not_unique' => 'nasabah dengan id ({value}) tidak ditemukan',
            ],
		],
        'id_transaksi' => [
            'rules'  => 'required|is_not_unique[transaksi.id]',
            'errors' => [
                'required'      => 'id_transaksi is required',
                'is_not_unique' => 'transaksi dengan id ({value}) tidak ditemukan',
            ],
		],
		'transaksi' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'transaksi is required',
            ],
		],
		'date' => [
            'rules'  => 'required|regex_match[/^[0-3][0-9][-][0-1][0-9][-][2-9][0-9][0-9][0-9] [0-2][0-9][:][0-5][0-9]$/]',
            // 'rules'  => 'required|regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'required'    => 'date is required',
                'regex_match' => 'format must be dd-mm-yyyy hh:mm',
            ],
		],
	];

    // pindah saldo
	public $pindahSaldo = [
		'id_nasabah' => [
            'rules'  => 'required|is_not_unique[users.id]',
            'errors' => [
                'required'      => 'id nasabah is required',
                'is_not_unique' => "nasabah with id ({value}) is not found",
            ],
		],
		'harga_emas' => [
            'rules'  => 'required|numeric',
            'errors' => [
                'required'    => 'harga_emas is required',
                'numeric'     => "value must be number",
            ],
		],
		'jumlah' => [
            'rules'  => 'required|numeric',
            'errors' => [
                'required' => 'jumlah is required',
                'numeric'  => 'value must be number',
            ],
		],
		'date' => [
            'rules'  => 'required|regex_match[/^[0-3][0-9][-][0-1][0-9][-][2-9][0-9][0-9][0-9] [0-2][0-9][:][0-5][0-9]$/]',
            // 'rules'  => 'required|regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'required'    => 'date is required',
                'regex_match' => 'format must be dd-mm-yyyy hh:mm',
            ],
		],
	];

    // tarik saldo
	public $tarikSaldo = [
		'id_nasabah' => [
            'rules'  => 'required|is_not_unique[users.id]',
            'errors' => [
                'required'      => 'id_nasabah is required',
                'is_not_unique' => 'id_nasabah with value ({value}) is not found',
            ],
		],
		'jenis_saldo' => [
            'rules'  => 'required|in_list[uang,ubs,antam,galery24]',
            'errors' => [
                'required' => 'jenis saldo sampah is required',
                'in_list'  => "value must be 'uang/ubs/antam/galery24'",
            ],
		],
		'jumlah' => [
            'rules'  => 'required|numeric',
            'errors' => [
                'required'   => 'jumlah is required',
                'numeric'    => 'only number allowed',
            ],
		],
		'date' => [
            'rules'  => 'required|regex_match[/^[0-3][0-9][-][0-1][0-9][-][2-9][0-9][0-9][0-9] [0-2][0-9][:][0-5][0-9]$/]',
            // 'rules'  => 'required|regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'required'    => 'date is required',
                'regex_match' => 'format must be dd-mm-yyyy hh:mm',
            ],
		],
	];

    public $tarikSaldoBst = [
		'jumlah' => [
            'rules'  => 'required|numeric',
            'errors' => [
                'required'   => 'jumlah is required',
                'numeric'    => 'only number allowed',
            ],
		],
		'date' => [
            'rules'  => 'required|regex_match[/^[0-3][0-9][-][0-1][0-9][-][2-9][0-9][0-9][0-9] [0-2][0-9][:][0-5][0-9]$/]',
            // 'rules'  => 'required|regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'required'    => 'date is required',
                'regex_match' => 'format must be dd-mm-yyyy hh:mm',
            ],
		],
        'description' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'description is required',
            ],
		],
	];

    // jual sampah
	public $jualSampah = [
		'id_admin' => [
            'rules'  => 'required|is_not_unique[users.id]',
            'errors' => [
                'required'      => 'id_admin is required',
                'is_not_unique' => 'admin dengan id ({value}) tidak ditemukan',
            ],
		],
		'transaksi' => [
            'rules'  => 'required',
            'errors' => [
                'required' => 'transaksi is required',
            ],
		],
		'date' => [
            'rules'  => 'required|regex_match[/^[0-3][0-9][-][0-1][0-9][-][2-9][0-9][0-9][0-9] [0-2][0-9][:][0-5][0-9]$/]',
            // 'rules'  => 'required|regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'required'    => 'date is required',
                'regex_match' => 'format must be dd-mm-yyyy hh:mm',
            ],
		],
	];

    // delete transaksi
    public $deleteTransaksi = [
		'id' => [
            'rules'  => 'required|is_not_unique[transaksi.id]',
            'errors' => [
                'required'      => 'id is required',
                'is_not_unique' => 'transaksi with id ({value}) is not found',
            ],
		]
	];

	public $dateForFilterTransaksi = [
		'start' => [
            'rules'  => 'regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'regex_match' => 'format must be dd-mm-yyyy',
            ],
		],
		'end' => [
            'rules'  => 'regex_match[/^(0[1-9]|[12][0-9]|3[01])[\-\ ](0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'regex_match' => 'format must be dd-mm-yyyy',
            ],
		],
	];

	public $rekapDataYear = [
		'year' => [
            'rules'  => 'regex_match[/^(19|20)\d\d$/]',
            'errors' => [
                'regex_match' => 'format must be yyyy',
            ],
		],
	];

	public $rekapDataDate = [
		'date' => [
            'rules'  => 'regex_match[/^(0[1-9]|1[012])[\-\ ](19|20)\d\d$/]',
            'errors' => [
                'regex_match' => 'format must be mm-yyyy',
            ],
		],
	];
}
