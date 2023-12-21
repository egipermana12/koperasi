<?php

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index() {
        $this->template->load('template', 'admin/users/index');
    }

    public function view($viewOnly = 0) {
        $pagePerHalaman = 5;
        $pageStart = 0;
        $like = '';
        $wheres = array();


        $data['viewOnly'] = $viewOnly;

        $json = [
            'data' => $this->load->view('admin/users/table', $data),
        ];
        echo json_encode($json);
    }
}