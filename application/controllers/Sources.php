<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sources extends CI_Controller {
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
			  $this->load->model('sources_database');
			 
        }  
	public function index()
	{
	
		$data['demand_sources']=$this->sources_database->get_demand_sources();
		
		$this->load->view('source_page',$data);
	}
	public function getOffers($sourceId,$offerStstus){
		$data['demand_offers']=$this->sources_database->get_demand_source_offers($sourceId,$offerStstus);
		
		$this->load->view('source_offers',$data);
	}
		public function addNew()
	{
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('advertiser', 'Advertiser', 'trim|xss_clean');
		$this->form_validation->set_rules('status', 'Status', 'trim|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			$data['advertiserdata'] = $this->sources_database->get_advertisers();
			$this->load->view('add_new_source',$data);
		}else{	
		
			$data = array(
			'demand_source_title' => $this->input->post('title'),
			'advertiser' => $this->input->post('advertiser'),
			'status' => $this->input->post('status')
			);
			$result = $this->sources_database->add_demand_source($data);
			if($result){
				redirect('sources');	
			}else{
				$data['error_message'] = 'Demand Source is not added, Please check if it is already exists';
				$this->load->view('add_new_source', $data);
			}
			
			
		}
	}
	public function enabledisable($action,$sourceid)
	{
		if(isset($sourceid)&&$sourceid!=''){
			$data = array(
				'status' => $action
			);
			$this->sources_database->update_source($sourceid,$data); 
			redirect('sources/');
		}else{
			redirect('sources/');
		}
	}
	public function edit($sourceid)
	{
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('advertiser', 'Advertiser', 'trim|xss_clean');
		$this->form_validation->set_rules('status', 'Status', 'trim|xss_clean');
		
		
		if(isset($sourceid)&&$sourceid!='')
		{
			if ($this->form_validation->run() == FALSE) 
			{
				$data['advertiserdata'] = $this->sources_database->get_advertisers();
				$data['demandsources'] = $this->sources_database->get_demand_sources($sourceid);
				$this->load->view('edit_source',$data);
			}
			else 
			{	
				if ($this->input->post('update_source')) 
				{
					
						$dataUpdate = array(
							'demand_source_title' => $this->input->post('title'),
							'advertiser' => $this->input->post('advertiser'),
							'status' => $this->input->post('status')
							);
							$result = $this->sources_database->update_source($sourceid,$dataUpdate);
							if($result){
								redirect('sources');	
							}else{
								$data['error_message'] = 'Demand Source is not update, Please check if it is already exists';
								$data['advertiserdata'] = $this->sources_database->get_advertisers();
								$data['demandsources'] = $this->sources_database->get_demandsources();
								$this->load->view('edit_source', $data);
							}
				
				}else 
				{
					redirect('sources/');
				}
				
			}
		}else 
		{
			redirect('sources/');
		}
		
		
		
		
	}
	public function Delete($sourceid)
	{
		if(isset($sourceid)&&$sourceid!=''){
			$this->sources_database->delete_source($sourceid); 
			redirect('sources/');
		}else{
			redirect('sources/');
		}
	}

	
}
