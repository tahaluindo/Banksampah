<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
		body, html {
			height: 100%;
		}

		body {
			background: -webkit-linear-gradient(left, #537629, #c1d966);
			
		}

		.digit-group input {
			width: 38px;
			height: 50px;
			background-color: lighten(#0f0f1a, 5%);
			border: none;
			line-height: 50px;
			text-align: center;
			font-size: 24px;
			font-weight: 200;
			color: black;
			margin: 0 2px;
			font-family: 'qc-medium';
			border-radius: 4px;
		}

		button {
			background: #537629;
			color: #fff;
			width: 274.27px;
			outline: none;
			border: none;
			padding: 8px 0;
		}

		button:hover {
			background: #c1d966;
			color: #537629;
		}
	</style>
  	<!-- ** develoment ** -->
    <!-- <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>"> -->
    <!-- ** production ** -->
	<link rel="stylesheet" href="<?= base_url('assets/css/purge/bootstrap/otp.css'); ?>">
<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->section('contentJs'); ?>
	<script>
		const PASSWORD = '<?= $password; ?>';
		const USERNAME_OR_EMAIL = '<?= $username_or_email; ?>';
		console.log(USERNAME_OR_EMAIL,PASSWORD);
	</script>
  	<script src="<?= base_url('assets/js/otp.js'); ?>"></script>
<?= $this->endSection(); ?>

<!-- Body -->
<?= $this->section('content'); ?>

	<!-- **** Loading Spinner **** -->
	<?= $this->include('Components/loadingSpinner'); ?>
	<!-- **** Alert Info **** -->
	<?= $this->include('Components/alertInfo'); ?>

	<section class="" style="height: 100vh;display: flex;justify-content: center;align-items: center;flex-direction: column;">
		<div class="text-white text-center mb-4">
			<h2 style="font-family: 'qc-semibold';font-size: 28px;">VERIFIKASI AKUN</h2>
			<p>masukan kode OTP yang anda terima <br> dari email</p>
		</div>

		<div class="digit-group mb-4" data-group-name="digits" data-autosubmit="true" autocomplete="off" >
			<input type="text" id="digit-1" name="digit-1" data-next="digit-2" autofocus/>
			<input type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" />
			<input type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" />
			<input type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" />
			<input type="text" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" />
			<input type="text" id="digit-6" name="digit-6" data-previous="digit-5" />
		</div>

		<button 
		class="" 
		style="max-width: 374px;max-height: 54px;border-radius: 4px;cursor: pointer;">
			kirim</button>

		<div class="text-white" style="margin-top: 40px;">
			<a id="kirim_ulang" class="text-white" href="">kirim ulang</a> |
			<a class="text-white" href="<?= base_url('/login') ?>">login</a> |
			<a class="text-white" style="margin-top: 20px;" href="https://wa.me/6281287200602?text=Hallo%20Admin,%20saya%20ada%20kendala%20mengenai%20password">hubungi admin</a>
		</div>
	</section>
<?= $this->endSection(); ?>