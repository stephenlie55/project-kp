<div class="modal-header">
    <h4 class="modal-title">APPROVE RFM</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
    <form id="frm-app" method="post" enctype="multipart/form-data">
        <div class="pesan"></div>
        <div class="row">
            <div class="col-md-6">
                <label>PROBLEM TYPE :</label>
                <select name="problem_type" class="form-control" required <?php echo $disabled ?>>
                    <?php
                        $this->db->where('id', $rows->problem_type);
                        $pt_id = $this->db->get(TB_PROBLEM_TYPE)->row();
                        
                        $this->db->where('id', $rows->request_type);
                        $rt_id = $this->db->get(TB_REQUEST_TYPE)->row();
                    ?>
                    <option value="<?php echo $rows->problem_type ?>"><?php echo $pt_id->problem_type ?></option>
                    <?php foreach($problem_type->result() as $r): ?>
                        <option value="<?php echo $r->id ?>"><?php echo $r->problem_type ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            
            <div class="col-md-6">
                <label>REQUEST TYPE :</label>
                <select name="request_type" class="form-control" required <?php echo $disabled ?>>
                    <option value="<?php echo $rows->request_type ?>"><?php echo $rt_id->request_type ?></option>
                    <?php foreach($request_type->result() as $r): ?>
                        <option value="<?php echo $r->id ?>"><?php echo $r->request_type ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>SUBJECT :</label>
            <input type="text" name="subject" class="form-control" placeholder="Subject. . ." value="<?php echo $rows->subject ?>" required <?php echo $readonly ?>>
        </div>

        <div class="form-group">
            <textarea name="detail" class="form-control" placeholder="Detail. . ." rows="5" required <?php echo $readonly ?>><?php echo $rows->rfm_detail ?></textarea>
        </div>
        <div class="form-group text-primary">
            <i class="far fa-clock"></i> <?php echo date('d-m-Y') ?>
        </div>

        <div class="form-group">
        <?php
            $no = 1;
            $this->db->where('rfm_id', $rows->id);
            $qAtt = $this->db->get(TB_ATTACHMENT);
            foreach($qAtt->result() as $rAtt){
                $nama_file = $rAtt->filename;
                $explode_file_ext = explode(".", $nama_file);
                $file_ext = $explode_file_ext[1];
                if($file_ext =='jpg' or $file_ext =='jpeg' or $file_ext =='png' or $file_ext =='PNG' or $file_ext =='gif' or $file_ext =='GIF'){
        ?>
                <span id="name_id<?php echo $rAtt->id ?>">
                    <a title="<?php echo $rAtt->filename ?>" target="_blank" href="<?php echo $rAtt->data_file ?>" class=""><i class="far fa-image fa-2x"></i></a>
                </span>
        <?php
            }elseif($file_ext =='docx' or $file_ext =='docm' or $file_ext =='dotx' or $file_ext =='dotm'){
        ?>
                <span id="name_id<?php echo $rAtt->id?>">
                    <a title="<?php echo $rAtt->filename?>" target="_blank" href="<?php echo $rAtt->data_file?>" class=""><i class="far fa-file-word fa-2x"></i></a>
                </span>
        <?php
            }elseif($file_ext =='xlsx' or $file_ext =='xlsm' or $file_ext =='xltx' or $file_ext =='xltm' or $file_ext =='xlsb' or $file_ext =='xlam'){
        ?>
                <span id="name_id<?php echo $rAtt->id?>">
                    <a title="<?php echo $rAtt->filename?>" target="_blank" href="<?php echo $rAtt->data_file?>" class=""><i class="far fa-file-excel fa-2x"></i></a>
                </span>
        <?php
            }else{
        ?>
                <span id="name_id<?php echo $rAtt->id?>">
                    <a title="<?php echo $rAtt->filename?>" target="_blank" href="<?php echo $rAtt->data_file?>" class=""><i class="far fa-file fa-2x"></i></a>
                    </label>
                </span>
        <?php
                }
            }
        ?>
        </div>
        
        <?php if(!empty($rows->approve_notes)): ?>
        <div class="form-group">
            <label>Notes : <?php echo $notes_name_approve->nama." | ".$rows->approve_date ?></label>
            <textarea placeholder="Notes..." rows="2" class="form-control" readonly><?php echo $rows->approve_notes ?></textarea>
        </div>
        <?php endif ?>
        
        <?php if(!empty($rows->receive_notes)): ?>
        <div class="form-group">
            <label>Notes : <?php echo $notes_name_receive->nama." | ".$rows->receive_date ?></label>
            <textarea placeholder="Notes..." rows="2" class="form-control" readonly><?php echo $rows->receive_notes ?></textarea>
        </div>
        <?php endif ?>
        
        <div class="form-group">
            <label>Tulis Notes :</label>
            <textarea name="notes" placeholder="Notes..." rows="2" class="form-control"></textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?php if(!empty($rows->receive_by) AND $rows->request_status==STT_APPROVED): ?>
                <div class="row">
                    <div class="col-md-6">
                        <label>PIC :</label>
                        <select name="assign_pic" class="form-control">
                            <option value="">PILIH P.I.C</option>
                            <?php foreach($select_pic->result() as $r): ?>
                                <option value="<?php echo $r->user_id ?>"><?php echo $r->nama ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>TARGET DATE :</label>
                        <input type="date" name="target_date" class="form-control">
                    </div>
                </div>
                <?php endif ?>
            </div>

            <div class="col-md-6 text-right pt-4">
                <input type="hidden" name="id_rfm" value="<?php echo $rows->id ?>">
                <!-- btn_kirim -->
                <div class="btn_post_request">
                    <?php echo $btn_update ?>
                    <a href="javascript:void(0)" onclick="<?php echo $onclick ?>" class="btn btn-primary"><i class="far fa-check-circle"></i> <?php echo $btnText ?></a>

                    <a href="javascript:void(0)" onclick="confirm_reject()" class="btn btn-danger" <?php echo $reject_aa ?>><i class="far fa-times-circle"></i> Reject</a>

                    <a href="javascript:void(0)" onclick="<?php echo $onclickReject ?>" class="btn btn-danger" <?php echo $closeModal ?>><i class="far fa-times-circle"></i> <?php echo $btnTextReject ?></a>
                </div>
            </div>
        </div>

    </form>
</div>