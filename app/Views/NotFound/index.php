<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/NotFound.css'); ?>">
<?= $this->endSection(); ?>

<!-- Body -->
<?= $this->section('content'); ?>

<body>

	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h1>404</h1>
				<h2>Page not found</h2>
			</div>
			<a href="<?= base_url('/');?>">Homepage</a>
		</div>
	</div>

</body>
<?= $this->endSection(); ?>
