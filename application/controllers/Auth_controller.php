<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_controller extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('auth_model');
    }

	public function index()
	{	
        // $this->template->load('template','rfm/table');
        if(!$this->auth_model->logged_id()) {
            base_url();
        }
    }

    public function login()
    {
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));

        $check = $this->auth_model->check_login(array('user' => $username), array('password' => $password));

        if($check != FALSE) {
            $r = $check;
			if($r->flg_block == 'Y') {
				$data = array('isValid'=>1, 'isPesan'=>'Akun di blok');
				echo json_encode($data);
			}else {
				$session_data = array(
					'USER_ID' => $r->user_id,
					'USER_INDUK' => $r->user_id_induk,
					'USER_NAME' => $r->user,
					'USER_FULLNAME' => $r->nama,
					'USER_JABATAN' => $r->jabatan,
					'USER_DIVISI' => $r->divisi_id,
					'USER_KODE_CABANG' => $r->kd_cabang,
					'USER_GROUP_MENU' => $r->group_menu,
				);
				$this->session->set_userdata($session_data);
				$data = array('isValid'=>0);
				echo json_encode($data);
			}
        }else {
            $data = array('isValid'=>1, 'isPesan'=>'Username atau password salah');
            echo json_encode($data);
        }
    }

    public function logout()
	{
		$this->session->sess_destroy();
		redirect();
	}

}