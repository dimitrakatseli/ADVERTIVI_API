<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Advertiser extends CI_Controller {
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
			  $this->load->model('adevrtiser_database');
			 
        }  
	public function index()
	{
	
		$data['advertisers']=$this->adevrtiser_database->get_advertisers();

		$this->load->view('advertisers_page',$data);
	}
	
	public function addNew()
	{
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('affise_id', 'Affise Id', 'trim|xss_clean');
		$this->form_validation->set_rules('api_uri', 'Api Url', 'trim|xss_clean');
		$this->form_validation->set_rules('api_key', 'Api Key', 'trim|xss_clean');
		if ($this->form_validation->run() == FALSE) {
		
			$this->load->view('add_new_adevrtiser');
		}else{	
		
			$data = array(
			'advertiser_name' => $this->input->post('title'),
			'api_url' => $this->input->post('api_uri'),
			'api_key' => $this->input->post('api_key'),
			'affise_adevrtiser_id' => $this->input->post('affise_id')
			);
			$result = $this->adevrtiser_database->add_advertiser($data);
			if($result){
				redirect('advertiser');	
			}else{
				$data['error_message'] = 'advertiser is not added, Please check if it is already exists';
				$this->load->view('add_new_adevrtiser', $data);
			}
			
			
		}
	}
	public function edit($advertiserId)
	{
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('affise_id', 'Affise Id', 'trim|xss_clean');
		$this->form_validation->set_rules('api_uri', 'Api Url', 'trim|xss_clean');
		$this->form_validation->set_rules('api_key', 'Api Key', 'trim|xss_clean');
		
		
		if(isset($advertiserId)&&$advertiserId!='')
		{
			if ($this->form_validation->run() == FALSE) 
			{
				$data['advertiser'] = $this->adevrtiser_database->get_advertisers($advertiserId);
				
				$this->load->view('edit_advertiser',$data);
			}
			else 
			{	
				if ($this->input->post('update_advertiser')) 
				{
								
					$dataUpdate = array(
					'advertiser_name' => $this->input->post('title'),
					'api_url' => $this->input->post('api_uri'),
					'api_key' => $this->input->post('api_key'),
					'affise_adevrtiser_id' => $this->input->post('affise_id')
					);
					$result = $this->adevrtiser_database->edit_advertiser($advertiserId,$dataUpdate);
				
					if($result)
					{
						redirect('advertiser/');
					}
					else
					{
						$data['error_message']="advertiser not  updated,Please try again";
						$data['advertiser'] = $this->adevrtiser_database->get_advertisers($advertiserId);
						$this->load->view('edit_advertiser', $data);
					}
				}else
					{
						$data['error_message']="advertiser not updated,Please try again";
						$data['advertiser'] = $this->adevrtiser_database->get_advertisers($advertiserId);
						
						$this->load->view('edit_advertiser', $data);
					}
				
			}
		}
		else 
		{
			redirect('carModel/');
		}
	}
	

	
	public function Delete($advertiserId)
	{
		if(isset($advertiserId)&&$advertiserId!=''){
			$this->adevrtiser_database->delete_advertiser($advertiserId); 
			redirect('advertiser/');
		}else{
			redirect('advertiser/');
		}
	}
	
}
