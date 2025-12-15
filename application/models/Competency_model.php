<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Competency_model extends CI_Model
{
	private $table = "competency_developments";

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Get data BELUM dinilai (competency_developments without evaluations)
	 */
	public function get_belum_dinilai(
		$limit = 10,
		$offset = 0,
		$evaluator_id = null,
	) {
		$params = [$limit, $offset];
		$sql = "
        SELECT
            c.*,
            t.name AS trainee_name,
            e.name AS evaluator_name
        FROM competency_developments c
        LEFT JOIN users t ON c.trainee_id = t.id
        LEFT JOIN users e ON c.evaluator_id = e.id
        WHERE c.id NOT IN (
            SELECT competency_development_id FROM evaluations
        )
    ";

		// Optional evaluator filter
		if ($evaluator_id !== null) {
			$sql .= " AND c.evaluator_id = ? ";
			// Add evaluator_id before limit & offset
			array_unshift($params, $evaluator_id);
		}

		$sql .= " ORDER BY c.training_start DESC LIMIT ? OFFSET ?";

		$query = $this->db->query($sql, $params);
		return $query->result();
	}

	/**
	 * Count total BELUM dinilai
	 */
	public function count_belum_dinilai()
	{
		$sql = "
			SELECT COUNT(*) AS total
			FROM competency_developments c
			WHERE c.id NOT IN (
				SELECT competency_development_id
				FROM evaluations
			)
		";
		return $this->db->query($sql)->row()->total;
	}

	public function count_belum_dinilai_all($evaluator_id = null)
	{
		$params = [];
		$sql = "
        SELECT COUNT(*) AS total
        FROM competency_developments
        WHERE id NOT IN (
            SELECT competency_development_id FROM evaluations
        )
    ";

		// Optional evaluator filter
		if ($evaluator_id !== null) {
			$sql .= " AND evaluator_id = ? ";
			$params[] = $evaluator_id;
		}

		$query = $this->db->query($sql, $params);
		return $query->row()->total;
	}

	/**
	 * Count total SUDAH dinilai
	 */
	public function count_sudah_dinilai($evaluator_id = null)
	{
		$sql =
			"SELECT COUNT(DISTINCT competency_development_id) AS total FROM evaluations";
		$params = [];

		if ($evaluator_id !== null) {
			$sql .= " WHERE evaluator_id = ?";
			$params[] = $evaluator_id;
		}

		return $this->db->query($sql, $params)->row()->total;
	}

	/**
	 * Count total pengembangan kompetensi
	 */
	public function count_total_pengembangan($evaluator_id = null)
	{
		if ($evaluator_id !== null) {
			$this->db->where("evaluator_id", $evaluator_id);
		}
		return $this->db->count_all_results("competency_developments");
	}

	// ===========================
	// HITUNG TOTAL DATA (COUNT)
	// ===========================
	public function count_all($year = 0, $status = "")
	{
		$this->db->from("competency_developments c");

		if ($year > 0) {
			$this->db->where("YEAR(c.training_start)", $year);
		}

		if ($status == "sudah") {
			$this->db->where(
				"EXISTS (SELECT 1 FROM evaluations ev WHERE ev.competency_development_id = c.id)",
				null,
				false,
			);
		} elseif ($status == "belum") {
			$this->db->where(
				"NOT EXISTS (SELECT 1 FROM evaluations ev WHERE ev.competency_development_id = c.id)",
				null,
				false,
			);
		}

		return $this->db->count_all_results();
	}

	// ===========================
	// GET LIST + PAGINATION
	// ===========================
	public function get_list(
		$limit,
		$offset,
		$year = 0,
		$status = "",
		$evaluator_id = null,
	) {
		// ========== BASE QUERY ==========
		$this->db->select(
			"
        c.*,
        t.name AS trainee_name,
        e.name AS evaluator_name,
        (SELECT COUNT(*) FROM evaluations ev
            WHERE ev.competency_development_id = c.id
        ) AS sudah_dinilai
    ",
			false,
		);

		$this->db->from("competency_developments c");
		$this->db->join("users t", "c.trainee_id = t.id", "left");
		$this->db->join("users e", "c.evaluator_id = e.id", "left");

		// ---- Filter Tahun ----
		if ($year > 0) {
			$this->db->where("YEAR(c.training_start)", $year);
		}

		// ---- Filter Status ----
		if ($status == "sudah") {
			$this->db->where(
				"EXISTS (
            SELECT 1 FROM evaluations ev
            WHERE ev.competency_development_id = c.id
        )",
				null,
				false,
			);
		} elseif ($status == "belum") {
			$this->db->where(
				"NOT EXISTS (
            SELECT 1 FROM evaluations ev
            WHERE ev.competency_development_id = c.id
        )",
				null,
				false,
			);
		}

		// ---- Filter by evaluator_id if provided ----
		if (!is_null($evaluator_id)) {
			$this->db->where("c.evaluator_id", $evaluator_id);
		}

		// Clone query for total rows
		$count_query = clone $this->db;
		$total = $count_query->count_all_results();

		// ========== Query data (with limit) ==========
		$this->db->order_by("c.training_start", "DESC");
		$this->db->limit($limit, $offset);

		$data = $this->db->get()->result();

		return [
			"data" => $data,
			"total" => $total,
		];
	}

	// ===========================
	// GET DISTINCT YEAR
	// ===========================
	public function get_years()
	{
		return $this->db
			->select("DISTINCT YEAR(training_start) AS y", false)
			->from("competency_developments")
			->order_by("y", "DESC")
			->get()
			->result();
	}

	public function find($id)
	{
		return $this->db->get_where($this->table, ["id" => $id])->row();
	}

	public function insert_one($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function insert($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function update_one($id, $data)
	{
		return $this->db->where("id", $id)->update($this->table, $data);
	}

	public function delete($id)
	{
		return $this->db->delete("competency_developments", ["id" => $id]);
	}

	public function get_detail_for_pdf($id)
	{
		$this->db->select("
        c.*,
        e.name AS evaluator_name,
        e.jabatan AS evaluator_jabatan,
        e.unit_kerja AS evaluator_unit,
        t.name AS trainee_name,
        t.jabatan AS trainee_jabatan,
        t.unit_kerja AS trainee_unit
    ");
		$this->db->from("competency_developments c");
		$this->db->join("users e", "c.evaluator_id = e.id", "left");
		$this->db->join("users t", "c.trainee_id = t.id", "left");
		$this->db->where("c.id", $id);
		$row = $this->db->get()->row();

		if (!$row) {
			return null;
		}

		// Get evaluation
		$eval = $this->db
			->get_where("evaluations", [
				"competency_development_id" => $id,
			])
			->row();

		$row->evaluation = $eval;
		return $row;
	}
}
