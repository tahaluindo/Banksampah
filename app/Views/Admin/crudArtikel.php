<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
	#editor-container {
		min-height: 450px;
		border-radius : 10px;
		border: 0.5px solid #D2D6DA;
	}
	#toolbar-container {
		border-radius : 10px;
		margin-bottom: 20px;
	}
	#table-kategori-artikel th {
		border-top: 0px !important;
	}
	</style>
  	<!-- ** develoment ** -->
	<!-- <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>"> -->
	<!-- ** production ** -->
	<link rel="stylesheet" href="<?= base_url('assets/css/purge/bootstrap/admin.crudartikel.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/soft-ui-dashboard.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/quill.snow.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/katex.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/monokai-sublime.min.css'); ?>">
<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->section('contentJs'); ?>
	<script>
		const IDARTIKEL = '<?= (isset($idartikel)) ? $idartikel : '' ; ?>';
		const PASSADMIN = '<?= $password; ?>';
	</script>
	<script src="<?= base_url('assets/js/core/jquery-2.1.0.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/core/bootstrap.min.js'); ?>"></script>
  	<script src="<?= base_url('assets/js/plugins/font-awesome.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/plugins/katex.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/plugins/highlight.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/plugins/quill.imageCompressor.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/plugins/quill.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/plugins/image-resize.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/plugins/compress.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/parent.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/admin.crudArtikel.min.js'); ?>"></script>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

	<!-- **** Loading Spinner **** -->
	<?= $this->include('Components/loadingSpinner'); ?>
	<!-- **** Alert Info **** -->
	<?= $this->include('Components/alertInfo'); ?>

	<body class="g-sidenav-show bg-gray-100">
		<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main"  style="font-family: 'qc-semibold';">
			<div class="sidenav-header">
				<a class="nav-link mt-4" href="<?= base_url('admin/listartikel');?>" style="display: flex;align-items: center;">
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
					<span class="nav-link-text ms-1">kembali</span>
				</a>
				<hr class="horizontal dark mt-2">
			</div>
		</aside>

		<main class="main-content position-relative h-100 mt-1 border-radius-lg"">
			<!-- navbar -->
			<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl position-sticky" style="top: 20px;z-index:100;">
				<div class="container-fluid py-1 px-3">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
							<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
							<li class="breadcrumb-item text-sm text-dark active" aria-current="page">artikel</li>
						</ol>
						<h6 class="font-weight-bolder mb-0">
							<?= (isset($idartikel)) ? 'Tambah' : 'Edit' ; ?> Artikel 
						</h6>
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
			
			<!-- form -->
			<form id="formCrudArticle" class="pl-4 pr-5 pb-5 mt-2" style="font-family: 'qc-medium';">
				<!-- Button 1 -->
				<div style="font-family: 'qc-medium';" class="row justify-content-end">
					<button type="" class="mt-1 btn btn-success col-12 col-sm-2" style="min-width:170px;letter-spacing: 1px;">
						<i class="far fa-paper-plane mr-1"></i>
						<?= (isset($idartikel)) ? 'Edit' : 'Publikasikan' ; ?> 
					</button>
				</div>
				
				<hr>
				
				<input type="hidden" name="id" id="idartikel">
				
				<!-- Thumbnail preview -->
				<div class="form-row mt-4 d-flex flex-column">
					<div id="thumbnail-wraper" class="position-relative col-12 col-sm-6 mt-1 mb-2">
						<!-- spinner -->
						<div id="thumbnail-spinner" class="img-thumbnail d-none position-absolute bg-white d-flex align-items-center justify-content-center pt-4" style="z-index: 11;top: 0;bottom: 0;left: 0;right: 0;">
							<img src="<?= base_url('assets/images/spinner.svg');?>" style="width: 20px;" />
						</div>
						<img src="<?= base_url('assets/images/skeleton-thumbnail.webp'); ?>" class="w-100" style="opacity: 0;">
						<img src="<?= base_url('assets/images/default-thumbnail.webp'); ?>" alt="thumbnail" id="preview-thumbnail" class="img-thumbnail position-absolute" style="z-index: 10;min-width: 100%;max-width: 100%;max-height: 100%;min-height: 100%;left:0;">
					</div>
				</div>

				<!-- Thumbnail & Published_at -->
				<div class="form-row mt-4">
					<div class="col-12 col-sm-6 form-group">
						<i class="far fa-image text-muted"></i>
						<h6 class="text-muted" style="display:inline;">Thumbnail</h6>
						<input type="file" class="form-control mt-1" id="thumbnail" autocomplete="off" placeholder="thumbnail" style="min-height: 38px" onchange="changeThumbPreview(this);">
						<small
							id="thumbnail-error"
							class="text-danger"></small>
					</div>
					<div class="col-12 col-sm-6 form-group">
						<i class="fas fa-calendar-alt mr-1 text-muted"></i>
						<h6 class="text-muted" style="display:inline;">Tanggal publikasi</h6>
						<input type="date" class="form-control mt-1" id="published_at" name="published_at" style="min-height: 38px" autocomplete="off">
						<small
							id="published_at-error"
							class="text-danger"></small>
					</div>
				</div>

				<!-- Title & Kategori -->
				<div class="form-row mt-4">
					<div class="col-12 col-sm-6 form-group">
						<i class="fas fa-pencil-alt mr-1 text-muted"></i>
						<h6 class="text-muted" style="display:inline;">Judul Artikel</h6>
						<input type="text" class="form-control mt-1" id="title" name="title" placeholder="Title" style="min-height: 38px" autocomplete="off">
						<small
							id="title-error"
							class="text-danger"></small>
					</div>
					<div class="col-12 col-sm-6 form-group">
						<i class="fas fa-list-ul mr-1 text-muted"></i>
						<h6 class="text-muted" style="display:inline;">Kategori</h6>
						<select id="kategori-artikel-wraper" name="id_kategori" class="form-control py-1 px-2 mt-1 mb-2 d-block" style="min-height: 38px">
							
						</select>
						<div class="d-flex justify-content-end">
							<a href="<?= base_url('admin/kategoriartikel') ?>" class="text-muted text-sm" style="width: max-content;">
								<u>manage kategori</u>
							</a>
						</div>
					</div>
				</div>

				<!-- Content -->
				<div id="toolbar-container" class="mt-4">
					<span class="ql-formats">
						<select class="ql-font"></select>
						<select class="ql-size"></select>
					</span>
					<span class="ql-formats">
						<button class="ql-bold"></button>
						<button class="ql-italic"></button>
						<button class="ql-underline"></button>
						<button class="ql-strike"></button>
						<select class="ql-color"></select>
						<select class="ql-background"></select>
						<button class="ql-script" value="sub"></button>
						<button class="ql-script" value="super"></button>
						<button class="ql-blockquote"></button>
						<!-- <button class="ql-header" value="2"></button> -->
						<!-- <button class="ql-header" value="1"></button> -->
						<!-- <button class="ql-code-block"></button> -->
					</span>
					<span class="ql-formats">
						<button class="ql-list" value="ordered"></button>
						<button class="ql-list" value="bullet"></button>
						<button class="ql-indent" value="-1"></button>
						<button class="ql-indent" value="+1"></button>
						<select class="ql-align"></select>
						<button class="ql-direction" value="rtl"></button>
					</span>
					<span class="ql-formats">
						<button class="ql-link"></button>
						<button class="ql-image"></button>
						<button class="ql-video"></button>
						<!-- <button class="ql-formula"></button> -->
					</span>
					<!-- <span class="ql-formats">
						<button class="ql-clean"></button>
					</span> -->
				</div>
				<div id="editor-container"></div>

				<!-- Button 2 -->
				<button type="" class="mt-4 btn btn-success w-100" style="min-width:170px;letter-spacing: 1px;">
					<i class="far fa-paper-plane mr-1"></i>
					<?= (isset($idartikel)) ? 'Edit' : 'Publikasikan' ; ?> 
				</button>
			</form>

		</main>
	</body>
<?= $this->endSection(); ?>