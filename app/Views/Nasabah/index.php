<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
		#row-sampah-masuk .card-wraper:hover{
			transform: scale(0.96);
			transition: all 0.5s;
		}
		
		.detil-transaksi-logo img{
			width: 80px;
		}

		/* .editTransateY{
			transform: translateY(-25px) rotate(-90deg) !important;
		} */

		@media (max-width:768px) {
			.numbers p {
				font-size: 12px !important;
			}
			.numbers h5 {
				font-size: 12px !important;
			}
		} 
		@media (max-width:422px) {
			.detil-transaksi-logo h4{
				font-size: 14px;
			}
			.detil-transaksi-logo img{
				width: 60px;
			}
			.detil-transaksi-header,
			#detil-transaksi-type ,
			#detil-transaksi-body {
				font-size: 8px;
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
	<script src="<?= base_url('assets/js/plugins/chartjs.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/plugins/font-awesome.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/parent.js'); ?>"></script>
	<script src="<?= base_url('assets/js/nasabah.min.js'); ?>"></script>
<?= $this->endSection(); ?>

<!-- Html -->
<?= $this->section('content'); ?>
	<!-- **** Loading Spinner **** -->
	<?= $this->include('Components/loadingSpinner'); ?>
	<!-- **** Alert Info **** -->
	<?= $this->include('Components/alertInfo'); ?>

	<body class="g-sidenav-show bg-gray-100">
		<!-- **** Sidebar **** -->
		<?= $this->include('Components/nasabahSidebar'); ?>

		<main class="main-content position-relative mt-1 border-radius-lg">
			<!-- Navbar -->
			<nav  class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl position-sticky" style="top: 20px;z-index:100;">
				<div class="container-fluid py-1 px-3" style="font-family: 'qc-semibold';">
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
									<a href="" class="nav-link text-body p-0" id="iconNavbarSidenav">
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
				<div id="row-sampah-masuk" class="row">
					<div class="card-wraper col-xl-3 col-6 mb-md-0 mb-4 cursor-pointer" data-toggle="modal" data-target="#modalDetailSampah" onclick="openModalSampahMasuk('kertas')">
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
					<div class="card-wraper col-xl-3 col-6 mb-md-0 mb-4 cursor-pointer" data-toggle="modal" data-target="#modalDetailSampah" onclick="openModalSampahMasuk('logam')">
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
					<div class="card-wraper col-xl-3 col-6 cursor-pointer" data-toggle="modal" data-target="#modalDetailSampah" onclick="openModalSampahMasuk('plastik')">
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
					<div class="card-wraper col-xl-3 col-6 cursor-pointer" data-toggle="modal" data-target="#modalDetailSampah" onclick="openModalSampahMasuk('lain-lain')">
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
				
				<!-- transaksi -->
				<div id="row-transaksi" class="row mt-5">
					<!-- grafik -->
					<div class="col-lg-8">
						<div class="card z-index-2 position-relative" style="min-height: 460px;max-height: 460px;overflow: hidden;font-family: 'qc-semibold';">
							<!-- header -->
							<div class="card-header pb-0" style="z-index: 11;">
								<h5>Grafik Penyetoran</h5>
								<div class="mt-2 position-relative" style="max-width: 120px;">
									<select class='form-control form-control-sm' name="tahun-grafik-setor">
										<?php foreach(range(2014, (int)date("Y")) as $year) { ?>
											<option value="<?= $year ?>" <?= $year== (int)date("Y") ? 'selected' : ''?>><?= $year ?></option>
										<?php } ?>
									</select>
									<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:6px;right:10px;"></i>
								</div>
							</div>
							<!-- spinner -->
							<div id="spinner-wraper-grafik" class="position-absolute bg-white d-flex align-items-center justify-content-center pt-5" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
								<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
							</div>
							<div class="card-body p-3 mt-2">
								<div class="chart d-flex">
									<div class="d-flex align-items-center text-xs" style="min-height:100%;">
										<small id="label-y"	style="opacity: 0.6;letter-spacing: 2px;transform:translateY(-25px)  rotate(-90deg);">
											Kg
										</small>
									</div>
									<canvas id="chart-line" class="chart-canvas"></canvas>
								</div>
								<div class="d-flex flex-column align-items-center justify-content-center text-xs" style="opacity: 0.6;letter-spacing: 2px">
									<hr class="w-100 horizontal dark mt-2 mb-2">
									<small id="chart-title">ID transaksi</small>
								</div>
							</div>
						</div>
					</div>
					<!-- Transaksi -->
					<div id="history-transaksi" class="col-lg-4 mt-5 mt-lg-0">
						<div class="card h-100" style="min-height: 460px;max-height: 460px;overflow: auto;">
							<!-- header -->
							<div class="card-header bg-white position-sticky p-3" style="z-index: 11;top: 0;">
								<div class="row" style="font-family: 'qc-semibold';">
									<div class="col-6 d-flex flex-column">
										<h5>History</h5>
										<div id="btn-filter-histori" class="d-flex align-items-center mt-3">
											<a class="shadow px-1 pt-1 border-radius-none mr-2" href="" data-toggle="modal" data-whatever="filter histori" data-target="#modalFilterTransaksi" onclick="openModalFilterT('Filter Histori');">
												<i class="fas fa-sliders-h text-secondary"></i>
											</a>
											<span id="startdate" class=" text-secondary text-sm mt-1">
												00/00/0000
											</span>
											<i class="fas fa-long-arrow-alt-right text-secondary mt-1 mx-2"></i>
											<span id="enddate" class=" text-secondary text-sm mt-1">
												00/00/0000
											</span>
										</div>
									</div>
								</div>
							</div>
							<!-- spinner -->
							<div id="spinner-wraper-histori" class="position-absolute bg-white d-flex align-items-center justify-content-center pt-5" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
								<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
							</div>
							<div id="transaksi-wraper-histori" class="card-body pl-3 pr-3 pt-0 pb-0 d-flex justify-content-center align-items-center" style="font-family: 'qc-semibold';">

							</div>
						</div>
					</div>
				</div>

				<!-- saldo -->
				<div id="jumlah-saldo" class="row mt-5">
					<div class="col-xl-6">
						<div class="card-id bg-transparent shadow-xl">
							<div class="overflow-hidden position-relative border-radius-md"
								style="background-image: url(<?= base_url('assets/images/curved-images/curved14.jpg'); ?>);">
								<span class="mask bg-gradient-dark"></span>
								<div class="card-body-id position-relative z-index-1 p-3">
									<i class="fas fa-wifi text-white p-2"></i>
									<h5 id="card-id" class="text-white mt-4 mb-5" style="font-family: 'qc-medium';">_ _ _ _ _&nbsp;&nbsp;&nbsp;_ _ _
										_&nbsp;&nbsp;&nbsp;_</h5>
									<div class="d-flex">
										<div class="d-flex">
											<div class="me-4" style="font-family: 'qc-medium';">
												<p class="text-white text-sm opacity-8 mb-0">Username</p>
												<h6 id="card-username" class="text-white mb-0">_ _ _ _ _ _</h6>
											</div>
											<div style="font-family: 'qc-medium';">
												<p class="text-white text-sm opacity-8 mb-0">Tanggal Bergabung</p>
												<h6 id="card-date" class="text-white mb-0">_ _/_ _/_ _ _ _<h6>
											</div>
										</div>
										<div class="ms-auto w-20 d-flex align-items-end justify-content-end">
											<img class="w-60 mt-2" src="<?= base_url('assets/images/banksampah-logo.webp'); ?>"
												alt="logo">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xl-3 h-100 pr-2 pl-2 mt-4 mt-md-0">
						<div class="card h-100 border-radius-md">
							<div class="card-header p-3 text-center d-flex justify-content-center">
								<div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
									<i class="fas fa-money-bill-wave-alt"></i>
								</div>
							</div>
							<div class="card-body pt-0 pt-4 text-center" style="font-family: 'qc-medium';">
								<h6 class="text-center mb-0">Tunai</h6>
								<hr class="horizontal dark my-3">
								<h5 class="mb-0">Rp <span id="saldo-uang">0</span></h5>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xl-3 h-100 pl-2 mt-4 mt-md-0">
						<div class="card h-100 border-radius-md">
							<div class="card-header p-3 text-center d-flex justify-content-center">
								<div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
									<i class="fas fa-coins"></i>
								</div>
							</div>
							<div class="card-body pt-0 pt-4 text-center" style="font-family: 'qc-medium';">
								<h6 class="text-center mb-0">Emas</h6>
								<hr class="horizontal dark my-3">
								<h5 class="mb-0"><span id="saldo-emas">0</span> g</h5>
							</div>
						</div>
					</div>
				</div>

				<!-- table sampah -->
				<div id="info-sampah" class="row mt-4 mt-md-5">
					<div class="col-12">
						<div class="card mb-4" style="overflow: hidden;font-family: 'qc-semibold';">
							<!-- header -->
							<div class="card-header form-row pb-0 d-flex flex-column" style="font-family: 'qc-semibold';">
								<h6 style="line-height: 8px;">Jenis-jenis sampah</h6>
								<p class="font-italic text-muted text-xs">*harga dapat berubah sewaktu-waktu</p>
							</div>
							<!-- container table -->
							<div class="card-body px-0 pb-2">
								<div class="table-responsive p-0 position-relative" style="min-height: 380px;max-height: 380px;overflow: auto;font-family: 'qc-semibold';">
									<!-- spinner -->
									<div id="list-sampah-spinner" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
										<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
									</div>
									<!-- message not found -->
									<div id="list-sampah-notfound" class="d-none position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
										<h6 id="text-notfound" class='opacity-6'></h6>
									</div>
									<!-- table -->
									<table id="table-jenis-sampah" class="table table-striped text-center mb-0">
										<thead class="position-sticky bg-white" style="top: 0;">
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
													Harga
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

				<!-- Footer -->
				<footer class="footer mt-4">
					<div class="container-fluid p-0">
						<div class="mb-2">
							<div class="copyright text-center text-sm text-muted text-lg-start">
								© <script>
									document.write(new Date().getFullYear())
								</script>,
								Bank Sampah Budi Luhur
							</div>
						</div>
					</div>
				</footer>
		</main>

	</body>

	<!-- modals detail sampah-->
	<div class="modal fade" id="modalDetailSampah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<form id="formFilterTransaksi" class="modal-dialog modal-dialog-centered" role="document">
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

	<!-- modals filter transaksi-->
	<div class="modal fade" id="modalFilterTransaksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<form id="formFilterTransaksi" class="modal-dialog modal-sm modal-dialog-centered" role="document">
			<input type="hidden" name="id">
			<div class="modal-content" style="overflow: hidden;">

				<!-- modal header -->
				<div class="modal-header">
					<h5 class="modal-title">filter</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<!-- modal body -->
				<div class="modal-body w-100 px-3 form-row">
					<h6 class="font-italic text-xs text-secondary">From</h6>
					<div class="input-group col-12">
						<div class="input-group-prepend">
							<span class="input-group-text bg-gray px-4 border-md">
								<i class="fas fa-calendar-alt text-muted"></i>
							</span>
						</div>
						<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date-start" name="date-start">
					</div>
					<h6 class="font-italic text-xs text-secondary mt-4">To</h6>
					<div class="input-group col-12">
						<div class="input-group-prepend">
							<span class="input-group-text bg-gray px-4 border-md">
								<i class="fas fa-calendar-alt text-muted"></i>
							</span>
						</div>
						<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date-end" name="date-end">
					</div>
				</div>

				<!-- modal footer -->
				<div class="modal-footer">
					<span id="btn-filter-transaksi" class="badge badge-success d-flex justify-content-center align-items-center border-0 cursor-pointer" data-dismiss="modal" onclick="filterTransaksi(this,event);">
						<span>Ok</span>
					</span>
				</div>
			</div>
		</form>
	</div>

	<!-- **** Modal print transaksi **** -->
	<div class="modal fade" id="modalPrintTransaksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle">Cetak bukti transaksi</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div id="modalPrintTransaksiTarget" class="modal-body w-100 position-relative" style="overflow: hidden;">
					<!-- spinner -->
					<div id="detil-transaksi-spinner" class="position-absolute bg-white d-flex align-items-center justify-content-center" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
						<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 40px;" />
					</div>
					<!-- header -->
					<div class="detil-transaksi-logo d-flex align-items-center justify-content-between py-2 px-4">
						<img src="<?= base_url('assets/images/banksampah-logo.png');?>" />
						<h4>bukti transaksi</h4>
					</div>
					<hr class="horizontal dark mt-2">
					<div class="px-4 detil-transaksi-header text-xs">
						<table>
							<tr>
								<td>TANGGAL&nbsp;&nbsp;&nbsp;</td>
								<td>: <span id="detil-transaksi-date"></span></td>
							</tr>
							<tr>
								<td>NAMA&nbsp;&nbsp;&nbsp;</td>
								<td>: <span id="detil-transaksi-nama" class="text-uppercase"></span></td>
							</tr>
							<tr>
								<td>ID.NASABAH&nbsp;&nbsp;&nbsp;</td>
								<td>: <span id="detil-transaksi-idnasabah"></span></td>
							</tr>
							<tr>
								<td>ID.TRANSAKSI&nbsp;&nbsp;&nbsp;</td>
								<td>: <span id="detil-transaksi-idtransaksi"></span></td>
							</tr>
						</table>
					</div>
					<hr class="horizontal dark mt-2">
					<h6 id="detil-transaksi-type" class="font-italic px-4 text-xs"></h6>
					<div id="detil-transaksi-body" class="px-4 mt-2 table-responsive">
						
					</div>
				</div>
				<div class="modal-footer">
					<a id="btn-cetak-transaksi" href="" target="_blank" class="btn btn-success d-flex justify-content-center align-items-center" style="height: 40.8px;">
						<span id="text">Cetak</span>
						<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 20px;">
					</a>
				</div>
			</div>
		</div>
	</div>
<?= $this->endSection(); ?>