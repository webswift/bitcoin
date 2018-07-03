<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Common_mdl extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function singleImageUpload($upload_name,$extension,$floder,$bnr)
    {
        
        $config['upload_path'] = './upload/'.$floder.'/';
        $config['allowed_types'] = '*';
        if($bnr == 2)
        {
            $config['max_width'] = '2000';
            $config['max_height'] = '2000';
        }
        elseif ($bnr == 1)
        {}
        $config['file_name'] = rand(0,9999).'_'.date('YmdHis').".".$extension;
        // $this->upload->initialize($config);
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload($upload_name))
        {
            $arrayRetutn['upload'] = 'False';
            $arrayRetutn['error'] = $this->upload->display_errors();
        }
        else
        {
            $arrayRetutn['upload'] = 'True';
            $arrayRetutn['data'] = $this->upload->data();
        }
         //echo '<pre>';print_r($arrayRetutn);echo '</pre>'; die;
        return $arrayRetutn;
    }

    function login($username, $password) {
        $query = $this->db->query("SELECT * FROM users WHERE (`name` = '".$username."' OR `email` = '".$username."') AND `password` = '".md5($password)."' AND role = '1'");
        return $query->row_array();
    }

    function query($query="",$is_single_row=false)
    {
        $q = $this->db->query($query);
        if($is_single_row){
            return $q->row_array();
        }
        return $q->result_array();
    }

    function count($table,$where=array('1'=>'1'))
    {
        $this->db->from($table);
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function select_group($table,$column,$where=array('1'=>'1'),$order_by=array(),$group_by=array(),$limit=false)
    {
        $this->db->select($column);
        $this->db->where($where);
        if($group_by) {
            $this->db->group_by($group_by);
        }
        if(isset($order_by) && count($order_by)==2){
            $this->db->order_by($order_by[0],$order_by[1]);
        }
        if($limit){
            $this->db->limit($limit);
        }
        return  $this->db->get($table)->result_array();
    }

    function select($table,$where=array('1'=>'1'),$order_by=array(),$limit=false)
    {
        $this->db->select('*');
        $this->db->where($where);
        if(isset($order_by) && count($order_by)==2){
            $this->db->order_by($order_by[0],$order_by[1]);
        }
        if($limit){
            $this->db->limit($limit);
        }
        return  $this->db->get($table)->result_array();
    }

    function select_data($table,$column,$where=array('1'=>'1'),$order_by=array(),$limit=false)
    {
        $this->db->select($column);
        $this->db->where($where);
        if(isset($order_by) && count($order_by)==2){
            $this->db->order_by($order_by[0],$order_by[1]);
        }
        if($limit){
            $this->db->limit($limit);
        }
        return  $this->db->get($table)->result_array();
    }

    function insert($table,$data)
    {
        $insert_id  = false;
        $is_insert = $this->db->insert($table,$data);
        if($is_insert)
        {
            $insert_id = $this->db->insert_id();
        }
        return $insert_id;
    }

    function insert_batch($table,$data=array())
    {
        $this->db->insert_batch($table,$data);
        return ($this->db->affected_rows()!=1)?false:true;
    }

    function update($table,$data,$where)
    {
        $this->db->where($where);
        $this->db->update($table,$data);
        return ($this->db->affected_rows()!=1)?false:true;
    }

    function delete($table,$where=array())
    {
        $this->db->delete($table, $where);
        return ($this->db->affected_rows()!=1)?false:true;
    }

    function get_table_by($table,$where,$field=false)
    {
        $this->db->where($where);
        if($field)
        return  $this->db->get($table)->row($field);
        else
        return  $this->db->get($table)->row_array();
    }

    function get_column_by($table,$column,$where = array('1'=>'1'))
    {
        $data = $this->db->select($column)->where($where)->get($table)->result_array();
        global $tmp_column;
        $tmp_column = $column;
        return array_map (function($value){
            global $tmp_column;
            return $value[$tmp_column];
        },$data);
    }

    function get_where_in($table,$column,$where_in = array('',array()))
    {
        $data = $this->db->select($column)->where_in($where_in[0],$where_in[1])->get($table)->result_array();
        global $tmp_column;
        $tmp_column = $column;
        return array_map (function($value){
            global $tmp_column;
            return $value[$tmp_column];
        },$data);
    }

    function get_user_meta($userid, $key = '')
    {
        if($userid!='')
        {
            $this->db->select('meta_key,meta_value');
            if($key)
            {
                $this->db->where('meta_key',$key);
            }
            $this->db->where('userid',$userid);
            $query = $this->db->get('usermeta');
            if($key)
            {
                $meta = $query->row('meta_value');
            }else{
                $meta = $query->result_array();
                $data = array();
                foreach ($meta as $value) $data[$value['meta_key']]=$value['meta_value'];
                $meta = $data;
            }
            return $meta;
        }
    }

    function update_user_meta($userid,$key,$val='')
    {
        if($userid!='' && $key !='')
        {
            $this->db->where('meta_key',$key);
            $this->db->where('userid',$userid);
            $q = $this->db->get('usermeta');
            if ( $q->num_rows() > 0 )
            {
                $data = array('meta_value'=>$val);
                $this->db->where('userid',$userid);
                $this->db->where('meta_key',$key);
                $this->db->update('usermeta',$data);
            } else {
                $this->db->set('userid',$userid);
                $this->db->set('meta_key',$key);
                $this->db->set('meta_value',$val);
                $this->db->insert('usermeta');
            }
        }
    }

    function delete_user_meta($userid,$key)
    {
        if($userid!=''&& $key!='')
        {
            return $this->db->delete('usermeta', array('userid' => $userid,'meta_key'=>$key));
        }
    }
}
?>