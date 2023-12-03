<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anggota extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('anggota_model');
		$this->load->library('form_validation');
	}

	public function index() {
		$this->template->load('template', 'admin/anggota/index');
	}

	public function create() {
		$kec = array(
			'1' => 'Luragung',
			'2' => 'Cidahu',
			'3' => 'Kuningan',
			'4' => 'Cigugur',
		);
		$data = array(
			'id' => "",
			'nik' => "",
			'nama' => "",
			'tgl_lahir' => "",
			'jns_kelamin' => "",
			'alamat' => "",
			'kd_desa' => "",
			'kd_kec' => "",
			"kd_kab" => "",
			"pekerjaan" => "",
			"tgl_gabung" => "",
			"status" => "",
			"file_kpt" => "",
			"daftar_kec" => $kec,
		);
		$this->template->load('template', 'admin/anggota/form', $data);
	}

	public function store() {
		$validator = array('success' => false, 'messages' => array());

		$validate_data = array(
			array(
				'field' => 'nik',
				'label' => 'NIK Anggota',
				'rules' => 'required',
			),
			array(
				'field' => 'nama',
				'label' => 'Nama Anggota',
				'rules' => 'required',
			),
			array(
				'field' => 'tgl_lahir',
				'label' => 'Tanggal Lahir',
				'rules' => 'required',
			),
			array(
				'field' => 'jnsKelamin',
				'label' => 'Jenis Kelamin',
				'rules' => 'required',
			),
			array(
				'field' => 'tgl_gabung',
				'label' => 'Tanggal Gabung Anggota',
				'rules' => 'required',
			),
			array(
				'field' => 'status',
				'label' => 'Status Anggota',
				'rules' => 'required',
			),
		);
		$this->form_validation->set_rules($validate_data);
		$this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');
		if ($this->form_validation->run() === true) {
			$create = false;
			if ($create == true) {

			} else {
				$validator['success'] = false;
				$validator['messages'] = "Error while inserting the information into the database";
			}
		} else {
			$validator['success'] = false;
			foreach ($_POST as $key => $value) {
				$validator['messages'][$key] = form_error($key);
			}
		}
		echo json_encode($validator);
	}

	public function view($viewOnly = 0) {
		$pagePerHalaman = 5;
		$pageStart = 0;
		$like = '';
		$wheres = array();

		//catch param from post
		$crAnggota = $this->input->post('qAnggota');
		$crTglGabung = $this->input->post('qTglGabung');
		$stAnggota = $this->input->post('qStanggota');

		if ($this->input->post('pageStart')) {
			$pageStart = $this->input->post('pageStart');
		}

		//set params
		if (!empty($crAnggota)) {
			$like = $crAnggota;
		}
		if (!empty($crTglGabung)) {
			$wheres['tgl_gabung'] = $crTglGabung;
		}
		if (!empty($stAnggota)) {
			$wheres['status'] = $stAnggota;
		}

		$dataAnggota = $this->anggota_model->getAll('1', $like, $wheres, $pagePerHalaman, $pageStart);
		$totalData = $this->anggota_model->getAll('0', $like, $wheres, $pagePerHalaman, $pageStart);

		$data['viewOnly'] = $viewOnly;
		$data['anggotas'] = $dataAnggota;
		$data['pageStart'] = $pageStart;
		$data['qAnggota'] = $crAnggota;
		$data['qTglGabung'] = $crTglGabung;
		$data['qStanggota'] = $stAnggota;
		$data['pagePerHalaman'] = $pagePerHalaman;
		$data['totalData'] = $totalData;

		$json = [
			'data' => $this->load->view('admin/anggota/table', $data),
		];
		echo json_encode($json);
	}
}
