<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AffiseCategories extends CI_Controller {
	public function __construct()
    {		parent::__construct();
				$this->load->model('Affise_database');
			 
    }  
	public function index()
	{
		$_response_categories_affise='';
				//CATEGORIES API URL
		$affise_CATEGORIES_api_url  ="http://api.advertivi.com/2.1/offer/categories"; 
		
		//HEADER
		$ch_affise = curl_init(); 
		curl_setopt($ch_affise, CURLOPT_URL, $affise_CATEGORIES_api_url); 
		curl_setopt($ch_affise, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		
		//curl_setopt($ch, CURLOPT_HEADER, 1); 
		curl_setopt($ch_affise, CURLOPT_CUSTOMREQUEST, "GET"); 	
	curl_setopt($ch_affise, CURLOPT_RETURNTRANSFER, 1);		
		$ch_categories_result_affise = curl_exec($ch_affise);

		curl_close($ch_affise);
		if($ch_categories_result_affise != false){
			$_response_categories_affise = json_decode($ch_categories_result_affise);
			
		}

		if($_response_categories_affise!=''&&$_response_categories_affise->status>0){
			$updatedTolocalAffiseCats=$this->Affise_database->updateTolocalDb($_response_categories_affise->categories);
		}
	}
	

		
}
