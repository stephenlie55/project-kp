<div class="modal-header">
    <h4 class="modal-title">BERI ULASAN</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
    <form id="frm-rating" method="post" enctype="multipart/form-data">
        <div class="pesan"></div>
        <div class="row">
            <div class="col-md-6">
                <label>PROBLEM TYPE :</label>
                <select name="" class="form-control" required disabled>
                    <?php
                        $this->db->where('id', $rows->problem_type);
                        $pt_id = $this->db->get(TB_PROBLEM_TYPE)->row();
                        
                        $this->db->where('id', $rows->request_type);
                        $rt_id = $this->db->get(TB_REQUEST_TYPE)->row();
                    ?>
                    <option value="<?php echo $rows->problem_type ?>"><?php echo $pt_id->problem_type ?></option>
                </select>
            </div>
            
            <div class="col-md-6">
                <label>REQUEST TYPE :</label>
                <select name="" class="form-control" required disabled>
                    <option value="<?php echo $rows->request_type ?>"><?php echo $rt_id->request_type ?></option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>SUBJECT :</label>
            <input type="text" name="" class="form-control" placeholder="Subject. . ." value="<?php echo $rows->subject ?>" required readonly>
        </div>

        <div class="form-group">
            <textarea name="" class="form-control" placeholder="Detail. . ." rows="5" required readonly><?php echo $rows->rfm_detail ?></textarea>
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
        
        <?php if(!empty($rows->done_notes)): ?>
        <div class="form-group">
            <label>Notes : <?php echo $notes_name_done->nama." | ".$rows->done_date ?></label>
            <textarea placeholder="Notes..." rows="2" class="form-control" readonly><?php echo $rows->done_notes ?></textarea>
        </div>
        <?php endif ?>
        
        <div class="form-group">
            <label>Tulis Notes :</label>
            <textarea name="notes" placeholder="Notes..." rows="2" class="form-control" require></textarea>
        </div>

        <div class="form-group text-center">
            <span class="star-rating star-5">
                <input type="radio" name="rates" value="1"><i></i>
                <input type="radio" name="rates" value="2"><i></i>
                <input type="radio" name="rates" value="3"><i></i>
                <input type="radio" name="rates" value="4"><i></i>
                <input type="radio" name="rates" value="5"><i></i>
            </span>
        </div>

        <div class="form-group">
            <input type="hidden" name="id_rfm" value="<?php echo $rows->id ?>">
            <div class="btn_post_request">
                <a href="javascript:void(0)" onclick="set_rating_request()" class="btn btn-primary btn-block"><i class="far fa-check-circle"></i> Simpan</a>
            </div>
        </div>

    </form>
</div>