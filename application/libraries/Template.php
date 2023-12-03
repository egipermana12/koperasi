<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Template {

	var $ci;
	var $template_data = array();

	function __construct() {
		$this->ci = &get_instance();
	}

	function set($name, $value) {
		$this->template_data[$name] = $value;
	}

	function load($template = '', $view = '', $view_data = array(), $return = FALSE) {
		$data['menu'] = $this->getMenu();
		$this->ci = &get_instance();
		$this->set('contents', $this->ci->load->view($view, $view_data, TRUE));
		return $this->ci->load->view($template, $this->template_data + $data, $return);
	}

	/**
	 * untuk menggenarate sidebar dari database
	 */
	function getMenu() {
		$this->ci->load->model('sidebar_model');
		$requery = $this->ci->sidebar_model->getMenu();
		return $requery;
	}

}
