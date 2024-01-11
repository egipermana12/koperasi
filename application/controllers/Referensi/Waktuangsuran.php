<?php

class Waktuangsuran extends MY_Controller {
    var $modul = 'modul_master';
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('./referensi/refwaktuangsuran_model');
        $this->load->model('./referensi/refsimpanan_model');
    }

    public function index(){
        $this->genView($this->modul,'admin/referensi/waktuangsuran/indexangsuran');
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

        $data['viewOnly'] = $viewOnly;

        $json = [
            'data' => $this->load->view('admin/referensi/waktuangsuran/tablewaktuangsuran', $data),
        ];
        echo json_encode($json);
    }
}