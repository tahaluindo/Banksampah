/**
 * KODEPOS
 * =============================================
 */
// search kodepos
const searchKodepos = async (el) => {

    if (el.value == '') {
        return 0;
    } 
    else {
        $('#kodepos-wraper').html(`<div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
           <img src="${BASEURL}/assets/images/spinner.svg" style="width: 20px;" />
        </div>`); 

        axios
        .get(`https://kodepos.vercel.app/search/?q=${el.value}`,{
            headers: {
            }
        })
        .then((response) => {
    
            // console.log(response.data.status);
            if (response.data.code === 200) {
                if (response.data.messages === 'No data can be returned.') {
                    $('#kodepos-wraper').html(`<div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
                        <h6 tyle="opacity: 0.6;">kodepos tidak ditemukan</h6>
                    </div>`);    
                } 
                else {
                    let elPostList = '';
    
                    response.data.data.forEach(x => {
                        elPostList += `
                        <div class="w-100">
                            <div class="kodepos-list w-100 d-flex align-items-center px-3 py-3" style="cursor: pointer;font-size:16px;" onclick="changeKodeposVal(this,'${x.postalcode}','${x.urban}','${x.subdistrict}','${x.city}','${x.province}');">
                                <span class="w-100" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">
                                    ${x.postalcode} - ${x.urban}, ${x.subdistrict}, ${x.city}, ${x.province}
                                </span>
                            </div>
                        </div>`;
                    });
            
                    $('#kodepos-wraper').html(elPostList);
                } 
            }
        })
    }
};

const changeKodeposVal = (el,postalcode,urban,subdistrict,city,province) => {
    $('.kodepos-list').removeClass('active');
    $('input[name=kodepos]').val(postalcode);
    $('input[name=kelurahan]').val(urban);
    $('input[name=kecamatan]').val(subdistrict);
    $('input[name=kota]').val(city);
    $('input[name=provinsi]').val(province);
    
    el.classList.add('active');
};

/**
 * NASABAH REGISTER
 * =============================================
 */

// form on submit
$('#formRegister').on('submit', function(e) {
    e.preventDefault();
    let formRegister = new FormData(e.target);
    
    if (doValidate(formRegister)) {
        showLoadingSpinner();
        let newTgl = formRegister.get('tgl_lahir').split('-');
        formRegister.set('tgl_lahir',`${newTgl[2]}-${newTgl[1]}-${newTgl[0]}`);
        formRegister.set('kodepos',$('input[name=kodepos]').val());

        axios
        .post(`${APIURL}/register/nasabah`,formRegister, {
            headers: {
                // header options 
            }
        })
        .then((response) => {
            hideLoadingSpinner();

            setTimeout(() => {
                Swal.fire({
                    icon : 'success',
                    title : '<strong>SUCCESS</strong>',
                    html:
                      'check email anda untuk mendapatkan ' +
                      '<strong>CODE OTP</strong> ',
                    showCancelButton: false,
                    confirmButtonText: 'ok',
                })
                .then(() => {
                    var url = BASEURL + '/otp';
                    var form = $('<form action="' + url + '" method="post">' +
                    '<input type="text" name="username_or_email" value="' + formRegister.get('username') + '" />' +
                    '<input type="text" name="password" value="' + formRegister.get('password') + '" />' +
                    '</form>');
                    $('body').append(form);
                    form.submit();
                })
            }, 300);
        })
        .catch((error) => {
            hideLoadingSpinner();

            // bad request
            if (error.response.status == 400) {
                showAlert({
                    message: `<strong>Gagal mendaftar...</strong> cek kembali data anda`,
                    autohide: true,
                    type:'warning'
                })

                if (error.response.data.messages.email) {
                    $('#email-regist').addClass('is-invalid');
                    $('#email-regist-error').text(error.response.data.messages.email);
                }
                if (error.response.data.messages.username) {
                    $('#username-regist').addClass('is-invalid');
                    $('#username-regist-error').text(error.response.data.messages.username);
                }
                if (error.response.data.messages.notelp) {
                    $('#notelp-regist').addClass('is-invalid');
                    $('#notelp-regist-error').text(error.response.data.messages.notelp);
                }
                if (error.response.data.messages.nik) {
                    $('#nik-regist').addClass('is-invalid');
                    $('#nik-regist-error').text(error.response.data.messages.nik);
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
})

// form validation
function doValidate(form) {
    let status     = true;
    let emailRules = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    let kelamin    = form.get('kelamin');

    // clear error message first
    $('.form-control').removeClass('is-invalid');
    $('.form-check-input').removeClass('is-invalid');
    $('.text-danger').html('');

    // name validation
    if ($('#nama-regist').val() == '') {
        $('#nama-regist').addClass('is-invalid');
        $('#nama-regist-error').html('*nama lengkap harus di isi');
        status = false;
    }
    else if ($('#nama-regist').val().length > 40) {
        $('#nama-regist').addClass('is-invalid');
        $('#nama-regist-error').html('*maksimal 40 huruf');
        status = false;
    }
    // username validation
    if ($('#username-regist').val() == '') {
        $('#username-regist').addClass('is-invalid');
        $('#username-regist-error').html('*username harus di isi');
        status = false;
    }
    else if ($('#username-regist').val().length < 8 || $('#username-regist').val().length > 20) {
        $('#username-regist').addClass('is-invalid');
        $('#username-regist-error').html('*minimal 8 huruf dan maksimal 20 huruf');
        status = false;
    }
    else if (/\s/.test($('#username-regist').val())) {
        $('#username-regist').addClass('is-invalid');
        $('#username-regist-error').html('*tidak boleh ada spasi');
        status = false;
    }
    // email validation
    if ($('#email-regist').val() == '') {
        $('#email-regist').addClass('is-invalid');
        $('#email-regist-error').html('*email harus di isi');
        status = false;
    }
    else if (!emailRules.test(String($('#email-regist').val()).toLowerCase())) {
        $('#email-regist').addClass('is-invalid');
        $('#email-regist-error').html('*email tidak valid');
        status = false;
    }
    // password validation
    if ($('#password-regist').val() == '') {
        $('#password-regist').addClass('is-invalid');
        $('#password-regist-error').html('*password harus di isi');
        status = false;
    }
    else if ($('#password-regist').val().length < 8 || $('#password-regist').val().length > 20) {
        $('#password-regist').addClass('is-invalid');
        $('#password-regist-error').html('*minimal 8 huruf dan maksimal 20 huruf');
        status = false;
    }
    else if (/\s/.test($('#password-regist').val())) {
        $('#password-regist').addClass('is-invalid');
        $('#password-regist-error').html('*tidak boleh ada spasi');
        status = false;
    }
    // tgl lahir validation
    if ($('#tgllahir-regist').val() == '') {
        $('#tgllahir-regist').addClass('is-invalid');
        $('#tgllahir-regist-error').html('*tgl lahir harus di isi');
        status = false;
    }
    // kelamin validation
    if (kelamin == null) {
        $('.form-check-input').addClass('is-invalid');
        
        status = false;
    }
    // rt validation
    if ($('#rt-regist').val() == '') {
        $('#rt-regist').addClass('is-invalid');
        $('#rt-regist-error').html('*rt harus di isi');
        status = false;
    }
    else if ($('#rt-regist').val().length < 3 || $('#rt-regist').val().length > 3) {
        $('#rt-regist').addClass('is-invalid');
        $('#rt-regist-error').html('*minimal 3 huruf dan maksimal 3 huruf');
        status = false;
    }
    else if (!/^\d+$/.test($('#rt-regist').val())) {
        $('#rt-regist').addClass('is-invalid');
        $('#rt-regist-error').html('*hanya boleh angka');
        status = false;
    }
    // rw validation
    if ($('#rw-regist').val() == '') {
        $('#rw-regist').addClass('is-invalid');
        $('#rw-regist-error').html('*rw harus di isi');
        status = false;
    }
    else if ($('#rw-regist').val().length < 3 || $('#rw-regist').val().length > 3) {
        $('#rw-regist').addClass('is-invalid');
        $('#rw-regist-error').html('*minimal 3 huruf dan maksimal 3 huruf');
        status = false;
    }
    else if (!/^\d+$/.test($('#rw-regist').val())) {
        $('#rw-regist').addClass('is-invalid');
        $('#rw-regist-error').html('*hanya boleh angka');
        status = false;
    }
    // kodepos validation
    if ($('#kodepos-regist').val() == '') {
        $('#kodepos-regist').addClass('is-invalid');
        $('#kodepos-regist-error').html('*kodepos harus di isi');
        status = false;
    }
    else if ($('#kodepos-regist').val().length < 5 || $('#kodepos-regist').val().length > 5) {
        $('#kodepos-regist').addClass('is-invalid');
        $('#kodepos-regist-error').html('*minimal 5 huruf dan maksimal 5 huruf');
        status = false;
    }
    else if (!/^\d+$/.test($('#kodepos-regist').val())) {
        $('#kodepos-regist').addClass('is-invalid');
        $('#kodepos-regist-error').html('*hanya boleh angka');
        status = false;
    }
    // alamat validation
    if ($('#alamat-regist').val() == '') {
        $('#alamat-regist').addClass('is-invalid');
        $('#alamat-regist-error').html('*alamat harus di isi');
        status = false;
    }
    else if ($('#alamat-regist').val().length > 255) {
        $('#alamat-regist').addClass('is-invalid');
        $('#alamat-regist-error').html('*maksimal 255 huruf');
        status = false;
    }
    // notelp validation
    if ($('#notelp-regist').val() == '') {
        $('#notelp-regist').addClass('is-invalid');
        $('#notelp-regist-error').html('*no.telp harus di isi');
        status = false;
    }
    else if ($('#notelp-regist').val().length > 14) {
        $('#notelp-regist').addClass('is-invalid');
        $('#notelp-regist-error').html('*maksimal 14 huruf');
        status = false;
    }
    else if (!/^\d+$/.test($('#notelp-regist').val())) {
        $('#notelp-regist').addClass('is-invalid');
        $('#notelp-regist-error').html('*hanya boleh angka');
        status = false;
    }
    // nik validation
    let resultNik = '';
    nikParse($('#nik-regist').val(), function(result) {
        resultNik = result;
    });	

    if ($('#nik-regist').val() == '') {
        $('#nik-regist').addClass('is-invalid');
        $('#nik-regist-error').html('*NIK harus di isi');
        status = false;
    }
    else if (resultNik.status == 'error') {
        $('#nik-regist').addClass('is-invalid');
        $('#nik-regist-error').html(resultNik.pesan);
        status = false;
    }

    return status;
}