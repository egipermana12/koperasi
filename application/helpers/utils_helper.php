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
	// echo '<pre>'; print_r($list);
	return $list;
}

function build_menu($arr_menu, $sub_menu = false) {

	$ci = get_instance();
	$currentUri = $ci->uri->segment(1);
	$currentUri2 = $ci->uri->segment(2);
	$menu = '';
	foreach ($arr_menu as $key => $val) {
		if (!$key) {
			continue;
		}

		$url = base_url($val['url']);
		$classActive = '';
		$paddingChild = '';
		$collapsed = 'collapsed';
		$stexpanded = false;
		$collapseChild = 'collapse';
		if ($val['url'] == '/' . $currentUri || $val['url'] == '/' . $currentUri . '/' .$currentUri2) {
			$classActive = "text-light bg-secondary";
		}

		if($val['id_parent'] != 0){
			$paddingChild = 'padding-left: 2rem !important';
		}

		if(key_exists('children', $val)){
			foreach($val['children'] as $ch){
				if ($ch['url'] == '/' . $currentUri || $ch['url'] == '/' . $currentUri . '/' .$currentUri2) {
					$collapsed = '';
					$stexpanded = true;
					$collapseChild = 'collapse show';
				}
			}
		}

		//if has child
		if(key_exists('children', $val)){
			$menu .= '<a class="nav-link '.$collapsed.'" href="#" data-bs-toggle="collapse" data-bs-target="#collapse'.$val['id_menu_kat_panel'].' " aria-expanded="'.$stexpanded.'" aria-controls="collapse'.$val['id_menu_kat_panel'].'">';
			$menu .= '<div class="sb-nav-link-icon"><i class="' . $val['class'] . '"></i></div>';
			$menu .= $val['nama_menu'];
			$menu .= '<div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>';
			$menu .= '</a>';
			$menu .= '<div class="'.$collapseChild.'" id="collapse'.$val['id_menu_kat_panel'].'" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">';
			$menu .= '<nav class="sb-sidenav-menu-nested nav" style="margin-left: 0 !important">';
			$menu .= build_menu($val['children']);
			$menu .= '</nav>';
			$menu .= '</div>';
		}else{
			$menu .= '<a class="nav-link ' . $classActive . ' " href="' . $url . '" style="'.$paddingChild.'">';
			$menu .= '<div class="sb-nav-link-icon"><i class=" ' . $val['class'] . ' ' . $classActive . ' "></i></div>';
			$menu .= $val['nama_menu'];
			$menu .= '</a>';
		}
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

function tgl_long($tgl) {
	$bulan = array(
		1 => 'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember',
	);
	$split = explode('-', $tgl);
	return $split[2] . ' ' . $bulan[(int) $split[1]] . ' ' . $split[0];
}

function convertStatusHelper($status){
	switch ($status) {
		case 'A':
		return "<span class='badge bg-success bg-opacity-10 text-success'> Aktif</span>";
		case 'N':
		return "<span class='badge bg-danger bg-opacity-10 text-danger'>Non Aktif</span>";
		default:
		return "<span class='label label-danger text-center small'></span>";
	}
}

function InputTypeHidden($name, $value){
	return '<input type="hidden" name="' . $name . '" value="' . $value . '" id="' . $name . '"/>';
}

function InputTypeUang($name, $value,$attr, $style = 'style="text-align:right;"'){
	$uangx = explode(",", FormatUang($value));
    $uang = floatval($value) != 0 ? $uangx[0] : "";
    $uangk = intval($uangx[1]) > 0 ? "," . $uangx[1] : "";
    return
        '<input type="text" '.$attr.' onkeypress="return isNumberKey(event)" onkeyup="inputCurrency(`' . $name . '`)" name="' . $name . '_Uang" id="' . $name . '_Uang" value="' . $uang . $uangk . '" ' . $style . ' />' .
        InputTypeHidden($name, $value);
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


function cmbQuery($id, $value, $query, $col1, $col2, $style, $labelAtas, $valueAtas, $pilihanAtas = 1, $withCodeInValue = 1, $withDataJSON = 0, $withText =""){
	$konten = '<select id="'.$id.'" name="'.$id.'" '.$style.' >';
	if($pilihanAtas) {
		$konten .= '<option value="'.$valueAtas.'">'.$labelAtas.'</option>';
	}
	$dataN = array();
	foreach($query as $qry){
		$dt = $qry;
		if(is_object($dt)){
			$dt = (array) $dt;
		}
		$selected = $value == $dt[$col1] ? "selected='selected'" : "";
		$valueData = $withCodeInValue == 1 ? $dt[$col1].". ".$dt[$col2] : $dt[$col2];

		$konten .= "<option value=".$dt[$col1]." ".$selected.">".$valueData." ".$withText."</option>";
		$dataN[$dt[$col1]] = $dt;
	}
	$konten .="</select>";
	if($withDataJSON){
		$konten .= "<span class='d-none' id='DataJSON_".$id."'>".json_encode($dataN)."</span>";
	}
	return $konten;
}

function imagePrev($width = '250px', $height = '160px') {
	return "
	<div class='position-relative border rounded bg-secondary bg-opacity-10 d-flex justify-content-center align-items-center'
	style='width: $width; height: $height'>
	<label for='file-input' class='position-absolute bg-light border border-secondary rounded-circle d-flex align-items-center justify-content-center p-1 text-center' style='top: -4px; right: -8px; cursor: pointer; width: 28px; height: 28px;'>
	<i class='fa fa-pencil text-secondary' style='font-size: .6rem;'></i>
	</label>
	<input class='w-full h-full' type='file' name='photo' id='file-input' accept='image/`*' hidden /><div class='border rounded'>
	<div id='imagePreview' class='d-flex justify-content-center align-items-center rounded' style='background-size: cover; background-repeat: no-repeat; background-position: center; width: $width; height: $height'>
	<i class='fas fa-fw fa-image text-muted' style='font-size: 1.7rem;'></i>
	</div>
	</div>
	</div>";
}

function imageFormDB($image = "", $width = '250px', $height = '160px'){
	$content = "<div class='position-relative border rounded bg-secondary bg-opacity-10 d-flex justify-content-center align-items-center'style='width: $width; height: $height'>";
	$content .= "<label for='file-input' class='position-absolute bg-light border border-secondary rounded-circle d-flex align-items-center justify-content-center p-1 text-center' style='top: -4px; right: -8px; cursor: pointer; width: 28px; height: 28px;'><i class='fa fa-pencil text-secondary' style='font-size: .6rem;'></i></label><input class='w-full h-full' type='file' name='photo' id='file-input' accept='image/`*' hidden />";
	if($image == ""){
		$content .= "<div class='border rounded'><div id='imagePreview' class='d-flex justify-content-center align-items-center rounded' style='background-size: cover; background-repeat: no-repeat; background-position: center; width: $width; height: $height'><i class='fas fa-fw fa-image text-muted' style='font-size: 1.7rem;'></i></div></div>";
	}else{
		$content .= "<div id='imagePreview' class='d-flex justify-content-center align-items-center rounded' style='background-size: cover; background-repeat: no-repeat; background-position: center; width: $width; height: $height'><img src='".$image."' class='d-flex justify-content-center align-items-center rounded' style='background-size: cover; background-repeat: no-repeat; background-position: center; width: $width; height: $height' /></div>";
	}
	$content .= "</div>";
	return $content;
}

function FormatUang($val){
	return number_format(floatval($val), 2, ",",".");
}

function generateRandomNumber($string){
    $ran = str_pad(rand(1,99999),5,'0',STR_PAD_LEFT);
    return $string .$ran;
}

function kekata($x) {
	    $x = abs($x);
	    $angka = array("", "satu", "dua", "tiga", "empat", "lima",
	    "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	    $temp = "";
	    if ($x <12) {
	        $temp = " ". $angka[$x];
	    } else if ($x <20) {
	        $temp = kekata($x - 10). " belas";
	    } else if ($x <100) {
	        $temp = kekata($x/10)." puluh". kekata($x % 10);
	    } else if ($x <200) {
	        $temp = " seratus" . kekata($x - 100);
	    } else if ($x <1000) {
	        $temp = kekata($x/100) . " ratus" . kekata($x % 100);
	    } else if ($x <2000) {
	        $temp = " seribu" . kekata($x - 1000);
	    } else if ($x <1000000) {
	        $temp = kekata($x/1000) . " ribu" . kekata($x % 1000);
	    } else if ($x <1000000000) {
	        $temp = kekata($x/1000000) . " juta" . kekata($x % 1000000);
	    } else if ($x <1000000000000) {
	        $temp = kekata($x/1000000000) . " milyar" . kekata(fmod($x,1000000000));
	    } else if ($x <1000000000000000) {
	        $temp = kekata($x/1000000000000) . " trilyun" . kekata(fmod($x,1000000000000));
	    }
	        return $temp;
	}


	function terbilang($x, $style=4) {
	    if($x<0) {
	        $hasil = "minus ". trim(kekata($x));
	    } else {
	        $hasil = trim(kekata($x));
	    }
	    switch ($style) {
	        case 1:
	            $hasil = strtoupper($hasil);
	            break;
	        case 2:
	            $hasil = strtolower($hasil);
	            break;
	        case 3:
	            $hasil = ucwords($hasil);
	            break;
	        default:
	            $hasil = ucfirst($hasil);
	            break;
	    }
	    return $hasil;
	}