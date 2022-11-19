
// -- modal when opened --
const openModalCrudKategori = (type,id = null) => {
    $('#modalCrudKategori .form-control').removeClass('is-invalid');

    if (type == 'addkategori') {
        resetModal();
        $("#modalCrudKategori .modal-title").html('Tambah kategori');
        $('#modalCrudKategori .text-danger').html('');
    } 
    else {
        $("#modalCrudKategori .modal-title").html('Edit kategori');

        let selectedKategori = arrayKatArtikel.filter((e) => e.id == id);
        $(`#modalCrudKategori input[name=id]`).val(selectedKategori[0].id);
        $(`#modalCrudKategori #img-preview`).attr('src',selectedKategori[0].icon);
        $(`#modalCrudKategori input[name=kategori_name]`).val(selectedKategori[0].name);
        $(`#modalCrudKategori textarea[name=description]`).val(selectedKategori[0].description);

        if (selectedKategori[0].kategori_utama == "1") {
            $('#modalCrudKategori .btn-toggle input').val('1');
            $('#modalCrudKategori .btn-toggle').addClass('active bg-success').removeClass('bg-secondary');
        }
        else{
            $('#modalCrudKategori .btn-toggle input').val('0');
            $('#modalCrudKategori .btn-toggle').removeClass('active bg-success').addClass('bg-secondary');
        }
    }
}

// -- reset form modal --
const resetModal = () => {
    $(`#modalCrudKategori .form-control`).val('');
    $('#modalCrudKategori .btn-toggle input').val('0');
    $('#modalCrudKategori .btn-toggle').removeClass('active bg-success').addClass('bg-secondary');
    $(`#modalCrudKategori #img-preview`).attr('src',`${BASEURL}/assets/images/skeleton-icon.png`);
}

// -- change toggle value --
$('#modalCrudKategori input[type=checkbox]').on('click', function(e) {
    if ($(this).val() == '1') {
        $(this).val('0');
        $(this).parent().removeClass('active bg-success').addClass('bg-secondary');
    } 
    else {
        $(this).val('1');
        $(this).parent().removeClass('bg-secondary').addClass('active bg-success');
    }
});

const changeThumbPreview = (el) => {
    let imgType = el.files[0].type.split('/');
    
    // If file is not image
    if(!/image/.test(imgType[0])){
        showAlert({
            message: `<strong>File yang anda upload bukan gambar!</strong>`,
            autohide: true,
            type:'danger'
        });

        el.value = "";
        return false;
    }
    // If image not in format
    else if(!["jpg","jpeg","png","webp"].includes(imgType[1])) {
        showAlert({
            message: `<strong>Format gambar yang diperbolehkan -> jpg/jpeg/png/webp!</strong>`,
            autohide: true,
            type:'danger'
        });

        el.value = "";
        return false;
    }
    // If image more than 200kb
    else if(el.files[0].size > 2000000){
        showAlert({
            message: `ukuran gambar maksimal 2mb`,
            autohide: true,
            type:'danger'
        });

        el.value = "";
        return false;
    }
    else{
        const file    = el.files[0];
        const blobURL = URL.createObjectURL(file);
        document.querySelector('#img-preview').src = blobURL;
    }
}

// validate add kategori
function doValidate(form) {
    let status = true;
    $('#modalCrudKategori .form-control').removeClass('is-invalid');
    $('#modalCrudKategori .text-danger').html('');
 
    if (form.get("id") == "") {
        if ($('input#icon').val() == '') {
            $('input#icon').addClass('is-invalid');
            status = false;
        }
    }

    if ($('input#kategori_name').val() == '') {
        $('input#kategori_name').addClass('is-invalid');
        status = false;
    }
    else if ($('input#kategori_name').val().length > 100) {
        $('input#kategori_name').addClass('is-invalid');
        $('#kategori_name-error').html('*maximal 100 huruf');
        status = false;
    }
    else if (/[^A-Za-z0-9\| ]/g.test($('input#kategori_name').val())) {
        $('input#kategori_name').addClass('is-invalid');
        $('#kategori_name-error').html('*hanya boleh huruf dan angka');
        status = false;
    }
    arrayKatArtikel.forEach(b => {
        if (form.get("id") == "") {
            if (b.name.toLowerCase() == $('input#kategori_name').val().toLowerCase().trim()) {
                $('input#kategori_name').addClass('is-invalid');
                $('#kategori_name-error').html('*kategori sudah tersedia');
                status = false;
            }
        }
        else{
            if (b.name.toLowerCase() == $('input#kategori_name').val().toLowerCase().trim() && b.id != form.get("id")) {
                $('input#kategori_name').addClass('is-invalid');
                $('#kategori_name-error').html('*kategori sudah tersedia');
                status = false;
            }
        }
    });

    if ($('#description').val() == '') {
        $('#description').addClass('is-invalid');
        status = false;
    }
 
    return status;
}

const crudKategoriArtikel = async (el,event) => {
    event.preventDefault();
    let form = new FormData(el.parentElement.parentElement);

    if (doValidate(form)) {
        let httpResponse = '';

        $('#modalCrudKategori button #text').addClass('d-none');
        $('#modalCrudKategori button #spinner').removeClass('d-none');

        if ($("#modalCrudKategori #kategori_utama").val() == "1") {
            form.set('kategori_utama',"1");    
        } 
        else {
            form.set('kategori_utama',"0");    
        }

        if (form.get("id") == "") {
            httpResponse = await httpRequestPost(`${APIURL}/artikel/addkategori`,form);
        }
        else {
            httpResponse = await httpRequestPut(`${APIURL}/artikel/editkategori`,form);
        }
        
        $('#modalCrudKategori button #text').removeClass('d-none');
        $('#modalCrudKategori button #spinner').addClass('d-none');

        if (httpResponse.status === 201) {
            if (form.get("id") == "") {
                resetModal();
            }

            getAllKatArtikel();
            
            showAlert({
                message: `<strong>Success...</strong> kategori berhasil ${(form.get("id") == "") ? 'ditambah' : 'diedit' }!`,
                autohide: true,
                type:'success'
            })
        }
        else if (httpResponse.status === 400) {
            if (httpResponse.message == "kategori utama maksimal 3") {
                showAlert({
                    message: `<strong>Gagal...</strong> kategori utama sudah melebihi 3!`,
                    autohide: true,
                    type:'warning'
                })
            }
        }
    }
}

/**
 * KATEGORI ARTIKEL SECTION
 * ====================================
 */
// GET all kategori
let arrayKatArtikel   = [];
const getAllKatArtikel = async () => {
    $('#search-kategori').val('');
    $('#table-kategori-artikel tbody').html('');
    $('#list-kategori-notfound').addClass('d-none'); 
    $('#list-kategori-spinner').removeClass('d-none'); 
    let httpResponse = await httpRequestGet(`${APIURL}/artikel/getkategori`);
    $('#list-kategori-spinner').addClass('d-none');
    
    if (httpResponse.status == 404) {
        arrayKatArtikel = [];
        $('#list-kategori-notfound').removeClass('d-none');
        $('#list-kategori-notfound #text-notfound').html(`kategori belum ditambah`); 
    }
    if (httpResponse.status === 200) {
        let trKategori  = '';
        arrayKatArtikel = httpResponse.data.data;
 
        httpResponse.data.data.forEach((k,i)=> {
            trKategori  += `<tr class="text-xs">
                <td class="align-middle text-center" style="max-width:40px;">
                    <span class="font-weight-bold"> ${++i} </span>
                </td>
                <td class="align-middle text-center py-3" style="max-width:100px;">
                    <img src="${k.icon}" class="img-thumbnail" style="width:100px;height:100px;max-width:100px;max-height:100px;">
                </td>
                <td class="align-middle text-center">
                    ${k.name}
                </td>
                <td class="align-middle text-center" style="max-width:150px;white-space: pre-wrap;"><div style="max-height:100px;overflow: auto;">${k.description}</div></td>
                <td class="align-middle text-center ${(k.kategori_utama == "1") ? "bg-success" : "bg-warning"}">
                    <i class="text-white ${(k.kategori_utama == "1") ? "fas fa-check " : "fas fa-times"}" style="font-size:30px;"></i>
                </td>
                <td class="align-middle text-center">
                    <a href='' data-toggle="modal" data-target="#modalCrudKategori" class="badge badge-warning text-xxs pb-1 rounded cursor-pointer" onclick="openModalCrudKategori('editkategori','${k.id}');">edit</a>
                    <a href='' id="${k.id}" class="badge badge-danger text-xxs pb-1 rounded cursor-pointer" onclick="hapusKategori('${k.id}','${k.name}',event)">hapus</a>
                </td>
             </tr>`;
        });
 
        $('#table-kategori-artikel tbody').html(trKategori);
    }
};
getAllKatArtikel();

// search kategori
$('#search-kategori').on('keyup', function() {
    let elSugetion      = '';
    let kategoriFiltered = [];
    
    if ($(this).val() === "") {
        kategoriFiltered = arrayKatArtikel;
    } 
    else {
        kategoriFiltered = arrayKatArtikel.filter((n) => {
            return n.name.includes($(this).val().toLowerCase());
        });
    }

    if (kategoriFiltered.length == 0) {
        $('#list-kategori-notfound').removeClass('d-none');
        $('#list-kategori-notfound #text-notfound').html(`kategori belum ditambah`); 
    } 
    else {
        $('#list-kategori-notfound').addClass('d-none');
        $('#list-kategori-notfound #text-notfound').html(``); 

        kategoriFiltered.forEach((k,i)=> {
            elSugetion  += `<tr class="text-xs">
                <td class="align-middle text-center" style="max-width:40px;">
                    <span class="font-weight-bold"> ${++i} </span>
                </td>
                <td class="align-middle text-center py-3" style="max-width:100px;">
                    <img src="${k.icon}" class="img-thumbnail" style="width:100px;height:100px;max-width:100px;max-height:100px;">
                </td>
                <td class="align-middle text-center">
                    ${k.name}
                </td>
                <td class="align-middle text-center" style="max-width:150px;white-space: pre-wrap;"><div style="max-height:100px;overflow: auto;">${k.description}</div></td>
                <td class="align-middle text-center ${(k.kategori_utama == "1") ? "bg-success" : "bg-warning"}">
                    <i class="text-white ${(k.kategori_utama == "1") ? "fas fa-check " : "fas fa-times"}" style="font-size:30px;"></i>
                </td>
                <td class="align-middle text-center">
                    <a href='' data-toggle="modal" data-target="#modalCrudKategori" class="badge badge-warning text-xxs pb-1 rounded cursor-pointer" onclick="openModalCrudKategori('editkategori','${k.id}');">edit</a>
                    <a href='' id="${k.id}" class="badge badge-danger text-xxs pb-1 rounded cursor-pointer" onclick="hapusKategori('${k.id}','${k.name}',event)">hapus</a>
                </td>
            </tr>`;
        });  
    }

    // $('#ket-total').html(kategoriFiltered.length);
    $('#table-kategori-artikel tbody').html(elSugetion);
});

// DELETE kategori
const hapusKategori = (id,katName,event) => {
    event.preventDefault();

    Swal.fire({
        title: 'ANDA YAKIN?',
        text: `berita dengan kategori '${katName}' akan terhapus juga`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'iya',
    }).then((result) => {
        if (result.isConfirmed) {
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
                        return httpRequestDelete(`${APIURL}/artikel/deletekategori?id=${id}`)
                        .then(e => {
                            if (e.status == 201) {
                                getAllKatArtikel();
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
    })
};
