<?php

/**
*  Apartment Model
*/
class Owner_model extends CI_Model
{
	public $tbl_name;
	public function __construct() {
        parent::__construct();
		$this->tbl_name = "tbl_owners";
    }

	public function getList($param){
		$this->db->select("tbl_users.first_name, tbl_users.last_name, tbl_users.email, tbl_users.mobile, tbl_buildings.name as building_name, tbl_units.unit_name, tbl_owners.id, tbl_owners.building_id, tbl_owners.unit_id, tbl_units.id as unit_id, tbl_owners.user_id");
		$this->db->join("tbl_buildings", "tbl_buildings.id = tbl_owners.building_id", 'left');
		$this->db->join("tbl_units", "tbl_units.id = tbl_owners.unit_id", 'left');
		$this->db->join("tbl_users", "tbl_users.id = tbl_owners.user_id", 'left');

		if(isset($param['building_id'])) {
			$this->db->where('tbl_owners.building_id', $param['building_id']);
		}

		if(isset($param['user_id'])) {
			$this->db->where('tbl_owners.user_id', $param['user_id']);
		}

		if(isset($param['start'])) {
			$this->db->limit($param['limit'], $param['start']);
		}

		if(isset($param['query'])) {
			$this->db->like('tbl_buildings.name', $param['query'], 'both');
			$this->db->or_like('tbl_units.unit_name', $param['query'], 'both');
		}

		$query = $this->db->get($this->tbl_name);
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}else {
			return array();
		}
	}

	public function getListCount($param){
		$this->db->select("count(*) as cnt");
		$this->db->join("tbl_buildings", "tbl_buildings.id = tbl_owners.building_id", 'left');
		$this->db->join("tbl_units", "tbl_units.id = tbl_owners.unit_id", 'left');
		$this->db->join("tbl_users", "tbl_users.id = tbl_owners.user_id", 'left');

		if(isset($param['building_id'])) {
			$this->db->where('tbl_owners.building_id', $param['building_id']);
		}

		if(isset($param['user_id'])) {
			$this->db->where('tbl_owners.user_id', $param['user_id']);
		}

		if(isset($param['query'])) {
			$this->db->like('tbl_buildings.name', $param['query'], 'both');
			$this->db->or_like('tbl_units.unit_name', $param['query'], 'both');
		}

		$query = $this->db->get($this->tbl_name);
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}else {
			return array();
		}
	}

	public function add($param) {
		$dbRet = $this->db->insert($this->tbl_name, $param);
		if (!$dbRet) {
			return false;
		} else {
			return true;
		}
	}

	public function update($param)
	{
		$this->db->set($param);
		$this->db->where('id', $param['id']);
		$this->db->update($this->tbl_name);
		if($this->db->affected_rows() > 0)
			return true;
		else
			return false;
	}

	public function delete($param)
	{
		$this->db->where('id', $param['id']);
		$this->db->delete($this->tbl_name);
		if($this->db->affected_rows() > 0)
			return true;
		else
			return false;
	}

	public function getUserAndToken($param) {
		$this->db->select('tbl_tokens.token, tbl_owners.*');
		$this->db->join("tbl_tokens", "tbl_tokens.user_id = tbl_owners.user_id", 'left');

		if($param['building_id'] != 0) {
            if($param['unit_id'] == 0) {
            	$this->db->where('tbl_owners.building_id', $param['building_id']);
            } else {
            	$this->db->where('tbl_owners.building_id', $param['building_id']);
            	$this->db->where('tbl_owners.unit_id', $param['unit_id']);
            }
        }
        
        $query = $this->db->get($this->tbl_name);
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}else {
			return array();
		}
	}
}