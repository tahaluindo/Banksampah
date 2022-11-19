/**
 * GET TOTAL SAMPAH MASUK
 * =========================================================
 */
const getSampahMasuk = async () => {

    let httpResponse = await httpRequestGet(`${APIURL}/transaksi/sampahmasuk`);

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
    let httpResponse = await httpRequestGet(`${APIURL}/transaksi/sampahmasuk?kategori=${kategori}`);
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
 * GET SALDO NASABAH
 * ==============================================
 */
const getSaldoNasabah = async () => {

    let httpResponse = await httpRequestGet(`${APIURL}/transaksi/getsaldo`);
    
    if (httpResponse.status === 200) {
        let data = httpResponse.data.data;

        if (data.saldo_bank != null ) {
            $('#saldo-uang-bsbl').html(modifUang(data.saldo_bank.toString()));
        }
        if (data.saldo_nasabah.uang != null ) {
            $('#saldo-uang-nasabah').html(modifUang(data.saldo_nasabah.uang.toString()));
        }
        if (data.saldo_nasabah.emas != null ) {
            $('#saldo-emas').html(parseFloat(data.saldo_nasabah.emas).toFixed(4));
        }
    }
};
getSaldoNasabah();

/**
 * GET TOTAL AKUN
 * ==============================================
 */
const getTotalAkun = async () => {

    let httpResponse = await httpRequestGet(`${APIURL}/admin/totalakun`);
    
    if (httpResponse.status === 200) {
        let dataAkun = httpResponse.data.data;

        $('#jml-nasabah').html(modifUang(dataAkun.jml_nasabah));
        $('#jml-admin').html(modifUang(dataAkun.jml_admin));
    }
};
getTotalAkun();

/**
 * GRAFIK PENYETORAN Section
 * =========================================
 */
// get data wilayah
let arrayWilayah = [];
const getAllWilayah = async () => {

    let httpResponse = await httpRequestGet(`${APIURL}/nasabah/wilayah`);

    let tmpProvinsi  = [];
    let elprovinsi   = `<option value="">-- pilih provinsi --</option>`;
    
    if (httpResponse.status === 200) {
        arrayWilayah = httpResponse.data.data;

        arrayWilayah.forEach(w=> {
            if (!tmpProvinsi.includes(w.provinsi)) {
                tmpProvinsi.push(w.provinsi)
                elprovinsi += `<option value="${w.provinsi}" data-provinsi="${w.provinsi}">${w.provinsi}</option>`;
            }
        });
    }

    $('#formFilterGrafikSetor select[name=provinsi]').html(elprovinsi);
};
getAllWilayah();

// wilayah on change
$('#formFilterGrafikSetor select[name=provinsi]').on('change', function() {
    let tmpKota = [];
    let elKota  = `<option value="">-- pilih kota --</option>`;
    
    if ($(this).val() != '') {
        $('#formFilterGrafikSetor select[name=kota]').removeAttr('disabled');

        arrayWilayah.forEach(w=> {
            if (w.provinsi == $(this).val()) {
                if (!tmpKota.includes(w.kota)) {
                    tmpKota.push(w.kota)
                    elKota += `<option value="${w.kota}">${w.kota}</option>`;
                }
            }
        });

        $('#formFilterGrafikSetor select[name=kota]').html(elKota);
    }
    else {
        $('#formFilterGrafikSetor select[name=kota]').attr('disabled',true);
    }
    $('#formFilterGrafikSetor select[name=kota]').val('');
    $('#formFilterGrafikSetor select[name=kecamatan]').val('');
    $('#formFilterGrafikSetor select[name=kecamatan]').attr('disabled',true);
    $('#formFilterGrafikSetor select[name=kelurahan]').val('');
    $('#formFilterGrafikSetor select[name=kelurahan]').attr('disabled',true);
});
$('#formFilterGrafikSetor select[name=kota]').on('change', function() {
    let tmpKecamatan = [];
    let elKecamatan  = `<option value="">-- pilih kecamatan --</option>`;

    if ($(this).val() != '') {
        $('#formFilterGrafikSetor select[name=kecamatan]').removeAttr('disabled');

        arrayWilayah.forEach(w=> {
            if (w.kota == $(this).val()) {
                if (!tmpKecamatan.includes(w.kecamatan)) {
                    tmpKecamatan.push(w.kecamatan)
                    elKecamatan += `<option value="${w.kecamatan}">${w.kecamatan}</option>`;
                }
            }
        });

        $('#formFilterGrafikSetor select[name=kecamatan]').html(elKecamatan);
    }
    else {
        $('#formFilterGrafikSetor select[name=kecamatan]').attr('disabled',true);
    }
    $('#formFilterGrafikSetor select[name=kecamatan]').val('');
    $('#formFilterGrafikSetor select[name=kelurahan]').val('');
    $('#formFilterGrafikSetor select[name=kelurahan]').attr('disabled',true);
});
$('#formFilterGrafikSetor select[name=kecamatan]').on('change', function() {
    let tmpKelurahan = [];
    let elKelurahan  = `<option value="">-- pilih kelurahan --</option>`;

    if ($(this).val() != '') {
        $('#formFilterGrafikSetor select[name=kelurahan]').removeAttr('disabled');

        arrayWilayah.forEach(w=> {
            if (w.kecamatan == $(this).val()) {
                if (!tmpKelurahan.includes(w.kelurahan)) {
                    tmpKelurahan.push(w.kelurahan)
                    elKelurahan += `<option value="${w.kelurahan}">${w.kelurahan}</option>`;
                }
            }
        });

        $('#formFilterGrafikSetor select[name=kelurahan]').html(elKelurahan);
    }
    else {
        $('#formFilterGrafikSetor select[name=kelurahan]').attr('disabled',true);
    }
    $('#formFilterGrafikSetor select[name=kelurahan]').val('');
});

// input tampilan onchange
$('#formFilterGrafikSetor input[name=tampilan]').on('change', function() {
    if ($(this).val() == 'per-daerah') {
        $('#formFilterGrafikSetor select[name=kelurahan]').addClass('d-none');
    } 
    else {
        $('#formFilterGrafikSetor select[name=kelurahan]').removeClass('d-none');
    }
})

// do filter rekap
const filterGrafikSetor = async (e) => {
    let formFilter = new FormData(e.parentElement.parentElement.parentElement);
    let ketFilter  = `${formFilter.get('year')} - `;
    penyetoranUrl  = `${APIURL}/transaksi/grafikssampah?year=${formFilter.get('year')}&tampilan=${formFilter.get('tampilan')}`;
    typeTampilan   = formFilter.get('tampilan');

    if (formFilter.get('kelurahan')) {
        ketFilter  += `${formFilter.get('kelurahan')}, `;
        penyetoranUrl  += `&kelurahan=${formFilter.get('kelurahan')}`;
    }
    if (formFilter.get('kecamatan')) {
        ketFilter  += `${formFilter.get('kecamatan')}, `;
        penyetoranUrl  += `&kecamatan=${formFilter.get('kecamatan')}`;
    }
    if (formFilter.get('kota')) {
        ketFilter  += `${formFilter.get('kota')}, `;
        penyetoranUrl  += `&kota=${formFilter.get('kota')}`;
    }
    if (formFilter.get('provinsi')) {
        ketFilter  += `${formFilter.get('provinsi')}`
        penyetoranUrl  += `&provinsi=${formFilter.get('provinsi')}`
    }
    if (formFilter.get('provinsi') == '') {
        ketFilter  = `${formFilter.get('year')} - semua wilayah`;
        penyetoranUrl  = `${APIURL}/transaksi/grafikssampah?year=${formFilter.get('year')}&tampilan=${formFilter.get('tampilan')}`;
    }

    $('#ket-filter-grafik-penyetoran').html(`${ketFilter} <small class="text-xxs">(${formFilter.get('tampilan')})</small>`);
    getDataSetorSampah();
};

// reset filter rekap
const resetFilterGrafik = async (e) => {
    $('#formFilterGrafikSetor select[name=year]').val(new Date().getFullYear());
    $('#formFilterGrafikSetor select[name=orderby]').val('terbaru');
    
    $('#formFilterGrafikSetor select[name=provinsi]').val('');
    $('#formFilterGrafikSetor select[name=kota]').val('');
    $('#formFilterGrafikSetor select[name=kota]').attr('disabled',true);
    $('#formFilterGrafikSetor select[name=kecamatan]').val('');
    $('#formFilterGrafikSetor select[name=kecamatan]').attr('disabled',true);
    $('#formFilterGrafikSetor select[name=kelurahan]').val('');
    $('#formFilterGrafikSetor select[name=kelurahan]').attr('disabled',true);

    $('#formFilterGrafikSetor .form-check-input').prop('checked',false);
    $(`#formFilterGrafikSetor input#per-bulan`).prop('checked',true);
};

// Get data penyetoran
let chartGrafik     = '';
let typeTampilan    = 'per-bulan';
let penyetoranUrl   = `${APIURL}/transaksi/grafikssampah?year=${new Date().getFullYear()}&tampilan=per-bulan`;
const getDataSetorSampah = async () => {

    $('#spinner-grafik-penyetoran').removeClass('d-none');
    let httpResponse = await httpRequestGet(penyetoranUrl);
    $('#spinner-grafik-penyetoran').addClass('d-none'); 

    let chartType = 'line';
    let arrayX = [""];
    let arrayY = [0];
    
    if (httpResponse.status === 200) {
        let allTransaksi = httpResponse.data.data;
        
        if (typeTampilan == 'per-daerah') {
            chartType = 'bar';
            $('#chart-title').html(allTransaksi.label);

            allTransaksi.daerah.forEach(e => {
                arrayX.push(e[allTransaksi.label]);
                arrayY.push(e.jumlah_kg);
            });
        }
        else{
            $('#chart-title').html('bulan');

            for (const key in allTransaksi) {
                arrayX.push(key);
                arrayY.push(allTransaksi[key].totSampahMasuk);
            }
        }
    }

    if (chartGrafik != '') {
        chartGrafik.destroy();
    }

    var ctx2 = document.getElementById("chart-grafik-penyetoran").getContext("2d");
    document.querySelector("#chart-grafik-penyetoran").style.width    = '100%';
    document.querySelector("#chart-grafik-penyetoran").style.height   = '300px';
    document.querySelector("#chart-grafik-penyetoran").style.maxHeight= '300px';

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
getDataSetorSampah();