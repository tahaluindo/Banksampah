<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
		html,body{
			height:100%;
			/* background-color: #b2b8c2; */
		}

		.toggle-transaksi,.toggle-rekap,.switch-section{
			transition: all 0.3s;
		}
		.switch-section:hover{
			background-color: rgba(212, 212, 212, 0.3);
		}

		/* #table-rekap-transaksi{
			border-spacing: 0px;
			border-collapse: separate !important;
		} */
		/* #table-rekap-transaksi th{
			border: 0.3px solid #454D55 !important;
		} */
		
		.detil-transaksi-logo img{
			width: 80px;
		}
	</style>
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/soft-ui-dashboard.min.css'); ?>">
<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->section('contentJs'); ?>
	<script>
		const IDADMIN   = '<?= $idadmin; ?>';
		const PASSADMIN = '<?= $password; ?>';
	</script>
	<script src="<?= base_url('assets/js/core/jquery-2.1.0.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/core/bootstrap.min.js'); ?>"></script>
  	<script src="<?= base_url('assets/js/plugins/font-awesome.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/parent.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/admin.transaksi.min.js'); ?>"></script>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

	<!-- **** Loading Spinner **** -->
	<?= $this->include('Components/loadingSpinner'); ?>
	<!-- **** Alert Info **** -->
	<?= $this->include('Components/alertInfo'); ?>

	<body class="g-sidenav-show bg-gray-100">
		<!-- **** Sidebar **** -->
		<?= $this->include('Components/adminSidebar'); ?>

		<main class="main-content position-relative mt-1 border-radius-lg">
			<!-- navbar -->
			<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl position-sticky" style="top: 20px;z-index:100;">
				<div class="container-fluid py-1 px-3">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
							<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
							<li class="breadcrumb-item text-sm text-dark active" aria-current="page">transaksi</li>
						</ol>
						<h6 class="font-weight-bolder mb-0">Halaman Transaksi</h6>
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
			<div class="container-fluid py-4 d-flex flex-column" style="flex: 1;overflow: hidden;">

				<!-- Insert transaksi  -->
				<div id="tambah-transaksi" class="row" style="flex: 1;">
					<div class="col-12 h-100">
						<div class="card h-100 d-flex flex-column" style="font-family: 'qc-semibold';overflow: hidden;">
							<!-- toggle switch -->
							<div class="form-row mt-2 py-2 d-flex justify-content-center" style="font-family: 'qc-semibold';">
								<div id="toggle-transaksi-wraper" class="position-relative p-0 d-flex align-items-center text-xxs" style="overflow: hidden;border-radius: 12px;width: 320px;height: 28px;box-shadow: inset 0 0 4px 0px rgba(0, 0, 0, 0.4);">
									<div class="toggle-transaksi position-absolute d-flex justify-content-center align-items-center bg-success opacity-7 text-white" data-color="success" style="width: 78px;height: 26px;z-index: 10;left: 1px;border-radius: 10px;">setor sampah</div>

									<div class="switch-section h-100 d-flex justify-content-center align-items-center cursor-pointer position-relative opacity-0" data-form="setor-jual-sampah" data-color="success" style="flex: 1;z-index: 9;border-radius: 10px;">setor sampah</div>
									<div class="switch-section h-100 d-flex justify-content-center align-items-center cursor-pointer position-relative" data-form="konversi-saldo" data-color="warning" style="flex: 1;z-index: 9;border-radius: 10px;">konversi saldo</div>
									<div class="switch-section h-100 d-flex justify-content-center align-items-center cursor-pointer position-relative" data-form="tarik-saldo" data-color="danger" style="flex: 1;z-index: 9;border-radius: 10px;">tarik saldo</div>
									<div class="switch-section h-100 d-flex justify-content-center align-items-center cursor-pointer position-relative" data-form="setor-jual-sampah" data-color="info" style="flex: 1;z-index: 9;border-radius: 10px;">jual sampah</div>
								</div>
							</div>

							<!-- search nasabah -->
							<div id="pemilik-saldo-wraper" class="form-row p-0 d-flex justify-content-center mt-2 px-4 mb-4 d-none">
								<div class="position-relative" style="width: 320px;border-radius: 0.5rem;border: 1px solid #d2d6da;">
									<select id="pemilik-saldo" class='form-control form-control-sm' name="pemilik-saldo" style="border: none">
										<option value="nasabah" selected>saldo nasabah</option>
										<option value="bsbl">saldo bsbl</option>
									</select>
									<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:7px;right:10px;"></i>
								</div>
							</div>
							<div id="search-nasabah-wraper" class="form-row p-0 d-flex mt-4 px-4 position-relative" style="font-family: 'qc-semibold';">
								<div id="barrier-search-nasabah" class="position-absolute d-none" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">

								</div>	
								<div class="input-group col-12 col-sm-3">
									<div class="input-group-prepend">
										<span class="input-group-text bg-gray pl-2 pr-2 border-md border-right-0" style="max-height: 28px;border-radius: 4px;">
											<i class="fas fa-search text-muted"></i>
										</span>
									</div>
									<input id="search-nasabah" type="text" class="form-control form-control-sm h-100 px-2" placeholder="id nasabah/username/nama" style="max-height: 28px;border-radius: 0px 4px 4px 0px;">
								</div>
								<div class="input-group col-12 col-sm-1 w-100" style="min-width: 50px;">
									<button id="btn-search-nasabah" class="btn btn-dark h-100 mt-2 mt-sm-0 text-xxs p-1" data-toggle="modal" data-target="#modalAddEditAdmin" onclick="searchNasabah(this,event)" style="width: 100%;max-height: 28px;border-radius: 4px;">
										<span id="text">check</span>
										<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 18px;">
									</button>
								</div>
								<div class="input-group p-0 col-12 mt-2 px-1 d-flex">
									<table class="mr-5">
										<tr>
											<td class="">id</td>
											<td>:&nbsp;&nbsp; <span id="id-check"></span></td>
										</tr>
										<tr>
											<td class="">email</td>
											<td>:&nbsp;&nbsp; <span id="email-check"></span></td>
										</tr>
										<tr>
											<td class="">username</td>
											<td>:&nbsp;&nbsp; <span id="username-check"></span></td>
										</tr>
										<tr>
											<td class="">nama lengkap&nbsp;&nbsp;</td>
											<td>:&nbsp;&nbsp; <span id="nama-lengkap-check"></span></td>
										</tr>
										<tr>
											<td class="">Saldo Uang</td>
											<td>:&nbsp;&nbsp; <span id="saldo-uang-check"></span></td>
										</tr>
										<tr>
											<td class="">Saldo Emas</td>
											<td>:&nbsp;&nbsp; <span id="saldo-emas-check"></span></td>
										</tr>
									</table>
								</div>
							</div>
							<hr class="horizontal dark mt-4 mb-0">

							<!-- container add transaksi -->
							<div id="formWraper" class="px-2 pt-4 position-relative" style="font-family: 'qc-semibold';">
								<div id="barrier-transaksi" class="position-absolute" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">

								</div>	

								<!-- form setor AND jual sampah -->
								<form id="form-setor-jual-sampah" class="w-100 position-relative opacity-6" style="z-index: 9;">
									<div class="modal-body form-row">
										<!-- **** tgl transaksi **** -->
										<div class="input-group col-12 col-md-6 mt-2 mb-4 form-group">
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

									<div class="modal-footer form-row">
										<button id="submit" type="submit" class="btn btn-success d-flex justify-content-center align-items-center" style="height: 40.8px;" onclick="doTransaksi(this,event,'setorjualsampah');">
											<span id="text">Submit</span>
											<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 20px;">
										</button>
									</div>
								</form>

								<!-- form konversi saldo -->
								<form id="form-konversi-saldo" class="w-100 position-relative opacity-6 d-none" style="z-index: 9;">
									<div class="modal-body form-row">
										<!-- **** tgl transaksi **** -->
										<h6 class="font-italic opacity-8 col-12 text-sm">Waktu transaksi</h6>
										<div class="input-group col-12 col-md-6 mb-4 form-group">
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
										<div class="input-group col-12 col-md-6 mb-4 form-group">
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
										<div class="input-group col-12 col-md-6 mb-4 form-group">
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

									<div class="modal-footer form-row">
										<button id="submit" type="submit" class="btn btn-success d-flex justify-content-center align-items-center" style="height: 40.8px;" onclick="doTransaksi(this,event,'pindahsaldo');">
											<span id="text">Submit</span>
											<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 20px;">
										</button>
									</div>
								</form>

								<!-- form tarik saldo -->
								<form id="form-tarik-saldo" class="w-100 position-relative opacity-6 d-none" style="z-index: 9;">
									<div class="modal-body form-row">
										<!-- **** tgl transaksi **** -->
										<h6 class="font-italic opacity-8 col-12 text-sm">Waktu transaksi</h6>
										<div class="input-group col-12 col-md-6 mb-4 form-group">
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
										<div id="input-jenis-saldo" class="col-12 row">
											<h6 class="font-italic opacity-8 col-12 text-sm">
												Jenis saldo
											</h6>
											<div class="input-group col-12 col-md-6 mb-4 form-group form-row">
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
										</div>
										
										<!-- **** jumlah **** -->
										<h6 class="font-italic opacity-8 col-12 text-sm">
											Jumlah saldo
										</h6>
										<small id="maximal-saldo" class="col-12 text-xs">
											saldo: <span></span>
										</small>
										<small id="maximal-saldo-bsbl" class="col-12 text-xs d-none">
											saldo: <span></span>
										</small>
										<div class="input-group col-12 col-md-6 mt-1 mb-4 form-group">
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
										<div id="input-jenis-emas" class="col-12 row">
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

										<!-- **** description **** -->
										<h6 class="font-italic opacity-8 col-12 text-sm keterangan d-none">
											Keterangan
										</h6>
										<div class="input-group col-12 col-md-6 mt-1 mb-4 form-group  keterangan d-none">
											<div class="input-group">
												<input type="text" class="form-control form-control-sm px-2 py-2 h-100" id="description" name="description" autocomplete="off">
											</div>
											<small
												id="description-error"
												class="text-danger"></small>
										</div>
									</div>

									<div class="modal-footer form-row">
										<button id="submit" type="submit" class="btn btn-success d-flex justify-content-center align-items-center" style="height: 40.8px;" onclick="doTransaksi(this,event,'tariksaldo');">
											<span id="text">Submit</span>
											<img id="spinner" class="d-none" src="<?= base_url('assets/images/spinner-w.svg');?>" style="width: 20px;">
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<!-- Semua transaksi -->
				<div id="semua-transaksi" class="row mt-5">
					<div class="col-12 mb-md-0 mb-4">
						<div class="card" style="overflow: hidden;">
							<div class="card-header pb-0">
								<!-- tittle -->
								<div class="row">
									<div class="col-12">
										<h5 class="text-center">Semua Transaksi</h5>
									</div>
								</div>
								<div class="form-row pb-0 mt-3 d-flex justify-content-between" style="font-family: 'qc-semibold';">
									<!-- search -->
									<div class="input-group col-12 col-sm-6">
										<div class="input-group-prepend">
											<span class="input-group-text px-4 border-md border-right-0" style="max-height: 39px;">
												<i class="fas fa-search text-muted"></i>
											</span>
										</div>
										<input id="search-data-transaksi" type="text" class="form-control h-100 px-2" placeholder="id transaksi/nama lengkap" style="max-height: 39px;">
									</div>
									<!-- Btn filter -->
									<div class="input-group mt-3 col-12 flex-column text-sm">
										<div class="d-flex align-items-center text-secondary">
											<a class="shadow px-1 border-radius-none mr-2" href="" data-toggle="modal" data-target="#modalFilterDataTransaksi" style="border-radius: 4px;" onclick="openModalFilterDataT();">
												<i class="fas fa-sliders-h text-muted"></i>
											</a>
											<span id="ket-filter-data-transaksi" class="">
												00/00/0000 
												<i class="fas fa-long-arrow-alt-right mt-1 mx-2"></i>
												00/00/0000
												&nbsp;&nbsp;(terbaru - semua jenis)
											</span>
										</div>
										<div class="mt-2 text-xs">
											<span id="ket-total">0</span> transaksi
										</div>
									</div>
								</div>
							</div>
							<div class="card-body mt-3 pt-0 px-0 pb-2">
								<div class="table-responsive position-relative" style="min-height: 380px;max-height: 380px;overflow: auto;font-family: 'qc-semibold';">
									<!-- spinner -->
									<div id="data-transaksi-spinner" class="position-absolute d-flex align-items-center justify-content-center pt-4" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
										<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
									</div>
									<!-- message not found -->
									<div id="data-transaksi-notfound" class="d-none position-absolute d-flex align-items-center justify-content-center pt-5" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
										<h6 id="text-notfound" class='opacity-6'></h6>
									</div>
									<!-- table -->
									<table id="table-data-transaksi" class="table table-striped text-left mb-0">
										<thead class="position-sticky bg-white" style="z-index: 11;top: 0;">
											<tr>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">
													ID Transaksi
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">
													Nama User
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">
													Jenis transaksi
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">
													Jumlah
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">
													Tgl
												</th>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">
													Action
												</th>
											</tr>
										</thead>
										<tbody>
											<?php for ($i=0; $i < 20 ; $i++) { ?>
											
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Rekap transaksi -->
				<div id="rekap-transaksi" class="row mt-5">
					<div class="col-12 mb-md-0 mb-4">
						<div class="card" style="overflow: hidden;">
							<div class="card-header pb-0">
								<!-- tittle -->
								<div class="row">
									<div class="col-12">
										<h5 class="text-center">Rekap Transaksi</h5>
									</div>
								</div>
								<div class="form-row pb-0 mt-3 d-flex justify-content-between" style="font-family: 'qc-semibold';">
									<!-- Btn filter -->
									<div class="d-flex align-items-center text-sm">
										<a class="shadow px-1 border-radius-none mr-2" href="" data-toggle="modal" data-target="#modalFilterRekapTransaksi" style="border-radius: 4px;" onclick="openModalFilterRekapT();">
											<i class="fas fa-sliders-h text-muted"></i>
										</a>
										<span id="ket-filter-rekap-transaksi" class=""><?= (int)date("Y"); ?> - semua wilayah</span>
									</div>
								</div>
							</div>
							<div class="card-body mt-3 pt-0 px-0 pb-2">
								<div class="table-responsive position-relative" style="min-height: 380px;max-height: 380px;overflow: auto;font-family: 'qc-semibold';">
									<!-- spinner -->
									<div id="rekap-transaksi-spinner" class="position-absolute d-flex align-items-center justify-content-center pt-4" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">
										<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 30px;" />
									</div>
									<!-- message not found -->
									<div id="rekap-transaksi-notfound" class="d-none position-absolute d-flex align-items-center justify-content-center pt-5" style="z-index: 10;top: 0;bottom: 0;left: 0;right: 0;">

									</div>
									<!-- table -->
									<table id="table-rekap-transaksi" class="table table-striped text-left mb-0">
										<thead class="position-sticky" style="z-index: 11;top: 0;">
											<tr style="background-color: rgba(255,255,255,0.2);">
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7" style="border-right: 0.5px solid #DEE2E6;">
													#
												</th>
												<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7" style="border-right: 0.5px solid #DEE2E6;">
													Action
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7" style="border-right: 0.5px solid #DEE2E6;">
													Waktu
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7" style="border-right: 0.5px solid #DEE2E6;">
													Setor sampah
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7" style="border-right: 0.5px solid #DEE2E6;">
													Jual sampah
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7" style="border-right: 0.5px solid #DEE2E6;">
													Uang masuk
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7" style="border-right: 0.5px solid #DEE2E6;">
													Tarik uang
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7" style="border-right: 0.5px solid #DEE2E6;">
													Konversi emas
												</th>
												<th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">
													Tarik emas
												</th>
											</tr>
										</thead>
										<tbody>
											<?php for ($i=0; $i < 20 ; $i++) { ?>
											
											<?php } ?>
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

	<!-- modals filter transaksi-->
	<div class="modal fade" id="modalFilterDataTransaksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<form id="formFilterDataTransaksi" class="modal-dialog" role="document">
			<div class="modal-content" style="overflow: hidden;">

				<!-- modal header -->
				<div class="modal-header">
					<h5 class="modal-title">filter transaksi</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<!-- modal body -->
				<div class="modal-body w-100 px-3">
					<h6 class="font-italic text-xs text-secondary">From</h6>
					<div class="input-group col-12 px-0">
						<div class="input-group-prepend">
							<span class="input-group-text bg-gray px-4 border-md">
								<i class="fas fa-calendar-alt text-muted"></i>
							</span>
						</div>
						<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date-start" name="date-start">
					</div>
					<h6 class="font-italic text-xs text-secondary mt-4">To</h6>
					<div class="input-group col-12 px-0">
						<div class="input-group-prepend">
							<span class="input-group-text bg-gray px-4 border-md">
								<i class="fas fa-calendar-alt text-muted"></i>
							</span>
						</div>
						<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date-end" name="date-end">
					</div>
					<h6 class="font-italic text-xs text-secondary mt-4">Urutan</h6>
					<div class="position-relative">
						<select class='form-control form-control-sm' name="orderby">
							<option value="terbaru" selected>terbaru</option>
							<option value="terlama">terlama</option>
						</select>
						<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:6px;right:10px;"></i>
					</div>
					<h6 class="font-italic text-xs text-secondary mt-4">Jenis transaksi</h6>				
					<div class="input-group col-12 form-group form-row px-0 text-xs">
						<div class="input-group col-4">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="jenis_transaksi" id="setorSampah" value="penyetoran sampah" style="padding: 4px;">
								<label class="form-check-label" for="setorSampah">
									Setor sampah
								</label>
							</div>
						</div>
						<div class="input-group col-4">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="jenis_transaksi" id="konversiSaldo" value="konversi saldo" style="padding: 4px;">
								<label class="form-check-label" for="konversiSaldo">
									Konversi saldo
								</label>
							</div>
						</div>
						<div class="input-group col-4">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="jenis_transaksi" id="tarikSaldo" value="penarikan saldo" style="padding: 4px;">
								<label class="form-check-label" for="tarikSaldo">
									Tarik saldo
								</label>
							</div>
						</div>
						<div class="input-group col-4 mt-2">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="jenis_transaksi" id="jualSampah" value="penjualan sampah" style="padding: 4px;">
								<label class="form-check-label" for="jualSampah">
									Jual sampah
								</label>
							</div>
						</div>
						<div class="input-group col-4 mt-2">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="jenis_transaksi" id="semuaJenis" value="semua jenis" style="padding: 4px;" checked>
								<label class="form-check-label" for="semuaJenis">
									Semua jenis
								</label>
							</div>
						</div>
					</div>
				</div>

				<!-- modal footer -->
				<div class="modal-footer">
					<span id="btn-filter-data-transaksi" class="badge badge-success d-flex justify-content-center align-items-center border-0 cursor-pointer" data-dismiss="modal" onclick="filterDataTransaksi(this,event);">
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
								<td id="id-user">ID.NASABAH</td>
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

	<!-- modals filter rekap transaksi-->
	<div class="modal fade" id="modalFilterRekapTransaksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content" style="overflow: hidden;">

				<!-- modal header -->
				<div class="modal-header">
					<h6 class="modal-title">filter rekap transaksi</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<!-- modal body -->
				<div class="modal-body form-row w-100 px-3">

					<div class="col-12 d-flex justify-content-center">
						<div id="toggle-rekap-wraper" class="position-relative p-0 d-flex align-items-center text-xxs" style="overflow: hidden;border-radius: 12px;width: 160px;height: 28px;box-shadow: inset 0 0 4px 0px rgba(0, 0, 0, 0.4);">
							<div class="toggle-rekap position-absolute d-flex justify-content-center align-items-center bg-success opacity-7 text-white" data-color="success" style="width: 78px;height: 26px;z-index: 10;left: 1px;border-radius: 10px;">per-tahun</div>

							<div class="switch-section h-100 d-flex justify-content-center align-items-center cursor-pointer position-relative opacity-0" data-form="pertahun" data-color="success" style="flex: 1;z-index: 9;border-radius: 10px;">per-tahun</div>
							<div class="switch-section h-100 d-flex justify-content-center align-items-center cursor-pointer position-relative" data-form="custom" data-color="warning" style="flex: 1;z-index: 9;border-radius: 10px;">custom</div>
						</div>
					</div>

					<form id="formFilterRekap-pertahun" class="mt-3 col-12">
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
					</form>

					<form id="formFilterRekap-custom" class="mt-3 col-12 d-none">
						<h6 class="font-italic text-xs text-secondary">From</h6>
						<div class="input-group col-12 px-0">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-calendar-alt text-muted"></i>
								</span>
							</div>
							<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date-start" name="date-start">
						</div>
						<h6 class="font-italic text-xs text-secondary mt-3">To</h6>
						<div class="input-group col-12 px-0">
							<div class="input-group-prepend">
								<span class="input-group-text bg-gray px-4 border-md">
									<i class="fas fa-calendar-alt text-muted"></i>
								</span>
							</div>
							<input type="date" class="form-control form-control-sm px-2 h-100 border-radius-sm" id="date-end" name="date-end">
						</div>
						<h6 class="font-italic text-xs text-secondary mt-3">Jenis Rekap</h6>
						<div class="mt-2 position-relative">
							<select class='form-control form-control-sm' name="jenis">
								<option value="penimbangan-sampah">penimbangan sampah</option>
								<option value="penarikan-saldo">penarikan saldo</option>
								<option value="penjualan-sampah">penjualan sampah</option>
								<option value="konversi-saldo">konversi saldo</option>
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
					</form>
				</div>

				<!-- modal footer -->
				<div id="modal-footer-pertahun" class="modal-footer">
					<a href="" class="badge badge-secondary d-flex justify-content-center align-items-center border-0 cursor-pointer" onclick="resetFilterRekap(event);">
						<span>Reset</span>
					</a>
					<a href="" class="badge badge-success d-flex justify-content-center align-items-center border-0 cursor-pointer" data-dismiss="modal" onclick="doFilterRekapPertahun();">
						<span>Ok</span>
					</a>
				</div>
				<div id="modal-footer-custom" class="modal-footer d-none">
					<button id="btn-filter-rekap-custom" class="badge badge-success d-flex justify-content-center align-items-center border-0 cursor-pointer" onclick="cetakCustomRekap();">
						<span>Cetak</span>
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- **** Modal jenis transaksi **** -->
	<div class="modal fade" id="modalJenisLaporan" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<form id="formJenisLaporan" class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Pilih Jenis Rekap</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body w-100 position-relative" style="overflow: hidden;">
					<input type="hidden" name="date">
					<div class="mt-2 position-relative">
						<select class='form-control form-control-sm' name="jenis">
							<option value="">-- jenis rekap --</option>
							<option value="penimbangan-sampah">penimbangan sampah</option>
							<option value="penarikan-saldo">penarikan saldo</option>
							<option value="penjualan-sampah">penjualan sampah</option>
							<option value="konversi-saldo">konversi saldo</option>
						</select>
						<i class="fas fa-sort-down text-secondary text-xs" style="position: absolute;top:6px;right:10px;"></i>
					</div>
				</div>
				<div class="modal-footer">
					<button id="btn-filter-rekap-custom" class="badge badge-success d-flex justify-content-center align-items-center border-0 cursor-pointer">
						<span>Cetak</span>
					</button>
				</div>
			</form>
		</div>
	</div>
<?= $this->endSection(); ?>