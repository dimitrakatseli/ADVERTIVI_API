<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiToolJungletap extends CI_Controller {
	public $ExistingOffers=array();
	public function __construct()
        {		parent::__construct();
				$this->load->model('Api_Jungletap_database');
			 
        }  
		public function index()
		{
			$demand_sources=$this->Api_Jungletap_database->get_demand_sources(12);
			if(isset($demand_sources[0])&&$demand_sources[0]->status==1){
				$this->jungletap($demand_sources[0]->demand_source_id,$demand_sources[0]->affise_adevrtiser_id,$demand_sources[0]->api_key,$demand_sources[0]->api_url);
			}
			
		}
		
		public function jungletap($sourceId,$advertiserId,$api_key,$api_url){
			$_response='';
			
		// ADVERTISR API KEY
			$affise_api_key="fe1a826b70bb1db82c83fd2539ed2696380a7a8a";
			
		//HEADER
			
			$header = Array(); 
			$header[0] = "Content-Type: application/json";
			$header[1]="Accept: application/json";
		
		//CURL   
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $api_url."?token=".$api_key); 
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
		
			
			$propcnt=0;
			$addcount=0;
			$updatecount=0;
			if($_response!=''&&$_response->success>0){
				
				$offerTolocalForEdit=array();
				$deletedOffersFromAffise=array();
				$allOffersFromAdvertiser=array();
				$os=array();
				curl_close($ch);
				 $this->ExistingOffers=$this->Api_Jungletap_database->getExistingOffer_db($advertiserId);
				foreach($_response->offers as $offer){
				
					$data = [];

					if(!(isset($offer->Approved))||!($offer->Approved=="1")){
						continue;
					}
					
					if(isset($offer->Status)&&$offer->Status=='active'){
					$data['status']=$offer->Status;

					}else{
					continue;
					}
					
					if(isset($offer->Name)&&$offer->Name!=''){
					$data['title']=($offer->Name);

					}else{
					continue;
					}
					
										
					if(isset($offer->Tracking_url)&&$offer->Tracking_url!=''){
					$tUrldata=explode("?",$offer->Tracking_url);
					$trackingUrl=$tUrldata[0];
					$data['url']=$trackingUrl."?aff_sub={clickid}&aff_sub2={pid}";


					}else{
					continue;
					}

					if(isset($offer->Preview_url)&&$offer->Preview_url!=''){
					$data['url_preview']=$offer->Preview_url;

					}else{
					continue;
					}


					if(isset($offer->Countries)&&$offer->Countries!=''){
					$allcountries=explode(",",$offer->Countries);
					foreach($allcountries as $i=>$country){
					$data['countries[{'.$i.'}]']=$country;
					}
					}
					if(isset($offer->Description)&&$offer->Description!=''){
					$data['description']=$offer->Description;
					}

					if(isset($offer->Payout)&&$offer->Payout!='')
					{
					if(isset($offer->Countries)&&count($offer->Countries)>0){	
					$allcountries=explode(",",$offer->Countries);
					foreach($allcountries as $i=>$country){
						$data['payments[0][countries][{'.$i.'}]']=$country;
					}

					}
					if(isset($offer->Platforms)&&$offer->Platforms!=''){	
					$allPlatforms=explode(",",$offer->Platforms);
					foreach($allPlatforms as $i=>$dvice){
						if($dvice=='iPad'){
							$dvice='tablet';
						}
						if($dvice=='iPhone'){
							$dvice='mobile';
						}
						if($dvice!='Android'){
						$data['payments[0][devices][{'.$i.'}]']=$dvice;
						}
						if($dvice=='Android'){
							if(!in_array($dvice,$os)){
							array_push($os,$dvice);
							}
						}
					}

					}
					if(isset($os)&&count($os)>0){	
					$offer->Os='Android';
					foreach($os as $i=>$oss){
						$data['payments[0][os][{'.$i.'}]']=$oss;
					}

					}
					$revenueper=(float)(($offer->Payout*20)/100);
					$revenue=round((float)($offer->Payout-$revenueper),2);

					$data['payments[0][total]']=$offer->Payout;
					$data['payments[0][revenue]']=$revenue;

					if(isset($offer->Currency)&&$offer->Currency!=''){	
					$data['payments[0][currency]']=$offer->Currency;

					}
					$data['payments[0][type]']='fixed';

					}


					if(isset($offer->Daily_cap)&&$offer->Daily_cap!=''){
					$data['daily_cap']=$offer->Daily_cap;

					}
					$data['send_emails']=1;
					$data['privacy']='protected';
					$data['advertiser']=$advertiserId;
					array_push($allOffersFromAdvertiser,$offer->ID);
					$oldOffer=$this->checkExistingOffer($offer->ID,$advertiserId);
					if(!$oldOffer['isexist']){
						$headerExOffer = Array(); 
						$headerExOffer[0] =  "Content-type: application/json";
						$sendData=array();
						$sendData['offer']=$offer;
						$sendData['offerDataTosend']=$data;
						
						$sendData['api_key']=$api_key;
						$sendData['addedsourceId']=$sourceId;
						
						$chExOffer = curl_init(); 
						curl_setopt($chExOffer, CURLOPT_URL, "http://rslinfotech.in/Advertivioffers/ApiToolJungletap"); 
						curl_setopt($chExOffer, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
						curl_setopt($chExOffer, CURLOPT_CUSTOMREQUEST, "POST"); 
						curl_setopt($chExOffer, CURLOPT_POST, 1); 
						curl_setopt($chExOffer, CURLOPT_POSTFIELDS, json_encode($sendData));
						curl_setopt($chExOffer, CURLOPT_SSL_VERIFYPEER, false); 
						//curl_setopt($chExOffer, CURLOPT_CONNECTTIMEOUT, 30000);
						//	curl_setopt($chExOffer, CURLOPT_TIMEOUT, 30000);
						//	curl_setopt($chExOffer, CURLOPT_TIMEOUT, 1);
						//	curl_setopt($chExOffer, CURLOPT_NOSIGNAL, 1);
						curl_setopt($chExOffer, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($chExOffer, CURLOPT_HTTPHEADER, $headerExOffer);
						curl_exec($chExOffer);
						curl_close($chExOffer);
						$addcount++;
					}else{
						$localOfferId=$oldOffer['oldOfferData']['id'];
						
						$oldAffiseOfferId=$oldOffer['oldOfferData']['affise_offer_id'];
						$deltedFromAffise=$oldOffer['oldOfferData']['deletedFromAffise'];
						$changeCount=0;
						if(($offer->Status)!=$oldOffer['oldOfferData']['status']){
							$changeCount++;
						}
						if(urlencode($offer->Name)!=$oldOffer['oldOfferData']['title']){
							$changeCount++;
						}
					
						if(($offer->Daily_cap)!=$oldOffer['oldOfferData']['daily_cap']){
							$changeCount++;
						}
						
						if(urlencode($offer->Description)!=$oldOffer['oldOfferData']['description']){
							$changeCount++;
						}
						if($offer->Payout!=$oldOffer['oldOfferData']['payments']){
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
							
							$updatecount++;
							$result=$this->updateOfferToAffise($data,$oldAffiseOfferId);
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
					if($addcount>50||$updatecount>50){
					break;
					}
					
					
					
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
				}
				
				if(count($offerTolocalForEdit)>0){
					$updatedTolocal=$this->Api_Jungletap_database->updateTolocalDb($offerTolocalForEdit,$advertiserId);
					 
				}
				if(count($deletedOffersFromAffise)>0){
					$updatedTolocalAffiseDelete=$this->Api_Jungletap_database->updateTolocalDbAffiseDelete($deletedOffersFromAffise,$advertiserId);
					
				}
				if(count($allOffersFromAdvertiser)>0){
					//$deletedFromLocal=$this->Api_Jungletap_database->deleteFromLocal($allOffersFromAdvertiser);
					
					$extraOnLocal=$this->Api_Jungletap_database->getExtraFromLocal($allOffersFromAdvertiser,$advertiserId);
					if(!EMPTY($extraOnLocal)&&count($extraOnLocal)>0){
						$offerTolocalForEditStatus=array();
						$deletedOffersFromAffise=array();
						echo count($extraOnLocal);
						foreach($extraOnLocal as $extraOfferOnLocal){
							$oldAffiseOfferId=$extraOfferOnLocal->affise_offer_id;
							$updateOfferRequestFields='';
							$updateOfferRequestFields=$updateOfferRequestFields."&status=stopped";
							
							$resultUpdateStatusAffise=$this->updateOfferToAffise($updateOfferRequestFields,$oldAffiseOfferId);
							
							if($resultUpdateStatusAffise->status==1){	
								array_push($offerTolocalForEditStatus,$extraOfferOnLocal->id);
							}else if($result->error == 'Unknown offer id'){
								array_push($deletedOffersFromAffise,$extraOfferOnLocal->id);
							}
							
						}
						if(count($deletedOffersFromAffise)>0){
							$updatedTolocalAffiseDelete=$this->Api_Jungletap_database->updateTolocalDbAffiseDelete($deletedOffersFromAffise,$advertiserId);
						}
						if(count($offerTolocalForEditStatus)>0){
							$updatedTolocalEditStatus=$this->Api_Jungletap_database->updateTolocalDbEditStatus($offerTolocalForEditStatus,$advertiserId);
						}
						
					}
				}
				$this->Api_Jungletap_database->updateDemandSource_lastUpdate($sourceId);		
			}
		}

		public function updateOfferToAffise($OfferRequest,$offerId){
			
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
			curl_close($ch_affise);
			if($ch_offer_result_affise === false){
				return false;
				
			}else{
				$_response_Offer_affise = json_decode($ch_offer_result_affise);
				return $_response_Offer_affise;
			}
		
		}		
		public function checkExistingOffer($advertOfferId,$advertiser){
		$oldRecord=array();
		$oldRecord['isexist']=false;
		$oldoffer=array();
		if(count($this->ExistingOffers)>0){
		foreach($this->ExistingOffers as $ExistingOffer){
			if($ExistingOffer->advertiser_offer_id==$advertOfferId){
				$oldRecord['isexist']=true;
				$oldRecord['oldOfferData']=(array)$ExistingOffer;
				$found=1;
				break;
			}
		}
		}
		return $oldRecord;
		
	}
}
