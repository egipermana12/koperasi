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
            $result = $generate->limit($pagePerHalaman, $pageStart)->get($this->table)->result_array();
        } else {
            $result = $generate->from($this->table)->count_all_results();
        }
        return $result;
    }
}
