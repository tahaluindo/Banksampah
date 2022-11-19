
// -- modal when opened --
const openModalAddPenghargaan = () => {
    $('#modalAddPenghargaan .form-control').removeClass('is-invalid');
    $('#modalAddPenghargaan .text-danger').html('');
    resetModal();
}

// -- reset form modal --
const resetModal = () => {
    $(`#modalAddPenghargaan .form-control`).val('');
    $(`#modalAddPenghargaan #img-preview`).attr('src',`${BASEURL}/assets/images/skeleton-icon.png`);
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

// validate add penghargaan
function doValidate() {
    let status = true;
    $('#modalAddPenghargaan .form-control').removeClass('is-invalid');
    $('#modalAddPenghargaan .text-danger').html('');

    if ($('input#penghargaan_name').val() == '') {
        $('input#penghargaan_name').addClass('is-invalid');
        status = false;
    }
    else if ($('input#penghargaan_name').val().length > 255) {
        $('input#penghargaan_name').addClass('is-invalid');
        $('#penghargaan_name-error').html('*maximal 255 huruf');
        status = false;
    }
    else if (/[^A-Za-z0-9\| ]/g.test($('input#penghargaan_name').val())) {
        $('input#penghargaan_name').addClass('is-invalid');
        $('#penghargaan_name-error').html('*hanya boleh huruf dan angka');
        status = false;
    }
    arrayPenghargaan.forEach(b => {
        if (b.name.toLowerCase() == $('input#penghargaan_name').val().toLowerCase().trim()) {
            $('input#penghargaan_name').addClass('is-invalid');
            $('#penghargaan_name-error').html('*penghargaan sudah tersedia');
            status = false;
        }
    });

    if ($('#description').val() == '') {
        $('#description').addClass('is-invalid');
        status = false;
    }
 
    return status;
}

const addPenghargaan = async (el,event) => {
    event.preventDefault();
    let form = new FormData(el.parentElement.parentElement);

    if (doValidate()) {
        let httpResponse = '';

        $('#modalAddPenghargaan button #text').addClass('d-none');
        $('#modalAddPenghargaan button #spinner').removeClass('d-none');

        httpResponse = await httpRequestPost(`${APIURL}/penghargaan/addpenghargaan`,form);
        
        $('#modalAddPenghargaan button #text').removeClass('d-none');
        $('#modalAddPenghargaan button #spinner').addClass('d-none');

        if (httpResponse.status === 201) {
            resetModal();

            getAllPenghargaan();
            
            showAlert({
                message: `<strong>Success...</strong> penghargaan berhasil ditambah!`,
                autohide: true,
                type:'success'
            })
        }
    }
}

// GET all penghargaan
let arrayPenghargaan   = [];
const getAllPenghargaan = async () => {
    $('#table-penghargaan tbody').html('');
    $('#list-penghargaan-notfound').addClass('d-none'); 
    $('#list-penghargaan-spinner').removeClass('d-none'); 
    let httpResponse = await httpRequestGet(`${APIURL}/penghargaan/getpenghargaan`);
    $('#list-penghargaan-spinner').addClass('d-none');
    
    if (httpResponse.status == 404) {
        arrayPenghargaan = [];
        $('#list-penghargaan-notfound').removeClass('d-none');
        $('#list-penghargaan-notfound #text-notfound').html(`penghargaan belum ditambah`); 
    }
    if (httpResponse.status === 200) {
        let trPenghargaan = '';
        arrayPenghargaan = httpResponse.data.data;
 
        httpResponse.data.data.forEach((k,i)=> {
            trPenghargaan  += `<tr class="text-xs">
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
                    <a href='' id="${k.id}" class="badge badge-danger text-xxs pb-1 rounded cursor-pointer" onclick="hapusPenghargaan('${k.id}',event)">hapus</a>
                </td>
             </tr>`;
        });
 
        $('#table-penghargaan tbody').html(trPenghargaan);
    }
};
getAllPenghargaan();

// DELETE penghargaan
const hapusPenghargaan = (id,event) => {
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
                        return httpRequestDelete(`${APIURL}/penghargaan/deletepenghargaan?id=${id}`)
                        .then(e => {
                            if (e.status == 201) {
                                getAllPenghargaan();
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
