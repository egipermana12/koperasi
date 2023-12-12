<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anggota extends MY_Controller {
	public $kdProv;
	public $kdKota;

	public function __construct() {
		parent::__construct();
		$this->load->model('anggota_model');
		$this->load->library('form_validation');
		$this->kdProv = $this->ModelUtama->SETTING("DEF_PROVINSI");
		$this->kdKota = $this->ModelUtama->SETTING("DEF_KABUPATEN");
	}

	public function index() {
		$this->template->load('template', 'admin/anggota/index');
	}

	public function queryKecamatan(){
		$queryKecamatan = $this->ModelUtama->tampilBanyakBaris("ref_wilayah", "*", array("kode_provinsi" => $this->kdProv, "kode_kota" => $this->kdKota, "CAST(kode_kelurahan as UNSIGNED)"=>0),"AND CAST(kode_kecamatan as UNSIGNED)!=0");
		return $queryKecamatan;
	}

	public function queryKelurahan($idKecamatan = 0){
		$queryKelurahan = $this->ModelUtama->tampilBanyakBaris("ref_wilayah", "*", array("kode_provinsi" => $this->kdProv, "kode_kota" => $this->kdKota, "CAST(kode_kecamatan as UNSIGNED)"=>$idKecamatan),"AND CAST(kode_kelurahan as UNSIGNED)!=0");
		return $queryKelurahan;
	}

	public function create() {
		$cmbKecamatan = cmbQuery("kode_kecamatan", "0", $this->queryKecamatan(), "kode_kecamatan", "nama", "class='form-select form-select-sm' onchange = 'pilihKecamatan()' ", "Pilih Kecamatan", "0","1", 1, 0);
		$cmbKelurahan = "<select id='kode_kelurahan' name='kode_kelurahan' class='form-select form-select-sm'><option value='0'>Pilih Kelurahan</option></select>";

		$data = array(
			'id' => "",
			'nik' => "",
			'nama' => "",
			'tgl_lahir' => "",
			'jns_kelamin' => "L",
			'alamat' => "",
			"pekerjaan" => "",
			"tgl_gabung" => "",
			"status" => "A",
			"file_ktp" => "",
			"file_ktp_old" => "",
			"cmbKecamatan" => $cmbKecamatan,
			"cmbKelurahan" => $cmbKelurahan,
		);
		$this->template->load('template', 'admin/anggota/formanggota', $data);
	}

	public function edit($id=null){
		if (!isset($id)) redirect('anggota');
		$getData = $this->ModelUtama->tampilSatuBaris('anggota', "*", array("id" => $id));

		if(!$getData){
			show_404();
		}

		$cmbKecamatan = cmbQuery("kode_kecamatan", $getData["kd_kec"], $this->queryKecamatan(), "kode_kecamatan", "nama", "class='form-select form-select-sm' onchange = 'pilihKecamatan()' ", "Pilih Kecamatan", "0","1", 1, 0);

		$cmbKelurahan = cmbQuery("kode_kelurahan", $getData["kd_desa"], $this->queryKelurahan($getData["kd_kec"]), "kode_kelurahan", "nama", "class='form-select form-select-sm' ", "Pilih Kelurahan", "0","1", 1, 0);

		$data = array(
			'id' => $getData["id"],
			'nik' => $getData["nik"],
			'nama' => $getData["nama"],
			'tgl_lahir' => $getData["tgl_lahir"],
			'jns_kelamin' => $getData["jns_kelamin"],
			'alamat' => $getData["alamat"],
			"pekerjaan" => $getData["pekerjaan"],
			"tgl_gabung" => $getData["tgl_gabung"],
			"status" => $getData["status"],
			"file_ktp" => $getData["file_ktp"],
			"file_ktp_old" => $getData["file_ktp"],
			"cmbKecamatan" => $cmbKecamatan,
			"cmbKelurahan" => $cmbKelurahan,
		);
		$this->template->load('template', 'admin/anggota/formanggota', $data);
	}

	public function pilihKecamatan($return = 0, $kdKelurahan = ""){
		$error = "";$content="";
		$PS = $this->input->post(NULL, TRUE);
		if(intval($PS["kode_kecamatan"]) == 0)$err = "Kecamatan Belum di Pilih !";

		$DEF_PROVINSI = $this->ModelUtama->SETTING("DEF_PROVINSI");
		$DEF_KABUPATEN = $this->ModelUtama->SETTING("DEF_KABUPATEN");

		$queryKecamatan = $this->ModelUtama->tampilBanyakBaris("ref_wilayah", "*", array("kode_provinsi" => $DEF_PROVINSI, "kode_kota" => $DEF_KABUPATEN, "CAST(kode_kecamatan as UNSIGNED)"=>$PS["kode_kecamatan"]),"AND CAST(kode_kelurahan as UNSIGNED)!=0");
		$content = cmbQuery("kode_kelurahan", $kdKelurahan, $queryKecamatan, "kode_kelurahan", "nama", "class='form-select form-select-sm' ", "Pilih Kelurahan", "0","1", 1, 0);
		$data = array(
			'content' => $content,
			'error' => $error
		);
		echo json_encode($data);

	}

	private function validationMessageCreate(){
		$validate_data = array(
			array(
				'field' => 'nik',
				'label' => 'NIK Anggota',
				'rules' => 'required|is_unique[anggota.nik]',
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
		return $validate_data;
	}

	public function store() {
		$validator = array('success' => false, 'messages' => array());

		$this->form_validation->set_rules($this->validationMessageCreate());
		$this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');

		if ($this->form_validation->run() === true) {
			$image = $this->uploadImage('photo');
			$create = $this->anggota_model->create($image);
			if ($create == true) {
				$validator['success'] = true;
				$validator['messages'] = "Data berhasil disimpan";
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

	private function validationMessageEdit($id = "", $nik = ""){
		$rules = '';
		if(!empty($id)){
			$getData = $this->ModelUtama->tampilSatuBaris('anggota', "*", array("id" => $id));
			if($getData["nik"] === $nik){
				$rules = 'required';
			}else{
				$rules = 'required|is_unique[anggota.nik]';
			}
		}
		$validate_data = array(
			array(
				'field' => 'nik',
				'label' => 'NIK Anggota',
				'rules' => $rules,
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
		return $validate_data;
	}

	public function update(){
		$validator = array('success' => false, 'messages' => array());

		$id = $this->input->post('id');
		$nik = $this->input->post('nik');
		$getData = $this->ModelUtama->tampilSatuBaris('anggota', "*", array("id" => $id));

		$this->form_validation->set_rules($this->validationMessageEdit($id, $nik));
		$this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');

		if ($this->form_validation->run() === true) {
			$update = $this->anggota_model->update($id);
			if ($update == true) {
				$validator['success'] = true;
				$validator['messages'] = "Data berhasil diupdate";
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

	public function uploadImage($field_name = 'photo'){
		$config['upload_path']	= "./uploads/ktpanggota";
		$config['allowed_types']='jpeg|gif|jpg|png';
		$config['encrypt_name'] = TRUE;
		$config['max_size']     = 1024;
		$this->load->library('upload',$config);

		if ($this->upload->do_upload($field_name)) {
	        $data = array('upload_data' => $this->upload->data());
	        return $data['upload_data']['file_name'];
	    } else {
	        return false;
	    }
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
