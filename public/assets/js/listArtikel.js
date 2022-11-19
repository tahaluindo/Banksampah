// add navbar shadow when scrolling
$(window).scroll(function () {
    var scroll = $(window).scrollTop();
    var box = $('nav').height();
    var header = $('#blog-single').height();

    if (scroll >= box - header) {
        $("nav").addClass("nav-shadow");
    } else {
        $("nav").removeClass("nav-shadow");
    }
});

/**
 * Get Kategori Artikel
 */
 axios.get(`${APIURL}/artikel/getkategori`)
 .then(res => {
     let elKategori = ''
     res.data.data.forEach(e => {
         elKategori += `<a class="dropdown-item py-3" href="${BASEURL}/homepage/${e.name}">${e.name}</a>`;
     });
 
     $('.dropdown-menu').html(elKategori);
 })
 .catch(err => {
 
 });

/**
* GET ALL ARTIKEL
*/
let arrayBerita = [];
axios.get(`${APIURL}/artikel/getartikel?kategori=${KATEGORI}&orderby=terbaru`)
    .then(res => {
        let elBerita  = '';
        let allBerita = res.data.data;
        arrayBerita   = allBerita;
        
        allBerita.forEach(b => {
            let date      = new Date(parseInt(b.published_at) * 1000);
            let day       = date.toLocaleString("en-US",{day: "numeric"});
            let month     = date.toLocaleString("en-US",{month: "long"});
            let year      = date.toLocaleString("en-US",{year: "numeric"});

            elBerita += `<div class="col-12 col-sm-6 col-md-4 mb-5">
                <a href="${BASEURL}/artikel/${b.slug}" class="card text-white card-has-bg click-col position-relative" style="min-height: 220px;border-radius: 10px;">
                
                    <img src="${b.thumbnail}" class="position-absolute" style="height:100%;width:100%;">

                    <div class="card-img-overlay d-flex flex-column">
                        <div class="card-body px-0">
                            <h6 class="card-meta mb-2">${b.kategori}</h6>
                            <h6 class="card-title mt-0" style="display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">
                                ${b.title}
                            </h6>
                        </div>
                        <div class="card-footer px-0">
                            <div class="media">
                                <div class="media-body">
                                <small><i class="far fa-clock"></i> ${month} ${day}, ${year}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>`;

            // elBerita += `<div class="col-12 col-sm-6 position-relative d-flex align-items-center">

            //     <img src="${BASEURL}/assets/images/default-thumbnail.webp" alt="" style="min-width:100%;max-width:100%; opacity:0;">
            //     <a href="${BASEURL}/artikel/${b.id}" class="card text-white card-has-bg click-col position-absolute" style="min-width:93%;max-width:93%;min-height: 220px;border-radius: 10px;">
                
            //         <img src="${b.thumbnail}" class="position-absolute" style="height:100%;width:100%;">

            //         <div class="card-img-overlay d-flex flex-column">
            //             <div class="card-body">
            //                 <h6 class="card-meta mb-2">${b.kategori}</h6>
            //                 <h6 class="card-title mt-0" style="display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">
            //                     ${b.title}
            //                 </h6>
            //             </div>
            //             <div class="card-footer">
            //                 <div class="media">
            //                     <div class="media-body">
            //                     <small><i class="far fa-clock"></i> ${month} ${day}, ${year}</small>
            //                     </div>
            //                 </div>
            //             </div>
            //         </div>
            //     </a>
            // </div>`;
        });
        
        $('#container-article').html(elBerita);
    })
    .catch(err => {
        if (err.response.status == 404){
            $('#container-article').addClass('d-none');
            $('#img-404').removeClass('d-none');
        }  
        else if (err.response.status == 500){
            $('#container-article').addClass('d-none');
            $('#img-404').removeClass('d-none');
            showAlert({
                message: `<strong>Ups...</strong> terjadi kesalahan pada server, silahkan refresh halaman.`,
                btnclose: true,
                type:'danger' 
            })
        }
    });