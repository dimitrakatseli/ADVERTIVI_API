<?php
Class Adevrtiser_Database extends CI_Model {

	public function get_advertisers($advertiserId='') {
		
		
		$this->db->select('*');
		$this->db->from('tbl_advertiser');
		if($advertiserId!=''){
			
			$this->db->where("advertiserId=".$advertiserId);
			}
		$query = $this->db->get();
		if ($query->num_rows()>0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	// Insert demand_source data in database
	public function add_advertiser($data) {
		
			$this->db->insert('tbl_advertiser', $data);
			if ($this->db->affected_rows() > 0) {
				return true;
			} else {
			return false;
			}
	}
	public function edit_advertiser($advertiserid,$data) 
		{
			// Query to update data in database
			$this->db->where('advertiserId', $advertiserid);
			$this->db->update('tbl_advertiser', $data);

			if ($this->db->affected_rows() >= 0) 
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		public function delete_advertiser($advertiserId) 
		{
			// Query to update data in database
			$this->db->where('advertiserId', $advertiserId);
			$this->db->delete('tbl_advertiser');
			
		
			if ($this->db->affected_rows() > 0) 
			{
				return true;
			} 
			else 
			{
				return false;
			}
		}

}

?>