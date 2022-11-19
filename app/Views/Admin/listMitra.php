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
			transform: translateX(25px);
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
	<script src="<?= base_url('assets/js/admin.mitra.js'); ?>"></script>
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
							<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Mitra</li>
						</ol>
						<h6 class="font-weight-bolder mb-0">Manage All Mitra</h6>
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
							<!-- add mitra -->
							<div class="card-header form-row pb-0 mb-3 d-flex justify-content-end" style="font-family: 'qc-semibold';">
								<div class="input-group col-12 col-sm-1 p-0" style="min-width: 90px;">
									<button class="btn btn-success mt-4 mt-sm-0 text-xxs" data-toggle="modal" data-target="#modalAddMitra" onclick="openModalAddMitra()" style="width: 100%;">tambah</button>
								</div>
							</div>
							<!-- container table -->
							<div class="card-body px-0 pt-0 pb-2 position-relative" style="flex: 1;overflow: hidden;font-family: 'qc-semibold';">
								<div class="table-responsive p-0 position-relative" style="min-height: 100%;max-height: 100%;overflow: auto;font-family: 'qc-semibold';">
									<!-- spinner -->
									<div id="list-mitra-spinner" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center pt-4" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
										<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
									</div>
									<!-- message not found -->
									<div id="list-mitra-notfound" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center pt-5" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
										<h6 id="text-notfound" class='opacity-6'></h6>
									</div>
									<!-- table -->
									<table id="table-mitra" class="table table-striped text-center mb-0">
										<thead class="position-sticky bg-white" style="z-index: 11;top: 0;">
											<tr>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													#
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													Icon
												</th>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													Nama
												</th>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													Deskripsi
												</th>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													Action
												</th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
								</div>
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

	<!-- modals Add Mitra-->
	<div class="modal fade" id="modalAddMitra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form id="formAddMitra" class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Tambah mitra</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12 col-sm-6">
							<img id="img-preview" src="<?= base_url('assets/images/skeleton-icon.png'); ?>" class="img-thumbnail" style="width:100px;height:100px;max-width:100px;max-height:100px;">
							<div class="input-group mt-2">
								<input type="file" class="form-control" name="icon" id="icon" autocomplete="off" placeholder="icon" style="min-height: 38px" onchange="changeThumbPreview(this);">
							</div>
						</div>

						<h6 class="font-italic opacity-8 mt-4 mb-2">Nama Mitra</h6>
						<div class="col-12 input-group">
							<input type="text" class="form-control px-2" id="mitra_name" name="mitra_name" autocomplete="off" placeholder="masukan kategori baru">
						</div>
						<small
						  id="mitra_name-error"
						  class="text-danger"></small>

						<h6 class="font-italic opacity-8 mt-4 mb-2">Deskripsi</h6>
						<div class="col-12 input-group">
							<textarea id="description" name="description" class="form-control rounded-sm" rows="3"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button id="btnAddMitra" type="submit" class="btn btn-success d-flex justify-content-center align-items-center" style="height: 40.8px;" onclick="addMitra(this,event)">
						<span id="text">Simpan</span>
						<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 20px;">
					</button>
				</div>
			</form>
		</div>
	</div>
	
<?= $this->endSection(); ?>