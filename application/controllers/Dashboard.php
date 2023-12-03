<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$data['page_title'] = 'ada';
		$this->template->load('template', 'about', $data);
	}
}
