<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiToolArtOfClick extends CI_Controller {
	public function __construct()
        {		parent::__construct();
				$this->load->model('Api_Art_Of_Click_database');
			 
        }  
		public function index()
		{
			$demand_sources=$this->Api_Art_Of_Click_database->get_demand_sources(10);
			if(isset($demand_sources[0])&&$demand_sources[0]->status==1){
				$this->art_of_click($demand_sources[0]->demand_source_id,$demand_sources[0]->affise_adevrtiser_id,$demand_sources[0]->api_key,$demand_sources[0]->api_url);
			}
			
		}
		
		public function art_of_click($sourceId,$advertiserId,$api_key,$api_url){
			$_response='';
			ini_set('mysql.connect_timeout', 1000);
			ini_set('default_socket_timeout', 1000);
		// ADVERTISR API KEY
			$affise_api_key="fe1a826b70bb1db82c83fd2539ed2696380a7a8a";
		
		// ADVERTISR API KEY 
			//$api_key="516369095c02f52147f379aa0a7a522fb58b5ccef2a0b995e91f339fbd719737";
			
		//ADVERTISER API URL
			//$api_url  ="http://api.artofclick.com/web/Api/v2.3/offer.json"; 

			//$advertiserId='58aac05b13e03baa5a8b458d';

		//HEADER
			
			$header = Array(); 
			$header[0] = "Content-Type: multipart/form-data";
			$header[1]="Accept: application/json";
		
		//CURL   
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $api_url."?api_key=".$api_key); 
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30000);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30000);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			$ch_result = curl_exec($ch);
			if($ch_result != false){
				$_response = json_decode($ch_result);
			}
			curl_close($ch);
		
			$propcnt=0;
			if($_response!=''&&$_response->count>0){
				$offerTolocalAdd=array();
				$offerTolocalForEdit=array();
				$deletedOffersFromAffise=array();
				$allOffersFromAdvertiser=array();
				$filePath = tempnam(sys_get_temp_dir(), 'php');
				file_put_contents($filePath, file_get_contents('http://advertivioffers.com/images/no-logo.gif'));

				foreach($_response->offers as $offer){
					$propcnt++;
						$headerExOffer = Array(); 
						$headerExOffer[0] =  "Content-type: application/json";
						$offer->advertiserId=$advertiserId;
					$chExOffer = curl_init(); 
					curl_setopt($chExOffer, CURLOPT_URL, "http://advertivioffers.com/ExArtOfClick"); 
					curl_setopt($chExOffer, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
					curl_setopt($chExOffer, CURLOPT_CUSTOMREQUEST, "POST"); 
					curl_setopt($chExOffer, CURLOPT_POST, 1); 
					curl_setopt($chExOffer, CURLOPT_POSTFIELDS, json_encode($offer));
					curl_setopt($chExOffer, CURLOPT_SSL_VERIFYPEER, false); 
					curl_setopt($chExOffer, CURLOPT_CONNECTTIMEOUT, 30000);
					curl_setopt($chExOffer, CURLOPT_TIMEOUT, 30000);
					curl_setopt($chExOffer, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($chExOffer, CURLOPT_HTTPHEADER, $headerExOffer);
					$ch_resultExOffer = curl_exec($chExOffer);
					if($errno = curl_errno($chExOffer)) {
    $error_message = curl_strerror($errno);
    echo "cURL error ({$errno}):\n {$error_message}";
}
					if($ch_resultExOffer == false){
					echo "error";
					}
					print_r($ch_resultExOffer);
					echo "***********************************";
					if($propcnt>2){
						break;
					}
					
				}
			
			}
		}
		
}
