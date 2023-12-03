<?php

class Settings extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function index() {
		$settings = $this->ModelUtama->tampilBanyakBaris('settings', "*", array(), "Order By urut");
		$data['settings'] = $settings;
		$this->template->load('template', 'admin/settings/index', $data);
	}

	public function store() {
		$cek = "";
		$err = "";
		$content = "";
		$form = $this->input->post(NULL, TRUE);

		if ($err == "" && $form["id"] == "") {
			$err = "Data tidak valid";
		}

		if ($err == "") {
			$update = $this->ModelUtama->queryUpdateData("settings", array("value" => $form["value"]), "WHERE id=?", array($form[
				"id"]));
		}

		echo json_encode(array("cek" => $cek, "err" => $err, "content" => $content));
	}

}