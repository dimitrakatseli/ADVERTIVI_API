<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaigns extends CI_Controller {
	public function __construct()
        {		parent::__construct();
				// Load form helper library
				$this->load->helper('form');
$this->load->helper('security');	
				// Load form validation library
				$this->load->library('form_validation');

				// Load session library
				$this->load->library('session');
               $this->load->helper('url_helper');
			  $this->load->model('campaigns_database');
			 
        }  
	public function index()
	{
		$data['demand_source_types']=$this->campaigns_database->get_demand_source_types();
		$data['demand_sources']=$this->campaigns_database->get_demand_sources();
		$this->load->view('campaign_page',$data);
	}
	
	public function addNew()
	{
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('api_uri', 'Api Url', 'trim|xss_clean');
		$this->form_validation->set_rules('api_key', 'Api Key', 'trim|xss_clean');
		$this->form_validation->set_rules('tracking_url_params', 'Tracking Params', 'trim|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			$data['demand_source_types']=$this->campaigns_database->get_demand_source_types();
			$this->load->view('add_new_source',$data);
		}else{	
			$data = array(
			'demand_source_title' => $this->input->post('title'),
			'demand_source_type' => $this->input->post('type'),
			'api_url' => $this->input->post('api_uri'),
			'api_key' => $this->input->post('api_key'),
			'tracking_params' => $this->input->post('tracking_url_params')
			);
			$result = $this->campaigns_database->add_demand_source($data);
			if($result){
				redirect('sources');	
			}else{
				$data['demand_source_types']=$this->campaigns_database->get_demand_source_types();	
				$data['error_message'] = 'Demand Source is not added, Please check if it is already exists';
				$this->load->view('add_new_campaign', $data);
			}
			
			
		}
	}

	
}
