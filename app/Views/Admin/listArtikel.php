<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
		html,body{
			height:100%;
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
	<script src="<?= base_url('assets/js/admin.listArtikel.min.js'); ?>"></script>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

	<!-- **** Loading Spinner **** -->
	<?= $this->include('Components/loadingSpinner'); ?>
	<!-- **** Alert Info **** -->
	<?= $this->include('Components/alertInfo'); ?>

	<body class="g-sidenav-show bg-gray-100">
		<!-- **** Sidebar **** -->
		<?= $this->include('Components/adminSidebar'); ?>

		<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg d-flex flex-column">
			<!-- navbar -->
			<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl position-sticky" style="top: 20px;z-index:100;">
				<div class="container-fluid py-1 px-3">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
							<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
							<li class="breadcrumb-item text-sm text-dark active" aria-current="page">List artikel</li>
						</ol>
						<h6 class="font-weight-bolder mb-0">List artikel</h6>
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
					<div class="col-12" style="max-height: 100%;">
						<div class="card mb-4 h-100 d-flex flex-column" style="max-height: 100%;overflow: hidden;font-family: 'qc-semibold';">
							<!-- search input -->
							<div class="card-header form-row pb-3 d-flex justify-content-between" style="font-family: 'qc-semibold';">
								<div class="input-group col-12 col-sm-6">
									<div class="input-group-prepend">
										<span class="input-group-text bg-gray px-4 border-md border-right-0" style="max-height: 39px;">
											<i class="fas fa-search text-muted"></i>
										</span>
									</div>
									<input id="search-artikel" type="text" class="form-control h-100 px-2" placeholder="judul artikel" style="max-height: 39px;">
								</div>
								<div class="input-group col-12 col-sm-1 p-0" style="min-width: 90px;">
									<a class="btn btn-success mt-4 mt-sm-0 text-xxs" href="<?= base_url('admin/addartikel');?>" style="width: 100%;">tambah</a>
								</div>
								<div class="input-group col-12 flex-column text-sm">
									<div class="d-flex align-items-center">
										<a id="btn-edit-profile" class="shadow px-1 border-radius-none mr-2" href="" data-toggle="modal" data-target="#modalFilterArtikel">
											<i class="fas fa-sliders-h text-secondary"></i>
										</a>
										<span id="ket-filter" class=" text-secondary">terbaru - semua kategori</span>
									</div>
									<div class="mt-2 text-xs text-secondary">
										<span id="ket-total">0</span> artikel
									</div>
								</div>
							</div>
							<!-- container list -->
							<div class="card-body pl-3 pr-3 pb-2 position-relative" style="flex: 1;overflow: auto;font-family: 'qc-semibold';">
								<!-- spinner -->
								<div id="list-artikel-spinner" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
									<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
								</div>
								<!-- message not found -->
								<div id="list-artikel-notfound" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
									<h6 id="text-notfound" class='opacity-6'></h6>
								</div>
								<!-- Card List Article  -->
								<div id="container-list-artikel" class="container-fluid p-0 row">
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

	<!-- modals filter artikel-->
	<div class="modal fade" id="modalFilterArtikel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<form id="formFilterArtikel" class="modal-dialog modal-sm" role="document">
			<input type="hidden" name="id">
			<div class="modal-content" style="overflow: hidden;">

				<!-- modal header -->
				<div class="modal-header">
					<h5 class="modal-title">filter artikel</h5>
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
					<h6 class="font-italic text-xs text-secondary mt-4">Kategori</h6>
					<div class="mt-2 position-relative">
						<select class='form-control form-control-sm' name="kategori">
							<option value="">-- pilih kategori --</option>
						</select>
						<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:6px;right:10px;"></i>
					</div>
				</div>

				<!-- modal footer -->
				<div class="modal-footer">
					<div class="badge badge-secondary d-flex justify-content-center align-items-center border-0 cursor-pointer" onclick="resetFilterArtikel();">
						<span>Reset</span>
					</div>
					<button id="submit" type="submit" class="badge badge-success d-flex justify-content-center align-items-center border-0" data-dismiss="modal" onclick="filterArtikel(this,event);">
						<span>Ok</span>
					</button>
				</div>
			</div>
		</form>
	</div>
<?= $this->endSection(); ?>