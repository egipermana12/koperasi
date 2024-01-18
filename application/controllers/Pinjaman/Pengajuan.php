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
            'status_pencairan' => 'belum',
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

    public function edit($id = null){
        if (!isset($id)) {
            redirect('Pinjaman/pengajuan');
        }


        $getData = $this->ModelUtama->tampilSatuBaris('t_pinjaman_pengajuan', "*", array("id" => $id));
        $findAnggota = $this->ModelUtama->tampilSatuBaris('anggota', '*', array('id' => $getData['id_anggota']));

        if(!$getData){
            show_404();
        }
        $disabled = 'disabled';
        $status_pencairan = $getData['status_pencairan'] == 'sudah' ? $disabled = 'disabled' : $disabled = '';

        $cmbWaktuAngsuran = cmbQuery("lama_bulan", $getData['refid_waktu_angsuran'], $this->queryWaktuCicilan(), "id", "lama_bulan", "class='form-select form-select-sm form-control' style='width: 88%;' $disabled ", "Pilih Lama Angsuran", "","1", 0, 0, "Bulan");
        $data = array(
            'judul' => 'Form Ubah Pengajuan Pinjaman Anggota',
            "nik" => $findAnggota['nik'],
            'nama' => $findAnggota['nama'],
            'no_pengajuan' => $getData['no_pengajuan'],
            'tgl_pengajuan' => $getData['tgl_pengajuan'],
            'refid_waktu_angsuran' => $cmbWaktuAngsuran,
            'nominal' => $getData['nominal'],
            'ket' => $getData['ket'],
            'status_pencairan' => $getData['status_pencairan'],
            'id' => $getData['id'],
            'id_anggota' => $findAnggota['id']
        );

        $this->hasPermissionView($this->modul,'admin/pinjaman/pengajuan/formPengajuan', $data);
    }

    public function update(){
        $id = $this->input->post('id');
        $getData = $this->ModelUtama->tampilSatuBaris('t_pinjaman_pengajuan', "*", array("id" => $id));
        $validator = array('success' => false, 'messages' => array());


        $this->form_validation->set_rules($this->validationMessageCreate());
        $this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');

        $status_pencairan = $getData['status_pencairan'];

        if ($this->form_validation->run() === true) {
            $save = $this->pengajuan_model->update($id);
            if($save === true){
                $validator['success'] = true;
                $validator['messages'] = "Data berhasil disimpan";
            }else{
                $validator['success'] = false;
                $validator['messages'] = "Error while inserting the information into the database";
            }
        }else if($status_pencairan == 'sudah'){
            $validator['success'] = false;
            $validator['messages'] = "Data sudah pencairan, tidak bisa diubah";
        }
        else{
            $validator['success'] = false;
            foreach ($_POST as $key => $value) {
                $validator['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($validator);
    }

    public function delete(){
        $validator = array('success' => false, 'messages' => array());
        $id = $this->input->post('id');
        $ids = explode(",", $id);

        $Kondisi = array(1=>1);
        $kondisi2 ="";
        $Kondisi2Val = array();
        if(!empty($id)){
            $kondisi2 .= " AND status_pencairan in (". $id .")";
        }
        $getData = $this->ModelUtama->tampilBanyakBaris('t_pinjaman_pengajuan', "status_pencairan", $Kondisi,$kondisi2. "ORDER BY id ASC");

        $status_pencairan = false;
        if (in_array('sudah', array_column($getData, 'status_pencairan'))) {
            $status_pencairan = true;
        } else {
            $status_pencairan = false;
        }

        $status = $this->getModul($this->modul);
        if($status != 1) {
            $validator['success'] = false;
            $validator['messages'] = "Anda tidak mempunyai hak akses";
        }else if($status_pencairan == true){
            $validator['success'] = false;
            $validator['messages'] = "Data yang sudah ada pencairan tidak bisa dihapus";
        }
        else{
            if(!empty($id)) {
                $delete = $this->pengajuan_model->delete($ids);
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