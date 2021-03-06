<?php
class Auth_model extends ci_model {
    
    function logged_id() {
        return $this->session->userdata('USER_ID');
	}

	function check_login($field1, $field2) {
		$this->db->from(TB_USER);
		$this->db->where($field1);
		//$this->db->where($field2);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() == 0) {
			return FALSE;
		}else {
			return $query->row();
		}
	}

	function roleAkses()
	{
		$this->db->from(TB_PARAMETER);
		$this->db->where('id', 'RFM_AKSES_USER_MANAGEMENT');
		$query = $this->db->get();
		$row = $query->row();
		
		$explode = explode(':', $row->value);
		foreach($explode as $r):
			$rows = $r;
			$data[] = $rows;
		endforeach;
		
		return $data;
	}
	
    var $table = TB_USER;
    var $column_order = array(null, 'nama', 'user', 'divisi_id', 'jabatan');
    var $column_search = array('nama', 'user', 'divisi_id', 'jabatan');
    var $order = array('nama' => 'asc');

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
 
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
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
	}

    public function my_akses()
    {
		$this->db->like('id', 'RFM_AKSES');
        return $this->db->get(TB_PARAMETER)->result();
    }
}