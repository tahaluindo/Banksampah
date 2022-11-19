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
	</style>
  	<!-- ** develoment ** -->
	<!-- <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>"> -->
	<!-- ** production ** -->
	<link rel="stylesheet" href="<?= base_url('assets/css/purge/bootstrap/admin.listadmin.css'); ?>">
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
	<script src="<?= base_url('assets/js/admin.listadmin.min.js'); ?>"></script>
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
							<li class="breadcrumb-item text-sm text-dark active" aria-current="page">List admin</li>
						</ol>
						<h6 class="font-weight-bolder mb-0">List admin</h6>
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
							<div class="card-header form-row pb-0 d-flex justify-content-between" style="font-family: 'qc-semibold';">
								<div class="input-group col-12 col-sm-6">
									<div class="input-group-prepend">
										<span class="input-group-text bg-gray px-4 border-md border-right-0" style="max-height: 39px;">
											<i class="fas fa-search text-muted"></i>
										</span>
									</div>
									<input id="search-admin" type="text" class="form-control h-100 px-2" placeholder="username/nama lengkap" style="max-height: 39px;">
								</div>
								<div class="input-group col-12 col-sm-1 p-0" style="min-width: 90px;">
									<button class="btn btn-success mt-4 mt-sm-0 text-xxs" data-toggle="modal" data-target="#modalAddEditAdmin" onclick="openModalAddEditAdm('addadmin')" style="width: 100%;">tambah</button>
								</div>
							</div>
							<!-- container table -->
							<div class="card-body table-responsive px-0 pt-0 pb-2 position-relative" style="flex: 1;overflow: auto;font-family: 'qc-semibold';">
								<!-- spinner -->
								<div id="list-admin-spinner" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
									<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
								</div>
								<!-- message not found -->
								<div id="list-admin-notfound" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
									<h6 id="text-notfound" class='opacity-6'></h6>
								</div>
								<!-- table -->
								<table id="table-admin" class="table table-striped text-center mb-0">
									<thead class="position-sticky bg-white" style="z-index: 11;top: 0;">
										<tr>
											<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												#
											</th>
											<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												username
											</th>
											<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												Nama lengkap
											</th>
											<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												Type admin
											</th>
											<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												Akun aktif
											</th>
											<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
												terakhir login
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

	<!-- modals Add / Edit admin -->
	<div class="modal fade" id="modalAddEditAdmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<form id="formAddEditAdmin" class="modal-dialog modal-dialog-centered" role="document" onsubmit="crudAdmin(this,event);">
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
					<div id="list-admin-spinner" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
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
					<!-- **** username **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
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
					<!-- **** password **** -->
					<div class="addadmin-item input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray border-md" style="padding-left: 1.66rem;padding-right: 1.66rem;">
									<i class="fa fa-lock text-muted"></i>
								</span>
							</div>
							<input type="password" class="form-control px-2" id="password" name="password" autocomplete="off" placeholder="masukan password">
						</div>
						<small
							id="password-error"
							class="text-danger"></small>
					</div>
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
					<!-- **** superadmin **** -->
					<div class="mb-3">
						<label class="form-check-label">
							super admin
						</label>
						<div class="mt-2 position-relative p-0 d-flex align-items-center" style="border-radius: 14px;width: 50px;height: 25px;box-shadow: inset 0 0 4px 0px rgba(0, 0, 0, 0.4);">
							<div class="btn-toggle toggle-privilege bg-secondary rounded-circle position-absolute" style="width: 25px;height: 25px;">
								<input type="checkbox" name="privilege" class="cursor-pointer" style="width: 25px;height: 25px;opacity: 0;">
							</div>
						</div>
					</div>
					<!-- **** akun aktif **** -->
					<div class="editadmin-item mb-3">
						<label class="form-check-label">
							akun aktif
						</label>
						<div class="mt-2 position-relative p-0 d-flex align-items-center" style="border-radius: 14px;width: 50px;height: 25px;box-shadow: inset 0 0 4px 0px rgba(0, 0, 0, 0.4);">
							<div class="btn-toggle toggle-akunaktif bg-secondary rounded-circle position-absolute" style="width: 25px;height: 25px;">
								<input type="checkbox" name="is_active" class="cursor-pointer" style="width: 25px;height: 25px;opacity: 0;">
							</div>
						</div>
					</div>

					<hr class="horizontal dark mt-2 mb-2">
					<h6 class="font-italic opacity-8">data opsionial</h6>

					<!-- **** alamat **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray border-md" style="padding-left: 1.66rem;padding-right: 1.66rem;">
									<i class="fas fa-map-marker-alt text-muted"></i>
								</span>
							</div>
							<input type="text" class="form-control px-2" id="alamat" name="alamat" autocomplete="off" placeholder="Masukan alamat lengkap">
						</div>
						<small
							id="alamat-error"
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
					<!-- **** change password **** -->
					<div class="editadmin-item input-group col-lg-12 mt-2 mb-4 form-group">
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