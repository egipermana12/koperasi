<?php

class Pengajuan extends MY_Controller {

    public $modul = 'modul_pinjaman';

    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('./pinjaman/pengajuan_model');
    }

    public function index(){
        $this->genView($this->modul,'admin/pinjaman/pengajuan/index');
    }

    public function queryWaktuCicilan(){
        $waktuAngsuran = $this->ModelUtama->tampilBanyakBaris("ref_waktu_angsuran", "*", array("aktif" => "Y"));
        return $waktuAngsuran;
    }

    public function create(){
        $random = generateRandomNumber('TPP');
        $cmbWaktuAngsuran = cmbQuery("lama_bulan", "0", $this->queryWaktuCicilan(), "id", "lama_bulan", "class='form-select form-select-sm form-control' style='width: 88%;' ", "Pilih Lama Angsuran", "","1", 0, 0, "Bulan");
        $data = array(
            'judul' => 'Form Pengajuan Pinjaman Anggota',
            "nik" => "",
            'nama' => "",
            'no_pengajuan' => $random,
            'tgl_pengajuan' => "",
            'refid_waktu_angsuran' => $cmbWaktuAngsuran,
            'nominal' => "",
            'ket' => "",
            'id' => "",
            'id_anggota' => ""
        );

        $this->hasPermissionView($this->modul,'admin/pinjaman/pengajuan/formPengajuan', $data);
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
                'field' => 'no_pengajuan',
                'label' => 'Nomor Transaksi Pengajuan',
                'rules' => 'required',
            ),
            array(
                'field' => 'tgl_pengajuan',
                'label' => 'Tanggal Transaksi Pengajuan',
                'rules' => 'required',
            ),
            array(
                'field' => 'lama_bulan',
                'label' => 'Waktu Angsuran',
                'rules' => 'required',
            ),
            array(
                'field' => 'nominal',
                'label' => 'Nominal',
                'rules' => 'required',
            ),
            array(
                'field' => 'nominal_Uang',
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
            $save = $this->pengajuan_model->create();
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

    public function view($viewOnly = 0){
        $pagePerHalaman = 5;
        $pageStart = 0;
        $likes = array();
        $wheres = array();
        $like = '';

        $pengajuanAnggota = $this->pengajuan_model->getAll('1', $likes, $like, $wheres, $pagePerHalaman, $pageStart);
        $totalData = $this->pengajuan_model->getAll('0', $likes, $like, $wheres, $pagePerHalaman, $pageStart);

        $data['viewOnly'] = $viewOnly;
        $data['pageStart'] = $pageStart;
        $data['pagePerHalaman'] = $pagePerHalaman;
        $data['pengajuanAnggota'] = $pengajuanAnggota;
        $data['totalData'] = $totalData;

        $json = [
            'data' => $this->load->view('admin/pinjaman/pengajuan/tablepengajuan', $data),
        ];
        echo json_encode($json);
    }

}