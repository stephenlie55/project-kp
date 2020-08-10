<div class="row mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <b>PERHITUNGAN RFM</b>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h1><?php echo $jumlah_queue ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">On Queued</span>
                            </i>
                        </div>
                    </div>
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h1><?php echo $jumlah_approve ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-check-double"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">Approved</span>
                            </i>
                        </div>
                    </div>
                
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-dark">
                            <div class="card-body">
                                <h1><?php echo $jumlah_progress ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">On Progress</span>
                            </i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h1><?php echo $jumlah_done ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-thumbs-up"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">Done</span>
                            </i>
                        </div>
                    </div>
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <h1><?php echo $jumlah_reject ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-ban"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">Reject</span>
                            </i>
                        </div>
                    </div>
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h1><?php echo $jumlah_rfm ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">Total RFM</span>
                            </i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <b>PERHITUNGAN RFP</b>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h1><?php echo $jumlah_queue_rfp ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">On Queued</span>
                            </i>
                        </div>
                    </div>
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h1><?php echo $jumlah_approve_rfp ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-check-double"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">Approved</span>
                            </i>
                        </div>
                    </div>
                
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-dark">
                            <div class="card-body">
                                <h1><?php echo $jumlah_progress_rfp ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">On Progress</span>
                            </i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h1><?php echo $jumlah_done_rfp ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-thumbs-up"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">Done</span>
                            </i>
                        </div>
                    </div>
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <h1><?php echo $jumlah_reject_rfp ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-ban"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">Reject</span>
                            </i>
                        </div>
                    </div>
                    <div class="col-md-4 p-1">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h1><?php echo $jumlah_rfp ?></h1>
                                <div class="card-body-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <i class="card-footer text-white clearfix small">
                            <span class="float-left">Total RFM</span>
                            </i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="table" style="margin-top: 15px">
    <div class="card-header" >
        <b>DAILY PROGRESS REPORT</b>
    </div>
    <div class="card-body">
    <!-- table table-bordered table-hover -->
        <table class="colapse-table res3"  width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>HARI</th>
                    <th>TANGGAL</th>
                    <th>WAKTU</th>
                    <th>PROJECT</th>
                    <th>NON-PROJECT</th>
                    <th>STATUS</th>
                    <th>KETERANGAN</th>
                    <th>PIC</th>
                </tr>
            </thead>

            
        </table>
    </div>
</div>

<div class="card mb-3" id="table" style="margin-top: 15px">
    <div class="card-header" >
        <b>PROGRESS PROJECT</b>
    </div>
    <div class="card-body">
    <!-- table table-bordered table-hover -->
        <table class="colapse-table res3"  width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>HARI</th>
                    <th>TANGGAL</th>
                    <th>PROJECT</th>
                    <th>STATUS</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>

        </table>
    </div>
</div>

<div class="card">
	<div class="card-header">
		TIMELINE PROJECT IT
	</div>
	<div class="card-body">
		>> <a href="upload/IT PROJECT 2020.pdf" target="_blank">IT PROJECT 2020.pdf</a>
	</div>
</div>

<?php
    $tb_detail = TB_DETAIL;
    $Q = "SELECT DISTINCT MONTH(`request_date`) AS bulan FROM `rfm_new_detail` WHERE YEAR(`request_date`) BETWEEN '2019' AND YEAR(CURDATE()) ORDER BY MONTH(`request_date`) ASC";
    $query = $this->db->query($Q)->result();
    
    $Q = "SELECT DISTINCT YEAR(`request_date`) AS tahun 
            FROM $tb_detail";
    $queryyear = $this->db->query($Q)->result();
    
    $post_month = $this->input->post('month');
    $post_year = $this->input->post('year');
    if($post_month==1)
    {
        $bulan = "Januari";
    }
    elseif($post_month==2)
    {
        $bulan = "Februari";
    }
    elseif($post_month==3)
    {
        $bulan = "Maret";
    }
    elseif($post_month==4)
    {
        $bulan = "April";
    }
    elseif($post_month==5)
    {
        $bulan = "Mei";
    }
    elseif($post_month==6)
    {
        $bulan = "Juni";
    }
    elseif($post_month==7)
    {
        $bulan = "Juli";
    }
    elseif($post_month==8)
    {
        $bulan = "Agustus";
    }
    elseif($post_month==9)
    {
        $bulan = "September";
    }
    elseif($post_month==10)
    {
        $bulan = "Oktober";
    }
    elseif($post_month==11)
    {
        $bulan = "November";
    }
    elseif($post_month==12)
    {
        $bulan = "Desember";
    }

    if(empty($post_month))
    {
        $text_bulan = "Bulan";
        $val_bulan = "";
    }
    else
    {
        $text_bulan = $bulan;
        $val_bulan = $post_month;
    }

    if(empty($post_year))
    {
        $text_tahun = "Tahun";
        $val_tahun = date('Y');
    }
    else
    {
        $text_tahun = $post_year;
        $val_tahun = $post_year;
    }

    //CHART==================================
    echo "
        <div class='row mt-3'>
            <div class='col-md-12'>
                <form action='' method='post'>
                    <div class='row'>
                        <div class='col-md-5'>
                            <select name='month' class='form-control'>
                                <option value='$val_bulan'>$text_bulan</option>
    ";
                            foreach($query as $row):
                                if($row->bulan==01)
                                {
                                    $bulan = "Januari";
                                }
                                elseif($row->bulan==02)
                                {
                                    $bulan = "Februari";
                                }
                                elseif($row->bulan==03)
                                {
                                    $bulan = "Maret";
                                }
                                elseif($row->bulan==04)
                                {
                                    $bulan = "April";
                                }
                                elseif($row->bulan==05)
                                {
                                    $bulan = "Mei";
                                }
                                elseif($row->bulan==06)
                                {
                                    $bulan = "Juni";
                                }
                                elseif($row->bulan==07)
                                {
                                    $bulan = "Juli";
                                }
                                elseif($row->bulan=='08')
                                {
                                    $bulan = "Agustus";
                                }
                                elseif($row->bulan=='09')
                                {
                                    $bulan = "September";
                                }
                                elseif($row->bulan==10)
                                {
                                    $bulan = "Oktober";
                                }
                                elseif($row->bulan==11)
                                {
                                    $bulan = "November";
                                }
                                elseif($row->bulan==12)
                                {
                                    $bulan = "Desember";
                                }
    echo "
                                <option value='$row->bulan'>$bulan</option>
    ";
                            endforeach;
    echo "
                            </select>
                        </div>
                        <div class='col-md-5'>
                            <select name='year' class='form-control'>
                                <option value='$val_tahun'>$text_tahun</option>
                                ";
                            foreach($queryyear as $row):
    echo "
                                <option value='$row->tahun'>$row->tahun</option>
    ";
                            endforeach;
    echo "
                            </select>
                        </div>
                        <div class='col-md-2'>
                            <input type='submit' name='btnSearch' class='btn btn-primary btn-block' value='CARI'>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    ";
?>

<script src="<?php echo base_url('assets/js/chart/Chart.bundle.js') ?>"></script>
<div class="row mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <b>STATISTIK PERMINTAAN</b>
            </div>
            <div class="card-body">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <b>PERSENTASE KESALAHAN</b>
            </div>
            <div class="card-body">
                <canvas id="myChart_"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
    var lineChartData = {
        labels: [
            <?php
                foreach($chart_line as $r):
                    $data = array();
                    $data =  date('d', strtotime($r->tgl));
                    echo json_encode($data).",";
                endforeach;
            ?>
        ],
        datasets: [{
        label: 'All RFM',
        borderColor: 'rgb(255, 255, 0, 1)',
        borderWidth: 1,
        backgroundColor: 'rgb(255, 255, 0, 0.4)',
        data: [
            <?php
                foreach($chart_line as $r):
                    echo $r->a.",";
                endforeach;
            ?>
        ]
		},
        {
            label: 'On Progress',
            borderColor: 'rgb(0, 0, 0, 1)',
            borderWidth: 1,
            backgroundColor: 'rgb(0, 0, 0, 0.6)',
            data: [
                <?php
                    foreach($chart_line as $r):
                        echo $r->b.",";
                    endforeach;
                ?>
            ]
        },
        {
            label: 'Done',
            borderColor: 'rgb(0, 204, 0, 1)',
            borderWidth: 1,
            backgroundColor: 'rgb(0, 204, 0, 0.4)',
            data: [
                <?php
                    foreach($chart_line as $r):
                        echo $r->c.",";
                    endforeach;
                ?>
            ]
        }]
	}

    
        var ctx = document.getElementById('myChart').getContext('2d');
        window.myLine = Chart.Line(ctx, {
            data: lineChartData,
            options: {
                aspectRatio: 1,
                layout: {
                        padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0,
                    }
                },
                responsive: true,
                title:{
                    display:true,
                    text:'Line Chart'
                },
                tooltips: {
                    enabled: true,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            return val + ' Support';
                        }
                    }
                }  
            }
        });
//==================================================

var ctx_ = document.getElementById("myChart_").getContext("2d");
    var data_ = {
        labels: [
            <?php
                foreach($chart_pie as $r):
                    $data = array();
                    $data = $r->problem_type;
                    echo json_encode($data).",";
                endforeach;
            ?>
        ],
        datasets:
        [{
            data: [
                <?php
                    foreach($chart_pie as $r):
                        $data = array();
                        $data = $r->rasio_rfm_accepted;
                        echo json_encode($data).",";
                    endforeach;
                ?>
            ],
            backgroundColor: [
                "rgba(59, 100, 222, 1)",
                "rgba(203, 22, 225, 1)",
                "rgba(102, 50, 179, 1)",
                "rgba(201, 29, 29, 1)",
                "rgba(81, 230, 153, 1)",
                "rgba(246, 34, 19, 1)",
                "rgba(246, 250, 1, 1)",
                "rgba(69, 198, 32, 1)",
                "rgba(7, 44, 12, 1)",
                "rgba(246, 198, 19, 1)",
                "rgba(65, 123, 2, 1)",
                "rgba(255, 34, 198, 1)"],
                hoverBackgroundColor: 'rgb(0, 0, 0, 0.4)',
                hoverBorderColor: 'rgb(0, 0, 0, 1)',
        }]
    };

    var myBarChart = new Chart(ctx_, {
        type: 'pie',
        data: data_,
        options: {
            legend: {
                display: false
            },
            responsive: true,
            title:{
                display:true,
                text:'Problem Type Chart'
            },
            tooltips: {
                callbacks: {
                    // this callback is used to create the tooltip label
                    label: function(tooltipItem, data) {
                    // get the data label and data value to display
                    // convert the data value to local string so it uses a comma seperated number
                    var dataLabel = data.labels[tooltipItem.index];
                    var value = ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString()+'%';

                    // make this isn't a multi-line label (e.g. [["label 1 - line 1, "line 2, ], [etc...]])
                    if (Chart.helpers.isArray(dataLabel)) {
                        // show value on first line of multiline label
                        // need to clone because we are changing the value
                        dataLabel = dataLabel.slice();
                        dataLabel[0] += value;
                    } else {
                        dataLabel += value;
                    }

                    // return the text to display on the tooltip
                    return dataLabel;
                    }
                }
            }
        }
    });
</script>

<div class="row pt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">ACCEPTED RFM RATIO</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" id="dt_dashboard">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th>PROBLEM TYPE</th>
                                <th>JML PENDING</th>
                                <th>JML ACCEPTED</th>
                                <th>JML REJECTED</th>
                                <th>RASIO ACCEPTED</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($chart_pie as $r): ?>
                            <tr>
                                <td style="text-align: left"><?php echo $r->problem_type ?></td>
                                <td style="text-align: right"><?php echo $r->jml_rfm_pending ?></td>
                                <td style="text-align: right"><?php echo $r->jml_rfm_accepted ?></td>
                                <td style="text-align: right"><?php echo $r->jml_rfm_reject ?></td>
                                <td style="text-align: right"><?php echo $r->rasio_rfm_accepted." %" ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><b>JUMLAH</b></td>
                                <td></td>
                                <td style="text-align: right"><?php echo "<b>$total_semua</b>" ?></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>