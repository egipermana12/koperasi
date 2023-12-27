<?php

class MY_Controller extends CI_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->checkLogin();
	}

	public function checkLogin()
	{
		if(!$this->session->userdata('logged_in')){
			redirect("/");
		}
	}

	public function getModul($modul){
		$session = $this->session->userdata();
		$status = 0;
		if (array_key_exists($modul, $session)) {
		    $status = $session[$modul];
		}
		return $status;
	}

	public function genView($modul,$view, $data = array())
	{
		$status = $this->getModul($modul);
		if($status == 0) {
			$this->template->load('template', 'role_view');
		}
		else{
			$this->template->load('template', $view, $data);
		}
	}

	public function hasPermissionView($modul,$view, $data = array())
	{
		$status = $this->getModul($modul);
		if($status == 1) {
			$this->template->load('template', $view, $data);
		}
		else{
			$this->template->load('template', 'role_view');
		}
	}

	public function hasPermissionJSON($modul, $view, $data = array())
	{
		$status = $this->getModul($modul);
		if($status != 1){
			return $this->load->view('role_json');
		}else{
			return $this->load->view($view, $data);
		}
	}

}