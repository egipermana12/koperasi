<?php

class Pengajuan_model extends CI_Model{

    private $table = 't_pinjaman_pengajuan ';

    public function getAll($view = 1, $likes,$like,$wheres, $pagePerHalaman, $pageStart)
    {
        $query = $this->generateData($view, $likes,$like,$wheres, $pagePerHalaman, $pageStart);
        return $query;
    }

    public function generateData($view = 1, $likes,$like,$wheres, $pagePerHalaman, $pageStart)
    {
        $generate = $this->db
                    ->select($this->table.'.*, b.nik, b.nama, c.lama_bulan')
                    ->group_start()
                        ->like('b.nik', $like)
                        ->or_like('b.nama', $like)
                    ->group_end()
                    ->like($likes)
                    ->where($wheres)
                    ->join('anggota as b', 'b.id = ' .$this->table. '.id_anggota')
                    ->join('ref_waktu_angsuran as c', 'c.id = ' .$this->table. '.refid_waktu_angsuran');
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
            'no_pengajuan' => $post["no_pengajuan"],
            'tgl_pengajuan' => $post["tgl_pengajuan"],
            'refid_waktu_angsuran' => $post["lama_bulan"],
            'ket' => $post["ket"],
            'nominal' => $post["nominal"],
            'uid_create' => $this->session->userdata['username']
        );
        $result = $this->db->insert($this->table, $insert_data);
        return $result;
    }

}