
/**
 * Get Admin Profile
 */
 const getDataProfile = async () => {

    let httpResponse = await httpRequestGet(`${APIURL}/admin/getprofile`);
    
    if (httpResponse.status === 200) {
        dataAdmin = httpResponse.data.data;
        updatePersonalInfo(dataAdmin);
    }
};
getDataProfile();
 
/** 
 * update personal info
*/
const updatePersonalInfo = (data) => {
    $('#profile-spinner').addClass('d-none');
    
    // id admin
    $('#idadmin').html(data.id);
    // nama lengkap
    $('#nama-lengkap').html(data.nama_lengkap);
    // username
    $('#username').html(data.username);
    // tgl lahir
    $('#tgl-lahir').html(data.tgl_lahir);
    // kelamin
    $('#kelamin').html(data.kelamin);
    // alamat
    $('#alamat').html(data.alamat);
    // No Telp
    $('#notelp').html(data.notelp);
};

/**
 * Open modal edit profile
 */
$('#btn-edit-profile').on('click', function(e) {
    e.preventDefault();

    // clear error message first
    $('#formEditProfile .form-control').removeClass('is-invalid');
    $('#formEditProfile .form-check-input').removeClass('is-invalid');
    $('#formEditProfile .text-danger').html('');

    for (const name in dataAdmin) {
        $(`#formEditProfile input[name=${name}]`).val(dataAdmin[name]);
    }

    let tglLahir = dataAdmin.tgl_lahir.split('-');
    $(`#formEditProfile input[name=tgl_lahir]`).val(`${tglLahir[2]}-${tglLahir[1]}-${tglLahir[0]}`);
    $(`#formEditProfile input#kelamin-${dataAdmin.kelamin}`).prop('checked',true);
    $('#newpass-edit').val('');
    $('#oldpass-edit').val('');
});

// change kelamin value
$('#formEditProfile .form-check-input').on('click', function(e) {
    $(`#formEditProfile input[name=kelamin]`).val($(this).val());
    $('#formEditProfile .form-check-input').prop('checked',false);
    $(this).prop('checked',true);
});

/**
 * EDIT PROFILE PROFILE
 */
$('#formEditProfile').on('submit', function(e) {
    e.preventDefault();
    let updatePass = false;
    let form       = new FormData(e.target);

    if (validateFormEditProfile(form)) {
        let newTgl = form.get('tgl_lahir').split('-');
        form.set('tgl_lahir',`${newTgl[2]}-${newTgl[1]}-${newTgl[0]}`)

        if (form.get('new_password') == '') {
            form.delete('new_password');
        }
        else {
            updatePass = true;
        }

        $('#formEditProfile button#submit #text').addClass('d-none');
        $('#formEditProfile button#submit #spinner').removeClass('d-none');

        axios
        .put(`${APIURL}/admin/editprofile`,form, {
            headers: {
                token: TOKEN
            }
        })
        .then((response) => {
            $('#formEditProfile button#submit #text').removeClass('d-none');
            $('#formEditProfile button#submit #spinner').addClass('d-none');
            $('#newpass-edit').val('');
            $('#oldpass-edit').val('');

            let newDataProfile = {};
            for (var pair of form.entries()) {
                newDataProfile[pair[0]] = pair[1];
            }

            dataAdmin = newDataProfile;
            updatePersonalInfo(newDataProfile);

            showAlert({
                message: `<strong>Success...</strong> edit profile berhasil!`,
                autohide: true,
                type:'success'
            });

            setTimeout(() => {
                if (updatePass) {
                    Swal.fire({
                        icon  : 'info',
                        title : '<strong>INFO</strong>',
                        html  : 'Password telah diupdate, silahkan login ulang',
                        showCancelButton: false,
                        confirmButtonText: 'ok',
                    })
                    .then(() => {
                        doLogout();
                    })
                }
            }, 2000);
        })
        .catch((error) => {
            $('#formEditProfile button#submit #text').removeClass('d-none');
            $('#formEditProfile button#submit #spinner').addClass('d-none');

            // bad request
            if (error.response.status == 400) {
                if (error.response.data.messages.username) {
                    $('#username-edit').addClass('is-invalid');
                    $('#username-edit-error').text('*'+error.response.data.messages.username);
                }
                if (error.response.data.messages.notelp) {
                    $('#notelp-edit').addClass('is-invalid');
                    $('#notelp-edit-error').text('*'+error.response.data.messages.notelp);
                }
                if (error.response.data.messages.old_password) {
                    $('#oldpass-edit').addClass('is-invalid');
                    $('#oldpass-edit-error').text('*'+error.response.data.messages.old_password);
                }
            }
            // unauthorized
            else if (error.response.status == 401) {
                if (error.response.data.messages == 'token expired') {
                    Swal.fire({
                        icon : 'error',
                        title : '<strong>LOGIN EXPIRED</strong>',
                        text: 'silahkan login ulang untuk perbaharui login anda',
                        showCancelButton: false,
                        confirmButtonText: 'ok',
                    }).then(() => {
                        document.cookie = `token=null;expires=;path=/;`;
                        window.location.replace(`${BASEURL}/login`);
                    })
                }
                else{
                    document.cookie = `token=null;expires=;path=/;`;
                    window.location.replace(`${BASEURL}/login`);
                }
            }
            // error server
            else if (error.response.status == 500) {
                showAlert({
                    message: `<strong>Ups . . .</strong> terjadi kesalahan pada server, coba sekali lagi`,
                    autohide: true,
                    type:'danger'
                })
            }
        })
    }
});

// form edit profile validation
function validateFormEditProfile(form) {
    let status     = true;
    let kelamin    = form.get('kelamin');

    // clear error message first
    $('#formEditProfile .form-control').removeClass('is-invalid');
    $('#formEditProfile .form-check-input').removeClass('is-invalid');
    $('#formEditProfile .text-danger').html('');

    // name validation
    if ($('#nama-edit').val() == '') {
        $('#nama-edit').addClass('is-invalid');
        $('#nama-edit-error').html('*nama lengkap harus di isi');
        status = false;
    }
    else if ($('#nama-edit').val().length > 40) {
        $('#nama-edit').addClass('is-invalid');
        $('#nama-edit-error').html('*maksimal 40 huruf');
        status = false;
    }
    // username validation
    if ($('#username-edit').val() == '') {
        $('#username-edit').addClass('is-invalid');
        $('#username-edit-error').html('*username harus di isi');
        status = false;
    }
    else if ($('#username-edit').val().length < 8 || $('#username-edit').val().length > 20) {
        $('#username-edit').addClass('is-invalid');
        $('#username-edit-error').html('*minimal 8 huruf dan maksimal 20 huruf');
        status = false;
    }
    else if (/\s/.test($('#username-edit').val())) {
        $('#username-edit').addClass('is-invalid');
        $('#username-edit-error').html('*tidak boleh ada spasi');
        status = false;
    }
    // tgl lahir validation
    if ($('#tgllahir-edit').val() == '') {
        $('#tgllahir-edit').addClass('is-invalid');
        $('#tgllahir-edit-error').html('*tgl lahir harus di isi');
        status = false;
    }
    // kelamin validation
    if (kelamin == null) {
        $('#formEditProfile .form-check-input').addClass('is-invalid');
        status = false;
    }
    // alamat validation
    if ($('#alamat-edit').val() == '') {
        $('#alamat-edit').addClass('is-invalid');
        $('#alamat-edit-error').html('*alamat harus di isi');
        status = false;
    }
    else if ($('#alamat-edit').val().length > 255) {
        $('#alamat-edit').addClass('is-invalid');
        $('#alamat-edit-error').html('*maksimal 255 huruf');
        status = false;
    }
    // notelp validation
    if ($('#notelp-edit').val() == '') {
        $('#notelp-edit').addClass('is-invalid');
        $('#notelp-edit-error').html('*no.telp harus di isi');
        status = false;
    }
    else if ($('#notelp-edit').val().length > 14) {
        $('#notelp-edit').addClass('is-invalid');
        $('#notelp-edit-error').html('*maksimal 14 huruf');
        status = false;
    }
    else if (!/^\d+$/.test($('#notelp-edit').val())) {
        $('#notelp-edit').addClass('is-invalid');
        $('#notelp-edit-error').html('*hanya boleh angka');
        status = false;
    }
    // pass validation
    if ($('#newpass-edit').val() !== '') {   
        if ($('#newpass-edit').val().length < 8 || $('#newpass-edit').val().length > 20) {
            $('#newpass-edit').addClass('is-invalid');
            $('#newpass-edit-error').html('*minimal 8 huruf dan maksimal 20 huruf');
            status = false;
        }
        else if (/\s/.test($('#newpass-edit').val())) {
            $('#newpass-edit').addClass('is-invalid');
            $('#newpass-edit-error').html('*tidak boleh ada spasi');
            status = false;
        }
        if ($('#oldpass-edit').val() == '') {
            $('#oldpass-edit').addClass('is-invalid');
            $('#oldpass-edit-error').html('*password lama harus di isi');
            status = false;
        }
    }

    return status;
}

// logout
function doLogout() {
    showLoadingSpinner();
    axios
        .delete(`${APIURL}/admin/logout`, {
            headers: {
                token: TOKEN
            }
        })
        .then(() => {
            hideLoadingSpinner();
            document.cookie = `token=null; path=/;`;
            window.location.replace(`${BASEURL}/login/admin`);
        })
        .catch(error => {
            hideLoadingSpinner();
            document.cookie = `token=null; path=/;`;
            window.location.replace(`${BASEURL}/login/admin`);
        })
}