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
 * Clear form
 */
const clearAllForm = (isFormJualSetorSampah = false) => {
    $('#formWraper .form-check-input').removeClass('is-invalid');
    $('#formWraper .form-control').removeClass('is-invalid');
    $('#formWraper .form-check-input').prop('checked',false);
    $('#formWraper .text-danger').html('');
    $(`#formWraper .form-control`).val('');
    $('#form-tarik-saldo #maximal-saldo span').html('')

    if (isFormJualSetorSampah) {
        $('.barisSetorSampah').remove();
        tambahBaris();
        countTotalHarga();
    } 
}

/**
 * Toggle switch transaksi
 */
let idnasabah  = '';
let formTarget = 'setor-jual-sampah';
$('#toggle-transaksi-wraper .switch-section').on('click',function (e) {
    $('.toggle-transaksi').removeClass(`bg-${$('.toggle-transaksi').attr('data-color')}`);
    $('.toggle-transaksi').attr('data-color',$(this).data('color'));
    $('.toggle-transaksi').addClass(`bg-${$(this).data('color')}`);
    $('#toggle-transaksi-wraper .switch-section').removeClass('opacity-0');
    $(this).addClass('opacity-0');
    $('.toggle-transaksi').css("transform", `translateX(${$(this).position().left}px)`);
    $('.toggle-transaksi').html($(this).html());

    idnasabah  = '';
    formTarget = $(this).data('form');
    
    $('#barrier-transaksi').removeClass('d-none');
    $('#formWraper form').addClass('d-none opacity-6');
    $(`#form-${formTarget}`).removeClass('d-none');
    $('#search-nasabah-wraper table td span').html('');

    if ($(this).html() == 'setor sampah' || $(this).html() == 'jual sampah') {
        clearAllForm(true);
    }
    else{
        clearAllForm();
    }

    $('#pemilik-saldo-wraper').removeClass('d-none');
    $('#pemilik-saldo').val('nasabah');
    $(`#form-tarik-saldo #input-jenis-saldo`).removeClass('d-none');
    $(`#form-tarik-saldo #input-jenis-emas`).removeClass('d-none');

    jenisForm = $(this).html(); 
    if (jenisForm == 'jual sampah') {
        $('#pemilik-saldo-wraper').addClass('d-none');
        $('#barrier-transaksi').addClass('d-none');
        $(`#form-${formTarget}`).removeClass('opacity-6');
        $('#search-nasabah-wraper').addClass('d-none');
        $(`#form-${formTarget} input[type=date]`).val(getCurrentDate());
        $(`#form-${formTarget} input[type=time]`).val(getCurrentTime());
    }
    else{
        if (jenisForm != 'tarik saldo') {
            $('#pemilik-saldo-wraper').addClass('d-none');
        }
        $('#search-nasabah-wraper').removeClass('d-none');
        $('#barrier-search-nasabah').addClass('d-none');
        $(`#search-nasabah-wraper .input-group`).removeClass('opacity-6');
    }
})

/**
 * GET SALDO BSBL
 * ==============================================
 */
const getSaldoBsbl = async () => {

    let httpResponse = await httpRequestGet(`${APIURL}/transaksi/getsaldo`);
    
    if (httpResponse.status === 200) {
        let data = httpResponse.data.data;
        $('#form-tarik-saldo #maximal-saldo-bsbl span').html(`Rp ${modifUang(data.saldo_bank)}`);
    }
};
getSaldoBsbl();

/**
 * Pemilik Saldo On Change
 */
 $('#pemilik-saldo').on('click',function (e) {

    if ($(this).val() == "bsbl") {
        $('#barrier-transaksi').addClass('d-none');
        $(`#form-tarik-saldo`).removeClass('opacity-6');
        $(`#form-tarik-saldo`).attr('data-pemilik',"bsbl");
        $('#search-nasabah-wraper').addClass('d-none');
        $('#barrier-search-nasabah').removeClass('d-none');
        $(`#search-nasabah-wraper .input-group`).addClass('opacity-6');
        $(`#form-tarik-saldo input[type=date]`).val(getCurrentDate());
        $(`#form-tarik-saldo input[type=time]`).val(getCurrentTime());
        $(`#form-tarik-saldo #input-jenis-saldo`).addClass('d-none');
        $(`#form-tarik-saldo #input-jenis-emas`).addClass('d-none');
        $('#form-tarik-saldo #maximal-saldo').addClass('d-none');
        $('#form-tarik-saldo #maximal-saldo-bsbl').removeClass('d-none');
        $('#form-tarik-saldo .keterangan').removeClass('d-none');
    }
    else{
        $('#barrier-transaksi').removeClass('d-none');
        $(`#form-tarik-saldo`).addClass('opacity-6');
        $(`#form-tarik-saldo`).attr('data-pemilik',"nasabah");
        $('#search-nasabah-wraper').removeClass('d-none');
        $('#barrier-search-nasabah').addClass('d-none');
        $(`#search-nasabah-wraper .input-group`).removeClass('opacity-6');
        $(`#form-tarik-saldo #input-jenis-saldo`).removeClass('d-none');
        $(`#form-tarik-saldo #input-jenis-emas`).removeClass('d-none');
        $('#form-tarik-saldo #maximal-saldo').removeClass('d-none');
        $('#form-tarik-saldo #maximal-saldo-bsbl').addClass('d-none');
        $('#form-tarik-saldo .keterangan').addClass('d-none');
    }
})

/**
 * Search nasabah
 */
let dataNasabah = "";
let dataSaldo   = "";
const searchNasabah = async (el = false,event = false) => {
    if(event !== false){
        event.preventDefault();
    }

    if ($('#search-nasabah').val() == '') {
        return 0;
    }

    $('#btn-search-nasabah #text').addClass('d-none');
    $('#btn-search-nasabah #spinner').removeClass('d-none');

    let searchVal     = $('#search-nasabah').val();
    let httpResponse1 = await httpRequestGet(`${APIURL}/admin/getnasabah?key=${searchVal}`);

    $('#btn-search-nasabah #text').removeClass('d-none');
    $('#btn-search-nasabah #spinner').addClass('d-none');

    if (httpResponse1.status === 200) {
        dataNasabah = httpResponse1.data.data[0];
        idnasabah   = dataNasabah.id;

        $('#btn-search-nasabah #text').addClass('d-none');
        $('#btn-search-nasabah #spinner').removeClass('d-none');
        let httpResponse2 = await httpRequestGet(`${APIURL}/transaksi/getsaldo?idnasabah=${idnasabah}`);
        dataSaldo = httpResponse2.data.data;
        $('#btn-search-nasabah #text').removeClass('d-none');
        $('#btn-search-nasabah #spinner').addClass('d-none');

        $('#id-check').html(idnasabah);
        $('#email-check').html(dataNasabah.email);
        $('#username-check').html(dataNasabah.username);
        $('#nama-lengkap-check').html(dataNasabah.nama_lengkap);
        $('#saldo-uang-check').html(`Rp. ${modifUang(dataSaldo.uang)}`);
        $('#saldo-emas-check').html(`${parseFloat(dataSaldo.emas || 0).toFixed(4)} g`);
        
        $('#barrier-transaksi').addClass('d-none');
        $(`#form-${formTarget}`).removeClass('opacity-6');
        $(`#form-${formTarget} input[type=date]`).val(getCurrentDate());
        $(`#form-${formTarget} input[type=time]`).val(getCurrentTime());
    }
    else if (httpResponse1.status === 404) {
        showAlert({
            message: `<strong>Ups...</strong> nasabah tidak ditemukan!`,
            autohide: true,
            type:'warning'
        })

        $('#id-check').html("");
        $('#email-check').html("");
        $('#username-check').html("");
        $('#nama-lengkap-check').html("");
        $('#saldo-uang-check').html(``);
        $('#saldo-emas-check').html(``);

        $('#barrier-transaksi').removeClass('d-none');
        $(`#form-${formTarget}`).addClass('opacity-6');
    }
}

/**
 * TRANSAKSI SETOR SAMPAH
 * =============================================
 */
// GET ALL JENIS SAMPAH`
let arrayJenisSampah = [];
const getAllJenisSampah = async () => {
    let httpResponse = await httpRequestGet(`${APIURL}/sampah/getsampah`);

    if (httpResponse.status === 200) {
        arrayJenisSampah = httpResponse.data.data;
    }

    $('.barisSetorSampah').remove();
    tambahBaris();
    countTotalHarga();
};
getAllJenisSampah();

// tambah baris
let jenisForm = '';
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
        <small class="text-danger"></small>
    </td>
    <td class="py-2">
        <input type="text" class="inputHargaSampah form-control form-control-sm pl-2 border-radius-sm" style="min-height: 38px" data-harga="0" value="0" disabled>
    </td>`

    let tr = document.createElement('tr');
    tr.classList.add('barisSetorSampah');
    
    tr.innerHTML=elementRow;
    document.querySelector('#table-setor-sampah tbody').insertBefore(tr,document.querySelector('#special-tr'));
}

// hapus baris
const hapusBaris = (el) => {
    el.parentElement.parentElement.remove();
    $('.barisSetorSampah').each(function (e,i) {
        i.querySelector('.nomor').innerHTML = e+1;
    })
    countTotalHarga();
}

// kategori sampah on change
const insertJenisSampah = (el,event) =>{
    var kategori      = event.target.options[event.target.selectedIndex].dataset.kategori;
    let eljenisSampah = `<option value='' data-harga='' data-harga_pusat='' data-tersedia="0" selected>-- jenis sampah --</option>`;

    let arrayJenisSampahSorted = arrayJenisSampah.sort((a, b) => a.jenis.localeCompare(b.jenis));
    
    arrayJenisSampahSorted.forEach(s=> {
        if (s.kategori == kategori) {
            eljenisSampah += `<option value='${s.id}' data-harga='${s.harga}'  data-harga_pusat='${s.harga_pusat}' data-tersedia="${s.jumlah}">${s.jenis}</option>`;
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

    if (kategori != '') {
        elInputJenisSampah.removeAttribute('disabled');
    } 
    else {
        elInputJenisSampah.setAttribute('disabled',true);
    }
};

// jenis sampah on change
const getHargaInOption = (el,event) =>{
    var harga = "";
    if (jenisForm == "jual sampah") {
        harga = event.target.options[event.target.selectedIndex].dataset.harga_pusat;
    } 
    else {
        harga = event.target.options[event.target.selectedIndex].dataset.harga;
    }

    var tersedia = event.target.options[event.target.selectedIndex].dataset.tersedia;

    let elInputJumlah   = el.parentElement.nextElementSibling.children[0];
    elInputJumlah.value = 1;
    elInputJumlah.setAttribute('data-tersedia',tersedia);
    elInputJumlah.classList.remove('is-invalid');
    elInputJumlah.nextElementSibling.innerHTML = '';

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
    $(`.inputHargaSampah`).each(function() {
        total = total + parseFloat($(this).val());
    });

    $('#special-tr #total-harga').html(modifUang(total.toString()));
};

// Validate Setor sampah
const validateSetorJualSampah = () => { 
    let status = true;
    let msg    = '';
    $('#form-setor-jual-sampah .form-control').removeClass('is-invalid');
    $('#form-setor-jual-sampah .text-danger').html('');

    // tgl transaksi
    if ($('#form-setor-jual-sampah #date').val() == '') {
        $('#form-setor-jual-sampah #date').addClass('is-invalid');
        $('#form-setor-jual-sampah #date-error').html('*waktu harus di isi');
        status = false;
    }
    // waktu transaksi
    if ($('#form-setor-jual-sampah #time').val() == '') {
        $('#form-setor-jual-sampah #time').addClass('is-invalid');
        $('#form-setor-jual-sampah #date-error').html('*waktu harus di isi');
        status = false;
    }

    // jenis sampah
    $(`.inputJenisSampah`).each(function() {
        if ($(this).val() == '' || $(this).attr('disabled')) {
            $(this).addClass('is-invalid');
            status = false;
            msg    = 'input tidak boleh kosong!';
        }
    });
    // jumlah sampah
    $(`.inputJumlahSampah`).each(function() {
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
        // if (idnasabah == '') {
        //     let kgTersedia = parseFloat($(this).attr('data-tersedia')).toFixed(2);
        //     if (parseFloat($(this).val()) > kgTersedia) {
        //         $(this).addClass('is-invalid');
        //         $(this).siblings().html(`hanya tersedia ${kgTersedia} kg`);
        //         status = false;
        //     }
        // }
    });

    if (status == false && msg !== "") {
        showAlert({
            message: `<strong>${msg}</strong>`,
            autohide: true,
            type:'danger'
        })
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
    let form   = new FormData(document.querySelector('#form-konversi-saldo'));

    $('#form-konversi-saldo .form-check-input').removeClass('is-invalid');
    $('#form-konversi-saldo .form-control').removeClass('is-invalid');
    $('#form-konversi-saldo .text-danger').html('');

    // tgl transaksi
    if ($('#form-konversi-saldo #date').val() == '') {
        $('#form-konversi-saldo #date').addClass('is-invalid');
        $('#form-konversi-saldo #date-error').html('*tanggal harus di isi');
        status = false;
    }
    // waktu transaksi
    if ($('#form-konversi-saldo #time').val() == '') {
        $('#form-konversi-saldo #time').addClass('is-invalid');
        $('#form-konversi-saldo #date-error').html('*waktu harus di isi');
        status = false;
    }
    // harga emas
    if ($('#form-konversi-saldo #harga_emas').val() == '') {
        $('#form-konversi-saldo #harga_emas').addClass('is-invalid');
        $('#form-konversi-saldo #harga_emas-error').html('*harga emas harus di isi');
        status = false;
    }
    else if (/[^0-9\.]/g.test($('#form-konversi-saldo #harga_emas').val().replace(/ /g,''))) {
        $('#form-konversi-saldo #harga_emas').addClass('is-invalid');
        $('#form-konversi-saldo #harga_emas-error').html('*hanya boleh berupa angka positif dan titik!');
        status = false;
    }
    // jumlah pindah
    if ($('#form-konversi-saldo #jumlah').val() == '') {
        $('#form-konversi-saldo #jumlah').addClass('is-invalid');
        $('#form-konversi-saldo #jumlah-error').html('*jumlah saldo harus di isi');
        status = false;
    }
    else if (/[^0-9\.]/g.test($('#form-konversi-saldo #jumlah').val().replace(/ /g,''))) {
        $('#form-konversi-saldo #jumlah').addClass('is-invalid');
        $('#form-konversi-saldo #jumlah-error').html('*hanya boleh berupa angka positif dan titik!');
        status = false;
    }
    else if (parseFloat($('#form-konversi-saldo #jumlah').val()) < 10000) {
        $('#form-konversi-saldo #jumlah').addClass('is-invalid');
        $('#form-konversi-saldo #jumlah-error').html('*minimal Rp.10,000');
        status = false;
    }

    return status;
}

/**
 * TRANSAKSI TARIK SALDO
 * =============================================
 */

// jenis saldo on click
$('#form-tarik-saldo input[name=jenis_saldo]').on('click', function() {
    if ($(this).attr('value') == "uang") {
        $('#form-tarik-saldo #maximal-saldo span').html(`Rp ${modifUang(dataSaldo.uang)}`);
        $('#form-tarik-saldo #jenis-emas').attr(`disabled`,true);
        $('#form-tarik-saldo #jenis-emas').val('');
    } 
    else {
        $('#form-tarik-saldo #maximal-saldo span').html(`${dataSaldo.emas} g`);
        $('#form-tarik-saldo #jenis-emas').removeAttr(`disabled`);
    }
})

// Validate Tarik Saldo Nasabah
const validateTarikSaldo = (pemilikSaldo) => {
    let status = true;
    let form   = new FormData(document.querySelector('#form-tarik-saldo'));

    $('#form-tarik-saldo .form-check-input').removeClass('is-invalid');
    $('#form-tarik-saldo .form-control').removeClass('is-invalid');
    $('#form-tarik-saldo .text-danger').html('');

    // tgl transaksi
    if ($('#form-tarik-saldo #date').val() == '') {
        $('#form-tarik-saldo #date').addClass('is-invalid');
        $('#form-tarik-saldo #date-error').html('*tanggal harus di isi');
        status = false;
    }
    // waktu transaksi
    if ($('#form-tarik-saldo #time').val() == '') {
        $('#form-tarik-saldo #time').addClass('is-invalid');
        $('#form-tarik-saldo #date-error').html('*waktu harus di isi');
        status = false;
    }
    // jenis saldo
    if (pemilikSaldo != "bsbl") {
        if (form.get('jenis_saldo') == null) {
            $('#form-tarik-saldo .form-check-input').addClass('is-invalid');
            status = false;
        }
        if (form.get('jenis_saldo') == 'emas') {
            if (form.get('jenis_emas') == '') {
                $('#form-tarik-saldo #jenis-emas').addClass('is-invalid');
                status = false;
            }
        }
    }
    // jumlah
    if ($('#form-tarik-saldo #jumlah').val() == '') {
        $('#form-tarik-saldo #jumlah').addClass('is-invalid');
        $('#form-tarik-saldo #jumlah-error').html('*jumlah saldo harus di isi');
        status = false;
    }
    else if (/[^0-9\.]/g.test($('#form-tarik-saldo #jumlah').val().replace(/ /g,''))) {
        $('#form-tarik-saldo #jumlah').addClass('is-invalid');
        $('#form-tarik-saldo #jumlah-error').html('*hanya boleh berupa angka positif dan titik!');
        status = false;
    }
    // keterangan
    if(pemilikSaldo == 'bsbl') {
        if ($('#form-tarik-saldo #description').val() == '') {
            $('#form-tarik-saldo #description').addClass('is-invalid');
            $('#form-tarik-saldo #description-error').html('*keterangan harus di isi');
            status = false;
        }
    }

    return status;
}

// Validate Tarik Saldo BSBL
const validateTarikSaldoBsbl = () => {
    let status = true;
    let form   = new FormData(document.querySelector('#form-tarik-saldo'));

    $('#form-tarik-saldo .form-check-input').removeClass('is-invalid');
    $('#form-tarik-saldo .form-control').removeClass('is-invalid');
    $('#form-tarik-saldo .text-danger').html('');

    // tgl transaksi
    if ($('#form-tarik-saldo #date').val() == '') {
        $('#form-tarik-saldo #date').addClass('is-invalid');
        $('#form-tarik-saldo #date-error').html('*tanggal harus di isi');
        status = false;
    }
    // waktu transaksi
    if ($('#form-tarik-saldo #time').val() == '') {
        $('#form-tarik-saldo #time').addClass('is-invalid');
        $('#form-tarik-saldo #date-error').html('*waktu harus di isi');
        status = false;
    }
    // jumlah tarik
    if ($('#form-tarik-saldo #jumlah').val() == '') {
        $('#form-tarik-saldo #jumlah').addClass('is-invalid');
        $('#form-tarik-saldo #jumlah-error').html('*jumlah saldo harus di isi');
        status = false;
    }
    else if (/[^0-9\.]/g.test($('#form-tarik-saldo #jumlah').val().replace(/ /g,''))) {
        $('#form-tarik-saldo #jumlah').addClass('is-invalid');
        $('#form-tarik-saldo #jumlah-error').html('*hanya boleh berupa angka positif dan titik!');
        status = false;
    }

    return status;
}

/**
 * Send Transaksi to API
 * =============================================
 */
const doTransaksi = async (el,event,method) => {
    event.preventDefault();
    let validate      = '';
    let elForm        = el.parentElement.parentElement;
    let transaksiName = ''
    let pemilikSaldo  = elForm.getAttribute("data-pemilik");

    if (method == 'pindahsaldo') {
        validate = validatePindahSaldo;
        transaksiName = 'pindah saldo';
    }
    else if (method == 'tariksaldo') {
        validate = validateTarikSaldo;
        transaksiName = 'tarik saldo';

        console.log(pemilikSaldo);
        if (pemilikSaldo == "bsbl") {
            method = "tariksaldobsbl";
        }
    }
    else if (method == 'setorjualsampah') {
        validate = validateSetorJualSampah;
        
        if (idnasabah == '') {
            method = 'jualsampah'
            transaksiName = 'jual sampah';
        } else {
            method = 'setorsampah'
            transaksiName = 'setor sampah';
        }
    }

    let doTransaksiInner = async () => {
        let form           = new FormData(elForm);
        let tglTransaksi   = form.get('date').split('-');
        let waktuTransaksi = form.get('time');
        form.set('date',`${tglTransaksi[2]}-${tglTransaksi[1]}-${tglTransaksi[0]} ${waktuTransaksi}`);

        if (idnasabah == '') {
            form.set('id_admin',IDADMIN);
        } 
        else {
            form.set('id_nasabah',idnasabah);
        }

        if (method == 'tariksaldo') {
            if ($('#form-tarik-saldo #jenis-emas').val() !== '') {
                form.set('jenis_saldo',$('#form-tarik-saldo #jenis-emas').val());
            }
        } 

        showLoadingSpinner();
        httpResponse = await httpRequestPost(`${APIURL}/transaksi/${method}`,form);    
        hideLoadingSpinner();

        if (httpResponse.status === 201) {
            clearAllForm();
            getAllJenisSampah();
            updateAllTable(`${tglTransaksi[0]}/${tglTransaksi[1]}/31`);
            
            if (method != 'jualsampah') {
                $('#search-nasabah-wraper table td span').html('_ _ _ _');
                $(`#form-${formTarget}`).addClass('opacity-6');
                $('#barrier-transaksi').removeClass('d-none');
                searchNasabah();                    
            }
            else{
                getSaldoBsbl();
                $(`#form-${formTarget} input[type=date]`).val(getCurrentDate());
                $(`#form-${formTarget} input[type=time]`).val(getCurrentTime());
            }

            if(method == 'tariksaldo' || method == 'tariksaldobsbl'){
                $('#form-tarik-saldo #maximal-saldo span').html(``);
                $('#form-tarik-saldo #jenis-emas').attr(`disabled`,true);
                $('#form-tarik-saldo #jenis-emas').val('');
                    
                if (pemilikSaldo == 'bsbl') {
                    getSaldoBsbl();
                    $(`#form-${formTarget}`).removeClass('opacity-6');
                    $('#barrier-transaksi').addClass('d-none');
                    $(`#form-${formTarget} input[type=date]`).val(getCurrentDate());
                    $(`#form-${formTarget} input[type=time]`).val(getCurrentTime());
                }
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
                    $('#form-konversi-saldo #jumlah').addClass('is-invalid');
                    $('#form-konversi-saldo #jumlah-error').html(`*${httpResponse.message.jumlah}`);
                } 
                else {
                    $('#form-tarik-saldo #jumlah').addClass('is-invalid');
                    $('#form-tarik-saldo #jumlah-error').html(`*${httpResponse.message.jumlah}`);
                }
            }
        }
    }

    if (validate(pemilikSaldo)) {
        doTransaksiInner();

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

const updateAllTable = (valInputDate) => {
    let unixStart = new Date(`${valInputDate} 00:00:01`).getTime();
    setCurrentStartDate(unixStart);
    getDataTransaksi();

    ketFilter  = `${new Date(unixStart).getFullYear()} - semua wilayah`;
    rekapTUrl  = `${APIURL}/transaksi/rekapdata?year=${new Date(unixStart).getFullYear()}`;

    $('#ket-filter-rekap-transaksi').html(ketFilter);
    getRekapTransaksi();
}

/**
 * HAPUS TRANSAKSI
 * =============================================
 */
const deleteTransaksi = (id,event) => {
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
                                getAllJenisSampah();
                                getDataTransaksi();
                                getSaldoBsbl();
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
 * FILTER DATA TRANSACTION Section
 * =========================================================
 */
let dateStar        = '';
let dateEnd         = '';
let ketDateStar     = '';
let ketDateEnd      = '';
let orderby         = 'terbaru';
let jenisTransaksi  = 'semua jenis';

// modal filter transaksi when open
let openModalFilterDataT = () =>  {
    let dateStart  = $(`#ket-filter-data-transaksi #startdate`).html().split('/');
    let dateEnd    = $(`#ket-filter-data-transaksi #enddate`).html().split('/');

    $('#formFilterDataTransaksi input[name=date-start]').val(`${dateStart[2]}-${dateStart[1]}-${dateStart[0]}`);
    $('#formFilterDataTransaksi input[name=date-end]').val(`${dateEnd[2]}-${dateEnd[1]}-${dateEnd[0]}`);
}

// input date on change
$('#formFilterDataTransaksi input[type=date]').on('change',function (e) {
    let dateStart = $('#formFilterDataTransaksi input[name=date-start]').val();
    let dateEnd   = $('#formFilterDataTransaksi input[name=date-end]').val();

    if (dateStart && dateEnd) {
        $('#btn-filter-data-transaksi').attr('data-dismiss','modal');
        $('#btn-filter-data-transaksi').attr('onclick','filterDataTransaksi(this,event);');

        $('#formFilterDataTransaksi input[type=date]').removeClass('is-invalid');
    }
    else {
        $('#btn-filter-data-transaksi').removeAttr('data-dismiss');
        $('#btn-filter-data-transaksi').removeAttr('onclick');

        if (dateStart == '') {
            $('#formFilterDataTransaksi input[name=date-start]').addClass('is-invalid');
        }
        if (dateEnd == '') {
            $('#formFilterDataTransaksi input[name=date-end]').addClass('is-invalid');
        }
    }
})

// set current start and end DATE
let setCurrentStartDate = (unixTime = null) =>  {
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

    dateStar    = `${previousDay}-${previousMonth}-${previousYear}`;
    dateEnd     = `${currentDay}-${currentMonth}-${currentYear}`;
    ketDateStar = `${previousDay}/${previousMonth}/${previousYear}`;
    ketDateEnd  = `${currentDay}/${currentMonth}/${currentYear}`;

    $('#ket-filter-data-transaksi').html(`
    <span id='startdate'>${ketDateStar}</span> 
    <i class="fas fa-long-arrow-alt-right mt-1 mx-2"></i> 
    <span id='enddate'>${ketDateEnd}</span> 
    &nbsp;&nbsp;(${orderby} - ${jenisTransaksi})`);
}

setCurrentStartDate();

// do filter data transaksi
const filterDataTransaksi = (e) => {
    let formFilter = new FormData(e.parentElement.parentElement.parentElement);
    
    let inputStartDate = formFilter.get('date-start').split('-');
    dateStar    = `${inputStartDate[2]}-${inputStartDate[1]}-${inputStartDate[0]}`;
    ketDateStar = `${inputStartDate[2]}/${inputStartDate[1]}/${inputStartDate[0]}`;

    let inputEndDate = formFilter.get('date-end').split('-');
    dateEnd    = `${inputEndDate[2]}-${inputEndDate[1]}-${inputEndDate[0]}`;
    ketDateEnd = `${inputEndDate[2]}/${inputEndDate[1]}/${inputEndDate[0]}`;

    orderby = formFilter.get('orderby');
    jenisTransaksi = formFilter.get('jenis_transaksi');
    
    $('#ket-filter-data-transaksi').html(`
    <span id='startdate'>${ketDateStar}</span> 
    <i class="fas fa-long-arrow-alt-right text-secondary mt-1 mx-2"></i> 
    <span id='enddate'>${ketDateEnd}</span> 
    &nbsp;&nbsp;(${orderby} - ${jenisTransaksi})`);

    getDataTransaksi();
};

/**
 * DATA TRANSAKSI Section
 * ==============================================
 */

// get all data transaksi
let arrayDataTransaksi = [];
const getDataTransaksi = async () => {
    $('#search-data-transaksi').val('');
    $('#ket-total').html('0');
    $('#table-data-transaksi tbody').html('');
    $('#data-transaksi-notfound').addClass('d-none'); 
    $('#data-transaksi-spinner').removeClass('d-none'); 
    let httpResponse = await httpRequestGet(`${APIURL}/transaksi/getdata?start=${dateStar}&end=${dateEnd}&orderby=${orderby}`);
    $('#data-transaksi-spinner').addClass('d-none'); 
    
    if (httpResponse.status === 404) {
        $('#data-transaksi-notfound').removeClass('d-none'); 
        $('#data-transaksi-notfound').html(`<h6 class='opacity-6'>belum ada transaksi</h6>`); 
    }
    else if (httpResponse.status === 200) {
        let trTransaksi  = '';
        let allTransaksi = '';
        
        if (jenisTransaksi == 'semua jenis') {
            allTransaksi = httpResponse.data.data;    
        } 
        else {
            allTransaksi = httpResponse.data.data.filter(e => e.jenis_transaksi == jenisTransaksi);
        }

        arrayDataTransaksi = allTransaksi;

        allTransaksi.forEach(t => {
            let date      = new Date(parseInt(t.date) * 1000);
            let hour      = date.toLocaleString("en-US",{ hour: '2-digit', minute: '2-digit' });
            let day       = date.toLocaleString("en-US",{day: "2-digit"});
            let month     = date.toLocaleString("en-US",{month: "long"});
            let year      = date.toLocaleString("en-US",{year: "numeric"});
            let color     = '';
            let jumlah    = '';

            if (t.jenis_transaksi == 'penyetoran sampah') {
                color  = 'success';
                jumlah = `+ ${parseFloat(t.total_kg_setor).toFixed(2)} kg`;
                // jumlah = `+ ${t.total_kg_setor}kg/Rp${modifUang(kFormatter(t.total_uang_setor))}`;
            } 
            else if (t.jenis_transaksi == 'penarikan saldo') {
                color  = 'danger';
                jumlah = (t.jenis_saldo == 'uang')?`- Rp ${modifUang(parseFloat(t.total_tarik).toFixed(0))}`:`- ${parseFloat(t.total_tarik).toFixed(4)} g`;
            } 
            else if (t.jenis_transaksi == 'konversi saldo') {
                color  = 'warning';
                jumlah = 'Rp'+modifUang(kFormatter(t[`total_pindah`]))+' <i class="fas fa-exchange-alt"></i> '+parseFloat(t[`hasil_konversi`]).toFixed(2)+'g';
            }
            else {
                color  = 'info';
                jumlah = `- ${parseFloat(t.total_kg_jual).toFixed(2)} kg`;
                // jumlah = `+ ${t.total_kg_jual}kg/Rp${modifUang(kFormatter(t.total_uang_jual))}`;
            }

            let tagNamaLengkap = (t.jenis_transaksi == 'penjualan sampah') ? 'span' : 'a';

            trTransaksi += `<tr>
                <td class="align-middle text-sm py-4">
                    <span class="text-xs text-name font-weight-bold"> 
                        ${t.id_transaksi}
                    </span>
                </td>
                <td class="align-middle text-sm" style="max-width: 120px;white-space: nowrap;">
                    <${tagNamaLengkap} href="${BASEURL}/admin/detilnasabah/${t.id_user}" target="_blank" class="text-xs text-name font-weight-bold text-dark opacity-8" style="white-space: normal;word-break: break-all;display: block;">
                        <u>${t.nama_lengkap}</u>
                    </${tagNamaLengkap}>
                </td>
                <td class="align-middle text-sm">
                    <span class="text-xxs text-name font-weight-bold badge border bg-${color} text-white border-${color} pb-1" style="min-width:150px;max-width:150px;border-radius:4px;letter-spacing:0.5px;"> 
                        ${t.jenis_transaksi}
                    </span>
                </td>
                <td class="align-middle text-sm">
                    <span class="text-xs text-name font-weight-bold"> 
                        ${jumlah}
                    </span>
                </td>
                <td class="align-middle text-sm">
                    <span class="text-xs text-name font-weight-bold">
                        ${day}-${month}-${year} ${hour}
                    </span>
                </td>
                <td class="align-middle text-center">
                    <a href='' class="badge badge-dark text-xxs pb-1 cursor-pointer" data-toggle="modal" data-target="#modalPrintTransaksi" onclick="getDetailTransaksi('${t.id_transaksi}');" style="border-radius:4px;">cetak</a>
                    <a href='' class="badge badge-danger text-xxs pb-1 cursor-pointer" onclick="deleteTransaksi('${t.id_transaksi}',event);" style="border-radius:4px;">hapus</a>
                </td>
            </tr>`;
        });

        $('#ket-total').html(allTransaksi.length);
        $('#table-data-transaksi tbody').html(trTransaksi);
    }
};
getDataTransaksi();

// search data transaksi
$('#search-data-transaksi').on('keyup', function() {
    let elSugetion      = '';
    let transaksiFiltered = [];
    
    if ($(this).val() === "") {
        transaksiFiltered = arrayDataTransaksi;
    } 
    else {
        transaksiFiltered = arrayDataTransaksi.filter((n) => {
            return n.nama_lengkap.toLowerCase().includes($(this).val().toLowerCase()) || n.id_transaksi.toLowerCase().includes($(this).val().toLowerCase());
        });
    }

    if (transaksiFiltered.length == 0) {
        $('#data-transaksi-notfound').removeClass('d-none'); 
        $('#data-transaksi-notfound #text-notfound').html(`transaksi tidak ditemukan`); 
    } 
    else {
        $('#data-transaksi-notfound').addClass('d-none'); 
        $('#data-transaksi-notfound #text-notfound').html(` `); 

        transaksiFiltered.forEach(t => {
            let date      = new Date(parseInt(t.date) * 1000);
            let hour      = date.toLocaleString("en-US",{ hour: '2-digit', minute: '2-digit' });
            let day       = date.toLocaleString("en-US",{day: "2-digit"});
            let month     = date.toLocaleString("en-US",{month: "long"});
            let year      = date.toLocaleString("en-US",{year: "numeric"});
            let color     = '';
            let jumlah    = '';

            if (t.jenis_transaksi == 'penyetoran sampah') {
                color  = 'success';
                jumlah = `+ ${parseFloat(t.total_kg_setor).toFixed(2)} kg`;
                // jumlah = `+ ${t.total_kg_setor}kg/Rp${modifUang(kFormatter(t.total_uang_setor))}`;
            } 
            else if (t.jenis_transaksi == 'penarikan saldo') {
                color  = 'danger';
                jumlah = (t.jenis_saldo == 'uang')?`- Rp ${modifUang(parseFloat(t.total_tarik).toFixed(0))}`:`- ${parseFloat(t.total_tarik).toFixed(4)} g`;
            } 
            else if (t.jenis_transaksi == 'konversi saldo') {
                color  = 'warning';
                jumlah = 'Rp'+modifUang(kFormatter(t[`total_pindah`]))+' <i class="fas fa-exchange-alt"></i> '+parseFloat(t[`hasil_konversi`]).toFixed(2)+'g';
            }
            else {
                color  = 'info';
                jumlah = `- ${parseFloat(t.total_kg_jual).toFixed(2)} kg`;
                // jumlah = `+ ${t.total_kg_jual}kg/Rp${modifUang(kFormatter(t.total_uang_jual))}`;
            }

            let tagNamaLengkap = (t.jenis_transaksi == 'penjualan sampah') ? 'span' : 'a';

            elSugetion += `<tr>
                <td class="align-middle text-sm py-4">
                    <span class="text-xs text-name font-weight-bold"> 
                        ${t.id_transaksi}
                    </span>
                </td>
                <td class="align-middle text-sm" style="max-width: 120px;white-space: nowrap;">
                    <${tagNamaLengkap} href="${BASEURL}/admin/detilnasabah/${t.id_user}" target="_blank" class="text-xs text-name font-weight-bold text-dark opacity-8" style="white-space: normal;word-break: break-all;display: block;">
                        <u>${t.nama_lengkap}</u>
                    </${tagNamaLengkap}>
                </td>
                <td class="align-middle text-sm">
                    <span class="text-xxs text-name font-weight-bold badge border bg-${color} text-white border-${color} pb-1" style="min-width:150px;max-width:150px;border-radius:4px;letter-spacing:0.5px;"> 
                        ${t.jenis_transaksi}
                    </span>
                </td>
                <td class="align-middle text-sm">
                    <span class="text-xs text-name font-weight-bold"> 
                        ${jumlah}
                    </span>
                </td>
                <td class="align-middle text-sm">
                    <span class="text-xs text-name font-weight-bold">
                        ${day}-${month}-${year} ${hour}
                    </span>
                </td>
                <td class="align-middle text-center">
                    <a href='' class="badge badge-dark text-xxs pb-1 cursor-pointer" data-toggle="modal" data-target="#modalPrintTransaksi" onclick="getDetailTransaksi('${t.id_transaksi}');" style="border-radius:4px;">cetak</a>
                    <a href='' class="badge badge-danger text-xxs pb-1 cursor-pointer" onclick="deleteTransaksi('${t.id_transaksi}',event);" style="border-radius:4px;">hapus</a>
                </td>
            </tr>`;
        });    
    }

    $('#ket-total').html(transaksiFiltered.length);
    $('#table-data-transaksi tbody').html(elSugetion);
});

// get detail data transaksi
const getDetailTransaksi = async (id) => {
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

        if (httpResponse.data.data.jenis_transaksi == 'penjualan sampah') {
            $('#modalPrintTransaksiTarget td#id-user').html('ID.ADMIN');
            $('#modalPrintTransaksi .modal-dialog').addClass('modal-lg');
        } 
        else {
            $('#modalPrintTransaksiTarget td#id-user').html('ID.NASABAH');
            $('#modalPrintTransaksi .modal-dialog').removeClass('modal-lg');
        }

        // tarik saldo
        if (httpResponse.data.data.jenis_transaksi == 'penarikan saldo') {
            let jenisSaldo = httpResponse.data.data.jenis_saldo;
            let jumlah     = (jenisSaldo == 'uang')?'Rp '+modifUang(parseFloat(httpResponse.data.data.jumlah_tarik).toFixed(0)):parseFloat(httpResponse.data.data.jumlah_tarik).toFixed(4)+' gram';
            let keterangan = httpResponse.data.data.description;

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
                <div class="${(keterangan == null) ? 'd-none' : ""}">
                    <hr class="horizontal dark mt-2">
                    <div class="text-dark text-center">
                        "<i>${keterangan}</i>"
                    </div>
                </div>
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
        if (['penyetoran sampah'].includes(httpResponse.data.data.jenis_transaksi)) {
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
        // jual sampah
        if (httpResponse.data.data.jenis_transaksi == 'penjualan sampah') {
            let totalKg      = 0;
            let totalHJual   = 0;
            let totalHBeli   = 0;
            let totalSelisih = 0;
            let trBody = '';
            let barang = httpResponse.data.data.barang;
            barang.forEach((b,i) => {
                totalKg      += b.jumlah_kg;
                totalHJual   += parseFloat(b.jumlah_rp);
                totalHBeli   += parseFloat(b.harga_nasabah);
                let selisih   = parseFloat(b.jumlah_rp).toFixed(2)-parseFloat(b.harga_nasabah).toFixed(2);
                totalSelisih += parseFloat(selisih).toFixed(2);

                trBody  += `<tr class="text-center">
                    <td>${b.jenis}</td>
                    <td class="text-right">${parseFloat(b.jumlah_kg).toFixed(2)} kg</td>
                    <td class="text-right">${modifUang(b.jumlah_rp)}</td>
                    <td class="text-right">${modifUang(b.harga_nasabah)}</td>
                    <td class="text-right">${modifUang(parseFloat(selisih).toFixed(2))}</td>
                </tr>`;
            })

            $('#detil-transaksi-body').html(`<table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Jenis sampah</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Harga Jual</th>
                        <th scope="col">Harga Beli</th>
                        <th scope="col">Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    ${trBody}
                    <tr>
                        <th class="text-center">Total</th>
                        <td class="text-right">${parseFloat(totalKg).toFixed(2)} kg</td>
                        <td class="text-right">Rp ${modifUang(parseFloat(totalHJual).toFixed(2))}</td>
                        <td class="text-right">Rp ${modifUang(parseFloat(totalHBeli).toFixed(2))}</td>
                        <td class="text-right">Rp ${modifUang(parseFloat(totalSelisih).toFixed(2))}</td>
                    </tr>
                </tbody>
            </table>`);
        }
    }
};

/**
 * REKAP TRANSAKSI Section
 * =========================================
 */
// modal filter rekap transaksi when open
let openModalFilterRekapT = () =>  {
    let ketFilter = $(`#ket-filter-rekap-transaksi`).html().split('-');
    
    if (ketFilter[1].includes('semua wilayah')) {
        resetFilterRekap(null,ketFilter[0].replace(' ',''));
    }
}

// Toggle switch transaksi
let formTargetRekap = 'pertahun';
$('#toggle-rekap-wraper .switch-section').on('click',function (e) {
    $('.toggle-rekap').removeClass(`bg-${$('.toggle-rekap').attr('data-color')}`);
    $('.toggle-rekap').attr('data-color',$(this).data('color'));
    $('.toggle-rekap').addClass(`bg-${$(this).data('color')}`);
    $('#toggle-rekap-wraper .switch-section').removeClass('opacity-0');
    $(this).addClass('opacity-0');
    $('.toggle-rekap').css("transform", `translateX(${$(this).position().left}px)`);
    $('.toggle-rekap').html($(this).html());

    formTargetRekap = $(this).data('form');
    
    $('#modalFilterRekapTransaksi form').addClass('d-none');
    $(`#formFilterRekap-${formTargetRekap}`).removeClass('d-none');
    $('#modalFilterRekapTransaksi .modal-footer').addClass('d-none');
    $(`#modal-footer-${formTargetRekap}`).removeClass('d-none');

    if (formTargetRekap == 'custom') {
        let currentUnixTime = new Date(new Date().getTime());
        let currentDay   = currentUnixTime.toLocaleString("en-US",{day: "2-digit"});
        let currentMonth = currentUnixTime.toLocaleString("en-US",{month: "2-digit"});
        let currentYear  = currentUnixTime.toLocaleString("en-US",{year: "numeric"});

        let previousUnixTime = new Date(currentUnixTime.getTime()-(86400*30*1000));
        let previousDay   = previousUnixTime.toLocaleString("en-US",{day: "2-digit"});
        let previousMonth = previousUnixTime.toLocaleString("en-US",{month: "2-digit"});
        let previousYear  = previousUnixTime.toLocaleString("en-US",{year: "numeric"});

        $('#formFilterRekap-custom input[name=date-start]').val(`${previousYear}-${previousMonth}-${previousDay}`);
        $('#formFilterRekap-custom input[name=date-end]').val(`${currentYear}-${currentMonth}-${currentDay}`);

        $('#formFilterRekap-custom input[type=date]').removeClass('is-invalid');
        $('#btn-filter-rekap-custom').attr('onclick','cetakCustomRekap();');
    }

    resetFilterRekap();
    insertPorvinsi();
})

// get data wilayah
let arrayWilayah = [];
const getAllWilayah = async () => {
    let httpResponse = await httpRequestGet(`${APIURL}/nasabah/wilayah`);
    
    if (httpResponse.status === 200) {
        arrayWilayah = httpResponse.data.data;
        insertPorvinsi();
    }
};
getAllWilayah();

const insertPorvinsi = () => {
    let tmpProvinsi  = [];
    let elprovinsi   = `<option value="">-- pilih provinsi --</option>`;
    
    arrayWilayah.forEach(w=> {
        if (!tmpProvinsi.includes(w.provinsi)) {
            tmpProvinsi.push(w.provinsi)
            elprovinsi += `<option value="${w.provinsi}" data-provinsi="${w.provinsi}">${w.provinsi}</option>`;
        }
    });

    $('#modalFilterRekapTransaksi select[name=provinsi]').html(elprovinsi);
};

// wilayah on change
$('#modalFilterRekapTransaksi select[name=provinsi]').on('change', function() {
    let tmpKota = [];
    let elKota  = `<option value="">-- pilih kota --</option>`;
    
    if ($(this).val() != '') {
        $('#modalFilterRekapTransaksi select[name=kota]').removeAttr('disabled');

        arrayWilayah.forEach(w=> {
            if (w.provinsi == $(this).val()) {
                if (!tmpKota.includes(w.kota)) {
                    tmpKota.push(w.kota)
                    elKota += `<option value="${w.kota}">${w.kota}</option>`;
                }
            }
        });

        $('#modalFilterRekapTransaksi select[name=kota]').html(elKota);
    }
    else {
        $('#modalFilterRekapTransaksi select[name=kota]').attr('disabled',true);
    }
    $('#modalFilterRekapTransaksi select[name=kota]').val('');
    $('#modalFilterRekapTransaksi select[name=kecamatan]').val('');
    $('#modalFilterRekapTransaksi select[name=kecamatan]').attr('disabled',true);
    $('#modalFilterRekapTransaksi select[name=kelurahan]').val('');
    $('#modalFilterRekapTransaksi select[name=kelurahan]').attr('disabled',true);
});
$('#modalFilterRekapTransaksi select[name=kota]').on('change', function() {
    let tmpKecamatan = [];
    let elKecamatan  = `<option value="">-- pilih kecamatan --</option>`;

    if ($(this).val() != '') {
        $('#modalFilterRekapTransaksi select[name=kecamatan]').removeAttr('disabled');

        arrayWilayah.forEach(w=> {
            if (w.kota == $(this).val()) {
                if (!tmpKecamatan.includes(w.kecamatan)) {
                    tmpKecamatan.push(w.kecamatan)
                    elKecamatan += `<option value="${w.kecamatan}">${w.kecamatan}</option>`;
                }
            }
        });

        $('#modalFilterRekapTransaksi select[name=kecamatan]').html(elKecamatan);
    }
    else {
        $('#modalFilterRekapTransaksi select[name=kecamatan]').attr('disabled',true);
    }
    $('#modalFilterRekapTransaksi select[name=kecamatan]').val('');
    $('#modalFilterRekapTransaksi select[name=kelurahan]').val('');
    $('#modalFilterRekapTransaksi select[name=kelurahan]').attr('disabled',true);
});
$('#modalFilterRekapTransaksi select[name=kecamatan]').on('change', function() {
    let tmpKelurahan = [];
    let elKelurahan  = `<option value="">-- pilih kelurahan --</option>`;

    if ($(this).val() != '') {
        $('#modalFilterRekapTransaksi select[name=kelurahan]').removeAttr('disabled');

        arrayWilayah.forEach(w=> {
            if (w.kecamatan == $(this).val()) {
                if (!tmpKelurahan.includes(w.kelurahan)) {
                    tmpKelurahan.push(w.kelurahan)
                    elKelurahan += `<option value="${w.kelurahan}">${w.kelurahan}</option>`;
                }
            }
        });

        $('#modalFilterRekapTransaksi select[name=kelurahan]').html(elKelurahan);
    }
    else {
        $('#modalFilterRekapTransaksi select[name=kelurahan]').attr('disabled',true);
    }
    $('#modalFilterRekapTransaksi select[name=kelurahan]').val('');
});

// input date on change
$('#formFilterRekap-custom input[type=date]').on('change',function (e) {
    let dateStart = $('#formFilterRekap-custom input[name=date-start]').val();
    let dateEnd   = $('#formFilterRekap-custom input[name=date-end]').val();

    if (dateStart && dateEnd) {
        $('#formFilterRekap-custom input[type=date]').removeClass('is-invalid');
        $('#btn-filter-rekap-custom').attr('onclick','cetakCustomRekap();');
    }
    else {
        $('#btn-filter-rekap-custom').removeAttr('onclick');

        if (dateStart == '') {
            $('#formFilterRekap-custom input[name=date-start]').addClass('is-invalid');
        }
        if (dateEnd == '') {
            $('#formFilterRekap-custom input[name=date-end]').addClass('is-invalid');
        }
    }
})

// do filter rekap
const doFilterRekapPertahun = async () => {
    let formFilter = new FormData(document.querySelector('#formFilterRekap-pertahun'));
    let ketFilter  = `${formFilter.get('year')} - `;
    rekapTUrl      = `${APIURL}/transaksi/rekapdata?year=${formFilter.get('year')}`;
    wilayahRekapUrl = ``;

    if (formFilter.get('kelurahan')) {
        ketFilter  += `${formFilter.get('kelurahan')}, `;
        rekapTUrl  += `&kelurahan=${formFilter.get('kelurahan')}`;
        wilayahRekapUrl += `kelurahan=${formFilter.get('kelurahan')}&`;
    }
    if (formFilter.get('kecamatan')) {
        ketFilter  += `${formFilter.get('kecamatan')}, `;
        rekapTUrl  += `&kecamatan=${formFilter.get('kecamatan')}`;
        wilayahRekapUrl += `kecamatan=${formFilter.get('kecamatan')}&`;
    }
    if (formFilter.get('kota')) {
        ketFilter  += `${formFilter.get('kota')}, `;
        rekapTUrl  += `&kota=${formFilter.get('kota')}`;
        wilayahRekapUrl += `kota=${formFilter.get('kota')}&`;
    }
    if (formFilter.get('provinsi')) {
        ketFilter  += `${formFilter.get('provinsi')}`
        rekapTUrl  += `&provinsi=${formFilter.get('provinsi')}`
        wilayahRekapUrl += `provinsi=${formFilter.get('provinsi')}`;
    }
    if (formFilter.get('provinsi') == '') {
        ketFilter  = `${formFilter.get('year')} - semua wilayah`;
        rekapTUrl  = `${APIURL}/transaksi/rekapdata?year=${formFilter.get('year')}`;
        wilayahRekapUrl = ``;
    }

    $('#ket-filter-rekap-transaksi').html(ketFilter);
    getRekapTransaksi();
};

// do filter rekap
const cetakCustomRekap = () => {
    let formFilter      = new FormData(document.querySelector('#formFilterRekap-custom'));
    let inputStartDate  = formFilter.get('date-start').split('-');
    let inputEndDate    = formFilter.get('date-end').split('-');
    let jenis           = formFilter.get('jenis');
    
    let start = `${inputStartDate[2]}-${inputStartDate[1]}-${inputStartDate[0]}`;
    let end   = `${inputEndDate[2]}-${inputEndDate[1]}-${inputEndDate[0]}`;
    let wilayah = "&wilayah=false";

    let customRekapTUrl = `${BASEURL}/transaksi/cetakrekap/${jenis}?start=${start}&end=${end}`;

    if (formFilter.get('kelurahan')) {
        wilayah = "&wilayah=true";
        customRekapTUrl  += `&kelurahan=${formFilter.get('kelurahan')}`;
    }
    if (formFilter.get('kecamatan')) {
        wilayah = "&wilayah=true";
        customRekapTUrl  += `&kecamatan=${formFilter.get('kecamatan')}`;
    }
    if (formFilter.get('kota')) {
        wilayah = "&wilayah=true";
        customRekapTUrl  += `&kota=${formFilter.get('kota')}`;
    }
    if (formFilter.get('provinsi')) {
        wilayah = "&wilayah=true";
        customRekapTUrl  += `&provinsi=${formFilter.get('provinsi')}`
    }

    window.open(customRekapTUrl+wilayah, '_blank');
};

// reset filter rekap
const resetFilterRekap = (event = null,year = null) => {
    if (event) {
        event.preventDefault(event);
    }

    if (year) {
        $('#modalFilterRekapTransaksi select[name=year]').val(year);
    }
    else{
        $('#modalFilterRekapTransaksi select[name=year]').val(new Date().getFullYear());
    }

    $('#modalFilterRekapTransaksi select[name=orderby]').val('terbaru');
    $('#modalFilterRekapTransaksi select[name=provinsi]').val('');
    $('#modalFilterRekapTransaksi select[name=kota]').val('');
    $('#modalFilterRekapTransaksi select[name=kota]').attr('disabled',true);
    $('#modalFilterRekapTransaksi select[name=kecamatan]').val('');
    $('#modalFilterRekapTransaksi select[name=kecamatan]').attr('disabled',true);
    $('#modalFilterRekapTransaksi select[name=kelurahan]').val('');
    $('#modalFilterRekapTransaksi select[name=kelurahan]').attr('disabled',true);
};

// Get rekap transaksi
let rekapTUrl       = `${APIURL}/transaksi/rekapdata?year=${new Date().getFullYear()}`;
let wilayahRekapUrl = '';
const getRekapTransaksi = async () => {
    $('#table-rekap-transaksi tbody').html('');
    $('#rekap-transaksi-spinner').removeClass('d-none');
    $('#rekap-transaksi-notfound').addClass('d-none'); 
    let httpResponse = await httpRequestGet(rekapTUrl);
    $('#rekap-transaksi-spinner').addClass('d-none'); 
    
    if (httpResponse.status === 404) {
        $('#rekap-transaksi-notfound').removeClass('d-none'); 
        $('#rekap-transaksi-notfound').html(`<h6 class='opacity-6'>belum ada transaksi</h6>`); 
    }
    else if (httpResponse.status === 200) {
        let no           = 0;
        let trTransaksi  = '';
        let allTransaksi = httpResponse.data.data;

        for (const key in allTransaksi) {
            trTransaksi  += `<tr>
                <td class="align-middle text-sm text-center" style="border-right: 0.5px solid rgba(222, 226, 230, 0.6);">
                    <span class="text-xs font-weight-bold">
                        ${++no}
                    </span>
                </td>
                <td class="align-middle text-center">
                    <a href="" target="_blank" class="badge badge-dark text-xxs pb-1 cursor-pointer" style="border-radius:4px;"  data-toggle="modal" data-target="#modalJenisLaporan" onclick="openModalJenisLaporan('${allTransaksi[key].date1}');">
                        cetak
                    </a>
                </td>
                <td class="align-middle text-sm text-center" style="border-right: 0.5px solid rgba(222, 226, 230, 0.6);">
                    <span class="text-xs text-name font-weight-bold">
                        ${allTransaksi[key].date2}
                    </span>
                </td>
                <td class="py-3 align-middle text-sm text-center" style="border-right: 0.5px solid rgba(222, 226, 230, 0.6);">
                    <span class="text-xs text-name font-weight-bold">
                        <i class="fas fa-trash text-xs text-success mr-1"></i>
                        <span class="text-success">
                            ${parseFloat(allTransaksi[key].totSampahMasuk).toFixed(2)} kg
                        </span>
                    </span>
                </td>
                <td class="py-3 align-middle text-sm text-center" style="border-right: 0.5px solid rgba(222, 226, 230, 0.6);">
                    <span class="${(wilayahRekapUrl != '') ? 'd-none' : '' } text-xs text-name font-weight-bold">
                        <i class="fas fa-trash text-xs text-info mr-1"></i>
                        <span class="text-info">
                            ${parseFloat(allTransaksi[key].totSampahKeluar).toFixed(2)} kg
                        </span>
                    </span>
                </td>
                <td class="align-middle text-sm text-center" style="border-right: 0.5px solid rgba(222, 226, 230, 0.6);">
                    <span class="text-xs text-name font-weight-bold text-success">
                        Rp ${kFormatter(allTransaksi[key].totUangMasuk)}
                    </span>
                </td>
                <td class="align-middle text-sm text-center" style="border-right: 0.5px solid rgba(222, 226, 230, 0.6);">
                    <span class="text-xs text-name font-weight-bold text-danger">
                        Rp ${kFormatter(allTransaksi[key].totUangKeluar)}
                    </span>
                </td>
                <td class="align-middle text-sm text-center" style="border-right: 0.5px solid rgba(222, 226, 230, 0.6);letter-spacing:0.5px;color:orange;">
                    <span class="text-xs text-name font-weight-bold">
                        <i class="fas fa-coins text-xs mr-1"></i>
                        ${parseFloat(allTransaksi[key].totEmasMasuk).toFixed(2)}  g
                    </span>
                </td>
                <td class="align-middle text-sm text-center" style="border-right: 0.5px solid rgba(222, 226, 230, 0.6);">
                    <span class="text-xs text-name font-weight-bold text-danger">
                        <i class="fas fa-coins text-xs mr-1"></i>
                        ${parseFloat(allTransaksi[key].totEmasKeluar).toFixed(2)} g
                    </span>
                </td>
            </tr>`;
        }
        
        $('#table-rekap-transaksi tbody').html(trTransaksi);
    }
};
getRekapTransaksi();

// open modal jenis laporan
const openModalJenisLaporan = (date) => {
    document.querySelector('#formJenisLaporan input[name=date]').value = date;
}

// OnSubmit: Form Jenis Laporan
document.querySelector("#formJenisLaporan").addEventListener('submit', function (e) {
    e.preventDefault()

    let form = new FormData(e.target);

    let date = form.get('date');
    let jenis = form.get('jenis');

    $('#formJenisLaporan input[name=jenis]').removeClass('is-invalid');

    if (jenis == "") {
        showAlert({
            message: `<strong>Warning!!</strong> pilih jenis laporan !!`,
            autohide: true,
            type:'warning'
        })
        return 0;
    }

    window.open(`${BASEURL}/transaksi/cetakrekap/${jenis}?date=${date}&wilayah=${wilayahRekapUrl != "" ? 'true' : ''}&${wilayahRekapUrl}`,'_blank')
})