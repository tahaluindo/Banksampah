<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
		#row-sampah-masuk .card-wraper:hover{
			transform: scale(0.96);
			transition: all 0.5s;
		}

		@media (max-width:768px) {
			.numbers p {
				font-size: 12px !important;
			}
			.numbers h5 {
				font-size: 12px !important;
			}
		} 
	</style>
  	<!-- ** develoment ** -->
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
	<!-- ** production ** -->
	<!-- <link rel="stylesheet" href="<?= base_url('assets/css/purge/bootstrap/admin.dashboard.css'); ?>"> -->
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
	<script src="<?= base_url('assets/js/plugins/chartjs.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/parent.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/admin.dashboard.min.js'); ?>"></script>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<!-- **** Loading Spinner **** -->
<?= $this->include('Components/loadingSpinner'); ?>
<!-- **** Alert Info **** -->
<?= $this->include('Components/alertInfo'); ?>

<body class="g-sidenav-show  bg-gray-100">
	<!-- **** Sidebar **** -->
	<?= $this->include('Components/adminSidebar'); ?>
	
	<main class="main-content position-relative mt-1 border-radius-lg ">
		<!-- Navbar -->
		<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl position-sticky" style="top: 20px;z-index:100;">
			<div class="container-fluid py-1 px-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
						<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
						<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
					</ol>
					<h6 class="font-weight-bolder mb-0">Dashboard</h6>
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
		<div class="container-fluid py-4">
			<!-- sampah masuk -->
			<div id="row-sampah-masuk" class="row" style="font-family: 'qc-semibold';">
				<div class="card-wraper col-xl-3 col-6 mb-4 cursor-pointer" data-toggle="modal" data-target="#modalDetailSampah" onclick="openModalSampahMasuk('kertas')">
					<div class="card">
						<div class="card-body p-3">
							<div class="row" style="font-family: 'qc-medium';">
								<div class="col-8 d-flex align-items-center">
									<div class="numbers">
										<p class="text-sm mb-0 text-capitalize font-weight-bold">Kertas</p>
										<h5 id="sampah-kertas" class="font-weight-bolder mb-0">
											0 Kg
										</h5>
									</div>
								</div>
								<div class="col-4 text-end">
									<div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
										<i class="fas fa-scroll"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-wraper col-xl-3 col-6 mb-4 cursor-pointer" data-toggle="modal" data-target="#modalDetailSampah" onclick="openModalSampahMasuk('logam')">
					<div class="card">
						<div class="card-body p-3">
							<div class="row" style="font-family: 'qc-medium';">
								<div class="col-8 d-flex align-items-center">
									<div class="numbers">
										<p class="text-sm mb-0 text-capitalize font-weight-bold">Logam</p>
										<h5 id="sampah-logam" class="font-weight-bolder mb-0">
											0 Kg
										</h5>
									</div>
								</div>
								<div class="col-4 text-end">
									<div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
										<i class="fas fa-trophy"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-wraper col-xl-3 col-6 mb-4 cursor-pointer" data-toggle="modal" data-target="#modalDetailSampah" onclick="openModalSampahMasuk('plastik')">
					<div class="card">
						<div class="card-body p-3">
							<div class="row" style="font-family: 'qc-medium';">
								<div class="col-8 d-flex align-items-center">
									<div class="numbers">
										<p class="text-sm mb-0 text-capitalize font-weight-bold">Plastik</p>
										<h5 id="sampah-plastik" class="font-weight-bolder mb-0">
											0 Kg
										</h5>
									</div>
								</div>
								<div class="col-4 text-end">
									<div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
										<i class="fas fa-wine-bottle"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-wraper col-xl-3 col-6 mb-4 cursor-pointer" data-toggle="modal" data-target="#modalDetailSampah" onclick="openModalSampahMasuk('lain-lain')">
					<div class="card">
						<div class="card-body p-3">
							<div class="row" style="font-family: 'qc-medium';">
								<div class="col-8 d-flex align-items-center">
									<div class="numbers">
										<p class="text-sm mb-0 text-capitalize font-weight-bold">Lain-lain</p>
										<h5 id="sampah-lain-lain" class="font-weight-bolder mb-0">
											0 Kg
										</h5>
									</div>
								</div>
								<div class="col-4 text-end">
									<div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
										<i class="fas fa-recycle"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- jumlah saldo -->
			<div class="row" style="font-family: 'qc-semibold';">
				<div class="col-xl-3 col-6 mb-4">
					<div class="card">
						<div class="card-body p-3">
							<div class="row" style="font-family: 'qc-medium';">
								<div class="col-8 d-flex align-items-center">
									<div class="numbers">
										<p class="text-sm mb-0 text-capitalize font-weight-bold">Uang BSBL</p>
										<h5 id="sampah-kertas" class="font-weight-bolder mb-0">
											Rp <span id="saldo-uang-bsbl">0</span>
										</h5>
									</div>
								</div>
								<div class="col-4 text-end">
									<div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
										<i class="fas fa-money-bill-wave-alt"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-6 mb-4">
					<div class="card">
						<div class="card-body p-3">
							<div class="row" style="font-family: 'qc-medium';">
								<div class="col-8 d-flex align-items-center">
									<div class="numbers">
										<p class="text-sm mb-0 text-capitalize font-weight-bold">Uang Nasabah</p>
										<h5 id="sampah-kertas" class="font-weight-bolder mb-0">
											Rp <span id="saldo-uang-nasabah">0</span>
										</h5>
									</div>
								</div>
								<div class="col-4 text-end">
									<div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
										<i class="fas fa-money-bill-wave-alt"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-6 mb-4">
					<div class="card">
						<div class="card-body p-3">
							<div class="row" style="font-family: 'qc-medium';">
								<div class="col-8 d-flex align-items-center">
									<div class="numbers">
										<p class="text-sm mb-0 text-capitalize font-weight-bold">Emas</p>
										<h5 id="sampah-logam" class="font-weight-bolder mb-0">
											<span id="saldo-emas">0</span> g
										</h5>
									</div>
								</div>
								<div class="col-4 text-end">
									<div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
										<i class="fas fa-coins"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-6 mb-4">
					<div class="card">
						<div class="card-body p-3">
							<div class="row" style="font-family: 'qc-medium';">
								<div class="col-8 d-flex align-items-center">
									<div class="numbers">
										<p class="text-sm mb-0 text-capitalize font-weight-bold">Nasabah</p>
										<h5 id="sampah-plastik" class="font-weight-bolder mb-0">
											<span id="jml-nasabah">0</span>
										</h5>
									</div>
								</div>
								<div class="col-4 text-end">
									<div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
										<i class="fas fa-user"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- grafik -->
			<div class="row mt-4">
				<!-- grafik -->
				<div class="col-12">
					<div class="card z-index-2 position-relative" style="min-height: 430px;overflow: hidden;font-family: 'qc-semibold';">
						<!-- header -->
						<div class="card-header pb-0" style="z-index: 11;">
							<!-- tittle -->
							<div class="row">
								<div class="col-12">
									<h5 class="text-center">Grafik Penyetoran Sampah</h5>
								</div>
							</div>
							<div class="form-row pb-0 mt-3 d-flex justify-content-between" style="font-family: 'qc-semibold';">
								<!-- Btn filter -->
								<div class="d-flex align-items-center text-sm">
									<a class="shadow px-1 border-radius-none mr-2" href="" data-toggle="modal" data-target="#modalFilterGrafikSetor" style="border-radius: 4px;">
										<i class="fas fa-sliders-h text-muted"></i>
									</a>
									<span id="ket-filter-grafik-penyetoran" class="">
										<?= (int)date("Y"); ?> - semua wilayah <small class="text-xxs">(per-bulan)</small>
									</span>
								</div>
							</div>
						</div>
						<!-- spinner -->
						<div id="spinner-grafik-penyetoran" class="spinner-wraper position-absolute bg-white d-flex align-items-center justify-content-center pt-5" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
							<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
						</div>
						<div class="card-body p-3 mt-2">
							<div class="chart d-flex">
								<div class="d-flex align-items-center pr-2 text-xs" style="min-height:100%;">
									<small	style="opacity: 0.6;letter-spacing: 2px;transform:rotate(-90deg);">
										kg
									</small>
								</div>
								<canvas id="chart-grafik-penyetoran" class="chart-canvas"></canvas>
							</div>
							<div class="d-flex flex-column align-items-center justify-content-center" style="opacity: 0.6;letter-spacing: 2px">
								<hr class="w-100 horizontal dark mt-2 mb-2">
								<small id="chart-title">bulan</small>
							</div>
						</div>
					</div>
				</div>
			</div>

			<footer class="footer pt-3">
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

<!-- modals detail sampah-->
<div class="modal fade" id="modalDetailSampah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<form id="formFilterTransaksi" class="modal-dialog" role="document">
		<input type="hidden" name="id">
		<div class="modal-content" style="overflow: hidden;">

			<!-- modal header -->
			<div class="modal-header">
				<h5 class="modal-title text-capitalize"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- modal body -->
			<div class="modal-body w-100 position-relative p-0" style="min-height: 200px;overflow: hidden;">
				<!-- spinner -->
				<div id="detil-sampah-spinner" class="position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
					<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 40px;" />
				</div>
				<!-- message not found -->
				<div id="detil-sampah-notfound" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
					<h6 id="text-notfound" class='opacity-6'>data tidak tersedia</h6>
				</div>
				<!-- table jenis -->
				<div id="table-jenis-wraper" class="table-responsive">

				</div>
			</div>
		</div>
	</form>
</div>

<!-- modals filter grafik setor-->
<div class="modal fade" id="modalFilterGrafikSetor" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<form id="formFilterGrafikSetor" class="modal-dialog" role="document">
		<div class="modal-content" style="overflow: hidden;">

			<!-- modal header -->
			<div class="modal-header">
				<h6 class="modal-title">filter grafik setor</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- modal body -->
			<div class="modal-body w-100 px-3">
				<h6 class="font-italic text-xs text-secondary">Tahun</h6>
				<div class="input-group col-12 px-0">
					<select id="year" name="year" class="filter-transaksi custom-select custom-select-sm w-100 mt-0" style="max-height: 31px;">
						<?php $curYear = (int)date("Y"); ?>
						<?php for ($i=$curYear; $i >= 2017 ; $i--) { ?>
							<option value="<?= $i; ?>"><?= $i; ?></option>
						<?php } ?>
					</select>
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
				<h6 class="font-italic text-xs text-secondary mt-4">Tampilan</h6>
				<div class="mt-2 form-row">
					<div class="input-group col-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="tampilan"  id="per-bulan" value="per-bulan" checked>
							<label class="form-check-label" for="per-bulan">
								Per-bulan
							</label>
						</div>
					</div>
					<div class="input-group col-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="tampilan" id="per-daerah" value="per-daerah">
							<label class="form-check-label" for="per-daerah">
								Per-daerah
							</label>
						</div>
					</div>
				</div>
			</div>

			<!-- modal footer -->
			<div class="modal-footer">
				<span class="badge badge-secondary d-flex justify-content-center align-items-center border-0 cursor-pointer" onclick="resetFilterGrafik();">
					<span>Reset</span>
				</span>
				<span id="btn-filter-data-transaksi" class="badge badge-success d-flex justify-content-center align-items-center border-0 cursor-pointer" data-dismiss="modal" onclick="filterGrafikSetor(this,event);">
					<span>Ok</span>
				</span>
			</div>
		</div>
	</form>
</div>
<?= $this->endSection(); ?>