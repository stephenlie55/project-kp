<?php
class daily_report_model extends ci_model{
    
    function __construct() {
        parent::__construct();
        $this->load->database('ticket_support', TRUE);
	    // the TRUE paramater tells CI that you'd like to return the database object.
    }
	
	
	public function get_crud($data)
    {
        $db2 = $this->load->database('ticket_support', TRUE);
        if (is_array($data)) {
            if(array_key_exists('select', $data)) {
                $db2->select($data['select']);
            }
			
			if (array_key_exists('insert', $data)) {
				$db2->insert($data['insert']);
			}

            if(array_key_exists('table', $data)) {
                $db2->from($data['table']);
            }

            if(array_key_exists('where', $data)) {
                $db2->where($data['where']);
            }

            if(array_key_exists('or_where', $data)) {
                $db2->or_where($data['or_where']);
            }

            if(array_key_exists('like', $data)) {
                $db2->like($data['like']);
            }

            if(array_key_exists('or_like', $data)) {
                $db2->or_like($data['or_like']);
            }

            if(array_key_exists('order_by', $data)) {
                $db2->order_by($data['order_by']);
            }

            return $db2->get();
        }
    }
	
}