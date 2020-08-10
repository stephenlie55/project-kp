<?php
class Um_controller extends ci_controller{
    
    function __construct() {
        parent::__construct();
        $this->load->model('auth_model');
    }
    
    function index()
    {
        $this->template->load('template','user_management/table');
    }

    function get_data_tip()
    {
        $list = $this->auth_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nama;
            $row[] = $field->user;
            $row[] = $field->divisi_id;
            $row[] = $field->jabatan;
            $row[] = "
                <a class='btn btn-primary text-light btn-sm btn-block' href='javascript:void(0)' data-toggle='modal' data-target='#form_add_akses' data-id='$field->user_id' title='Ubah Hak Akses'><i class='fa fa-cog'></i></a>
            ";
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->auth_model->count_all(),
            "recordsFiltered" => $this->auth_model->count_filtered(),
            "data" => $data,
        );
        
        echo json_encode($output);
    }

    function get_akses()
    {
        $idx = $this->input->post('idx');
        $model_akses = $this->auth_model->my_akses();

        $this->db->where('user_id', $idx);
        $data['user_fullname'] = $this->db->get(TB_USER)->row()->nama;
        
        
        $data['idx'] = $idx;
        $data['my_akses'] = $model_akses;
        
        $this->load->view('user_management/form_add_akses', $data);
    }

    function new_akses()
    {
        $user = $this->input->post('user');
        $app_it = $this->input->post('RFM_AKSES_IT_APP');
        $app_op_bdg = $this->input->post('RFM_AKSES_OP_APP_BDG');
        $app_op_jabodetabek = $this->input->post('RFM_AKSES_OP_APP_JABODETABEK');
        $user_management = $this->input->post('RFM_AKSES_USER_MANAGEMENT');
        
        $data_app_it = array('value'=>$app_it);
        $this->db->where('id', 'RFM_AKSES_IT_APP');
        $cek_update = $this->db->update(TB_PARAMETER, $data_app_it);
        if(!$cek_update) {
            $data = array('isValid' => 0, 'isPesan' => 'Hak Akses APPROVAL IT Gagal Tersimpan');
            echo json_encode($data);
            die();
        }
        
        $data_app_op_bdg = array('value'=>$app_op_bdg);
        $this->db->where('id', 'RFM_AKSES_OP_APP_BDG');
        $cek_update = $this->db->update(TB_PARAMETER, $data_app_op_bdg);
        if(!$cek_update) {
            $data = array('isValid' => 0, 'isPesan' => 'Hak Akses APPROVAL AREA BANDUNG Gagal Tersimpan');
            echo json_encode($data);
            die();
        }
        
        $data_app_op_jabodetabek = array('value'=>$app_op_jabodetabek);
        $this->db->where('id', 'RFM_AKSES_OP_APP_JABODETABEK');
        $cek_update = $this->db->update(TB_PARAMETER, $data_app_op_jabodetabek);
        if(!$cek_update) {
            $data = array('isValid' => 0, 'isPesan' => 'Hak Akses APPROVAL AREA JABODETABEK Gagal Tersimpan');
            echo json_encode($data);
            die();
        }

        $data_user_management = array('value'=>$user_management);
        $this->db->where('id', 'RFM_AKSES_USER_MANAGEMENT');
        $cek_update = $this->db->update(TB_PARAMETER, $data_user_management);
        if(!$cek_update) {
            $data = array('isValid' => 0, 'isPesan' => 'Hak Akses MENU USER MANAGEMENT Gagal Tersimpan');
            echo json_encode($data);
            die();
        }

        $data = array('isValid' => 1, 'isPesan' => 'Hak akses berhasil di simpan');
        echo json_encode($data);
    }

    function kpi_pic()
    {
        $this->load->model('rfm_model');
        $arr = array(
            'table' => TB_USER,
            'where' => array(
                'divisi_id' => 'IT',
                'flg_block' => 'N',
                'user_id !=' => '68'
            ),
        );
        $data['result'] = $this->rfm_model->get_crud($arr)->result();
        $this->template->load('template','user_management/table_kpi',$data);
    }
}