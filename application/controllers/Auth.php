<?php
class Auth extends CI_Controller
{
	public function index()
	{
		$data["title"] = "Login - Sneat Admin";
		$this->load->view("templates/auth/header", $data);
		$this->load->view("auth/login");
		$this->load->view("templates/auth/footer");
	}

	public function login()
	{
		$this->load->model("User_model");
		$user = $this->User_model->login(
			$this->input->post("userid"),
			$this->input->post("password"),
		);

		if ($user) {
			$this->session->set_userdata("user", $user);
			redirect("dashboard");
		} else {
			$this->session->set_flashdata("error", "Invalid credentials");
			redirect("auth");
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect("auth");
	}

	public function change_password()
	{
		$this->load->model("User_model");
		$user_id = $this->session->userdata("user")->id ?? null;
		if (!$user_id) {
			redirect("login");
		}

		$data = [
			"title" => "Ubah Password",
			"active" => "",
			"content" => "auth/change_password",
		];

		$data["msg"] = "";

		if ($this->input->method() == "post") {
			$old = $this->input->post("old_password");
			$new = $this->input->post("new_password");
			$confirm = $this->input->post("confirm_password");

			if ($new !== $confirm) {
				$data["msg"] =
					'<div class="alert alert-danger">Konfirmasi password tidak sama.</div>';
			} else {
				$changed = $this->User_model->change_password(
					$user_id,
					$old,
					$new,
				);
				if ($changed) {
					$data["msg"] =
						'<div class="alert alert-success">Password berhasil diubah.</div>';
				} else {
					$data["msg"] =
						'<div class="alert alert-danger">Password lama salah.</div>';
				}
			}
		}

		$this->load->view("templates/master", $data);
	}
}
