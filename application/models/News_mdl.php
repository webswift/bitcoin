<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class News_mdl extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    var $table = 'news as p';
    var $column_order = array('p.id','p.title','p.description','p.image','p.insert_date','p.status'); 
    var $column_search = array('p.id','p.title','p.description','p.image','p.insert_date','p.status'); 
    var $order = array('p.id' => 'ASC'); 
    
    private function _get_datatables_query()
    {
        $this->db->select($this->column_order);
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
    
    public function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function count_filtered()
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

    public function getcategory()
    {
        return $this->db->get('category')->result_array();
    }
}
?>