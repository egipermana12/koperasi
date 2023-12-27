<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('auth_model');
	}
    public function index()
	{
		if($this->session->userdata('logged_in')){
			redirect("dashboard");
		}

		$this->load->view('login');
	}

	public function login(){
		$validator = array('success' => false, 'messages' => array());
		$uid = $this->input->post('username');
		$password = $this->input->post('password');
		$validate_data = array(
			array(
				'field' => 'username',
				'label' => 'username',
				'rules' => 'required'
			),
			array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required'
			)
		);
		$this->form_validation->set_rules($validate_data);
		$this->form_validation->set_error_delimiters('<p class="text-danger" style="font-size: 12px; font-weight: 500;">', '</p>');
		if ($this->form_validation->run() === true) {
			$user = $this->auth_model->loginAction($uid);
			if(empty($user)){
				$validator['success'] = false;
                $validator['messages'] = "User tidak ditemukan";
			}else{
				if(password_verify($password, $user->password)){
					$set_session = array(
						'logged_in' => true,
						'username' => $user->uid,
						'nama' => $user->nama,
						'level' => $user->level,
						'status' => $user->status,
						'modul_anggota' => $user->modul_anggota,
						'modul_users' => $user->modul_users,
						'modul_settings' => $user->modul_settings
					);
					$this->session->set_userdata($set_session);
					$validator['success'] = true;
                	$validator['messages'] = "dashboard"; // for redirect after login success
				}else{
					$validator['success'] = false;
                	$validator['messages'] = "Password tidak cocok";
				}
			}
		}else{
			$validator['success'] = false;
			foreach ($_POST as $key => $value) {
				$validator['messages'][$key] = form_error($key);
			}
		}
		echo json_encode($validator);
	}

	public function logout()
	{
		$this->session->sess_destroy(); // Hapus semua session
    	redirect('/'); // Redirect ke halaman logi
	}
}