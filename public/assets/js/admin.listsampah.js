/**
 * KATEGORI SAMPAH section
 * ==================================
 */
// get kategori sampah
let arrayKatSampah = [];
const getAllKatSampah = async () => {
    $('#kategori-sampah-wraper').html(`<div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
        <img src="${BASEURL}/assets/images/spinner.svg" style="width: 20px;" />
    </div>`); 

    let elSelectKat  = `<option value='' selected>-- pilih kategori --</option>`;
    let elKatFilter  = `<option value='' selected>-- semua kategori --</option>`;
    let httpResponse = await httpRequestGet(`${APIURL}/sampah/getkategori`);

    if (httpResponse.status === 404) {
        arrayKatSampah = [];
        $('#kategori-sampah-wraper').html(`<div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
            <h6 style="opacity: 0.6;">kategori belum tersedia</h6>
        </div>`); 
    }
    else if (httpResponse.status === 200) {
        let elKategori  = '';
        let allKategori = httpResponse.data.data;
        arrayKatSampah  = httpResponse.data.data;

        allKategori.forEach(k => {
            elSelectKat += `<option id="${k.id}" value="${k.id}">${k.name}</option>`;
            elKatFilter += `<option id="${k.name}" value="${k.name}">${k.name}</option>`;

            elKategori  += `
            <div class="w-100">
                <div class="kategori-list w-100 d-flex justify-content-between align-items-center px-3 py-2">
                    <div class="d-flex align-items-center text-md" style="flex: 1;">
                        ${k.name}
                    </div>
                    <a href='' id="${k.id}" class="badge badge-danger border-radius-sm cursor-pointer" onclick="hapusKatSampahVal('${k.id}','${k.name}',event)">
                        <i class="fas fa-trash text-white"></i>
                    </a>
                </div>
                <hr class="horizontal dark mt-0">
            </div>`;
        });

        $('#kategori-sampah-wraper').html(elKategori);
    }

    $('#select-sampah-wraper').html(elSelectKat);
    $('#formFilterSampah select[name=kategori]').html(elKatFilter);
};
getAllKatSampah();

// add kategori sampah
$('#btnAddKategoriSampah').on('click', async function(e) {
    e.preventDefault();
    
    if (validateAddKategori()) {

        let form = new FormData();
        form.append('kategori_name',$('input#NewkategoriSampah').val().trim().toLowerCase());

        $('#btnAddKategoriSampah #text').addClass('d-none');
        $('#btnAddKategoriSampah #spinner').removeClass('d-none');
        let httpResponse = await httpRequestPost(`${APIURL}/sampah/addkategori`,form);
        $('#btnAddKategoriSampah #text').removeClass('d-none');
        $('#btnAddKategoriSampah #spinner').addClass('d-none');

        if (httpResponse.status === 201) {
            $('input#NewkategoriSampah').val('');
            getAllKatSampah();

            showAlert({
                message: `<strong>Success...</strong> kategori berhasil ditambah!`,
                autohide: true,
                type:'success'
            })
        }
    }
});

// validate add kategori sampah
function validateAddKategori() {
    let status = true;

    if ($('input#NewkategoriSampah').val() == '') {
        showAlert({
            message: `<strong>Masukan kategori baru !</strong>`,
            autohide: true,
            type:'danger'
        })
        status = false;
    }
    else if ($('input#NewkategoriSampah').val().length > 100) {
        showAlert({
            message: `<strong>Kategori baru maximal 100 huruf !</strong>`,
            autohide: true,
            type:'danger'
        })
        status = false;
    }
    // else if (/[^A-Za-z0-9\| ]/g.test($('input#NewkategoriSampah').val())) {
    //     showAlert({
    //         message: `<strong>Kategori hanya boleh huruf dan angka !</strong>`,
    //         autohide: true,
    //         type:'danger'
    //     })
    //     status = false;
    // }
    // check kategori is exist
    arrayKatSampah.forEach(ks => {
        if (ks.name.toLowerCase() == $('input#NewkategoriSampah').val().toLowerCase().trim()) {
            showAlert({
                message: `<strong>Kategori sudah tersedia !</strong>`,
                autohide: true,
                type:'danger'
            })
            status = false;
        }
    });

    return status;
}

// delete kategori sampah
const hapusKatSampahVal = (id,katName,event) => {
    event.preventDefault();

    Swal.fire({
        title: 'ANDA YAKIN?',
        text: `semua sampah dengan kategori '${katName}' akan ikut terhapus`,
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
                        if ($('#formAddEditSampah input[name=kategori]').val() == katName) {
                            $('#formAddEditSampah input[name=kategori]').val('');    
                        }
                    
                        return httpRequestDelete(`${APIURL}/sampah/deletekategori?id=${id}`)
                        .then(e => {
                            if (e.status == 201) {
                                getAllKatSampah();
                                getAllJenisSampah();
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
    })
};

/**
 * SAMPAH section
 * ==================================
 */
// filter sampah
const filterSampah = async (e) => {
    let formFilter = new FormData(e.parentElement.parentElement.parentElement);
    let ketFilter  = `${formFilter.get('orderby')} - `;
    sampahUrl      = `${APIURL}/sampah/getsampah?orderby=${formFilter.get('orderby')}`;

    if (formFilter.get('kategori')) {
        ketFilter  += `${formFilter.get('kategori')}`
        sampahUrl  += `&kategori=${formFilter.get('kategori')}`
    }
    if (formFilter.get('kategori') == '') {
        ketFilter  = `${formFilter.get('orderby')} - semua kategori`;
        sampahUrl  = `${APIURL}/sampah/getsampah?orderby=${formFilter.get('orderby')}`
    }

    console.log(sampahUrl);

    $('#ket-filter').html(ketFilter);
    getAllJenisSampah();
};

const resetFilterSampah = async (e) => {
    $('#formFilterSampah select[name=orderby]').val('terlama');
    $('#formFilterSampah select[name=kategori]').val('');
};

// get all sampah
let arrayJenisSampah = [];
let sampahUrl        = `${APIURL}/sampah/getsampah?orderby=terlama`;
const getAllJenisSampah = async () => {
    $('#search-sampah').val('');
    $('#ket-total').html('0');
    $('#table-jenis-sampah tbody').html('');
    $('#list-sampah-notfound').addClass('d-none'); 
    $('#list-sampah-spinner').removeClass('d-none'); 
    let httpResponse = await httpRequestGet(sampahUrl);
    $('#list-sampah-spinner').addClass('d-none'); 
    
    if (httpResponse.status === 404) {
        $('#list-sampah-notfound').removeClass('d-none'); 
        $('#list-sampah-notfound #text-notfound').html(`jenis sampah belum ditambah`); 
    }
    else if (httpResponse.status === 200) {
        let trJenisSampah  = '';
        let allJenisSampah = sortingSampah(httpResponse.data.data);
        arrayJenisSampah   = allJenisSampah;
    
        allJenisSampah.forEach((n,i) => {

            trJenisSampah += `<tr class="text-xs">
                <td class="align-middle text-center py-3">
                    <span class="font-weight-bold"> ${++i} </span>
                </td>
                <td class="align-middle text-center">
                    <span class="font-weight-bold"> ${n.kategori} </span>
                </td>
                <td class="align-middle text-center">
                    ${n.jenis}
                </td>
                <td class="align-middle text-center py-3">
                    <span class="font-weight-bold">Rp. ${modifUang(n.harga_pusat)} </span>
                </td>
                <td class="align-middle text-center py-3">
                    <span class="font-weight-bold">Rp. ${modifUang(n.harga)} </span>
                </td>
                <td class="align-middle text-center py-3">
                    <span class="font-weight-bold"> ${parseFloat(n.jumlah).toFixed(2)} </span>
                </td>
                <td class="align-middle text-center">
                    <a href="" class="badge badge-danger text-xxs pb-1 rounded-sm cursor-pointer" onclick="hapusSampah('${n.id}',event)" style="border-radius:4px;">hapus</a>
                    <a href="" class="badge badge-warning text-xxs pb-1 rounded-sm cursor-pointer" data-toggle="modal" data-target="#modalAddEditSampah" onclick="openModalAddEditSmp('editasampah','${n.id}')" style="border-radius:4px;">edit</a>
                </td>
            </tr>`;
        });

        $('#ket-total').html(allJenisSampah.length);
        $('#table-jenis-sampah tbody').html(trJenisSampah);
    }
};
getAllJenisSampah();

// sorting sampah
const sortingSampah = (data) => {
    let arrKategori = [];
    let objSampah   = {};
    let newArrSampah= [];
    // let newData     = data.sort((a, b) => b.jumlah - a.jumlah);
    
    // create array kategori
    data.forEach(d => {
        if (!arrKategori.includes(d.kategori)) {
            arrKategori.push(d.kategori.replace(/\s/g,'_'));
        }
    });

    arrKategori.forEach(aK => {
        objSampah[aK] = data.filter((d) => {
            return d.kategori == aK.replace(/_/g,' ');
        })
    });

    for (let key in objSampah) {
        objSampah[key].forEach(x => {
            newArrSampah.push(x);
        });
    }

    return newArrSampah;
}

// Search sampah
$('#search-sampah').on('keyup', function() {
    let elSugetion     = '';
    let sampahFiltered = [];
    
    if ($(this).val() === "") {
        sampahFiltered = arrayJenisSampah;
    } 
    else {
        sampahFiltered = arrayJenisSampah.filter((n) => {
            return n.kategori.includes($(this).val().toLowerCase()) || n.jenis.includes($(this).val().toLowerCase());
        });
    }

    if (sampahFiltered.length == 0) {
        $('#list-sampah-notfound').removeClass('d-none'); 
        $('#list-sampah-notfound #text-notfound').html(`sampah tidak ditemukan`); 
    } 
    else {
        $('#list-sampah-notfound').addClass('d-none'); 
        $('#list-sampah-notfound #text-notfound').html(` `); 

        sampahFiltered.forEach((n,i) => {
            elSugetion += `<tr class="text-xs">
            <td class="align-middle text-center py-3">
                <span class="font-weight-bold"> ${++i} </span>
            </td>
            <td class="align-middle text-center">
                <span class="font-weight-bold"> ${n.kategori} </span>
            </td>
            <td class="align-middle text-center">
            ${n.jenis}
            </td>
            <td class="align-middle text-center py-3">
                <span class="font-weight-bold">Rp. ${modifUang(n.harga_pusat)} </span>
            </td>
            <td class="align-middle text-center py-3">
                <span class="font-weight-bold">Rp. ${modifUang(n.harga)} </span>
            </td>
            <td class="align-middle text-center py-3">
                <span class="font-weight-bold"> ${parseFloat(n.jumlah).toFixed(2)} </span>
            </td>
            <td class="align-middle text-center">
                <a href="" class="badge badge-danger text-xxs pb-1 rounded-sm cursor-pointer" onclick="hapusSampah('${n.id}',event)" style="border-radius:4px;">hapus</a>
                <a href="" class="badge badge-warning text-xxs pb-1 rounded-sm cursor-pointer" data-toggle="modal" data-target="#modalAddEditSampah" onclick="openModalAddEditSmp('editasampah','${n.id}')" style="border-radius:4px;">edit</a>
            </td>
        </tr>`;
        });    
    }

    $('#ket-total').html(sampahFiltered.length);
    $('#table-jenis-sampah tbody').html(elSugetion);
});

// clear input form
const clearInputForm = () => {
    $(`#formAddEditSampah .form-control`).val('');
}

// Edit modal when open
const openModalAddEditSmp = (modalName,idSampah=null) => {
    let modalTitle = (modalName=='addsampah') ? 'tambah sampah' : 'edit sampah' ;
    
    $('#modalAddEditSampah .modal-title').html(modalTitle);
    // clear error message first
    $('#formAddEditSampah .form-control').removeClass('is-invalid');
    $('#formAddEditSampah .text-danger').html('');

    if (modalName == 'addsampah') {
        clearInputForm();
        $('#formAddEditSampah #id').val('');

        $(`#kategori-sampah-wraper .kategori-list span`).removeClass('d-none');
    } 
    else {
        let selectedSampah = arrayJenisSampah.filter((n) => {
            return n.id == idSampah;
        });
        
        $('#formAddEditSampah #id').val(selectedSampah[0].id);
        $('#formAddEditSampah #jenis').val(selectedSampah[0].jenis);
        $('#formAddEditSampah #harga').val(selectedSampah[0].harga);
        $('#formAddEditSampah #harga_pusat').val(selectedSampah[0].harga_pusat);
        $('#formAddEditSampah #select-sampah-wraper').val(selectedSampah[0].id_kategori);
        $(`#kategori-sampah-wraper .kategori-list span#${selectedSampah[0].id_kategori}`).addClass('d-none');
    }
}

// Auto insert nasabah price
$('input#harga_pusat').on('keyup', function(e) {
    $('input#harga').val(this.value - ((10/100)*this.value));
});

// crud sampah
const crudSampah = async (el,event) => {
    event.preventDefault();
    
    if (doValidateAddSmp()) {
        let httpResponse = '';
        let message = 'sampah berhasil diedit!';
        let form = new FormData(el);

        $('#formAddEditSampah #btn-add-edit-sampah #text').addClass('d-none');
        $('#formAddEditSampah #btn-add-edit-sampah #spinner').removeClass('d-none');
        
        if ($('#formAddEditSampah #id').val() != '') {
            httpResponse = await httpRequestPut(`${APIURL}/sampah/editsampah`,form);
        } 
        else {
            httpResponse = await httpRequestPost(`${APIURL}/sampah/addsampah`,form);    
        }

        $('#formAddEditSampah #btn-add-edit-sampah #text').removeClass('d-none');
        $('#formAddEditSampah #btn-add-edit-sampah #spinner').addClass('d-none');

        if (httpResponse.status === 201) {
            if ($('#formAddEditSampah #id').val() == '') {
                message = 'sampah berhasil ditambah!';
                clearInputForm();
            }
            else {
                $(`#kategori-sampah-wraper .kategori-list span`).removeClass('d-none');
                $(`#kategori-sampah-wraper .kategori-list span#${form.get('id_kategori')}`).addClass('d-none');
            }

            getAllJenisSampah();

            showAlert({
                message: `<strong>Success...</strong> ${message}`,
                autohide: true,
                type:'success'
            })
        }
        else if (httpResponse.status === 400) {
            if (httpResponse.message.jenis) {
                $('#formAddEditSampah #jenis').addClass('is-invalid');
                $('#formAddEditSampah #jenis-error').text(httpResponse.message.jenis);
            }
        }
    }
}

// hapus sampah
const hapusSampah = (id,event) => {
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
                return httpRequestDelete(`${APIURL}/sampah/deletesampah?id=${id}`)
                .then((e) => {
                    if (e.status == 201) {
                        getAllJenisSampah();
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

// validate add sampah
const doValidateAddSmp = () => {
    let status = true;

    // clear error message first
    $('.form-control').removeClass('is-invalid');
    $('.text-danger').html('');

    // jenis validation
    if ($('#formAddEditSampah #jenis').val() == '') {
        $('#formAddEditSampah #jenis').addClass('is-invalid');
        $('#formAddEditSampah #jenis-error').html('*jenis harus di isi');
        status = false;
    }
    else if ($('#formAddEditSampah #jenis').val().length > 40) {
        $('#formAddEditSampah #jenis').addClass('is-invalid');
        $('#formAddEditSampah #jenis-error').html('*maksimal 40 huruf');
        status = false;
    }
    // harga validation
    if ($('#formAddEditSampah #harga').val() == '') {
        $('#formAddEditSampah #harga').addClass('is-invalid');
        $('#formAddEditSampah #harga-error').html('*harga harus di isi');
        status = false;
    }
    else if ($('#formAddEditSampah #harga').val().length > 11) {
        $('#formAddEditSampah #harga').addClass('is-invalid');
        $('#formAddEditSampah #harga-error').html('*maksimal 11 angka');
        status = false;
    }
    else if (!/^\d+$/.test($('#formAddEditSampah #harga').val())) {
        $('#formAddEditNasabah #harga').addClass('is-invalid');
        $('#formAddEditNasabah #harga-error').html('*hanya boleh angka');
        status = false;
    }
    // harga pusat validation
    if ($('#formAddEditSampah #harga_pusat').val() == '') {
        $('#formAddEditSampah #harga_pusat').addClass('is-invalid');
        $('#formAddEditSampah #harga_pusat-error').html('*harga pengepul harus di isi');
        status = false;
    }
    else if ($('#formAddEditSampah #harga_pusat').val().length > 11) {
        $('#formAddEditSampah #harga_pusat').addClass('is-invalid');
        $('#formAddEditSampah #harga_pusat-error').html('*maksimal 11 angka');
        status = false;
    }
    else if (!/^\d+$/.test($('#formAddEditSampah #harga_pusat').val())) {
        $('#formAddEditNasabah #harga_pusat').addClass('is-invalid');
        $('#formAddEditNasabah #harga_pusat-error').html('*hanya boleh angka');
        status = false;
    }
    // kategori validation
    if ($('#formAddEditSampah #select-sampah-wraper').val() == '') {
        $('#formAddEditSampah #select-sampah-wraper').addClass('is-invalid');
        $('#formAddEditSampah #select-sampah-wraper-error').html('*kategori harus di isi');
        status = false;
    }

    return status;
}