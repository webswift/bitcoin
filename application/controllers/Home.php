<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct() {
        parent::__construct();
    }
	
	public function index()
	{
		$data['exchange_list'] = $this->common_mdl->select('exchange');
		$this->load->view('home_view',$data);
	}
}
