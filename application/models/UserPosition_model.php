<?php

class UserPosition_model extends CI_Model
{
	public function get_by_user($uid)
	{
		return $this->db
			->where("user_id", $uid)
			->get("user_positions")
			->result();
	}

	public function insert_many($uid, $jabs)
	{
		foreach ($jabs as $j) {
			if (trim($j) != "") {
				$this->db->insert("user_positions", [
					"user_id" => $uid,
					"jabatan" => $j,
				]);
			}
		}
	}

	public function replace_positions($uid, $jabs)
	{
		// delete old
		$this->db->where("user_id", $uid)->delete("user_positions");

		// add new
		$this->insert_many($uid, $jabs);
	}
}
