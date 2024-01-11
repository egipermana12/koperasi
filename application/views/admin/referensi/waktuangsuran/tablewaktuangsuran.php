<?php

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

$html[] ='
<div class="card my-4">
    <div class="card-body">
        <div class="row mb-4 align-items-center">
            <div class="col-6">
                <h5 class="text-gray-800">Referensi Waktu Angsuran</h5>
            </div>
            <div class="col-6">
            '.$btnDiv.'
            </div>
        </div>
        <div class="d-flex justify-content-start align-items-center gap-4">
            '.InputType("text", "jns_simpanan_t", "jns_simpanan", "", "class='form-control form-control-sm' placeholder='Cari Jenis Simpanan' ").'
            <button type="button" onclick="tampilkan();" class="btn btn-secondary btn-sm small d-flex justify-content-center align-items-center" style="width: 100pt;"><i class="fa fa-search"></i>&nbsp;Tampilkan</button>
        </div>
        <div class="my-4 overflow-x-auto">
            <table class="table table-sm table-striped table-hover table-bordered table-responsive">
                <thead class="font-medium text-muted bg-success">
                    <tr class="small text-white border-bottomku">
                        <th class="text-center align-middle" width="5pt"><input class="form-check-input text-center" type="checkbox" name="check-all" id="check-all" onclick="checkedAll()"/></th>
                        <th class="text-center align-middle" width="250pt">Jenis Simpanan</th>
                        <th class="text-center align-middle" width="100pt">Jumlah</th>
                        <th class="text-center align-middle" width="50pt">Tampil</th>
                    </tr>
                </thead>
                <tbody>
    </div>
</div>
';

$err = "";
$content = implode('', $html);

echo json_encode(array('content' => $content, 'error' => $err));