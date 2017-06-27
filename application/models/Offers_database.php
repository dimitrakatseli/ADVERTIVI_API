<?php
Class Offers_Database extends CI_Model {

	public function get_demand_sources() {
		
		//$condition = "is_Active =0";
		$this->db->select('*');
		$this->db->from('tbl_demand_sources');
	//	$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows()>0) {
			return $query->result();
		} else {
			return false;
		}
	}
	public function get_demand_offers() {
		//$condition = "is_Active =0";
		$this->db->select('offer.*,demand_sources.demand_source_title');
		$this->db->from("tbl_offer offer");
		$this->db->join('tbl_demand_sources demand_sources', 'demand_sources.demand_source_id = offer.demand_source'); 
		//$this->db->from('tbl_offer');
	//	$this->db->where($condition);
		$query = $this->db->get();
	
		if ($query->num_rows()>0) {
			return $query->result();
		} else {
			return false;
		}
	}

}

?>