<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
 
class Template {
    var $template_data = array();

    function set($name, $value)
    {
        $this->template_data[$name] = $value;
    }

    function load($template = '', $view = '' , $view_data = array(), $return = FALSE)
    {
        $this->CI =& get_instance();

        $this->CI->load->model('Auth_model');
        $view_data['menu'] = $this->CI->Auth_model->roleAkses();
        

        $this->CI->load->model('rfm_model');
        $array_crud = array(
            'table' => TB_PARAMETER,
            'where' => array('id' => 'RFM_AKSES_IT_APP')
        );
        $area_it = $this->CI->rfm_model->get_crud($array_crud)->row();
        $ex_id_it = explode(":", $area_it->value);
        if(in_array($this->CI->session->userdata('USER_ID'), $ex_id_it))
        {
            $ini = "
                <li class='list-group-item'><a href='".base_url('kpi')."'>KPI PIC</a></li>
            ";
        }else{
            $ini = "";
        }
        $view_data['menu_kpi'] = $ini;

        $this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
        return $this->CI->load->view($template, $this->template_data, $return);
    }
}