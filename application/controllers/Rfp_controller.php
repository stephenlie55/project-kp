<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfp_controller extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->model('rfm_model');
        $this->load->model('rfp_model');
    }

	public function index()
	{
        if($this->auth_model->logged_id()) {
            $data['SESSION_USER_ID'] = $this->session->userdata('USER_ID');
            $this->template->load('template','rfp/table',$data);
        }else {
            $this->load->view('login/form_login');
        }
    }
    
    function get_tb_detail()
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');

        $this->db->where('id', 'RFM_AKSES_OP_APP_BDG');
        $area_bandung = $this->db->get(TB_PARAMETER)->row();
        
        $this->db->where('id', 'RFM_AKSES_OP_APP_JABODETABEK');
        $area_jabodetabek = $this->db->get(TB_PARAMETER)->row();
        
        $this->db->where('id', 'RFM_AKSES_IT_APP');
        $area_it = $this->db->get(TB_PARAMETER)->row();
        
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
        
        $list = $this->rfp_model->get_datatables($SESSION_UPLINE);
        $data = array();
        $no = $_POST['start'];
        
        foreach ($list as $field) {

            // problem type
            $this->db->where('id', $field->problem_type);
            $row_problem_type = $this->db->get(TB_PROBLEM_TYPE)->row()->problem_type;

            // request status
            $this->db->where('id', $field->request_type);
            $row_request_type = $this->db->get(TB_REQUEST_TYPE)->row()->request_type;

            // nama pic
            if($field->assign_to == NULL) {
                $row_assign_to = '-';
            }else {
                $this->db->where('user_id', $field->assign_to);
                $row_assign_to = $this->db->get(TB_USER)->row()->nama;
            }

            // btn approve sesuai status
            $btn_option = "<a class='btn btn-primary text-light btn-sm btn-block' href='javascript:void(0)' data-toggle='modal' data-target='#modal-approve-rfm' data-id='$field->id' title='Approve RFP'><i class='fa fa-spell-check'></i></a>";
            
            $explode_request_upline_by = explode(':', $field->request_upline_by);
            $explode_receive_by = explode(':', $field->receive_by);
            
            if(in_array($SESSION_USER_ID, $explode_request_upline_by) AND $field->request_status == STT_ON_QUEUE) {
                $btn_option = $btn_option;
            }elseif(in_array($SESSION_USER_ID, $explode_receive_by) AND $field->request_status == STT_APPROVED) {
                $btn_option = $btn_option;
            }elseif($field->assign_to == $SESSION_USER_ID AND $field->request_status == STT_ON_PROGRESS) {
                $btn_option = $btn_option;
            }else {
                $btn_option = "-";
            }

            // nama yg harus approve
            $this->db->where('id', 'RFM_AKSES_IT_APP');
            $team_it = $this->db->get(TB_PARAMETER)->row()->value;

            if($field->request_upline_by != NULL AND $field->request_status == STT_ON_QUEUE) {
                if($field->request_upline_by == $team_it)
                {
                    $app_by = "IT";
                }
                else
                {
                    $this->db->where('user_id', $field->request_upline_by);
                    $app_by = $this->db->get(TB_USER)->row()->nama;
                }
            }else {
                $app_by = 'IT';
            }
            
            // btn edit di status on queue
            $btn_rating = "<a class='btn btn-success text-warning btn-sm btn-block' href='javascript:void(0)' data-toggle='modal' data-target='#modal-rating-rfm' data-id='$field->id' title='Give Rating'><i class='fa fa-star'></i></a>";
            if($field->request_by == $SESSION_USER_ID AND $field->request_status == STT_DONE AND $field->result_status == STT_PENDING) {
                $btn_option = $btn_rating;
            }

            // btn edit di status on queue
            $btn_edit = "<a class='btn btn-warning text-light btn-sm btn-block' href='javascript:void(0)' data-toggle='modal' data-target='#modal-edit-rfm' data-id='$field->id' title='Edit RFP'><i class='fa fa-edit'></i></a>";
            if($field->request_by == $SESSION_USER_ID AND $field->request_status == STT_ON_QUEUE) {
                $btn_option = $btn_edit;
            }

            //txt color
            if($field->request_status == STT_ON_QUEUE)
            {
                $txtApprove = "<b class='text-warning'>$field->request_status</b>";
            }
            elseif($field->request_status == STT_VALIDATED)
            {
                $txtApprove = "<b class='text-secondary'>$field->request_status</b>";
            }
            elseif($field->request_status == STT_APPROVED)
            {
                $txtApprove = "<b class='text-primary'>$field->request_status</b>";
            }
            elseif($field->request_status == STT_ON_PROGRESS)
            {
                $txtApprove = "<b class='text-dark'>$field->request_status</b>";
            }
            elseif($field->request_status == STT_DONE)
            {
                $txtApprove = "<b class='text-success'>$field->request_status</b>";
            }
            elseif($field->request_status == STT_REJECT)
            {
                $txtApprove = "<b class='text-danger'>$field->request_status</b>";
            }
            else
            {
                $txtApprove = "$field->request_status";
            }

            //icon rating
            if($field->rates == 1)
            {
                $rates = "<i class='fa fa-star text-warning'></i>";
            }
            elseif($field->rates == 2)
            {
                $rates = "<i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i>";
            }
            elseif($field->rates == 3)
            {
                $rates = "<i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i>";
            }
            elseif($field->rates == 4)
            {
                $rates = "<i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i>";
            }
            elseif($field->rates == 5)
            {
                $rates = "<i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i> <i class='fa fa-star text-warning'></i>";
            }
            else
            {
                $rates = "-";
            }

            if($field->approve_notes != NULL)
            {
                $notes_approve = $field->approve_notes;
            }
            else
            {
                $notes_approve = "-";
            }

            if($field->receive_notes != NULL)
            {
                $notes_receive = $field->receive_notes;
            }
            else
            {
                $notes_receive = "-";
            }

            if($field->done_notes != NULL)
            {
                $notes_done = $field->done_notes;
            }
            else
            {
                $notes_done = "-";
            }

            if($field->reject_notes != NULL)
            {
                $notes_reject = $field->reject_notes;
            }
            else
            {
                $notes_reject = "-";
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
            $row[] = $btn_option;
            $row[] = $row_problem_type;
            $row[] = $row_request_type;
            $row[] = $field->subject;
            $row[] = $field->rfm_detail;
            $row[] = $rates;
            $row[] = $notes_approve;
            $row[] = $notes_receive;
            $row[] = $notes_done;
            $row[] = $notes_reject;
            $row[] = $field->jabatan;
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->rfp_model->count_all(),
            "recordsFiltered" => $this->rfp_model->count_filtered($SESSION_UPLINE),
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
                'where' => array('system_type' => 'RFP'),
            );
            $data['problem_type'] = $this->rfm_model->get_crud($array_crud);
            
            $array_crud = array(
                'table' => TB_REQUEST_TYPE,
            );
            $data['request_type'] = $this->rfm_model->get_crud($array_crud);
            $this->load->view('rfp/form_create', $data);
        }
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
                    "problem_type IN($rfp_id)" => NULL,
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
                    "problem_type IN($rfp_id)" => NULL,
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
                    "problem_type IN($rfp_id)" => NULL,
                )
        );
        $assign = $this->rfm_model->get_crud($array_crud)->row()->total;

        echo $upline + $approve + $assign;
    }

}