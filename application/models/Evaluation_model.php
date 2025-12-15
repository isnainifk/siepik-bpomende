<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Evaluation_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	// Get competency development by ID and evaluator
	public function get_competency($id, $evaluator_id)
	{
		$this->db->select("c.*, t.name AS trainee_name");
		$this->db->from("competency_developments c");
		$this->db->join("users t", "c.trainee_id = t.id", "left");
		$this->db->where("c.id", $id);
		$this->db->where("c.evaluator_id", $evaluator_id);
		return $this->db->get()->row();
	}

	// Check if evaluation exists
	public function get_evaluation($competency_id, $evaluator_id)
	{
		$this->db->where("competency_development_id", $competency_id);
		$this->db->where("evaluator_id", $evaluator_id);
		return $this->db->get("evaluations")->row();
	}

	// Insert evaluation
	public function insert_evaluation($competency_id, $evaluator_id, $data)
	{
		$data["competency_development_id"] = $competency_id;
		$data["evaluator_id"] = $evaluator_id;
		$data["evaluated_at"] = date("Y-m-d H:i:s");
		$this->db->insert("evaluations", $data);
		return $this->db->insert_id();
	}
}
