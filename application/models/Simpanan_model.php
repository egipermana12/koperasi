<?php

class Simpanan_model extends CI_Model{

    private $table = 't_simpanan';

    public function getAll($view = 1, $likes, $wheres, $pagePerHalaman, $pageStart)
    {
        $query = $this->generateData($view, $likes, $wheres, $pagePerHalaman, $pageStart);
        return $query;
    }

    public function generateData($view = 1, $likes, $wheres, $pagePerHalaman, $pageStart)
    {
        $generate = $this->db
                    ->select($this->table.'.*, b.nik, b.nama, c.jns_simpanan, c.nominal as nomial_ref')
                    ->like($likes)
                    ->where($wheres)
                    ->join('anggota as b', 'b.id = ' .$this->table. '.id_anggota')
                    ->join('ref_jns_simpanan as c', 'c.id = ' .$this->table. '.refid_jns_simpanan');
        if($view == 1){
            $result = $generate->order_by('id', 'ASC')->limit($pagePerHalaman, $pageStart)->get($this->table)->result_array();
        }else{
            $result = $generate->from($this->table)->count_all_results();
        }

        return $result;
    }
}