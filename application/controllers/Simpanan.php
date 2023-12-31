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
        $jnsSimpanan = $this->ModelUtama->tampilBanyakBaris("ref_jns_simpanan", "*");
        return $jnsSimpanan;
    }

    public function create(){
        $random = generateRandomNumber('TSA');
        $cmbJnsSimpanan = cmbQuery("jns_simpanan", "0", $this->queryJnsSimpanan(), "id", "jns_simpanan", "class='form-select form-select-sm' style='width: 88%;'' ", "Pilih Jenis Simpanan", "","1", 1, 0);
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

        }else{
            $validator['success'] = false;
            foreach ($_POST as $key => $value) {
                $validator['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($validator);
    }

    public function view($viewOnly = 0){
        $pagePerHalaman = 5;
        $pageStart = 0;
        $likes = array();
        $wheres = array();

        $dataSimpanan = $this->simpanan_model->getAll('1', $likes, $wheres, $pagePerHalaman, $pageStart);
        $totalData = $this->simpanan_model->getAll('0', $likes, $wheres, $pagePerHalaman, $pageStart);

        $data['viewOnly'] = $viewOnly;
        $data['pageStart'] = $pageStart;
        $data['dataSimpanan'] = $dataSimpanan;
        $data['pagePerHalaman'] = $pagePerHalaman;
        $data['totalData'] = $totalData;

        $json = [
            'data' => $this->load->view('admin/simpanan/table', $data),
        ];
        echo json_encode($json);
    }

}