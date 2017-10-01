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
				
				foreach($_response->offers as $offer){
					$propcnt++;
				
					$sendOfferRequestFields='';
				
					if(isset($offer->status)&&$offer->status=='active'){
						$sendOfferRequestFields=$sendOfferRequestFields."&status=".$offer->status;
					}else{
						continue;
					}
					
					if(isset($offer->name)&&$offer->name!=''){
						$sendOfferRequestFields=$sendOfferRequestFields."&title=".urlencode($offer->name);
					}else{
						continue;
					}
					if(isset($offer->trackingUrl)&&$offer->trackingUrl!=''){
						$tUrldata=explode("?",$offer->trackingUrl);
						$trackingUrl=$tUrldata[0];
						$sendOfferRequestFields=$sendOfferRequestFields."&url=".urlencode($trackingUrl."?aff_sub={clickid}&source={pid}");
						
					}else{
						continue;
					}
					if(isset($offer->previewUrl)&&$offer->previewUrl!=''){
						$sendOfferRequestFields=$sendOfferRequestFields."&url_preview=".urlencode($offer->previewUrl);
						
					}else{
						continue;
					}
					if(isset($offer->countries)&&count($offer->countries)>0){
						foreach($offer->countries as $country){
						$sendOfferRequestFields=$sendOfferRequestFields."&countries[]=".urlencode($country);
						
						}
					}
					if(isset($offer->description)&&$offer->description!=''){
						
						$sendOfferRequestFields=$sendOfferRequestFields."&description=".urlencode($offer->description);
						
					}						
					if(isset($offer->payout)&&$offer->payout!='')
					{
						if(isset($offer->countries)&&count($offer->countries)>0){	
							foreach($offer->countries as $country){
								$sendOfferRequestFields=$sendOfferRequestFields."&payments[0][countries][]=".urlencode($country);
							}
						}
						if(isset($offer->device)&&count($offer->device)>0){	
							foreach($offer->device as $dvice){
								if($dvice=='iPad'){
									$dvice='tablet';
								}
								if($dvice=='iPhone'){
									$dvice='mobile';
								}
								$sendOfferRequestFields=$sendOfferRequestFields."&payments[0][devices][]=".urlencode($dvice);
							}
						}
						if(isset($offer->os)&&count($offer->os)>0){	
							foreach($offer->os as $oss){
								$sendOfferRequestFields=$sendOfferRequestFields."&payments[0][os][]=".urlencode($oss);
							}
						}
						$revenueper=(float)(($offer->payout*20)/100);
						$revenue=round((float)($offer->payout-$revenueper),2);
						
						$sendOfferRequestFields=$sendOfferRequestFields."&payments[0][total]=".$offer->payout;
						$sendOfferRequestFields=$sendOfferRequestFields."&payments[0][revenue]=".urlencode($revenue);
						
						if(isset($offer->currency)&&$offer->currency!=''){	
							$sendOfferRequestFields=$sendOfferRequestFields."&payments[0][currency]=".urlencode($offer->currency);
						}
						$sendOfferRequestFields=$sendOfferRequestFields."&payments[0][type]=fixed";
					}
					if(isset($offer->dailyCap)&&$offer->dailyCap!=''){
						$sendOfferRequestFields=$sendOfferRequestFields."&daily_cap=".urlencode($offer->dailyCap);
					}
					
					
					
					
					
					
					
					
					
					
					
					
					/*	if(isset($offer->incent)&&$offer->incent!=''){
						$sendOfferRequestFields=$sendOfferRequestFields."&categories[]=incent";
						
					}*/
					/*if(isset($offer->creatives)&&count($offer->creatives)>0){
						foreach($offer->creatives as $creatives){
							$sendOfferRequestFields=$sendOfferRequestFields."&creativeFiles[]=".$creatives;
						}
					}*/
					/*	if(isset($offer->browser )&&count($offer->browser )>0){
						foreach($offer->browser  as $brwser){
							$sendOfferRequestFields=$sendOfferRequestFields."&strictly_os[]=".$brwser;
						}
					}
					
					if(isset($offer->os )&&count($offer->os )>0){
						foreach($offer->os  as $oss){
							$sendOfferRequestFields=$sendOfferRequestFields."&strictly_os[]=".$oss;
						}
					}
					*/	
					$sendOfferRequestFields=$sendOfferRequestFields."&privacy=protected";
					$sendOfferRequestFields=$sendOfferRequestFields."&advertiser=".$advertiserId;
					/*incent
					storeId
					noticePeriod
					browser
					device
					os
					osVersionMinimum
					countries
					approved
					downloadType
					monthlyCap
					dailyCapsRemaining
					subIds*/
					array_push($allOffersFromAdvertiser,$offer->id);
					$oldOffer=$this->checkExistingOffer($offer->id);
					if(!$oldOffer['isexist']){	
						$result=$this->sendOfferToAffise($sendOfferRequestFields);
						if($result->status==1){	
							$offer->affiseofferid=$result->offer->id;
							$offer->advertiser=$result->offer->advertiser;
							array_push($offerTolocalAdd,$offer);
						}						
					}else{
						$localOfferId=$oldOffer['oldOfferData']['id'];
						
						$oldAffiseOfferId=$oldOffer['oldOfferData']['affise_offer_id'];
						$deltedFromAffise=$oldOffer['oldOfferData']['deletedFromAffise'];
						$changeCount=0;
						if(urlencode($offer->status)!=$oldOffer['oldOfferData']['status']){
							$changeCount++;
						}
						if(urlencode($offer->name)!=$oldOffer['oldOfferData']['title']){
							$changeCount++;
						}
					
						if(urlencode($offer->dailyCap)!=$oldOffer['oldOfferData']['daily_cap']){
							$changeCount++;
						}
						
						if(urlencode($offer->description)!=$oldOffer['oldOfferData']['description']){
							$changeCount++;
						}
						if($offer->payout!=$oldOffer['oldOfferData']['payments']){
							$changeCount++;
						}	
					/*	$sendOfferRequestFields=$sendOfferRequestFields."&privacy=protected";
						$sendOfferRequestFields=$sendOfferRequestFields."&advertiser=58aac05b13e03baa5a8b458d";
						$sendOfferRequestFields=$sendOfferRequestFields."&daily_cap=".urlencode($offer->dailyCap);
						$sendOfferRequestFields=$sendOfferRequestFields."&description=".urlencode($offer->description);
						$sendOfferRequestFields=$sendOfferRequestFields."&countries[]=".urlencode($country);
						$sendOfferRequestFields=$sendOfferRequestFields."&url_preview=".urlencode($offer->previewUrl);
						$sendOfferRequestFields=$sendOfferRequestFields."&url=".urlencode($trackingUrl."?aff_sub={clickid}&source={pid}");
						$sendOfferRequestFields=$sendOfferRequestFields."&title=".urlencode($offer->name);
						$sendOfferRequestFields=$sendOfferRequestFields."&payments[0][total]=".$offer->payout;
						$sendOfferRequestFields=$sendOfferRequestFields."&payments[0][revenue]=".urlencode($revenue);
						
						*/			
						if(!$deltedFromAffise&&$changeCount>0){
							
							$result=$this->updateOfferToAffise($sendOfferRequestFields,$oldAffiseOfferId);
							if($result->status==1){	
								$offer->oldid=$localOfferId;
								$offer->affiseofferid=$result->offer->id;
								$offer->advertiser=$result->offer->advertiser;
								array_push($offerTolocalForEdit,$offer);
							}else if($result->error == 'Unknown offer id'){
								array_push($deletedOffersFromAffise,$localOfferId);
							}				
						}
					}
					/*if($propcnt>5){
						break;
					}*/
				}
				if(count($offerTolocalAdd)>0){	
					$addedTolocal=$this->Api_Art_Of_Click_database->addTolocalDb($sourceId,$offerTolocalAdd);
				}
				if(count($offerTolocalForEdit)>0){
					$updatedTolocal=$this->Api_Art_Of_Click_database->updateTolocalDb($offerTolocalForEdit);
					 
				}
				if(count($deletedOffersFromAffise)>0){
					$updatedTolocalAffiseDelete=$this->Api_Art_Of_Click_database->updateTolocalDbAffiseDelete($deletedOffersFromAffise);
					
				}
				if(count($allOffersFromAdvertiser)>0){
					//$deletedFromLocal=$this->Api_Art_Of_Click_database->deleteFromLocal($allOffersFromAdvertiser);
					
					$extraOnLocal=$this->Api_Art_Of_Click_database->getExtraFromLocal($allOffersFromAdvertiser);
					if(!EMPTY($extraOnLocal)&&count($extraOnLocal)>0){
						$offerTolocalForEditStatus=array();
						$deletedOffersFromAffise=array();
						echo count($extraOnLocal);
						foreach($extraOnLocal as $extraOfferOnLocal){
							$oldAffiseOfferId=$extraOfferOnLocal->affise_offer_id;
							$updateOfferRequestFields='';
							$updateOfferRequestFields=$updateOfferRequestFields."&status=stopped";
							
							$resultUpdateStatusAffise=$this->updateOfferToAffise($updateOfferRequestFields,$oldAffiseOfferId);
							print_R($resultUpdateStatusAffise);
							if($resultUpdateStatusAffise->status==1){	
								array_push($offerTolocalForEditStatus,$extraOfferOnLocal->id);
							}else if($result->error == 'Unknown offer id'){
								array_push($deletedOffersFromAffise,$extraOfferOnLocal->id);
							}
							
						}
						if(count($deletedOffersFromAffise)>0){
							$updatedTolocalAffiseDelete=$this->Api_Art_Of_Click_database->updateTolocalDbAffiseDelete($deletedOffersFromAffise);
						}
						if(count($offerTolocalForEditStatus)>0){
							$updatedTolocalEditStatus=$this->Api_Art_Of_Click_database->updateTolocalDbEditStatus($offerTolocalForEditStatus);
						}
						
					}
				}
				$this->Api_Art_Of_Click_database->updateDemandSource_lastUpdate();		
			}
		}
		public function sendOfferToAffise($OfferRequest){
			//global $affise_api_key;
		//echo $affise_api_key;
			//API URL
				$affise_api_url  ="http://api.advertivi.com/2.1/admin/offer"; 
			//HEADER
				$header = Array(); 
				$header[0] = "Content-Type: application/x-www-form-urlencoded";
				$header[1]="Accept: application/json";
				$header[2]="API-Key:fe1a826b70bb1db82c83fd2539ed2696380a7a8a";
			
				$uData=$OfferRequest;
			$ch_affise = curl_init(); 
			curl_setopt($ch_affise, CURLOPT_URL, "http://api.advertivi.com/2.1/admin/offer"); 
			curl_setopt($ch_affise, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        	curl_setopt($ch_affise, CURLOPT_CUSTOMREQUEST, "POST"); 
			curl_setopt($ch_affise, CURLOPT_POST, 1); 
			curl_setopt($ch_affise, CURLOPT_SSL_VERIFYPEER, false); 
			curl_setopt($ch_affise, CURLOPT_CONNECTTIMEOUT, 35);
			curl_setopt($ch_affise, CURLOPT_TIMEOUT, 50);
			curl_setopt($ch_affise, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch_affise, CURLOPT_POSTFIELDS, $uData);
			curl_setopt($ch_affise, CURLOPT_HTTPHEADER, $header);
			$ch_result_affise = curl_exec($ch_affise);
			if($ch_result_affise === false){
				return false;
			}else{
				$_response_affise = json_decode($ch_result_affise);
				return $_response_affise;
			}
			curl_close($ch_affise);
		}
		public function updateOfferToAffise($OfferRequest,$offerId){
		//	global $affise_api_key;
			
			//API URL
			$affise_GETOFFER_api_url  ="http://api.advertivi.com/2.1/offer"; 
			
			//HEADER
			$header = Array(); 
			$header[0] = "Content-Type: application/x-www-form-urlencoded";
			$header[1]="Accept: application/json";
			$header[2]="API-Key:fe1a826b70bb1db82c83fd2539ed2696380a7a8a";
			$uData=$OfferRequest;
	
			$ch_affise = curl_init(); 
			curl_setopt($ch_affise, CURLOPT_URL, "http://api.advertivi.com/2.1/admin/offer/".$offerId); 
			curl_setopt($ch_affise, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
			
			//curl_setopt($ch, CURLOPT_HEADER, 1); 
			curl_setopt($ch_affise, CURLOPT_CUSTOMREQUEST, "POST"); 
			curl_setopt($ch_affise, CURLOPT_POST, 1); 
			curl_setopt($ch_affise, CURLOPT_SSL_VERIFYPEER, false); 
			curl_setopt($ch_affise, CURLOPT_CONNECTTIMEOUT, 35);
			curl_setopt($ch_affise, CURLOPT_TIMEOUT, 50);
			curl_setopt($ch_affise, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch_affise, CURLOPT_POSTFIELDS, $uData);
			curl_setopt($ch_affise, CURLOPT_HTTPHEADER, $header);
			//curl_setopt($ch, CURLOPT_FAILONERROR, 1); 

			$ch_offer_result_affise = curl_exec($ch_affise);
			if($ch_offer_result_affise === false){
				return false;
				
			}else{
				$_response_Offer_affise = json_decode($ch_offer_result_affise);
				return $_response_Offer_affise;
			}
			curl_close($ch_affise);
		}		
		public function checkExistingOffer($advertOfferId){
			$oldRecord=$this->Api_Art_Of_Click_database->checkExistingOffer_db($advertOfferId);
			return $oldRecord;
		}
}
