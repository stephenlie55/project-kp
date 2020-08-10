<div class="card mb-3" id="table">
    <div class="card-header">
        <button class="btn btn-success btn-sm" id="btn_create" data-id="<?php echo $SESSION_USER_ID ?>" data-toggle="modal" data-target="#modal-create-rfm">
            <i class="far fa-comments"></i> Tulis RFM
        </button>
    </div>
    <div class="card-body">
    <div class="pesan"></div>
    <!-- table table-bordered table-hover -->
        <table class="colapse-table res3" id="tb_detail" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>#</th>
                <th>REQUEST BY</th>
                <th>APPROVAL</th>
                <th>NO.RFM</th>
                <th>TIME</th>
                <th>REQUEST STATUS</th>
                <th>RESULT STATUS</th>
                <th>PIC</th>
                <th>OPTION</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-create-rfm">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="view-modal-create"></div>
    </div>
</div>

<div class="modal fade" id="modal-edit-rfm">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="view-modal-edit"></div>
    </div>
</div>

<div class="modal fade" id="modal-approve-rfm">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="view-approve-rfm"></div>
    </div>
</div>

<div class="modal fade" id="modal-reject-rfm">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" id="view-reject-rfm"></div>
    </div>
</div>

<div class="modal fade" id="modal-rating-rfm">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="view-rating-rfm"></div>
    </div>
</div>