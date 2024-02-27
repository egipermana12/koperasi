<?php

$html = [];
$btnDiv = "";

if ($viewOnly) {

} else {
    $btnDiv .= '
    <div class="d-flex justify-content-end align-items-center gap-1">
        <button id="btnAcc" disabled type="button" class="btn btn-secondary text-white btn-sm fw-bold btnAcc" onclick="accData()" data-bs-toggle="tooltip" data-bs-placement="top" title="acc Pinjaman"><i class="fa fa-stamp "></i><span class="small">&nbsp;ACC Pinjaman</span></button>
        <button id="btnDelete" disabled type="button" class="btn btn-secondary text-white btn-sm fw-bold btnDelete" onclick="hapusData()"><i class="fa fa-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Data"></i><span class="small">&nbsp;Delete</span></button>
        <button id="btnEdit" disabled type="button" class="btn btn-secondary text-white btn-sm fw-bold btnEdit" onclick="editData();" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data"><i class="fa fa-pencil fs-6"></i><span class="small">&nbsp;Edit</span></button>
        <a href="'. base_url('Pinjaman/pengajuan/create') . '"><button type="button" class="btn btn-primary text-white btn-sm fw-bold"><i class="fa fa-plus fs-5" data-bs-toggle="tooltip" data-bs-placement="top" title="Data Baru"></i><span class="small">&nbsp;Add</span></button></a>
    </div>
    ';
};

$html[] = '
<div class="card my-4">
        <div class="card-header">
            <div class="col-6">
                <h6 class="text-gray-800">Data Pengajuan Pinjaman Anggota</h6>
            </div>
        </div>
    <div class="card-body">

        <div class="row mb-4 align-items-center justify-content-end">
            <div class="col-12">
                ' . $btnDiv . '
            </div>
        </div>
        <div class="d-flex justify-content-start align-items-center gap-2">
        </div>
        <div class="my-4 overflow-x-auto">
            <table class="table table-sm table-striped table-hover table-bordered table-responsive">
                <thead class="text-muted bg-success">
                    <tr class="small text-white border-bottomku" style="font-size: 12px;">
                        <th class="text-center align-middle" width="5pt"><input class="form-check-input text-center" type="checkbox" name="check-all" id="check-all" onclick="checkedAll()"/></th>
                        <th class="text-center align-middle" width="100pt">NIK / Nama Anggota</th>
                        <th class="text-center align-middle" width="120pt">No Pengajuan / Tanggal Pengajuan</th>
                        <th class="text-center align-middle" width="40pt">Nominal</th>
                        <th class="text-center align-middle" width="20pt">Lama Angsuran (Bulan)</th>
                        <th class="text-center align-middle" width="20pt">Status</th>
                        <th class="text-center align-middle" width="20pt">Status Pencairan</th>
                    </tr>
                </thead>
                <tbody>';
if(count($pengajuanAnggota) > 0) {
    foreach ($pengajuanAnggota as $key => $value) {
        $html[] ='
                    <tr class="small align-middle" style="font-size: 12px;">
                        <td class="text-center"><input class="form-check-input text-center" type="checkbox" id="check" name="check" value="' . $value['id'] . '" onclick="setChecklist(this)"  /></td>
                        <td class="text-body-tertiary">'.$value["nik"].'<br>'.$value["nama"].' </td>
                        <td class="text-body-tertiary">'.$value["no_pengajuan"].'<br>'.tgl_long($value["tgl_pengajuan"]).' </td>
                        <td class="text-body-tertiary text-end">'.formatUang($value["nominal"]).' </td>
                        <td class="text-body-tertiary text-center">'.$value["lama_bulan"].' </td>
                        <td class="text-body-tertiary text-center" style="font-size: 13px;">'.convertStatus($value["status"]).' </td>
                        <td class="text-body-tertiary text-center">'.$value["status_pencairan"].' </td>
                    </tr>
        ';
    }
}else{
    $html[] = '<tr><td colspan="6" class="text-center small text-muted">Tidak ada data</td></tr>';
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
    <input type="hidden" id="pageStart" value=' . $pageStart . ' name="pageStart" />
    <input type="hidden" id="pagePerHalaman" value=' . $pagePerHalaman . ' name="pagePerHalaman" />
    </div>
</div>
<script>
    $("#tgl_transaksi_table").datepicker({
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
    }

    setDataForm = function()
    {
        setForm();
        getData();
    }

    tampilkan = function()
    {
        filter = 1;
        setDataForm();
    }

    PindahHalaman = function(pageStart)
    {
        $("#pageStart").val();
        getData(pageStart);
    }
</script>
';

$err = "";
$content = implode('', $html);

echo json_encode(array('content' => $content, 'error' => $err));

function convertStatus($jns) {
       switch ($jns) {
           case 'pending':
           return "<span class='badge bg-warning text-dark'>Pending</span>";
           case 'acc':
           return "<span class='badge bg-info text-dark'>Di Setujui</span>";
           default:
           return "<span class='badge bg-warning text-dark'>Di Tolak</span>";
       }
   }