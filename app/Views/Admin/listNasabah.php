<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
		html,body{
			height:100%;
		}

		.btn-toggle{
			left: 0;
			transition: all 0.3s;
			transform: translateX(0px);
		}

		.btn-toggle.active{
			/* left: auto !important;
			right: 0 !important; */
			transform: translateX(25px);
		}

		#search-kodepos::placeholder {
			color: #ccc;
			font-weight: lighter;
			font-size: 14px;
		}

		.kodepos-list:hover{
			background-color: rgba(233, 236, 239, 0.4) !important;
		}

		.kodepos-list.active{
			background-color: #E9ECEF !important;
		}
	</style>
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/soft-ui-dashboard.min.css'); ?>">
<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->section('contentJs'); ?>
	<script>
		const PASSADMIN = '<?= $password; ?>';
	</script>
	<script src="<?= base_url('assets/js/core/jquery-2.1.0.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/core/bootstrap.min.js'); ?>"></script>
  	<script src="<?= base_url('assets/js/plugins/font-awesome.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/parent.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/nikparse.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/admin.listnasabah.min.js'); ?>"></script>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

	<!-- **** Loading Spinner **** -->
	<?= $this->include('Components/loadingSpinner'); ?>
	<!-- **** Alert Info **** -->
	<?= $this->include('Components/alertInfo'); ?>

	<body class="g-sidenav-show bg-gray-100" style="overflow: hidden;">
		<!-- **** Sidebar **** -->
		<?= $this->include('Components/adminSidebar'); ?>

		<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg d-flex flex-column">
			<!-- navbar -->
			<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl position-sticky" style="top: 20px;z-index:100;">
				<div class="container-fluid py-1 px-3">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
							<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
							<li class="breadcrumb-item text-sm text-dark active" aria-current="page">List nasabah</li>
						</ol>
						<h6 class="font-weight-bolder mb-0">List nasabah</h6>
					</nav>
					<div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
						<div class="ms-auto pe-md-3 d-flex align-items-center">
							<ul class="navbar-nav justify-content-end">
								<li class="nav-item d-xl-none ps-3 d-flex align-items-center">
									<a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
										<div class="sidenav-toggler-inner">
											<i class="sidenav-toggler-line"></i>
											<i class="sidenav-toggler-line"></i>
											<i class="sidenav-toggler-line"></i>
										</div>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</nav>

			<!-- End Navbar -->
			<div class="container-fluid py-4 d-flex flex-column" style="flex: 1;max-height: 90%;overflow: hidden;">
				<div class="row" style="flex: 1;max-height: 96%;">
					<div class="col-12 h-100" style="max-height: 100%;">
						<div class="card mb-4 h-100 d-flex flex-column" style="max-height: 100%;overflow: hidden;font-family: 'qc-semibold';">
							<!-- search input -->
							<div class="card-header form-row pb-0 mb-3 d-flex justify-content-between" style="font-family: 'qc-semibold';">
								<div class="input-group col-12 col-md-6 mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text bg-gray px-4 border-md border-right-0" style="max-height: 39px;">
											<i class="fas fa-search text-muted"></i>
										</span>
									</div>
									<input id="search-nasabah" type="text" class="form-control h-100 px-2" placeholder="id/nama lengkap" style="max-height: 39px;">
								</div>
								<div class="input-group col-12 col-md-3">
									<button class="btn btn-success text-xxs" data-toggle="modal" data-target="#modalAddEditNasabah" onclick="openModalAddEditNsb('addnasabah')" style="width: 50%;">tambah</button>
									<a href="<?= base_url('/admin/printlistnasabah') ?>" target="_blank" class="btn btn-primary text-xxs" style="width: 50%;">cetak</a>
								</div>
								<div class="input-group col-12 flex-column text-sm">
									<div class="d-flex align-items-center">
										<a id="btn-edit-profile" class="shadow px-1 border-radius-none mr-2" href="" data-toggle="modal" data-target="#modalFilterNasabah">
											<i class="fas fa-sliders-h text-secondary"></i>
										</a>
										<span id="ket-filter" class=" text-secondary">terbaru - semua wilayah</span>
									</div>
									<div class="mt-2 text-xs text-secondary">
										<span id="ket-total">0</span> nasabah
									</div>
								</div>
							</div>
							<!-- container table -->
							<div class="card-body table-responsive px-0 pt-0 pb-2 position-relative" style="flex: 1;overflow: auto;font-family: 'qc-semibold';">
								<!-- spinner -->
								<div id="list-nasabah-spinner" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
									<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
								</div>
								<!-- message not found -->
								<div id="list-nasabah-notfound" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
									<h6 id="text-notfound" class='opacity-6'></h6>
								</div>
								<!-- table -->
								<table id="table-nasabah" class="table table-striped text-center mb-0">
									<thead class="position-sticky bg-white" style="z-index: 11;top: 0;">
										<tr>
											<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												#
											</th>
											<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												ID Nasabah
											</th>
											<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												Nama lengkap
											</th>
											<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												Ter-verifikasi
											</th>
											<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												Terakhir login
											</th>
											<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												Action
											</th>
										</tr>
									</thead>
									<tbody>
										<?php for ($i=0; $i < 20; $i++) { ?>
											
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- FOOTER -->
				<footer class="footer pt-3  ">
					<div class="container-fluid">
						<div class="row align-items-center justify-content-lg-between">
							<div class="col-lg-6 mb-lg-0 mb-4">
								<div class="copyright text-center text-sm text-muted text-lg-start">
									© <script>
										document.write(new Date().getFullYear())
									</script>,
									Bank Sampah Budi Luhur
								</div>
							</div>
						</div>
					</div>
				</footer>
			</div>
		</main>
	</body>

	<!-- modals filter nasabah-->
	<div class="modal fade" id="modalFilterNasabah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<form id="formFilterNasabah" class="modal-dialog modal-sm" role="document">
			<input type="hidden" name="id">
			<div class="modal-content" style="overflow: hidden;">

				<!-- modal header -->
				<div class="modal-header">
					<h5 class="modal-title"> filter nasabah</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<!-- modal body -->
				<div class="modal-body w-100 px-3">
					<h6 class="font-italic text-xs text-secondary">Urutan</h6>
					<div class="position-relative">
						<select class='form-control form-control-sm' name="orderby">
							<option value="terbaru" selected>terbaru</option>
							<option value="terlama">terlama</option>
						</select>
						<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:6px;right:10px;"></i>
					</div>
					<h6 class="font-italic text-xs text-secondary mt-4">Wilayah</h6>
					<div class="mt-2 position-relative">
						<select class='form-control form-control-sm' name="provinsi">
							<option value="">-- pilih provinsi --</option>
						</select>
						<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:6px;right:10px;"></i>
					</div>
					<div class="mt-2 position-relative">
						<select class='form-control form-control-sm' name="kota" disabled>
							<option value="">-- pilih kota --</option>
						</select>
						<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:6px;right:10px;"></i>
					</div>
					<div class="mt-2 position-relative">
						<select class='form-control form-control-sm' name="kecamatan" disabled>
							<option value="">-- pilih kecamatan --</option>
						</select>
						<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:6px;right:10px;"></i>
					</div>
					<div class="mt-2 position-relative">
						<select class='form-control form-control-sm' name="kelurahan" disabled>
							<option value="">-- pilih kelurahan --</option>
						</select>
						<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:6px;right:10px;"></i>
					</div>
				</div>

				<!-- modal footer -->
				<div class="modal-footer">
					<div class="badge badge-secondary d-flex justify-content-center align-items-center border-0 cursor-pointer" onclick="resetFilterNasabah();">
						<span>Reset</span>
					</div>
					<button id="submit" type="submit" class="badge badge-success d-flex justify-content-center align-items-center border-0" data-dismiss="modal" onclick="filterNasabah(this,event);">
						<span>Ok</span>
					</button>
				</div>
			</div>
		</form>
	</div>

	<!-- modals Add / Edit nasabah -->
	<div class="modal fade" id="modalAddEditNasabah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<form id="formAddEditNasabah" class="modal-dialog modal-dialog-centered" role="document" onsubmit="crudNasabah(this,event);">
			<input type="hidden" name="id">
			<div class="modal-content" style="overflow: hidden;">

				<!-- modal header -->
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<!-- modal body -->
				<div class="modal-body row position-relative">

					<!-- spinner -->
					<div id="list-nasabah-spinner" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
						<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
					</div>

					<input type="hidden" name="id">
					<!-- **** nama lengkap **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fa fa-user text-muted"></i>
								</span>
							</div>
							<input type="text" class="form-control px-2" id="nama" name="nama_lengkap" autocomplete="off" placeholder="Masukan nama lengkap">
						</div>
						<small
							id="nama-error"
							class="text-danger"></small>
					</div>
					<!-- **** email **** -->
					<div class="editnasabah-item editnasabah-item input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
							<span class="input-group-text bg-gray px-4 border-md border-right-0">
								<i class="fa fa-envelope text-muted"></i>
							</span>
							</div>
							<input type="text" class="form-control px-2" id="email" name="email" autocomplete="off" placeholder="Masukan email">
						</div>
						<small
							id="email-error"
							class="text-danger"></small>
					</div>
					<!-- **** username **** -->
					<div class="editnasabah-item input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-at text-muted"></i>
								</span>
							</div>
							<input type="text" class="form-control px-2" id="username" name="username" autocomplete="off" placeholder="Masukan username">
						</div>
						<small
							id="username-error"
							class="text-danger"></small>
					</div>
					<!-- kelamin -->
					<input type="hidden" name="kelamin">
					<div class="input-group col-lg-6 mb-2 form-group">
						<div class="form-check">
							<input class="form-check-input" type="radio" id="kelamin-laki-laki" value="laki-laki" />
							<label class="form-check-label" for="kelamin-laki-laki">
							Laki Laki
							</label>
						</div>
					</div>
					<div class="input-group col-lg-6 mb-4 form-group">
						<div class="form-check">
							<input class="form-check-input" type="radio" id="kelamin-perempuan" value="perempuan" />
							<label class="form-check-label" for="kelamin-perempuan">
							Perempuan
							</label>
						</div>
					</div>
					<!-- **** RT RW KODEPOS **** -->
					<div class="addnasabah-item form-row mb-4" style="padding-right: 2px;">
						<div class="col-6">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-gray px-4 border-md border-right-0">
										<i class="fas fa-home text-muted"></i>
									</span>
								</div>
								<input type="text" class="form-control px-2" id="rt" name="rt" autocomplete="off" placeholder="RT: 001">
							</div>
							<small
								id="rt-error"
								class="text-danger"></small>
						</div>
						<div class="col-6">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-gray px-4 border-md border-right-0">
										<i class="fas fa-home text-muted"></i>
									</span>
								</div>
								<input type="text" class="form-control px-2" id="rw" name="rw" autocomplete="off" placeholder="RW: 002">
							</div>
							<small
								id="rw-error"
								class="text-danger"></small>
						</div>
						<!-- **** kode pos **** -->
						<div class="input-group col-12 mt-4">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-gray px-4 border-md border-right-0">
										<i class="fas fa-mail-bulk text-muted"></i>
									</span>
								</div>
								<input type="text" class="form-control px-2" id="kodepos" name="kodepos" autocomplete="off" placeholder="KODE POS (pilih dibawah)" disabled>
							</div>
							<small
								id="kodepos-error"
								class="text-danger"></small>
						</div>
						<!-- LIST kodepos -->
						<input type="hidden" name="kelurahan">
						<input type="hidden" name="kecamatan">
						<input type="hidden" name="kota">
						<input type="hidden" name="provinsi">
						<div class="input-group col-lg-12 mt-4 form-group">
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
					</div>
					<!-- **** is verify **** -->
					<div class="editnasabah-item mb-3">
						<label class="form-check-label">
							verifikasi akun
						</label>
						<div class="mt-2 position-relative p-0 d-flex align-items-center" style="border-radius: 14px;width: 50px;height: 25px;box-shadow: inset 0 0 4px 0px rgba(0, 0, 0, 0.4);">
							<div class="btn-toggle toggle-akunverify bg-secondary rounded-circle position-absolute" style="width: 25px;height: 25px;">
								<input type="checkbox" name="is_verify" class="cursor-pointer" style="width: 25px;height: 25px;opacity: 0;">
							</div>
						</div>
					</div>

					<hr class="horizontal dark mb-2">
					<h6 class="font-italic opacity-8 mb-2">data opsionial</h6>

					<!-- **** tgl lahir **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-calendar-alt text-muted"></i>
								</span>
							</div>
							<input type="date" class="form-control px-2 h-100" id="tgllahir" name="tgl_lahir">
						</div>
						<small
							id="tgllahir-error"
							class="text-danger"></small>
					</div>
					<!-- **** no telp **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray border-md" style="padding-left: 1.66rem;padding-right: 1.66rem;">
									<i class="fa fa-phone-square text-muted"></i>
								</span>
							</div>
							<input type="text" class="form-control px-2" id="notelp" name="notelp" autocomplete="off" placeholder="Masukan no.telp">
						</div>
						<small
							id="notelp-error"
							class="text-danger"></small>
					</div>
					<!-- **** nik **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray border-md" style="padding-left: 1.66rem;padding-right: 1.66rem;">
									<i class="fa fa-id-card text-muted"></i>
								</span>
							</div>
							<input type="text" class="form-control px-2" id="nik" name="nik" autocomplete="off" placeholder="Masukan NIK nasabah">
						</div>
						<small
							id="nik-error"
							class="text-danger"></small>
					</div>
					<!-- **** alamat **** -->
					<div class="input-group col-12 mb-4">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray border-md" style="padding-left: 1.66rem;padding-right: 1.66rem;">
									<i class="fas fa-map-marker-alt text-muted"></i>
								</span>
							</div>
							<input type="text" class="form-control px-2" id="alamat" name="alamat" autocomplete="off" placeholder="Detil alamat lengkap">
						</div>
						<small
							id="alamat-error"
							class="text-danger"></small>
					</div>
					<!-- **** change password **** -->
					<div class="editnasabah-item input-group col-lg-12 mt-2 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray border-md" style="padding-left: 1.66rem;padding-right: 1.66rem;">
									<i class="fa fa-lock text-muted"></i>
								</span>
							</div>
							<input type="password" class="form-control px-2" id="newpass" name="new_password" autocomplete="off" placeholder="password baru">
						</div>
						<small
							id="newpass-error"
							class="text-danger"></small>
					</div>
				</div>

				<!-- modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
					<button id="submit" type="submit" class="btn btn-success d-flex justify-content-center align-items-center" style="height: 40.8px;">
						<span id="text">Simpan</span>
						<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 20px;">
					</button>
				</div>
			</div>
		</form>
	</div>
<?= $this->endSection(); ?>