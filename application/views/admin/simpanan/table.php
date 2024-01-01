<?php

$html = [];
$btnDiv = "";

if ($viewOnly) {

} else {
    $btnDiv .= '
    <div class="d-flex justify-content-end align-items-center gap-1">
        <button id="btnPrint" disabled type="button" class="btn btn-secondary text-white btn-sm fw-bold btnPrint" onclick="printData()" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Kwitansi"><i class="fa fa-print "></i></button>
        <button id="btnDelete" disabled type="button" class="btn btn-secondary text-white btn-sm fw-bold btnDelete" onclick="hapusData()"><i class="fa fa-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Data"></i></button>
        <button id="btnEdit" disabled type="button" class="btn btn-secondary text-white btn-sm fw-bold btnEdit" onclick="editData();" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data"><i class="fa fa-pencil fs-6"></i></button>
        <a href="'. base_url('simpanan/create') . '"><button type="button" class="btn btn-primary text-white btn-sm fw-bold"><i class="fa fa-plus fs-5" data-bs-toggle="tooltip" data-bs-placement="top" title="Data Baru"></i></button></a>
    </div>
    ';
};

$html[] = '
<div class="card my-4">
    <div class="card-body">
        <div class="row mb-4 align-items-center">
            <div class="col-6">
                <h5 class="text-gray-800">Data Simpanan Anggota</h5>
            </div>
            <div class="col-6">
                ' . $btnDiv . '
            </div>
        </div>
        <div class="d-flex justify-content-start align-items-center gap-2">
            <div style="width: 200pt">
            '.InputType("text", "nikname", "nikname", $qNikname, "class='form-control form-control-sm' placeholder='Cari NIK / Nama Anggota' ").'
            </div>
            <div style="width: 200pt">
            '.InputType("text", "no_simpanan_table", "no_simpanan", $qNoSimpanan, "class='form-control form-control-sm' placeholder='Cari Nomor Transaksi' ").'
            </div>
            <div style="width: 100pt">
            '.InputType("text", "tgl_transaksi_table", "tgl_transaksi", $qTglTransaksi, "class='form-control form-control-sm' placeholder='Tgl Transaksi' ").'
            </div>
            <div style="width: 180pt">
                '.$cmbJnsSimpanan.'
            </div>
            <div style="width: 80pt;">
            <button type="button" onclick="tampilkan();" class="btn btn-secondary btn-sm small d-flex justify-content-center align-items-center"><i class="fa fa-search"></i>&nbsp;Tampilkan</button>
            </div>
        </div>
        <div class="my-4 overflow-x-auto">
            <table class="table table-sm table-striped table-hover table-bordered table-responsive">
                <thead class="font-medium text-muted bg-success">
                    <tr class="small text-white border-bottomku">
                        <th class="text-center align-middle" width="5pt"><input class="form-check-input text-center" type="checkbox" name="check-all" id="check-all" onclick="checkedAll()"/></th>
                        <th class="text-center align-middle" width="100pt">NIK / Nama Anggota</th>
                        <th class="text-center align-middle" width="70pt">No Transaksi / Tanggal Transaksi</th>
                        <th class="text-center align-middle" width="70pt">Jenis Simpanan</th>
                        <th class="text-center align-middle" width="70pt">Nominal</th>
                        <th class="text-center align-middle" width="90pt">Ket</th>
                    </tr>
                </thead>
                <tbody>';
if(count($dataSimpanan) > 0) {
    foreach ($dataSimpanan as $key => $value) {
        $html[] ='
                    <tr class="small">
                        <td class="text-center"><input class="form-check-input text-center" type="checkbox" id="check" name="check" value="' . $value['id'] . '" onclick="setChecklist(this)"  /></td>
                        <td class="text-body-tertiary">'.$value["nik"].'<br>'.$value["nama"].' </td>
                        <td class="text-body-tertiary">'.$value["no_simpanan"].'<br>'.tgl_long($value["tgl_transaksi"]).' </td>
                        <td class="text-body-tertiary">'.$value["jns_simpanan"].' </td>
                        <td class="text-body-tertiary text-end">'.formatUang($value["nominal"]).' </td>
                        <td class="text-body-tertiary">'.$value["ket"].' </td>
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
        Form.append("nikname", $("#nikname").val() );
        Form.append("no_simpanan_table", $("#no_simpanan_table").val() );
        Form.append("tgl_transaksi_table", $("#tgl_transaksi_table").val() );
        Form.append("jns_simpanan_table", $("#jns_simpanan_table").val() );
    }

    setData = function()
    {
        setForm();
        getData();
    }

    tampilkan = function()
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
';

$err = "";
$content = implode('', $html);

echo json_encode(array('content' => $content, 'error' => $err));