<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_controller extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('rfm_model');
        $this->load->model('auth_model');
    }

	public function index()
	{
        $tb_detail = TB_DETAIL;
        if($this->auth_model->logged_id()) {
            $data['SESSION_USER_ID'] = $this->session->userdata('USER_ID');

            $query = "  SELECT 
            TGL,
            SUM(APPROVE) AS APPROVE,
            sum(PROGRESS) AS PROGRESS,
            SUM(DONE) AS DONE
          FROM
          (
          select
            approve_date AS TGL,
            COUNT(approve_date) AS APPROVE,
            0 AS PROGRESS,
            0 as DONE
          FROM
            rfm_new_detail 
            WHERE MONTH(approve_date) IN ('01','02','03','04','05','06','07','08','09','10','11','12')
            AND YEAR(done_date) = YEAR(CURDATE())
            and problem_type NOT IN('30','31','35','77','78')
            AND request_status NOT IN ('REJECT')
            group by month(approve_date)
          UNION ALL
          
          
          SELECT 
            assign_date AS TGL,
            0 AS APPROVE,
            COUNT(assign_date) AS PROGRESS,
            0 AS DONE
          FROM
            rfm_new_detail 
            WHERE MONTH(assign_date) IN ('01','02','03','04','05','06','07','08','09','10','11','12')
            AND YEAR(done_date) = YEAR(CURDATE())
            AND problem_type NOT IN('30','31','35','77','78')
            AND request_status NOT IN ('REJECT')
            GROUP BY MONTH(assign_date)
            
            
            
          UNION ALL
          
          
          
          SELECT 
            done_date AS TGL,
            0 AS APPROVE,
            0 as PROGRESS,
            COUNT(done_date) AS DONE
          FROM
            rfm_new_detail 
            WHERE MONTH(done_date) IN ('01','02','03','04','05','06','07','08','09','10','11','12')
            AND YEAR(done_date) = YEAR(CURDATE())
            AND problem_type NOT IN('30','31','35','77','78')
            AND request_status NOT IN('REJECT')
            GROUP BY MONTH(done_date)
          ) rfm
          group by month(TGL)
            "; 
            $data['rDone'] = $this->db->query($query)->row();

            $this->template->load('template','report/home',$data);
        }else {
            $this->load->view('login/form_login');
        }
    }

}