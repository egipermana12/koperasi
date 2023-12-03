<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sidebar_model extends CI_Model
{
    public function getMenu()
    {
        $this->db->select('*');
        $this->db->from('menu_sidebar_panel');
        $this->db->join('menu_kategori_panel', 'menu_kategori_panel.id_menu_kat_panel = menu_sidebar_panel.id_menu_kategori', 'left');
        $this->db->where('menu_kategori_panel.aktif', 'Y');
        $this->db->order_by('menu_kategori_panel.urut', 'ASC');
        $this->db->order_by('menu_sidebar_panel.urut', 'ASC');
        $queryRes = $this->db->get()->result_array();

        $menu = array();
        foreach ($queryRes as $val) {
            $menu[$val['id_menu_panel']] = $val;
            $menu[$val['id_menu_panel']]['higlight'] = 0;
            $menu[$val['id_menu_panel']]['depth'] = 0;
        }

        $menu_kategori = [];
        foreach ($menu as $id_menu_panel => $val) {
            if (!$id_menu_panel)
                continue;

            $menu_kategori[$val['id_menu_kategori']][$val['id_menu_panel']] = $val;
        }

        $kategoriMenu = $this->db->select('*');
        $kategoriMenu->from('menu_kategori_panel');
        $kategoriMenu->where('aktif', 'Y');
        $kategoriMenu->order_by('urut', 'ASC');
        $queryKatMenu = $kategoriMenu->get()->result_array();

        $result = array();
        foreach ($queryKatMenu as $val) {
            if (key_exists($val['id_menu_kat_panel'], $menu_kategori)) {
                $result[$val['id_menu_kat_panel']] = array('kategori' => $val, 'menu' => $menu_kategori[$val['id_menu_kat_panel']]);
            };
        }

        // echo '<pre>';
        // print_r($result);
        // die;
        return $result;
    }
}
