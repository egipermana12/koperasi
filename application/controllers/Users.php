<?php

class Users extends MY_Controller {
    var $modul = 'modul_users';

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $this->genView($this->modul,'admin/users/index');
    }

    public function new(){
        $data = [
            "judul" => "Tambah User Baru",
            "username" => "",
            "nama" => "",
            "password" => "",
            "status" => "1",
            "level" => "2",
            "modul_anggota" => 0,
            "id" => ""
        ];
        $json = [
            'data' => $this->hasPermissionJSON($this->modul,'admin/users/formUser', $data),
        ];

        echo json_encode($json);
    }

    public function edit(){
        $uid = $this->input->post('uid');
        $getData = $this->ModelUtama->tampilSatuBaris('users', "*", array("uid" => $uid));

        $data = [
            "judul" => "Edit Data User",
            "username" => $getData['uid'],
            "nama" => $getData['nama'],
            "password" => "",
            "status" => $getData['status'],
            "level" => $getData['level'],
            "modul_anggota" => $getData['modul_anggota'],
            "id" => $getData['uid']
        ];
        $json = [
            'data' => $this->hasPermissionJSON($this->modul,'admin/users/formUser', $data),
        ];
        echo json_encode($json);
    }

    public function store(){
        $validator = array('success' => false, 'messages' => array());

        $validate_data = array(
            array(
                'field' => 'uid',
                'label' => 'username',
                'rules' => 'required|is_unique[users.uid]'
            ),
            array(
                'field' => 'nama',
                'label' => 'Nama Lengkap',
                'rules' => 'required'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required'
            ),
        );
        $this->form_validation->set_rules($validate_data);
        $this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');
        if ($this->form_validation->run() === true) {
            $save = $this->users_model->create();
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
        $unique = "";
        $id = $this->input->post('id');
        if(!empty($id)){
            $getData = $this->ModelUtama->tampilSatuBaris('users', "*", array("uid" => $id));
            if($getData["uid"] === $id){
                $rules = 'required';
            }else{
                $rules = 'required|is_unique[users.uid]';
            }
        }

        $validator = array('success' => false, 'messages' => array());

        $validate_data = array(
            array(
                'field' => 'uid',
                'label' => 'username',
                'rules' => $unique
            ),
            array(
                'field' => 'nama',
                'label' => 'Nama Lengkap',
                'rules' => 'required'
            )
        );
        $this->form_validation->set_rules($validate_data);
        $this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');
        if ($this->form_validation->run() === true) {
            $save = $this->users_model->update($id);
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

    public function delete()
    {
        $validator = array('success' => false, 'messages' => array());

        $status = $this->getModul($this->modul);
        if($status != 1) {
            $validator['success'] = false;
            $validator['messages'] = "Anda tidak mempunyai hak akses";
        }else{
            $uid = $this->input->post('uid');
            $uids = explode(",", $uid);

            if(!empty($uid)) {
                $delete = $this->users_model->delete($uids);
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

    public function view($viewOnly = 0) {
        $pagePerHalaman = 5;
        $pageStart = 0;
        $likes = array();
        $wheres = array();

        //param post 
        $pageStartPost = $this->input->post('pageStart');
        $qUid = $this->input->post('username');
        $qName = $this->input->post('nama');
        $qStatus = $this->input->post('status');
        $qLevel = $this->input->post('level');

        if($pageStartPost){
            $pageStart = $pageStartPost;
        }
        if(!empty($qUid)){
            $likes['uid'] = $qUid;
        }
        if(!empty($qName)){
            $likes['nama'] = $qName;
        }
        if(!empty($qStatus)){
            $wheres['status'] = $qStatus;
        }
        if(!empty($qLevel)){
            $wheres['level'] = $qLevel;
        }

        $dataUser = $this->users_model->getAll('1', $likes, $wheres, $pagePerHalaman, $pageStart);
		$totalData = $this->users_model->getAll('0', $likes, $wheres, $pagePerHalaman, $pageStart);

        $data['viewOnly'] = $viewOnly;
        $data['users'] = $dataUser;
        $data['qUid'] = $qUid;
        $data['qName'] = $qName;
        $data['qStatus'] = $qStatus;
        $data['qLevel'] = $qLevel;
        $data['pageStart'] = $pageStart;
        $data['pagePerHalaman'] = $pagePerHalaman;
		$data['totalData'] = $totalData;

        $json = [
            'data' => $this->load->view('admin/users/table', $data),
        ];
        echo json_encode($json);
    }
}