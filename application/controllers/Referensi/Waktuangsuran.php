<?php

class Waktuangsuran extends MY_Controller {
    private $modul = 'modul_master';
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('./referensi/refwaktuangsuran_model');
    }

    public function index(){
        $this->genView($this->modul,'admin/referensi/waktuangsuran/indexangsuran');
    }

    public function create(){
        $data = array(
            'judul' => 'Tambah Waktu Angsuran Baru',
            'lama_bulan' => '',
            'aktif' => 'Y',
            'id' => ''
        );
        $json = [
            'data' => $this->hasPermissionJSON($this->modul,'admin/referensi/waktuangsuran/formAngsuran', $data),
        ];

        echo json_encode($json);
    }

    public function edit(){
        $id = $this->input->post('id');
        $getData = $this->ModelUtama->tampilSatuBaris('ref_waktu_angsuran', "*", array("id" => $id));
        $data = array(
            'judul' => 'Edit Waktu Angsuran',
            'lama_bulan' => $getData['lama_bulan'],
            'aktif' => $getData['aktif'],
            'id' => $id
        );
        $json = [
            'data' => $this->hasPermissionJSON($this->modul,'admin/referensi/waktuangsuran/formAngsuran', $data),
        ];

        echo json_encode($json);
    }

    public function store(){
        $validator = array('success' => false, 'messages' => array());

        $validate_data = array(
            array(
                'field' => 'lama_bulan',
                'label' => 'Jenis Simpanan',
                'rules' => 'required|numeric|greater_than[0]|less_than[13]'
            )
        );
        $this->form_validation->set_rules($validate_data);
        $this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');
        if ($this->form_validation->run() === true) {
            $save = $this->refwaktuangsuran_model->create();
            if ($save == true) {
                $validator['success'] = true;
                $validator['messages'] = "Data berhasil disimpan";
            } else {
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

    public function update(){
        $id = $this->input->post('id');
        $rules = "";
        if(!empty($id)){
            $getData = $this->ModelUtama->tampilSatuBaris('ref_waktu_angsuran', "*", array("id" => $id));
            if($getData["id"] === $id){
                $rules = 'required|numeric|greater_than[0]|less_than[13]';
            }else{
                $rules = 'required|numeric|greater_than[0]|less_than[13]|is_unique[ref_waktu_angsuran.lama_bulan]';
            }
        }

        $validator = array('success' => false, 'messages' => array());
        $validate_data = array(
            array(
                'field' => 'lama_bulan',
                'label' => 'Jenis Simpanan',
                'rules' => $rules
            )
        );
        $this->form_validation->set_rules($validate_data);
        $this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');
        if ($this->form_validation->run() === true) {
            $save = $this->refwaktuangsuran_model->update($id);
            if ($save == true) {
                $validator['success'] = true;
                $validator['messages'] = "Data berhasil diupdate";
            } else {
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

        //param post
        $pageStartPost = $this->input->post('pageStart');

        if($pageStartPost){
            $pageStart = $pageStartPost;
        }

        $refwaktuangsuran = $this->refwaktuangsuran_model->getAll('1', $likes, $wheres, $pagePerHalaman, $pageStart);
        $totalData = $this->refwaktuangsuran_model->getAll('0', $likes, $wheres, $pagePerHalaman, $pageStart);

        $data['viewOnly'] = $viewOnly;
        $data['Waktuangsurans'] = $refwaktuangsuran;
        $data['totalData'] = $totalData;
        $data['pageStart'] = $pageStart;
        $data['pagePerHalaman'] = $pagePerHalaman;

        $json = [
            'data' => $this->load->view('admin/referensi/waktuangsuran/tablewaktuangsuran', $data),
        ];
        echo json_encode($json);
    }

    public function delete()
    {
        $validator = array('success' => false, 'messages' => array());

        $status = $this->getModul($this->modul);
        if($status != 1) {
            $validator['success'] = false;
            $validator['messages'] = "Anda tidak mempunyai hak akses";
        }else{
            $id = $this->input->post('id');
            $ids = explode(",", $id);

            if(!empty($id)) {
                $delete = $this->refwaktuangsuran_model->delete($ids);
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

}