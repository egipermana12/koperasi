<?php


class Simpanan extends MY_Controller{
    var $modul = 'modul_simpanan';

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('simpanan_model');
    }

    public function index(){
        $this->genView($this->modul,'admin/simpanan/index');
    }

    public function queryJnsSimpanan(){
        $jnsSimpanan = $this->ModelUtama->tampilBanyakBaris("ref_jns_simpanan", "*", array("tampil" => "Y"));
        return $jnsSimpanan;
    }

    public function create(){
        $random = generateRandomNumber('TSA');
        $cmbJnsSimpanan = cmbQuery("jns_simpanan", "0", $this->queryJnsSimpanan(), "id", "jns_simpanan", "class='form-select form-select-sm form-control' onchange = 'pilihJnsSimpanan()' style='width: 88%;' ", "Pilih Jenis Simpanan", "","1", 1, 0);
        $data = array(
            'judul' => 'Form Baru Simpanan Anggota',
            "nik" => "",
            'nama' => "",
            'no_simpanan' => $random,
            'tgl_transaksi' => '',
            'id_anggota' => '',
            'refid_jns_simpanan' => $cmbJnsSimpanan,
            'nominal' => '',
            'ket' => '',
            'id' => ''
        );

        $this->hasPermissionView($this->modul,'admin/simpanan/formsimpanan', $data);
    }

    private function validationMessageCreate(){
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
                'field' => 'no_simpanan',
                'label' => 'Nomor Transaksi',
                'rules' => 'required',
            ),
            array(
                'field' => 'tgl_transaksi',
                'label' => 'Tanggal Transaksi',
                'rules' => 'required',
            ),
            array(
                'field' => 'jns_simpanan',
                'label' => 'Jenis Simpanan',
                'rules' => 'required',
            ),
            array(
                'field' => 'nominal',
                'label' => 'Nominal',
                'rules' => 'required',
            ),
        );
        return $validate_data;
    }

    public function store(){
        $validator = array('success' => false, 'messages' => array());

        $this->form_validation->set_rules($this->validationMessageCreate());
        $this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');
        if ($this->form_validation->run() === true) {
            $save = $this->simpanan_model->create();
            if($save === true){
                $validator['success'] = true;
                $validator['messages'] = "Data berhasil disimpan";
            }else{
                $validator['success'] = false;
                $validator['messages'] = "Error while inserting the information into the database";
            }
        }else{
            $validator['success'] = false;
            foreach ($_POST as $key => $value) {
                $validator['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($validator);
    }

    public function edit($id){
        $findSimpanan = $this->ModelUtama->tampilSatuBaris('t_simpanan', '*', array('id' => $id));
        $findAnggota = $this->ModelUtama->tampilSatuBaris('anggota', '*', array('id' => $findSimpanan['id_anggota']));

        $cmbJnsSimpanan = cmbQuery("jns_simpanan", $findSimpanan["refid_jns_simpanan"], $this->queryJnsSimpanan(), "id", "jns_simpanan", "class='form-select form-select-sm form-control' onchange = 'pilihJnsSimpanan()' style='width: 88%;' ", "Pilih Jenis Simpanan", "","1", 1, 0);
        $data = array(
            'judul' => 'Edit Simpanan Anggota',
            "nik" => $findAnggota['nik'],
            'nama' => $findAnggota['nama'],
            'no_simpanan' => $findSimpanan['no_simpanan'],
            'tgl_transaksi' => $findSimpanan['tgl_transaksi'],
            'id_anggota' => $findAnggota['id'],
            'refid_jns_simpanan' => $cmbJnsSimpanan,
            'nominal' => $findSimpanan['nominal'],
            'ket' => $findSimpanan['ket'],
            'id' => $findSimpanan['id']
        );

        $this->hasPermissionView($this->modul,'admin/simpanan/formsimpanan', $data);
    }

    public function update(){
        $id = $this->input->post('id');

        $validator = array('success' => false, 'messages' => array());

        $this->form_validation->set_rules($this->validationMessageCreate());
        $this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');
        if ($this->form_validation->run() === true) {
            $save = $this->simpanan_model->update($id);
            if($save === true){
                $validator['success'] = true;
                $validator['messages'] = "Data berhasil diupdate";
            }else{
                $validator['success'] = false;
                $validator['messages'] = "Error while inserting the information into the database";
            }
        }else{
            $validator['success'] = false;
            foreach ($_POST as $key => $value) {
                $validator['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($validator);
    }

    public function delete(){
        $validator = array('success' => false, 'messages' => array());

        $status = $this->getModul($this->modul);
        if($status != 1) {
            $validator['success'] = false;
            $validator['messages'] = "Anda tidak mempunyai hak akses";
        }else{
            $id = $this->input->post('id');
            $ids = explode(",", $id);

            if(!empty($id)) {
                $delete = $this->simpanan_model->delete($ids);
                if($delete === true){
                    $validator['success'] = true;
                    $validator['messages'] = "Data berhasil dihapus";
                }else{
                    $validator['success'] = false;
                    $validator['messages'] = "Error while delete the information into the database";
                }
            }
        }

        echo json_encode($validator);
    }

    public function view($viewOnly = 0){
        $pagePerHalaman = 5;
        $pageStart = 0;
        $likes = array();
        $wheres = array();
        $like = '';

        //catch param from post
        $qjnsSimpanan = $this->input->post('jns_simpanan_table');
        $qNikname = $this->input->post('nikname');
        $qNoSimpanan = $this->input->post('no_simpanan_table');
        $qTglTransaksi = $this->input->post('tgl_transaksi_table');

        $cmbJnsSimpanan = cmbQuery("jns_simpanan_table", $qjnsSimpanan, $this->queryJnsSimpanan(), "id", "jns_simpanan", "class='form-select form-select-sm form-control' ", "Pilih Jenis Simpanan", "","1", 1, 0);

        if ($this->input->post('pageStart')) {
            $pageStart = $this->input->post('pageStart');
        }

        if (!empty($qjnsSimpanan)) {
            $wheres['refid_jns_simpanan'] = $qjnsSimpanan;
        }
        if (!empty($qNoSimpanan)) {
            $wheres['no_simpanan'] = $qNoSimpanan;
        }
        if (!empty($qTglTransaksi)) {
            $wheres['tgl_transaksi'] = $qTglTransaksi;
        }
        if (!empty($qNikname)) {
            $like = $qNikname;
        }

        $dataSimpanan = $this->simpanan_model->getAll('1', $likes, $like, $wheres, $pagePerHalaman, $pageStart);
        $totalData = $this->simpanan_model->getAll('0', $likes, $like, $wheres, $pagePerHalaman, $pageStart);

        $data['viewOnly'] = $viewOnly;
        $data['pageStart'] = $pageStart;
        $data['dataSimpanan'] = $dataSimpanan;
        $data['pagePerHalaman'] = $pagePerHalaman;
        $data['totalData'] = $totalData;
        $data['cmbJnsSimpanan'] = $cmbJnsSimpanan;
        $data['crJnsSimpanan'] = $qjnsSimpanan;
        $data['qNikname'] = $qNikname;
        $data['qNoSimpanan'] = $qNoSimpanan;
        $data['qTglTransaksi'] = $qTglTransaksi;

        $json = [
            'data' => $this->load->view('admin/simpanan/table', $data),
        ];
        echo json_encode($json);
    }

    public function generateBukit(){
        $nmIsntansi = strtoupper($this->ModelUtama->SETTING("NAMA_INSTANSI"));
        $bdnHukum = strtoupper($this->ModelUtama->SETTING("NO_BADAN_HUKUM"));
        $alamat = ucwords($this->ModelUtama->SETTING("ALAMAT"));

        error_reporting(0);
        $pdfTemplate = new FPDF('P', 'mm','A4');
        $pdfTemplate->SetLeftMargin(20);
        $pdfTemplate->SetRightMargin(20);
        $pdfTemplate->AddPage();
        $pdfTemplate->SetFont('Courier','B',12);
        for($i = 1; $i <= 2; $i++){
            $pdfTemplate->Cell(0,7,'KOPERASI SIMPAN PINJAM',0,1,'C');
            $pdfTemplate->SetFont('Courier','B',16);
            $pdfTemplate->Ln(-2);
            $pdfTemplate->Cell(0,9,$nmIsntansi,0,1,'C');
            $pdfTemplate->Ln(-2);
            $pdfTemplate->SetFont('Courier','B',11);
            $pdfTemplate->Cell(0,6,$bdnHukum,0,1,'C');
            $pdfTemplate->Ln(-1);
            $pdfTemplate->SetFont('Courier','B',9);
            $pdfTemplate->Cell(30,5,'',0,0,'C');
            $pdfTemplate->MultiCell(110,4,$alamat,0,'C');
            $pdfTemplate->Cell(0,6,'---------------------------------------------------------------------------------------',0,1,'C');
            $pdfTemplate->Ln(1);
            $pdfTemplate->SetFont('Courier','B',12);
            $pdfTemplate->Cell(0,6,'BUKTI SIMPANANAN ANGGOTA',0,1,'C');
            $pdfTemplate->Ln(1);
            $pdfTemplate->Cell(50,6,'NOMOR TRANSAKSI',0,0,'L');
            $pdfTemplate->Cell(5,6,':',0,0,'C');
            $pdfTemplate->Cell(110,6,'TSA012384',0,1,'L');
            $pdfTemplate->Cell(50,6,'NIK ANGGOTA',0,0,'L');
            $pdfTemplate->Cell(5,6,':',0,0,'C');
            $pdfTemplate->Cell(110,6,'320919394857',0,1,'L');
            $pdfTemplate->Cell(50,6,'NAMA ANGGOTA',0,0,'L');
            $pdfTemplate->Cell(5,6,':',0,0,'C');
            $pdfTemplate->Cell(110,6,'BAIM WONG WING WUNG',0,1,'L');
            $pdfTemplate->Cell(50,6,'TANGGAL TRANSAKSI',0,0,'L');
            $pdfTemplate->Cell(5,6,':',0,0,'C');
            $pdfTemplate->Cell(110,6,'24 DESEMBER 2023',0,1,'L');
            $pdfTemplate->Cell(50,6,'JENIS SIMPANAN',0,0,'L');
            $pdfTemplate->Cell(5,6,':',0,0,'C');
            $pdfTemplate->Cell(110,6,'SIMPANAN POKOK',0,1,'L');
            $pdfTemplate->Cell(50,6,'NOMINAL',0,0,'L');
            $pdfTemplate->Cell(5,6,':',0,0,'C');
            $pdfTemplate->Cell(110,6,'RP. 150.000,00',0,1,'L');
            $pdfTemplate->Cell(50,6,'TERBILANG',0,0,'L');
            $pdfTemplate->Cell(5,6,':',0,0,'C');
            $pdfTemplate->Cell(110,6,'SERATUS LIMA PULUH RIBU RUPIAH',0,1,'L');
            $pdfTemplate->Ln(3);
            $pdfTemplate->Cell(0,6,'Kuningan, 02 Januari 2024',0,1,'R');
            $pdfTemplate->Cell(70,6,'NASABAH',0,0,'C');
            $pdfTemplate->Cell(30,5,'',0,0,'C');
            $pdfTemplate->Cell(70,6,'BENDAHARA',0,1,'C');
            $pdfTemplate->Ln(10);
            $pdfTemplate->Cell(70,6,'JONI',0,0,'C');
            $pdfTemplate->Cell(30,5,'',0,0,'C');
            $pdfTemplate->Cell(70,6,'SUGENG',0,1,'C');
            $pdfTemplate->Ln(1);
            $pdfTemplate->SetFont('Courier','B',9);
            $pdfTemplate->Cell(0,6,'---------------------------------------------------------------------------------------',0,1,'C');
            $pdfTemplate->Ln(4);
        }
        $pdfTemplate->Output();
    }

}