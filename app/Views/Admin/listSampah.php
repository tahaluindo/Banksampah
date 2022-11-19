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
	<script src="<?= base_url('assets/js/admin.listsampah.min.js'); ?>"></script>
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
							<li class="breadcrumb-item text-sm text-dark active" aria-current="page">List sampah</li>
						</ol>
						<h6 class="font-weight-bolder mb-0">List sampah</h6>
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
								<div class="input-group col-12 col-sm-6">
									<div class="input-group-prepend">
										<span class="input-group-text bg-gray px-4 border-md border-right-0" style="max-height: 39px;">
											<i class="fas fa-search text-muted"></i>
										</span>
									</div>
									<input id="search-sampah" type="text" class="form-control h-100 px-2" placeholder="jenis sampah" style="max-height: 39px;">
								</div>
								<div class="input-group col-12 col-sm-1 p-0" style="min-width: 90px;">
									<button class="btn btn-success mt-4 mt-sm-0 text-xxs" data-toggle="modal" data-target="#modalAddEditSampah" onclick="openModalAddEditSmp('addsampah')" style="width: 100%;">tambah</button>
								</div>
								<div class="input-group col-12 flex-column text-sm">
									<div class="d-flex align-items-center">
										<a id="btn-edit-profile" class="shadow px-1 border-radius-none mr-2" href="" data-toggle="modal" data-target="#modalFilterSampah">
											<i class="fas fa-sliders-h text-secondary"></i>
										</a>
										<span id="ket-filter" class=" text-secondary">terlama - semua kategori</span>
									</div>
									<div class="mt-2 text-xs text-secondary">
										<span id="ket-total">0</span> jenis sampah
									</div>
								</div>
							</div>
							<!-- container table -->
							<div class="card-body px-0 pt-0 pb-2 position-relative" style="flex: 1;overflow: hidden;font-family: 'qc-semibold';">
								<div class="table-responsive p-0 position-relative" style="min-height: 100%;max-height: 100%;overflow: auto;font-family: 'qc-semibold';">
									<!-- spinner -->
									<div id="list-sampah-spinner" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center pt-4" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
										<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
									</div>
									<!-- message not found -->
									<div id="list-sampah-notfound" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center pt-5" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
										<h6 id="text-notfound" class='opacity-6'></h6>
									</div>
									<!-- table -->
									<table id="table-jenis-sampah" class="table table-striped text-center mb-0">
										<thead class="position-sticky bg-white" style="z-index: 11;top: 0;">
											<tr>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													#
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													Kategori
												</th>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													Jenis
												</th>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													Harga Pengepul
												</th>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													Harga Nasabah
												</th>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													Jumlah(Kg)
												</th>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
													Action
												</th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
										<?php for ($i=0; $i < 0; $i++) { ?>
										<?php } ?>
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

	<!-- modals filter sampah-->
	<div class="modal fade" id="modalFilterSampah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<form id="formFilterSampah" class="modal-dialog modal-sm" role="document">
			<input type="hidden" name="id">
			<div class="modal-content" style="overflow: hidden;">

				<!-- modal header -->
				<div class="modal-header">
					<h5 class="modal-title">filter sampah</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<!-- modal body -->
				<div class="modal-body w-100 px-3">
					<h6 class="font-italic text-xs text-secondary">Urutan</h6>
					<div class="position-relative">
						<select class='form-control form-control-sm' name="orderby">
							<option value="terlama" selected>terlama</option>
							<option value="terbaru">terbaru</option>
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
					<div class="badge badge-secondary d-flex justify-content-center align-items-center border-0 cursor-pointer" onclick="resetFilterSampah();">
						<span>Reset</span>
					</div>
					<button id="submit" type="submit" class="badge badge-success d-flex justify-content-center align-items-center border-0" data-dismiss="modal" onclick="filterSampah(this,event);">
						<span>Ok</span>
					</button>
				</div>
			</div>
		</form>
	</div>

	<!-- modals Add / Edit sampah -->
	<div class="modal fade show" id="modalAddEditSampah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<form id="formAddEditSampah" class="modal-dialog modal-dialog-centered" role="document" onsubmit="crudSampah(this,event);">
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
					<input type="hidden" name="id" id="id">
					<!-- **** JENIS **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<h6 class="font-italic text-xs text-secondary">Jenis</h6>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md border-right-0">
									<i class="fas fa-recycle text-muted"></i>
								</span>
							</div>
							<input type="text" class="form-control px-2" id="jenis" name="jenis" autocomplete="off">
						</div>
						<small
							id="jenis-error"
							class="text-danger"></small>
					</div>
					<!-- **** Harga Pengepul **** -->
					<div class="form-row mb-4" style="padding-right: 2px;">
						<div class="col">
							<h6 class="font-italic text-xs text-secondary">Harga Pengepul</h6>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-gray px-4 border-md border-right-0">
										<i class="fas fa-money-bill-wave-alt text-muted"></i>
									</span>
								</div>
								<input type="number" class="form-control px-2" id="harga_pusat" name="harga_pusat" autocomplete="off">
							</div>
							<small
								id="harga_pusat-error"
								class="text-danger"></small>
						</div>
					</div>
					<!-- **** Harga Nasabah **** -->
					<div class="form-row mb-4" style="padding-right: 2px;">
						<div class="col">
							<h6 class="font-italic text-xs text-secondary">Harga Nasabah</h6>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-gray px-4 border-md border-right-0">
										<i class="fas fa-money-bill-wave-alt text-muted"></i>
									</span>
								</div>
								<input type="number" class="form-control px-2" id="harga" name="harga" autocomplete="off" readonly>
							</div>
							<small
								id="harga-error"
								class="text-danger"></small>
						</div>
					</div>
					<!-- **** Jumlah **** -->
					<div class="form-row mb-4 d-none inputJumlah" style="padding-right: 2px;">
						<div class="col">
							<h6 class="font-italic text-xs text-secondary">Jumlah</h6>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-gray px-4 border-md border-right-0">
										<i class="fas fa-weight text-muted"></i>
									</span>
								</div>
								<input type="text" class="form-control px-2" id="jumlah" name="jumlah" autocomplete="off" placeholder="Jumlah">
							</div>
							<small
								id="jumlah-error"
								class="text-danger"></small>
						</div>
					</div>
					<!-- **** KATEGORI **** -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<h6 class="font-italic text-xs text-secondary">Kategori</h6>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md" style="max-height: 38px;">
									<i class="fas fa-clipboard-list text-muted"></i>
								</span>
							</div>
							<select id="select-sampah-wraper" name="id_kategori" class="form-control py-1 px-2 d-block" style="min-height: 38px">
								
							</select>
						</div>
						<small
							id="select-sampah-wraper-error"
							class="text-danger"></small>
					</div>

					<div class="input-group col-12 form-group">
						<button id="btn-add-edit-sampah" type="submit" class="btn btn-success d-flex justify-content-center align-items-center w-100" style="height: 40.8px;">
							<span id="text">Simpan</span>
							<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 20px;">
						</button>
					</div>

					<hr class="horizontal dark mt-0 mb-3">
					<h6 class="font-italic opacity-8">Manage kategori</h6>

					<!-- LIST KATEGORI -->
					<div class="input-group col-lg-12 mb-4 form-group">
						<div class="container-fluid border-radius-sm p-2" style="border: 0.5px solid #D2D6DA;">
							<!-- header -->
							<div class="container-fluid mb-2 d-flex p-0">
								<input id="NewkategoriSampah" type="text" class="form-control px-2 text-xxs border-radius-sm" placeholder="ketik kategori baru" style="max-width: 150px;max-height: 30px;border: 0.5px solid #D2D6DA;" autocomplete="off">
								<a href="" id="btnAddKategoriSampah" class="badge badge-info border-0 border-radius-sm text-xxs text-lowercase ml-2 d-flex justify-conten-center align-items-center">
									<i id="text" class="fas fa-plus"></i>
									<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 14px;">
								</a>
							</div>
							<!-- body -->
							<div id="kategori-sampah-wraper" class="container-fluid border-radius-sm p-0 position-relative" style="min-height: 150px;max-height: 150px;overflow: auto;border: 0.5px solid #D2D6DA;">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
<?= $this->endSection(); ?>