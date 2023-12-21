<?php
defined('BASEPATH') or exit('No direct script access allowed');

include_once APPPATH . '/third_party/PHPExcel/Classes/PHPExcel.php';

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
			$imageStatus = $_FILES['photo']['error'];
			$image = '';
			if($imageStatus == 4){ // UPLOAD_ERR_NO_FILE
				$image = $this->input->post('file_ktp_old');
			}else{
				$image = $this->uploadImage('photo');
			}
			$update = $this->anggota_model->update($id, $image);
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

	public function export($opt){
		$error = "";
		$crAnggota = $this->input->post("qAnggota");
		$crTglGabung = $this->input->post("qTglGabung");
		$stAnggota = $this->input->post("qStanggota");
		$url = base_url("anggota/cetak?nama=". $crAnggota ."&tgl=". $crTglGabung ."&status=". $stAnggota);

		$json = [
			'error' => $error,
			'url' => $url,
			'aa' => $crTglGabung
		];
		echo json_encode($json);
	}

	public function cetak(){
		$crAnggota = $this->input->get('nama');
		$crTglGabung = $this->input->get('tgl');
		$stAnggota = $this->input->get('status');
		$like = '';
		$wheres = array();
		//set params
		if (!empty($crAnggota) && $crAnggota != "") {
			$like = $crAnggota;
		}
		if (!empty($crTglGabung) && $crTglGabung != "") {
			$wheres['tgl_gabung'] = $crTglGabung;
		}
		if (!empty($stAnggota) && $stAnggota != "") {
			$wheres['status'] = $stAnggota;
		}

		$dataAnggota = $this->anggota_model->getAll('2', $like, $wheres, 0, 0);
		$logo = $this->ModelUtama->SETTING("LOGO_APPS");

		$data = array(
			'dataAnggota' => $dataAnggota,
			'logo' => $logo
		);

		$this->load->view('admin/anggota/cetak', $data);
	}

	public function excel(){
		$objPHPExcel = new PHPExcel();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
		->setLastModifiedBy("Maarten Balliauw")
		->setTitle("Office 2007 XLSX Test Document")
		->setSubject("Office 2007 XLSX Test Document")
		->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		->setKeywords("office 2007 openxml php")
		->setCategory("Test result file");


		// Add some data
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Hello')
				->setCellValue('B2', 'world!')
				->setCellValue('C1', 'Hello')
				->setCellValue('D2', 'world!');

		// Miscellaneous glyphs, UTF-8
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A4', 'Miscellaneous glyphs')
				->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

		// Rename worksheet
				$objPHPExcel->getActiveSheet()->setTitle('Simple');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel2007)
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="01simple.xlsx"');
				header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
				header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}

}
