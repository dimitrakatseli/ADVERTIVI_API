<?php
ini_set('max_execution_time', 0);
ini_set('mysql.connect_timeout', 1000);
ini_set('default_socket_timeout', 1000);
defined('BASEPATH') OR exit('No direct script access allowed');
class ApiToolArtOfClick extends CI_Controller {
	public $ExistingOffers=array();
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
		$addcount=0;
		$updatecount=0;
		if($_response!=''&&$_response->count>0){
			$offerTolocalAdd=array();
			$offerTolocalForEdit=array();
			$deletedOffersFromAffise=array();
			$allOffersFromAdvertiser=array();
			//$filePath = tempnam(sys_get_temp_dir(), 'php');
			//file_put_contents($filePath, file_get_contents('http://advertivioffers.com/images/no-logo.gif'));
			 $this->ExistingOffers=$this->Api_Art_Of_Click_database->getExistingOffer_db($advertiserId);
			
			foreach($_response->offers as $offer){
				$propcnt++;
					
						$data = [];
				if(!(isset($offer->approved))||!($offer->approved==1)){
					continue;
				}					
				if(isset($offer->status)&&$offer->status=='active'){
					$data['status']=$offer->status;
				}else{
					continue;
				}
				
				if(isset($offer->name)&&$offer->name!=''){
					$data['title']=$offer->name;
				}else{
					continue;
				}
				//$clogofile = new CURLFile($filePath, filetype($filePath), basename($filePath));
				//$data['logo']=$clogofile;
				if(isset($offer->trackingUrl)&&$offer->trackingUrl!=''){
					$tUrldata=explode("?",$offer->trackingUrl);
					$trackingUrl=$tUrldata[0];
					$data['url']=$trackingUrl."?aff_sub={clickid}&source={pid}";
				}else{
					continue;
				}
				if(isset($offer->previewUrl)&&$offer->previewUrl!=''){
					$data['url_preview']=$offer->previewUrl;
				}else{
					continue;
				}
				if(isset($offer->countries)&&count($offer->countries)>0){
					foreach($offer->countries as $i=>$country){
						$data['countries[{'.$i.'}]']=$country;
					}
				}
				if(isset($offer->description)&&$offer->description!=''){
					$data['description']=$offer->description;
				}						
				if(isset($offer->payout)&&$offer->payout!='')
				{
					if(isset($offer->countries)&&count($offer->countries)>0){	
						foreach($offer->countries as $i=>$country){
							$data['payments[0][countries][{'.$i.'}]']=$country;
						}
					}
					if(isset($offer->device)&&count($offer->device)>0){	
						foreach($offer->device as $i=>$dvice){
							if($dvice=='iPad'){
								$dvice='tablet';
							}
							if($dvice=='iPhone'){
								$dvice='mobile';
							}
							$data['payments[0][devices][{'.$i.'}]']=$dvice;
						}
					}
					if(isset($offer->os)&&count($offer->os)>0){	
						foreach($offer->os as $i=>$oss){
							$data['payments[0][os][{'.$i.'}]']=$oss;
						}
					}
					$revenueper=(float)(($offer->payout*20)/100);
					$revenue=round((float)($offer->payout-$revenueper),2);
					$data['payments[0][total]']=$offer->payout;
					$data['payments[0][revenue]']=$revenue;
					if(isset($offer->currency)&&$offer->currency!=''){	
						$data['payments[0][currency]']=$offer->currency;
					}
					$data['payments[0][type]']='fixed';
				}
				if(isset($offer->dailyCap)&&$offer->dailyCap!=''){
					$data['daily_cap']=$offer->dailyCap;
				}	
								
				$data['send_emails']=1;
				$data['privacy']='protected';
				$data['advertiser']=$advertiserId;
				
				$oldOffer=$this->checkExistingOffer($offer->id,$advertiserId);
				array_push($allOffersFromAdvertiser,$offer->id);
				if(!$oldOffer['isexist']){
					$headerExOffer = Array(); 
					$headerExOffer[0] =  "Content-type: application/json";
						$sendData['offer']=$offer;
						$sendData['offerDataTosend']=$data;
						$sendData['addedsourceId']=$sourceId;
					
					$chExOffer = curl_init(); 
					curl_setopt($chExOffer, CURLOPT_URL, "http://rslinfotech.in/Advertivioffers/ApitoolArtOfClick"); 
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
					if(($offer->status)!=$oldOffer['oldOfferData']['status']){
						$changeCount++;
					}
					if(urlencode($offer->name)!=$oldOffer['oldOfferData']['title']){
						$changeCount++;
					}
					if(($offer->dailyCap)!=$oldOffer['oldOfferData']['daily_cap']){
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
				if($addcount>20||$updatecount>200){
					break;
				}	
			}
			$this->db->close();
						$this->db->initialize();
			if(count($offerTolocalForEdit)>0){
				$updatedTolocal=$this->Api_Art_Of_Click_database->updateTolocalDb($offerTolocalForEdit,$advertiserId);
			}
			if(count($deletedOffersFromAffise)>0){
				$updatedTolocalAffiseDelete=$this->Api_Art_Of_Click_database->updateTolocalDbAffiseDelete($deletedOffersFromAffise,$advertiserId);
			}	
			if(count($allOffersFromAdvertiser)>0){
				//$deletedFromLocal=$this->Api_Art_Of_Click_database->deleteFromLocal($allOffersFromAdvertiser);
				$extraOnLocal=$this->Api_Art_Of_Click_database->getExtraFromLocal($allOffersFromAdvertiser,$advertiserId);
				if(!EMPTY($extraOnLocal)&&count($extraOnLocal)>0){
					$offerTolocalForEditStatus=array();
					$deletedOffersFromAffise=array();
					foreach($extraOnLocal as $extraOfferOnLocal){
						$oldAffiseOfferId=$extraOfferOnLocal->affise_offer_id;
						$updateOfferRequestFields='';
						$updateOfferRequestFields=$updateOfferRequestFields."&status=stopped";
						$resultUpdateStatusAffise=$this->updateOfferToAffise($updateOfferRequestFields,$oldAffiseOfferId);
						if($resultUpdateStatusAffise->status==1){	
							array_push($offerTolocalForEditStatus,$extraOfferOnLocal->id);
						}else if($resultUpdateStatusAffise->error == 'Unknown offer id'){
							array_push($deletedOffersFromAffise,$extraOfferOnLocal->id);
						}
					}
					$this->db->close();
						$this->db->initialize();
					if(count($deletedOffersFromAffise)>0){
						$updatedTolocalAffiseDelete=$this->Api_Art_Of_Click_database->updateTolocalDbAffiseDelete($deletedOffersFromAffise,$advertiserId);
					}
					if(count($offerTolocalForEditStatus)>0){
						$updatedTolocalEditStatus=$this->Api_Art_Of_Click_database->updateTolocalDbEditStatus($offerTolocalForEditStatus,$advertiserId);
					}
				}
			}
			$this->Api_Art_Of_Click_database->updateDemandSource_lastUpdate($sourceId);
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
