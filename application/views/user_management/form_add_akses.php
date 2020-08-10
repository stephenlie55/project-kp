<div class="modal-body">
    <div class="modal-header">
        <h4 class="modal-title">HAK AKSES <?php echo strtoupper($user_fullname) ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <form id="form_hak_akses" method="post">
            <div class="table-responsive modal-akses">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th width="15%">
                                <div class="row">
                                    <div class="col-md-6">
                                        YES
                                    </div>
                                    <div class="col-md-6">
                                        NO
                                    </div>
                                </div>
                            </th>
                            <th>AKSES</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($my_akses as $r): ?>
                        <tr>
                            <td>
                                <div class="row">
                                <?php
                                    $data = explode(':', $r->value);
                                    if(in_array($idx, $data)) {
                                        $update_akses = str_replace("$idx:", "", $r->value);
                                        echo "
                                        <div class='col-md-6'>
                                            <input type='radio' name='$r->id' value='$r->value' checked> Y
                                        </div>
                                        <div class='col-md-6'>
                                            <input type='radio' name='$r->id' value='$update_akses'> N
                                        </div>
                                        ";
                                    }else {
                                        $update_akses = $r->value.$idx.":";
                                        echo "
                                        <div class='col-md-6'>
                                            <input type='radio' name='$r->id' value='$update_akses'> Y
                                        </div>
                                        <div class='col-md-6'>
                                            <input type='radio' name='$r->id' value='$r->value' checked> N
                                        </div>
                                        ";
                                    }
                                ?>
                                </div>
                            </td>
                            <td>
                                <?php
                                    if($r->id == 'RFM_AKSES_USER_MANAGEMENT') {
                                        echo "
                                            AKSES MENU USER MANAGEMENT
                                        ";
                                    }elseif($r->id == 'RFM_AKSES_OP_APP_JABODETABEK') {
                                        echo "
                                            AKSES APPROVAL OPERASIONAL JABODETABEK
                                        ";
                                    }elseif($r->id == 'RFM_AKSES_OP_APP_BDG') {
                                        echo "
                                            AKSES APPROVAL OPERASIONAL BANDUNG
                                        ";
                                    }elseif($r->id == 'RFM_AKSES_IT_APP') {
                                        echo "
                                            AKSES APPROVAL IT
                                        ";
                                    }else {
                                        echo $r->id;
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <input type="hidden" name="user" value="<?php echo $idx ?>">
            <a href="javascript:void(0)" id="btn_akses_baru" class="btn btn-success btn-block">SIMPAN</a>
            <div class="pesan text-center"></div>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    $("#btn_akses_baru").click(function() {
        var data = $("#form_hak_akses").serialize();
        $.ajax({
            type: "post",
            url: "um_controller/new_akses",
            data: data,
            dataType: "json",
            beforeSend : function() {
                $('#btn_akses_baru').hide();
            },
            success: function (e) {
                var isValid = e.isValid;
                var isPesan = e.isPesan;
                if(isValid == 1)
                {
                    $(".pesan").html("<b class='text-success'>"+isPesan+"</div>");
                    window.location.href ="<?php echo base_url('um_controller') ?>";
                }
                else
                {
                    $(".pesan").html('<b class="text-danger">Gagal ditambah</div>');
                    $('#btn_akses_baru').show();
                }
            }
        });
    })
});
</script>