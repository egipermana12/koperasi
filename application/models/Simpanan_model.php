<?php

class Simpanan_model extends CI_Model{

    private $table = 't_simpanan';

    public function getAll($view = 1, $likes,$like,$wheres, $pagePerHalaman, $pageStart)
    {
        $query = $this->generateData($view, $likes,$like,$wheres, $pagePerHalaman, $pageStart);
        return $query;
    }

    public function generateData($view = 1, $likes,$like,$wheres, $pagePerHalaman, $pageStart)
    {
        $generate = $this->db
                    ->select($this->table.'.*, b.nik, b.nama, c.jns_simpanan, c.nominal as nomial_ref')
                    ->group_start()
                        ->like('b.nik', $like)
                        ->or_like('b.nama', $like)
                    ->group_end()
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

    public function create()
    {
        $post = $this->input->post(NULL, TRUE);
        $insert_data = array(
            'id_anggota' => $post["id_anggota"],
            'no_simpanan' => $post["no_simpanan"],
            'tgl_transaksi' => $post["tgl_transaksi"],
            'refid_jns_simpanan' => $post["jns_simpanan"],
            'ket' => $post["ket"],
            'nominal' => $post["nominal"],
            'uid_create' => $this->session->userdata['username']
        );
        $result = $this->db->insert($this->table, $insert_data);
        return $result;
    }

    public function update($id)
    {
        $post = $this->input->post(NULL, TRUE);
        $insert_data = array(
            'id_anggota' => $post["id_anggota"],
            'no_simpanan' => $post["no_simpanan"],
            'tgl_transaksi' => $post["tgl_transaksi"],
            'refid_jns_simpanan' => $post["jns_simpanan"],
            'ket' => $post["ket"],
            'nominal' => $post["nominal"],
            'uid_create' => $this->session->userdata['username']
        );
        $this->db->where('id', $id);
        $result = $this->db->update($this->table, $insert_data);
        return $result;
    }

    public function delete($id = array())
    {
        $this->db->where_in('id', $id);
        $result = $this->db->delete($this->table);
        return $result;
    }

}