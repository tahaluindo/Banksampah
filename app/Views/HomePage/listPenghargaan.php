<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
        #page_title{
            margin-top: 80px !important;
            padding-bottom: 40px !important;
            opacity: 0.8;
        }
        .skeleton{
            background: rgb(193, 217, 102) !important;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: .8;
            }
            50% {
                opacity: .5;
            }
        }

        .card.no_skeleton{
            transition: all 0.5s;
        }
        .card.no_skeleton:hover{
            transform: scale(1.1);
        }

        
        @media (max-width:640px){
            #page_title{
                font-size: 24px;
                margin-top: 40px !important;
                margin-bottom: 10px !important;
                padding-bottom: 10px !important;
            }
        }

        #zoom_bg{
            position: fixed;
            z-index: 100;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            overflow: auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: rgba(20,20,20,0.7);
            box-sizing: border-box;
        }
        #zoom_close{
            position: fixed;
            z-index: 120;
            top: 10px;
            right: 30px;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }
        #zoom_img_wraper{
            padding: 50px 50px 0 50px;
            width: 100%;
        }
        #zoom_img{
            width: 100%;
        }
        #zoom_des_wraper{
            background: rgba(0,0,0,0.4);
            padding: 20px 20px 50px 20px;
            position: relative;
            display:flex;
            justify-content: center;
        }
        #zoom_des{
            width: 100%;
            color: rgba(255,255,255,0.8);
        }
        #read_more{
            text-decoration:underline;
            cursor:pointer;
            position:absolute;
            bottom: 20px;
            color: white;
        }
        .hide{
            display: none !important;
        }
	</style>
    
  <link rel="stylesheet" href="<?= base_url('assets/css/purge/bootstrap/listPenghargaan.css'); ?>">
    <!-- <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>"> -->
<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->section('contentJs'); ?>
	<script src="<?= base_url('assets/js/plugins/font-awesome.min.js'); ?>"></script>
	<script src="<?= base_url('assets/js/listPenghargaan.js'); ?>"></script>
<?= $this->endSection(); ?>

<!-- Body -->
<?= $this->section('content'); ?>

<body>	
	<!-- **** Alert Info **** -->
    <?= $this->include('Components/alertInfo'); ?>

	<!-- ***** Header Area Start ***** -->
	<div class="navbar navbar-light bg-white position-sticky" style="padding:14px 20px;top: 0;z-index: 10;box-shadow: 0 .25rem .375rem -.0625rem rgba(20,20,20,.12),0 .125rem .25rem -.0625rem rgba(20,20,20,.07);">
        <div class="container">
            <a class="logo navbar-brand d-flex align-items-center pt-2 pb-1 px-3" href="<?= base_url('/'); ?>" style="cursor:pointer;box-shadow: 0 .25rem .375rem -.0625rem rgba(20,20,20,.12),0 .125rem .25rem -.0625rem rgba(20,20,20,.07)">
                <svg width="20" height="30" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_302:4)">
                    <path d="M6.50489 8.41773L3.20258 5.11542L6.50489 1.81312C6.79641 1.51129 6.79224 1.03151 6.49552 0.734794C6.1988 0.438075 5.71903 0.433906 5.4172 0.725423L1.57104 4.57158C1.27075 4.87196 1.27075 5.35889 1.57104 5.65927L5.4172 9.50542C5.61033 9.70539 5.89633 9.78559 6.16528 9.71519C6.43423 9.6448 6.64426 9.43476 6.71466 9.16582C6.78505 8.89687 6.70486 8.61087 6.50489 8.41773Z" fill="#252F40"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_302:4">
                    <rect width="10" height="7" fill="white" transform="translate(7.49951 0.500031) rotate(90)"/>
                    </clipPath>
                    </defs>
                </svg>
            </a>
        </div>
	</div>

    <h1 id="page_title" class="text-center">
        Kumpulan Penghargaan <br> Banksampah Budiluhur
    </h1>
    <section class="wrapper d-flex position-relative" style="margin-top: 30px;z-index: 0;">
		<div class="container px-4 px-sm-5">
			<div class="w-100 h-100 d-flex align-items-center d-none" id="img-404">
				<img src="<?= base_url('assets/images/penghargaan-404.png') ?>" alt="" style="min-width:100%;max-width:100%; opacity:0.7;">
			</div>

			<div class="row" id="container-penghargaan">
				<?php for ($i=0; $i < 6; $i++) { ?>
					<div class="col-12 col-sm-6 col-md-4 mb-5">
						<div class="card text-white position-relative skeleton" style="box-shadow: none;min-height: 220px;border-radius: 10px;">
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

    <!-- Zoom Image -->
    <div id="zoom_bg" class="hide" >
        <span id="zoom_close">X</span>
        <div id="zoom_img_wraper">
            <img id="zoom_img" src="" alt="">
        </div>
        <div id="zoom_des_wraper">
            <div id="zoom_des" style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">
            </div>
            <div id="read_more">read more</div>
        </div>
    </div>

	<!-- **** footer artikiel **** -->
    <?= $this->include('Components/artikelFooter'); ?>
</body>
	
<?= $this->endSection(); ?>