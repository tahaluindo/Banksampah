const confirmModalClose = (modalId) => {
    if (document.querySelector(`${modalId} #total-harga`).innerHTML == 0) {
        $(modalId).modal('hide');
        $(".modal-backdrop").remove();
        return 0;
    }

    Swal.fire({
        title: 'Data akan terhapus <br> lanjut keluar?',
        showDenyButton: true,
        confirmButtonText: 'yes',
        denyButtonText: `no`,
      }).then((result) => {
        if (result.isConfirmed) {
          $(modalId).modal('hide');
          $(".modal-backdrop").remove();
        } else if (result.isDenied) {
          Swal.close()
        }
      })
}

/**
 * GET TOTAL SAMPAH MASUK
 * =========================================================
 */
 const getSampahMasuk = async () => {

    let httpResponse = await httpRequestGet(`${APIURL}/transaksi/sampahmasuk?idnasabah=${IDNASABAH}`);

    if (httpResponse.status === 200) {
        let dataSampah = httpResponse.data.data;

        for (const key in dataSampah) {
            $(`#sampah-${key.replace(/\s/g,'-')}`).html(parseFloat(dataSampah[key].total).toFixed(1)+' Kg');
        } 
    }
};
getSampahMasuk();

// open modal detail sampah masuk
const openModalSampahMasuk = async (kategori) => {
    $('#modalDetailSampah .modal-title').html(`kategori ${kategori}`);
    
    $('#detil-sampah-spinner').removeClass('d-none');
    $('#detil-sampah-notfound').addClass('d-none')
    $('#modalDetailSampah #table-jenis-wraper').html(``);
    let httpResponse = await httpRequestGet(`${APIURL}/transaksi/sampahmasuk?kategori=${kategori}&idnasabah=${IDNASABAH}`);
    $('#detil-sampah-spinner').addClass('d-none');

    if (httpResponse.status === 404) {
        $('#detil-sampah-notfound').removeClass('d-none')
    }
    else if (httpResponse.status === 200) {
        let trBody     = '';
        let dataSampah = httpResponse.data.data;
        
        dataSampah.forEach((b,i) => {
            trBody  += `<tr class="text-center">
                <th scope="row">${++i}</th>
                <td>${b.jenis}</td>
                <td>${parseFloat(b.jumlah_kg).toFixed(2)} kg</td>
            </tr>`;
        })

        $('#modalDetailSampah #table-jenis-wraper').html(`<table class="table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">Jenis sampah</th>
                    <th scope="col">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                ${trBody}
            </tbody>
        </table>`);
    }

};

/**
 * FILTER TRANSACTION Section
 * =========================================================
 */

// modal filter transaksi when open
let openModalFilterT = (modalTitle) =>  {
    $('#formFilterTransaksi .modal-title').html(modalTitle);

    let modifTitle = modalTitle.toLowerCase().replace(' ','-');
    let dateStart  = $(`#btn-${modifTitle} #startdate`).html().split('/');
    let dateEnd    = $(`#btn-${modifTitle} #enddate`).html().split('/');

    $('#formFilterTransaksi input[name=date-start]').val(`${dateStart[2]}-${dateStart[1]}-${dateStart[0]}`);
    $('#formFilterTransaksi input[name=date-end]').val(`${dateEnd[2]}-${dateEnd[1]}-${dateEnd[0]}`);
}

// input date on change
$('#formFilterTransaksi input[type=date]').on('change',function (e) {
    let dateStart = $('#formFilterTransaksi input[name=date-start]').val();
    let dateEnd   = $('#formFilterTransaksi input[name=date-end]').val();

    if (dateStart && dateEnd) {
        $('#btn-filter-transaksi').attr('data-dismiss','modal');
        $('#btn-filter-transaksi').attr('onclick','filterTransaksi(this,event);');

        $('#formFilterTransaksi input[type=date]').removeClass('is-invalid');
    }
    else {
        $('#btn-filter-transaksi').removeAttr('data-dismiss');
        $('#btn-filter-transaksi').removeAttr('onclick');

        if (dateStart == '') {
            $('#formFilterTransaksi input[name=date-start]').addClass('is-invalid');
        }
        if (dateEnd == '') {
            $('#formFilterTransaksi input[name=date-end]').addClass('is-invalid');
        }
    }
})

// set current start and end DATE
let dateStartGrafik  = '';
let dateEndGrafik    = '';
let dateStartHistori = '';
let dateEndHistori   = '';
let setCurrentStartDate = (unixTime = null,updateGrafik = true) =>  {
    let currentUnixTime = '';
    
    if (unixTime) {
        currentUnixTime = new Date(unixTime);    
    } 
    else {
        currentUnixTime = new Date(new Date().getTime());
    }

    let currentDay   = currentUnixTime.toLocaleString("en-US",{day: "2-digit"});
    let currentMonth = currentUnixTime.toLocaleString("en-US",{month: "2-digit"});
    let currentYear  = currentUnixTime.toLocaleString("en-US",{year: "numeric"});

    let previousUnixTime = new Date(currentUnixTime.getTime()-(86400*30*1000));
    let previousDay   = previousUnixTime.toLocaleString("en-US",{day: "2-digit"});
    let previousMonth = previousUnixTime.toLocaleString("en-US",{month: "2-digit"});
    let previousYear  = previousUnixTime.toLocaleString("en-US",{year: "numeric"});

    if (updateGrafik) {
        dateStartGrafik = `${previousDay}-${previousMonth}-${previousYear}`;
        dateEndGrafik   = `${currentDay}-${currentMonth}-${currentYear}`;
        $('#btn-filter-grafik #startdate').html(`${previousDay}/${previousMonth}/${previousYear}`);
        $('#btn-filter-grafik #enddate').html(`${currentDay}/${currentMonth}/${currentYear}`);
    }

    dateStartHistori = `${previousDay}-${previousMonth}-${previousYear}`;
    dateEndHistori   = `${currentDay}-${currentMonth}-${currentYear}`;
    $('#btn-filter-histori #startdate').html(`${previousDay}/${previousMonth}/${previousYear}`);
    $('#btn-filter-histori #enddate').html(`${currentDay}/${currentMonth}/${currentYear}`);
}

setCurrentStartDate();

// do filter transaksi
const filterTransaksi = async (e) => {
    let formFilter = new FormData(e.parentElement.parentElement.parentElement);
    let startDate  = formFilter.get('date-start').split('-');
    let endDate    = formFilter.get('date-end').split('-');

    let unixStart  = new Date(`${startDate[0]}/${startDate[1]}/${startDate[2]} 00:00:01`).getTime();
    let unixEnd    = new Date(`${endDate[0]}/${endDate[1]}/${endDate[2]} 23:00:00`).getTime();

    if (parseInt(unixEnd) - parseInt(unixStart) > 2674799000) {
        showAlert({
            message: `maksimal 31 hari`,
            autohide: true,
            type:'danger'
        });

        return 0;
    }

    if ($('#formFilterTransaksi .modal-title').html() == 'Filter Histori') {
        dateStartHistori = `${startDate[2]}-${startDate[1]}-${startDate[0]}`;
        dateEndHistori   = `${endDate[2]}-${endDate[1]}-${endDate[0]}`;
        $('#btn-filter-histori #startdate').html(`${startDate[2]}/${startDate[1]}/${startDate[0]}`);
        $('#btn-filter-histori #enddate').html(`${endDate[2]}/${endDate[1]}/${endDate[0]}`);
        getHistoriTransaksi();
    } 
    else {
        dateStartGrafik = `${startDate[2]}-${startDate[1]}-${startDate[0]}`;
        dateEndGrafik   = `${endDate[2]}-${endDate[1]}-${endDate[0]}`;
        $('#btn-filter-grafik #startdate').html(`${startDate[2]}/${startDate[1]}/${startDate[0]}`);
        $('#btn-filter-grafik #enddate').html(`${endDate[2]}/${endDate[1]}/${endDate[0]}`);
        // getDataGrafikSetor();
    }
};

/**
 * UPDATE GRAFIK SETOR
 * ========================================
 */
let chartGrafik = '';
let grafikSetorYear = new Date().getFullYear();
let grafikSetorUrl  = `${APIURL}/transaksi/grafikssampah?year=${grafikSetorYear}&tampilan=per-bulan&idnasabah=${IDNASABAH}`;

const getDataGrafikSetor = async () => {
    $('#spinner-wraper-grafik').removeClass('d-none');
    let httpResponse = await httpRequestGet(grafikSetorUrl);
    $('#spinner-wraper-grafik').addClass('d-none'); 

    let chartType = 'line';
    let arrayX = [""];
    let arrayY = [0];
    
    if (httpResponse.status === 200) {
        let allTransaksi = httpResponse.data.data;
        
        for (const key in allTransaksi) {
            arrayX.push(key);
            arrayY.push(allTransaksi[key].totSampahMasuk);
        }
    }

    if (chartGrafik != '') {
        chartGrafik.destroy();
    }

    var ctx2 = document.getElementById("chart-line").getContext("2d");
    document.querySelector("#chart-line").style.width    = '100%';
    document.querySelector("#chart-line").style.height   = '300px';
    document.querySelector("#chart-line").style.maxHeight= '300px';

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);
    gradientStroke1.addColorStop(1, 'rgba(193,217,102,0.2)');

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);
    gradientStroke2.addColorStop(1, 'rgba(193,217,102,0.2)');

    for (let i = arrayX.length; i <10; i++) {
        arrayX.push(' ');
    }

    if (chartType == 'line') {
        if (arrayY.length == 1) {
            arrayY = [];
            arrayX.unshift('');
        }
    }

    chartGrafik = new Chart(ctx2, {
        type: chartType,
        data: {
            labels: arrayX,
            datasets: [
                {
                    label: "Kg",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#c1d966",
                    borderWidth: 3,
                    backgroundColor: gradientStroke1,
                    fill: true,
                    data: arrayY,
                    maxBarThickness: 6,
                    minBarLength: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            scales: {
            y: {
                grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5]
                },
                ticks: {
                    display: true,
                    padding: 10,
                    color: '#b2b9bf',
                    beginAtZero: true,
                    font: {
                        size: 11,
                        family: "Open Sans",
                        style: 'normal',
                        lineHeight: 2
                    },
                }
            },
            x: {
                grid: {
                    drawBorder: false,
                    display: false,
                    drawOnChartArea: false,
                    drawTicks: false,
                    borderDash: [5, 5]
                },
                ticks: {
                    display: true,
                    color: '#b2b9bf',
                    padding: 0,
                    font: {
                        size: 11,
                        family: "Open Sans",
                        style: 'normal',
                        lineHeight: 2
                    },
                }
            },
            },
        },
    });
};
getDataGrafikSetor();

$("select[name=tahun-grafik-setor]").on('input', function () {
    grafikSetorYear = $(this).val();
    grafikSetorUrl  = `${APIURL}/transaksi/grafikssampah?year=${grafikSetorYear}&tampilan=per-bulan&idnasabah=${IDNASABAH}`;
    getDataGrafikSetor();
}) 

/**
 * GET ALL TRANSAKSI NASABAH
 * ========================================
 */
const getHistoriTransaksi = async () => {
    $('#spinner-wraper-histori').removeClass('d-none');
    $('#transaksi-wraper-histori').addClass('d-none');
    let httpResponse = await httpRequestGet(`${APIURL}/transaksi/getdata?idnasabah=${IDNASABAH}&orderby=terbaru&start=${dateStartHistori}&end=${dateEndHistori}`);
    $('#spinner-wraper-histori').addClass('d-none'); 
    $('#transaksi-wraper-histori').removeClass('d-none');
    
    if (httpResponse.status === 404) {
        $('#transaksi-wraper-histori').html(`<h6 class='opacity-6'>belum ada transaksi</h6>`); 
    }
    else if (httpResponse.status === 200) {
        let elTransaksi  = '';
        let allTransaksi = httpResponse.data.data;
        
        allTransaksi.forEach(t => {
            let textClass      = '';
            let totalTransaksi = '';
            let jenisTransaksi = t.jenis_transaksi;
            let jenisSaldo     = t.jenis_saldo;
            let date      = new Date(parseInt(t.date) * 1000);
            let day       = date.toLocaleString("en-US",{day: "numeric"});
            let month     = date.toLocaleString("en-US",{month: "long"});
            let year      = date.toLocaleString("en-US",{year: "numeric"});

            // const zeroPad = (num, places) => String(num).padStart(places, '0');
            // const xMonth  = zeroPad(date.toLocaleString("en-US",{month: "numeric"}), 2);
            // const yMonth  = dateFilter.split('-');
            
            if (jenisTransaksi == 'penyetoran sampah') {
                textClass      = 'text-success';
                totalTransaksi = '+ Rp'+modifUang(t[`total_uang_setor`]);
                
                // arrayId.push(t.id_transaksi);
                // arrayKg.push(t.total_kg);
            } 
            else if (jenisTransaksi == 'konversi saldo') {
                textClass      = 'text-warning';
                totalTransaksi = 'Rp'+modifUang(kFormatter(t[`total_pindah`]))+' <i class="fas fa-exchange-alt"></i> '+parseFloat(t[`hasil_konversi`]).toFixed(4)+'g';
            }
            else {
                textClass = 'text-danger';
                if (jenisSaldo == 'uang') {
                    totalTransaksi = '- Rp'+modifUang(parseFloat(t[`total_tarik`]).toFixed(0));
                } 
                else {
                    totalTransaksi = '- '+parseFloat(t[`total_tarik`]).toFixed(4)+'g';
                }
            }

            elTransaksi  += `<li class="list-group-item border-0 ps-0 border-radius-lg">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-column">
                        <h6 class="mb-1 text-dark font-weight-bold text-sm">${month}, ${day}, ${year}</h6>
                        <span class="text-xs">ID: ${t.id_transaksi}</span>
                        <span class="${textClass} mt-2">${totalTransaksi}</span>
                    </div>
                    <div class="d-flex align-items-center text-sm">
                        <a href='' class="btn btn-link text-dark text-sm mb-0 p-2 text-sm bg-info border-radius-sm" data-toggle="modal" data-target="#modalPrintTransaksi" onclick="getDetailTransaksiNasabah('${t.id_transaksi}');">
                            <i class="fas fa-file-pdf text-xs text-white"></i>
                        </a>
                        <a href='' class="btn btn-link text-dark text-sm mb-0 p-2 ml-1 text-sm bg-warning border-radius-sm" data-toggle="modal" data-target="#modalEditSetor" data-backdrop="static" data-keyboard="false" onclick="openModalEditSetor('${t.id_transaksi}',event);">
                            <i class="fas fa-edit text-xs text-white"></i>
                        </a>
                        <a href='' class="btn btn-link text-dark text-sm mb-0 p-2 ml-1 text-sm bg-danger border-radius-sm" onclick="deleteTransaksiNasabah('${t.id_transaksi}',event);">
                            <i class="fas fa-trash text-xs text-white"></i>
                        </a>
                    </div>
                </div>
                <hr class="horizontal dark mt-2">
            </li>`;
        });

        // getDataGrafikSetor(arrayId,arrayKg);
        $('#transaksi-wraper-histori').html(`<ul class="list-group h-100 w-100" style="font-family: 'qc-medium';">
            ${elTransaksi}
        </ul>`);
    }
};
getHistoriTransaksi();

/**
 * GET DETAIL TRANSAKSI
 * ==============================================
 */
const getDetailTransaksiNasabah = async (id) => {
    $('#detil-transaksi-body').html(' ');
    $('#detil-transaksi-spinner').removeClass('d-none');
    let httpResponse = await httpRequestGet(`${APIURL}/transaksi/getdata?id_transaksi=${id}`);
    
    if (httpResponse.status === 200) {
        $('#detil-transaksi-spinner').addClass('d-none');
        let date  = new Date(parseInt(httpResponse.data.data.date) * 1000);
        let day   = date.toLocaleString("en-US",{day: "numeric"});
        let month = date.toLocaleString("en-US",{month: "numeric"});
        let year  = date.toLocaleString("en-US",{year: "numeric"});
        let time  = date.toLocaleString("en-US",{hour: '2-digit', minute: '2-digit',second: '2-digit'});
        
        $('#detil-transaksi-date').html(`${day}/${month}/${year}&nbsp;&nbsp;&nbsp;${time}`);
        $('#detil-transaksi-nama').html(httpResponse.data.data.nama_lengkap);
        $('#detil-transaksi-idnasabah').html(httpResponse.data.data.id_user);
        $('#detil-transaksi-idtransaksi').html(httpResponse.data.data.id_transaksi);
        $('#detil-transaksi-type').html(httpResponse.data.data.jenis_transaksi);
        $('#btn-cetak-transaksi').attr('href',`${BASEURL}/transaksi/cetaktransaksi/${httpResponse.data.data.id_transaksi}`);

        // tarik saldo
        if (httpResponse.data.data.jenis_transaksi == 'penarikan saldo') {
            let jenisSaldo = httpResponse.data.data.jenis_saldo;
            let jumlah     = (jenisSaldo == 'uang')?'Rp '+modifUang(parseFloat(httpResponse.data.data.jumlah_tarik).toFixed(0)):parseFloat(httpResponse.data.data.jumlah_tarik).toFixed(4)+' gram';

            $('#detil-transaksi-body').html(`<div class="p-4 bg-secondary border-radius-sm">
                <table>
                    <tr class="text-dark">
                        <td><h4>Jenis saldo&nbsp;</h4></td>
                        <td>
                            <h4>
                            : &nbsp;&nbsp;${(jenisSaldo == 'uang') ? jenisSaldo : 'emas '+jenisSaldo}
                            </h4>
                        </td>
                    </tr>
                    <tr class="text-dark">
                        <td><h4>Jumlah</h4></td>
                        <td><h4>: &nbsp;&nbsp;${jumlah}</h4></td>
                    </tr>
                </table>
            </div>`);
        }
        // konversi saldo
        if (httpResponse.data.data.jenis_transaksi == 'konversi saldo') {
            $('#detil-transaksi-body').html(`<div class="p-4 bg-secondary border-radius-sm">
            <table>
                <tr class="text-dark">
                    <td>Jumlah</td>
                    <td>: &nbsp;&nbsp;Rp ${modifUang(httpResponse.data.data.jumlah)}</td>
                </tr>
                <tr class="text-dark">
                    <td>Harga emas</td>
                    <td>: &nbsp;&nbsp;Rp ${modifUang(httpResponse.data.data.harga_emas)}</td>
                </tr>
                <tr class="text-dark">
                    <td>Hasil konversi&nbsp;</td>
                    <td>
                        : &nbsp;&nbsp;${parseFloat(httpResponse.data.data.hasil_konversi).toFixed(4)} g
                    </td>
                </tr>
            </table>
            </div>`);
        }
        // setor sampah
        if (httpResponse.data.data.jenis_transaksi == 'penyetoran sampah') {
            let totalRp= 0;
            let totalKg= 0;
            let trBody = '';
            let barang = httpResponse.data.data.barang;
            barang.forEach((b,i) => {
                totalRp += parseFloat(b.jumlah_rp);
                totalKg += parseFloat(b.jumlah_kg);
                trBody  += `<tr class="text-center">
                    <th scope="row">${++i}</th>
                    <td>${b.jenis}</td>
                    <td>${parseFloat(b.jumlah_kg).toFixed(2)} kg</td>
                    <td class="text-right">${modifUang(b.jumlah_rp)}</td>
                </tr>`;
            })

            $('#detil-transaksi-body').html(`<table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Jenis sampah</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    ${trBody}
                    <tr>
                        <th class="text-center" colspan='2'>Total</th>
                        <td class="text-center">${parseFloat(totalKg).toFixed(2)}</td>
                        <td class="text-center">${modifUang(totalRp.toString())}</td>
                    </tr>
                </tbody>
            </table>`);
        }
    }
};

/**
 * GET SALDO NASABAH
 * ==============================================
 */
let dataSaldo = "";
const getSaldoNasabah = async () => {

    $('#formPindahSaldo #maximal-saldo').html(`_ _ _ _`);
    $('#saldo-uang').html('_ _');
    $('#saldo-emas').html('_ _');

    let httpResponse = await httpRequestGet(`${APIURL}/transaksi/getsaldo?idnasabah=${IDNASABAH}`);
    
    if (httpResponse.status === 200) {
        dataSaldo = httpResponse.data.data

        $('#formPindahSaldo #maximal-saldo').html(`${modifUang(dataSaldo.uang)}`);
        $('#saldo-uang').html(modifUang(dataSaldo.uang));
        $('#saldo-emas').html(parseFloat(dataSaldo.emas || 0).toFixed(4));
    }
};
getSaldoNasabah();

/**
 * GET DATA PROFILE NASABAH
 * ==============================================
 */
const getDataProfileNasabah = async () => {

    let httpResponse = await httpRequestGet(`${APIURL}/admin/getnasabah?id=${IDNASABAH}`);
    
    if (httpResponse.status == 404) {
        Swal.fire({
            icon : 'error',
            title : '<strong>NOT FOUND</strong>',
            text: `nasabah dengan id ${IDNASABAH} tidak ditemukan!`,
            showCancelButton: false,
            confirmButtonText: 'ok',
        }).then(() => {
            window.location.replace(`${BASEURL}/admin/listnasabah`);
        })
    }
    else if (httpResponse.status === 200) {
        let dataNasabah = httpResponse.data.data[0];
        let date        = new Date(parseInt(dataNasabah.created_at) * 1000);

        $('#navbar-namalengkap').html(dataNasabah.nama_lengkap);

        // -- nasabah card --
        $('#card-id').html(`${dataNasabah.id.slice(0, 5)}&nbsp;&nbsp;&nbsp;${dataNasabah.id.slice(5, 9)}&nbsp;&nbsp;&nbsp;${dataNasabah.id.slice(9,99999999)}`);
        $('#card-username').html(dataNasabah.username);
        $('#card-date').html(`${date.toLocaleString("en-US",{day: "numeric"})}/${date.toLocaleString("en-US",{month: "numeric"})}/${date.toLocaleString("en-US",{year: "numeric"})}`);

        // -- saldo --
        // $('#saldo-uang').html(modifUang(dataNasabah.saldo_uang));
        // $('#saldo-ubs').html(parseFloat(dataNasabah.saldo_ubs).toFixed(4));
        // $('#saldo-antam').html(parseFloat(dataNasabah.saldo_antam).toFixed(4));
        // $('#saldo-galery24').html(parseFloat(dataNasabah.saldo_galery24).toFixed(4));

        // -- personal info --
        $('#personal-info #email').html(dataNasabah.email);
        $('#personal-info #nama-lengkap').html(dataNasabah.nama_lengkap);
        $('#personal-info #username').html(dataNasabah.username);
        $('#personal-info #tgl-lahir').html(dataNasabah.tgl_lahir);
        $('#personal-info #kelamin').html(dataNasabah.kelamin);
        $('#personal-info #alamat').html(dataNasabah.alamat);
        $('#personal-info #notelp').html(dataNasabah.notelp);
        $('#personal-info #nik').html(dataNasabah.nik);
    }
};
getDataProfileNasabah();

/**
 * Get default date AND time
 */
function getCurrentDate() {
    let currentUnixTime = new Date(new Date().getTime());
    let currentDay   = currentUnixTime.toLocaleString("en-US",{day: "2-digit"});
    let currentMonth = currentUnixTime.toLocaleString("en-US",{month: "2-digit"});
    let currentYear  = currentUnixTime.toLocaleString("en-US",{year: "numeric"});
    
    return `${currentYear}-${currentMonth}-${currentDay}`;
}

function getCurrentTime() {
    let currentUnixTime = new Date(new Date().getTime());
    
    return currentUnixTime.toLocaleString("en-US",{hour12: false,hour: '2-digit', minute: '2-digit'});
}

/**
 * Edit modal when open
 */
const openModalTransaksi = (modalTitle) => {
    $('.form-check-input').removeClass('is-invalid');
    $('.form-check-input').prop('checked',false);
    $('.form-control').removeClass('is-invalid');
    $('.text-danger').html('');
    $(`.form-control`).val('');
    $('#formTarikSaldo #maximal-saldo').html('');
    
    if (modalTitle == 'setor sampah') {
        $('#formSetorSampah .barisSetorSampah').remove();
        tambahBaris();
        countTotalHarga();
    }
    else if (modalTitle == 'pindah saldo') {
        $('#formPindahSaldo #maximal-saldo').html(`${modifUang(dataSaldo.uang)}`);
    }

    $(`.modalTransaksi input[type=date]`).val(getCurrentDate());
    $(`.modalTransaksi input[type=time]`).val(getCurrentTime());
}

/**
 * TRANSAKSI SETOR SAMPAH
 * =============================================
 */

// GET ALL JENIS SAMPAH
let arrayJenisSampah = [];
const getAllJenisSampah = async () => {
    let httpResponse = await httpRequestGet(`${APIURL}/sampah/getsampah`);

    if (httpResponse.status === 200) {
        arrayJenisSampah = httpResponse.data.data;
    }

    tambahBaris();
};
getAllJenisSampah();

// tambah baris
const tambahBaris = (event = false) => {
    if (event) {
        event.preventDefault();
    }

    let tmpKatSampah = [];
    let elKatSampah  = `<option data-kategori="" selected>-- kategori sampah  --</option>`;

    if (arrayJenisSampah.length !== 0) {
        arrayJenisSampah.forEach(s=> {
            if (!tmpKatSampah.includes(s.kategori)) {
                tmpKatSampah.push(s.kategori)
                elKatSampah += `<option data-kategori="${s.kategori}">${s.kategori}</option>`;
            }
        });
    }

    let totalBaris = $('.barisSetorSampah').size();
    let elementRow = ` <td class="py-2" style="border-right: 0.5px solid #E9ECEF;">
        <span class="w-100 btn btn-danger d-flex justify-content-center align-items-center border-radius-sm" style="height: 38px;margin: 0;" onclick="hapusBaris(this);">
            <i class="fas fa-times text-white"></i>
        </span>
    </td>
    <td class="py-2" style="border-right: 0.5px solid #E9ECEF;">
        <span class="nomor w-100 d-flex justify-content-center align-items-center border-radius-sm" style="height: 38px;margin: 0;">
            ${$('.barisSetorSampah').size()+1}
        </span>
    </td>
    <td class="py-2" style="border-right: 0.5px solid #E9ECEF;">
        <select id="kategori-berita-wraper" class="inputJenisSampah form-control form-control-sm py-1 pl-2 border-radius-sm" style="min-height: 38px" onchange="insertJenisSampah(this,event);">
            ${elKatSampah}
        </select>
    </td>
    <td class="py-2" style="border-right: 0.5px solid #E9ECEF;">
        <select id="kategori-berita-wraper" class="inputJenisSampah form-control form-control-sm py-1 pl-2 border-radius-sm" name="transaksi[slot${totalBaris+1}][id_sampah]" style="min-height: 38px" onchange="getHargaInOption(this,event);" disabled>
            <option>-- jenis sampah  --</option>
        </select>
    </td>
    <td class="py-2" style="border-right: 0.5px solid #E9ECEF;">
        <input type="text" class="inputJumlahSampah form-control form-control-sm pl-2 border-radius-sm" value="0" name="transaksi[slot${totalBaris+1}][jumlah]" style="min-height: 38px" onkeyup="countHargaXjumlah(this);" autocomplete="off">
    </td>
    <td class="py-2">
        <input type="text" class="inputHargaSampah form-control form-control-sm pl-2 border-radius-sm" style="min-height: 38px" data-harga="0" value="0" disabled>
    </td>`

    let tr = document.createElement('tr');
    tr.classList.add('barisSetorSampah');
    
    tr.innerHTML=elementRow;
    document.querySelector('#table-setor-sampah tbody').insertBefore(tr,document.querySelector('#formSetorSampah #special-tr'));
}

// tambah baris
const hapusBaris = (el) => {
    el.parentElement.parentElement.remove();
    
    $('#formSetorSampah .barisSetorSampah').each(function (e,i) {
        i.querySelector('.nomor').innerHTML = e+1;
    })
    
    countTotalHarga();
}

// kategori sampah on change
const insertJenisSampah = (el,event) =>{
    var kategori      = event.target.options[event.target.selectedIndex].dataset.kategori;
    let eljenisSampah = `<option value='' data-harga='' selected>-- jenis sampah --</option>`;
    
    let arrayJenisSampahSorted = arrayJenisSampah.sort((a, b) => a.jenis.localeCompare(b.jenis));

    arrayJenisSampahSorted.forEach(s=> {
        if (s.kategori == kategori) {
            eljenisSampah += `<option value='${s.id}' data-harga='${s.harga}'>${s.jenis}</option>`;
        }
    });

    let elInputJenisSampah = el.parentElement.nextElementSibling.children[0];
    let elInputJumlah      = el.parentElement.nextElementSibling.nextElementSibling.children[0];
    let elInputHarga       = el.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.children[0];
    elInputJenisSampah.innerHTML = eljenisSampah;
    elInputJumlah.value          = 0;
    elInputHarga.value           = 0;
    elInputHarga.setAttribute('data-harga',0);
    countTotalHarga();
    countTotalHargaEditSetor();

    if (kategori != '') {
        elInputJenisSampah.removeAttribute('disabled');
    } 
    else {
        elInputJenisSampah.setAttribute('disabled',true);
    }
};

// jenis sampah on change
const getHargaInOption = (el,event) =>{
    var harga = event.target.options[event.target.selectedIndex].dataset.harga;

    let elInputJumlah   = el.parentElement.nextElementSibling.children[0];
    elInputJumlah.value = 1;

    let elInputHarga   = el.parentElement.nextElementSibling.nextElementSibling.children[0];
    // elInputHarga.value = modifUang(harga);
    elInputHarga.value = harga;
    elInputHarga.setAttribute('data-harga',harga);

    countTotalHarga();
};

// count harga*jumlah
const countHargaXjumlah = (el) =>{
    var jumlahKg = el.value;
    let elInputJenis = el.parentElement.previousElementSibling.children[0];
    
    if (elInputJenis.value !== '') {
        if (jumlahKg !== '') {
            let elInputHarga = el.parentElement.nextElementSibling.children[0];
            let harga        = elInputHarga.getAttribute('data-harga');
            
            elInputHarga.value = parseFloat(parseFloat(jumlahKg)*parseInt(harga)).toFixed(2);
            countTotalHarga();
        }
    }
};

// count total harga sampah
const countTotalHarga = () =>{
    let total = 0;
    $(`#formSetorSampah .inputHargaSampah`).each(function() {
        total = total + parseFloat($(this).val());
    });

    $('#formSetorSampah #special-tr #total-harga').html(modifUang(total.toString()));
};

// Validate Setor sampah
const validateSetorSampah = () => {
    let status = true;
    let msg    = '';
    $('#formSetorSampah .form-control').removeClass('is-invalid');
    $('#formSetorSampah .text-danger').html('');

    // tgl transaksi
    if ($('#formSetorSampah #date').val() == '') {
        $('#formSetorSampah #date').addClass('is-invalid');
        $('#formSetorSampah #date-error').html('*waktu harus di isi');
        status = false;
    }
    // waktu transaksi
    if ($('#formSetorSampah #time').val() == '') {
        $('#formSetorSampah #time').addClass('is-invalid');
        $('#formSetorSampah #date-error').html('*waktu harus di isi');
        status = false;
    }

    // jenis sampah
    $(`#formSetorSampah .inputJenisSampah`).each(function() {
        if ($(this).val() == '' || $(this).attr('disabled')) {
            $(this).addClass('is-invalid');
            status = false;
            msg    = 'input tidak boleh kosong!';
        }
    });
    // jumlah sampah
    $(`#formSetorSampah .inputJumlahSampah`).each(function() {
        if ($(this).val() == '') {
            $(this).addClass('is-invalid');
            status = false;
            msg    = 'input tidak boleh kosong!';
        }
        if (/[^0-9\.]/g.test($(this).val().replace(/ /g,''))) {
            $(this).addClass('is-invalid');
            status = false;
            msg    = 'jumlah hanya boleh berupa angka positif dan titik!';
        }
    });

    if (status == false && msg !== "") {
        showAlert({
            message: `<strong>${msg}</strong>`,
            autohide: true,
            type:'danger'
        });
    }

    return status;
}

/**
 * TRANSAKSI PINDAH SALDO
 * =============================================
 */

// Validate Pindah Saldo
const validatePindahSaldo = () => {
    let status = true;

    $('#formPindahSaldo .form-check-input').removeClass('is-invalid');
    $('#formPindahSaldo .form-control').removeClass('is-invalid');
    $('#formPindahSaldo .text-danger').html('');

    // tgl transaksi
    if ($('#formPindahSaldo #date').val() == '') {
        $('#formPindahSaldo #date').addClass('is-invalid');
        $('#formPindahSaldo #date-error').html('*tanggal harus di isi');
        status = false;
    }
    // waktu transaksi
    if ($('#formPindahSaldo #time').val() == '') {
        $('#formPindahSaldo #time').addClass('is-invalid');
        $('#formPindahSaldo #date-error').html('*waktu harus di isi');
        status = false;
    }
    // harga emas
    if ($('#formPindahSaldo #harga_emas').val() == '') {
        $('#formPindahSaldo #harga_emas').addClass('is-invalid');
        $('#formPindahSaldo #harga_emas-error').html('*harga emas harus di isi');
        status = false;
    }
    else if (/[^0-9\.]/g.test($('#formPindahSaldo #harga_emas').val().replace(/ /g,''))) {
        $('#formPindahSaldo #harga_emas').addClass('is-invalid');
        $('#formPindahSaldo #harga_emas-error').html('*hanya boleh berupa angka positif dan titik!');
        status = false;
    }
    // jumlah pindah
    if ($('#formPindahSaldo #jumlah').val() == '') {
        $('#formPindahSaldo #jumlah').addClass('is-invalid');
        $('#formPindahSaldo #jumlah-error').html('*jumlah saldo harus di isi');
        status = false;
    }
    else if (/[^0-9\.]/g.test($('#formPindahSaldo #jumlah').val().replace(/ /g,''))) {
        $('#formPindahSaldo #jumlah').addClass('is-invalid');
        $('#formPindahSaldo #jumlah-error').html('*hanya boleh berupa angka positif dan titik!');
        status = false;
    }
    else if (parseFloat($('#formPindahSaldo #jumlah').val()) < 10000) {
        $('#formPindahSaldo #jumlah').addClass('is-invalid');
        $('#formPindahSaldo #jumlah-error').html('*minimal Rp.10,000');
        status = false;
    }

    return status;
}

/**
 * TRANSAKSI TARIK SALDO
 * =============================================
 */
// jenis saldo on click
$('#formTarikSaldo input[name=jenis_saldo]').on('click', function() {
    if ($(this).attr('value') == "uang") {
        $('#formTarikSaldo #maximal-saldo').html(`Rp ${modifUang(dataSaldo.uang)}`);
        $('#formTarikSaldo #jenis-emas').attr(`disabled`,true);
        $('#formTarikSaldo #jenis-emas').val('');
    } 
    else {
        $('#formTarikSaldo #maximal-saldo').html(`${dataSaldo.emas}`);
        $('#formTarikSaldo #jenis-emas').removeAttr(`disabled`);
    }
})

// Validate Tarik Saldo
const validateTarikSaldo = () => {
    let status = true;
    let form   = new FormData(document.querySelector('#formTarikSaldo'));

    $('#formTarikSaldo .form-check-input').removeClass('is-invalid');
    $('#formTarikSaldo .form-control').removeClass('is-invalid');
    $('#formTarikSaldo .text-danger').html('');

    // tgl transaksi
    if ($('#formTarikSaldo #date').val() == '') {
        $('#formTarikSaldo #date').addClass('is-invalid');
        $('#formTarikSaldo #date-error').html('*tanggal harus di isi');
        status = false;
    }
    // waktu transaksi
    if ($('#formTarikSaldo #time').val() == '') {
        $('#formTarikSaldo #time').addClass('is-invalid');
        $('#formTarikSaldo #date-error').html('*waktu harus di isi');
        status = false;
    }
    // jenis saldo
    if (form.get('jenis_saldo') == null) {
        $('#formTarikSaldo .form-check-input').addClass('is-invalid');
        status = false;
    }
    if (form.get('jenis_saldo') == 'emas') {
        if (form.get('jenis_emas') == '') {
            $('#formTarikSaldo #jenis-emas').addClass('is-invalid');
            status = false;
        }
    }
    // jumlah pindah
    if ($('#formTarikSaldo #jumlah').val() == '') {
        $('#formTarikSaldo #jumlah').addClass('is-invalid');
        $('#formTarikSaldo #jumlah-error').html('*jumlah saldo harus di isi');
        status = false;
    }
    else if (/[^0-9\.]/g.test($('#formTarikSaldo #jumlah').val().replace(/ /g,''))) {
        $('#formTarikSaldo #jumlah').addClass('is-invalid');
        $('#formTarikSaldo #jumlah-error').html('*hanya boleh berupa angka positif dan titik!');
        status = false;
    }

    return status;
}

/**
 * SEND TRANSAKSI SETOR TARIK KONVERSI
 * =============================================
 */
const doTransaksi = async (el,event,method) => {
    event.preventDefault();
    let validate  = '';
    let elForm    = el.parentElement.parentElement.parentElement;
    let transaksiName = ''

    if (method == 'setorsampah') {
        validate = validateSetorSampah;
        transaksiName = 'setor sampah';
    }
    else if (method == 'pindahsaldo') {
        validate = validatePindahSaldo;
        transaksiName = 'pindah saldo';
    }
    else if (method == 'tariksaldo') {
        validate = validateTarikSaldo;
        transaksiName = 'tarik saldo';
    }

    if (validate()) {
        let form           = new FormData(elForm);
        let tglTransaksi   = form.get('date').split('-');
        let waktuTransaksi = form.get('time');
        form.set('date',`${tglTransaksi[2]}-${tglTransaksi[1]}-${tglTransaksi[0]} ${waktuTransaksi}`);

        if (method == 'tariksaldo') {
            if ($('#formTarikSaldo #jenis-emas').val() !== '') {
                form.set('jenis_saldo',$('#formTarikSaldo #jenis-emas').val());
            }
        } 

        showLoadingSpinner();
        httpResponse = await httpRequestPost(`${APIURL}/transaksi/${method}`,form);    
        hideLoadingSpinner();

        if (httpResponse.status === 201) {
            $(`.form-control`).val('');
            $('.form-check-input').prop('checked',false);
            $(`input[type=date]`).val(getCurrentDate());
            $(`input[type=time]`).val(getCurrentTime());
            updateTableAndGrafik(`${tglTransaksi[0]}/${tglTransaksi[1]}/31`,method);
            getSaldoNasabah();
            
            if (method == 'setorsampah') {
                $('.barisSetorSampah').remove();
                tambahBaris();
                countTotalHarga();
                getSampahMasuk();
            } 
            else if(method == 'tariksaldo'){
                $('#formTarikSaldo #maximal-saldo').html(``);
                $('#formTarikSaldo #jenis-emas').attr(`disabled`,true);
                $('#formTarikSaldo #jenis-emas').val('');
            }

            showAlert({
                message: `<strong>Success...</strong> ${transaksiName} berhasil!`,
                autohide: true,
                type:'success'
            })
        }
        else if (httpResponse.status === 400) {
            if (httpResponse.message.jumlah) {
                if (method == 'pindahsaldo') {
                    $('#formPindahSaldo #jumlah').addClass('is-invalid');
                    $('#formPindahSaldo #jumlah-error').html(`*${httpResponse.message.jumlah}`);
                } 
                else {
                    $('#formTarikSaldo #jumlah').addClass('is-invalid');
                    $('#formTarikSaldo #jumlah-error').html(`*${httpResponse.message.jumlah}`);
                }
            }
        }

        // Swal.fire({
        //     input: 'password',
        //     inputAttributes: {
        //         autocapitalize: 'off'
        //     },
        //     html:`<h5 class='mb-4'>Password</h5>`,
        //     showCancelButton: true,
        //     confirmButtonText: 'submit',
        //     showLoaderOnConfirm: true,
        //     preConfirm: (password) => {
        //         let form = new FormData();
        //         form.append('hashedpass',PASSADMIN);
        //         form.append('password',password);
    
        //         return axios
        //         .post(`${APIURL}/admin/confirmdelete`,form, {
        //             headers: {
        //                 // header options 
        //             }
        //         })
        //         .then((response) => {
        //             doTransaksiInner();
        //         })
        //         .catch(error => {
        //             if (error.response.status == 404) {
        //                 Swal.showValidationMessage(
        //                     `password salah`
        //                 )
        //             }
        //             else if (error.response.status == 500) {
        //                 Swal.showValidationMessage(
        //                     `terjadi kesalahan, coba sekali lagi`
        //                 )
        //             }
        //         })
        //     },
        //     allowOutsideClick: () => !Swal.isLoading()
        // })
    }
}

/**
 * Edit Setor Sampah
 * =============================================
 */
const openModalEditSetor = async (id) => {
    $('#formEditSetor .form-check-input').removeClass('is-invalid');
    $('#formEditSetor .form-check-input').prop('checked',false);
    $('#formEditSetor .form-control').removeClass('is-invalid');
    $('#formEditSetor .text-danger').html('');
    $(`#formEditSetor .form-control`).val('');
    $('#formEditSetor .barisEditSetor').remove();

    $('#formEditSetor #spinner').removeClass('d-none');
    let httpResponse = await httpRequestGet(`${APIURL}/transaksi/getdata?id_transaksi=${id}`);
    
    if (httpResponse.status === 200) {
        $('#formEditSetor #spinner').addClass('d-none');

        let date  = new Date(parseInt(httpResponse.data.data.date) * 1000);
        let day   = date.toLocaleString("en-US",{day: "2-digit"});
        let month = date.toLocaleString("en-US",{month: "2-digit"});
        let year  = date.toLocaleString("en-US",{year: "numeric"});
        let time  = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit',hour12: false });
        let barang= httpResponse.data.data.barang;
        console.log(`${year}-${month}-${day}`);
        
        $('#formEditSetor input[name=id_nasabah]').val(httpResponse.data.data.id_user);
        $('#formEditSetor input[name=id_transaksi]').val(httpResponse.data.data.id_transaksi);
        $('#formEditSetor input[name=date]').val(`${year}-${month}-${day}`);
        $('#formEditSetor input[name=time]').val(time);

        barang.forEach(b => {
            tambahBarisEditSetor(false,b);
        })

        countTotalHargaEditSetor();
    }
};

// hapus baris edit setor
const hapusBarisEditSetor = (el) => {
    el.parentElement.parentElement.remove();
    
    $('#formEditSetor .barisEditSetor').each(function (e,i) {
        i.querySelector('.nomor').innerHTML = e+1;
    })

    countTotalHargaEditSetor();
}

// tambah baris edit setor
const tambahBarisEditSetor = (event = false,barang = false) => {
    if (event) {
        event.preventDefault();
    }

    let tmpKatSampah = [];
    let elKatSampah  = `<option data-kategori="" selected>-- kategori sampah  --</option>`;

    if (arrayJenisSampah.length !== 0) {
        arrayJenisSampah.forEach(s=> {
            if (!tmpKatSampah.includes(s.kategori)) {
                tmpKatSampah.push(s.kategori)
                elKatSampah += `<option data-kategori="${s.kategori}"  ${barang.kategori == s.kategori ? 'selected' : ''}>${s.kategori}</option>`;
            }
        });
    }

    let eljenisSampah = `<option value='' data-harga='' selected>-- jenis sampah --</option>`;

    if (barang != false) {
        let arrayJenisSampahSorted = arrayJenisSampah.sort((a, b) => a.jenis.localeCompare(b.jenis));
    
        arrayJenisSampahSorted.forEach(s=> {
            if (s.kategori == barang.kategori) {
                eljenisSampah += `<option value='${s.id}' data-harga='${s.harga}' ${barang.jenis == s.jenis ? 'selected' : ''}>${s.jenis}</option>`;
            }
        });
    }

    let totalBaris = $('.barisEditSetor').size();
    let elementRow = ` <td class="py-2" style="border-right: 0.5px solid #E9ECEF;">
        <span class="w-100 btn btn-danger d-flex justify-content-center align-items-center border-radius-sm" style="height: 38px;margin: 0;" onclick="hapusBarisEditSetor(this);">
            <i class="fas fa-times text-white"></i>
        </span>
    </td>
    <td class="py-2" style="border-right: 0.5px solid #E9ECEF;">
        <span class="nomor w-100 d-flex justify-content-center align-items-center border-radius-sm" style="height: 38px;margin: 0;">
            ${$('.barisEditSetor').size()+1}
        </span>
    </td>
    <td class="py-2" style="border-right: 0.5px solid #E9ECEF;">
        <select id="kategori-berita-wraper" class="inputJenisSampah form-control form-control-sm py-1 pl-2 border-radius-sm" style="min-height: 38px" onchange="insertJenisSampah(this,event);">
            ${elKatSampah}
        </select>
    </td>
    <td class="py-2" style="border-right: 0.5px solid #E9ECEF;">
        <select id="kategori-berita-wraper" class="inputJenisSampah form-control form-control-sm py-1 pl-2 border-radius-sm" name="transaksi[slot${totalBaris+1}][id_sampah]" style="min-height: 38px" onchange="getHargaInOptionEditSetor(this,event);" ${barang != false ? '' : 'disabled'}>
            ${eljenisSampah}
        </select>
    </td>
    <td class="py-2" style="border-right: 0.5px solid #E9ECEF;">
        <input type="text" class="inputJumlahSampah form-control form-control-sm pl-2 border-radius-sm" value="${barang != false ? barang.jumlah_kg : 0}" name="transaksi[slot${totalBaris+1}][jumlah]" style="min-height: 38px" onkeyup="countHargaXjumlahEditSetor(this);" autocomplete="off">
    </td>
    <td class="py-2">
        <input type="text" class="inputHargaSampah form-control form-control-sm pl-2 border-radius-sm" style="min-height: 38px" data-harga="${barang != false ? barang.jumlah_rp/barang.jumlah_kg : 0}"  value="${barang != false ? barang.jumlah_rp : 0}" disabled>
    </td>`

    let tr = document.createElement('tr');
    tr.classList.add('barisEditSetor');

    tr.innerHTML=elementRow;
    document.querySelector('#table-edit-setor tbody').insertBefore(tr,document.querySelector('#table-edit-setor #special-tr'));
}

// jenis sampah on change edit setor
const getHargaInOptionEditSetor = (el,event) =>{
    var harga = event.target.options[event.target.selectedIndex].dataset.harga;

    let elInputJumlah   = el.parentElement.nextElementSibling.children[0];
    elInputJumlah.value = 1;

    let elInputHarga   = el.parentElement.nextElementSibling.nextElementSibling.children[0];
    // elInputHarga.value = modifUang(harga);
    elInputHarga.value = harga;
    elInputHarga.setAttribute('data-harga',harga);

    countTotalHargaEditSetor();
};

// count harga*jumlah edit setor
const countHargaXjumlahEditSetor = (el) =>{
    var jumlahKg = el.value == '' ? 0 : el.value;
    let elInputJenis = el.parentElement.previousElementSibling.children[0];
    
    if (elInputJenis.value !== '') {
        if (jumlahKg !== '') {
            let elInputHarga = el.parentElement.nextElementSibling.children[0];
            let harga        = elInputHarga.getAttribute('data-harga');
            
            elInputHarga.value = parseFloat(parseFloat(jumlahKg)*parseInt(harga)).toFixed(2);
            countTotalHargaEditSetor();
        }
    }
};

// count total harga sampah edit setor
const countTotalHargaEditSetor = () =>{
    let total = 0;
    $(`#formEditSetor .inputHargaSampah`).each(function() {
        total = total + parseFloat($(this).val());
    });

    $('#formEditSetor #special-tr #total-harga').html(modifUang(total.toString()));
};

// Validate Edit Setor sampah
const validateEditSetor = () => {
    let status = true;
    let msg    = '';
    $('#formEditSetor .form-control').removeClass('is-invalid');
    $('#formEditSetor .text-danger').html('');

    // tgl transaksi
    if ($('#formEditSetor #date').val() == '') {
        $('#formEditSetor #date').addClass('is-invalid');
        $('#formEditSetor #date-error').html('*waktu harus di isi');
        status = false;
    }
    // waktu transaksi
    if ($('#formEditSetor #time').val() == '') {
        $('#formEditSetor #time').addClass('is-invalid');
        $('#formEditSetor #date-error').html('*waktu harus di isi');
        status = false;
    }

    // jenis sampah
    $(`#formEditSetor .inputJenisSampah`).each(function() {
        if ($(this).val() == '' || $(this).attr('disabled')) {
            $(this).addClass('is-invalid');
            status = false;
            msg    = 'input tidak boleh kosong!';
        }
    });
    // jumlah sampah
    $(`#formEditSetor .inputJumlahSampah`).each(function() {
        if ($(this).val() == '') {
            $(this).addClass('is-invalid');
            status = false;
            msg    = 'input tidak boleh kosong!';
        }
        if (/[^0-9\.]/g.test($(this).val().replace(/ /g,''))) {
            $(this).addClass('is-invalid');
            status = false;
            msg    = 'jumlah hanya boleh berupa angka positif dan titik!';
        }
    });

    if (status == false && msg !== "") {
        showAlert({
            message: `<strong>${msg}</strong>`,
            autohide: true,
            type:'danger'
        });
    }

    return status;
}

/**
 * SEND EDIT SETOR
 * =============================================
 */
const doEditSetor = async (el,event) => {
    event.preventDefault();
    let elForm    = el.parentElement.parentElement.parentElement;

    if (validateEditSetor()) {
        let form           = new FormData(elForm);
        let tglTransaksi   = form.get('date').split('-');
        let waktuTransaksi = form.get('time');
        form.set('date',`${tglTransaksi[2]}-${tglTransaksi[1]}-${tglTransaksi[0]} ${waktuTransaksi}`);

        showLoadingSpinner();
        httpResponse = await httpRequestPost(`${APIURL}/transaksi/editsetorsampah`,form);    
        hideLoadingSpinner();

        if (httpResponse.status === 201) {
            updateTableAndGrafik(`${tglTransaksi[0]}/${tglTransaksi[1]}/31`,'setorsampah');
            getSaldoNasabah();

            showAlert({
                message: `<strong>Success...</strong> edit setor berhasil!`,
                autohide: true,
                type:'success'
            })
        }
    }
}

/**
 * HAPUS TRANSAKSI
 * =============================================
 */
const deleteTransaksiNasabah = (id,event) => {
    event.preventDefault();

    Swal.fire({
        title: 'ANDA YAKIN?',
        text: "Data akan terhapus permanen",
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
                        return httpRequestDelete(`${APIURL}/transaksi/deletedata?id=${id}`)
                        .then((e) => {
                            if (e.status == 201) {
                                getSampahMasuk();
                                getSaldoNasabah();

                                if(id.includes('TSS')){
                                    dateStartGrafik = dateStartHistori;
                                    dateEndGrafik   = dateEndHistori;
                                    
                                    $('#btn-filter-grafik #startdate').html($('#btn-filter-histori #startdate').html());
                                    $('#btn-filter-grafik #enddate').html($('#btn-filter-histori #enddate').html());
                                    
                                    // getDataGrafikSetor();
                                }

                                getHistoriTransaksi();
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
}

/**
 * REKAP TRANSAKSI
 * ============================================
 */
// input jenis on change
$('#formRekapTransaksi select[name=jenis]').on('change',function (e) {
    let value = $(this).val();

    if (value == 'buku-tabungan') {
        $('#formRekapTransaksi .input-start-date').addClass('d-none');
        $('#formRekapTransaksi .input-end-date').addClass('d-none');
    }
    else {
        $('#formRekapTransaksi .input-start-date').removeClass('d-none');
        $('#formRekapTransaksi .input-end-date').removeClass('d-none');
    }
})

// input date on change
$('#formRekapTransaksi input[type=date]').on('change',function (e) {
    let dateStart = $('#formRekapTransaksi input[name=date-start]').val();
    let dateEnd   = $('#formRekapTransaksi input[name=date-end]').val();

    if (dateStart && dateEnd) {
        $('#btn-filter-transaksi').attr('data-dismiss','modal');
        // $('#btn-filter-transaksi').attr('onclick','filterTransaksi(this,event);');
        $('#btn-filter-transaksi').attr('disabled',true);

        $('#formRekapTransaksi input[type=date]').removeClass('is-invalid');
    }
    else {
        $('#btn-filter-transaksi').removeAttr('data-dismiss');
        $('#btn-filter-transaksi').removeAttr('disabled');

        if (dateStart == '') {
            $('#formRekapTransaksi input[name=date-start]').addClass('is-invalid');
        }
        if (dateEnd == '') {
            $('#formRekapTransaksi input[name=date-end]').addClass('is-invalid');
        }
    }
})

// do filter rekap
const cetakRekap = () => {
    let formFilter      = new FormData(document.querySelector('#formRekapTransaksi'));
    let jenis           = formFilter.get('jenis');

    if (jenis != 'buku-tabungan') {
        if (formFilter.get('date-start') == '' || formFilter.get('date-end') == '') {
            showAlert({
                message: `<strong>Perhatian...</strong> isi waktu mulai dan waktu berakhir!`,
                autohide: true,
                type:'warning'
            })
            return 0;
        }
    }

    let inputStartDate  = formFilter.get('date-start').split('-');
    let inputEndDate    = formFilter.get('date-end').split('-');
    
    let start    = `${inputStartDate[2]}-${inputStartDate[1]}-${inputStartDate[0]}`;
    let end      = `${inputEndDate[2]}-${inputEndDate[1]}-${inputEndDate[0]}`;
    let rekapUrl = `${BASEURL}/transaksi/cetakrekap/${jenis}?start=${start}&end=${end}&idnasabah=${IDNASABAH}`;

    window.open(rekapUrl, '_blank');
};

// update grafik
const updateTableAndGrafik = (valInputDate,method) => {
    let unixStart     = new Date(`${valInputDate} 00:00:01`).getTime();
    let isSetorSampah = (method == 'setorsampah') ? true : false ;

    setCurrentStartDate(unixStart,isSetorSampah);
    
    if (method == 'setorsampah') {
        // getDataGrafikSetor();
    } 

    getHistoriTransaksi();
}