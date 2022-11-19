localStorage.removeItem("add-artikel"); 

/**
 * GET all kategori
 * ====================================
 */
const getAllKatBerita = async () => {
    let elKatFilter  = `<option value='' selected>-- semua kategori --</option>`;
    let httpResponse = await httpRequestGet(`${APIURL}/artikel/getkategori`);
    
    if (httpResponse.status === 200) {
        httpResponse.data.data.forEach((k,i)=> {
            elKatFilter += `<option id="${k.id}" value="${k.name}">${k.name}</option>`;
        });
    }
    
    $('#formFilterArtikel select[name=kategori]').html(elKatFilter);
};

getAllKatBerita();

/**
 * filter artikel
 * =============================================================
 */
const filterArtikel = async (e) => {
    let formFilter = new FormData(e.parentElement.parentElement.parentElement);
    let ketFilter  = `${formFilter.get('orderby')} - `;
    artikelUrl     = `${APIURL}/artikel/getartikel?orderby=${formFilter.get('orderby')}`;

    if (formFilter.get('kategori')) {
        ketFilter  += `${formFilter.get('kategori')}`
        artikelUrl += `&kategori=${formFilter.get('kategori')}`
    }
    if (formFilter.get('kategori') == '') {
        ketFilter  = `${formFilter.get('orderby')} - semua kategori`;
        artikelUrl = `${APIURL}/artikel/getartikel?orderby=${formFilter.get('orderby')}`
    }

    $('#ket-filter').html(ketFilter);
    getAllBerita();
};

const resetFilterArtikel = async (e) => {
    $('#formFilterArtikel select[name=orderby]').val('terbaru');
    $('#formFilterArtikel select[name=kategori]').val('');
};

// GET all artikel
let arrayBerita    = [];
let artikelUrl     = `${APIURL}/artikel/getartikel?orderby=terbaru`;
const getAllBerita = async () => {
    $('#search-artikel').val('');
    $('#ket-total').html('0');
    $('#container-list-artikel').html(''); 
    $('#list-artikel-notfound').addClass('d-none'); 
    $('#list-artikel-spinner').removeClass('d-none'); 
    let httpResponse = await httpRequestGet(artikelUrl);
    $('#list-artikel-spinner').addClass('d-none'); 
    
    if (httpResponse.status === 404) {
        arrayBerita = [];
        $('#list-artikel-notfound').removeClass('d-none'); 
        $('#list-artikel-notfound #text-notfound').html(`artikel belum ditambah`); 
    }
    else if (httpResponse.status === 200) {
        let elBerita  = '';
        let allBerita = httpResponse.data.data;
        arrayBerita   = allBerita;
    
        allBerita.forEach(b => {
            let date      = new Date(parseInt(b.published_at) * 1000);
            // let day       = date.toLocaleString("en-US",{day: "2-digit"});
            // let month     = date.toLocaleString("en-US",{month: "long"});
            // let year      = date.toLocaleString("en-US",{year: "numeric"});
            let isPublish = new Date().getTime() >= date.getTime();

            elBerita += `<div class="col-12 col-sm-6 col-lg-4 mb-4" style="min-height: 100%;">
            <div class="card" style="border: 0.5px solid #D2D6DA;min-height: 100%;">
                <div class="position-relative">
                    <img class="card-img-top border-radius-0 position-absolute" style="z-index: 10;min-width: 100%;max-width: 100%;max-height: 100%;;min-height: 100%;" src="${b.thumbnail}" style="opacity: 0;">
                    <img class="card-img-top border-radius-0" src="${BASEURL}/assets/images/default-thumbnail.jpg" style="opacity: 0;">
                </div>
                <div class="card-body pb-0 d-flex flex-column justify-content-between" style="font-family: 'qc-semibold';">
                    <div class="row">
                        <h4 class="card-title text-capitalize" style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">${b.title}</h4>
                        <h6 class="card-subtitle mb-2 text-muted text-sm">
                            <i class="fas fa-list-ul mr-1 text-muted text-xs"></i>
                            ${b.kategori}
                        </h6>
                        <div class="px-2">
                            <div style="width:max-content;" class="badge ${isPublish?'badge-success':'badge-secondary'}">
                                ${isPublish?'sudah terpublikasi':'belum terpublikasi'}
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-6">
                            <a href="${BASEURL}/admin/editartikel/${b.id}" class="w-100 btn btn-warning p-2 border-radius-sm" style="height: 34px;">
                                <i class="far fa-edit"></i>
                            </a>
                        </div>
                        <div class="col-6">
                            <span class="w-100 btn btn-danger p-2 border-radius-sm" style="height: 34px;" onclick="hapusArtikel('${b.id}');">
                                <i class="fas fa-trash text-white"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        });

        $('#ket-total').html(allBerita.length);
        $('#container-list-artikel').html(elBerita);
    }
};
getAllBerita();

// Search artikel
$('#search-artikel').on('keyup', function() {
    let elSugetion     = '';
    let beritaFiltered = [];
    
    if ($(this).val() === "") {
        beritaFiltered = arrayBerita;
    } 
    else {
        beritaFiltered = arrayBerita.filter((n) => {
            return n.title.includes($(this).val().toLowerCase()) || n.kategori.includes($(this).val().toLowerCase());
        });
    }

    if (beritaFiltered.length == 0) {
        $('#list-artikel-notfound').removeClass('d-none'); 
        $('#list-artikel-notfound #text-notfound').html(`artikel tidak ditemukan`); 
    } 
    else {
        $('#list-artikel-notfound').addClass('d-none'); 
        $('#list-artikel-notfound #text-notfound').html(` `); 

        beritaFiltered.forEach(b => {
            let date      = new Date(parseInt(b.created_at) * 1000);
            let day       = date.toLocaleString("en-US",{day: "numeric"});
            let month     = date.toLocaleString("en-US",{month: "long"});
            let year      = date.toLocaleString("en-US",{year: "numeric"});
            elSugetion += `<div class="col-12 col-sm-6 col-lg-4" style="min-height: 100%;">
            <div class="card mb-3" style="border: 0.5px solid #D2D6DA;min-height: 100%;">
                <div class="position-relative">
                    <img class="card-img-top border-radius-0 position-absolute" style="z-index: 10;min-width: 100%;max-width: 100%;max-height: 100%;;min-height: 100%;" src="${b.thumbnail}" style="opacity: 0;">
                    <img class="card-img-top border-radius-0" src="${BASEURL}/assets/images/default-thumbnail.jpg" style="opacity: 0;">
                </div>
                <div class="card-body pb-0 d-flex flex-column justify-content-between" style="font-family: 'qc-semibold';">
                    <div class="row">
                        <h4 class="card-title text-capitalize" style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">${b.title}</h4>
                        <h6 class="card-subtitle mb-2 text-muted text-sm">
                            <i class="fas fa-list-ul mr-1 text-muted text-xs"></i>
                            ${b.kategori}
                        </h6>
                        <h6 class="card-subtitle mb-2 text-muted text-sm">
                            <i class="far fa-clock mr-1 text-muted text-xs"></i>
                            ${month}, ${day}, ${year}
                        </h6>
                        <h6 class="card-subtitle text-muted text-sm" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">
                            <i class="fas fa-user-edit mr-1 text-muted text-xs"></i>
                            ${b.penulis}
                        </h6>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <a href="${BASEURL}/admin/editartikel/${b.id}" class="w-100 btn btn-warning p-2 border-radius-sm" style="height: 34px;">
                                <i class="far fa-edit"></i>
                            </a>
                        </div>
                        <div class="col-6">
                            <span class="w-100 btn btn-danger p-2 border-radius-sm" style="height: 34px;" onclick="hapusArtikel('${b.id}');">
                                <i class="fas fa-trash text-white"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        });    
    }

    $('#ket-total').html(beritaFiltered.length);
    $('#container-list-artikel').html(elSugetion);
});

/**
  * HAPUS ARTIKEL
  */
const hapusArtikel = (id) => {
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
                return httpRequestDelete(`${APIURL}/artikel/deleteartikel?id=${id}`)
                .then((e) => {
                    if (e.status == 201) {
                        getAllBerita();
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