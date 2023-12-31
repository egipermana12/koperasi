<?php

class Simpanan extends MY_Controller {
    var $modul = 'modul_master';
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('./referensi/refsimpanan_model');
    }

    public function index(){
        $this->genView($this->modul,'admin/referensi/simpanan/index');
    }

    public function create(){
        $data = array(
            'judul' => 'Tambah Jenis Simpanan Baru',
            'jns_simpanan' => '',
            'nominal' => '',
            'tampil' => 'Y',
            'id' => ''
        );
        $json = [
            'data' => $this->hasPermissionJSON($this->modul,'admin/referensi/simpanan/formSimpanan', $data),
        ];

        echo json_encode($json);
    }

    public function edit(){
        $id = $this->input->post('id');
        $getData = $this->ModelUtama->tampilSatuBaris('ref_jns_simpanan', "*", array("id" => $id));
        $data = array(
            'judul' => 'Edit Jenis Simpanan',
            'jns_simpanan' => $getData['jns_simpanan'],
            'nominal' => $getData['nominal'],
            'tampil' => $getData['tampil'],
            'id' => $id
        );
        $json = [
            'data' => $this->hasPermissionJSON($this->modul,'admin/referensi/simpanan/formSimpanan', $data),
        ];

        echo json_encode($json);
    }

    public function store(){
        $validator = array('success' => false, 'messages' => array());

        $validate_data = array(
            array(
                'field' => 'jns_simpanan',
                'label' => 'Jenis Simpanan',
                'rules' => 'required'
            ),
            array(
                'field' => 'nominal',
                'label' => 'Nominal',
                'rules' => 'required'
            ),
        );
        $this->form_validation->set_rules($validate_data);
        $this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');
        if ($this->form_validation->run() === true) {
            $save = $this->refsimpanan_model->create();
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
        $validator = array('success' => false, 'messages' => array());
        $validate_data = array(
            array(
                'field' => 'jns_simpanan',
                'label' => 'Jenis Simpanan',
                'rules' => 'required'
            ),
            array(
                'field' => 'nominal',
                'label' => 'Nominal',
                'rules' => 'required'
            ),
        );
        $this->form_validation->set_rules($validate_data);
        $this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');
        if ($this->form_validation->run() === true) {
            $update = $this->refsimpanan_model->update($id);
            if ($update == true) {
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
                $delete = $this->refsimpanan_model->delete($ids);
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

        //param post
        $pageStartPost = $this->input->post('pageStart');
        $qSimpanan = $this->input->post('jns_simpanan');

        if($pageStartPost){
            $pageStart = $pageStartPost;
        }
        if(!empty($qSimpanan)){
            $likes['jns_simpanan'] = $qSimpanan;
        }

        $refSimpanan = $this->refsimpanan_model->getAll('1', $likes, $wheres, $pagePerHalaman, $pageStart);
        $totalData = $this->refsimpanan_model->getAll('0', $likes, $wheres, $pagePerHalaman, $pageStart);

        $data['viewOnly'] = $viewOnly;
        $data['simpanans'] = $refSimpanan;
        $data['totalData'] = $totalData;
        $data['pageStart'] = $pageStart;
        $data['pagePerHalaman'] = $pagePerHalaman;
        $data['jns_simpanan'] = $qSimpanan;

        $json = [
            'data' => $this->load->view('admin/referensi/simpanan/table', $data),
        ];
        echo json_encode($json);
    }
}