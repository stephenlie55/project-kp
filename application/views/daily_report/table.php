<div class="card mb-3" id="table">
    <div class="card-header">
        <button class="btn btn-success btn-sm" id="btn_create" data-toggle="modal" data-target="#modal-create-task">
            <i class="far fa-comments"></i> Tulis Task
        </button>
    </div>
    <div class="card-body">
    <div class="pesan"></div>
    <!-- table table-bordered table-hover -->
        <table class="colapse-table res3" id="tb_detail_dr" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>HARI</th>
                    <th>TANGGAL</th>
					<th>PROJECT</th>
					<th>RFM</th>
                    <th>STATUS</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
			
			<?php foreach($daily_activities->result() as $r): ?>
				<tr>
					<td><?php
					$hari = date('l',strtotime($r->tanggal));
					switch($hari){
						case 'Sunday':
							$hari = "Minggu";
						break;
				 
						case 'Monday':			
							$hari = "Senin";
						break;
				 
						case 'Tuesday':
							$hari = "Selasa";
						break;
				 
						case 'Wednesday':
							$hari = "Rabu";
						break;
				 
						case 'Thursday':
							$hari = "Kamis";
						break;
				 
						case 'Friday':
							$hari = "Jumat";
						break;
				 
						case 'Saturday':
							$hari = "Sabtu";
						break;
						
						default:
							$hari= "Tidak di ketahui";		
						break;
					}
					echo $hari;
				?>
				</td>
					<td><?php echo date("d/m/Y",strtotime( $r->tanggal)) ?></td>
					<td><?php echo ($r->project_id == null) ? "-" : $r->project_id ?></td>
					<td><?php echo ($r->rfm_id == null) ? "-" : $r->rfm_id ?></td>
					<td><?php echo $r->status ?></td>
					<td><?php echo ($r->keterangan == null) ? "-" : $r->keterangan ?></td>
				</tr>
			<?php endforeach ?>
         </table>
    </div>
</div>

<div class="modal fade" id="modal-create-task" role="dialog">
    <div class="modal-dialog modal-lg">>
    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">New Task</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" id="frm-create-task">
					<div class="form-group text-primary">
						<i class="far fa-clock"></i> <?php echo date(' d/m/Y') ?>
					</div>

					<div class="form-group">
						<label for="projectFlag">Jenis task:</label>
						<select id="projectFlag" class="form-control">
							<option>Project</option>
							<option selected="selected">RFM</option>
							<option>Other</option>
						</select>
					</div>

					<div class="panel-group" id="accordion" style="margin-top: 8px">
						<div class="panel panel-default">
							
							<div id="collapseProject" class="panel-collapse collapse">
								<div class="panel-body">
									<label for="projectList">Daftar project:</label>
									<select id="project_id" class="form-control" name="project_id" style="margin-bottom: 15px">
										<option selected="selected" value="-">-Pilih project-</option>
										<?php foreach($projectList->result() as $r): ?>
											<option value=<?php echo $r->id ?> ><?php echo $r->project_name ?></option>
										<?php endforeach ?>
									</select>
								</div>

								<div class="panel-group" id="accordion" style="margin-top: 8px">
									<div class="panel panel-default">
										<div id="collapseProjectSubtask" class="panel-collapse collapse">
											<div class="panel-body">
												<div class="form-group">
													<label for="task_id">Task:</label>
													<select id="task_id" class="form-control" name="task_id" style="margin-bottom: 15px">
														<option value="Perbaiki tampilan dashboard">Perbaiki tampilan dashboard</option>
														<option value="Tambah sub-menu">Tambah sub-menu</option>
														<option value="Perbaiki grafik">Pernbaiki grafik</option>
														<option value="Tambah user">Tambah user</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>

							<div id="collapseRFM" class="panel-collapse collapse">
							<div class="panel-body">
									<div class="form-group">
										<label for="rfm_id">No. RFM:</label>
										<select id="rfm_id" class="form-control" name="rfm_id" style="margin-bottom: 15px">
											<option selected="selected">-Pilih RFM-</option>
											<?php foreach($rfmList->result() as $r): ?>
												<option value=<?php echo $r->id ?> ><?php echo $r->no_rfm ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
		  
					<div class="form-group">
						<label for="Status">Status</label>
						<select type="text" class="form-control" id="status" name="status">
							<option value="CANCEL">CANCEL</option>
							<option value="DONE">DONE</option>
							<option value="ON PROGRESS">ON PROGRESS</option>
							<option value="ON QUEUE">ON QUEUE</option>
							<option value="PENDING">PENDING</option>
						</select>
					</div>

					<div class="form-group">
						<label for="keterangan">Keterangan:</label>
						<textarea class="form-control" rows="3" id="keterangan" style="resize: none" name="keterangan"></textarea>
					</div>

					<div class="form-group">
						<label for="PIC">PIC: </label>
						<?php echo strtoupper($this->session->userdata('USER_FULLNAME')) ?>
					</div>
				</form>
				<div class="modal-footer">
					<div class="btn_post_request">
						<a href="javascript:void(0)" onclick="post_request_dr()" class="btn btn-success"><i class="fa fa-check"></i> Add</a>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function (event) {

    	$('#tb_detail_dr').DataTable({
			"bSort" : false
		});

        $('#collapseRFM').collapse('show');
        $('#projectFlag').on('change', function (e) {
			// TODO: Get project list
            var optionSelected = $("option:selected", this);
            var valueSelected = this.value;

            if (valueSelected === "Project") {
                $('#collapseProject').collapse('show');
				$('#collapseRFM').collapse('hide');
            } else if (valueSelected === "RFM") {
                $('#collapseProject').collapse('hide');
				$('#collapseRFM').collapse('show');
            } else {
				$('#collapseProject').collapse('hide');
				$('#collapseRFM').collapse('hide');
			}
        });

		$('#project').on('change', function (e) {
			// TODO: Get specific project available task
            var optionSelected = $("option:selected", this);
            var valueSelected = this.value;

            if (valueSelected !== "-") {
                $('#collapseProjectSubtask').collapse('show');
            } else {
                $('#collapseProjectSubtask').collapse('hide');
            }
        });
    });

	function post_request_dr() {
		var form = $('#frm-create-task')[0];
		var data = new FormData(form);
		console.log(data);
		$.ajax({
			type: "post",
			url: "dailyreport_controller/post_request_dr",
			data: data,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function() {
				$('.btn_post_request').html('<a href="javascript:void(0)" class="btn btn-secondary"><i class="fas fa-spinner fa-pulse"></i> Proses</a>');
			},
			success: function (res) {
				var isValid = res.isValid,
					isPesan = res.isPesan;

				console.log(`${isValid}: ${isPesan}`);

				if(isValid == 0) {
					$('.btn_post_request').html('<a href="javascript:void(0)" onclick="post_request_dr()" class="btn btn-success"><i class="fa fa-check"></i> Kirim</a>');
					$('.pesan').html(isPesan);
				}else {
					$('.pesan').html(isPesan);
					setTimeout (()=> window.location.reload(), 2000);
				}
				$('#modal-create-task').modal('hide');
			}
		});
	}
	
</script>
