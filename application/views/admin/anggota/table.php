<?php
$html = [];
$btnDiv = "";
if ($viewOnly) {
    $btnDiv .= '
    <div class="d-flex justify-content-end align-items-center gap-2">
        <button id="btnPilih" type="button" class="btn btn-primary text-white btn-sm fw-bold" onclick="getDataAnggota()"><i class="fas fa-user-tag"></i>&nbsp;Pilih Anggota</button>
    </div>
    ';
} else {
    $btnDiv .= '
        <div class="d-flex justify-content-end align-items-center">
            <div class="dropdown mx-2">
                <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-download"></i> <span>Export</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item small text-secondary" href="javascript:Export(`cetak`)"><i class="fa fa-print"></i>&nbsp;Print Data</a></li>
                    <li><a class="dropdown-item small text-secondary" href="'. base_url('anggota/excel') . '"><i class="fa fa-file-excel"></i>&nbsp;Excel</a></li>
                    <li><a class="dropdown-item small text-secondary" href="#""><i class="fa fa-file-pdf"></i>&nbsp;PDF</a></li>
                </ul>
            </div>
            <a href=" '. base_url('anggota/create') . ' "><button type="button" class="btn btn-primary btn-sm fw-semibold small"><i class="fa fa-plus-circle"></i>  Add Anggota</button></a>
        </div>';
}
;

$opAktif = array('A' => 'Aktif', 'N' => 'Non Aktif');

$stSelectOp = '';
foreach ($opAktif as $key => $val) {
	$selected = $qStanggota == $key ? "selected" : "";
	$stSelectOp .= "<option value = " . $key . " " . $selected . " > " . $val . " </option>";
}

$html[] = '
<div class="card my-4">
<div class="card-body">
<div class="row mb-4 align-items-center">
    <div class="col-6">
        <h6 class="text-muted">Daftar Anggota Koperasi</h6>
    </div>
    <div class="col-6">
        ' . $btnDiv . '
    </div>
</div>
<div class="row g-3 mb-4">
<div class="col-12">
<div class="d-flex justify-content-start align-items-center gap-2">
<div class="">
<input type="text" class="form-control form-control-sm" id="qAnggota" value="' . $qAnggota . '" placeholder="Cari Anggota" name="qAnggota" aria-label="First name">
</div>
<div class="">
<input type="text" value="' . $qTglGabung . '" id="datepicker" class="form-control form-control-sm qTglGabung" placeholder="Tanggal Gabung" name="qTglGabung">
</div>
<div class="">
<select class="form-control form-select-sm" id="qStanggota" name="qStanggota" aria-label="Default select example">
<option value="">Filter Status</option>
' . $stSelectOp . '
</select>
</div>
<div class="">
<button type="button" onclick="refresh();" class="btn btn-success-25 btn-sm small"><i class="fa fa-refresh"></i>
Tampilkan</button>
</div>
</div>
</div>
</div>
<table class="table table-sm table-striped table-hover table-responsive">
<thead class="font-medium text-muted">
<tr class="small">
<th><input class="form-check-input text-center" type="checkbox" name="check-all" id="check-all" onclick="checkedAll()" /></th>
<th class="text-center" width="15%">NIK</th>
<th class="text-center" width="25%">Nama Anggota</th>
<th class="text-center" width="25%">Alamat</th>
<th class="text-center" width="15%">Tanggal Gabung</th>
<th class="text-center" width="10%">Status</th>
<th class="text-center" width="10%">Actions</th>
</tr>
</thead>
<tbody>
';

if(count($anggotas) > 0) {

    foreach ($anggotas as $anggota) {
       $gender = convertGender($anggota['jns_kelamin']);
       $status = convertStatus($anggota['status']);
       $html[] = '
       <tr class="small">
       <td><input class="form-check-input text-center" type="checkbox" id="check" name="check" value="' . $anggota['id'] . '"  /></td>
       <td class="text-body-tertiary py-2">' . $anggota['nik'] . '</td>
       <td class="text-body-tertiary py-2">' . $anggota['nama'] . '</td>
       <td class="text-body-tertiary py-2">' . $anggota['alamat'] . '</td>
       <td class="text-body-tertiary py-2">' . tgl_short($anggota['tgl_gabung']) . '</td>
       <td class="text-center py-2">' . $status . '</td>
       <td class="text-center">
       <div class="dropdown">
       <button class="btn btn-sm btn-light-100 bg-transparent dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
       <span class="small">Actions</span>
       </button>
       <ul class="dropdown-menu">
       <li><a class="dropdown-item small" href="' . base_url("anggota/edit/" . $anggota['id'] ) . '">Edit</a></li>
       <li><a class="dropdown-item small" href="#">Delete</a></li>
       </ul>
       </div>
       </td>
       </tr>
       ';
   }
}else{
    $html[] = '<tr><td colspan="7" class="text-center small text-muted">Tidak ada data</td></tr>';
}

$html[] = '
<tbody>
</table>
';

/**
 * counting and generating paginasi
 */

$html[] = paginasi($pageStart, $pagePerHalaman, $totalData);

$html[] = '
</div>
</div>
<div>
<input type="hidden" id="pageStart" value=' . $pageStart . ' name="pageStart" />
<input type="hidden" id="pagePerHalaman" value=' . $pagePerHalaman . ' name="pagePerHalaman" />
</div>
<script type="text/javascript">
$("#datepicker").datepicker({
    dateFormat: "yy-mm-dd",
    changeMonth: true,
    changeYear: true,
    yearRange: "-5:+00",
    showOn: "button",
    buttonImage: "/assets/vendors/jquery-ui/images/calendar.gif",
    buttonImageOnly: true,
    });

    var filter = 1;
    var Form = new FormData();

    setForm = function()
    {
        Form = new FormData();
        Form.append("pageStart", $("#pageStart").val() );
        Form.append("qAnggota", $("#qAnggota").val() );
        Form.append("qTglGabung", $("#datepicker").val() );
        Form.append("qStanggota", $("#qStanggota").val() );
    }

    setData = function()
    {
        setForm();
        getData();
    }

    refresh = function()
    {
        filter = 1;
        setData();
    }

    PindahHalaman = function(pageStart)
    {
        $("#pageStart").val();
        getData(pageStart);
    }

    Export = function(opt){
        setForm();
        $.ajax({
            type:"POST",
            data: Form,
            processData: false,
            contentType: false,
            url: "'.base_url("anggota/export/").'" + opt,
            success: function(data){
                var res = JSON.parse(data);
                console.log(res);
                if(res.error === ""){
                   window.open(res.url, "_blank");
                }else{

                }
            }
        });
    }

    getDataAnggota = function(){
        let cek = cekJumlahData();
        if(cek != 1){
            alert_error("Harus Pilih Satu Data");
            idPilih = "";
            DataPilih = 0;
        }else{
            loading();
            var formData = new FormData();
            formData.append("id", idPilih);
            $.ajax({
                type:"POST",
                data:formData,
                url: "' . base_url("anggota/GetData") . '",
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success: function(data) {
                    var res = JSON.parse(data);
                    clearLoading();
                    if(res.error === ""){
                        getDataAfter(res);
                        idPilih = "";
                        DataPilih = 0;
                    }else{
                        alert_error(res.error);
                    }
                }
            });
        }
    }

    </script>
    ';

    $err = "";

    $content = implode('', $html);

    echo json_encode(array('content' => $content, 'error' => $err));

    function convertGender($jns) {
       switch ($jns) {
           case 'L':
           return "<span class='badge bg-primary bg-opacity-10 text-primary'>Laki - Laki</span>";
           case 'P':
           return "<span class='badge bg-info bg-opacity-10 text-info'>Perempuan</span>";
           default:
           return "<span class='label label-danger text-center small'></span>";
       }
   }

   function convertStatus($status) {
       switch ($status) {
           case 'A':
           return "<span class='badge bg-success bg-opacity-10 text-success py-2'> Aktif</span>";
           case 'N':
           return "<span class='badge bg-danger bg-opacity-10 text-danger py-2'>Non Aktif</span>";
           default:
           return "<span class='label label-danger text-center small'></span>";
       }
   }
