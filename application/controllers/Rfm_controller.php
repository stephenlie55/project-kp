<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfm_controller extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('rfm_model');
        $this->load->model('auth_model');
    }

	public function index()
	{
        if($this->auth_model->logged_id()) {
            $data['SESSION_USER_ID'] = $this->session->userdata('USER_ID');
            $this->template->load('template','rfm/table',$data);
        } else {
            $this->load->view('login/form_login');
        }
    }
    
    function get_tb_detail()
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');
        $SESSION_USER_GROUP_MENU = $this->session->userdata('USER_GROUP_MENU');

        $array_crud = array(
            'table' => TB_PARAMETER,
            'where' => array('id' => 'RFM_AKSES_OP_APP_BDG')
        );
        $area_bandung = $this->rfm_model->get_crud($array_crud)->row();
        
        $array_crud = array(
            'table' => TB_PARAMETER,
            'where' => array('id' => 'RFM_AKSES_OP_APP_JABODETABEK')
        );
        $area_jabodetabek = $this->rfm_model->get_crud($array_crud)->row();
        
        $array_crud = array(
            'table' => TB_PARAMETER,
            'where' => array('id' => 'RFM_AKSES_IT_APP')
        );
        $area_it = $this->rfm_model->get_crud($array_crud)->row();
        
        $ex_id_bandung = explode(":", $area_bandung->value);
        $ex_id_jabodetabek = explode(":", $area_jabodetabek->value);
        $ex_id_it = explode(":", $area_it->value);

        $SESSION_UPLINE = $SESSION_USER_ID;

        if(in_array($SESSION_USER_ID, $ex_id_bandung))
        {
            $SESSION_UPLINE = $area_bandung->value;
        }

        if(in_array($SESSION_USER_ID, $ex_id_jabodetabek))
        {
            $SESSION_UPLINE = $area_jabodetabek->value;
        }

        if(in_array($SESSION_USER_ID, $ex_id_it))
        {
            $SESSION_UPLINE = $area_it->value;
        }
        
        $list = $this->rfm_model->get_datatables($SESSION_UPLINE);
        $data = array();
        $no = $_POST['start'];
        
        foreach ($list as $field) {

            // problem type
            $array_crud = array(
                'table' => TB_PROBLEM_TYPE,
                'where' => array('id' => $field->problem_type),
            );
            $row_problem_type = $this->rfm_model->get_crud($array_crud)->row()->problem_type;

            // request status
            $array_crud = array(
                'table' => TB_REQUEST_TYPE,
                'where' => array('id' => $field->request_type),
            );
            $row_request_type = $this->rfm_model->get_crud($array_crud)->row()->request_type;

            // nama pic
            if($field->assign_to === NULL) {
                $row_assign_to = '-';
            }else {
                $array_crud = array(
                    'table' => TB_USER,
                    'where' => array('user_id' => $field->assign_to),
                );
                $row_assign_to = $this->rfm_model->get_crud($array_crud);
                if(!empty($row_assign_to->num_rows())) {
                    $row_assign_to = $row_assign_to->row()->nama;
                }else{
                    $row_assign_to = '-';
                }
            }

            // btn approve sesuai status
            $btn_option = "<a class='btn btn-primary text-light btn-sm btn-block' href='javascript:void(0)' data-toggle='modal' data-target='#modal-approve-rfm' data-id='$field->id' title='Approve RFM'><i class='fa fa-spell-check'></i></a>";
            
            $explode_request_upline_by = explode(':', $field->request_upline_by);
            $explode_receive_by = explode(':', $field->receive_by);
            
            if(in_array($SESSION_USER_ID, $explode_request_upline_by) AND $field->request_status === STT_ON_QUEUE) {
                $btn_option = $btn_option;
            }elseif(in_array($SESSION_USER_ID, $explode_receive_by) AND $field->request_status === STT_APPROVED) {
                $btn_option = $btn_option;
            }elseif($field->assign_to === $SESSION_USER_ID AND $field->request_status === STT_ON_PROGRESS) {
                $btn_option = $btn_option;
            }else {
                $btn_option = "";
            }

            // nama yg harus approve
            $array_crud = array(
                'table' => TB_PARAMETER,
                'where' => array('id' => 'RFM_AKSES_IT_APP'),
            );
            $team_it = $this->rfm_model->get_crud($array_crud)->row()->value;

            if($field->request_upline_by != NULL AND $field->request_status === STT_ON_QUEUE) {
                if($field->request_upline_by === $team_it)
                {
                    $app_by = "IT";
                }
                else
                {
                    $array_crud = array(
                        'table' => TB_USER,
                        'where' => array('user_id' => $field->request_upline_by),
                    );
                    $app_by = $this->rfm_model->get_crud($array_crud)->row()->nama;
                }
            }else {
                $app_by = 'IT';
            }
            
            // btn edit di status on queue
            $btn_rating = "<a class='btn btn-success text-warning btn-sm btn-block' href='javascript:void(0)' data-toggle='modal' data-target='#modal-rating-rfm' data-id='$field->id' title='Give Rating'><i class='fa fa-star'></i></a>";
            if($field->request_by === $SESSION_USER_ID AND $field->request_status === STT_DONE AND $field->result_status === STT_PENDING) {
                $btn_option = $btn_rating;
            }

            // btn edit di status on queue
            $btn_edit = "<a class='btn btn-warning text-light btn-sm btn-block' href='javascript:void(0)' data-toggle='modal' data-target='#modal-edit-rfm' data-id='$field->id' title='Edit RFM'><i class='fa fa-edit'></i></a>";
            if($field->request_by === $SESSION_USER_ID AND $field->request_status === STT_ON_QUEUE) {
                $btn_option = $btn_edit;
            }

            //txt color
            if($field->request_status === STT_ON_QUEUE)
            {
                $txtApprove = "<b class='text-warning'>$field->request_status</b>";
            }
            elseif($field->request_status === STT_VALIDATED)
            {
                $txtApprove = "<b class='text-secondary'>$field->request_status</b>";
            }
            elseif($field->request_status === STT_APPROVED)
            {
                $txtApprove = "<b class='text-primary'>$field->request_status</b>";
            }
            elseif($field->request_status === STT_ON_PROGRESS)
            {
                $txtApprove = "<b class='text-dark'>$field->request_status</b>";
            }
            elseif($field->request_status === STT_DONE)
            {
                $txtApprove = "<b class='text-success'>$field->request_status</b>";
            }
            elseif($field->request_status === STT_REJECT)
            {
                $txtApprove = "<b class='text-danger'>$field->request_status</b>";
            }
            else
            {
                $txtApprove = "$field->request_status";
            }

            //icon rating
            if($field->rates === '1')
            {
                $rates = "<i class='fa fa-star text-warning'></i>";
            }
            elseif($field->rates === '2')
            {
                $rates = "<i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i>";
            }
            elseif($field->rates === '3')
            {
                $rates = "<i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i>";
            }
            elseif($field->rates === '4')
            {
                $rates = "<i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i>";
            }
            elseif($field->rates === '5')
            {
                $rates = "<i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i>";
            }
            else
            {
                $rates = "";
            }

            if($field->approve_notes != NULL)
            {
                $notes_approve = $field->approve_notes;
            }
            else
            {
                $notes_approve = "";
            }

            if($field->receive_notes != NULL)
            {
                $notes_receive = $field->receive_notes;
            }
            else
            {
                $notes_receive = "";
            }

            if($field->done_notes != NULL)
            {
                $notes_done = $field->done_notes;
            }
            else
            {
                $notes_done = "";
            }

            if($field->reject_notes != NULL OR $field->confirm_notes)
            {
                $notes_reject = $field->reject_notes;
                $confirm_notes = $field->confirm_notes;
            }
            else
            {
                $notes_reject = "-";
                $confirm_notes = "-";
            }

            $btn_case = "<a class='btn btn-success text-light btn-sm btn-block' href='javascript:void(0)' data-toggle='modal' data-target='#modal-case' data-id='$field->id' title='CASE'><i class='fa fa-exclamation-circle'></i></a>";
            if($SESSION_USER_GROUP_MENU === 'IT') {
                if($field->request_status === STT_DONE) {
                    $btn_case = $btn_case;
                }else {
                    $btn_case = NULL;
                }
            }else {
                $btn_case = NULL;
            }


            $no++;
            $row = array();
            $row[] = $field->nama;
            $row[] = $app_by;
            $row[] = $field->no_rfm;
            $row[] = date('d-m-Y', strtotime($field->request_date));
            $row[] = $txtApprove;
            $row[] = $field->result_status;
            $row[] = $row_assign_to;
            $row[] = $btn_option.$btn_case;
            $row[] = $row_problem_type;
            $row[] = $row_request_type;
            $row[] = $field->subject;
            $row[] = $field->rfm_detail;
            $row[] = $rates;
            $row[] = $notes_approve;
            $row[] = $notes_receive;
            $row[] = $notes_done;
            $row[] = $notes_reject;
            $row[] = $confirm_notes;
            $row[] = $field->id;
            $row[] = $field->jabatan;
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->rfm_model->count_all(),
            "recordsFiltered" => $this->rfm_model->count_filtered($SESSION_UPLINE),
            "data" => $data,
        );
        
        echo json_encode($output);
    }

    public function btn_create()
    {
        $id = $this->input->post('idx');
        $array_crud = array(
            'table' => TB_DETAIL,
            'where' => array(
                'request_by' => $id,
                'request_status' => STT_DONE,
                'result_status' => STT_PENDING
            )
        );
        $check = $this->rfm_model->get_crud($array_crud)->num_rows();
        if($check >= 1) {
            $data['isPesan'] = 'Kami telah menyelesaikan tiket support kamu, jangan lupa memberi kami penilaian. Terima kasih';
            $this->load->view('modal/notify', $data);
        }else {
            $array_crud = array(
                'table' => TB_PROBLEM_TYPE,
                'where' => array('system_type' => NULL),
            );
            $data['problem_type'] = $this->rfm_model->get_crud($array_crud);
            
            $array_crud = array(
                'table' => TB_REQUEST_TYPE,
            );
            $data['request_type'] = $this->rfm_model->get_crud($array_crud);
            $this->load->view('rfm/form_create', $data);
        }
    }

    public function btn_edit()
    {
        $id = $this->input->post('idx');
        $array_crud = array(
            'table' => TB_DETAIL,
            'where' => array(
                'id' => $id
            )
        );
        $row = $this->rfm_model->get_crud($array_crud)->row();
        $data['rows'] = $row;
        
        $array_crud = array(
            'table' => TB_PROBLEM_TYPE,
            // 'where' => array('system_type' => NULL),
        );
        $data['problem_type'] = $this->rfm_model->get_crud($array_crud);
        
        $array_crud = array(
            'table' => TB_REQUEST_TYPE,
        );
        $data['request_type'] = $this->rfm_model->get_crud($array_crud);

        $this->load->view('rfm/form_edit', $data);
    }

    public function btn_approve()
    {
        $id = $this->input->post('idx');
        $SESSION_USER_ID = $this->session->userdata('USER_ID');

        $array_crud = array(
            'table' => TB_DETAIL,
            'where' => array(
                'id' => $id
            )
        );
        $row = $this->rfm_model->get_crud($array_crud)->row();
        $data['rows'] = $row;
        
        $array_crud = array(
            'table' => TB_PROBLEM_TYPE,
            'where' => array('system_type' => NULL),
        );
        $data['problem_type'] = $this->rfm_model->get_crud($array_crud);
        
        $array_crud = array(
            'table' => TB_REQUEST_TYPE,
        );
        $data['request_type'] = $this->rfm_model->get_crud($array_crud);
        
        $array_crud = array(
            'table' => TB_USER,
            'where' => array(
                'group_menu' => 'IT',
                'flg_block' => 'N',
                )
        );
        $data['select_pic'] = $this->rfm_model->get_crud($array_crud);
        
        // nama yang tulis notes
        $explode_notes_name = explode(":", $row->approve_by);
        $notes_name = array_search($SESSION_USER_ID, $explode_notes_name);
        $array_crud = array(
            'table' => TB_USER,
            'where' => array(
                'user_id' => $explode_notes_name[$notes_name]
            )
        );
        $data['notes_name_approve'] = $this->rfm_model->get_crud($array_crud)->row();
        
        $explode_notes_name = explode(":", $row->receive_by);
        $notes_name = array_search($SESSION_USER_ID, $explode_notes_name);
        $array_crud = array(
            'table' => TB_USER,
            'where' => array(
                'user_id' => $explode_notes_name[$notes_name]
            )
        );
        $data['notes_name_receive'] = $this->rfm_model->get_crud($array_crud)->row();
        //=======================================================

        $explode_disabled = explode(":", $row->receive_by);
        foreach($explode_disabled as $r){
            $rows = $r;
            $data_explode_disabled[] = $rows;
        }
        if(in_array($SESSION_USER_ID, $data_explode_disabled))
        {
            $data['disabled'] = "";
            $data['readonly'] = "readonly";
            $data['onclick'] = "set_assign_request()";
            $data['btnText'] = "Assign";
            $data['btn_update'] = '<a href="javascript:void(0)" onclick="set_update_it()" class="btn btn-success"><i class="far fa-check-circle"></i> Update</a>';
        }
        else
        {
            $data['disabled'] = "disabled";
            $data['readonly'] = "readonly";
            $data['onclick'] = "set_app_request()";
            $data['btnText'] = "Approve";
            $data['btn_update'] = '';
        }
        
        if($SESSION_USER_ID === $row->assign_to)
        {
            if($row->request_upline_by===NULL AND $row->approve_by===NULL AND $row->receive_by===NULL)
            {
                $data['reject_aa'] = "";
            }
            else
            {
                $data['reject_aa'] = "style='display:none'";
            }
            
            $data['closeModal'] = "data-dismiss='modal'";
            $data['onclick'] = "set_done_request()";
            $data['btnText'] = "Done";
            $data['onclickReject'] = "";
            $data['btnTextReject'] = "Batal";
        }
        else
        {
            $data['onclickReject'] = "confirm_reject()";
            $data['btnTextReject'] = "Reject";
            $data['closeModal'] = "";
            $data['reject_aa'] = "style='display:none'";
        }

        $this->load->view('rfm/form_approval', $data);
    }

    public function btn_reject()
    {
        $id = $this->input->post('id_rfm');
        $SESSION_USER_ID = $this->session->userdata('USER_ID');

        $array_crud = array(
            'table' => TB_DETAIL,
            'where' => array(
                'id' => $id
            )
        );
        $row = $this->rfm_model->get_crud($array_crud)->row();
        $data['rows'] = $row;

        $this->load->view('rfm/form_reject', $data);
    }

    public function btn_rating()
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');
        $id = $this->input->post('idx');
        $array_crud = array(
            'table' => TB_DETAIL,
            'where' => array(
                'id' => $id
            )
        );
        $row = $this->rfm_model->get_crud($array_crud)->row();
        $data['rows'] = $row;
        
        $array_crud = array(
            'table' => TB_PROBLEM_TYPE,
            'where' => array('system_type' => NULL),
        );
        $data['problem_type'] = $this->rfm_model->get_crud($array_crud);
        
        $array_crud = array(
            'table' => TB_REQUEST_TYPE,
        );
        $data['request_type'] = $this->rfm_model->get_crud($array_crud);
        
        // nama yang tulis notes
        $explode_notes_name = explode(":", $row->approve_by);
        $notes_name = array_search($SESSION_USER_ID, $explode_notes_name);
        $array_crud = array(
            'table' => TB_USER,
            'where' => array(
                'user_id' => $explode_notes_name[$notes_name]
            )
        );
        $data['notes_name_approve'] = $this->rfm_model->get_crud($array_crud)->row();
        
        $explode_notes_name = explode(":", $row->receive_by);
        $notes_name = array_search($SESSION_USER_ID, $explode_notes_name);
        $array_crud = array(
            'table' => TB_USER,
            'where' => array(
                'user_id' => $explode_notes_name[$notes_name]
            )
        );
        $data['notes_name_receive'] = $this->rfm_model->get_crud($array_crud)->row();
        
        $array_crud = array(
            'table' => TB_USER,
            'where' => array(
                'user_id' => $row->assign_to
            )
        );
        $data['notes_name_done'] = $this->rfm_model->get_crud($array_crud)->row();
        //=======================================================

        $this->load->view('rfm/form_rating', $data);
    }

    public function post_request()
    {
        if(!$this->auth_model->logged_id())
        {
            $data = array('isValid' => 0, 'isPesan' => '<div class="alert alert-danger">Sesi telah berakhir, silahkan segarkan halaman ini terlebih dahulu. <a href="./">Segarkan</a></div>');
            echo json_encode($data);
            die();
        }
        $SESSION_USER_ID = $this->session->userdata('USER_ID');
        $SESSION_USER_DIVISI = $this->session->userdata('USER_DIVISI');
        $SESSION_USER_JABATAN = $this->session->userdata('USER_JABATAN');
        $SESSION_USER_INDUK = $this->session->userdata('USER_INDUK');
        $date_now = date('Y-m-d h:i:s');
        $problem_type = $this->input->post('problem_type');
        $request_type = $this->input->post('request_type');
        $subject = $this->input->post('subject');
        $detail = $this->input->post('detail');
        $user_id = $this->input->post('user_id');
        $kode_cabang = $this->input->post('kode_cabang');
        $head_id = $this->input->post('head_id');
        $array_crud = array(
            'table' => TB_PARAMETER,
            'where' => array('id' => 'RFM_EXTENSI_FILE'),
        );
        $row_extensi_value = $this->rfm_model->get_crud($array_crud)->row();
        $extensionList = explode(',', $row_extensi_value->value);
        
        $array_crud = array(
            'table' => TB_PARAMETER,
            'where' => array('id' => 'RFM_AKSES_IT_APP'),
        );
        $app_it = $this->rfm_model->get_crud($array_crud)->row()->value;
        $explode_app_it = explode(':', $app_it);
        foreach($explode_app_it as $r) {
            $rows = $r;
            $data_app_it[] = $rows;
        }

        //Generate no_rfm
        $array_crud = array(
            'select' => 'max(id) AS maxID',
            'table' => TB_DETAIL,
        );
        $resMax = $this->rfm_model->get_crud($array_crud)->row();
        $total = $resMax->maxID;
        $total++;
        $char = "IT/RFM/".$kode_cabang.".";
        $no_rfm = $char . sprintf("%06s", $total);

        if(empty($problem_type)) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Problem Type Harus Diisi !!!</div>";
        }elseif(empty($request_type)) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Request Type Harus Diisi !!!</div>";
        }elseif(empty($subject)) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Subject Harus Diisi !!!</div>";
        }elseif(empty($detail)) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Detail Harus Diisi !!!</div>";
        }else {
            if($SESSION_USER_JABATAN === 'DIREKSI')
            {
                $head_id = $app_it;
            }
            elseif($SESSION_USER_DIVISI === 'OPERASIONAL')
            {
                // FILTER AREA JABODETABEK DAN BANDUNG
                $array_crud = array(
                    'table' => TB_PARAMETER,
                    'where' => array('id' => 'RFM_KODE_AREA_BDG'),
                );
                $row_ft_area_value = $this->rfm_model->get_crud($array_crud)->row();
                $explode_ft_area_value = explode(':', $row_ft_area_value->value);
                foreach($explode_ft_area_value as $r) {
                    $rows = $r;
                    $data_ft_area[] = $rows;
                }
                
                $array_crud = array(
                    'table' => TB_PARAMETER,
                    'where' => array('id' => 'RFM_AKSES_OP_APP_JABODETABEK'),
                );
                $row_app_jabodetabek = $this->rfm_model->get_crud($array_crud)->row();
                $explode_app_jabodetabek = explode(':', $row_app_jabodetabek->value);
                foreach($explode_app_jabodetabek as $r) {
                    $rows = $r;
                    $data_app_jabodetabek[] = $rows;
                }
                
                $array_crud = array(
                    'table' => TB_PARAMETER,
                    'where' => array('id' => 'RFM_AKSES_OP_APP_BDG'),
                );
                $row_app_bdg = $this->rfm_model->get_crud($array_crud)->row();
                $explode_app_bdg = explode(':', $row_app_bdg->value);
                foreach($explode_app_bdg as $r) {
                    $rows = $r;
                    $data_app_bdg[] = $rows;
                }

                if(in_array($kode_cabang, $data_ft_area)){
                    $head_id_area = $row_app_bdg->value; //BANDUNG
                }else{
                    $head_id_area = $row_app_jabodetabek->value; //JABODETABEK
                }
                //===================================================

                if(in_array($SESSION_USER_ID, $data_app_jabodetabek) || in_array($SESSION_USER_ID, $data_app_bdg) || in_array($SESSION_USER_JABATAN, JABATAN_HEAD))
                {
                    $head_id = $app_it;
                }
                elseif(in_array($SESSION_USER_JABATAN, JABATAN_HC))
                {
                    $arr_hc_head = array('807','806','308');
                    if(in_array($SESSION_USER_ID, $arr_hc_head)) {
                        $head_id = APP_HC;
                    }else {
                        $head_id = '807';
                    }
                }
                else
                {
                    // HUMAN ERROR / KEBIJAKAN LAIN
                    $array_crud = array(
                        'table' => TB_PARAMETER,
                        'where' => array('id' => 'RFM_PROBLEM_TYPE'),
                    );
                    $row_prm_problem_type = $this->rfm_model->get_crud($array_crud)->row();
                    $explode_prm_problem_type = explode(':', $row_prm_problem_type->value);
                    foreach($explode_prm_problem_type as $r) {
                        $rows = $r;
                        $data_prm_problem_type[] = $rows;
                    }

                    // INSTALL, ERROR APLIKASI, NETWORK
                    $array_crud = array(
                        'table' => TB_PARAMETER,
                        'where' => array('id' => 'RFM_PROBLEM_TYPE_I'),
                    );
                    $row_prm_problem_type_i = $this->rfm_model->get_crud($array_crud)->row();
                    $explode_prm_problem_type_i = explode(',', $row_prm_problem_type_i->value);
                    foreach($explode_prm_problem_type_i as $r_i) {
                        $rows_i = $r_i;
                        $data_prm_problem_type_i[] = $rows_i;
                    }

                    // PROBLEM TYPE PRODUK KAMi
                    $array_crud = array(
                        'table' => TB_PARAMETER,
                        'where' => array('id' => 'RFM_PROBLEM_TYPE_KAMI'),
                    );
                    $row_problem_type_produk_kami = explode(",", $this->rfm_model->get_crud($array_crud)->row()->value);

                    // CABANG PRODUK KAMi
                    $array_crud = array(
                        'table' => TB_PARAMETER,
                        'where' => array('id' => 'RFM_CABANG_PRODUK_KAMI'),
                    );
                    $row_cabang_produk_kami = explode(",", $this->rfm_model->get_crud($array_crud)->row()->value);

                    if(in_array($problem_type, $data_prm_problem_type))
                    {   
                        if(!in_array($SESSION_USER_JABATAN, JABATAN_HEAD))
                        {
                            $head_id = $head_id_area;
                        }
                        else
                        {
                            $head_id = $head_id;
                        }
                    }
                    elseif(in_array($problem_type, $data_prm_problem_type_i))
                    {   
                        if(in_array($SESSION_USER_JABATAN, JABATAN_HEAD_SPV))
                        {
                            $head_id = $app_it;
                        }
                        else
                        {
                            $head_id = $head_id;
                        }
                    }
                    elseif(in_array($problem_type, $row_problem_type_produk_kami))
                    {
                        if(in_array($kode_cabang, $row_cabang_produk_kami))
                        {
                            $head_id = $row_app_bdg->value;
                        }
                        else
                        {
                            $head_id = $head_id;
                        }
                    }
                    else
                    {
                        if(in_array($SESSION_USER_INDUK, $data_app_jabodetabek) || in_array($SESSION_USER_INDUK, $data_app_bdg))
                        {
                            $head_id = $head_id_area;
                        }
                        else
                        {
                            $head_id = $head_id;
                        }
                    }
                }
            }
            else
            {
                if($SESSION_USER_JABATAN==="HEAD IT" || $SESSION_USER_DIVISI==='SUPERVISOR IT' || $SESSION_USER_DIVISI==='DIREKSI')
                {
                    $head_id = $app_it;
                }
                else
                {
                    // INSTALL, ERROR APLIKASI, NETWORK
                    $array_crud = array(
                        'table' => TB_PARAMETER,
                        'where' => array('id' => 'RFM_PROBLEM_TYPE_I'),
                    );
                    $row_prm_problem_type_i = $this->rfm_model->get_crud($array_crud)->row();
                    $explode_prm_problem_type_i = explode(',', $row_prm_problem_type_i->value);
                    foreach($explode_prm_problem_type_i as $r_i) {
                        $rows_i = $r_i;
                        $data_prm_problem_type_i[] = $rows_i;
                    }
                    
                    if(in_array($SESSION_USER_INDUK, $data_app_it) || in_array($SESSION_USER_INDUK, $data_app_it))
                    {
                        $head_id = $app_it;
                    }
                    elseif(in_array($problem_type, $data_prm_problem_type_i))
                    {   
                        if(in_array($SESSION_USER_JABATAN, JABATAN_HEAD_SPV))
                        {
                            $head_id = $app_it;
                        }
                        else
                        {
                            $head_id = $head_id;
                        }
                    }
                    else
                    {
                        $head_id = $head_id;
                    }
                }
            }
            
            //auto assign
            $array_crud = array(
                'table' => TB_PROBLEM_TYPE,
                'where' => array('id' => $problem_type),
            );
            $assign_to = $this->rfm_model->get_crud($array_crud)->row();

            // if($assign_to->user_id === 0) {
                $head_id = $head_id;
                $assign_to = (NULL);
                $assign_date = (NULL);
                $req_stt = STT_ON_QUEUE;
            // }else {
            //     $head_id = (NULL);
            //     $assign_to = $assign_to->user_id;
            //     $assign_date = $date_now;
            //     $req_stt = STT_ON_PROGRESS;
            // }
            
            if($SESSION_USER_ID==='945') { // UPLINE IKHLAS JEFRI
                $head_id = '807';
            }

            if($SESSION_USER_ID==='674') { // UPLINE DIMAS PURWITA
                $head_id = '207';
            }

            $array_insert = array(
                'no_rfm'            => $no_rfm,
                'problem_type'      => $problem_type,
                'request_type'      => $request_type,
                'request_by'        => $user_id,
                'request_date'      => $date_now,
                'request_upline_by' => $head_id,
                'kode_kantor'       => $kode_cabang,
                'subject'           => $subject,
                'rfm_detail'        => $detail,
                'request_status'    => $req_stt,
                'assign_to'         => $assign_to,
                'assign_date'       => $assign_date
            );
            // print_r($array_insert);die();

            if(empty($_FILES['attachment']['name'])) {
                $insert_data = $this->db->insert(TB_DETAIL, $array_insert);
            }else{
                $insert_data = $this->db->insert(TB_DETAIL, $array_insert);
                $no=1;
                foreach ($_FILES['attachment']['name'] as $key => $value) {
                    $name = $_FILES['attachment']['name'][$key];
                    $tmp = $_FILES['attachment']['tmp_name'][$key];
                    $size = $_FILES['attachment']['size'][$key];
                    $ext = explode(".", $name);
                    $extensi = end($ext);
                    $maxsize = 1024 * 5000;
                    $path = "upload/";

                    if($size>=$maxsize) {
                        $isValid = 0;
                        $isPesan = "<div class='alert alert-danger'>Attachment $name max 5mb</div>";
                                
                        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
                        echo json_encode($data);
                        die();
                    }elseif(!in_array($extensi, $extensionList)) {
                        $isValid = 0;
                        $isPesan = "<div class='alert alert-danger'>Format attachment tidak di izinkan</div>";
                                
                        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
                        echo json_encode($data);
                        die();
                    }else {
                        if(trim($name)!='') {
                            $explode_name = explode(".", $name);
                            $random_name = round(microtime(true)).'.'.end($explode_name);
                            $new_name = md5(date('YmdHis'))."-".$no++."-".$random_name;

                            $array_insert = array(
                                'rfm_id'        => $total,
                                'filename'      => $name,
                                'full_filename' => $new_name,
                                'data_file'     => "upload/$new_name"
                            );
                            $insert_attachment = $this->db->insert(TB_ATTACHMENT, $array_insert);

                            if($insert_attachment) {
                                move_uploaded_file($tmp, $path.''.$new_name);
                            }else {
                                $isValid = 0;
                                $isPesan = "<div class='alert alert-danger'>Attachment gagal terkirim, tidak Terhubung Database.</div>";
                                
                                $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
                                echo json_encode($data);
                                die();
                            }
                        }
                    }
                }
            }
            
            if(!$insert_data) {
                $isValid = 0;
                $isPesan = "<div class='alert alert-danger'>Format attachment tidak di izinkan</div>";
                        
                $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
                echo json_encode($data);
                die(); 
            }else {
                $exp = explode(':', $head_id);
                foreach($exp as $uid) {
                    $arr = array(
                        'user_id'     => $uid,
                        'waktu'       => $date_now,
                        'subject'     => $no_rfm.' On Queue(Waiting Approve)',
                        'pesan'       => $subject,
                        'via_android' => 1
                    );
                    $this->db->insert(TB_SYS_PESAN, $arr);
                }
                
                $isValid = 1;
                $isPesan = "<div class='alert alert-success'>Berhasil Membuat RFM</div>";
            }
            
        }

        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
        echo json_encode($data);
    }

    public function btn_case()
    {
        $id = $this->input->post('idx');
        $array_crud = array(
            'table' => TB_COMMENT,
            'where' => array(
                'id' => $id
            )
        );
        $sql = $this->rfm_model->get_crud($array_crud);
		$row = $sql->row();
		if($sql->num_rows() === 0) {
			$data['row'] = 'Tidak terdapat case penyelesaian';
		}else {
			$data['row'] = $row->comment;
		}

        $this->load->view('modal/modal_case', $data);
    }

    public function set_post_request()
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');
        $SESSION_USER_DIVISI = $this->session->userdata('USER_DIVISI');
        $SESSION_USER_JABATAN = $this->session->userdata('USER_JABATAN');
        $SESSION_USER_INDUK = $this->session->userdata('USER_INDUK');
        $date_now = date('Y-m-d h:i:s');
        $problem_type = $this->input->post('problem_type');
        $request_type = $this->input->post('request_type');
        $subject = $this->input->post('subject');
        $detail = $this->input->post('detail');
        $user_id = $this->input->post('user_id');
        $kode_cabang = $this->input->post('kode_cabang');
        $head_id = $this->input->post('head_id');
        $removeAtt = $this->input->post('removeAtt');
        $extensionList = array("jpg", "jpeg", "png", "bmp", "gif", "JPG", "JPEG", "PNG", "BMP", "GIF", "pdf", "docx", "xlsx", "pptx", "txt", "TXT");
        $id_rfm = $this->input->post('id_rfm');
        
        $array_crud = array(
            'table' => TB_PARAMETER,
            'where' => array('id' => 'RFM_AKSES_IT_APP'),
        );
        $app_it = $this->rfm_model->get_crud($array_crud)->row()->value;
        $explode_app_it = explode(':', $app_it);
        foreach($explode_app_it as $r) {
            $rows = $r;
            $data_app_it[] = $rows;
        }

        if(empty($problem_type)) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Problem Type Harus Diisi !!!</div>";
        }elseif(empty($request_type)) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Request Type Harus Diisi !!!</div>";
        }elseif(empty($subject)) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Subject Harus Diisi !!!</div>";
        }elseif(empty($detail)) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Detail Harus Diisi !!!</div>";
        }else {
            //auto assign
            // $array_crud = array(
            //     'table' => TB_PROBLEM_TYPE,
            //     'where' => array('id' => $problem_type),
            // );
            // $assign_to = $this->rfm_model->get_crud($array_crud)->row();

            // if($assign_to->user_id === 0) {
                $assign_to = (NULL);
                $assign_date = (NULL);
                $req_stt = STT_ON_QUEUE;
            // }else {
            //     $assign_to = $assign_to->user_id;
            //     $assign_date = $date_now;
            //     $req_stt = STT_ON_PROGRESS;
            // }
            if($SESSION_USER_JABATAN === 'DIREKSI')
            {
                $head_id = $app_it;
            }
            elseif($SESSION_USER_DIVISI === 'OPERASIONAL')
            {
                // FILTER AREA JABODETABEK DAN BANDUNG
                $array_crud = array(
                    'table' => TB_PARAMETER,
                    'where' => array('id' => 'RFM_KODE_AREA_BDG'),
                );
                $row_ft_area_value = $this->rfm_model->get_crud($array_crud)->row();
                $explode_ft_area_value = explode(':', $row_ft_area_value->value);
                foreach($explode_ft_area_value as $r) {
                    $rows = $r;
                    $data_ft_area[] = $rows;
                }
                
                $array_crud = array(
                    'table' => TB_PARAMETER,
                    'where' => array('id' => 'RFM_AKSES_OP_APP_JABODETABEK'),
                );
                $row_app_jabodetabek = $this->rfm_model->get_crud($array_crud)->row();
                $explode_app_jabodetabek = explode(':', $row_app_jabodetabek->value);
                foreach($explode_app_jabodetabek as $r) {
                    $rows = $r;
                    $data_app_jabodetabek[] = $rows;
                }
                
                $array_crud = array(
                    'table' => TB_PARAMETER,
                    'where' => array('id' => 'RFM_AKSES_OP_APP_BDG'),
                );
                $row_app_bdg = $this->rfm_model->get_crud($array_crud)->row();
                $explode_app_bdg = explode(':', $row_app_bdg->value);
                foreach($explode_app_bdg as $r) {
                    $rows = $r;
                    $data_app_bdg[] = $rows;
                }

                if(in_array($kode_cabang, $data_ft_area)){
                    $head_id_area = $row_app_bdg->value; //BANDUNG
                }else{
                    $head_id_area = $row_app_jabodetabek->value; //JABODETABEK
                }
                //===================================================

                if(in_array($SESSION_USER_ID, $data_app_jabodetabek) || in_array($SESSION_USER_ID, $data_app_bdg) || in_array($SESSION_USER_JABATAN, JABATAN_HEAD))
                {
                    $head_id = $app_it;
                }
                elseif(in_array($SESSION_USER_JABATAN, JABATAN_HC))
                {
                    $head_id = APP_HC;
                }
                else
                {
                    // HUMAN ERROR / KEBIJAKAN LAIN
                    $array_crud = array(
                        'table' => TB_PARAMETER,
                        'where' => array('id' => 'RFM_PROBLEM_TYPE'),
                    );
                    $row_prm_problem_type = $this->rfm_model->get_crud($array_crud)->row();
                    $explode_prm_problem_type = explode(':', $row_prm_problem_type->value);
                    foreach($explode_prm_problem_type as $r) {
                        $rows = $r;
                        $data_prm_problem_type[] = $rows;
                    }

                    // INSTALL, ERROR APLIKASI, NETWORK
                    $array_crud = array(
                        'table' => TB_PARAMETER,
                        'where' => array('id' => 'RFM_PROBLEM_TYPE_I'),
                    );
                    $row_prm_problem_type_i = $this->rfm_model->get_crud($array_crud)->row();
                    $explode_prm_problem_type_i = explode(',', $row_prm_problem_type_i->value);
                    foreach($explode_prm_problem_type_i as $r_i) {
                        $rows_i = $r_i;
                        $data_prm_problem_type_i[] = $rows_i;
                    }

                    // PROBLEM TYPE PRODUK KAMi
                    $array_crud = array(
                        'table' => TB_PARAMETER,
                        'where' => array('id' => 'RFM_PROBLEM_TYPE_KAMI'),
                    );
                    $row_problem_type_produk_kami = explode(",", $this->rfm_model->get_crud($array_crud)->row()->value);

                    // CABANG PRODUK KAMi
                    $array_crud = array(
                        'table' => TB_PARAMETER,
                        'where' => array('id' => 'RFM_CABANG_PRODUK_KAMI'),
                    );
                    $row_cabang_produk_kami = explode(",", $this->rfm_model->get_crud($array_crud)->row()->value);

                    if(in_array($problem_type, $data_prm_problem_type))
                    {   
                        if(!in_array($SESSION_USER_JABATAN, JABATAN_HEAD))
                        {
                            $head_id = $head_id_area;
                        }
                        else
                        {
                            $head_id = $head_id;
                        }
                    }
                    elseif(in_array($problem_type, $data_prm_problem_type_i))
                    {   
                        if(in_array($SESSION_USER_JABATAN, JABATAN_HEAD_SPV))
                        {
                            $head_id = $head_id_area;
                        }
                        else
                        {
                            $head_id = $head_id;
                        }
                    }
                    elseif(in_array($problem_type, $row_problem_type_produk_kami))
                    {
                        if(in_array($kode_cabang, $row_cabang_produk_kami))
                        {
                            $head_id = $row_app_bdg->value;
                        }
                        else
                        {
                            $head_id = $head_id;
                        }
                    }
                    else
                    {
                        if(in_array($SESSION_USER_INDUK, $data_app_jabodetabek) || in_array($SESSION_USER_INDUK, $data_app_bdg))
                        {
                            $head_id = $head_id_area;
                        }
                        else
                        {
                            $head_id = $head_id;
                        }
                    }
                }
            }
            else
            {
                if($SESSION_USER_JABATAN==="HEAD IT" || $SESSION_USER_DIVISI==='SUPERVISOR IT' || $SESSION_USER_DIVISI==='DIREKSI')
                {
                    $head_id = $app_it;
                }
                else
                {
                    // INSTALL, ERROR APLIKASI, NETWORK
                    $array_crud = array(
                        'table' => TB_PARAMETER,
                        'where' => array('id' => 'RFM_PROBLEM_TYPE_I'),
                    );
                    $row_prm_problem_type_i = $this->rfm_model->get_crud($array_crud)->row();
                    $explode_prm_problem_type_i = explode(',', $row_prm_problem_type_i->value);
                    foreach($explode_prm_problem_type_i as $r_i) {
                        $rows_i = $r_i;
                        $data_prm_problem_type_i[] = $rows_i;
                    }
                    
                    if(in_array($SESSION_USER_INDUK, $data_app_it) || in_array($SESSION_USER_INDUK, $data_app_it))
                    {
                        $head_id = $app_it;
                    }
                    elseif(in_array($problem_type, $data_prm_problem_type_i))
                    {   
                        if(in_array($SESSION_USER_JABATAN, JABATAN_HEAD_SPV))
                        {
                            $head_id = $app_it;
                        }
                        else
                        {
                            $head_id = $head_id;
                        }
                    }
                    else
                    {
                        $head_id = $head_id;
                    }
                }
            }
            
            // if($SESSION_USER_DIVISI === 'OPERASIONAL') {
            //     // FILTER AREA JABODETABEK DAN BANDUNG
            //     $array_crud = array(
            //         'table' => TB_PARAMETER,
            //         'where' => array('id' => 'RFM_KODE_AREA_BDG'),
            //     );
            //     $row_ft_area_value = $this->rfm_model->get_crud($array_crud)->row();
            //     $explode_ft_area_value = explode(':', $row_ft_area_value->value);
            //     foreach($explode_ft_area_value as $r) {
            //         $rows = $r;
            //         $data_ft_area[] = $rows;
            //     }
                
            //     $array_crud = array(
            //         'table' => TB_PARAMETER,
            //         'where' => array('id' => 'RFM_AKSES_OP_APP_JABODETABEK'),
            //     );
            //     $row_app_jabodetabek = $this->rfm_model->get_crud($array_crud)->row();
            //     $explode_app_jabodetabek = explode(':', $row_app_jabodetabek->value);
            //     foreach($explode_app_jabodetabek as $r) {
            //         $rows = $r;
            //         $data_app_jabodetabek[] = $rows;
            //     }
                
            //     $array_crud = array(
            //         'table' => TB_PARAMETER,
            //         'where' => array('id' => 'RFM_AKSES_OP_APP_BDG'),
            //     );
            //     $row_app_bdg = $this->rfm_model->get_crud($array_crud)->row();
            //     $explode_app_bdg = explode(':', $row_app_bdg->value);
            //     foreach($explode_app_bdg as $r) {
            //         $rows = $r;
            //         $data_app_bdg[] = $rows;
            //     }

            //     if(in_array($kode_cabang, $data_ft_area)){
            //         //BANDUNG
            //         $head_id_area = $row_app_bdg->value;
            //     }else{
            //         $head_id_area = $row_app_jabodetabek->value;
            //     }
            //     //===================================================

            //     if(in_array($SESSION_USER_ID, $data_app_jabodetabek) || in_array($SESSION_USER_ID, $data_app_bdg) || $SESSION_USER_JABATAN === "DEPARTMENT HEAD" || $SESSION_USER_JABATAN === "KEPALA KANTOR KAS" || $SESSION_USER_JABATAN === "PIMPINAN CABANG" || $SESSION_USER_JABATAN === "BUSINESS MANAGER" || $SESSION_USER_JABATAN === "DEPARTMENT HEAD HC" || $SESSION_USER_JABATAN === "AREA MANAGER" || $SESSION_USER_JABATAN === "UNIT HEAD HO")
            //     {
            //         $head_id = $app_it;
            //     }
            //     elseif($SESSION_USER_JABATAN === 'HC PERSONALIA' || $SESSION_USER_JABATAN === 'HC RECRUITMENT' || $SESSION_USER_JABATAN === 'HC TRAINING' || $SESSION_USER_JABATAN === 'COMPENSATION AND BENEFIT HEAD' || $SESSION_USER_JABATAN === 'SUPERVISOR HUMAN CAPITAL AREA')
            //     {
            //         $head_id = $head_id;
            //     }
            //     elseif($SESSION_USER_JABATAN === 'HEAD HUMAN CAPITAL')
            //     {
            //         $head_id = APP_HC;
            //     }
            //     else
            //     {
            //         $array_crud = array(
            //             'table' => TB_PARAMETER,
            //             'where' => array('id' => 'RFM_PROBLEM_TYPE'),
            //         );
            //         $row_prm_problem_type = $this->rfm_model->get_crud($array_crud)->row();
            //         $explode_prm_problem_type = explode(':', $row_prm_problem_type->value);
            //         foreach($explode_prm_problem_type as $r) {
            //             $rows = $r;
            //             $data_prm_problem_type[] = $rows;
            //         }

            //         if(in_array($problem_type, $data_prm_problem_type))
            //         {
            //             if($SESSION_USER_JABATAN === "TELLER" || $SESSION_USER_JABATAN === "ADMIN" || $SESSION_USER_JABATAN === "ADMIN 1 BEKASI" || $SESSION_USER_JABATAN === "ADMIN 3 BEKASI" || $SESSION_USER_JABATAN === "ADMIN 3 CIKAMPEK" || $SESSION_USER_JABATAN === "ADMIN CIKARANG" || $SESSION_USER_JABATAN === "ADMIN COLLECTION" || $SESSION_USER_JABATAN === "ADMIN CUSTODIAN" || $SESSION_USER_JABATAN === "ADMIN HEAD OFFICE" || $SESSION_USER_JABATAN === "ADMIN KREDIT" || $SESSION_USER_JABATAN === "ADMIN KREDIT BSD" || $SESSION_USER_JABATAN === "ADMIN3 KARAWANG" || $SESSION_USER_JABATAN === "ADMINBSD_PJS" || $SESSION_USER_JABATAN === "ADMINISTRATION" || $SESSION_USER_JABATAN === "KEPALA CABANG BSD" || $SESSION_USER_JABATAN === "KEPALA KANTOR KAS" || $SESSION_USER_JABATAN === "KEPALA KAS PAMULANG")
            //             {
            //                 $head_id = $head_id_area;
            //             }
            //             else
            //             {
            //                 $head_id = $head_id;
            //             }
            //         }
            //         else
            //         {
            //             if(in_array($SESSION_USER_INDUK, $data_app_jabodetabek) || in_array($SESSION_USER_INDUK, $data_app_bdg))
            //             {
            //                 $head_id = $head_id_area;
            //             }
            //             else
            //             {
            //                 $head_id = $head_id;
            //             }
            //         }
            //     }
            // }
            // else
            // {
            //     if($SESSION_USER_JABATAN=== "HEAD IT" || $SESSION_USER_DIVISI==='DIREKSI')
            //     {
            //         $head_id = $app_it;
            //     }
            //     else
            //     {
            //         if(in_array($SESSION_USER_INDUK, $data_app_it) || in_array($SESSION_USER_INDUK, $data_app_it))
            //         {
            //             $head_id = $app_it;
            //         }
            //         else
            //         {
            //             $head_id = $head_id;
            //         }
            //     }
            // }

            if(!empty($_FILES['attachment']['name'])) {
                $no=1;
                foreach ($_FILES['attachment']['name'] as $key => $value) {
                    $name = $_FILES['attachment']['name'][$key];
                    $tmp = $_FILES['attachment']['tmp_name'][$key];
                    $size = $_FILES['attachment']['size'][$key];
                    $ext = explode(".", $name);
                    $extensi = end($ext);
                    $maxsize = 1024 * 5000;
                    $path = "upload/";

                    if($size>=$maxsize) {
                        $isValid = 0;
                        $isPesan = "<div class='alert alert-danger'>Attachment $name max 5mb</div>";
                                
                        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
                        echo json_encode($data);
                        die();
                    }elseif(!in_array($extensi, $extensionList)) {
                        $isValid = 0;
                        $isPesan = "<div class='alert alert-danger'>Format attachment tidak di izinkan</div>";
                                
                        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
                        echo json_encode($data);
                        die();
                    }else {
                        if(trim($name)!='') {
                            $explode_name = explode(".", $name);
                            $random_name = round(microtime(true)).'.'.end($explode_name);
                            $new_name = md5(date('YmdHis'))."-".$no++."-".$random_name;

                            $array_insert = array(
                                'rfm_id'        => $id_rfm,
                                'filename'      => $name,
                                'full_filename' => $new_name,
                                'data_file'     => "upload/$new_name"
                            );
                            $insert_attachment = $this->db->insert(TB_ATTACHMENT, $array_insert);

                            if($insert_attachment) {
                                move_uploaded_file($tmp, $path.''.$new_name);
                            }else {
                                $isValid = 0;
                                $isPesan = "<div class='alert alert-danger'>Attachment gagal terkirim, tidak Terhubung Database.</div>";
                                
                                $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
                                echo json_encode($data);
                                die();
                            }
                        }
                    }
                }
            }
            
            if($removeAtt)
            {
                foreach($removeAtt as $keyRmv => $valRmv){
                    $idremove = $removeAtt[$keyRmv];
                    $this->db->where('id', $idremove);
                    $remove_name = $this->db->get(TB_ATTACHMENT)->row();
                    $dir = $remove_name->data_file;
                    
                    $this->db->where('id', $idremove);
                    $this->db->delete(TB_ATTACHMENT);
                    unlink($dir);
                }
            }

            $array_insert = array(
                'problem_type' => $problem_type,
                'request_type' => $request_type,
                'request_upline_by' => $head_id,
                'kode_kantor' => $kode_cabang,
                'subject' => $subject,
                'rfm_detail' => $detail,
                'request_status' => $req_stt,
                'assign_to' => $assign_to,
                'assign_date' => $assign_date
            );
            $this->db->where('id', $id_rfm);
            $insert_data = $this->db->update(TB_DETAIL, $array_insert);

            if(!$insert_data) {
                $isValid = 0;
                $isPesan = "<div class='alert alert-danger'>Gagal Membuat RFM</div>";
                        
                $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
                echo json_encode($data);
                die(); 
            }else {
                $isValid = 1;
                $isPesan = "<div class='alert alert-success'>Berhasil Membuat RFM</div>";
            }
            
        }

        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
        echo json_encode($data);
    }

    public function set_update_it()
    {
        $problem_type = $this->input->post('problem_type');
        $request_type = $this->input->post('request_type');

        $rfm_id = $this->input->post('id_rfm');
        $array_crud = array(
            'table' => TB_DETAIL,
            'where' => array('id' => $rfm_id),
        );
        $get_detail = $this->rfm_model->get_crud($array_crud)->row();
        
        $array_crud = array(
            'table' => TB_USER,
            'where' => array('user_id' => $get_detail->request_by),
        );
        $get_user = $this->rfm_model->get_crud($array_crud)->row();
        
        $array_crud = array(
            'table' => TB_PARAMETER,
            'where' => array('id' => 'RFM_AKSES_IT_APP'),
        );
        $app_it = $this->rfm_model->get_crud($array_crud)->row()->value;
        $explode_app_it = explode(':', $app_it);
        foreach($explode_app_it as $r) {
            $rows = $r;
            $data_app_it[] = $rows;
        }
        if($get_user->divisi_id === 'OPERASIONAL') {
            // FILTER AREA JABODETABEK DAN BANDUNG
            $array_crud = array(
                'table' => TB_PARAMETER,
                'where' => array('id' => 'RFM_KODE_AREA_BDG'),
            );
            $row_ft_area_value = $this->rfm_model->get_crud($array_crud)->row();
            $explode_ft_area_value = explode(':', $row_ft_area_value->value);
            foreach($explode_ft_area_value as $r) {
                $rows = $r;
                $data_ft_area[] = $rows;
            }
            
            $array_crud = array(
                'table' => TB_PARAMETER,
                'where' => array('id' => 'RFM_AKSES_OP_APP_JABODETABEK'),
            );
            $row_app_jabodetabek = $this->rfm_model->get_crud($array_crud)->row();
            $explode_app_jabodetabek = explode(':', $row_app_jabodetabek->value);
            foreach($explode_app_jabodetabek as $r) {
                $rows = $r;
                $data_app_jabodetabek[] = $rows;
            }
            
            $array_crud = array(
                'table' => TB_PARAMETER,
                'where' => array('id' => 'RFM_AKSES_OP_APP_BDG'),
            );
            $row_app_bdg = $this->rfm_model->get_crud($array_crud)->row();
            $explode_app_bdg = explode(':', $row_app_bdg->value);
            foreach($explode_app_bdg as $r) {
                $rows = $r;
                $data_app_bdg[] = $rows;
            }

            if(in_array($get_user->kd_cabang, $data_ft_area)){
                //BANDUNG
                $head_id_area = $row_app_bdg->value;
            }else{
                $head_id_area = $row_app_jabodetabek->value;
            }
            //===================================================

            $head_id = $get_user->user_id_induk;

            if(in_array($get_user->user_id, $data_app_jabodetabek) || in_array($get_user->user_id, $data_app_bdg) || $get_user->jabatan === "DEPARTMENT HEAD" || $get_user->jabatan === "KEPALA KANTOR KAS" || $get_user->jabatan === "PIMPINAN CABANG" || $get_user->jabatan === "BUSINESS MANAGER" || $get_user->jabatan === "DEPARTMENT HEAD HC" || $get_user->jabatan === "AREA MANAGER" || $get_user->jabatan === "UNIT HEAD HO")
            {
                $head_id = $app_it;
            }
            else
            {
                $array_crud = array(
                    'table' => TB_PARAMETER,
                    'where' => array('id' => 'RFM_PROBLEM_TYPE'),
                );
                $row_prm_problem_type = $this->rfm_model->get_crud($array_crud)->row();
                $explode_prm_problem_type = explode(':', $row_prm_problem_type->value);
                foreach($explode_prm_problem_type as $r) {
                    $rows = $r;
                    $data_prm_problem_type[] = $rows;
                }

                if(in_array($problem_type, $data_prm_problem_type))
                {
                    $head_id = $head_id_area;
                    $req_status = STT_ON_QUEUE;
                    $req_app_date = (NULL);
                    $req_app_notes = (NULL);
                    $req_app_by = (NULL);
                    $req_rec_by = (NULL);
                }
                else
                {
                    if(in_array($get_user->user_id_induk, $data_app_jabodetabek) || in_array($get_user->user_id_induk, $data_app_bdg))
                    {
                        $head_id = $head_id_area;
                        $req_status = $get_detail->request_status;
                        $req_app_date = $get_detail->approve_date;
                        $req_app_notes = $get_detail->approve_notes;
                        $req_app_by = $get_detail->approve_by;
                        $req_rec_by = $get_detail->receive_by;
                    }
                    else
                    {
                        $head_id = $head_id;
                        $req_status = $get_detail->request_status;
                        $req_app_date = $get_detail->approve_date;
                        $req_app_notes = $get_detail->approve_notes;
                        $req_app_by = $get_detail->approve_by;
                        $req_rec_by = $get_detail->receive_by;
                    }
                }
            }
        }
        else
        {
            if($get_user->jabatan=== "HEAD IT" || $get_user->divisi_id==='DIREKSI')
            {
                $head_id = $app_it;
                $req_status = $get_detail->request_status;
                $req_app_date = $get_detail->approve_date;
                $req_app_notes = $get_detail->approve_notes;
                $req_app_by = $get_detail->approve_by;
                $req_rec_by = $get_detail->receive_by;
            }
            else
            {
                if(in_array($get_user->user_id_induk, $data_app_it) || in_array($get_user->user_id_induk, $data_app_it))
                {
                    $head_id = $app_it;
                    $req_status = $get_detail->request_status;
                    $req_app_date = $get_detail->approve_date;
                    $req_app_notes = $get_detail->approve_notes;
                    $req_app_by = $get_detail->approve_by;
                    $req_rec_by = $get_detail->receive_by;
                }
                else
                {
                    $head_id = $head_id;
                    $req_status = $get_detail->request_status;
                    $req_app_date = $get_detail->approve_date;
                    $req_app_notes = $get_detail->approve_notes;
                    $req_app_by = $get_detail->approve_by;
                    $req_rec_by = $get_detail->receive_by;
                }
            }
        }
        $arrUpdate = array(
            'problem_type' => $problem_type,
            'request_type' => $request_type,
            'request_upline_by' => $head_id,
            'approve_by' => $req_app_by,
            'approve_date' => $req_app_date,
            'approve_notes' => $req_app_notes,
            'receive_by' => $req_rec_by,
            'request_status' => $req_status,
        );

        $this->db->where('id', $rfm_id);
        $exe = $this->db->update(TB_DETAIL, $arrUpdate);

        if(!$exe)
        {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>RFM Gagal di Simpan</div>";
        }
        else
        {
            $isValid = 1;
            $isPesan = "<div class='alert alert-success'>RFM Berhasil di Simpan</div>";
        }

        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
        echo json_encode($data);
    }

    public function set_app_request()
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');
        $SESSION_USER_FULLNAME = $this->session->userdata('USER_FULLNAME');
        $id_rfm = $this->input->post('id_rfm');
        $notes = $this->input->post('notes');
        $date_now = date('Y-m-d h:i:s');
        $app_it = $this->db->where('id', 'RFM_AKSES_IT_APP')->get(TB_PARAMETER)->row();
        
        $array_insert = array(
            'request_status' => STT_APPROVED,
            'approve_by'     => $SESSION_USER_ID,
            'approve_date'   => $date_now,
            'approve_notes'  => $notes,
            'receive_by'     => $app_it->value,
        );
        
        $insert_data = $this->db->where('id', $id_rfm)->update(TB_DETAIL, $array_insert);

        if(!$insert_data) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Gagal Menyetujui RFM</div>";
            
            $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
            echo json_encode($data);
            die(); 
        }else {
            $exp = explode(':', $app_it->value);
            foreach($exp as $uid) {
                $arr = array(
                    'user_id'     => $uid,
                    'waktu'       => $date_now,
                    'subject'     => 'RFM Approval(Waiting Assign To PIC)',
                    'pesan'       => $SESSION_USER_FULLNAME.' menyetujui dan mengatakan '.$notes,
                    'via_android' => 1
                );
                $this->db->insert(TB_SYS_PESAN, $arr);
            }

            $isValid = 1;
            $isPesan = "<div class='alert alert-success'>Berhasil Menyetujui RFM</div>";
        }

        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
        echo json_encode($data);
    }

    public function set_assign_request()
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');
        $id_rfm = $this->input->post('id_rfm');
        $problem_type = $this->input->post('problem_type');
        $request_type = $this->input->post('request_type');
        $notes = $this->input->post('notes');
        $assign_pic = $this->input->post('assign_pic');
        $target_date = $this->input->post('target_date');
        $date_now = date('Y-m-d h:i:s');

        if(empty($assign_pic) || empty($target_date))
        {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Pic Atau Tanggal Target Tidak Boleh Kosong</div>";
            
            $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
            echo json_encode($data);
            die(); 
        }
        
        $array_insert = array(
            'problem_type'   => $problem_type,
            'request_type'   => $request_type,
            'request_status' => STT_ON_PROGRESS,
            'receive_by'     => $SESSION_USER_ID,
            'receive_date'   => $date_now,
            'receive_notes'  => $notes,
            'assign_to'      => $assign_pic,
            'assign_date'    => $date_now,
            'target_date'    => $target_date,
        );
        $insert_data = $this->db->where('id', $id_rfm)->update(TB_DETAIL, $array_insert);

        if(!$insert_data) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Gagal Menyetujui RFM</div>";
            
            $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
            echo json_encode($data);
            die(); 
        }else {
            $target_date = date('d-m-Y', strtotime($target_date));
            $arr = array(
                'user_id'     => $assign_pic,
                'waktu'       => $date_now,
                'subject'     => 'RFM On Progress',
                'pesan'       => "Kerjakan case tersebut sebelum $target_date",
                'via_android' => 1
            );
            $this->db->insert(TB_SYS_PESAN, $arr);

            $isValid = 1;
            $isPesan = "<div class='alert alert-success'>Berhasil Menyetujui RFM</div>";
        }

        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
        echo json_encode($data);
    }

    public function set_done_request()
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');
        $id_rfm = $this->input->post('id_rfm');
        $notes = $this->input->post('notes');
        $penyelesaian = $this->input->post('penyelesaian');
        $date_now = date('Y-m-d h:i:s');

        if(empty($penyelesaian)) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Notes penyelesaian tidak boleh kosong</div>";
            
            $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
            echo json_encode($data);
            die(); 
        }
        
        $array_insert = array(
            'request_status' => STT_DONE,
            'done_date'   => $date_now,
            'done_notes'  => $notes,
        );
        $insert_data = $this->db->where('id', $id_rfm)->update(TB_DETAIL, $array_insert);
        
        $array_insert = array(
            'id' => $id_rfm,
            'user' => $SESSION_USER_ID,
            'date' => $date_now,
            'comment' => $penyelesaian
        );
        $this->db->insert(TB_COMMENT, $array_insert);

        if(!$insert_data) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Gagal Menyelesaikan RFM</div>";
            
            $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
            echo json_encode($data);
            die(); 
        }else {
            $isValid = 1;
            $isPesan = "<div class='alert alert-success'>Berhasil Menyelesaikan RFM</div>";
        }

        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
        echo json_encode($data);
    }

    public function set_reject_request()
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');
        $id_rfm = $this->input->post('id_rfm');
        $notes = $this->input->post('notes');
        $date_now = date('Y-m-d h:i:s');
        
        $array_insert = array(
            'request_status' => STT_REJECT,
            'result_status' => STT_REJECT,
            'reject_date'   => $date_now,
            'reject_by'  => $SESSION_USER_ID,
            'reject_notes'   => $notes,
        );
        $insert_data = $this->db->where('id', $id_rfm)->update(TB_DETAIL, $array_insert);

        if(!$insert_data) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Gagal Mereject RFM</div>";
            
            $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
            echo json_encode($data);
            die(); 
        }else {
            $isValid = 1;
            $isPesan = "<div class='alert alert-success'>Berhasil Mereject RFM</div>";
        }

        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
        echo json_encode($data);
    }

    public function set_rating_request()
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');
        $id_rfm = $this->input->post('id_rfm');
        $notes = $this->input->post('notes');
        $rates = $this->input->post('rates');
        $date_now = date('Y-m-d h:i:s');

        if(empty($rates) || empty($notes)) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Notes Atau Bintang Harus Diisi</div>";
            
            $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
            echo json_encode($data);
            die(); 
        }
        
        $array_insert = array(
            'result_status' => STT_SOLVED,
            'confirm_by' => $SESSION_USER_ID,
            'confirm_date'   => $date_now,
            'confirm_notes'  => $notes,
            'rates'   => $rates,
        );
        $insert_data = $this->db->where('id', $id_rfm)->update(TB_DETAIL, $array_insert);

        if(!$insert_data) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Gagal Menyelesaikan RFM</div>";
            
            $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
            echo json_encode($data);
            die(); 
        }else {
            $isValid = 1;
            $isPesan = "<div class='alert alert-success'>Berhasil Menyelesaikan RFM</div>";
        }

        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
        echo json_encode($data);
    }

    public function bell()
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');

        $this->db->where('id', 'RFM_RFP_ID');
        $row_rfp = $this->db->get(TB_PARAMETER)->row()->value;
        $rfp_id = explode(":", $row_rfp);
        array_pop($rfp_id);
        $rfp_id = implode(",",$rfp_id);

        $array_crud = array(
            'table' => TB_PARAMETER,
            'where' => array('id' => 'RFM_AKSES_OP_APP_BDG')
        );
        $area_bandung = $this->rfm_model->get_crud($array_crud)->row();
        
        $array_crud = array(
            'table' => TB_PARAMETER,
            'where' => array('id' => 'RFM_AKSES_OP_APP_JABODETABEK')
        );
        $area_jabodetabek = $this->rfm_model->get_crud($array_crud)->row();
        
        $array_crud = array(
            'table' => TB_PARAMETER,
            'where' => array('id' => 'RFM_AKSES_IT_APP')
        );
        $area_it = $this->rfm_model->get_crud($array_crud)->row();
        
        $ex_id_bandung = explode(":", $area_bandung->value);
        $ex_id_jabodetabek = explode(":", $area_jabodetabek->value);
        $ex_id_it = explode(":", $area_it->value);

        $SESSION_UPLINE = $SESSION_USER_ID;

        if(in_array($SESSION_USER_ID, $ex_id_bandung))
        {
            $SESSION_UPLINE = $area_bandung->value;
        }

        if(in_array($SESSION_USER_ID, $ex_id_jabodetabek))
        {
            $SESSION_UPLINE = $area_jabodetabek->value;
        }

        if(in_array($SESSION_USER_ID, $ex_id_it))
        {
            $SESSION_UPLINE = $area_it->value;
        }

        $array_crud = array(
            'select' => 'count(*) as total',
            'table' => TB_DETAIL,
            'where' => array(
                    'request_upline_by' => $SESSION_UPLINE,
                    'request_status' => STT_ON_QUEUE,
                    'approve_by' => NULL,
                    'receive_by' => NULL,
                    'assign_to' => NULL,
                    "problem_type NOT IN($rfp_id)" => NULL,
                )
        );
        $upline = $this->rfm_model->get_crud($array_crud)->row()->total;

        $array_crud = array(
            'select' => 'count(*) as total',
            'table' => TB_DETAIL,
            'where' => array(
                    'request_upline_by !=' => NULL,
                    'request_status' => STT_APPROVED,
                    'approve_by !=' => NULL,
                    'receive_by' => $SESSION_UPLINE,
                    'assign_to' => NULL,
                    "problem_type NOT IN($rfp_id)" => NULL,
                )
        );
        $approve = $this->rfm_model->get_crud($array_crud)->row()->total;

        $array_crud = array(
            'select' => 'count(*) as total',
            'table' => TB_DETAIL,
            'where' => array(
                    'request_upline_by !=' => NULL,
                    'request_status' => STT_ON_PROGRESS,
                    'approve_by !=' => NULL,
                    'receive_by !=' => NULL,
                    'assign_to' => $SESSION_USER_ID,
                    "problem_type NOT IN($rfp_id)" => NULL,
                )
        );
        $assign = $this->rfm_model->get_crud($array_crud)->row()->total;

        $array_crud = array(
            'select' => 'count(*) as total',
            'table' => TB_DETAIL,
            'where' => array(
                    'request_upline_by' => NULL,
                    'request_status' => STT_ON_PROGRESS,
                    'approve_by' => NULL,
                    'receive_by' => NULL,
                    'assign_to' => $SESSION_USER_ID,
                    "problem_type NOT IN($rfp_id)" => NULL,
                )
        );
        $auto_assign = $this->rfm_model->get_crud($array_crud)->row()->total;

        echo $upline + $approve + $assign + $auto_assign;
    }

    public function export_to_excel($month='', $year='')
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');
        if(!$SESSION_USER_ID)
        {
            redirect(base_url());
        }
        if(empty($month)) $month = "MONTH(CURDATE())";

        $Q = "SELECT 
                no_rfm AS `no_rfm`,
                e.nama AS `pic`,
                d.nama AS `request_by`,
                b.`problem_type` AS `problem_type`,
                c.`request_type` AS `request_type`,
                `subject` AS `subject`,
                rfm_detail AS `detail`,
                request_status AS `status`,
                request_date  AS `date`
            FROM
                rfm_new_detail a 
                LEFT JOIN rfm_new_problem_type b 
                ON a.problem_type = b.id
                LEFT JOIN rfm_new_request_type c
                ON a.request_type = c.id
                LEFT JOIN `user` d
                ON a.request_by = d.user_id
                LEFT JOIN `user` e
                ON a.assign_to = e.user_id
            WHERE
                MONTH(a.request_date)='$month' AND
                YEAR(a.request_date)='$year'
                -- AND b.system_type IS NULL
            -- GROUP BY a.problem_type
        ";
        $data['row'] = $this->db->query($Q)->result();
        $this->load->view('export_to_excel', $data);
    }

}