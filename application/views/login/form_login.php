<!DOCTYPE html>
<html>
    <head>
        <title>LOGIN - <?php echo NAMA_APLIKASI ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="create-by" content="Reynaldi">
        <meta name="create-date" content="15/05/2019">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/font/css/all.min.css') ?>">
        <link href="<?php echo base_url('favicon.ico') ?>" rel="shortcut icon">
    </head>
    <div class="preloader">
    <div class="loading">
        <img src="<?php echo base_url('assets/img/loading.gif') ?>" width="80">
        <p>Please Wait</p>
    </div>
    </div>
    <body class="bg-dark">

    <div class="container modal-sm">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header">Login</div>
            <div class="card-body">
                <form name="form_login" id="form_login" method="POST">
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required="required" autofocus="autofocus">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="required" autofocus="autofocus">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <div class="form-label-group">
                            <label>Connection:</label>
                            <select name="koneksi" id="koneksi" class="form-control">
                                <option value="test">RFM TEST (LOCAL)</option>
                                <option value="local">RFM TEST (PUBLIC)</option>
                                <option value="rfm">RFM ONLINE</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <button class="btn btn-primary col" onclick="proses_login()" id="btn_login" type="button">Masuk</button>
                    </div>
                </form>

                <div id="pesan" class="text-center text-danger"></div>
                <div style="text-align: center">
                    <a target="_blank" href="upload/manual_books.pdf">Buku Panduan</a>
                </div>

            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/js/jquery-3.1.1.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script>
    $(document).ready(function () {
        $(".preloader").fadeOut(1000);
    });

    function proses_login() {
        var data = $('#form_login').serialize();
        $.ajax({
            url : 'auth_controller/login',
            type : 'post',
            cahce : false,
            data : data,
            dataType : 'json',
            beforeSend : function(){
                $('#btn_login').html("<img src='assets/img/loading.gif' width='20px'>");
            },
            success : function(result){
                localStorage.clear();
                var isValid = result.isValid;
                var isPesan = result.isPesan;
                if(isValid == 1){
                    $('#btn_login').html('Masuk');
                    $('#btn_login').show();
                    $('#pesan').html(isPesan);
                }else{
                    window.location.href = './';
                }
            }
        });
    }
    </script>

    </body>
</html>
