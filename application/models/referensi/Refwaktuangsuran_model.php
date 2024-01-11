<?php

class Refwaktuangsuran_model extends CI_Model
{
    private $table = 'ref_waktu_angsuran';

    public function getAll($view = 1, $likes, $wheres, $pagePerHalaman, $pageStart)
    {
        $query = $this->generateData($view, $likes, $wheres, $pagePerHalaman, $pageStart);
        return $query;
    }

    public function generateData($view = 1, $likes, $wheres, $pagePerHalaman, $pageStart)
    {
        $generate = $this->db
                    ->select('*')
                    ->like($likes)
                    ->where($wheres);
        if($view == 1){
            $result = $generate->order_by('id', 'ASC')->limit($pagePerHalaman, $pageStart)->get($this->table)->result_array();
        }else{
            $result = $generate->from($this->table)->count_all_results();
        }

        return $result;
    }
}