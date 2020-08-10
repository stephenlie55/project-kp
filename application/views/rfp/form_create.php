            <div class="modal-header">
                <h4 class="modal-title">TULIS RFP BARU</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form id="frm-create" method="post" enctype="multipart/form-data">
                    <div class="pesan"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>PROBLEM TYPE :</label>
                            <select name="problem_type" class="form-control" required>
                                <option value="">PROBLEM TYPE</option>
                                <?php foreach($problem_type->result() as $r): ?>
                                    <option value="<?php echo $r->id ?>"><?php echo $r->problem_type ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label>REQUEST TYPE :</label>
                            <select name="request_type" class="form-control" required>
                                <option value="">REQUEST TYPE</option>
                                <?php foreach($request_type->result() as $r): ?>
                                    <option value="<?php echo $r->id ?>"><?php echo $r->request_type ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>SUBJECT :<?php $cc ?></label>
                        <input type="text" name="subject" class="form-control" placeholder="Subject. . ." required>
                    </div>

                    <div class="form-group">
                        <textarea style="resize: none" name="detail" class="form-control" placeholder="Detail. . ." rows="5" required></textarea>
                    </div>
                    <div class="form-group text-primary">
                        <i class="far fa-clock"></i> <?php echo date('d-m-Y') ?>
                    </div>

                    <div id="files"></div>

                    <div class="row">
                        <div class="col-md-6">
                            Attachment: <a href="javascript:void(0)" class="btn btn-warning text-white" onclick="addFile();"><i class="fa fa-paperclip"></i></a>         
                        </div>

                        <div class="col-md-6 text-right">
                            <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('USER_ID') ?>" readonly>
                            
                            <input type="hidden" name="kode_cabang" value="<?php echo $this->session->userdata('USER_KODE_CABANG') ?>" readonly>
                            
                            <input type="hidden" name="head_id" value="<?php echo $this->session->userdata('USER_INDUK') ?>" readonly>

                            <!-- btn_kirim -->
                            <div class="btn_post_request">
                                <a href="javascript:void(0)" onclick="post_request()" class="btn btn-success"><i class="fa fa-check"></i> Kirim</a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>