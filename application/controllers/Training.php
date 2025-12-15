<?php

class Training extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata("user")) {
			redirect("auth");
		}
		$user = $this->session->userdata("user");

		if (!$user || !in_array($user->role, ["admin", "kepegawaian"])) {
			show_error("Unauthorized", 403);
		}

		$this->load->model("User_model");
		$this->load->model("Competency_model");
	}

	public function index()
	{
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
		);

		$total_rows = $result["total"];
		$total_pages = ceil($total_rows / $limit);

		$data = [
			"title" => "Training",
			"active" => "training",
			"content" => "training/index",
			"list" => $result["data"],
			"years" => $this->Competency_model->get_years(),
			"page" => $page,
			"total_pages" => $total_pages,
			"offset" => $offset,
		];

		$this->load->view("templates/master", $data);
	}

	public function form($id = null)
	{
		$data["title"] = $id
			? "Edit Pengembangan Kompetensi"
			: "Tambah Pengembangan Kompetensi";
		$data["active"] = "training";

		$data["trainees"] = $this->User_model->get_staff();
		$data["evaluators"] = $this->User_model->get_staff();

		$data["form"] = $id ? $this->Competency_model->find($id) : null;

		$data["content"] = "training/form";

		$this->load->view("templates/master", $data);
	}

	public function save($id = null)
	{
		$payload = [
			"trainee_id" => $this->input->post("trainee_id"),
			"training_title" => $this->input->post("training_title"),
			"training_start" => $this->input->post("training_start"),
			"training_end" => $this->input->post("training_end"),
			"training_organizer" => $this->input->post("training_organizer"),
			"evaluator_id" => $this->input->post("evaluator_id"),
			"evaluator_position" => $this->input->post("evaluator_position"),
		];

		if ($id) {
			$this->Competency_model->update_one($id, $payload);
		} else {
			$this->Competency_model->insert_one($payload);
		}

		redirect("training");
	}

	public function get_positions()
	{
		$user_id = $this->input->get("user_id");
		$positions = $this->User_model->get_positions($user_id);

		echo json_encode($positions);
	}

	public function delete($id)
	{
		// Validasi ID
		if (!$id || !is_numeric($id)) {
			$this->session->set_flashdata("error", "ID tidak valid.");
			redirect("training");
			return;
		}

		// Load model (jika belum autoload)
		$this->load->model("Competency_model");

		// Cek apakah data ada
		$record = $this->Competency_model->find($id);
		if (!$record) {
			$this->session->set_flashdata("error", "Data tidak ditemukan.");
			redirect("training");
			return;
		}

		// Hapus data
		$this->Competency_model->delete($id);

		// Pesan sukses
		$this->session->set_flashdata(
			"success",
			"Data pelatihan berhasil dihapus.",
		);

		redirect("training");
	}

	public function export_pdf($id)
	{
		if (!$id || !is_numeric($id)) {
			show_error("ID tidak valid");
			return;
		}

		// Load dependencies
		$this->load->model("Competency_model");
		$this->load->helper("url");

		$data = $this->Competency_model->get_detail_for_pdf($id);
		if (!$data) {
			show_error("Data tidak ditemukan");
			return;
		}

		// Questions text
		$questions = [
			1 => "Peserta menunjukkan penguasaan dan peningkatan kompetensi setelah mengikuti pelatihan (kompetensi dalam hal ini mengacu dengan jenis kompetensi yang ditingkatkan sesuai tujuan dan jenis pelatihan)",
			2 => "Peserta menunjukkan peningkatan kinerja dibandingkan sebelum mengikuti pelatihan",
			3 => "Peserta mampu menghasilkan terobosan / inovasi / perbaikan sistem / mekanisme / cara pelaksanaan pekerjaan yang lebih efektif dan efisien setelah mengikuti pelatihan",
			4 => "Peserta bersedia untuk berbagi informasi / pengetahuan / keterampilan yang diperoleh dalam pelatihan kepada pegawai lainnya",
			5 => "Peserta lebih antusias dan bersemangat untuk melaksanakan pekerjaan setelah mengikuti pelatihan",
			6 => "Peserta menunjukkan perubahan sikap / perilaku / perkataan ke arah yang lebih baik (positif) setelah mengikuti pelatihan",
			7 => "Peserta pelatihan menyampaikan ide / gagasan / pendapat untuk perbaikan dan peningkatan kinerja organisasi",
			8 => "Peserta lebih mampu mengorganisir pelaksanaan pekerjaan dibandingkan sebelum mengikuti pelatihan (mengorganisir dalam hal ini meliputi pengelolaan perencanaan, pelaksanaan, evaluasi, sumber daya, stakeholders, dll yang terkait dengan pekerjaan)",
		];

		// Hitung rata-rata
		$eval = $data->evaluation;
		$avg = "-";
		if ($eval) {
			$sum = 0;
			for ($i = 1; $i <= 8; $i++) {
				$sum += (int) $eval->{"q$i"};
			}
			$avg = round($sum / 8, 2);
		}

		$dompdf = new Dompdf\Dompdf();

		// Generate HTML
		$html = $this->load->view(
			"training/pdf",
			[
				"data" => $data,
				"questions" => $questions,
				"eval" => $eval,
				"avg" => $avg,
			],
			true,
		);

		$dompdf->loadHtml($html);
		$dompdf->setPaper("A4", "portrait");
		$dompdf->render();
		$dompdf->stream("evaluasi_$id.pdf", ["Attachment" => false]);
	}
}
