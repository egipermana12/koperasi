<?php

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $this->template->load('template', 'admin/users/index');
    }

    public function new(){
        $data = [
            "judul" => "Tambah User Baru",
            "username" => "",
            "name" => "",
            "password" => "",
            "status" => "",
            "level" => "",
            "modul_anggota" => 0
        ];
        $json = [
            'data' => $this->load->view('admin/users/formUsers', $data),
        ];
        echo json_encode($json);
    }

    public function view($viewOnly = 0) {
        $pagePerHalaman = 5;
        $pageStart = 0;
        $likes = array();
        $wheres = array();

        //param post 
        $post = $this->input->post(NULL, TRUE);
        $pageStartPost = $post['pageStart'];
        $qUid = $post['username'];
        $qName = $post['name'];
        $qStatus = $post['status'];
        $qLevel = $post['level'];

        if($pageStartPost){
            $pageStart = $pageStartPost;
        }
        if(!empty($qUid)){
            $likes['username'] = $qUid;
        }
        if(!empty($qName)){
            $likes['name'] = $qUid;
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