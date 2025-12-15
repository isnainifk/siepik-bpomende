<?php
class User_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function login($userid, $password)
	{
		$this->db->where("userid", $userid);
		$this->db->where("password", md5($password));
		return $this->db->get("users")->row();
	}

	public function all_staff()
	{
		return $this->db->get("users")->result();
	}

	public function count_all_users()
	{
		return $this->db->count_all("users");
	}

	public function count_admin_kepegawaian()
	{
		$this->db->where_in("role", ["admin", "kepegawaian"]); // adjust column name
		return $this->db->count_all_results("users");
	}

	public function count_staff()
	{
		$this->db->where("role", "staff"); // adjust column name
		return $this->db->count_all_results("users");
	}

	public function get_staff()
	{
		return $this->db
			->where("role", "staff")
			->order_by("name", "ASC")
			->get("users")
			->result();
	}

	public function get_positions($user_id)
	{
		return $this->db
			->select("jabatan")
			->where("user_id", $user_id)
			->order_by("jabatan", "ASC")
			->get("user_positions")
			->result();
	}

	public function get_users($limit, $offset, $search = "")
	{
		// ====== BASE QUERY ======
		$this->db->select(
			"
            u.*,
            GROUP_CONCAT(up.jabatan SEPARATOR ', ') AS jabatans
        ",
			false,
		);

		$this->db->from("users u");
		$this->db->join("user_positions up", "u.id = up.user_id", "left");

		// ====== SEARCH FILTER ======
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like("u.name", $search);
			$this->db->or_like("u.userid", $search);
			$this->db->or_like("u.role", $search);
			$this->db->or_like("u.unit_kerja", $search);
			$this->db->or_like("up.jabatan", $search);
			$this->db->group_end();
		}

		$this->db->group_by("u.id");

		// ====== TOTAL COUNT (clone query) ======
		$count_query = clone $this->db;
		$total = $count_query->count_all_results();

		// ====== ORDERING ======
		// $this->db->order_by(
		// 	"FIELD(u.role,'admin','kepegawaian','staff')",
		// 	"",
		// 	false,
		// );
		// $this->db->order_by(
		// 	"CASE WHEN u.role='staff' THEN u.name END",
		// 	"ASC",
		// 	false,
		// );
		$this->db->order_by("u.id", "ASC");

		// ====== LIMIT ======
		$this->db->limit($limit, $offset);

		$data = $this->db->get()->result();

		return [
			"data" => $data,
			"total" => $total,
		];
	}

	public function count_users($search = null)
	{
		if ($search) {
			$this->db->like("name", $search);
			$this->db->or_like("username", $search);
		}

		return $this->db->count_all_results("users");
	}

	public function get_by_id($id)
	{
		return $this->db->get_where("users", ["id" => $id])->row();
	}

	public function insert($data)
	{
		return $this->db->insert("users", $data);
	}

	public function update($id, $data)
	{
		return $this->db->update("users", $data, ["id" => $id]);
	}

	public function delete($id)
	{
		return $this->db->delete("users", ["id" => $id]);
	}

	public function create_user($data, $jabatans = [])
	{
		// Insert users table
		$this->db->insert("users", [
			"name" => $data["name"],
			"userid" => $data["userid"],
			"password" => md5($data["password"]), // follow your native logic
			"role" => $data["role"],
			"unit_kerja" => $data["unit_kerja"],
		]);

		$uid = $this->db->insert_id();

		// Insert jabatans
		if (!empty($jabatans)) {
			foreach ($jabatans as $j) {
				if (trim($j) != "") {
					$this->db->insert("user_positions", [
						"user_id" => $uid,
						"jabatan" => $j,
					]);
				}
			}
		}

		return $uid;
	}

	public function insert_one($data)
	{
		$this->db->insert("users", $data);
		return $this->db->insert_id();
	}

	public function update_one($id, $data)
	{
		$this->db->where("id", $id);
		return $this->db->update("users", $data);
	}

	public function change_password($user_id, $old_password, $new_password)
	{
		// Cek password lama
		$user = $this->db
			->get_where("users", [
				"id" => $user_id,
				"password" => md5($old_password),
			])
			->row();
		if (!$user) {
			return false; // password lama salah
		}

		// Update password baru
		return $this->db->update(
			"users",
			["password" => md5($new_password)],
			["id" => $user_id],
		);
	}
}
