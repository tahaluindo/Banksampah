<?= $this->extend('Layout/template') ?>

<!-- Css -->
<?= $this->section('contentCss'); ?>
    <style>
        #alert.hide{
            display: none !important;
        }
    </style>
	<link rel="stylesheet" href="<?= base_url('assets/css/purge/bootstrap/alertinfo.css'); ?>">
<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->section('jsComponent'); ?>
    <script>
        function showAlert(data) {
            $('#alert #message').html(data.message);
            $('#alert').removeClass(`hide`);
            $('#alert').addClass(`alert-${data.type} show`);
            if (data.autohide) {
                setTimeout(() => {
                    $('#alert').removeClass('show alert-success alert-danger alert-warning alert-info');
                    setTimeout(() => {
                        $('#alert').addClass(`hide`);
                    }, 1000);
                }, 5000);    
            }     
        }
        
        if(!navigator.onLine){
            showAlert({
                message: `<strong>Ups . . .</strong> koneksi anda terputus!`,
                autohide: false,
                type:'danger'
            })
        }
        window.onoffline = () => {
            showAlert({
                message: `<strong>Ups . . .</strong> koneksi anda terputus!`,
                autohide: false,
                type:'danger'
            })
        };
        window.ononline = () => {
            $('#alert').removeClass('show alert-success alert-danger alert-warning alert-info');
        };
    </script>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
  <div
    id="alert" 
    class="container-fluid position-fixed alert alert-dismissible fade hide"
    style="top:0;z-index:10000;" role="alert">
      <span id="message">custom text</span>
      <!-- <button id="close" type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button> -->
  </div>
<?= $this->endSection(); ?>