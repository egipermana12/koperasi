<?php

class Users_model extends CI_Model
{
    private $table = 'users';

    public function getAll($view = 1, $likes, $wheres, $pagePerHalaman, $pageStart)
    {
        $query = $this->generateData($view, $likes, $wheres, $pagePerHalaman, $pageStart);
        return $query;
    }

    //$array = array('BookName' => 'Power', 'Author' => 'e', 'ISBN' => '14');
    //$this->db->like($array);

    public function generateData($view = 1, $likes, $wheres, $pagePerHalaman, $pageStart)
    {
        $generate = $this->db
                    ->select('*')
                    ->like($likes)
                    ->where($wheres);
        if($view == 1){
            $result = $generate->order_by('nama', 'ASC')->limit($pagePerHalaman, $pageStart)->get($this->table)->result_array();
        }else{
            $result = $generate->order_by('id', 'DESC')->get($this->table)->result();
        }

        return $result;
    }
}