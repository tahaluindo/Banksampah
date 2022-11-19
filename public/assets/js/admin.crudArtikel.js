// Quil editor initialization
if (pageTitle2 === 'tambah artikel' || pageTitle2 === 'edit artikel') {
    Quill.register("modules/imageCompressor", imageCompressor);
    var quill = new Quill('#editor-container', {
        modules: {
            imageResize: {
                displaySize: true,
                debug: false, // default
            },
            imageCompressor: {
                quality: 0.9, // default
                imageType: 'image/jpeg', // default
                debug: false, // default
            },
            formula: true,
            syntax: true,
            toolbar: '#toolbar-container'
        },
        theme: 'snow'
    });
}

/**
 * GET all kategori
 * ====================================
 */
const getAllKatArtikel = async () => {

    showLoadingSpinner();

    let elKategori   = `<option value='' selected>-- pilih kategori --</option>`;
    let httpResponse = await httpRequestGet(`${APIURL}/artikel/getkategori`);
    
    if (httpResponse.status === 200) {
        httpResponse.data.data.forEach((k,i)=> {
            elKategori  += `<option id="${k.id}" value="${k.id}">${k.name}</option>`;
        });

        if (pageTitle2 === 'edit artikel') {
            getDetailBerita();
        }
        else{
            hideLoadingSpinner();
        }
    }
    else {
        hideLoadingSpinner();
    }

    $('#kategori-artikel-wraper').html(elKategori);
    editTemporaryContent();
};
getAllKatArtikel();

/**
 * ARTIKEL SECTION
 * =============================================================
 */
// GET DETAIL ARTIKEL
const getDetailBerita = async () => {
    let httpResponse = await httpRequestGet(`${APIURL}/artikel/getartikel?id=${IDARTIKEL}`);
    hideLoadingSpinner();
    
    if (httpResponse.status == 404) {
        Swal.fire({
            icon : 'error',
            title : '<strong>NOT FOUND</strong>',
            text: `artikel dengan id '${IDARTIKEL}' tidak ditemukan!`,
            showCancelButton: false,
            confirmButtonText: 'ok',
        }).then(() => {
            window.location.replace(`${BASEURL}/admin/listartikel`);
        })
    }
    if (httpResponse.status === 200) {
        let dataArtikel = httpResponse.data.data;
        let date        = new Date(parseInt(dataArtikel.published_at) * 1000);
        let day         = date.toLocaleString("en-US",{day: "2-digit"});
        let month       = date.toLocaleString("en-US",{month: "2-digit"});
        let year        = date.toLocaleString("en-US",{year: "numeric"});

        $('#idartikel').val(dataArtikel.id);
        document.querySelector('#preview-thumbnail').src = dataArtikel.thumbnail;
        $('#published_at').val(`${year}-${month}-${day}`);
        $('#title').val(dataArtikel.title);
        $(`#kategori-artikel-wraper`).val(dataArtikel.id_kategori);
        $('.ql-editor').html(dataArtikel.content);
    }
};

// CRUD artikel
$('#formCrudArticle').on('submit', async (e) => {
    e.preventDefault();
    
    if (validateCrudArtikel()) {
        let httpResponse = '';
        let form         = new FormData(e.target);
        let tglPublish   = form.get('published_at').split('-');

        if (fileThumbnail !== '') {
            form.set('thumbnail', fileThumbnail, fileThumbnail.name);
        }
        form.append('content',$('.ql-editor').html());
        form.set('published_at',`${tglPublish[2]}-${tglPublish[1]}-${tglPublish[0]}`);

        showLoadingSpinner();

        if (IDARTIKEL !== '') {
            if (fileThumbnail !== '') {
                form.set('new_thumbnail', fileThumbnail, fileThumbnail.name);
            }
            httpResponse = await httpRequestPut(`${APIURL}/artikel/editartikel`,form);    
        } 
        else {
            form.set('thumbnail', fileThumbnail, fileThumbnail.name);
            httpResponse = await httpRequestPost(`${APIURL}/artikel/addartikel`,form);    
        }
        
        hideLoadingSpinner();

        if (httpResponse.status === 201) {

            localStorage.removeItem('artikel-content');

            setTimeout(() => {
                Swal.fire({
                    icon : 'success',
                    title : '<strong>SUCCESS</strong>',
                    html: `artikel berhasil ${(IDARTIKEL == '') ? 'dipublish' : 'diedit' }!`,
                    showCancelButton: false,
                    confirmButtonText: 'ok',
                })
                .then(() => {
                    window.location.replace(`${BASEURL}/admin/listartikel`);
                })
            }, 300);
        }
        else if (httpResponse.status === 400) {
           if (httpResponse.message.title) {
               $('#title').addClass('is-invalid');
               $('#title-error').text(httpResponse.message.title);
           }
        }
    }
})

// auto save ql editor
let dataTemporaryContent = JSON.parse(localStorage.getItem('add-artikel'));
if (pageTitle2 === 'tambah artikel') {
    $('#formCrudArticle #published_at').on('change', (e) => {
        saveTemporaryContent();
    })
    $('#formCrudArticle #title').on('keyup', (e) => {
        saveTemporaryContent();
    })
    $('#formCrudArticle #kategori-artikel-wraper').on('change', (e) => {
        saveTemporaryContent();
    })
    $('.ql-editor').on('keyup', (e) => {
        saveTemporaryContent();
    })
}

function saveTemporaryContent() {
    let objContent = {
        published_at: $('#formCrudArticle #published_at').val(),
        title: $('#formCrudArticle #title').val(),
        kategori: $('#formCrudArticle #kategori-artikel-wraper').val(),
        content: $('.ql-editor').html(),
    }

    localStorage.setItem('add-artikel',JSON.stringify(objContent));
}

function editTemporaryContent() {
    if (pageTitle2 === 'tambah artikel' && dataTemporaryContent) {
        if (dataTemporaryContent.published_at) {
            $('#formCrudArticle #published_at').val(dataTemporaryContent.published_at);
        }

        if (dataTemporaryContent.title) {
            $('#formCrudArticle #title').val(dataTemporaryContent.title);
        }
        
        if (dataTemporaryContent.kategori) {
            $('#formCrudArticle #kategori-artikel-wraper').val(dataTemporaryContent.kategori);
        }

        $('.ql-editor').html(dataTemporaryContent.content);
    }
}

// validate crud artikel
function validateCrudArtikel() {
    let status = true;

    // clear error message first
    $('.form-control').removeClass('is-invalid');
    $('.text-danger').html('');
 
    // thumbnail
    if (IDARTIKEL == '') {
        if ($('#thumbnail').val() == '') {
            $('#thumbnail').addClass('is-invalid');
            status = false;
        }
    }
    // published_at
    if ($('#published_at').val() == '') {
        $('#published_at').addClass('is-invalid');
        status = false;
    }
    // title
    if ($('#title').val() == '') {
        $('#title').addClass('is-invalid');
        status = false;
    }
    else if ($('#title').val().length > 250) {
        $('#title').addClass('is-invalid');
        $('#title-error').html('*maximal 250 character');
        status = false;
    }
    // kategori
    if ($('#kategori-artikel-wraper').val() == '') {
        $('#kategori-artikel-wraper').addClass('is-invalid');
        status = false;
    }

    return status;
}

// Thumbnail Preview
let fileThumbnail = '';

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
    else{
        const MAX_WIDTH  = 500;
        const MAX_HEIGHT = 308;
        const MIME_TYPE  = "image/webp";
        const QUALITY    = 0.9;
        const file       = el.files[0];
        const blobURL    = URL.createObjectURL(file);
        const img        = new Image();

        $('#thumbnail-spinner').removeClass('d-none');

        img.src    = blobURL;
        img.onload = function () {
            if(el.files[0].size < 2000000){
                fileThumbnail = el.files[0];
                document.querySelector('#preview-thumbnail').src = blobURL;
            }
            else{
                URL.revokeObjectURL(this.src);
                const [newWidth, newHeight] = calculateSize(img, MAX_WIDTH, MAX_HEIGHT);
                const canvas = document.createElement("canvas");
                canvas.width = newWidth;
                canvas.height = newHeight;
                const ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, newWidth, newHeight);
                canvas.toBlob(
                    (blob) => {
                        // Handle the compressed image. es. upload or save in local state
                    },
                    MIME_TYPE,
                    QUALITY
                );
        
                document.querySelector('#preview-thumbnail').src = canvas.toDataURL();
                fileThumbnail = dataURLtoFile(canvas.toDataURL(),'thumbnail.webp');
            }

            $('#thumbnail-spinner').addClass('d-none');
        }

        function calculateSize(img, maxWidth, maxHeight) {
            let width = img.width;
            let height = img.height;
          
            // calculate the width and height, constraining the proportions
            if (width > height) {
                if (width > maxWidth) {
                    height = Math.round((height * maxWidth) / width);
                    width = maxWidth;
                }
            } else {
                if (height > maxHeight) {
                    width = Math.round((width * maxHeight) / height);
                    height = maxHeight;
                }
            }
            return [width, height];
        }

        function dataURLtoFile(dataurl, filename) {
 
            var arr = dataurl.split(','),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]), 
                n = bstr.length, 
                u8arr = new Uint8Array(n);
                
            while(n--){
                u8arr[n] = bstr.charCodeAt(n);
            }
            
            return new File([u8arr], filename, {type:mime});
        }
    }
}