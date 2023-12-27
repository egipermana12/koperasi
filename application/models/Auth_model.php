<?php

class Auth_model extends CI_Model
{
    private $table = 'users';

    public function loginAction($uid)
    {
        $this->db->where('uid', $uid);
        $result = $this->db->get($this->table)->row();
        return $result;
    }

}