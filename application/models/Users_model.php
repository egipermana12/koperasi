<?php

class Users_model extends CI_Model
{
    private $table = 'users';

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
            $result = $generate->order_by('nama', 'ASC')->limit($pagePerHalaman, $pageStart)->get($this->table)->result_array();
        }else{
            $result = $generate->from($this->table)->count_all_results();
        }

        return $result;
    }

    public function hasPassword($val)
    {
        return password_hash($val, PASSWORD_BCRYPT);
    }

    public function create()
    {
        $post = $this->input->post(NULL, TRUE);
        $password = $this->hasPassword($post["password"]);
        $insertData = array(
            'uid' => $post["uid"],
            'nama' => $post["nama"],
            'password' => $password,
            'level' => $post["level"],
            'status' => $post["status"],
            'modul_anggota' => $post["modul_anggota"]
        );
        $result = $this->db->insert($this->table, $insertData);
        return $result;
    }

    public function update($uid)
    {
        $post = $this->input->post(NULL, TRUE);
        $insertData = array(
            'uid' => $post["uid"],
            'nama' => $post["nama"],
            'level' => $post["level"],
            'status' => $post["status"],
            'modul_anggota' => $post["modul_anggota"]
        );
        $this->db->where('uid', $uid);
        $result = $this->db->update($this->table, $insertData);
        return $result;
    }

    public function delete($uid = array())
    {
        $this->db->where_in('uid', $uid);
        $result = $this->db->delete($this->table);
        return $result;
    }
}