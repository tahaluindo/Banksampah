<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
	<style>
        #href-transaksi-wraper a:hover{
			color: #344767 !important;
		}
    </style>
<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->section('contentJs'); ?>
	<script>
		//smoothscroll
		$('#href-transaksi-wraper a').on('click', function (e) {
			e.preventDefault();
			
			var target = $(`${$(this).attr('href')}`);

			$('html, body').stop().animate({
				'scrollTop': target.offset().top-60
			}, 500, 'swing');
		});
	</script>
<?= $this->endSection(); ?>

<aside 
  class="noprint sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" 
  id="sidenav-main"
  style="font-family: 'qc-semibold';">
    <div class="sidenav-header position-sticky bg-white" style="top: 0;z-index: 20">
        <span class="navbar-brand mt-3"
            target="_blank">
            <img src="<?= base_url('assets/images/banksampah-logo.webp');?>" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Laporan BSBL</span>
        </span>
    </div>
    <hr class="horizontal dark mt-0">
    <div 
      class="collapse navbar-collapse w-auto overflow-hidden" 
      id="sidenav-collapse-main"
      style="height: max-content;padding-bottom: 20px;">
        <ul class="navbar-nav">
            <!-- dashboard -->
            <li class="nav-item">
                <a class="nav-link <?= ($title=='Admin | dashboard') ? 'active' : ''?>" href="<?= base_url('admin/');?>">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-home <?= ($title=='Admin | dashboard') ? 'text-white' : 'text-muted'?>" style="font-size: 13px;transform: translateY(-1px);"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <!-- list sampah -->
            <li class="nav-item">
                <a class="nav-link <?= ($title=='Admin | list sampah') ? 'active' : ''?>" href="<?= base_url('admin/listsampah');?>">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-recycle <?= ($title=='Admin | list sampah') ? 'text-white' : 'text-muted'?>" style="font-size: 13px;transform: translateY(-1px);"></i>
                    </div>
                    <span class="nav-link-text ms-1">Sampah</span>
                </a>
            </li>
            <!-- transaksi -->
            <li class="nav-item">
                <a class="nav-link <?= ($title == 'Admin | transaksi') ? 'active' : ''?>" href="<?= base_url('admin/transaksi');?>">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-cash-register <?= ($title=='Admin | transaksi') ? 'text-white' : 'text-muted'?>" style="font-size: 14px;transform: translateY(-1px);"></i>
                    </div>
                    <span class="nav-link-text ms-1">Transaksi</span>
                </a>
                <?php if ($title == 'Admin | transaksi') { ?>
                <div id="href-transaksi-wraper" class="w-100 pl-5 d-flex flex-column">
                    <a href="#tambah-transaksi" class="pt-4 pb-2 text-muted text-xs">
                        <i class="fas fa-angle-right mr-2"></i>
                        <span>tambah transaksi</span>
                    </a>
                    <a href="#semua-transaksi" class="pt-2 pb-2 text-muted text-xs">
                        <i class="fas fa-angle-right mr-2"></i>
                        <span>semua transaksi</span>
                    </a>
                    <a href="#rekap-transaksi" class="pt-2 pb-2 text-muted text-xs">
                        <i class="fas fa-angle-right mr-2"></i>
                        <span>rekap transaksi</span>
                    </a>
                </div>
                <?php } ?>
            </li>
            <!-- list admin -->
            <?php if ($privilege == 'superadmin') { ?>
            <li class="nav-item mt-2">
                <a class="nav-link <?= ($title=='Admin | list admin') ? 'active' : ''?>" href="<?= base_url('admin/listadmin');?>">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-users-cog <?= ($title=='Admin | list admin') ? 'text-white' : 'text-muted'?>" style="font-size: 14px;transform: translateY(-2px);"></i>
                    </div>
                    <span class="nav-link-text ms-1">Admin</span>
                </a>
            </li>
            <?php } ?>
            <!-- list nasabah -->
            <li class="nav-item mt-2">
                <a class="nav-link <?= ($title=='Admin | list nasabah') ? 'active' : ''?>" href="<?= base_url('admin/listnasabah');?>">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user-friends <?= ($title=='Admin | list nasabah') ? 'text-white' : 'text-muted'?>" style="font-size: 13px;transform: translateY(-2px);"></i>
                    </div>
                    <span class="nav-link-text ms-1">Nasabah</span>
                </a>
            </li>
            <!-- list penghargaan -->
            <li class="nav-item mt-2">
                <a class="nav-link <?= ($title=='Admin | list penghargaan') ? 'active' : ''?>" href="<?= base_url('admin/listpenghargaan');?>">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-trophy <?= ($title=='Admin | list penghargaan') ? 'text-white' : 'text-muted'?>" style="font-size: 14px;transform: translateY(-2px);"></i>
                    </div>
                    <span class="nav-link-text ms-1">Penghargaan</span>
                </a>
            </li>
            <!-- list mitra -->
            <li class="nav-item mt-2">
                <a class="nav-link <?= ($title=='Admin | list mitra') ? 'active' : ''?>" href="<?= base_url('admin/listmitra');?>">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-hands-helping <?= ($title=='Admin | list mitra') ? 'text-white' : 'text-muted'?>" style="font-size: 14px;transform: translateY(-2px);"></i>
                    </div>
                    <span class="nav-link-text ms-1">Mitra</span>
                </a>
            </li>
            <!-- list artikel -->
            <li class="nav-item mt-2">
                <a class="nav-link <?= ($title=='Admin | list artikel') ? 'active' : ''?>" href="<?= base_url('admin/listartikel');?>">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-file-alt <?= ($title=='Admin | list artikel') ? 'text-white' : 'text-muted'?>" style="font-size: 14px;transform: translateY(-2px);"></i>
                    </div>
                    <span class="nav-link-text ms-1">Artikel</span>
                </a>
            </li>
            <!-- profile admin -->
            <li class="nav-item mt-2">
                <a class="nav-link <?= ($title=='Admin | profile') ? 'active' : ''?>" href="<?= base_url('admin/profile');?>">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-address-card <?= ($title=='Admin | profile') ? 'text-white' : 'text-muted'?>" style="font-size: 13px;transform: translateY(-1px);"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
            <hr class="horizontal dark mt-2 mb-0">
            <li class="nav-item">
                <a class="nav-link" id="btn-logout" href="">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width='12px' height='12px' version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30.143 30.143"
                            style="enable-background:new 0 0 30.143 30.143;" xml:space="preserve">
                            <g>
                                <path class="opacity-6" fill="#00000"
                                    d="M20.034,2.357v3.824c3.482,1.798,5.869,5.427,5.869,9.619c0,5.98-4.848,10.83-10.828,10.83
                                        c-5.982,0-10.832-4.85-10.832-10.83c0-3.844,2.012-7.215,5.029-9.136V2.689C4.245,4.918,0.731,9.945,0.731,15.801
                                        c0,7.921,6.42,14.342,14.34,14.342c7.924,0,14.342-6.421,14.342-14.342C29.412,9.624,25.501,4.379,20.034,2.357z" />
                                <path class="opacity-6" fill="#00000"
                                    d="M14.795,17.652c1.576,0,1.736-0.931,1.736-2.076V2.08c0-1.148-0.16-2.08-1.736-2.08
                                        c-1.57,0-1.732,0.932-1.732,2.08v13.496C13.062,16.722,13.225,17.652,14.795,17.652z" />
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>