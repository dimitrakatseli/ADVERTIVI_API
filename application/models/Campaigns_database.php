<?php
Class Campaigns_Database extends CI_Model {

	public function get_demand_source_types() {
		$condition = "is_Active =0";
		$this->db->select('*');
		$this->db->from('tbl_demand_source_type');
		$this->db->where($condition);
		$query = $this->db->get();
		if ($query->num_rows()>0) {
			return $query->result();
		} else {
			return false;
		}
	}
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
	// Insert demand_source data in database
	public function add_demand_source($data) {
		$condition = "demand_source_title =" . "'" . $data['demand_source_title'] . "'";
		$this->db->select('*');
		$this->db->from('tbl_demand_sources');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$this->db->insert('tbl_demand_sources', $data);
			if ($this->db->affected_rows() > 0) {
				return true;
			}
		} else {
			return false;
		}
	}
}

?>