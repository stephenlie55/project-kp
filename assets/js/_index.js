$(document).ready(function(){
    preloader();
    bell();
    
    $('.tombol').click(function(){
        $('.nav-slider').toggleClass("slide-menu-tampil");
    });
    
    var tb_detail = $('#tb_detail').DataTable({ 
        "bSort" : false,
        "processing": true, 
        "serverSide": true,
         
        "ajax": {
            "url": "rfm_controller/get_tb_detail",
            "type": "POST"
        },

        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": '',
            },
            { "data": "0" },
            { "data": "1" },
            { "data": "2" },
            { "data": "3" },
            { "data": "4" },
            { "data": "5" },
            { "data": "6" },
            { "data": "7" },
        ],
        "order": []

    });

    $('#tb_detail tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = tb_detail.row( tr );
 
        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            row.child( format_tb_detail(row.data()) ).show();
            tr.addClass('shown');
        }
    });
    
    //RFP==========================
    var tb_detail_rfp = $('#tb_detail_rfp').DataTable({ 
        "bSort" : false,
        "processing": true, 
        "serverSide": true,
         
        "ajax": {
            "url": "rfp_controller/get_tb_detail",
            "type": "POST"
        },

        "columns": [
            {
                "className":      'details-control_rfp',
                "orderable":      false,
                "data":           null,
                "defaultContent": '',
            },
            { "data": "0" },
            { "data": "1" },
            { "data": "2" },
            { "data": "3" },
            { "data": "4" },
            { "data": "5" },
            { "data": "6" },
            { "data": "7" },
        ],
        "order": []

    });

    $('#tb_detail_rfp tbody').on('click', 'td.details-control_rfp', function () {
        var tr = $(this).closest('tr');
        var row = tb_detail_rfp.row( tr );
 
        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            row.child( format_tb_detail_rfp(row.data()) ).show();
            tr.addClass('shown');
        }
    });

    //USER MANAGEMENT====================
    var table = $('#tb_user').DataTable({ 
        "bSort" : false,
        "processing": true, 
        "serverSide": true, 
        "order": [], 
         
        "ajax": {
            "url": "um_controller/get_data_tip",
            "type": "POST"
        }

    });

    $('#form_add_akses').on('show.bs.modal', function (e) {
        var idx = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'um_controller/get_akses',
            data :  'idx='+ idx,
            cache: false,
            success : function(data){
                $('.hasil-data').html(data);
            }
        });
    });
    //===================================

    $('#modal-create-rfm').on('show.bs.modal', function (e) {
        var data = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'rfm_controller/btn_create',
            data :  'idx='+ data,
            cache: false,
            success : function(res) {
                $('#view-modal-create').html(res);
            }
        });
    })

    $('#modal-edit-rfm').on('show.bs.modal', function (e) {
        var data = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'rfm_controller/btn_edit',
            data :  'idx='+ data,
            cache: false,
            success : function(res) {
                $('#view-modal-edit').html(res);
            }
        });
    })

    $('#modal-approve-rfm').on('show.bs.modal', function (e) {
        var data = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'rfm_controller/btn_approve',
            data :  'idx='+ data,
            cache: false,
            success : function(res) {
                $('#view-approve-rfm').html(res);
            }
        });
    })

    $('#modal-rating-rfm').on('show.bs.modal', function (e) {
        var data = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'rfm_controller/btn_rating',
            data :  'idx='+ data,
            cache: false,
            success : function(res) {
                $('#view-rating-rfm').html(res);
            }
        });
    })

    //RFP=============================
    $('#modal-create-rfp').on('show.bs.modal', function (e) {
        var data = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'rfp_controller/btn_create',
            data :  'idx='+ data,
            cache: false,
            success : function(res) {
                $('#view-modal-create').html(res);
            }
        });
    })
    //=====================================

})
    
function preloader()
{
    $(".preloader").fadeOut(1000);
}

function bell() {
    $.ajax({
        url: "rfm_controller/bell",
        success: function (response) {
            $('.rfm-bells').html(response);
        }
    });

    
    $.ajax({
        url: "rfp_controller/bell",
        success: function (response) {
            $('.rfp-bells').html(response);
        }
    });
}

//reload datatable ajax
function reload_table(){
    $('#tb_detail').DataTable().ajax.reload(null, false);
    $('#tb_detail_rfp').DataTable().ajax.reload(null, false);
    bell();
}

//collapse tb_detail
function format_tb_detail(row)
{
    return '<table width="100%" cellspacing="0" border="0">'+
        '<tr class="res-mob">'+
            '<td width="10px">No.Rfm</td>'+
            '<td>'+row[2]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Type</td>'+
            '<td>'+row[8]+' ('+row[9]+')</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Subject</td>'+
            '<td>'+row[10]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Detail</td>'+
            '<td>'+row[11]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Approve Notes</td>'+
            '<td>'+row[13]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">IT Notes</td>'+
            '<td>'+row[14]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">PIC Notes</td>'+
            '<td>'+row[15]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Reject Notes</td>'+
            '<td>'+row[16]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Confirm Notes</td>'+
            '<td>'+row[17]+'</td>'+
        '</tr>'+

        //MOBILE VIEW
        '<tr class="res-mob">'+
            '<td width="10px">Time</td>'+
            '<td>'+row[3]+'</td>'+
        '</tr>'+
        '<tr class="res-mob">'+
            '<td width="10px">Status</td>'+
            '<td>'+row[4]+' - '+row[5]+'</td>'+
        '</tr>'+
        '<tr class="res-mob">'+
            '<td width="10px">PIC</td>'+
            '<td>'+row[6]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Rating</td>'+
            '<td>'+row[12]+'</td>'+
        '</tr>'+
        '<tr class="res-mob">'+
            '<td width="10px">Option</td>'+
            '<td>'+row[7]+'</td>'+
        '</tr>'+
    '</table>';
}
//RFP=======================
function format_tb_detail_rfp(row)
{
    return '<table width="100%" cellspacing="0" border="0">'+
        '<tr class="res-mob">'+
            '<td width="10px">No.Rfp</td>'+
            '<td>'+row[2]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Type</td>'+
            '<td>'+row[8]+' ('+row[9]+')</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Subject</td>'+
            '<td>'+row[10]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Detail</td>'+
            '<td>'+row[11]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Approve Notes</td>'+
            '<td>'+row[13]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">IT Notes</td>'+
            '<td>'+row[14]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">PIC Notes</td>'+
            '<td>'+row[15]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Reject Notes</td>'+
            '<td>'+row[16]+'</td>'+
        '</tr>'+

        //MOBILE VIEW
        '<tr class="res-mob">'+
            '<td width="10px">Time</td>'+
            '<td>'+row[3]+'</td>'+
        '</tr>'+
        '<tr class="res-mob">'+
            '<td width="10px">Status</td>'+
            '<td>'+row[4]+' - '+row[5]+'</td>'+
        '</tr>'+
        '<tr class="res-mob">'+
            '<td width="10px">PIC</td>'+
            '<td>'+row[6]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10px">Rating</td>'+
            '<td>'+row[12]+'</td>'+
        '</tr>'+
        '<tr class="res-mob">'+
            '<td width="10px">Option</td>'+
            '<td>'+row[7]+'</td>'+
        '</tr>'+
    '</table>';
}


//-----Adds an element to the document-------
function addElement(parentId, elementTag, elementId, html) {
    var p = document.getElementById(parentId);
    var newElement = document.createElement(elementTag);
    newElement.setAttribute('id', elementId);
    newElement.innerHTML = html;
    p.appendChild(newElement);
}

function removeElement(elementId) {
    var element = document.getElementById(elementId);
    element.parentNode.removeChild(element);
}

var fileId = 0;
function addFile() {
    fileId++;
    var html =  '<input type="file" name="attachment[]" />'+
                ' <a href="javascript:void(0)" onclick="javascript:removeElement(\'file-' + fileId + '\'); return false;">'+
                '<i class="far fa-window-close fa-lg text-danger"></i></a>';
    addElement('files', 'p', 'file-' + fileId, html);
}
//-----Adds an element to the document-------

//-----create new rfm post request-------
function post_request() {
    // var data = $('#frm-create').serialize();
    var form = $('#frm-create')[0];
    var data = new FormData(form);
    $.ajax({
        type: "post",
        url: "rfm_controller/post_request",
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
            if(isValid == 0) {
                $('.btn_post_request').html('<a href="javascript:void(0)" onclick="post_request()" class="btn btn-success"><i class="fa fa-check"></i> Kirim</a>');
                $('.pesan').html(isPesan);
            }else {
                $('.pesan').html(isPesan);
                $('#modal-create-rfm').modal('hide');
                $('#modal-create-rfp').modal('hide');
                reload_table();
            }
        }
    });
}
//-----------------------------------------------

//-----edit rfm post request-------
function set_post_request() {
    // var data = $('#frm-create').serialize();
    var form = $('#frm-edit')[0];
    var data = new FormData(form);
    $.ajax({
        type: "post",
        url: "rfm_controller/set_post_request",
        data: data,
        processData: false,
        contentType: false,
        dataType: "json",
        cache: false,
        beforeSend: function() {
            $('.btn_post_request').html('<a href="javascript:void(0)" class="btn btn-secondary"><i class="fas fa-spinner fa-pulse"></i> Proses</a>');
        },
        success: function (res) {
            var isValid = res.isValid,
                isPesan = res.isPesan;
            if(isValid == 0) {
                $('.btn_post_request').html('<a href="javascript:void(0)" onclick="set_post_request()" class="btn btn-success"><i class="fa fa-check"></i> Kirim</a>');
                $('.pesan').html(isPesan);
            }else {
                $('.pesan').html(isPesan);
                $('#modal-edit-rfm').modal('hide');
                reload_table();
            }
        }
    });
}
//-------------------------------------

//-----update it rfm post request-------
function set_update_it() {
    // var data = $('#frm-create').serialize();
    var form = $('#frm-app')[0];
    var data = new FormData(form);
    $.ajax({
        type: "post",
        url: "rfm_controller/set_update_it",
        data: data,
        processData: false,
        contentType: false,
        dataType: "json",
        cache: false,
        beforeSend: function() {
            $('.btn_post_request').html('<a href="javascript:void(0)" class="btn btn-secondary"><i class="fas fa-spinner fa-pulse"></i> Proses</a>');
        },
        success: function (res) {
            var isValid = res.isValid,
                isPesan = res.isPesan;
            if(isValid == 0) {
                $('.btn_post_request').html('<a href="javascript:void(0)" onclick="set_update_it()" class="btn btn-success"><i class="far fa-check-circle"></i> Update</a><a href="javascript:void(0)" onclick="set_app_request()" class="btn btn-success"><i class="far fa-check-circle"></i> Approve</a> <a href="javascript:void(0)" onclick="" class="btn btn-success"><i class="far fa-times-circle"></i> Reject</a>');
                $('.pesan').html(isPesan);
            }else {
                $('.pesan').html(isPesan);
                $('#modal-approve-rfm').modal('hide');
                reload_table();
            }
        }
    });
}
//-------------------------------------

//-----create new rfm post request-------
function set_app_request() {
    // var data = $('#frm-create').serialize();
    var form = $('#frm-app')[0];
    var data = new FormData(form);
    $.ajax({
        type: "post",
        url: "rfm_controller/set_app_request",
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
            if(isValid == 0) {
                $('.btn_post_request').html('<a href="javascript:void(0)" onclick="set_app_request()" class="btn btn-success"><i class="far fa-check-circle"></i> Approve</a> <a href="javascript:void(0)" onclick="" class="btn btn-success"><i class="far fa-times-circle"></i> Reject</a>');
                $('.pesan').html(isPesan);
            }else {
                $('.pesan').html(isPesan);
                $('#modal-approve-rfm').modal('hide');
                reload_table();
            }
        }
    });
}
//-------------------------------------

//-----create new rfm post request-------
function set_assign_request() {
    // var data = $('#frm-create').serialize();
    var form = $('#frm-app')[0];
    var data = new FormData(form);
    $.ajax({
        type: "post",
        url: "rfm_controller/set_assign_request",
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
            if(isValid == 0) {
                $('.btn_post_request').html('<a href="javascript:void(0)" onclick="set_assign_request()" class="btn btn-success"><i class="far fa-check-circle"></i> Assign</a> <a href="javascript:void(0)" onclick="" class="btn btn-success"><i class="far fa-times-circle"></i> Reject</a>');
                $('.pesan').html(isPesan);
            }else {
                $('.pesan').html(isPesan);
                $('#modal-approve-rfm').modal('hide');
                reload_table();
            }
        }
    });
}
//-------------------------------------

//-----create new rfm post request-------
function set_done_request() {
    // var data = $('#frm-create').serialize();
    var form = $('#frm-app')[0];
    var data = new FormData(form);
    $.ajax({
        type: "post",
        url: "rfm_controller/set_done_request",
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
            if(isValid == 0) {
                $('.btn_post_request').html('<a href="javascript:void(0)" onclick="set_done_request()" class="btn btn-success"><i class="far fa-check-circle"></i> Assign</a> <a href="javascript:void(0)" onclick="" class="btn btn-success"><i class="far fa-times-circle"></i> Batal</a>');
                $('.pesan').html(isPesan);
            }else {
                $('.pesan').html(isPesan);
                $('#modal-approve-rfm').modal('hide');
                reload_table();
            }
        }
    });
}
//-------------------------------------

//-----reject rfm post request-------
function confirm_reject() {
    var data = $('#frm-app').serialize();
    $.ajax({
        type : 'post',
        url : 'rfm_controller/btn_reject',
        data :  data,
        cache: false,
        success : function(res) {
            $('#modal-reject-rfm').modal('show');
            $('#view-reject-rfm').html(res);
        }
    });
}

function set_reject_request() {
    // var data = $('#frm-create').serialize();
    var form = $('#frm-reject')[0];
    var data = new FormData(form);
    $.ajax({
        type: "post",
        url: "rfm_controller/set_reject_request",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        dataType: "json",
        beforeSend: function() {
            $('#modal-reject-rfm').modal('hide');
        },
        success: function (res) {
            var isValid = res.isValid,
                isPesan = res.isPesan;
            if(isValid == 0) {
                $('.btn_post_request').html('<a href="javascript:void(0)" class="btn btn-secondary"><i class="fas fa-spinner fa-pulse"></i> Proses</a> <a href="javascript:void(0)" onclick="confirm_reject()" class="btn btn-danger"><i class="far fa-times-circle"></i> Reject</a>');
                $('.pesan').html(isPesan);
            }else {
                $('.pesan').html(isPesan);
                $('#modal-approve-rfm').modal('hide');
                reload_table();
            }
        }
    });
}
//-------------------------------------

function set_rating_request() {
    // var data = $('#frm-rating').serialize();
    var form = $('#frm-rating')[0];
    var data = new FormData(form);
    $.ajax({
        type: "post",
        url: "rfm_controller/set_rating_request",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        dataType: "json",
        beforeSend: function() {
            $('.btn_post_request').html('<a href="javascript:void(0)" class="btn btn-secondary btn-block"><i class="fas fa-spinner fa-pulse"></i> Proses</a>');
        },
        success: function (res) {
            var isValid = res.isValid,
                isPesan = res.isPesan;
            if(isValid == 0) {
                $('.btn_post_request').html('<a href="javascript:void(0)" onclick="set_rating_request()" class="btn btn-success btn-block"><i class="far fa-check-circle"></i> Simpan</a>');
                $('.pesan').html(isPesan);
            }else {
                $('.pesan').html(isPesan);
                $('#modal-rating-rfm').modal('hide');
                reload_table();
            }
        }
    });
}
//-------------------------------------