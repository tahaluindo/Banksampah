<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
    <style>
      .form-control::placeholder {
        color: #ccc;
        font-weight: lighter;
        font-size: 0.9rem;
      }

      #search-kodepos::placeholder {
        color: #ccc;
        font-weight: lighter;
        font-size: 14px;
      }

      .kodepos-list:hover{
        /* background-color: #E9ECEF !important; */
      }

      .kodepos-list.active{
        background-color: #E9ECEF !important;
      }

      @media (max-width:768px) {
        #img-phone {
          display: none;
        }
      } 
    </style>
  	<!-- ** develoment ** -->
    <!-- <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>"> -->
    <!-- ** production ** -->
	  <link rel="stylesheet" href="<?= base_url('assets/css/purge/bootstrap/signup.css'); ?>">
<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->section('contentJs'); ?>
  	<script src="<?= base_url('assets/js/plugins/font-awesome.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/nikparse.min.js'); ?>"></script>
    <!-- <script src="<?= base_url('assets/js/registerNasabah.js'); ?>"></script> -->
    <script src="<?= base_url('assets/js/registerNasabah.min.js'); ?>"></script>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

  <!-- **** Loading Spinner **** -->
  <?= $this->include('Components/loadingSpinner'); ?>
  <!-- **** Alert Info **** -->
  <?= $this->include('Components/alertInfo'); ?>

  <div class="container">
    <div class="row py-5 mt-4 align-items-center">

      <!-- **** side left **** -->
      <div class="col-md-5 pr-lg-5 mb-5 mb-md-0 text-center">
        <img 
          id="img-phone"
          src="<?= base_url('assets/images/left-image.webp'); ?>" 
          alt="banksampah budiluhur apk" class="img-fluid mb-3" />
        <h1>Buat Akun Bank Sampah</h1>
        <p class="font-italic text-muted mb-0">
          Mari bergabung bersama kami demi lingkungan yang lebih baik
        </p>
      </div>

      <!-- **** right left **** -->
      <div class="col-md-7 col-lg-6 ml-auto">
        <form id="formRegister">
          <div class="row">
            <!-- **** nama lengkap **** -->
            <div class="input-group col-lg-12 mb-4 form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span 
                    class="input-group-text bg-gray px-4 border-md border-right-0" style="border-width: 2px;max-width: 62px;">
                      <i class="fa fa-user text-muted"></i>
                  </span>
                </div>
                <input id="nama-regist" type="text" class="form-control pb-4 pt-4" name="nama_lengkap" autocomplete="off" placeholder="Masukan nama lengkap anda">
              </div>
              <small id="nama-regist-error" class="text-danger"></small>
            </div>

            <!-- **** username **** -->
            <div class="input-group col-lg-12 mb-4 form-group">
              <div class="input-group">
                <div class="input-group-prepend" style="max-width: 64px;">
                  <span 
                   class="input-group-text bg-gray px-4 border-md border-right-0" style="border-width: 2px;max-width: 62px;">
                      <i class="fas fa-at text-muted"></i>
                  </span>
                </div>
                <input id="username-regist" type="text" class="form-control pb-4 pt-4" name="username" autocomplete="off" placeholder="Masukan username anda">
              </div>
              <small id="username-regist-error" class="text-danger"></small>
            </div>

            <!-- **** email **** -->
            <div class="input-group col-lg-12 mb-4 form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span 
                    class="input-group-text bg-gray px-4 border-md border-right-0" style="border-width: 2px;max-width: 62px;">
                      <i class="fa fa-envelope text-muted"></i>
                  </span>
                </div>
                <input id="email-regist" type="text" class="form-control pb-4 pt-4" name="email" autocomplete="off" placeholder="Masukan email anda">
              </div>
              <small id="email-regist-error" class="text-danger"></small>
            </div>

            <!-- **** password **** -->
            <div class="input-group col-lg-12 mb-4 form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span 
                    class="input-group-text bg-gray px-4 border-md border-right-0" style="border-width: 2px;max-width: 62px;">
                      <i class="fa fa-lock text-muted"></i>
                  </span>
                </div>
                <input id="password-regist" type="password" class="form-control pb-4 pt-4" name="password" autocomplete="off" placeholder="Masukan password">
              </div>
              <small id="password-regist-error" class="text-danger"></small>
            </div>

            <!-- **** no telp **** -->
            <div class="input-group col-lg-12 mb-4 form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span 
                    class="input-group-text bg-gray px-4 border-md border-right-0" style="border-width: 2px;">
                      <i class="fa fa-phone-square"></i>
                  </span>
                </div>
                <input id="notelp-regist" type="text" class="form-control pb-4 pt-4" name="notelp" autocomplete="off" placeholder="Masukan no.telp anda">
              </div>
              <small id="notelp-regist-error" class="text-danger"></small>
            </div>

            <!-- **** NIK **** -->
            <div class="input-group col-lg-12 mb-4 form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span 
                    class="input-group-text bg-gray px-4 border-md border-right-0" style="border-width: 2px;">
                      <i class="fa fa-id-card"></i>
                  </span>
                </div>
                <input id="nik-regist" type="text" class="form-control pb-4 pt-4" name="nik" autocomplete="off" placeholder="Masukan Nomor induk KTP anda">
              </div>
              <small id="nik-regist-error" class="text-danger"></small>
            </div>

            <!-- **** tgl lahir **** -->
            <div class="input-group col-lg-12 mb-4 form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span 
                    class="input-group-text bg-gray px-4 border-md border-right-0" style="border-width: 2px;max-width: 62px;">
                      <i class="fas fa-calendar-alt"></i>
                  </span>
                </div>
                <input id="tgllahir-regist" type="date" class="form-control h-100 pb-3 pt-3" name="tgl_lahir" style="max-height: 50px;">
              </div>
              <small id="tgllahir-regist-error" class="text-danger"></small>
            </div>

            <!-- **** kelamin laki **** -->
            <div class="input-group col-lg-6 mb-4 form-group">
              <div class="form-check">
                <input id="kelamin-laki" class="form-check-input" type="radio" name="kelamin" value="laki-laki" />
                <label class="form-check-label" for="kelamin-laki">
                  Laki Laki
                </label>
              </div>
            </div>

            <!-- **** kelamin perempuan **** -->
            <div class="input-group col-lg-6 mb-4 form-group">
              <div class="form-check">
                <input id="kelamin-perempuan" class="form-check-input" type="radio" name="kelamin" value="perempuan" />
                <label class="form-check-label" for="kelamin-perempuan">
                  Perempuan
                </label>
              </div>
            </div>

            <!-- **** RT **** -->
            <div class="input-group col-lg-6 mb-4 form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span 
                    class="input-group-text bg-gray px-4 border-md border-right-0" style="max-height: 50px;max-width: 62px;border-width: 2px;">
                      <i class="fas fa-home"></i>
                  </span>
                </div>
                <input id="rt-regist" type="text" class="form-control pb-4 pt-4" name="rt" autocomplete="off" placeholder="RT: 001">
              </div>
              <small id="rt-regist-error" class="text-danger"></small>
            </div>

            <!-- **** RW **** -->
            <div class="input-group col-lg-6 mb-4 form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span 
                    class="input-group-text bg-gray px-4 border-md border-right-0" style="max-height: 50px;max-width: 62px;border-width: 2px;">
                      <i class="fas fa-home"></i>
                  </span>
                </div>
                <input id="rw-regist" type="text" class="form-control pb-4 pt-4" name="rw" autocomplete="off" placeholder="RW: 001">
              </div>
              <small id="rw-regist-error" class="text-danger"></small>
            </div>

            <!-- **** alamat **** -->
            <div class="input-group col-lg-12 mb-4 form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span 
                    class="input-group-text bg-gray px-4 border-md border-right-0" style="border-width: 2px;">
                      <i class="fas fa-map-marker-alt"></i>
                  </span>
                </div>
                <input id="alamat-regist" type="text" class="form-control pb-4 pt-4" name="alamat" autocomplete="off" placeholder="Masukan alamat lengkap anda">
              </div>
              <small id="alamat-regist-error" class="text-danger"></small>
            </div>

            <!-- **** CODE POS **** -->
            <div class="input-group col-lg-12 mb-3 form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span 
                    class="input-group-text bg-gray px-4 border-md border-right-0" style="border-width: 2px;max-width: 62px;">
                      <i class="fas fa-mail-bulk text-muted"></i>
                  </span>
                </div>
                <input id="kodepos-regist" type="text" class="form-control pb-4 pt-4" name="kodepos" autocomplete="off" placeholder="KODE POS (pilih kodepos dibawah)" disabled>
              </div>
              <small id="kodepos-regist-error" class="text-danger"></small>
            </div>

            <!-- LIST kodepos -->
            <input type="hidden" name="kelurahan">
            <input type="hidden" name="kecamatan">
            <input type="hidden" name="kota">
            <input type="hidden" name="provinsi">
            <div class="input-group col-lg-12 mb-4 form-group">
              <div class="container-fluid border-radius-sm p-2" style="border: 0.5px solid #D2D6DA;">
                <!-- header -->
                <div class="add-item container-fluid input-group mb-2 d-flex p-0">
                  <div class="input-group-prepend">
                    <span class="input-group-text d-flex justify-content-center p-0 bg-gray border-md border-right-0" style="width: 52px;max-height: 39px;">
                      <i class="fas fa-search text-muted"></i>
                    </span>
                  </div>
                  <input id="search-kodepos" type="text" class="form-control px-2 text-xxs border-radius-sm" placeholder="ketik provinsi/kota/kecamatan/kelurahan" style="max-height: 30px;border: 0.5px solid #D2D6DA;" autocomplete="off" onkeyup="searchKodepos(this);">
                </div>
                <!-- body -->
                <div id="kodepos-wraper" class="container-fluid border-radius-sm p-0 position-relative" style="min-height: 150px;max-height: 150px;overflow: auto;border: 0.5px solid #D2D6DA;">
                  
                </div>
              </div>
            </div>

            <!-- **** BUTTON **** -->
            <div class="form-group col-lg-12 mx-auto mb-0">
              <button type="submit" class="btn btn-success btn-block py-2 btn-costum border-0" style="background: #537629;">
                <span class="font-weight-bold">Buat Akun</span>
              </button>
            </div>
            <div class="text-center w-100 mt-4">
              <p class="text-muted font-weight-bold">
                Apakah Sudah Memiliki Akun? 
                <a href="login" class="text-primary ml-2">Login</a>
              </p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?= $this->endSection(); ?>