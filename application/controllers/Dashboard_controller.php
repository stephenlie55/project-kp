<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_controller extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('auth_model');
    }

	public function index()
	{
        if($this->auth_model->logged_id()) {
            $SESSION_USER_ID = $this->session->userdata('USER_ID');
            $data['SESSION_USER_ID'] = $SESSION_USER_ID;

            $this->db->where('id', 'RFM_RFP_ID');
            $row_rfp = $this->db->get(TB_PARAMETER)->row()->value;
            $rfp_id = explode(":", $row_rfp);
            array_pop($rfp_id);
            $rfp_id = implode(",",$rfp_id);
    
            $this->db->select("COUNT(*) AS jml_rfm");
            $this->db->where("problem_type NOT IN($rfp_id)", NULL, FALSE);
            $data['jumlah_rfm'] = $this->db->get(TB_DETAIL)->row()->jml_rfm;
    
            $this->db->select("COUNT(*) AS jml_queue");
            $this->db->where('request_status', STT_ON_QUEUE);
            $this->db->where("problem_type NOT IN($rfp_id)", NULL, FALSE);
            $data['jumlah_queue'] = $this->db->get(TB_DETAIL)->row()->jml_queue;
    
            $this->db->select("COUNT(*) AS jml_approve");
            $this->db->where('request_status', STT_APPROVED);
            $this->db->where("problem_type NOT IN($rfp_id)", NULL, FALSE);
            $data['jumlah_approve'] = $this->db->get(TB_DETAIL)->row()->jml_approve;
    
            $this->db->select("COUNT(*) AS jml_progress");
            $this->db->where('request_status', STT_ON_PROGRESS);
            $this->db->where("problem_type NOT IN($rfp_id)", NULL, FALSE);
            $data['jumlah_progress'] = $this->db->get(TB_DETAIL)->row()->jml_progress;
    
            $this->db->select("COUNT(*) AS jml_done");
            $this->db->where('request_status', STT_DONE);
            $this->db->where("problem_type NOT IN($rfp_id)", NULL, FALSE);
            $data['jumlah_done'] = $this->db->get(TB_DETAIL)->row()->jml_done;
    
            $this->db->select("COUNT(*) AS jml_reject");
            $this->db->where('request_status', STT_REJECT);
            $this->db->where("problem_type NOT IN($rfp_id)", NULL, FALSE);
            $data['jumlah_reject'] = $this->db->get(TB_DETAIL)->row()->jml_reject;

            //=================================================
    
            $this->db->select("COUNT(*) AS jml_rfp");
            $this->db->where("problem_type IN($rfp_id)", NULL, FALSE);
            $data['jumlah_rfp'] = $this->db->get(TB_DETAIL)->row()->jml_rfp;
    
            $this->db->select("COUNT(*) AS jml_queue");
            $this->db->where('request_status', STT_ON_QUEUE);
            $this->db->where("problem_type IN($rfp_id)", NULL, FALSE);
            $data['jumlah_queue_rfp'] = $this->db->get(TB_DETAIL)->row()->jml_queue;
    
            $this->db->select("COUNT(*) AS jml_approve");
            $this->db->where('request_status', STT_APPROVED);
            $this->db->where("problem_type IN($rfp_id)", NULL, FALSE);
            $data['jumlah_approve_rfp'] = $this->db->get(TB_DETAIL)->row()->jml_approve;
    
            $this->db->select("COUNT(*) AS jml_progress");
            $this->db->where('request_status', STT_ON_PROGRESS);
            $this->db->where("problem_type IN($rfp_id)", NULL, FALSE);
            $data['jumlah_progress_rfp'] = $this->db->get(TB_DETAIL)->row()->jml_progress;
    
            $this->db->select("COUNT(*) AS jml_done");
            $this->db->where('request_status', STT_DONE);
            $this->db->where("problem_type IN($rfp_id)", NULL, FALSE);
            $data['jumlah_done_rfp'] = $this->db->get(TB_DETAIL)->row()->jml_done;
    
            $this->db->select("COUNT(*) AS jml_reject");
            $this->db->where('request_status', STT_REJECT);
            $this->db->where("problem_type IN($rfp_id)", NULL, FALSE);
            $data['jumlah_reject_rfp'] = $this->db->get(TB_DETAIL)->row()->jml_reject;

            //===================================================

            $tb_detail = TB_DETAIL;
            $tb_problem_type = TB_PROBLEM_TYPE;
            $month     = $this->input->post('month');
            $year      = $this->input->post('year');
            $where_request_date = "DATE(request_date) >= DATE_ADD(NOW(), INTERVAL -30 DAY)";
            $where_assign_date = "DATE(assign_date) >= DATE_ADD(NOW(), INTERVAL -30 DAY)";
            $where_done_date = "DATE(done_date) >= DATE_ADD(NOW(), INTERVAL -30 DAY)";
            if(!empty($month)) {
                $where_request_date = "MONTH(request_date) = '$month' AND YEAR(request_date) = '$year'";
                $where_assign_date = "MONTH(assign_date) = '$month' AND YEAR(assign_date) = '$year'";
                $where_done_date = "MONTH(done_date) = '$month' AND YEAR(done_date) = '$year'";
            }

            if(!empty($year))
            {
                $where_request_date = "MONTH(request_date) = '$month' AND YEAR(request_date) = '$year'";
                $where_assign_date = "MONTH(assign_date) = '$month' AND YEAR(assign_date) = '$year'";
                $where_done_date = "MONTH(done_date) = '$month' AND YEAR(done_date) = '$year'";
            }

            $Q = "  SELECT tgl, SUM(a) AS a, SUM(b) AS b, SUM(c) AS c
                    FROM (
                        SELECT 
                            request_date AS tgl, COUNT(request_date) AS a, 0 AS b, 0 AS c 
                        FROM
                            $tb_detail
                        WHERE
                            $where_request_date
                        GROUP BY
                            DATE_FORMAT(request_date, '%Y%m%d') 
                    UNION ALL 
                        SELECT 
                            assign_date AS tgl, 0 AS a, COUNT(assign_date) AS b, 0 AS c 
                        FROM
                            $tb_detail
                        WHERE
                            $where_assign_date
                        GROUP BY
                            DATE_FORMAT(assign_date, '%Y%m%d') 
                    UNION ALL 
                        SELECT 
                            done_date AS tgl, 0 AS a, 0 AS b, COUNT(done_date) AS c 
                        FROM
                            $tb_detail
                        WHERE
                            $where_done_date
                        GROUP BY
                            DATE_FORMAT(done_date, '%Y%m%d')
                    ) rfm
                    GROUP BY DATE_FORMAT(tgl, '%Y%m%d')
                ";
            $data['chart_line'] = $this->db->query($Q)->result();

            //=====================================================
            
            // $this->db->select("COUNT(*) AS jml_rfm");
            // $this->db->where("request_date");
            // $total_semua = $this->db->get(TB_DETAIL)->row()->jml_rfm;
            // $data['total_semua'] = $total_semua;
            $and_where = "AND MONTH(request_date) = '$month' AND YEAR(request_date) = '$year'";
            if(empty($month)) $and_where ="";
            $data['total_semua'] = $this->db->query("SELECT COUNT(*) as jml_rfm FROM $tb_detail WHERE 1 $and_where")->row()->jml_rfm;
            $total_semua = $data['total_semua'];

            $Q = "  SELECT rfm.problem_type, bb.`problem_type`, 
                        SUM(IF(`request_status`='ON QUEUE',1,0)) AS jml_rfm_pending, 
                        SUM(IF(`request_status` IN ('ON QUEUE','REJECT'),0,1)) AS jml_rfm_accepted, 
                        SUM(IF(`request_status`='REJECT',1,0)) AS jml_rfm_reject, 
                        ROUND((SUM(IF(`request_status` IN ('ON QUEUE','REJECT'),0,1)) / $total_semua)*100,2)AS rasio_rfm_accepted 
                    FROM $tb_detail rfm
                        LEFT JOIN $tb_problem_type bb ON bb.`id`=rfm.`problem_type`
                        WHERE bb.system_type IS NULL $and_where
                    GROUP BY rfm.problem_type";
            $data['chart_pie'] = $this->db->query($Q)->result();

            $this->template->load('template','dashboard/dashboard',$data);
        }else {
            $this->load->view('login/form_login');
        }
    }

}