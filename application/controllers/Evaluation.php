<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Evaluation extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Evaluation_model");

		// Session check
		if (
			!$this->session->userdata("user") ||
			$this->session->userdata("user")->role != "staff"
		) {
			show_error("Unauthorized", 403);
		}
		$this->load->model("User_model");
		$this->load->model("Competency_model");
	}

	public function index($id)
	{
		$uid = $this->session->userdata("user")->id;
		$id = intval($id);

		// Ambil data competency
		$competency = $this->Evaluation_model->get_competency($id, $uid);
		if (!$competency) {
			show_error("Data tidak ditemukan!");
		}

		// Cek evaluasi
		$evaluation = $this->Evaluation_model->get_evaluation($id, $uid);

		// Simpan jika POST dan belum ada evaluasi
		$msg = "";
		if ($this->input->method() == "post" && !$evaluation) {
			$q = [];
			for ($i = 1; $i <= 8; $i++) {
				$q["q$i"] = intval($this->input->post("q$i"));
			}
			$notes = $this->db->escape_str($this->input->post("notes"));
			$signature = $this->db->escape_str($this->input->post("signature"));

			$insert_data = array_merge($q, [
				"notes" => $notes,
				"signature" => $signature,
			]);

			$this->Evaluation_model->insert_evaluation($id, $uid, $insert_data);
			$evaluation = $this->Evaluation_model->get_evaluation($id, $uid);
			$msg =
				"<div class='alert alert-success'>Penilaian berhasil disimpan!</div>";
		}

		// Pertanyaan
		$questions = [
			"Peserta menunjukkan penguasaan dan peningkatan kompetensi setelah mengikuti pelatihan (kompetensi dalam hal ini mengacu dengan jenis kompetensi yang ditingkatkan sesuai tujuan dan jenis pelatihan)",
			"Peserta menunjukkan peningkatan kinerja dibandingkan sebelum mengikuti pelatihan",
			"Peserta mampu menghasilkan terobosan / inovasi / perbaikan sistem / mekanisme / cara pelaksanaan pekerjaan yang lebih efektif dan efisien setelah mengikuti pelatihan",
			"Peserta bersedia untuk berbagi informasi / pengetahuan / keterampilan yang diperoleh dalam pelatihan kepada pegawai lainnya",
			"Peserta lebih antusias dan bersemangat untuk melaksanakan pekerjaan setelah mengikuti pelatihan",
			"Peserta menunjukkan perubahan sikap / perilaku / perkataan ke arah yang lebih baik (positif) setelah mengikuti pelatihan",
			"Peserta pelatihan menyampaikan ide / gagasan / pendapat untuk perbaikan dan peningkatan kinerja organisasi",
			"Peserta lebih mampu mengorganisir pelaksanaan pekerjaan dibandingkan sebelum mengikuti pelatihan (mengorganisir dalam hal ini meliputi pengelolaan perencanaan, pelaksanaan, evaluasi, sumber daya, stakeholders, dll yang terkait dengan pekerjaan)",
		];

		$data = [
			"title" => "Evaluasi Pelatihan",
			"active" => "evaluasi",
			"content" => "evaluation/index",
			"competency" => $competency,
			"evaluation" => $evaluation,
			"questions" => $questions,
			"msg" => $msg,
		];

		$this->load->view("templates/master", $data);
	}

	public function list()
	{
		$user = $this->session->userdata("user");
		$role = $user->role;

		$year = $this->input->get("year") ?? "";
		$status = $this->input->get("status") ?? "";

		$limit = 10;
		$page = $this->input->get("page")
			? intval($this->input->get("page"))
			: 1;
		$offset = ($page - 1) * $limit;

		$result = $this->Competency_model->get_list(
			$limit,
			$offset,
			$year,
			$status,
			$role == "staff" ? $user->id : null,
		);

		$total_rows = $result["total"];
		$total_pages = ceil($total_rows / $limit);

		$data = [
			"title" => "Evaluasi Saya",
			"active" => "evaluasi",
			"content" => "evaluation/list",
			"list" => $result["data"],
			"years" => $this->Competency_model->get_years(),
			"page" => $page,
			"total_pages" => $total_pages,
			"offset" => $offset,
		];

		$this->load->view("templates/master", $data);
	}
}
