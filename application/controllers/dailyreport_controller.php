<?php
class dailyreport_controller extends ci_controller{
    
    function __construct() {
        parent::__construct();
		$this->load->model('daily_report_model');
        $this->load->model('auth_model');
        $this->load->model('rfm_model');
        $this->load->database('ticket_support'. TRUE);
    }
    
    function index()
    {
        if($this->auth_model->logged_id()) {
            $data['SESSION_USER_ID'] = $this->session->userdata('USER_ID');
            $data['daily_activities'] = $this->getDailyActivity();

            // Validasi daftar rfm, bisa dimasukkan ke $array_crud
            $array_crud = array(
                'select' => '*',
                'table' => TB_DETAIL,
                // 'where' => array(
                //     'assign_to' => $this->session->userdata('USER_ID')
                // )
            );

            $data['rfmList'] = $this->rfm_model->get_crud($array_crud);
			
			$array_crud = array(
                'select' => '*',
                'table' => TB_PROJECT
            );

            $data['projectList'] = $this->daily_report_model->get_crud($array_crud);
			
            $this->template->load('template','daily_report/table', $data);
        }else {
            $this->load->view('login/form_login');
        }
    }
	
	public function getDailyActivity()
    {
        
        $array_crud = array(
            'table' => TB_DAILY_ACTIVITY,
            'where' => array(
            'user_id' => $this->session->userdata('USER_FULLNAME')
            ),
            'order_by' => "tanggal DESC"
        );
        return $this->daily_report_model->get_crud($array_crud);
    }
	
	public function post_request_dr()
    {
        if(!$this->auth_model->logged_id())
        {
            $data = array('isValid' => 0, 'isPesan' => '<div class="alert alert-danger">Sesi telah berakhir, silahkan segarkan halaman ini terlebih dahulu. <a href="./">Segarkan</a></div>');
            echo json_encode($data);
            die();
        }
		
        $date_now = date('Y-m-d');
        $user_id = $this->session->userdata('USER_ID');
        $project_id = $this->input->post('project_id');
        $task_id = $this->input->post('task_id');
        $rfm_id = $this->input->post('rfm_id');
        $status = $this->input->post('status');
        $keterangan = $this->input->post('keterangan');
        
        if(empty($project_id) && empty($rfm_id) && empty($keterangan) ) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Task Harus Diisi !!!</div>";
        }elseif(empty($status)) {
            $isValid = 0;
            $isPesan = "<div class='alert alert-danger'>Status Pekerjaan Harus Diisi !!!</div>";
        }else{

            $array_crud = array(
                'table' => TB_DAILY_ACTIVITY,
                'where' => array(
                    'user_id' => $this->session->userdata('USER_ID'),
                    'tanggal' => $date_now,
                )
            );

            $sql = $this->daily_report_model->get_crud($array_crud);
            
            if($sql->num_rows() !== 0) {
                $isValid = 0;
                $isPesan = "<div class='alert alert-danger'>GAGAL !!! Anda telah menambahkan daily activity di jam ini</div>";
                        
                $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
                echo json_encode($data);
                die();
            }

            if (!empty($project_id)) {
                $rfm_id = null;
            } elseif(!empty($rfm_id)) {
                $project_id = null;
            }

            $array_insert = array(
                'user_id'       => $user_id,
                'tanggal'      	=> $date_now,
                'project_id'    => $project_id,
                'task_id'       => $task_id,
                'rfm_id'        => $rfm_id,
                'status'        => $status,
                'keterangan' 	=> $keterangan,
            );
        
            $db2 = $this->load->database('ticket_support', TRUE);
	    	$insert_data =$db2->insert(TB_DAILY_ACTIVITY, $array_insert);
            
            
            if(!$insert_data) {
                $isValid = 0;
                $isPesan = "<div class='alert alert-danger'>Gagal menambahkan daily activity</div>";
                        
                $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
                echo json_encode($data);
                die(); 
            }else {
                $isValid = 1;
                $isPesan = "<div class='alert alert-success'>Berhasil menambahkan daily activity</div>";
            }
            
        }

        $data = array('isValid' => $isValid, 'isPesan' => $isPesan);
        echo json_encode($data);
    }

    // function status_enum ($table, $field)
    // {
    //     $query = "SHOW COLUMNS FROM" .$table. "LIKE '$field'";
    //     $row = $this->query("SHOW COLUMNS FROM ". $table. "LIKE $field'")->row()->Type;
    //     $regex = "/'(.*?)'/";
    //     preg_match_all($regex, $row, $enum_array);
    //     $enum_fields = $enum_array[1];
    //     foreach($enum_fields as $key=>$value)
    //     {
    //         $enums[$value]=$value;
    //     }
    //     return $enums;
    // }

    

}