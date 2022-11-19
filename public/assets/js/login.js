/**
 * LOGIN NASABAH
 * =============================================
 */

// form on submit
$('#formLoginNasabah').on('submit', function(e) {
    e.preventDefault();

    if (doValidateNasabah()) {
        showLoadingSpinner();
        let formLogin = new FormData(e.target);

        axios
        .post(`${APIURL}/login/nasabah`,formLogin, {
            headers: {
                // header options 
            }
        })
        .then((response) => {
            hideLoadingSpinner();

            document.cookie = `token=${response.data.token}; path=/;SameSite=None; Secure`;
            window.location.replace(`${BASEURL}/nasabah`);
        })
        .catch((error) => {
            hideLoadingSpinner();

            // error email/password
            if (error.response.status == 404) {
                if (error.response.data.messages.username_or_email) {
                    $('#nasabah-username-or-email').addClass('is-invalid');
                    $('#nasabah-username-or-email-error').text(error.response.data.messages.username_or_email);
                } 
                else if (error.response.data.messages.password){
                    $('#nasabah-password').addClass('is-invalid');
                    $('#nasabah-password-error').text(error.response.data.messages.password);
                }
            }
            // account not verify
            else if (error.response.status == 401) {
                if (error.response.data.messages == 'account is not verify') {
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'warning',
                            title: 'LOGIN GAGAL!',
                            text: 'akun anda belum ter-verifikasi. silahkan verifikasi akun terlebih dahulu',
                            confirmButtonText: 'ok',
                        })
                        .then(() => {
                            var url = BASEURL + '/otp';
                            var form = $('<form action="' + url + '" method="post">' +
                            '<input type="text" name="username_or_email" value="' + formLogin.get('username_or_email') + '" />' +
                            '<input type="text" name="password" value="' + formLogin.get('password') + '" />' +
                            '</form>');
                            $('body').append(form);
                            form.submit();
                        })
                    }, 300);
                }
                else if (error.response.data.messages == 'akun tidak aktif') {
                    showAlert({
                        message: `<strong>Maaf . . .</strong> akun anda sedang di Non-aktifkan!`,
                        autohide: true,
                        type:'warning' 
                    })
                }
            }
            // server error
            else if (error.response.status == 500){
                showAlert({
                    message: `<strong>Ups . . .</strong> terjadi kesalahan, coba sekali lagi!`,
                    autohide: true,
                    type:'danger' 
                })
            }
        })
    }
})

// validate login nasabah
function doValidateNasabah() {
    let status     = true;
    // let emailRules = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

    // clear error message first
    $('.form-control').removeClass('is-invalid');
    $('.text-danger').html('');

    // email validation
    if ($('#nasabah-username-or-email').val() == '') {
        $('#nasabah-username-or-email').addClass('is-invalid');
        $('#nasabah-username-or-email-error').html('*username/email harus di isi');
        status = false;
    }
    // password validation
    if ($('#nasabah-password').val() == '') {
        $('#nasabah-password').addClass('is-invalid');
        $('#nasabah-password-error').html('*password harus di isi');
        status = false;
    }

    return status;
}

// btn forgot password on click
$('#btn-forgotpass').on('click', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'LUPA PASSWORD?',
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        html:`<p class='mb-4'>masukan email yang terdaftar. kami akan mengirim password anda melalui email</p>`,
        footer: '<a href="https://wa.me/6281287200602?text=Hallo%20Admin,%20saya%20ada%20kendala%20mengenai%20password">hubungi admin</a>',
        showCancelButton: true,
        confirmButtonText: 'submit',
        showLoaderOnConfirm: true,
        preConfirm: (email) => {
            let form = new FormData();
            form.append('email',email);

            return axios
            .post(`${APIURL}/login/forgotpass`,form, {
                headers: {
                    // header options 
                }
            })
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'success!',
                    text: 'password sudah dikirim ke email anda. silahkan cek email',
                    showConfirmButton: true,
                })
            })
            .catch(error => {
                if (error.response.status == 404) {
                    Swal.showValidationMessage(error.response.data.messages);
                }
                else if (error.response.status == 500) {
                    Swal.showValidationMessage(
                        `terjadi kesalahan, coba sekali lagi`
                    )
                }
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
    })
})

/**
 * LOGIN ADMIN
 * =============================================
 */
// form on submit
$('#formLoginAdmin').on('submit', function(e) {
    e.preventDefault();

    if (doValidateAdmin()) {
        showLoadingSpinner();
        let form = new FormData(e.target);

        axios
        .post(`${APIURL}/login/admin`,form, {
            headers: {
                // header options 
            }
        })
        .then((response) => {
            hideLoadingSpinner();
            
            let url = `${BASEURL}/admin`;
            if (LASTURL != '' && LASTURL != 'null' && LASTURL != null) {
                url = LASTURL;
            }

            document.cookie = `token=${response.data.token}; path=/;SameSite=None; Secure`;
            window.location.replace(url);
        })
        .catch((error) => {
            hideLoadingSpinner();

            // akun tidak aktif
            if (error.response.status == 401) {
                showAlert({
                    message: `<strong>Maaf . . .</strong> akun anda sedang di Non-aktifkan!`,
                    autohide: true,
                    type:'warning' 
                })
            }
            // error username/password
            else if (error.response.status == 404) {
                if (error.response.data.messages.username) {
                    $('#admin-username').addClass('is-invalid');
                    $('#admin-username-error').text(error.response.data.messages.username);
                } 
                else if (error.response.data.messages.password){
                    $('#admin-password').addClass('is-invalid');
                    $('#admin-password-error').text(error.response.data.messages.password);
                }
            }
            // server error
            else if (error.response.status == 500){
                showAlert({
                    message: `<strong>Ups . . .</strong> terjadi kesalahan, coba sekali lagi!`,
                    autohide: true,
                    type:'danger' 
                })
            }
        })
    }
})

// validate login admin
function doValidateAdmin(form) {
    let status     = true;

    // clear error message first
    $('.form-control').removeClass('is-invalid');
    $('.text-danger').html('');

    // email validation
    if ($('#admin-username').val() == '') {
        $('#admin-username').addClass('is-invalid');
        $('#admin-username-error').html('*username harus di isi');
        status = false;
    }
    // password validation
    if ($('#admin-password').val() == '') {
        $('#admin-password').addClass('is-invalid');
        $('#admin-password-error').html('*password harus di isi');
        status = false;
    }

    return status;
}