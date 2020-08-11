<div class="modal-header">
    <h4 class="modal-title">REJECT RFM <?php echo $rows->no_rfm ?></h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
    <form id="frm-reject" method="post">
        <div class="form-group">
        <input type="hidden" name="id_rfm" value="<?php echo $rows->id ?>">
            <textarea name="notes" class="form-control" style="resize: none" placeholder="Notes..."></textarea>
        </div>
        <div class="row">
            <div class="col-md-6">
                <a href="javascript:void(0)" onclick="set_reject_request()" class="btn btn-danger btn-block"><i class="far fa-check-circle"></i> YA</a>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-success btn-block" data-dismiss="modal"><i class="far fa-times-circle"></i> TIDAK</button>
            </div>
        </div>
    </form>
</div>