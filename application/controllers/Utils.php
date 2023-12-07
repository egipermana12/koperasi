<?php

class Anggota extends MY_Controller {
    public function pilihKecamatan(){
        $DEF_PROVINSI = $this->ModelUtama->SETTING("DEF_PROVINSI");
		$DEF_KABUPATEN = $this->ModelUtama->SETTING("DEF_KABUPATEN");
		$queryKecamatan = $this->ModelUtama->tampilBanyakBaris("ref_wilayah", "*", array("kode_provinsi" => $DEF_PROVINSI, "kode_kota" => $DEF_KABUPATEN, "CAST(kode_kelurahan as UNSIGNED)"=>0),"AND CAST(kode_kecamatan as UNSIGNED)!=0");
		$cmbKecamatan = cmbQuery("kode_kecamatan", "0", $queryKecamatan, "kode_kecamatan", "nama", "class='form-select form-select-sm' onchange = 'pilihKecamatan()' ", "Pilih Kecamatan", "0","1", 1, 0);

        $data = array(
            "cmbKecamatan" => $cmbKecamatan,
        );

        return $this->load->view('_components/selectkota', );
    }
}