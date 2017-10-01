<?php
Class Affise_database extends CI_Model {

	public function update_categories($advertiserId='') {
		
		
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
	public function updateTolocalDb($categoriesTolocalForEdit) {
		
		$queryInsetTolocatDb= 'INSERT INTO tbl_categories(category_id, category_title) VALUES';
		$Addvals='';
		$queryUpdateTolocatDb=' ON DUPLICATE KEY UPDATE ';
		$Updatevals='';
		foreach($categoriesTolocalForEdit as $categoryTolocalForEdit){
			if($Addvals!=''){
				$Addvals=$Addvals.",('".$categoryTolocalForEdit->id."','".$categoryTolocalForEdit->title."')";
			}else{
				$Addvals=$Addvals."('".$categoryTolocalForEdit->id."','".$categoryTolocalForEdit->title."')";
			}
		}
		$Updatevals=$Updatevals.'id=values(id),category_id=values(category_id),category_title=values(category_title)';
		$queryInsetTolocatDb=$queryInsetTolocatDb.$Addvals.$queryUpdateTolocatDb.$Updatevals;
		$resultUpdate = $this->db->query($queryInsetTolocatDb);
		return $resultUpdate;
	}
	



}

?>