<?php
ini_set('max_execution_time', 0);
ini_set('mysql.connect_timeout', 1000);
ini_set('default_socket_timeout', 1000);
defined('BASEPATH') OR exit('No direct script access allowed');
class ExArtOfClickAffiseExtra extends CI_Controller {
	public function __construct()
    {		parent::__construct();
				$this->load->model('Api_Art_Of_Click_database');
			 
    }  
	public function index()
	{
		
			$postdata=file_get_contents("php://input");
			$json_postdata=json_decode($postdata,true);
			$advertiserId=$json_postdata['advertiserId'];
			$offerTolocalForEdit=$json_postdata['offerTolocalForEdit'];
			print_r($offerTolocalForEdit);
			/*if(count($offerTolocalForEdit)>0){
						$headerExOfferEdit = Array(); 
						$headerExOfferEdit[0] =  "Content-type: application/json";
						$sendData['offer']=$offer;
						$sendData['offerDataTosend']=$data;
						$sendData['addedsourceId']=$sourceId;
					$dataTosend=array();
					$dataTosend['advertiserId']=$advertiserId
					$dataTosend['offerTolocalForEdit']=$offerTolocalForEdit;
					$chExOfferForEdit = curl_init(); 
					curl_setopt($chExOfferForEdit, CURLOPT_URL, "http://advertivioffers.com/ExArtOfClickUpdateTolocalDb"); 
					curl_setopt($chExOfferForEdit, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
					curl_setopt($chExOfferForEdit, CURLOPT_CUSTOMREQUEST, "POST"); 
					curl_setopt($chExOfferForEdit, CURLOPT_POST, 1); 
					curl_setopt($chExOfferForEdit, CURLOPT_POSTFIELDS, json_encode($dataTosend));
					curl_setopt($chExOfferForEdit, CURLOPT_SSL_VERIFYPEER, false); 
					//curl_setopt($chExOffer, CURLOPT_CONNECTTIMEOUT, 30000);
					//	curl_setopt($chExOffer, CURLOPT_TIMEOUT, 30000);
					//	curl_setopt($chExOffer, CURLOPT_TIMEOUT, 1);
					//	curl_setopt($chExOffer, CURLOPT_NOSIGNAL, 1);
					curl_setopt($chExOfferForEdit, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($chExOfferForEdit, CURLOPT_HTTPHEADER, $headerExOfferEdit);
					curl_exec($chExOfferForEdit);
					curl_close($chExOfferForEdit);
				
				
				
				
				
				
				
				$updatedTolocal=$this->Api_Art_Of_Click_database->updateTolocalDb($offerTolocalForEdit,$advertiserId);
			}*/
	}
}