<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TimeC extends CI_Controller {
	public function __construct()
        {		parent::__construct();
				$this->load->model('Api_Art_Of_Click_database');
			 
        }  
		public function index()
		{
			$this->Api_Art_Of_Click_database->updateDemandSource_lastUpdate(10);		
			
		}
	
		
}
