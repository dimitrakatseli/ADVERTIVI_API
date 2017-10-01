<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sources extends CI_Controller {
	public function __construct()
        {		parent::__construct();
				// Load form helper library
				$this->load->helper('form');

				// Load form validation library

				// Load session library
				$this->load->library('session');
               $this->load->helper('url_helper');
			  $this->load->model('offers_database');
			 
        }  
	public function index()
	{
	
		$data['demand_sources']=$this->offers_database->get_demand_sources();
		$this->load->view('offers_page',$data);
	}


	
}
