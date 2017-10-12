<?php

ini_set('max_execution_time', 0);
ini_set('mysql.connect_timeout', 1000);
ini_set('default_socket_timeout', 1000);
defined('BASEPATH') OR exit('No direct script access allowed');

        const MOBALOO_ID = 15;
        const API_KEY_PARAM = "?apikey=";

class ApiToolMobaloo extends CI_Controller {

    public $ExistingOffers = array();
    public $All_Os_Versions = array();

    public function __construct() {
        parent::__construct();
        $this->load->model('Api_Mobaloo_database');
    }

    public function index() {
        $demand_sources = $this->Api_Mobaloo_database->get_demand_sources(MOBALOO_ID);
        if (isset($demand_sources[0]) && $demand_sources[0]->status == 1) {
            $this->art_of_click($demand_sources[0]->demand_source_id, $demand_sources[0]->affise_adevrtiser_id, $demand_sources[0]->api_key, $demand_sources[0]->api_url);
        }
    }

    public function art_of_click($sourceId, $advertiserId, $api_key, $api_url) {
        $_response = '';

        //echo join(' ', array($sourceId, $advertiserId, $api_key, $api_url));
        // ADVERTISR API KEY
        $affise_api_key = "cc55b8e0c20027cbba6cc8fbd3cf7801642bc6d6";
        //HEADER
        $header = Array();
        $header[0] = "Content-Type: multipart/form-data";
        $header[1] = "Accept: application/json";

        $api_call_url = $api_url . API_KEY_PARAM . $api_key . "&recommended=1";
        //echo $api_call_url;
        //CURL for get offers from advertiser  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_call_url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30000);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30000);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $ch_result = curl_exec($ch);
        if ($ch_result != false) {
            $_response = json_decode($ch_result);
        }
        curl_close($ch);

        echo count($_response->data);

        $propcnt = 0;
        $addcount = 0;
        $updatecount = 0;
        if ($_response != '') {
            $offerTolocalAdd = array();
            $offerTolocalForEdit = array();
            $deletedOffersFromAffise = array();
            $allOffersFromAdvertiser = array();
            $allValidOffersFromAdvertiser = array();
            //Get all existing offers in any status on local from this advertiser
            $this->ExistingOffers = $this->Api_Mobaloo_database->getExistingOffer_db($advertiserId);
            $this->All_Os_Versions = $this->Api_Mobaloo_database->getOsVersions_db();
            foreach ($_response->data as $offer) {
                array_push($allOffersFromAdvertiser, $offer->id);
                 if(!(isset($offer->approved))||!($offer->approved==1)){
                  continue;
                  }
                  if(!(isset($offer->status))||($offer->status!='active')){
                  continue;
                  }
                  if(!(isset($offer->name))||$offer->name==''){
                  continue;
                  }
                  //$clogofile = new CURLFile($filePath, filetype($filePath), basename($filePath));
                  //$data['logo']=$clogofile;
                  if(!(isset($offer->trackingUrl))||$offer->trackingUrl==''){
                  continue;
                  }
                  if(!(isset($offer->previewUrl))||$offer->previewUrl==''){
                  continue;
                  }
                  array_push($allValidOffersFromAdvertiser,$offer->id); 
            }
            //print_r($allValidOffersFromAdvertiser);
            //echo var_dump($allOffersFromAdvertiser);

            $insert_data = array();
            $insert_affise_data = array();

            foreach ($_response->data as $offer) {
                $propcnt++;
                $data = [];

                if (isset($offer->name) && $offer->name != '') {
                    $data['title'] = $offer->name;
                    $insert_row['title'] = $data['title'];
                    $insert_affise_data['title'] = $data['title'];
                } else {
                    continue;
                }
                if (isset($offer->status) && $offer->status != '') {
                    $data['status'] = $offer->status;
                } else {
                    $data['status'] = 'active';
                }

                $insert_row['status'] = $data['status'];

                //$clogofile = new CURLFile($filePath, filetype($filePath), basename($filePath));
                //$data['logo']=$clogofile;
                if (isset($offer->offer_url) && $offer->offer_url != '') {
                    $data['url'] = $offer->offer_url . "?aff_sub={clickid}&aff_sub2={pid}";
                    $insert_row['url'] = $data['url'];
                    $insert_affise_data['url'] = $data['url'];
                }
                if (isset($offer->preview_url) && $offer->preview_url != '') {
                    $data['url_preview'] = $offer->preview_url;
                    $insert_row['url_preview'] = $data['url_preview'];
                    $insert_affise_data['url_preview'] = $data['url_preview'];
                }
                if (isset($offer->countries) && count($offer->countries) > 0) {

                    $insert_row['countries'] = $offer->countries;
                    // Convert countries string to an array of countries

                    $offer->countries = explode(",", $offer->countries);
                    //$affise_array = $this->toAffiseArray('countries', $offer->countries);
                    //array_merge($insert_affise_data, $affise_array);
                    // $insert_affise_data['countries[{' . $i . '}]'] = $offer->countries;

                    foreach ($offer->countries as $i => $country) {
                        $data['countries[{' . $i . '}]'] = $country;
                        $insert_affise_data['countries[{' . $i . '}]'] = $country;
                    }
                }
                if (isset($offer->description) && $offer->description != '') {
                    $data['description'] = $offer->description;
                    $insert_row['description'] = $data['description'];
                    $insert_affise_data['description'] = $data['description'];
                }
                if (isset($offer->revenue_per_install) && $offer->revenue_per_install != '') {
                    $insert_row['payments'] = $offer->revenue_per_install;
                    $affise_array = $this->toAffiseArray('countries', $offer->countries);
                    $insert_affise_data['payments[0][total]'] = $insert_row['payments'];
                    if (isset($offer->countries) && count($offer->countries) > 0) {
                        foreach ($offer->countries as $i => $country) {
                            $data['payments[0][countries][{' . $i . '}]'] = $country;
                        }
                    }
//                    if (isset($offer->device) && count($offer->device) > 0) {
//                        foreach ($offer->device as $i => $dvice) {
//                            if ($dvice == 'iPad') {
//                                $dvice = 'tablet';
//                            }
//                            if ($dvice == 'iPhone') {
//                                $dvice = 'mobile';
//                            }
//                            $data['payments[0][devices][{' . $i . '}]'] = $dvice;
//                        }
//                    }
                    if (isset($offer->os)) {
                        $data['payments[0][os][{0}]'] = $offer->os;
                    }
                    $revenueper = (float) (($offer->revenue_per_install * 20) / 100);
                    $revenue = round((float) ($offer->revenue_per_install - $revenueper), 2);
                    $data['payments[0][total]'] = $offer->revenue_per_install;
                    $data['payments[0][revenue]'] = $revenue;
                    $insert_affise_data['payments[0][revenue]'] = ($revenue);
                    $insert_affise_data['payments[0][currency]'] = 'USD';
                    $insert_affise_data['payments[0][type]'] = 'fixed';
                    if (isset($offer->currency) && $offer->currency != '') {
                        $data['payments[0][currency]'] = $offer->currency;
                    }
                    $data['payments[0][type]'] = 'fixed';
                }

                /* if (isset($offer->os) && count($offer->os) > 0) {
                  foreach ($offer->os as $i => $oss) {
                  $data['strictly_os[os][{' . $i . '}]'] = strtolower($oss);
                  if (isset($offer->osVersionMinimum) && $offer->osVersionMinimum != '') {
                  $vcnt = 0;
                  foreach ($this->All_Os_Versions as $version) {
                  if ($version['version_name'] >= $offer->osVersionMinimum && strtolower($oss) == strtolower($version['version_os'])) {
                  $data['strictly_os[versions][{' . $vcnt . '}]'] = strtolower($oss) . " " . $version['version_name'];
                  $vcnt++;
                  }
                  }
                  }
                  }
                  } */


                if (isset($offer->conversion_cap) && $offer->conversion_cap != '') {
                    $data['daily_cap'] = $offer->conversion_cap;
                    $insert_row['daily_cap'] = $data['daily_cap'];
                    $insert_affise_data['daily_cap'] = $data['daily_cap'];
                }
                $data['send_emails'] = 1;
                $data['privacy'] = 'protected';
                $data['advertiser'] = $advertiserId;

                $insert_row['send_emails'] = $data['send_emails'];
                $insert_row['privacy'] = $data['privacy'];
                $insert_row['advertiser'] = $data['advertiser'];

                //Check offer exists in local database
                $oldOffer = $this->checkExistingOffer($offer->id, $advertiserId);

                //If offer not exist, add offer
                if (!$oldOffer['isexist']) {

                    /*
                      if (!(isset($offer->approved)) || $offer->approved != 1) {
                      continue;
                      }
                     */
                    if (isset($offer->status) && $offer->status == 'active') {
                        $data['status'] = $offer->status;
                    } else {
                        $data['status'] = 'active';

                        //continue;
                    }
                    if (isset($offer->name) && $offer->name != '') {
                        $data['title'] = $offer->name;
                    } else {
                        continue;
                    }
                    //$clogofile = new CURLFile($filePath, filetype($filePath), basename($filePath));
                    //$data['logo']=$clogofile;
                    if (isset($offer->offer_url) && $offer->offer_url != '') {
                        $data['url'] = $offer->offer_url . "?aff_sub={clickid}&aff_sub2={pid}";
                    } else {
                        continue;
                    }
                    if (isset($offer->preview_url) && $offer->preview_url != '') {
                        $data['url_preview'] = $offer->preview_url;
                    } else {
                        continue;
                    }

                    //Add offer only if Approved,Active,With Name,With Tracking Url and Preview Url

                    $headerExOffer = Array();
                    $headerExOffer[0] = "Content-type: application/json";
                    $sendData['offer'] = $offer;
                    $sendData['offerDataTosend'] = $data;
                    $sendData['addedsourceId'] = $sourceId;

                    $chExOffer = curl_init();
                    curl_setopt($chExOffer, CURLOPT_URL, "http://rslinfotech.in/Advertivioffers/ApiToolMobaloo");
                    curl_setopt($chExOffer, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
                    curl_setopt($chExOffer, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($chExOffer, CURLOPT_POST, 1);
                    curl_setopt($chExOffer, CURLOPT_POSTFIELDS, json_encode($sendData));
                    curl_setopt($chExOffer, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($chExOffer, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($chExOffer, CURLOPT_HTTPHEADER, $headerExOffer);
                    $offerAddResult = curl_exec($chExOffer);
                    if ($offerAddResult == "1") {
                        $addcount++;
                    }
                    curl_close($chExOffer);
                } else {
                    $localOfferId = $oldOffer['oldOfferData']['id'];
                    $oldAffiseOfferId = $oldOffer['oldOfferData']['affise_offer_id'];
                    $deltedFromAffise = $oldOffer['oldOfferData']['deletedFromAffise'];
                    $changeCount = 0;
                    if ($offer->approved != $oldOffer['oldOfferData']['approved']) {
                        if (!(isset($offer->approved)) || $offer->approved != 1) {
                            $data['status'] = 'stopped';
                        }
                        $changeCount++;
                    }
                    if (($offer->status) != $oldOffer['oldOfferData']['status']) {
                        if ($offer->status == 'inactive') {
                            $data['status'] = 'suspended';
                        }
                        $changeCount++;
                    }
                    if (urlencode($offer->name) != $oldOffer['oldOfferData']['title']) {
                        $changeCount++;
                    }
                    if (($offer->conversion_cap) != $oldOffer['oldOfferData']['daily_cap']) {
                        $changeCount++;
                    }
                    if (urlencode($offer->description) != $oldOffer['oldOfferData']['description']) {
                        $changeCount++;
                    }
                    if ($offer->revenue_per_install != $oldOffer['oldOfferData']['payments']) {
                        $changeCount++;
                    }
                    if (!$deltedFromAffise && $changeCount > 0) {
                        $updatecount++;
                        $result = $this->updateOfferToAffise($data, $oldAffiseOfferId);
                        if ($result->status == 1) {

                            $offer->oldid = $localOfferId;
                            $offer->affiseofferid = $result->offer->id;
                            $offer->advertiser = $result->offer->advertiser;
                            array_push($offerTolocalForEdit, $offer);
                        } else if ($result->error == 'Unknown offer id') {
                            array_push($deletedOffersFromAffise, $localOfferId);
                        }
                    }
                }
                if ($addcount > 20 || $updatecount > 200) {
                    break;
                }
                $insert_affise_data['advertiser'] = '58e7821113e03b2b618b4f62';
                $insert_row['demand_source'] = MOBALOO_ID;
                $insert_row['approved'] = 1;
                $insert_data[] = $insert_row;

                //echo var_dump($this->sendOfferToAffise($insert_affise_data));
            }


            $this->db->close();
            $this->db->initialize();
            //only the first time
            //$this->db->insert_batch("tbl_offer", $insert_data);




            if (count($offerTolocalForEdit) > 0) {
                $updatedTolocal = $this->Api_Mobaloo_database->updateTolocalDb($offerTolocalForEdit, $advertiserId);
            }
            if (count($deletedOffersFromAffise) > 0) {
                $updatedTolocalAffiseDelete = $this->Api_Mobaloo_database->updateTolocalDbAffiseDelete($deletedOffersFromAffise, $advertiserId);
            }

            if (count($allOffersFromAdvertiser) > 0) {

                //$deletedFromLocal=$this->Api_Art_Of_Click_database->deleteFromLocal($allOffersFromAdvertiser);
                $extraOnLocal = $this->Api_Mobaloo_database->getExtraFromLocal($allOffersFromAdvertiser, $advertiserId);
                //print_r($extraOnLocal);
                if (!EMPTY($extraOnLocal) && count($extraOnLocal) > 0) {
                    $offerTolocalForEditStatus = array();
                    $deletedOffersFromAffise = array();
                    foreach ($extraOnLocal as $extraOfferOnLocal) {
                        $oldAffiseOfferId = $extraOfferOnLocal->affise_offer_id;
                        $updateOfferRequestFields = [];
                        $updateOfferRequestFields['status'] = 'stopped';
                        $resultUpdateStatusAffise = $this->updateOfferToAffise($updateOfferRequestFields, $oldAffiseOfferId);

                        if ($resultUpdateStatusAffise->status == 1) {
                            array_push($offerTolocalForEditStatus, $extraOfferOnLocal->id);
                        } else if ($resultUpdateStatusAffise->error == 'Unknown offer id') {
                            array_push($deletedOffersFromAffise, $extraOfferOnLocal->id);
                        }
                    }
                    $this->db->close();
                    $this->db->initialize();
                    if (count($deletedOffersFromAffise) > 0) {
                        $updatedTolocalAffiseDelete = $this->Api_Mobaloo_database->updateTolocalDbAffiseDelete($deletedOffersFromAffise, $advertiserId);
                    }
                    if (count($offerTolocalForEditStatus) > 0) {
                        $updatedTolocalEditStatus = $this->Api_Mobaloo_database->updateTolocalDbEditStatus($offerTolocalForEditStatus, $advertiserId);
                    }
                }
            }

            // echo "HERE";

            $this->Api_Mobaloo_database->updateDemandSource_lastUpdate($sourceId);
        }
    }

    public function updateOfferToAffise($OfferRequest, $offerId) {
        //API URL
        $affise_GETOFFER_api_url = "http://api.advertivi.com/3.0/offer";

        //HEADER
        $header = Array();
        $header[0] = "Content-type: multipart/form-data";
        //$header[1]="Accept: application/json";
        $header[1] = "API-Key:cc55b8e0c20027cbba6cc8fbd3cf7801642bc6d6";
        $uData = $OfferRequest;

        $ch_affise = curl_init();
        curl_setopt($ch_affise, CURLOPT_URL, "http://api.advertivi.com/3.0/admin/offer/" . $offerId);
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
        if ($ch_offer_result_affise === false) {
            return false;
        } else {
            $_response_Offer_affise = json_decode($ch_offer_result_affise);
            return $_response_Offer_affise;
        }
    }

    public function sendOfferToAffise($OfferRequest) {
        ///	print_r($OfferRequest);
        //API URL
        $affise_api_url = "http://api.advertivi.com/3.0/admin/offer";
        //HEADER
        $header = Array();
        $header[0] = "Content-type: multipart/form-data";
        $header[1] = "API-Key:cc55b8e0c20027cbba6cc8fbd3cf7801642bc6d6";


        $uData = $OfferRequest;
        $ch_affise = curl_init();
        curl_setopt($ch_affise, CURLOPT_URL, "http://api.advertivi.com/3.0/admin/offer");
        curl_setopt($ch_affise, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch_affise, CURLOPT_POST, 1);
        curl_setopt($ch_affise, CURLOPT_POSTFIELDS, $uData);
        curl_setopt($ch_affise, CURLOPT_RETURNTRANSFER, true);

        $ch_result_affise = curl_exec($ch_affise);

        curl_close($ch_affise);

        if ($ch_result_affise === false) {
            return false;
        } else {
            $_response_affise = json_decode($ch_result_affise);

            return $_response_affise;
        }
    }

    public function checkExistingOffer($advertOfferId, $advertiser) {
        $oldRecord = array();
        $oldRecord['isexist'] = false;
        $oldoffer = array();
        if (($this->ExistingOffers) && !(empty($this->ExistingOffers)) && count($this->ExistingOffers) > 0) {
            foreach ($this->ExistingOffers as $ExistingOffer) {
                if ($ExistingOffer->advertiser_offer_id == $advertOfferId) {
                    $oldRecord['isexist'] = true;
                    $oldRecord['oldOfferData'] = (array) $ExistingOffer;
                    $found = 1;
                    break;
                }
            }
        }
        return $oldRecord;
    }

    public function toAffiseArray($name, $values) {
        $affise_array = array();
        for ($i = 0; $i < count($values); $i++) {
            $affise_array[$name . '[' . $i . ']'] = $values[$i];
        }
        return $affise_array;
    }

}
