<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * untuk menggenate menu
 */

function menu_list($menu) {
	$refs = array();
	$list = array();

	foreach ($menu as $key => $val) {
		if (!$key || empty($val['id_menu_panel'])) {
			continue;
		}

		$thisref = &$refs[$val['id_menu_panel']];

		foreach ($val as $fields => $value) {
			$thisref[$fields] = $value;
		}

		if ($val['id_parent'] == 0) {
			$list[$val['id_menu_panel']] = &$thisref;
		} else {
			$thisref['depth'] = ++$refs[$val['id_menu_panel']]['depth'];
			$refs[$val['id_parent']]['children'][$val['id_menu_panel']] = $thisref;
		}
	}

	return $list;
}

function build_menu($arr_menu, $sub_menu = false) {

	$ci = get_instance();
	$currentUri = $ci->uri->segment(1);
	$menu = '';
	foreach ($arr_menu as $key => $val) {
		if (!$key) {
			continue;
		}

		$url = base_url($val['url']);

		$classActive = '';
		if ($val['url'] == '/' . $currentUri) {
			$classActive = "text-light bg-secondary";
		}

		$menu .= '<a class="nav-link ' . $classActive . ' " href="' . $url . '">';
		$menu .= '<div class="sb-nav-link-icon"><i class=" ' . $val['class'] . ' ' . $classActive . ' "></i></div>';
		$menu .= $val['nama_menu'];
		$menu .= '</a>';
	}
	return $menu;
}

function tgl_short($tgl) {
	$bulan = array(
		1 => 'Jan',
		'Feb',
		'Mar',
		'Apr',
		'Mei',
		'Jun',
		'Jul',
		'Agu',
		'Sep',
		'Okt',
		'Nov',
		'Des',
	);
	$split = explode('-', $tgl);
	return $split[2] . ' ' . $bulan[(int) $split[1]] . ' ' . $split[0];
}

function InputType($type = "text", $id, $name, $value, $attr) {
	return "<input type='$type' id='$id' name='$name' value='$value' $attr />";
}

function LabelInput($for, $value, $warning = '') {
	return "<label for='$for' class='form-label small text-gray-800 fw-semibold'> $value <span class='text-danger'>$warning</span></label>";
}

function Div($class, $element) {
	return "<div class='" . $class . "'>" . $element . "</div>";
}

function imagePrev($width = '250px', $height = '160px') {
	return "
    <div class='position-relative border rounded bg-secondary bg-opacity-10 d-flex justify-content-center align-items-center'
    style='width: $width; height: $height'>
    <label for='file-input' class='position-absolute bg-light border border-secondary rounded-circle d-flex align-items-center justify-content-center p-1 text-center' style='top: -4px; right: -8px; cursor: pointer; width: 28px; height: 28px;'>
        <i class='fa fa-pencil text-secondary' style='font-size: .6rem;'></i>
    </label>
    <input class='w-full h-full' type='file' id='file-input' accept='image/`*' hidden /><div class='border rounded'>
    <div id='imagePreview' class='d-flex justify-content-center align-items-center' style='background-size: cover; background-repeat: no-repeat; background-position: center; width: $width; height: $height'>
        <i class='fas fa-fw fa-image text-muted' style='font-size: 1.7rem;'></i>
    </div>
    </div>
    </div>";
}
