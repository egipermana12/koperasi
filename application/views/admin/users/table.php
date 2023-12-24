<?php

$level = array("2" => "Operator", "1" => "Administrator");
$status = array("2" => "Disable", "1" => "Enable");

$stOpLevel = '';
foreach ($level as $key => $val) {
    $selected = $qLevel == $key ? "selected" : "";
    $stOpLevel .= "<option value = " . $key . " " . $selected . " > " . $val . " </option>";
}

$stOpStatus = '';
foreach ($status as $key => $val) {
    $selected = $qStatus == $key ? "selected" : "";
    $stOpStatus .= "<option value = " . $key . " " . $selected . " > " . $val . " </option>";
}

$html = [];
$btnDiv = "";
if ($viewOnly) {

} else {
    $btnDiv .= '
    <div class="d-flex justify-content-end align-items-center gap-2">
        <button id="btnDelete" disabled type="button" class="btn btn-secondary text-white btn-sm fw-bold btnDelete" onclick="hapusData()"><i class="fa fa-trash "></i>&nbsp;Hapus</button>
        <button id="btnEdit" disabled type="button" class="btn btn-secondary text-white btn-sm fw-bold btnEdit" onclick="editData()"><i class="fa fa-pencil fs-6"></i>&nbsp;Ubah</button>
        <a href="javascript:showModal()"><button type="button" class="btn btn-primary text-white btn-sm fw-bold"><i class="fa fa-plus fs-5"></i>&nbsp;Baru</button></a>
    </div>
    ';
};

$html[] =
'
<div class="card my-4">
    <div class="card-body">
        <div class="row mb-4 align-items-center">
            <div class="col-6">
                <h6 class="text-muted">Daftar Users</h6>
            </div>
            <div class="col-6">
                ' . $btnDiv . '
            </div>
        </div>
        <div class="d-flex justify-content-start align-items-center gap-4">
            '.InputType("text", "usernameCari", "username", $qUid, "class='form-control form-control-sm' placeholder='Cari UserID' ").'
            '.InputType("text", "namaCari", "nama", $qName, "class='form-control form-control-sm' placeholder='Cari Nama User' ").'
            <select class="form-control form-select-sm" style="width: 50%" id="levelCari" name="level">
                <option value="">Level User</option>
                '.$stOpLevel.'
            </select>
            <select class="form-control form-select-sm" style="width: 50%" id="statusCari" name="status">
                <option value="" selected>Status User</option>
                '.$stOpStatus.'
            </select>
            <button type="button" onclick="refresh();" class="btn btn-secondary btn-sm small d-flex justify-content-center align-items-center" style="width: 200pt;"><i class="fa fa-search"></i>&nbsp;Cari</button>
        </div>
        <div class="my-4 overflow-x-auto">
            <table class="table table-sm table-striped table-hover table-bordered table-responsive">
                <thead class="font-medium text-muted bg-success">
                    <tr class="small text-white">
                        <th rowspan="2" class="text-center align-middle"><input class="form-check-input text-center" type="checkbox" name="check-all" id="check-all" onclick="checkedAll()"/></th>
                        <th rowspan="2" class="text-center align-middle" width="20%">Username/ Nama Lengkap</th>
                        <th rowspan="2" class="text-center align-middle" width="10%">Level</th>
                        <th rowspan="2" class="text-center align-middle" width="10%">Status</th>
                        <th class="text-center" colspan="3" width="60%">Modul</th>
                    </tr>
                    <tr class="small text-white border-bottomku">
                        <th class="text-center">Anggota</th>
                        <th class="text-center">User</th>
                        <th class="text-center">User</th>
                    </tr>
                </thead>
                <tbody>';
if(count($users) > 0) {
    foreach ($users as $key => $value) {
        $html[] ='
                    <tr class="small">
                        <td><input class="form-check-input text-center" type="checkbox" id="check" name="check" value="' . $value['uid'] . '" onclick="setChecklist(this)"  /></td>
                        <td class="text-body-tertiary">'.$value["uid"].'<br> '.$value["nama"].' </td>
                        <td class="text-body-tertiary">'.convertLevel($value["level"]).'</td>
                        <td class="text-body-tertiary text-center">'.convertStatus($value["status"]).'</td>
                        <td class="text-body-tertiary text-center">'.convertModul($value["modul_anggota"]).'</td>
                        <td class="text-body-tertiary text-center">'.convertModul($value["modul_anggota"]).'</td>
                        <td class="text-body-tertiary text-center">'.convertModul($value["modul_anggota"]).'</td>
                    </tr>
        ';
    }
}else{
    $html[] = '<tr><td colspan="7" class="text-center small text-muted">Tidak ada data</td></tr>';
}

$html[] ='
                </tbody>
            </table>
        ';
/**
 * counting and generating paginasi
 */

$html[] = paginasi($pageStart, $pagePerHalaman, $totalData);

$html[] ='
        </div>
    </div>
</div>
<input type="hidden" id="pageStart" value=' . $pageStart . ' name="pageStart" />
<input type="hidden" id="pagePerHalaman" value=' . $pagePerHalaman . ' name="pagePerHalaman" />
<script type="text/javascript">
    var filter = 1;
    var Form = new FormData();

    setForm = function()
    {
        Form = new FormData();
        Form.append("pageStart", $("#pageStart").val() );
        Form.append("level", $("#levelCari").val() );
        Form.append("status", $("#statusCari").val() );
        Form.append("usernameCari", $("#username").val() );
        Form.append("namaCari", $("#nama").val() );
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
</script>
'
;

$err = "";

$content = implode('', $html);

echo json_encode(array('content' => $content, 'error' => $err));

    function convertLevel($jns) {
       switch ($jns) {
           case '1':
           return "<span class='text-success'>Administrator</span>";
           case '2':
           return "<span class='text-secondary'>Operator</span>";
           default:
           return "<span class='text-secondary'>Operator</span>";
       }
   }

   function convertStatus($jns) {
       switch ($jns) {
           case '1':
           return "<span class='text-secondary'>Enable</span>";
           case '2':
           return "<span class='text-danger'>Disable</span>";
           default:
           return "<span class='text-danger'>Disable</span>";
       }
   }

   function convertModul($jns) {
       switch ($jns) {
           case '1':
           return "<span class='text-success'>Write</span>";
           case '2':
           return "<span class='text-secondary'>Read</span>";
           default:
           return "<span class='text-danger'>Disable</span>";
       }
   }