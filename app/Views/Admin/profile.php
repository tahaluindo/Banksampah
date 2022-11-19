<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
		@media (max-width: 640px) {
			h4.text-responsive{
				font-size: 18px;
			}
			h5.text-responsive{
				font-size: 16px !important;
			}
			p.text-responsive{
				font-size: 12px !important;
			}
			table td{
				display: block;
			}
			table td.label{
				margin-top: 16px;
			}
			table td.main{
				border-left: 3px solid #c1d966;
				padding: 6px 10px;
			}
			table td span.titik{
				display: none;
			}
		} 
	</style>
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/soft-ui-dashboard.min.css'); ?>">
<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->section('contentJs'); ?>
	<script src="<?= base_url('assets/js/core/jquery-2.1.0.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/core/bootstrap.min.js'); ?>"></script>
  	<script src="<?= base_url('assets/js/plugins/font-awesome.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/parent.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/admin.profile.min.js'); ?>"></script>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

	<!-- **** Loading Spinner **** -->
	<?= $this->include('Components/loadingSpinner'); ?>
	<!-- **** Alert Info **** -->
	<?= $this->include('Components/alertInfo'); ?>

	<body class="g-sidenav-show bg-gray-100">
		<!-- **** Sidebar **** -->
		<?= $this->include('Components/adminSidebar'); ?>
		
		<div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
			<!-- Navbar -->
			<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl position-sticky" style="top: 20px;z-index:100;">
				<div class="container-fluid py-1 px-3">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
							<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
							<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Profile</li>
						</ol>
						<h6 class="font-weight-bolder mb-0">Profile</h6>
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
			<div class="container-fluid">
				<div class="page-header min-height-300 border-radius-xl mt-4"
					style="background-image: url(<?= base_url('assets/images/curved-images/curved14.jpg');?>); background-position-y: 50%;">
					<span class="mask bg-gradient-primary opacity-6"></span>
				</div>
				<div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
					<div class="gx-4" style="width: max-content;">
						<div class="text-center">
							<div class="avatar avatar-xl position-relative">
								<img src="<?= base_url('assets/images/default-profile.webp');?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
							</div>
							<p class="mt-2 mb-0 font-weight-bold text-sm" style="font-family: 'qc-medium';">
								id: <span id="idadmin">_ _ _ _</span>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="container-fluid py-4">
				<div class="row">
					<div class="col-12">
						<div class="card h-100 px-2 position-relative">
							<!-- header -->
							<div class="card-header pb-0 p-3 position-relative" style="z-index: 11;">
								<div class="row">
									<div class="opacity-8 col-9 mt-1">
										<h4 class="text-responsive" style="font-family: 'qc-medium';">Personal information</h4 >
									</div>
									<div class="col-3 text-end">
										<a id="btn-edit-profile" class="shadow p-2  border-radius-sm" href="" data-toggle="modal" data-target="#modalEditProfile">
											<i class="fas fa-user-edit text-secondary text-sm" title="Edit Profile"></i>
										</a>
									</div>
								</div>
							</div>
							<!-- spinner -->
							<div id="profile-spinner" class="position-absolute bg-white d-flex align-items-center justify-content-center border-radius-lg" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
								<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
							</div>
							<div class="table-responsive card-body mt-4 p-3">
								<table class="" style="font-family: 'qc-medium';min-width: min-content;">
									<tr class='text-responsive'>
										<td class="py-2 label" style="font-family: 'qc-semibold';">
											<strong>Nama lengkap</strong>
										</td>
										<td class="main">
											<span class="titik">:&nbsp;&nbsp;&nbsp;</span>
											<span id="nama-lengkap" class="text-capitalize">_ _ _ _ _</span>
										</td>
									</tr>
									<tr class='text-responsive'>
										<td class="py-2 label" style="font-family: 'qc-semibold';">
											<strong>Username</strong>
										</td>
										<td class="main"> 
											<span class="titik">:&nbsp;&nbsp;&nbsp;</span>
											<span id="username">_ _ _ _ _</span>
										</td>
									</tr>
									<tr class='text-responsive'>
										<td class="py-2 label" style="font-family: 'qc-semibold';">
											<strong>Tanggal lahir</strong>
										</td>
										<td class="main"> 
											<span class="titik">:&nbsp;&nbsp;&nbsp;</span>
											<span id="tgl-lahir">_ _ _ _ _</span>
										</td>
									</tr>
									<tr class='text-responsive'>
										<td class="py-2 label" style="font-family: 'qc-semibold';">
											<strong>Jenis kelamin</strong>
										</td>
										<td class="main"> 
											<span class="titik">:&nbsp;&nbsp;&nbsp;</span>
											<span id="kelamin">_ _ _ _ _</span>
										</td>
									</tr>
									<tr class='text-responsive'>
										<td class="py-2 label" style="font-family: 'qc-semibold';">
											<strong>Alamat</strong>
										</td>
										<td class="main"> 
											<span class="titik">:&nbsp;&nbsp;&nbsp;</span>
											<span id="alamat">_ _ _ _ _</span>
										</td>
									</tr>
									<tr class='text-responsive'>
										<td class="py-2 label" style="font-family: 'qc-semibold';">
											<strong>No.telepon</strong>
										</td>
										<td class="main"> 
											<span class="titik">:&nbsp;&nbsp;&nbsp;</span>
											<span id="notelp">_ _ _ _ _</span>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>

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
		</div>

		<!-- Edit profile -->
		<div class="modal fade" id="modalEditProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<form id="formEditProfile" class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Edit profile</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body row">
					<!-- **** nama lengkap **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fa fa-user text-muted"></i>
								</span>
							</div>
							<input type="text" class="form-control px-2" id="nama-edit" name="nama_lengkap" autocomplete="off" placeholder="Masukan nama lengkap anda">
						</div>
						<small
							id="nama-edit-error"
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
							<input type="text" class="form-control px-2" id="username-edit" name="username" autocomplete="off" placeholder="Masukan username anda">
						</div>
						<small
							id="username-edit-error"
							class="text-danger"></small>
					</div>
					<!-- **** tgl lahir **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-calendar-alt"></i>
								</span>
							</div>
							<input type="date" class="form-control px-2 h-100" id="tgllahir-edit" name="tgl_lahir">
						</div>
						<small
							id="tgllahir-edit-error"
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
					<!-- **** alamat **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray border-md" style="padding-left: 1.66rem;padding-right: 1.66rem;">
									<i class="fas fa-map-marker-alt"></i>
								</span>
							</div>
							<input type="text" class="form-control px-2" id="alamat-edit" name="alamat" autocomplete="off" placeholder="Masukan alamat lengkap anda">
						</div>
						<small
							id="alamat-edit-error"
							class="text-danger"></small>
					</div>
					<!-- **** no telp **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray border-md" style="padding-left: 1.66rem;padding-right: 1.66rem;">
								<i class="fa fa-phone-square"></i>
							</span>
							</div>
							<input type="text" class="form-control px-2" id="notelp-edit" name="notelp" autocomplete="off" placeholder="Masukan no.telp anda">
						</div>
						<small
							id="notelp-edit-error"
							class="text-danger"></small>
					</div>
					<hr class="horizontal dark mt-2 mb-2">
					<h6 class="font-italic opacity-8">Ubah password (opsionial)</h6>
					<!-- **** change password **** -->
					<div class="input-group col-lg-12 mt-2 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray border-md" style="padding-left: 1.66rem;padding-right: 1.66rem;">
									<i class="fa fa-lock text-muted"></i>
								</span>
							</div>
							<input type="password" class="form-control px-2" id="newpass-edit" name="new_password" autocomplete="off" placeholder="password baru">
						</div>
						<small
							id="newpass-edit-error"
							class="text-danger"></small>
					</div>
					<div class="input-group col-lg-12 mb-4 form-group">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray border-md" style="padding-left: 1.66rem;padding-right: 1.66rem;">
									<i class="fa fa-lock text-muted"></i>
								</span>
							</div>
							<input type="password" class="form-control px-2" id="oldpass-edit" name="old_password" autocomplete="off" placeholder="password lama">
						</div>
						<small
							id="oldpass-edit-error"
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
	</body>
<?= $this->endSection(); ?>