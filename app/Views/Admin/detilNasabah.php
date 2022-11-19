<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
		#row-sampah-masuk .card-wraper:hover{
			transform: scale(0.96);
			transition: all 0.5s;
		}

		.chartWrapper {
			position: relative !important;
		}

		.chartWrapper > canvas {
			position: absolute !important;
			left: 0 !important;
			top: 0 !important;
			pointer-events: none !important;
		}

		.chartAreaWrapper {
			width: 100% !important;
			overflow-x: scroll !important;
		}
		
		.detil-transaksi-logo img{
			width: 80px;
		}

		@media (max-width:768px) {
			.numbers p {
				font-size: 12px !important;
			}
			.numbers h5 {
				font-size: 12px !important;
			}
		} 
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
			#personal-info table td{
				display: block;
			}
			#personal-info table td.label{
				margin-top: 16px;
			}
			#personal-info table td.main{
				border-left: 3px solid #c1d966;
				padding: 6px 10px;
			}
			#personal-info table td span.titik{
				display: none;
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
  	<!-- ** develoment ** -->
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
	<!-- ** production ** -->
	<!-- <link rel="stylesheet" href="<?= base_url('assets/css/purge/bootstrap/admin.detilnasabah.css'); ?>"> -->
	<link rel="stylesheet" href="<?= base_url('assets/css/soft-ui-dashboard.min.css'); ?>">
<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->section('contentJs'); ?>
	<script>
		const PASSADMIN = '<?= $password; ?>';
		const IDNASABAH = '<?= $idnasabah; ?>';
	</script>
	<script src="<?= base_url('assets/js/core/jquery-2.1.0.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/core/bootstrap.min.js'); ?>"></script>
  	<script src="<?= base_url('assets/js/plugins/font-awesome.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/plugins/chartjs.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/parent.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/admin.detilnasabah.min.js'); ?>"></script>
<?= $this->endSection(); ?>

<!-- Html -->
<?= $this->section('content'); ?>

<!-- **** Loading Spinner **** -->
<?= $this->include('Components/loadingSpinner'); ?>
<!-- **** Alert Info **** -->
<?= $this->include('Components/alertInfo'); ?>

<body class="g-sidenav-show bg-gray-100">

	<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main"  style="font-family: 'qc-semibold';">
		<div class="sidenav-header">
			<a class="nav-link mt-4" href="<?= base_url('admin/listnasabah');?>" style="display: flex;align-items: center;">
				<div
					class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
					<svg width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg">
					<g clip-path="url(#clip0_302:4)">
					<path d="M6.50489 8.41773L3.20258 5.11542L6.50489 1.81312C6.79641 1.51129 6.79224 1.03151 6.49552 0.734794C6.1988 0.438075 5.71903 0.433906 5.4172 0.725423L1.57104 4.57158C1.27075 4.87196 1.27075 5.35889 1.57104 5.65927L5.4172 9.50542C5.61033 9.70539 5.89633 9.78559 6.16528 9.71519C6.43423 9.6448 6.64426 9.43476 6.71466 9.16582C6.78505 8.89687 6.70486 8.61087 6.50489 8.41773Z" fill="#252F40"/>
					</g>
					<defs>
					<clipPath id="clip0_302:4">
					<rect width="10" height="7" fill="white" transform="translate(7.49951 0.500031) rotate(90)"/>
					</clipPath>
					</defs>
					</svg>
				</div>
				<span class="nav-link-text ms-1">list nasabah</span>
			</a>
			<hr class="horizontal dark mt-2">
		</div>
		<div class="collapse navbar-collapse  w-auto  max-height-vh-100 h-100" id="sidenav-collapse-main">
			<ul class="navbar-nav">
				<!-- setor sampah -->
				<li class="nav-item">
					<a class="nav-link cursor-pointer" data-toggle="modal" data-target="#modalSetorSaldo"  data-backdrop="static" data-keyboard="false" onclick="openModalTransaksi('setor sampah')" >
						<div
							class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
							<i class="fas fa-trash-restore text-muted" style="font-size: 13px;transform: translateY(-1px);"></i>
						</div>
						<span class="nav-link-text ms-1">Setor sampah</span>
					</a>
				</li>
				<!-- konversi saldo -->
				<li class="nav-item">
					<a class="nav-link cursor-pointer" data-toggle="modal" data-target="#modalPindahSaldo" onclick="openModalTransaksi('pindah saldo')">
						<div
							class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
							<i class="fas fa-exchange-alt text-muted" style="font-size: 13px;transform: translateY(-1px);"></i>
						</div>
						<span class="nav-link-text ms-1">Konversi saldo</span>
					</a>
				</li>
				<!-- tarik saldo -->
				<li class="nav-item">
					<a class="nav-link cursor-pointer" data-toggle="modal" data-target="#modalTarikSaldo" onclick="openModalTransaksi('tarik saldo')">
						<div
							class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
							<i class="fas fa-hand-holding-usd text-muted" style="font-size: 15px;transform: translateY(-1px);"></i>
						</div>
						<span class="nav-link-text ms-1">Tarik saldo</span>
					</a>
				</li>
				<!-- rekap transaksi -->
				<li class="nav-item">
					<a class="nav-link cursor-pointer" data-toggle="modal" data-target="#modalRekapTransaksi">
						<div
							class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
							<i class="fas fa-file-signature text-muted" style="font-size: 15px;transform: translateY(-1px);"></i>
						</div>
						<span class="nav-link-text ms-1">Rekap transaksi</span>
					</a>
				</li>
			</ul>
		</div>
	</aside>

	<main class="main-content position-relative mt-1 border-radius-lg">
		<!-- Navbar -->
		<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl position-sticky" style="top: 20px;z-index:100;">
			<div class="container-fluid py-1 px-3" style="font-family: 'qc-semibold';">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
						<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
						<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
					</ol>
					<h4 id="navbar-namalengkap" class="font-weight-bolder text-capitalize mt-2 mb-0" style="font-size: 16px;">_ _ _ _</h4>
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
			<div id="row-sampah-masuk" class="row">
				<div class="card-wraper col-xl-3 col-6 mb-xl-0 mb-4 cursor-pointer" data-toggle="modal" data-target="#modalDetailSampah" onclick="openModalSampahMasuk('kertas')">
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
				<div class="card-wraper col-xl-3 col-6 mb-xl-0 mb-4 cursor-pointer" data-toggle="modal" data-target="#modalDetailSampah" onclick="openModalSampahMasuk('logam')">
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
				<div class="card-wraper col-xl-3 col-6 mb-xl-0 mb-4 cursor-pointer" data-toggle="modal" data-target="#modalDetailSampah" onclick="openModalSampahMasuk('plastik')">
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
			<div class="row mt-4">
				<!-- grafik -->
				<div class="col-lg-8">
					<div class="card z-index-2 position-relative" style="min-height: 460px;max-height: 460px;overflow: hidden;font-family: 'qc-semibold';">
						<!-- header -->
						<div class="card-header pb-0" style="z-index: 11;">
							<h5>Grafik Penyetoran</h5>
							<div class="mt-2 position-relative" style="max-width: 120px;">
								<select class='form-control form-control-sm' name="tahun-grafik-setor">
										<option value="">-- pilih tahun --</option>
									<?php foreach(range(2014, (int)date("Y")) as $year) { ?>
										<option value="<?= $year ?>" <?= $year== (int)date("Y") ? 'selected' : ''?>><?= $year ?></option>
									<?php } ?>
								</select>
								<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:6px;right:10px;"></i>
							</div>
							<!-- <div class="mt-3 form-row">
								<div id="btn-filter-grafik" class="d-flex align-items-center">
									<a class="shadow px-1 pt-1 border-radius-none mr-2" href="" data-toggle="modal" data-whatever="filter grafik" data-target="#modalFilterTransaksi" onclick="openModalFilterT('Filter Grafik');">
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
							</div> -->
						</div>
						<!-- spinner -->
						<div id="spinner-wraper-grafik" class="position-absolute bg-white d-flex align-items-center justify-content-center pt-5" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
							<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
						</div>
						<div class="card-body p-3 mt-2">
							<div class="chart d-flex">
								<div class="d-flex align-items-center text-xs" style="min-height:100%;">
									<small id="label-y"	style="opacity: 0.6;letter-spacing: 2px;transform:translateY(-25px) rotate(-90deg);">
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
				<div class="col-lg-4 mt-5 mt-lg-0">
					<div class="card h-100" style="min-height: 460px;max-height: 460px;overflow: auto;">
						<!-- header -->
						<div class="card-header bg-white position-sticky p-3" style="z-index: 11;top: 0;">
							<div class="row" style="font-family: 'qc-semibold';">
								<div class="col-6 d-flex flex-column">
									<h5>History</h5>
									<div id="btn-filter-histori" class="d-flex align-items-center mt-3">
										<a class="shadow px-1 pt-1 border-radius-none mr-2" href="" data-toggle="modal" data-target="#modalFilterTransaksi" onclick="openModalFilterT('Filter Histori');">
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

			<div class="container-fluid mt-5 p-0">
				<!-- saldo -->
				<div class="row">
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
								<h5 class="mb-0">
									Rp <span id="saldo-uang">_ _</span>
								</h5>
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
								<h5 class="mb-0">
									<span id="saldo-emas">_ _</span> g
								</h5>
							</div>
						</div>
					</div>
				</div>
				
				<!-- personal info -->
				<div id='personal-info' class="row mt-4 mt-md-5">
					<div class="col-12">
						<div class="card h-100">
							<div class="card-header pb-0 p-3">
								<div class="row">
									<div class="opacity-8 col-12 d-flex justify-content-center">
										<h4 style="font-family: 'qc-medium';">Personal information</h4 >
									</div>
								</div>
							</div>
							<div class="card-body mt-4 p-3">
								<table class="" style="font-family: 'qc-medium';min-width: min-content;">
									<tr class='text-responsive'>
										<td class="py-2 label" style="font-family: 'qc-semibold';">
											<strong>Nama lengkap</strong>&nbsp;&nbsp;
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
									<tr class='text-responsive'>
										<td class="py-2 label" style="font-family: 'qc-semibold';">
											<strong>NIK</strong>
										</td>
										<td class="main"> 
											<span class="titik">:&nbsp;&nbsp;&nbsp;</span>
											<span id="nik">_ _ _ _ _</span>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>

				<!-- Footer -->
				<footer class="footer mt-5">
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

<!-- modals filter transaksi-->
<div class="modal fade" id="modalFilterTransaksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<form id="formFilterTransaksi" class="modal-dialog modal-sm" role="document">
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

<!-- **** Modal Setor **** -->
<div class="modal fade modalTransaksi" id="modalSetorSaldo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  	<form id="formSetorSampah" class="modal-dialog modal-lg">
		<div class="modal-content">

			<!-- modal header -->
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Setor sampah</h5>
				<button type="button" class="close" aria-label="Close" onclick="confirmModalClose('#modalSetorSaldo')">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- modal body -->
			<div class="modal-body row">
				<input type="hidden" name="id_nasabah" value="<?= $idnasabah; ?>">
				
				<!-- **** tgl transaksi **** -->
				<!-- <h6 class="font-italic opacity-8 col-12 text-sm">Waktu transaksi</h6> -->
				<div class="input-group col-12 col-lg-6 mb-5 form-group">
					<div class="w-100 form-row">
						<div class="input-group col-6">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-calendar-alt text-muted"></i>
								</span>
							</div>
							<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date" name="date">
						</div>
						<div class="input-group col-6">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-clock text-muted"></i>
								</span>
							</div>
							<input type="time" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="time" name="time">
						</div>
					</div>
					<small
						id="date-error"
						class="text-danger"></small>
				</div>

				<!-- **** table **** -->
				<!-- <hr class="editnasabah-item horizontal col-12 dark mt-0 mb-4"> -->
				<div class="table-responsive col-12" style="overflow: auto;font-family: 'qc-semibold';">
					<table id="table-setor-sampah" class="table table-sm text-center mb-0">
						<thead class="bg-white" style="border: 0.5px solid #E9ECEF;">
							<tr>
								<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="border-right: 0.5px solid #E9ECEF;">
									#
								</th>
								<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="border-right: 0.5px solid #E9ECEF;">
									no
								</th>
								<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="border-right: 0.5px solid #E9ECEF;">
									kategori
								</th>
								<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="border-right: 0.5px solid #E9ECEF;">
									jenis
								</th>
								<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="border-right: 0.5px solid #E9ECEF;">
									jumlah(kg)
								</th>
								<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
									harga
								</th>
							</tr>
						</thead>
						<tbody style="border: 0.5px solid #E9ECEF;">
							<tr id="special-tr">
								<td colspan="5" class="py-2" style="border-right: 0.5px solid #E9ECEF;">
									Total harga
								</td>
								<td class="p-2 text-left">
									Rp. <span id="total-harga"></span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<!-- tambah baris -->
				<div class="input-group col-12 mt-2">
					<a href="" class="badge badge-info w-100 border-radius-sm" onclick="tambahBaris(event);">
						<i class="fas fa-plus text-white"></i>
					</a>
				</div>
			</div>

			<!-- modal footer -->
			<div class="modal-footer">
				<button id="submit" type="submit" class="btn btn-success d-flex justify-content-center align-items-center" style="height: 40.8px;" onclick="doTransaksi(this,event,'setorsampah');">
					<span id="text">Submit</span>
					<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 20px;">
				</button>
			</div>
		</div>
	</form>
</div>

<!-- **** Modal Pindah Saldo **** -->
<div class="modal fade modalTransaksi" id="modalPindahSaldo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<form id="formPindahSaldo" class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<!-- modal header -->
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Transaksi konversi saldo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- modal body -->
			<div class="modal-body form-row">
				<input type="hidden" name="id_nasabah" value="<?= $idnasabah; ?>">
				
				<!-- **** tgl transaksi **** -->
				<h6 class="font-italic opacity-8 col-12 text-sm">Waktu transaksi</h6>
				<div class="input-group col-12 mb-4 form-group">
					<div class="w-100 form-row">
						<div class="input-group col-6">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-calendar-alt text-muted"></i>
								</span>
							</div>
							<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date" name="date">
						</div>
						<div class="input-group col-6">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-clock text-muted"></i>
								</span>
							</div>
							<input type="time" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="time" name="time">
						</div>
					</div>
					<small
						id="date-error"
						class="text-danger"></small>
				</div>

				<!-- **** harga emas **** -->
				<h6 class="font-italic opacity-8 col-12 text-sm">
					Harga emas <small>(saat ini)</small>
				</h6>
				<div class="input-group col-12 mb-4 form-group">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text bg-gray px-4 border-md">
								<i class="fas fa-coins text-muted"></i>
							</span>
						</div>
						<input type="text" class="form-control form-control-sm px-2 h-100" id="harga_emas" name="harga_emas" placeholder="contoh: 900000" autocomplete="off">
					</div>
					<small
						id="harga_emas-error"
						class="text-danger"></small>
				</div>
				
				<!-- **** jumlah **** -->
				<h6 class="font-italic opacity-8 col-12 text-sm">
					Jumlah uang
				</h6>
				<small class="col-12 text-xs">max: <span id="maximal-saldo"></span></small>
				<div class="input-group col-12 mt-1 mb-4 form-group">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text bg-gray px-4 border-md">
							<i class="fas fa-money-bill-wave-alt text-muted"></i>
							</span>
						</div>
						<input type="text" class="form-control form-control-sm px-2 h-100" id="jumlah" name="jumlah" placeholder="contoh: 10000" autocomplete="off">
					</div>
					<small
						id="jumlah-error"
						class="text-danger"></small>
				</div>
			</div>

			<!-- modal footer -->
			<div class="modal-footer">
				<button id="submit" type="submit" class="btn btn-success d-flex justify-content-center align-items-center" style="height: 40.8px;" onclick="doTransaksi(this,event,'pindahsaldo');">
					<span id="text">Submit</span>
					<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 20px;">
				</button>
			</div>
		</div>
	</form>
</div>

<!-- **** Modal Tarik Saldo **** -->
<div class="modal fade modalTransaksi" id="modalTarikSaldo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<form id="formTarikSaldo" class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<!-- modal header -->
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Transaksi tarik saldo</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- modal body -->
			<div class="modal-body form-row">
				<input type="hidden" name="id_nasabah" value="<?= $idnasabah; ?>">
				
				<!-- **** tgl transaksi **** -->
				<h6 class="font-italic opacity-8 col-12 text-sm">Waktu transaksi</h6>
				<div class="input-group col-12 mb-4 form-group">
					<div class="w-100 form-row">
						<div class="input-group col-6">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-calendar-alt text-muted"></i>
								</span>
							</div>
							<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date" name="date">
						</div>
						<div class="input-group col-6">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-clock text-muted"></i>
								</span>
							</div>
							<input type="time" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="time" name="time">
						</div>
					</div>
					<small
						id="date-error"
						class="text-danger"></small>
				</div>

				<!-- **** jenis saldo **** -->
				<h6 class="font-italic opacity-8 col-12 text-sm">
					Jenis saldo
				</h6>
				<div class="input-group col-12 mb-4 form-group form-row">
					<div class="input-group col-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="jenis_saldo" id="tarikUang" value="uang" checked>
							<label class="form-check-label" for="tarikUang">
								Uang
							</label>
						</div>
					</div>
					<div class="input-group col-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="jenis_saldo" id="tarikEmas" value="emas">
							<label class="form-check-label" for="tarikEmas">
								Emas
							</label>
						</div>
					</div>
				</div>
				
				<!-- **** jumlah **** -->
				<h6 class="font-italic opacity-8 col-12 text-sm">
					Jumlah saldo
				</h6>
				<small class="col-12 text-xs">saldo: <span id="maximal-saldo"></span></small>
				<div class="input-group col-12 mt-1 mb-4 form-group">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text bg-gray px-4 border-md">
							<i class="fas fa-money-bill-wave-alt text-muted"></i>
							</span>
						</div>
						<input type="text" class="form-control form-control-sm px-2 h-100" id="jumlah" name="jumlah" placeholder="contoh: 1.1" autocomplete="off">
					</div>
					<small
						id="jumlah-error"
						class="text-danger"></small>
				</div>

				<!-- Jenis emas -->
				<h6 class="font-italic opacity-8 col-12 text-sm">
					Jenis emas
				</h6>
				<div class="input-group col-12 mt-1 mb-4 form-group">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text bg-gray px-4 border-md" style="min-height: 32.4px;max-height: 32.4px">
								<i class="fas fa-list-ul mr-1 text-muted"></i>
							</span>
						</div>
						<select id="jenis-emas" name="jenis_emas" class="form-control py-1 px-2" style="min-height: 32.4px;max-height: 32.4px" disabled>
							<option value="">-- jenis emas --</option>
							<option value="ubs">ubs</option>
							<option value="antam">antam</option>
							<option value="galery24">galery24</option>
						</select>
					</div>
				</div>
			</div>

			<!-- modal footer -->
			<div class="modal-footer">
				<button id="submit" type="submit" class="btn btn-success d-flex justify-content-center align-items-center" style="height: 40.8px;" onclick="doTransaksi(this,event,'tariksaldo');">
					<span id="text">Submit</span>
					<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 20px;">
				</button>
			</div>
		</div>
	</form>
</div>

<!-- modals filter rekap transaksi-->
<div class="modal fade" id="modalRekapTransaksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content" style="overflow: hidden;">

			<!-- modal header -->
			<div class="modal-header">
				<h6 class="modal-title">Rekap transaksi</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- modal body -->
			<div class="modal-body form-row w-100 px-3">
				<form id="formRekapTransaksi" class="mt-3 col-12">
					<h6 class="font-italic text-xs text-secondary">Jenis Rekap</h6>
					<div class="mt-2 position-relative">
						<select class='form-control form-control-sm' name="jenis">
							<option value="penimbangan-sampah">penimbangan sampah</option>
							<option value="buku-tabungan">buku tabungan</option>
						</select>
						<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:6px;right:10px;"></i>
					</div>
					<h6 class="font-italic text-xs text-secondary mt-3 input-start-date">From</h6>
					<div class="input-group col-12 px-0 input-start-date">
						<div class="input-group-prepend">
							<span class="input-group-text bg-gray px-4 border-md">
								<i class="fas fa-calendar-alt text-muted"></i>
							</span>
						</div>
						<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date-start" name="date-start">
					</div>
					<h6 class="font-italic text-xs text-secondary mt-3 input-end-date">To</h6>
					<div class="input-group col-12 px-0 input-end-date">
						<div class="input-group-prepend">
							<span class="input-group-text bg-gray px-4 border-md">
								<i class="fas fa-calendar-alt text-muted"></i>
							</span>
						</div>
						<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date-end" name="date-end">
					</div>
				</form>
			</div>

			<!-- modal footer -->
			<div id="modal-footer-custom" class="modal-footer mt-4">
				<button id="btn-filter-rekap-custom" class="badge badge-success d-flex justify-content-center align-items-center border-0 cursor-pointer" onclick="cetakRekap();">
					<span>Cetak</span>
				</button>
			</div>
		</div>
	</div>
</div>

<!-- **** Modal Edit Setor **** -->
<div class="modal fade" id="modalEditSetor" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  	<form id="formEditSetor" class="modal-dialog modal-lg">
		<div class="modal-content" style="position: relative;overflow: hidden;">
			<!-- spinner -->
			<div id="spinner" class="position-absolute bg-white d-flex align-items-center justify-content-center d-none" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
				<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 40px;" />
			</div>

			<!-- modal header -->
			<div class="modal-header" style="position: relative;;z-index:1;">
				<h5 class="modal-title" id="exampleModalLongTitle">Edit Setor Sampah</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- modal body -->
			<div class="modal-body row" style="position: relative;;z-index:1;">
				<input type="hidden" name="id_nasabah" value="">
				<input type="hidden" name="id_transaksi" value="">
				
				<!-- **** tgl transaksi **** -->
				<!-- <h6 class="font-italic opacity-8 col-12 text-sm">Waktu transaksi</h6> -->
				<div class="input-group col-12 col-lg-6 mb-5 form-group">
					<div class="w-100 form-row">
						<div class="input-group col-6">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-calendar-alt text-muted"></i>
								</span>
							</div>
							<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date" name="date">
						</div>
						<div class="input-group col-6">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-clock text-muted"></i>
								</span>
							</div>
							<input type="time" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="time" name="time">
						</div>
					</div>
					<small
						id="date-error"
						class="text-danger"></small>
				</div>

				<!-- **** table **** -->
				<!-- <hr class="editnasabah-item horizontal col-12 dark mt-0 mb-4"> -->
				<div class="table-responsive col-12" style="overflow: auto;font-family: 'qc-semibold';">
					<table id="table-edit-setor" class="table table-sm text-center mb-0">
						<thead class="bg-white" style="border: 0.5px solid #E9ECEF;">
							<tr>
								<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="border-right: 0.5px solid #E9ECEF;">
									#
								</th>
								<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="border-right: 0.5px solid #E9ECEF;">
									no
								</th>
								<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="border-right: 0.5px solid #E9ECEF;">
									kategori
								</th>
								<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="border-right: 0.5px solid #E9ECEF;">
									jenis
								</th>
								<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="border-right: 0.5px solid #E9ECEF;">
									jumlah(kg)
								</th>
								<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
									harga
								</th>
							</tr>
						</thead>
						<tbody style="border: 0.5px solid #E9ECEF;">
							<tr id="special-tr">
								<td colspan="5" class="py-2" style="border-right: 0.5px solid #E9ECEF;">
									Total harga
								</td>
								<td class="p-2 text-left">
									Rp. <span id="total-harga"></span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<!-- tambah baris -->
				<div class="input-group col-12 mt-2">
					<a href="" class="badge badge-info w-100 border-radius-sm" onclick="tambahBarisEditSetor(event);">
						<i class="fas fa-plus text-white"></i>
					</a>
				</div>
			</div>

			<!-- modal footer -->
			<div class="modal-footer" style="position: relative;z-index:1;">
				<button id="submit" type="submit" class="btn btn-success d-flex justify-content-center align-items-center" style="height: 40.8px;" onclick="doEditSetor(this,event);">
					<span id="text">Submit</span>
					<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 20px;">
				</button>
			</div>
		</div>
	</form>
</div>
<?= $this->endSection(); ?>