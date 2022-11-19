
// -- modal when opened --
const openModalAddMitra = () => {
    $('#modalAddMitra .form-control').removeClass('is-invalid');
    $('#modalAddMitra .text-danger').html('');
    resetModal();
}

// -- reset form modal --
const resetModal = () => {
    $(`#modalAddMitra .form-control`).val('');
    $(`#modalAddMitra #img-preview`).attr('src',`${BASEURL}/assets/images/skeleton-icon.png`);
}

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

// validate add mitra
function doValidate() {
    let status = true;
    $('#modalAddMitra .form-control').removeClass('is-invalid');
    $('#modalAddMitra .text-danger').html('');

    if ($('input#mitra_name').val() == '') {
        $('input#mitra_name').addClass('is-invalid');
        status = false;
    }
    else if ($('input#mitra_name').val().length > 255) {
        $('input#mitra_name').addClass('is-invalid');
        $('#mitra_name-error').html('*maximal 255 huruf');
        status = false;
    }
    else if (/[^A-Za-z0-9\| ]/g.test($('input#mitra_name').val())) {
        $('input#mitra_name').addClass('is-invalid');
        $('#mitra_name-error').html('*hanya boleh huruf dan angka');
        status = false;
    }
    arrayMitra.forEach(b => {
        if (b.name.toLowerCase() == $('input#mitra_name').val().toLowerCase().trim()) {
            $('input#mitra_name').addClass('is-invalid');
            $('#mitra_name-error').html('*mitra sudah tersedia');
            status = false;
        }
    });

    if ($('#description').val() == '') {
        $('#description').addClass('is-invalid');
        status = false;
    }
 
    return status;
}

const addMitra = async (el,event) => {
    event.preventDefault();
    let form = new FormData(el.parentElement.parentElement);

    if (doValidate()) {
        let httpResponse = '';

        $('#modalAddMitra button #text').addClass('d-none');
        $('#modalAddMitra button #spinner').removeClass('d-none');

        httpResponse = await httpRequestPost(`${APIURL}/mitra/addmitra`,form);
        
        $('#modalAddMitra button #text').removeClass('d-none');
        $('#modalAddMitra button #spinner').addClass('d-none');

        if (httpResponse.status === 201) {
            resetModal();

            getAllMitra();
            
            showAlert({
                message: `<strong>Success...</strong> mitra berhasil ditambah!`,
                autohide: true,
                type:'success'
            })
        }
    }
}

// GET all mitra
let arrayMitra    = [];
const getAllMitra = async () => {
    $('#table-mitra tbody').html('');
    $('#list-mitra-notfound').addClass('d-none'); 
    $('#list-mitra-spinner').removeClass('d-none'); 
    let httpResponse = await httpRequestGet(`${APIURL}/mitra/getmitra`);
    $('#list-mitra-spinner').addClass('d-none');
    
    if (httpResponse.status == 404) {
        arrayMitra = [];
        $('#list-mitra-notfound').removeClass('d-none');
        $('#list-mitra-notfound #text-notfound').html(`mitra belum ditambah`); 
    }
    if (httpResponse.status === 200) {
        let trMitra = '';
        arrayMitra = httpResponse.data.data;
 
        httpResponse.data.data.forEach((k,i)=> {
            trMitra  += `<tr class="text-xs">
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
                <td class="align-middle text-center">
                    <a href='' id="${k.id}" class="badge badge-danger text-xxs pb-1 rounded cursor-pointer" onclick="hapusMitra('${k.id}',event)">hapus</a>
                </td>
             </tr>`;
        });
 
        $('#table-mitra tbody').html(trMitra);
    }
};
getAllMitra();

// DELETE mitra
const hapusMitra = (id,event) => {
    event.preventDefault();

    Swal.fire({
        title: 'ANDA YAKIN?',
        text: `data akan terhapus permanen`,
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
                        return httpRequestDelete(`${APIURL}/mitra/deletemitra?id=${id}`)
                        .then(e => {
                            if (e.status == 201) {
                                getAllMitra();
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
