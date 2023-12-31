<?php

class Refsimpanan_model extends CI_Model {
    private $table = 'ref_jns_simpanan';

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
            $result = $generate->order_by('jns_simpanan', 'ASC')->limit($pagePerHalaman, $pageStart)->get($this->table)->result_array();
        }else{
            $result = $generate->from($this->table)->count_all_results();
        }

        return $result;
    }

    public function create()
    {
        $post = $this->input->post(NULL, TRUE);
        $insertData = array(
            'jns_simpanan' => $post["jns_simpanan"],
            'nominal' => $post["nominal"],
            'tampil' => $post["tampil"]
        );
        $result = $this->db->insert($this->table, $insertData);
        return $result;
    }

    public function update($id)
    {
        $post = $this->input->post(NULL, TRUE);
        $insertData = array(
            'jns_simpanan' => $post["jns_simpanan"],
            'nominal' => $post["nominal"],
            'tampil' => $post["tampil"]
        );
        $this->db->where('id', $id);
        $result = $this->db->update($this->table, $insertData);
        return $result;
    }

    public function delete($id = array())
    {
        $this->db->where_in('id', $id);
        $result = $this->db->delete($this->table);
        return $result;
    }
}