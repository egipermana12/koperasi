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
		$DEF_PROVINSI = $this->ModelUtama->SETTING("DEF_PROVINSI");
		$DEF_KABUPATEN = $this->ModelUtama->SETTING("DEF_KABUPATEN");
		$queryKecamatan = $this->ModelUtama->tampilBanyakBaris("ref_wilayah", "*", array("kode_provinsi" => $DEF_PROVINSI, "kode_kota" => $DEF_KABUPATEN, "CAST(kode_kelurahan as UNSIGNED)"=>0),"AND CAST(kode_kecamatan as UNSIGNED)!=0");
		$cmbKecamatan = cmbQuery("kode_kecamatan", "0", $queryKecamatan, "kode_kecamatan", "nama", "class='form-select form-select-sm' onchange = 'pilihKecamatan()' ", "Pilih Kecamatan", "0","1", 1, 0);

		$cmbKelurahan = "<select id='kode_kelurahan' name='kode_kelurahan' class='form-select form-select-sm'><option value='0'>Pilih Kelurahan</option></select>";


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
			"cmbKecamatan" => $cmbKecamatan,
			"cmbKelurahan" => $cmbKelurahan,
		);
		$this->template->load('template', 'admin/anggota/form', $data);
	}

	public function pilihKecamatan($return = 0, $selectedKecamatan = ""){
		$error = "";$content="";
		$PS = $this->input->post(NULL, TRUE);
		if(intval($PS["kode_kecamatan"]) == 0)$err = "Kecamatan Belum di Pilih !";

		$DEF_PROVINSI = $this->ModelUtama->SETTING("DEF_PROVINSI");
		$DEF_KABUPATEN = $this->ModelUtama->SETTING("DEF_KABUPATEN");

		$queryKecamatan = $this->ModelUtama->tampilBanyakBaris("ref_wilayah", "*", array("kode_provinsi" => $DEF_PROVINSI, "kode_kota" => $DEF_KABUPATEN, "CAST(kode_kecamatan as UNSIGNED)"=>$PS["kode_kecamatan"]),"AND CAST(kode_kelurahan as UNSIGNED)!=0");
		$content = cmbQuery("kode_kelurahan", "0", $queryKecamatan, "kode_kelurahan", "nama", "class='form-select form-select-sm' ", "Pilih Kelurahan", "0","1", 1, 0);

		echo json_encode($content);

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
