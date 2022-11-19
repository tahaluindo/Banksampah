/**
 * FILTER NASABAH
 */
let arrayWilayah = [];
const getAllWilayah = async () => {

    let httpResponse = await httpRequestGet(`${APIURL}/nasabah/wilayah`);

    let tmpProvinsi  = [];
    let elprovinsi   = `<option value="">-- semua wilayah --</option>`;
    
    if (httpResponse.status === 200) {
        arrayWilayah = httpResponse.data.data;
 
        arrayWilayah.forEach(w=> {
            if (!tmpProvinsi.includes(w.provinsi)) {
                tmpProvinsi.push(w.provinsi)
                elprovinsi += `<option value="${w.provinsi}" data-provinsi="${w.provinsi}">${w.provinsi}</option>`;
            }
        });
    }

    $('select[name=provinsi]').html(elprovinsi);
};
getAllWilayah();

$('select[name=provinsi]').on('change', function() {
    let tmpKota = [];
    let elKota  = `<option value="">-- pilih kota --</option>`;
    
    if ($(this).val() != '') {
        $('select[name=kota]').removeAttr('disabled');

        arrayWilayah.forEach(w=> {
            if (w.provinsi == $(this).val()) {
                if (!tmpKota.includes(w.kota)) {
                    tmpKota.push(w.kota)
                    elKota += `<option value="${w.kota}">${w.kota}</option>`;
                }
            }
        });

        $('select[name=kota]').html(elKota);
    }
    else {
        $('select[name=kota]').attr('disabled',true);
    }
    $('select[name=kota]').val('');
    $('select[name=kecamatan]').val('');
    $('select[name=kecamatan]').attr('disabled',true);
    $('select[name=kelurahan]').val('');
    $('select[name=kelurahan]').attr('disabled',true);
});
$('select[name=kota]').on('change', function() {
    let tmpKecamatan = [];
    let elKecamatan  = `<option value="">-- pilih kecamatan --</option>`;

    if ($(this).val() != '') {
        $('select[name=kecamatan]').removeAttr('disabled');

        arrayWilayah.forEach(w=> {
            if (w.kota == $(this).val()) {
                if (!tmpKecamatan.includes(w.kecamatan)) {
                    tmpKecamatan.push(w.kecamatan)
                    elKecamatan += `<option value="${w.kecamatan}">${w.kecamatan}</option>`;
                }
            }
        });

        $('select[name=kecamatan]').html(elKecamatan);
    }
    else {
        $('select[name=kecamatan]').attr('disabled',true);
    }
    $('select[name=kecamatan]').val('');
    $('select[name=kelurahan]').val('');
    $('select[name=kelurahan]').attr('disabled',true);
});
$('select[name=kecamatan]').on('change', function() {
    let tmpKelurahan = [];
    let elKelurahan  = `<option value="">-- pilih kelurahan --</option>`;

    if ($(this).val() != '') {
        $('select[name=kelurahan]').removeAttr('disabled');

        arrayWilayah.forEach(w=> {
            if (w.kecamatan == $(this).val()) {
                if (!tmpKelurahan.includes(w.kelurahan)) {
                    tmpKelurahan.push(w.kelurahan)
                    elKelurahan += `<option value="${w.kelurahan}">${w.kelurahan}</option>`;
                }
            }
        });

        $('select[name=kelurahan]').html(elKelurahan);
    }
    else {
        $('select[name=kelurahan]').attr('disabled',true);
    }
    $('select[name=kelurahan]').val('');
});

const filterNasabah = async (e) => {
    let formFilter = new FormData(e.parentElement.parentElement.parentElement);
    let ketFilter  = `${formFilter.get('orderby')} - `;
    nasabahUrl     = `${APIURL}/admin/getnasabah?orderby=${formFilter.get('orderby')}`;

    if (formFilter.get('kelurahan')) {
        ketFilter  += `${formFilter.get('kelurahan')}, `
        nasabahUrl += `&kelurahan=${formFilter.get('kelurahan')}`
    }
    if (formFilter.get('kecamatan')) {
        ketFilter  += `${formFilter.get('kecamatan')}, `
        nasabahUrl += `&kecamatan=${formFilter.get('kecamatan')}`
    }
    if (formFilter.get('kota')) {
        ketFilter  += `${formFilter.get('kota')}, `
        nasabahUrl += `&kota=${formFilter.get('kota')}`
    }
    if (formFilter.get('provinsi')) {
        ketFilter  += `${formFilter.get('provinsi')}`
        nasabahUrl += `&provinsi=${formFilter.get('provinsi')}`
    }
    if (formFilter.get('provinsi') == '') {
        ketFilter  = `${formFilter.get('orderby')} - semua wilayah`;
        nasabahUrl = `${APIURL}/admin/getnasabah?orderby=${formFilter.get('orderby')}`
    }

    $('#ket-filter').html(ketFilter);
    getAllNasabah();
};

const resetFilterNasabah = async (e) => {
    $('select[name=orderby]').val('terbaru');
    $('select[name=provinsi]').val('');
    $('select[name=kota]').val('');
    $('select[name=kota]').attr('disabled',true);
    $('select[name=kecamatan]').val('');
    $('select[name=kecamatan]').attr('disabled',true);
    $('select[name=kelurahan]').val('');
    $('select[name=kelurahan]').attr('disabled',true);
};

/**
 * GET ALL NASABAH
 */
let arrayNasabah = [];
let nasabahUrl   = `${APIURL}/admin/getnasabah?orderby=terbaru`;
const getAllNasabah = async () => {
    $('#search-nasabah').val('');
    $('#ket-total').html('0');
    $('#table-nasabah tbody').html('');
    $('#list-nasabah-notfound').addClass('d-none'); 
    $('#list-nasabah-spinner').removeClass('d-none'); 
    let httpResponse = await httpRequestGet(nasabahUrl);
    $('#list-nasabah-spinner').addClass('d-none'); 
    
    if (httpResponse.status === 404) {
        $('#list-nasabah-notfound').removeClass('d-none'); 
        $('#list-nasabah-notfound #text-notfound').html(`belum ada nasabah`); 
    }
    else if (httpResponse.status === 200) {
        let trNasabah  = '';
        let allNasabah = httpResponse.data.data;
        arrayNasabah   = httpResponse.data.data;

        allNasabah.forEach((n,i) => {
        let stringLastActive = 'belum login';

        if (n.last_active != n.created_at) {
            let date  = new Date(parseInt(n.last_active) * 1000);
            let hour  = date.toLocaleString("en-US",{ hour: 'numeric', minute: 'numeric' });
            let day   = date.toLocaleString("en-US",{day: "numeric"});
            let month = date.toLocaleString("en-US",{month: "long"});
            let year  = date.toLocaleString("en-US",{year: "numeric"});
            stringLastActive = `${day}-${month}-${year} ${hour}`;
        }

        trNasabah += `<tr class="text-xs">
            <td class="align-middle text-center py-3">
                <span class="font-weight-bold"> ${++i} </span>
            </td>
            <td class="align-middle text-center py-3">
                <span class="font-weight-bold"> ${n.id} </span>
            </td>
            <td class="align-middle text-center">
                <span class="font-weight-bold text-capitalize"> ${n.nama_lengkap} </span>
            </td>
            <td class="align-middle text-center">
                <span class="font-weight-bold badge border ${(n.is_verify === 't' || n.is_verify === '1')? 'text-success border-success' : 'text-warning border-warning'} pb-1" style="border-radius:4px;">
                    ${(n.is_verify === 't' || n.is_verify === '1')? 'yes' : 'no'}
                </span>
            </td>
            <td class="align-middle text-center">
                <span class="font-weight-bold text-capitalize"> 
                    ${stringLastActive}
                </span>
            </td>
            <td class="align-middle text-center">
                <div clas="row w-100">
                    <div class="col-12 px-0">
                        <a href='' id="btn-hapus" class="badge badge-danger text-xxs pb-1 rounded-sm cursor-pointer w-100" onclick="hapusNasabah('${n.id}',event)" style="border-radius:4px;">hapus</a>
                    </div>
                    <div class="col-12 px-0 mt-2">
                        <a href='' id="btn-hapus" class="badge badge-warning text-xxs pb-1 rounded-sm cursor-pointer w-100" data-toggle="modal" data-target="#modalAddEditNasabah" onclick="openModalAddEditNsb('editasabah','${n.id}')" style="border-radius:4px;">edit</a>
                    </div>
                    <div class="col-12 px-0 mt-2">
                        <a href="${BASEURL}/admin/detilnasabah/${n.id}" id="btn-detil" class="badge badge-info text-xxs pb-1 rounded-sm cursor-pointer w-100" style="border-radius:4px;" target="_blank">detil</a>
                    </div>
                </div>
            </td>
        </tr>`;
        });

        $('#ket-total').html(arrayNasabah.length);
        $('#table-nasabah tbody').html(trNasabah);
    }
};
getAllNasabah();
 
// Search nasabah
$('#search-nasabah').on('keyup', function() {
    let elSugetion      = '';
    let nasabahFiltered = [];
    
    if ($(this).val() === "") {
        nasabahFiltered = arrayNasabah;
    } 
    else {
        nasabahFiltered = arrayNasabah.filter((n) => {
            return n.nama_lengkap.includes($(this).val().toLowerCase()) || n.id.includes($(this).val());
        });
    }

    if (nasabahFiltered.length == 0) {
        $('#list-nasabah-notfound').removeClass('d-none'); 
        $('#list-nasabah-notfound #text-notfound').html(`nasabah tidak ditemukan`); 
    } 
    else {
        $('#list-nasabah-notfound').addClass('d-none'); 
        $('#list-nasabah-notfound #text-notfound').html(` `); 

        nasabahFiltered.forEach((n,i) => {
            let stringLastActive = 'belum login';

            if (n.last_active != n.created_at) {
                let date  = new Date(parseInt(n.last_active) * 1000);
                let hour  = date.toLocaleString("en-US",{ hour: 'numeric', minute: 'numeric' });
                let day   = date.toLocaleString("en-US",{day: "numeric"});
                let month = date.toLocaleString("en-US",{month: "long"});
                let year  = date.toLocaleString("en-US",{year: "numeric"});
                stringLastActive = `${day}-${month}-${year} ${hour}`;
            }

            elSugetion += `<tr class="text-xs">
                <td class="align-middle text-center py-3">
                    <span class="font-weight-bold"> ${++i} </span>
                </td>
                <td class="align-middle text-center py-3">
                    <span class="font-weight-bold"> ${n.id} </span>
                </td>
                <td class="align-middle text-center">
                    <span class="font-weight-bold text-capitalize"> ${n.nama_lengkap} </span>
                </td>
                <td class="align-middle text-center">
                    <span class="font-weight-bold badge border ${(n.is_verify === 't' || n.is_verify === '1')? 'text-success border-success' : 'text-warning border-warning'} pb-1" style="border-radius:4px;">
                        ${(n.is_verify === 't' || n.is_verify === '1')? 'yes' : 'no'}
                    </span>
                </td>
                <td class="align-middle text-center">
                    <span class="font-weight-bold text-capitalize"> 
                        ${stringLastActive}
                    </span>
                </td>
                <td class="align-middle text-center">
                    <div clas="row w-100">
                        <div class="col-12 px-0">
                            <a href='' id="btn-hapus" class="badge badge-danger text-xxs pb-1 rounded-sm cursor-pointer w-100" onclick="hapusNasabah('${n.id}',event)" style="border-radius:4px;">hapus</a>
                        </div>
                        <div class="col-12 px-0 mt-2">
                            <a href='' id="btn-hapus" class="badge badge-warning text-xxs pb-1 rounded-sm cursor-pointer w-100" data-toggle="modal" data-target="#modalAddEditNasabah" onclick="openModalAddEditNsb('editasabah','${n.id}')" style="border-radius:4px;">edit</a>
                        </div>
                        <div class="col-12 px-0 mt-2">
                            <a href="${BASEURL}/admin/detilnasabah/${n.id}" id="btn-detil" class="badge badge-info text-xxs pb-1 rounded-sm cursor-pointer w-100" style="border-radius:4px;" target="_blank">detil</a>
                        </div>
                    </div>
                </td>
            </tr>`;
        });    
    }

    $('#ket-total').html(nasabahFiltered.length);
    $('#table-nasabah tbody').html(elSugetion);
});
 
// Edit modal when open
const openModalAddEditNsb = (modalName,idnasabah=null) => {
    let modalTitle = (modalName=='addnasabah') ? 'tambah nasabah' : 'edit nasabah' ;
    
    $('#modalAddEditNasabah .modal-title').html(modalTitle);
    $('#formAddEditNasabah .form-check-input').prop('checked',false);
    $('#formAddEditNasabah .form-control').removeClass('is-invalid');
    $('#formAddEditNasabah .form-check-input').removeClass('is-invalid');
    $('#formAddEditNasabah .text-danger').html('');

    if (modalName == 'addnasabah') {
        $('#modalAddEditNasabah .addnasabah-item').removeClass('d-none');
        $('#modalAddEditNasabah .editnasabah-item').addClass('d-none');        
        $(`#formAddEditNasabah .form-control`).val('');
    } 
    else {
        $('#modalAddEditNasabah .addnasabah-item').addClass('d-none');
        $('#modalAddEditNasabah .editnasabah-item').removeClass('d-none');        
        $('#modalAddEditNasabah #list-nasabah-spinner').removeClass('d-none');
        getProfileNasabah(idnasabah);
    }
}
 
/**
 * KODEPOS
 * =============================================
 */
// search kodepos
const searchKodepos = async (el) => {
 
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
                    <h6 style="opacity: 0.6;">kodepos tidak ditemukan</h6>
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
};
 
// codepos on click
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
 * CRUD NASABAH
 */
const crudNasabah = async (el,event) => {
    event.preventDefault();
    let form = new FormData(el);

    if (doValidate(form)) {
        let httpResponse = '';
        let modalTitle   = $('#modalAddEditNasabah .modal-title').html();

        if (form.get('tgl_lahir') != "") {
            let newTgl       = form.get('tgl_lahir').split('-');
            form.set('tgl_lahir',`${newTgl[2]}-${newTgl[1]}-${newTgl[0]}`);
        }

        $('#formAddEditNasabah button#submit #text').addClass('d-none');
        $('#formAddEditNasabah button#submit #spinner').removeClass('d-none');
        if (modalTitle == 'edit nasabah') {
            form.set('is_verify',$('#formAddEditNasabah input[name=is_verify]').val());
            httpResponse = await httpRequestPut(`${APIURL}/admin/editnasabah`,form);    
        } 
        else {
            form.set('kodepos',$('input[name=kodepos]').val());
            httpResponse = await httpRequestPost(`${APIURL}/register/nasabah`,form);    
        }
        $('#formAddEditNasabah button#submit #text').removeClass('d-none');
        $('#formAddEditNasabah button#submit #spinner').addClass('d-none');

        if (httpResponse.status === 201) {
            getAllWilayah();
            getAllNasabah();
            
            if (modalTitle == 'tambah nasabah') {
                $(`#formAddEditNasabah .form-control`).val('');
                $('#kodepos-wraper').html(`<div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
                    <h6 style="opacity: 0.6;">kodepos tidak ditemukan</h6>
                </div>`);  
            } 

            showAlert({
                message: `<strong>Success...</strong> nasabah berhasil ${(modalTitle == 'tambah nasabah') ? 'ditambah' : 'diedit' }!`,
                autohide: true,
                type:'success'
            })
        }
        else if (httpResponse.status === 400) {
            showAlert({
                message: `<strong>Gagal...</strong> cek kembali kolom input nasabah`,
                autohide: true,
                type:'warning'
            })

            if (httpResponse.message.email) {
                $('#formAddEditNasabah #email').addClass('is-invalid');
                $('#formAddEditNasabah #email-error').text(httpResponse.message.email);
            }
            if (httpResponse.message.username) {
                $('#formAddEditNasabah #username').addClass('is-invalid');
                $('#formAddEditNasabah #username-error').text(httpResponse.message.username);
            }
            if (httpResponse.message.tgl_lahir) {
                $('#formAddEditNasabah #tgllahir').addClass('is-invalid');
                $('#formAddEditNasabah #tgllahir-error').text(httpResponse.message.tgl_lahir);
            }
            if (httpResponse.message.notelp) {
                $('#formAddEditNasabah #notelp').addClass('is-invalid');
                $('#formAddEditNasabah #notelp-error').text(httpResponse.message.notelp);
            }
            if (httpResponse.message.nik) {
                $('#formAddEditNasabah #nik').addClass('is-invalid');
                $('#formAddEditNasabah #nik-error').text(httpResponse.message.nik);
            }
        }
    }
}

/**
 * GET PROFILE NASABAH
 */
const getProfileNasabah = async (id) => {

    let httpResponse = await httpRequestGet(`${APIURL}/admin/getnasabah?id=${id}`);
    $('#modalAddEditNasabah #list-nasabah-spinner').addClass('d-none');
    
    if (httpResponse.status === 200) {
        dataNasabah = httpResponse.data.data[0];
        
        for (const name in dataNasabah) {
            $(`#formAddEditNasabah input[name=${name}]`).val(dataNasabah[name]);
        }
    
        // tgl lahir
        if (dataNasabah.tgl_lahir.split('-') != "00-00-0000") {
            let tglLahir = dataNasabah.tgl_lahir.split('-');
            $(`#formAddEditNasabah input[name=tgl_lahir]`).val(`${tglLahir[2]}-${tglLahir[1]}-${tglLahir[0]}`);
        }
        // kelamin
        $(`#formAddEditNasabah input#kelamin-${dataNasabah.kelamin}`).prop('checked',true);
        // is verify
        if (dataNasabah.is_verify == 't' || dataNasabah.is_verify === '1') {
            $(`#formAddEditNasabah input[name=is_verify]`).val('1');
            $(`#formAddEditNasabah .toggle-akunverify`).removeClass('bg-secondary').addClass('active bg-success');
        } 
        else {
            $(`#formAddEditNasabah input[name=is_verify]`).val('0');
            $(`#formAddEditNasabah .toggle-akunverify`).removeClass('active bg-success').addClass('bg-secondary');
        }

        $('#newpass').val('');
    }
};

// change kelamin value
$('#formAddEditNasabah .form-check-input').on('click', function(e) {
    $(`#formAddEditNasabah input[name=kelamin]`).val($(this).val());
    $('#formAddEditNasabah .form-check-input').prop('checked',false);
    $(this).prop('checked',true);
});

// change akun verify
$('#formAddEditNasabah input[type=checkbox]').on('click', function(e) {
    if ($(this).val() == '1') {
        $(this).val('0');
        $(this).parent().removeClass('active bg-success').addClass('bg-secondary');
    } 
    else {
        $(this).val('1');
        $(this).parent().removeClass('bg-secondary').addClass('active bg-success');
    }
});

// form validation
const doValidate = (form) => {
    let status     = true;
    let emailRules = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

    // clear error message first
    $('.form-control').removeClass('is-invalid');
    $('.form-check-input').removeClass('is-invalid');
    $('.text-danger').html('');

    // name validation
    if ($('#formAddEditNasabah #nama').val() == '') {
        $('#formAddEditNasabah #nama').addClass('is-invalid');
        $('#formAddEditNasabah #nama-error').html('*nama lengkap harus di isi');
        status = false;
    }
    else if ($('#formAddEditNasabah #nama').val().length > 40) {
        $('#formAddEditNasabah #nama').addClass('is-invalid');
        $('#formAddEditNasabah #nama-error').html('*maksimal 40 huruf');
        status = false;
    }

    // kelamin validation
    if ($(`#formAddEditNasabah input[name=kelamin]`).val() == '') {
        $('.form-check-input').addClass('is-invalid');
        
        status = false;
    }

    // Add Nasabah
    if (!$('#modalAddEditNasabah .addnasabah-item').hasClass('d-none')) {
        // rt validation
        if ($('#formAddEditNasabah #rt').val() == '') {
            $('#formAddEditNasabah #rt').addClass('is-invalid');
            $('#formAddEditNasabah #rt-error').html('*rt harus di isi');
            status = false;
        }
        else if ($('#formAddEditNasabah #rt').val().length < 3 || $('#formAddEditNasabah #rt').val().length > 3) {
            $('#formAddEditNasabah #rt').addClass('is-invalid');
            $('#formAddEditNasabah #rt-error').html('*minimal 2 huruf dan maksimal 2 huruf');
            status = false;
        }
        else if (!/^\d+$/.test($('#formAddEditNasabah #rt').val())) {
            $('#formAddEditNasabah #rt').addClass('is-invalid');
            $('#formAddEditNasabah #rt-error').html('*hanya boleh angka');
            status = false;
        }
        // rw validation
        if ($('#formAddEditNasabah #rw').val() == '') {
            $('#formAddEditNasabah #rw').addClass('is-invalid');
            $('#formAddEditNasabah #rw-error').html('*rw harus di isi');
            status = false;
        }
        else if ($('#formAddEditNasabah #rw').val().length < 3 || $('#formAddEditNasabah #rw').val().length > 3) {
            $('#formAddEditNasabah #rw').addClass('is-invalid');
            $('#formAddEditNasabah #rw-error').html('*minimal 2 huruf dan maksimal 2 huruf');
            status = false;
        }
        else if (!/^\d+$/.test($('#formAddEditNasabah #rw').val())) {
            $('#formAddEditNasabah #rw').addClass('is-invalid');
            $('#formAddEditNasabah #rw-error').html('*hanya boleh angka');
            status = false;
        }
        // kodepos validation
        if ($('#formAddEditNasabah #kodepos').val() == '') {
            $('#formAddEditNasabah #kodepos').addClass('is-invalid');
            $('#formAddEditNasabah #kodepos-error').html('*kodepos harus di isi');
            status = false;
        }
        else if ($('#formAddEditNasabah #kodepos').val().length < 5 || $('#formAddEditNasabah #kodepos').val().length > 5) {
            $('#formAddEditNasabah #kodepos').addClass('is-invalid');
            $('#formAddEditNasabah #kodepos-error').html('*minimal 5 huruf dan maksimal 5 huruf');
            status = false;
        }
        else if (!/^\d+$/.test($('#formAddEditNasabah #kodepos').val())) {
            $('#formAddEditNasabah #kodepos').addClass('is-invalid');
            $('#formAddEditNasabah #kodepos-error').html('*hanya boleh angka');
            status = false;
        }
    }
    else{
        // email validation
        if ($('#formAddEditNasabah #email').val() != '' && !emailRules.test(String($('#formAddEditNasabah #email').val()).toLowerCase())) {
            $('#formAddEditNasabah #email').addClass('is-invalid');
            $('#formAddEditNasabah #email-error').html('*email tidak valid');
            status = false;
        }
        // username validation
        if ($('#formAddEditNasabah #username').val() == '') {
            $('#formAddEditNasabah #username').addClass('is-invalid');
            $('#formAddEditNasabah #username-error').html('*username harus di isi');
            status = false;
        }
        else if ($('#formAddEditNasabah #username').val().length < 8 || $('#formAddEditNasabah #username').val().length > 20) {
            $('#formAddEditNasabah #username').addClass('is-invalid');
            $('#formAddEditNasabah #username-error').html('*minimal 8 huruf dan maksimal 20 huruf');
            status = false;
        }
        else if (/\s/.test($('#formAddEditNasabah #username').val())) {
            $('#formAddEditNasabah #username').addClass('is-invalid');
            $('#formAddEditNasabah #username-error').html('*tidak boleh ada spasi');
            status = false;
        }
        // new pass 
        if ($('#modalAddEditNasabah #newpass').val() !== '') {   
            if ($('#modalAddEditNasabah #newpass').val().length < 8 || $('#modalAddEditNasabah #newpass').val().length > 20) {
                $('#modalAddEditNasabah #newpass').addClass('is-invalid');
                $('#modalAddEditNasabah #newpass-error').html('*minimal 8 huruf dan maksimal 20 huruf');
                status = false;
            }
            else if (/\s/.test($('#modalAddEditNasabah #newpass').val())) {
                $('#modalAddEditNasabah #newpass').addClass('is-invalid');
                $('#modalAddEditNasabah #newpass-error').html('*tidak boleh ada spasi');
                status = false;
            }
        }
    }

    // // tgl lahir validation
    // if ($('#formAddEditNasabah #tgllahir').val() == '') {
    //     $('#formAddEditNasabah #tgllahir').addClass('is-invalid');
    //     $('#formAddEditNasabah #tgllahir-error').html('*tgl lahir harus di isi');
    //     status = false;
    // }
    // nik validation
    let resultNik = '';

    if ($('#formAddEditNasabah #nik').val() != '') {
        nikParse($('#formAddEditNasabah #nik').val(), function(result) {
            resultNik = result;
        });	
    }
    if (resultNik.status == 'error') {
        $('#formAddEditNasabah #nik').addClass('is-invalid');
        $('#formAddEditNasabah #nik-error').html(resultNik.pesan);
        status = false;
    }
    // notelp validation
    if ($('#formAddEditNasabah #notelp').val().length > 14) {
        $('#formAddEditNasabah #notelp').addClass('is-invalid');
        $('#formAddEditNasabah #notelp-error').html('*maksimal 14 huruf');
        status = false;
    }
    else if ($('#formAddEditNasabah #notelp').val() != '' && !/^\d+$/.test($('#formAddEditNasabah #notelp').val())) {
        $('#formAddEditNasabah #notelp').addClass('is-invalid');
        $('#formAddEditNasabah #notelp-error').html('*hanya boleh angka');
        status = false;
    }
    // alamat validation
    if ($('#formAddEditNasabah #alamat').val().length > 255) {
        $('#formAddEditNasabah #alamat').addClass('is-invalid');
        $('#formAddEditNasabah #alamat-error').html('*maksimal 255 huruf');
        status = false;
    }

    return status;
}

/**
 * HAPUS NASABAH
 */
 const hapusNasabah = (id,event) => {
    event.preventDefault();

    Swal.fire({
        input: 'password',
        inputAttributes: {
            autocapitalize: 'off'
        },
        html:`<h5 class='mb-4'>Password</h5>`,
        showCancelButton: true,
        confirmButtonText: 'submit',
        showLoaderOnConfirm: true,
        preConfirm: (password) => {
            let form = new FormData();
            form.append('hashedpass',PASSADMIN);
            form.append('password',password);

            return axios
            .post(`${APIURL}/admin/confirmdelete`,form, {
                headers: {
                    // header options 
                }
            })
            .then((response) => {
                return httpRequestDelete(`${APIURL}/admin/deletenasabah?id=${id}`)
                .then((e) => {
                    if (e.status == 201) {
                        getAllWilayah();
                        getAllNasabah();
                    }
                    else if (e.status == 400) {
                        showAlert({
                            message: `<strong>Gagal . . .</strong> ${e.message}`,
                            autohide: true,
                            type:'danger'
                        })
                    }
                })
            })
            .catch(error => {
                if (error.response.status == 404) {
                    Swal.showValidationMessage(
                        `password salah`
                    )
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
}