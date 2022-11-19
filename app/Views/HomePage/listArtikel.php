<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
	</style>
    
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/listArtikel.css'); ?>">
<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->section('contentJs'); ?>
	<script>
		const KATEGORI = '<?= $kategori; ?>';
	</script>
	<script src="<?= base_url('assets/js/plugins/font-awesome.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/listArtikel.js'); ?>"></script>
<?= $this->endSection(); ?>

<!-- Body -->
<?= $this->section('content'); ?>

<body>	
	<!-- **** Alert Info **** -->
	<?= $this->include('Components/alertInfo'); ?>

	<!-- ***** Header Area Start ***** -->
	<div class="container navbar navbar-expand-lg navbar-light bg-white px-4 px-sm-5 position-sticky" style="top: 0;z-index: 10;">
        <a class="logo navbar-brand" href="<?= base_url('/'); ?>">
            <img class="logo_nav" src="<?= base_url('assets/images/banksampah-logo.webp'); ?>" alt=""
                width="65" height="55">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto mt-lg-0 mt-4">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/'); ?>">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Kategori
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        
                    </div>
                </li>
            </ul>
        </div>
	</div>

	<section class="wrapper d-flex position-relative" style="margin-top: 30px;z-index: 0;">
		<div class="container px-4 px-sm-5">
			<div class="w-100 h-100 d-flex align-items-center d-none" id="img-404">
				<img src="<?= base_url('assets/images/artikel-404.webp') ?>" alt="" style="min-width:100%;max-width:100%; opacity:0.7;">
			</div>

			<div class="row" id="container-article">
				<?php for ($i=0; $i < 6; $i++) { ?>
					<div class="col-12 col-sm-6 col-md-4 mb-5">
						<div class="card text-white position-relative skeleton" style="box-shadow: none;min-height: 220px;border-radius: 10px;">
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
	
	<!-- **** footer artikiel **** -->
    <?= $this->include('Components/artikelFooter'); ?>
</body>
	
<?= $this->endSection(); ?>