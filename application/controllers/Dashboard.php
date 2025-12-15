<?php

class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata("user")) {
			redirect("auth");
		}
		$this->load->model("User_model");
		$this->load->model("Competency_model");
	}

	public function index()
	{
		$user = $this->session->userdata("user");
		$role = $user->role;

		$limit = 10;
		$page = $this->input->get("page")
			? intval($this->input->get("page"))
			: 1;
		$offset = ($page - 1) * $limit;

		$total_belum = $this->Competency_model->count_belum_dinilai_all(
			$role == "staff" ? $user->id : null,
		);
		$total_pages = ceil($total_belum / $limit);

		$total_user = $this->User_model->count_all_users();
		$total_admin = $this->User_model->count_admin_kepegawaian();
		$total_staff = $this->User_model->count_staff();
		$total_kompetensi = $this->Competency_model->count_total_pengembangan(
			$role == "staff" ? $user->id : null,
		);
		$sudah_dinilai = $this->Competency_model->count_sudah_dinilai(
			$role == "staff" ? $user->id : null,
		);

		$belum_dinilai_list = $this->Competency_model->get_belum_dinilai(
			$limit,
			$offset,
			$role == "staff" ? $user->id : null,
		);

		$data = [
			"title" => "Dashboard",
			"active" => "dashboard",
			"content" =>
				$role == "staff" ? "dashboard/staff" : "dashboard/index",
			"stats" => [
				"total_user" => $total_user,
				"total_admin" => $total_admin,
				"total_staff" => $total_staff,
				"total_kompetensi" => $total_kompetensi,
				"sudah_dinilai" => $sudah_dinilai,
				"belum_dinilai" => $total_belum,
			],
			"belum_dinilai_list" => $belum_dinilai_list,
			"page" => $page,
			"limit" => $limit,
			"total" => $total_belum,
			"total_pages" => $total_pages,
			"offset" => $offset,
		];

		$this->load->view("templates/master", $data);
	}
}
