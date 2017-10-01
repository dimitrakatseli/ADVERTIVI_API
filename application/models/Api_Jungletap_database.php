<?php
Class Api_Jungletap_Database extends CI_Model {

	public function get_demand_sources($demandSourceId) {
		//$condition = "is_Active =0";
		$this->db->select('*');
		$this->db->from('tbl_demand_sources');
		$this->db->join('tbl_advertiser','tbl_advertiser.advertiserId=tbl_demand_sources.advertiser');
		$this->db->where("tbl_demand_sources.demand_source_id=".$demandSourceId." and status=1");
		$query = $this->db->get();
		if ($query->num_rows()>0) {
			return $query->result();
		} else {
			return false;
		}
	}
	public function checkExistingOffer_db($advertOfferId,$advertiserId) {
		$oldRecord=array();
		$condition = "advertiser_offer_id =".$advertOfferId." and advertiser='".$advertiserId."' and deletedFromAffise!=1";
		$this->db->select('*');
		$this->db->from('tbl_offer');
		$this->db->where($condition);
		$query = $this->db->get();
		
		if ($query->num_rows()> 0) {
			$oldRecord['isexist']=true;
			$oldRecord['oldOfferData']=$query->row_array();
		}else{
			$oldRecord['isexist']=false;
		}	
		return $oldRecord;
	}
	public function getCategories_db() {
		$categories=array();
	
		$this->db->select('category_id,category_title');
		$this->db->from('tbl_categories');
		$query = $this->db->get();
		
		if ($query->num_rows()> 0) {
			foreach($query->result_array() as $cat){
				$categories[$cat['category_id']]=$cat['category_title'];
			}
			return $categories;
		}else{
			return false;
		}	
		
	}
	public function getExistingOffer_db($advertiserId) {
		
		$condition = "advertiser='".$advertiserId."'";
		$this->db->select('id,affise_offer_id,advertiser_offer_id,deletedFromAffise,status,title,daily_cap,description,payments,approved');
		$this->db->from('tbl_offer');
		$this->db->where($condition);
		$query = $this->db->get();
	
		if ($query->num_rows()> 0) {
			return $query->result();
		}else{
			return false;
		}	
		
	}
	// Insert demand_source data in database
	public function addTolocalDb($sourceId,$offerTolocalAdd,$advertiserId) {
		
		$queryInsetTolocatDb = 'INSERT INTO tbl_offer(affise_offer_id, advertiser_offer_id,demand_source, title, advertiser, url, url_preview, trafficback_url, domain_url, description, stopDate, countries, creativeFiles, sources, logo, status, freshness, privacy, is_top, payments, partner_payments, total_cap, total_cap_start_date, daily_cap, daily_cap_partner, daily_cap_partners, landings, strictly_country, strictly_os, hold_period, categories, action_status_url, notes, allowed_ip, allow_deeplink, hide_referer, start_at, send_emails, is_redirect_overcap, hide_paments, sub_account_1, sub_account_2, sub_account_1_except, sub_account_2_except,approved) VALUES';
		$Addvals='';
		foreach($offerTolocalAdd as $offerTolocal){
			
			if($Addvals!=''){
				$Addvals=$Addvals.',('.$offerTolocal->affiseofferid.','.$offerTolocal->ID.','.$sourceId.',"'.urlencode($offerTolocal->Name).'","'.$offerTolocal->advertiser.'","'.urlencode($offerTolocal->Tracking_url).'","'.urlencode($offerTolocal->Preview_url).'","","","'.urlencode($offerTolocal->Description).'","","'.($offerTolocal->Countries).'","","Art Of Click","","'.$offerTolocal->Status.'","","","","'.$offerTolocal->Payout.'","","","","'.($offerTolocal->Daily_cap).'","","","","","'.($offerTolocal->Os).'","","","","","","","","","","","","","","","",'.$offerTolocal->Approved.')';
			}else{
				$Addvals=$Addvals.'('.$offerTolocal->affiseofferid.','.$offerTolocal->ID.','.$sourceId.',"'.urlencode($offerTolocal->Name).'","'.$offerTolocal->advertiser.'","'.urlencode($offerTolocal->Tracking_url).'","'.urlencode($offerTolocal->Preview_url).'","","","'.urlencode($offerTolocal->Description).'","","'.($offerTolocal->Countries).'","","Art Of Click","","'.$offerTolocal->Status.'","","","","'.$offerTolocal->Payout.'","","","","'.($offerTolocal->Daily_cap).'","","","","","'.($offerTolocal->Os).'","","","","","","","","","","","","","","","",'.$offerTolocal->Approved.')';
			}
		}
		$queryInsetTolocatDb=$queryInsetTolocatDb.$Addvals;
		
		$result = $this->db->query($queryInsetTolocatDb);
		return $result;
	}
	public function updateTolocalDb($offerTolocalForEdit,$advertiserId) {
		
		$queryInsetTolocatDbEdit = 'INSERT INTO tbl_offer(id,affise_offer_id, advertiser_offer_id, title, advertiser, url, url_preview, trafficback_url, domain_url, description, stopDate, countries, creativeFiles, sources, logo, status, freshness, privacy, is_top, payments, partner_payments, total_cap, total_cap_start_date, daily_cap, daily_cap_partner, daily_cap_partners, landings, strictly_country, strictly_os, hold_period, categories, action_status_url, notes, allowed_ip, allow_deeplink, hide_referer, start_at, send_emails, is_redirect_overcap, hide_paments, sub_account_1, sub_account_2, sub_account_1_except, sub_account_2_except,approved) VALUES';
		$Addvals='';
		$queryUpdateTolocatDb=' ON DUPLICATE KEY UPDATE ';
		$Updatevals='';
		foreach($offerTolocalForEdit as $offerTolocalEdit){
			if($Addvals!=''){
				$Addvals=$Addvals.',('.$offerTolocalEdit->oldid.','.$offerTolocalEdit->affiseofferid.','.$offerTolocalEdit->ID.',"'.urlencode($offerTolocalEdit->Name).'","'.$offerTolocalEdit->advertiser.'","'.urlencode($offerTolocalEdit->Tracking_url).'","'.urlencode($offerTolocalEdit->Preview_url).'","","","'.urlencode($offerTolocalEdit->Description).'","","'.($offerTolocalEdit->Countries).'","","Art Of Click","","'.$offerTolocalEdit->Status.'","","","","'.$offerTolocalEdit->Payout.'","","","","'.($offerTolocalEdit->Daily_cap).'","","","","","'.($offerTolocalEdit->Os).'","","","","","","","","","","","","","","","",'.$offerTolocalEdit->Approved.')';
			}else{
				$Addvals=$Addvals.'('.$offerTolocalEdit->oldid.','.$offerTolocalEdit->affiseofferid.','.$offerTolocalEdit->ID.',"'.urlencode($offerTolocalEdit->Name).'","'.$offerTolocalEdit->advertiser.'","'.urlencode($offerTolocalEdit->Tracking_url).'","'.urlencode($offerTolocalEdit->Preview_url).'","","","'.urlencode($offerTolocalEdit->Description).'","","'.($offerTolocalEdit->Countries).'","","Art Of Click","","'.$offerTolocalEdit->Status.'","","","","'.$offerTolocalEdit->Payout.'","","","","'.($offerTolocalEdit->Daily_cap).'","","","","","'.($offerTolocalEdit->Os).'","","","","","","","","","","","","","","","",'.$offerTolocalEdit->Approved.')';
			}
		}
		$Updatevals=$Updatevals.'id=values(id),affise_offer_id=values(affise_offer_id),advertiser_offer_id=values(advertiser_offer_id),title=values(title),advertiser=values(advertiser),url=values(url),url_preview=values(url_preview),trafficback_url=values(trafficback_url),domain_url=values(domain_url),description=values(description),stopDate=values(stopDate),countries=values(countries),creativeFiles=values(creativeFiles),logo=values(logo),sources=values(sources),status=values(status),freshness=values(freshness),privacy=values(privacy),payments=values(payments),partner_payments=values(partner_payments),is_top=values(is_top),total_cap=values(total_cap), total_cap_start_date=values(total_cap_start_date),daily_cap=values(daily_cap),daily_cap_partner=values(daily_cap_partner),daily_cap_partners=values(daily_cap_partners), landings=values(landings), strictly_country=values(strictly_country), strictly_os=values(strictly_os), hold_period=values(hold_period), categories=values(categories), action_status_url=values(action_status_url), notes=values(notes), allowed_ip=values(allowed_ip), allow_deeplink=values(allow_deeplink), hide_referer=values(hide_referer), start_at=values(start_at), send_emails=values(send_emails), is_redirect_overcap=values(is_redirect_overcap), hide_paments=values(hide_paments), sub_account_1=values(sub_account_1), sub_account_2=values(sub_account_2), sub_account_1_except=values(sub_account_1_except), sub_account_2_except=values(sub_account_2_except),approved=values(approved)';
		$queryInsetTolocatDbEdit=$queryInsetTolocatDbEdit.$Addvals.$queryUpdateTolocatDb.$Updatevals;
		$resultUpdate = $this->db->query($queryInsetTolocatDbEdit);
		return $resultUpdate;
	}
		public function updateTolocalDbEditStatus($offerTolocalForEditStatus,$advertiserId) {
		
		$queryToUpdateStatusOnLocal = "update tbl_offer set status='stopped' where id in(".implode(",",$offerTolocalForEditStatus).") and advertiser='".$advertiserId."'";
		
		$resultUpdateForstatus = $this->db->query($queryToUpdateStatusOnLocal);
		return $resultUpdateForstatus;
	}
	
	public function updateTolocalDbAffiseDelete($deletedOffersFromAffise,$advertiserId) {
		
		$queryTodleteFromLocal = 'update tbl_offer set deletedFromAffise=1 where id in('.implode(",",$deletedOffersFromAffise).') and advertiser="'.$advertiserId.'"';
		$resultUpdateForDelted = $this->db->query($queryTodleteFromLocal);
		return $resultUpdateForDelted;
	}
	public function deleteFromLocal($allOffersFromAdvertiser,$advertiserId) {
		
		$queryTodeleteFromLocal = 'delete from tbl_offer  where advertiser_offer_id NOT IN('.implode(",",$allOffersFromAdvertiser).') and advertiser="'.$advertiserId.'"';
		$resultToDeltedFromLocal = $this->db->query($queryTodeleteFromLocal);
		return $resultToDeltedFromLocal;
	}
	public function getExtraFromLocal($allOffersFromAdvertiser,$advertiserId) {
		$condition="advertiser_offer_id NOT IN(".implode(",",$allOffersFromAdvertiser).") and status='active' and advertiser='".$advertiserId."'";
		$this->db->select('*');
		$this->db->from('tbl_offer');
		$this->db->where($condition);
		$query = $this->db->get();
	
		if ($query->num_rows()>0) {
			return $query->result();
		} else {
			return false;
		}
		
	}
	public function updateDemandSource_lastUpdate($sourceId){
		date_default_timezone_set('Europe/Berlin');
		$now = date('Y-m-d H:i:s');
		$queryTolastupdate = "update tbl_demand_sources set last_update='".$now."' where demand_source_id=".$sourceId;
		$resultlastupdate = $this->db->query($queryTolastupdate);
		return $resultlastupdate;
	}
}

?>