<?php
class Rfm_model extends ci_model{
    var $table = TB_DETAIL;
    var $column_order = array(null, 'id');
    var $column_search = array('id', 'nama', 'no_rfm', 'request_status', 'result_status');
    var $order = array('jumlah' => 'asc');

    private function _get_datatables_query($SESSION_UPLINE)
    {
        $SESSION_USER_ID = $this->session->userdata('USER_ID');
        
        $this->db->where('id', 'RFM_RFP_ID');
        $row_rfp = $this->db->get(TB_PARAMETER)->row()->value;
        $rfp_id = explode(":", $row_rfp);
        array_pop($rfp_id);
        $rfp_id = implode(",",$rfp_id);

        $qry = "*,
            CASE
                WHEN
                    (
                        request_upline_by = '$SESSION_UPLINE'
                        AND request_status = 'ON QUEUE'
                    )
                    OR (
                        receive_by = '$SESSION_UPLINE' 
                        AND request_status = 'APPROVED'
                    ) 
                    OR (
                        assign_to = '$SESSION_USER_ID' 
                        AND request_status = 'ON PROGRESS'
                    )
                THEN 1
                WHEN
                    request_by = '$SESSION_USER_ID'
                    AND request_status = 'DONE'
                    AND result_status = 'PENDING'
                THEN 2
                WHEN
                    request_by = '$SESSION_USER_ID'
                    AND request_status = 'ON QUEUE'
                THEN 3
                WHEN
                    request_by = '$SESSION_USER_ID'
                    AND request_status NOT IN('REJECT', 'DONE')
                THEN 4
                WHEN
                    request_by != '$SESSION_USER_ID'
                    AND request_status IN ('ON PROGRESS')
                THEN 4
            ELSE 99 
            END AS jumlah
        ";
        $this->db->select($qry);
        $this->db->from($this->table);
        $this->db->join(TB_USER, 'user.user_id =' .$this->table. '.request_by', 'left');
        $this->db->where("problem_type NOT IN($rfp_id)", NULL, FALSE);
        // $this->db->where("result_status NOT IN('SOLVED', 'REJECT')", NULL, FALSE);
 
        $i = 0;
     
        foreach ($this->column_search as $item)
        {
            if($_POST['search']['value'])
            {
                 
                if($i===0)
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }
         
        if(isset($_POST['order'])) 
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables($SESSION_UPLINE)
    {
        $this->_get_datatables_query($SESSION_UPLINE);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($SESSION_UPLINE)
    {
        
        $this->db->where('id', 'RFM_RFP_ID');
        $row_rfp = $this->db->get(TB_PARAMETER)->row()->value;
        $rfp_id = explode(":", $row_rfp);
        array_pop($rfp_id);
        $rfp_id = implode(",",$rfp_id);
        $this->_get_datatables_query($SESSION_UPLINE);
        $this->db->where("problem_type NOT IN($rfp_id)", NULL, FALSE);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->where('id', 'RFM_RFP_ID');
        $row_rfp = $this->db->get(TB_PARAMETER)->row()->value;
        $rfp_id = explode(":", $row_rfp);
        array_pop($rfp_id);
        $rfp_id = implode(",",$rfp_id);
        $this->db->where("problem_type NOT IN($rfp_id)", NULL, FALSE);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_crud($data)
    {
        $db = $this->load->database('ticket_support', TRUE);
        if (is_array($data)) {
            if(array_key_exists('select', $data)) {
                $this->db->select($data['select']);
            }

            if(array_key_exists('table', $data)) {
                $this->db->from($data['table']);
            }

            if(array_key_exists('where', $data)) {
                $this->db->where($data['where']);
            }

            if(array_key_exists('or_where', $data)) {
                $this->db->or_where($data['or_where']);
            }

            if(array_key_exists('like', $data)) {
                $this->db->like($data['like']);
            }

            if(array_key_exists('or_like', $data)) {
                $this->db->or_like($data['or_like']);
            }

            if(array_key_exists('order_by', $data)) {
                $this->db->order_by($data['order_by']);
            }

            return $this->db->get();
        }
    }
}