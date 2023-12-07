<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anggota_model extends CI_Model
{
    private $table = 'anggota';

    public function getAll($status, $likes, $wheres, $pagePerHalaman, $pageStart)
    {
        $query = $this->generateData($status, $likes, $wheres, $pagePerHalaman, $pageStart);
        return $query;
    }

    public function generateData($status, $likes, $wheres, $pagePerHalaman, $pageStart)
    {
        $generate = $this->db->group_start()
            ->like('nik', $likes)
            ->or_like('nama', $likes)
            ->group_end()
            ->where($wheres);
        if ($status == 1) {
            $result = $generate->order_by('id', 'DESC')->limit($pagePerHalaman, $pageStart)->get($this->table)->result_array();
        } else {
            $result = $generate->from($this->table)->count_all_results();
        }
        return $result;
    }

    public function create(){
        $post = $this->input->post(NULL, TRUE);

        $insert_data = array(
            'nik' => $post['nik'],
            'nama' => $post['nama'],
            'tgl_lahir' => $post['tgl_lahir'],
            'jns_kelamin' => $post['jns_kelamin'],
            'alamat' => $post['alamat'],
            'kd_kec' => $post['kode_kecamatan'],
            'kd_desa' => $post['kode_kelurahan'],
            'pekerjaan' => $post['pekerjaan'],
            'tgl_gabung' => $post['tgl_gabung'],
            'status' => $post['status'],
            'tgl_gabung' => $post['tgl_gabung'],
        );
        $result = $this->db->insert($this->table, $insert_data);
        return $result;
    }
}
