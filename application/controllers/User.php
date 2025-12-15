<?php
defined("BASEPATH") or exit("No direct script access allowed");

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("User_model");
		$this->load->model("UserPosition_model");
		$this->load->library("form_validation");

		// Role protection (admin only)
		$user = $this->session->userdata("user");
		if (!$user || $user->role != "admin") {
			show_error("Unauthorized", 403);
		}
	}

	public function index()
	{
		// search
		$search = $this->input->get("search") ?? "";

		$limit = 10;
		$page = $this->input->get("page")
			? intval($this->input->get("page"))
			: 1;
		$offset = ($page - 1) * $limit;

		$result = $this->User_model->get_users($limit, $offset, $search);

		$total_rows = $result["total"];
		$total_pages = ceil($total_rows / $limit);

		$data = [
			"title" => "User",
			"active" => "user",
			"content" => "user/index",
			"list" => $result["data"],
			"page" => $page,
			"total_pages" => $total_pages,
			"offset" => $offset,
			"search" => $search,
		];

		$this->load->view("templates/master", $data);
	}

	public function delete($id)
	{
		// Validasi ID
		if (!$id || !is_numeric($id)) {
			$this->session->set_flashdata("error", "ID tidak valid.");
			redirect("user");
			return;
		}

		// Cek apakah user ada
		$user = $this->User_model->get_by_id($id);
		if (!$user) {
			$this->session->set_flashdata("error", "User tidak ditemukan.");
			redirect("user");
			return;
		}

		// Hapus user
		$this->User_model->delete($id);

		// Pesan sukses
		$this->session->set_flashdata("success", "User berhasil dihapus.");
		redirect("user");
	}

	// ===============================
	//  FORM: Add/Edit User
	// ===============================
	public function form($id = null)
	{
		$data["title"] = $id ? "Edit User" : "Tambah User";
		$data["active"] = "user";

		// form data (user row or null)
		$data["form"] = $id ? $this->User_model->get_by_id($id) : null;

		// jabatans (array for edit)
		$data["jabatans"] = $id
			? $this->UserPosition_model->get_by_user($id)
			: [];

		$data["content"] = "user/form";

		$this->load->view("templates/master", $data);
	}

	// ===============================
	//  SAVE (Insert / Update)
	// ===============================
	public function save($id = null)
	{
		$payload = [
			"name" => $this->input->post("name"),
			"userid" => $this->input->post("userid"),
			"role" => $this->input->post("role"),
			"unit_kerja" => $this->input->post("unit_kerja"),
		];

		// Password only updated if provided
		if (!$id && $this->input->post("password")) {
			$payload["password"] = md5($this->input->post("password"));
		}
		if ($id && $this->input->post("password")) {
			$payload["password"] = md5($this->input->post("password"));
		}

		$jabatans = $this->input->post("jabatan") ?? [];

		if ($id) {
			// update user
			$this->User_model->update_one($id, $payload);

			// update jabatans
			$this->UserPosition_model->replace_positions($id, $jabatans);
		} else {
			// create user
			$new_id = $this->User_model->insert_one($payload);

			// insert jabatans
			$this->UserPosition_model->insert_many($new_id, $jabatans);
		}

		redirect("user");
	}
}
