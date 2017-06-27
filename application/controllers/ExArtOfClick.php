<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExArtOfClick extends CI_Controller {
	public function __construct()
        {		parent::__construct();
				$this->load->model('Api_Art_Of_Click_database');
			 
        }  
		public function index()
		{
			$postdata=file_get_contents("php://input");
			$json_OfferData=json_decode($postdata,true);
			
			$sourceId=$json_OfferData['addedsourceId'];
			
			
			$api_key=$json_OfferData['api_key'];
			
			$data=$json_OfferData['offerDataTosend'];
			$offer=(object)$json_OfferData['offer'];
			$advertiserId=$data['advertiser'];
			
		
			$offerTolocalAdd=array();
			$offerTolocalForEdit=array();
			$deletedOffersFromAffise=array();
			$allOffersFromAdvertiser=array();
			
			//$filePath = tempnam(sys_get_temp_dir(), 'php');
			//file_put_contents($filePath, file_get_contents('http://advertivioffers.com/images/no-logo.gif'));
			
			$creativeimagecount=0;
			$creativezipcount=0;
			$creativevideocount=0;
			$filecount=0;
			$creativefilePath = './downloads/';
			
				
				//array_push($allOffersFromAdvertiser,$offer->id);
				if(isset($offer->creatives)&&count($offer->creatives)>0){
						foreach($offer->creatives as $i=>$creative){
							$path_info = pathinfo($creative);
							$filenamefromserver=$path_info['filename'];
							$filename=$creativefilePath.$advertiserId."_".$offer->id.$filenamefromserver.".".$path_info['extension']; // "bill"
							if($creativeimagecount!=1){							
								if(strtolower($path_info['extension'])=='png'||strtolower($path_info['extension'])=='jpg'||strtolower($path_info['extension'])=='jpeg'||strtolower($path_info['extension'])=='gif'){
									if(file_exists($filename)){
										$creativeimagecount++;
										continue;
									}else{								
										file_put_contents($filename, file_get_contents($creative));
										$creativefile = new CURLFile($filename, filetype($filename), basename($filename));
										$data["creativeFiles[{$filecount}]"]=$creativefile;
										$creativeimagecount++;
										$filecount++;
									}
								}
							}
							if($creativezipcount!=1){
								if(strtolower($path_info['extension'])=='zip'){
									if(file_exists($filename)){
										$creativezipcount++;
										continue;
									}else{
										file_put_contents($filename, file_get_contents($creative));
										$creativefile = new CURLFile($filename, filetype($filename), basename($creativefilePath));
										$data["creativeFiles[{$filecount}]"]=$creativefile;
										$creativezipcount++;
										$filecount++;
									}
								}
							}
						}
					}
					$result=$this->sendOfferToAffise($data);
					if($result->status==1){	
						$offer->affiseofferid=$result->offer->id;
						$offer->advertiser=$result->offer->advertiser;
						array_push($offerTolocalAdd,$offer);
					}						
			
				if(count($offerTolocalAdd)>0){	
					$addedTolocal=$this->Api_Art_Of_Click_database->addTolocalDb($sourceId,$offerTolocalAdd,$advertiserId);
				}
				
		}
		
		public function sendOfferToAffise($OfferRequest){
		///	print_r($OfferRequest);
			//API URL
				$affise_api_url  ="http://api.advertivi.com/2.1/admin/offer"; 
			//HEADER
				$header = Array(); 
				$header[0] =  "Content-type: multipart/form-data";
				$header[1]="API-Key:fe1a826b70bb1db82c83fd2539ed2696380a7a8a";

				$uData=$OfferRequest;
				$ch_affise = curl_init(); 
			curl_setopt($ch_affise, CURLOPT_URL, "http://api.advertivi.com/2.1/admin/offer"); 
			curl_setopt($ch_affise, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch_affise, CURLOPT_POST, 1);
			curl_setopt($ch_affise, CURLOPT_POSTFIELDS, $uData);
			curl_setopt($ch_affise, CURLOPT_RETURNTRANSFER, true);
			
			$ch_result_affise = curl_exec($ch_affise);

			curl_close($ch_affise);
			
			if($ch_result_affise === false){
				return false;
			}else{
				$_response_affise = json_decode($ch_result_affise);
				
				return $_response_affise;
			}
			
		}

}
