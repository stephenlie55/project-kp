<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo NAMA_APLIKASI ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="create-by" content="Reynaldi">
        <meta name="create-date" content="15/05/2019">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/font/css/all.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/js/datatables/dataTables.bootstrap4.css') ?>">
        <link href="<?php echo base_url('favicon.ico') ?>" rel="shortcut icon">
    </head>

    <div class="preloader">
        <div class="loading">
            <img src="<?php echo base_url('assets/img/loading.gif') ?>" width="80">
            <p>MEMUAT</p>
        </div>
    </div>
    
    <body>
    
        <nav class="navbar navbar-dark navbar-expand-md bg-danger justify-content-between fixed-top sticky-top">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <i class="tombol fa fa-ellipsis-v"></i>
                    </li>
                </ul>

                <a href="./" class="navbar-brand text-center">
                    <b><?php echo NAMA_APLIKASI ?></b>
                </a>

                <ul class="nav navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo base_url('logout') ?>"><i class="fa fa-sign-out-alt fa-lg"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
        <nav class="nav-slider">
            <div class="card">
                <div class="card-header">
                    <b><?php echo strtoupper($this->session->userdata('USER_FULLNAME')) ?></b>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item"><a href="<?php echo base_url() ?>">DASHBOARD</a></li>
                        <li class="list-group-item"><a href="<?php echo base_url('rfm') ?>">RFM</a></li>
                        <li class="list-group-item"><a href="<?php echo base_url('rfp') ?>">RFP</a></li>
                        <li class="list-group-item"><a href="<?php echo base_url('daily_report') ?>">DAILY REPORT</a></li>
                        <?php
                            if(in_array($this->session->userdata('USER_ID'), $menu)) {
                                echo "
                                    <li class='list-group-item'><a href='".base_url('user_management')."'>USER MANAGEMENT</a></li>
                                ";
                            }

                            echo $menu_kpi;
                        ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid pt-3">
            <div class="breadcrumb">
                <div class="col text-left">
                    <?php echo $this->session->userdata('USER_FULLNAME') ?>
                </div>

                <div class="col text-right">
                    <!-- <a href="<?php echo base_url('export_to_excel') ?>" title="Export To Excel" class="btn btn-primary btn-sm mr-3" target="_blank">
                        <i class="fa fa-print"></i> Export To Excel
                    </a> -->
                    <a href="javascript:void(0)" title="Export To Excel" class="btn btn-primary btn-sm mr-3" onclick="export_to_excel()">
                        <i class="fa fa-print"></i> Export To Excel
                    </a>

                    <a href="<?php echo base_url('rfp#table') ?>" title="RFP">
                        <i class="far fa-envelope-open fa-lg"></i><sup class="badge badge-danger rfp-bells"></sup>
                    </a>
                    <a href="<?php echo base_url('rfm#table') ?>" title="RFM">
                        <i class="far fa-bell fa-lg"></i><sup class="badge badge-danger rfm-bells"></sup>
                    </a>
                </div>
            </div>
            <?php echo $contents ?>
        </div>

        <footer class="small text-center">
            <p>Copyright <?php echo date('Y')?> &copy; <a href="./"><?php echo NAMA_APLIKASI." :: ".NAMA_PERUSAHAAN ?></a></p>
        </footer>

        <script src="<?php echo base_url('assets/js/jquery-3.1.1.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/js/datatables/jquery.dataTables.js') ?>"></script>
        <script src="<?php echo base_url('assets/js/datatables/dataTables.bootstrap4.js') ?>"></script>
        <script src="<?php echo base_url('assets/js/jquery-expander.js') ?>"></script>
        <script src="<?php echo base_url('assets/js/index.js') ?>"></script>
        <script>
            setInterval(function(){
                let sess = <?php echo $this->session->userdata('USER_ID'); ?>;
                if(sess == ''){
                    window.location.href = "<?php echo base_url('logout') ?>";
                }
            }, 1000000);
        </script>
    </body>
</html>