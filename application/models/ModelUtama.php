<?php
class ModelUtama extends CI_Model {

	var $TukCek = "";

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function queryTampilBanyakBaris($Tabel, $Field = "*", $FieldWhere = array(), $Value = array(), $Order = "") {
		$where = "";
		for ($i = 0; $i < count($FieldWhere); $i++) {
			$where .= $i != 0 ? " AND " : " WHERE ";
			$where .= $FieldWhere[$i] . "=?";
		}

		$sql = "SELECT $Field FROM $Tabel $where $Order";
		$query = $this->db->query($sql, $Value);

		$this->TukCek = $query;
		return $query->result();
	}

	public function tampilBanyakBaris($Tabel, $Field = "*", $Data = array(), $Order = "", $Value2 = array()) {
		$FieldWhere = array_keys($Data);
		$valueData = array_values($Data);

		if (count($Value2) > 0) {
			$valueData = array_merge($valueData, $Value2);
		}

		return $this->queryTampilBanyakBaris($Tabel, $Field, $FieldWhere, $valueData, $Order);
	}

	public function queryTampilSatuBaris($Tabel, $Field = "*", $FieldWhere = array(), $Value = array(), $Order = "") {
		$where = "";
		for ($i = 0; $i < count($FieldWhere); $i++) {
			$where .= $i != 0 ? " AND " : " WHERE ";
			$where .= $FieldWhere[$i] . "=?";
		}

		$sql = "SELECT $Field FROM $Tabel $where $Order";
		$query = $this->db->query($sql, $Value);

		$this->TukCek = $query;
		return $query->first_row('array');
	}

	public function tampilSatuBaris($Tabel, $Field = "*", $Data = array(), $Order = "", $Value2 = array()) {
		$FieldWhere = array_keys($Data);
		$valueData = array_values($Data);

		if (count($Value2) > 0) {
			$valueData = array_merge($valueData, $Value2);
		}

		return $this->queryTampilSatuBaris($Tabel, $Field, $FieldWhere, $valueData, $Order);
	}

	public function queryUpdateData($Tabel, $Data = array(), $Where = '', $ValWhere = array()) {
		$Field = array_keys($Data);
		$Value = array_values($Data);

		$Value = array_merge($Value, $ValWhere);

		$Fd = "";
		for ($i = 0; $i < count($Field); $i++) {
			$Fd .= $i != 0 ? ", " : "";
			$Fd .= "" . $Field[$i] . "=?";
		}

		$sql = "UPDATE $Tabel SET $Fd $Where";
		$query = $this->db->query($sql, $Value);

		$this->TukCek = $query;
		return $this->db->affected_rows();
	}

	public function SETTING($id = "") {
		$data = $this->tampilSatuBaris("settings", "value", array("id" => $id));
		return $data['value'];
	}
}
