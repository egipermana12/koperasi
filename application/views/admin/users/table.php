<?php
$html = [];
$btnDiv = "";
if ($viewOnly) {

} else {
    $btnDiv .= '
    <div class="d-flex justify-content-end align-items-center">
        <a href="javascript:showModal()"><button type="button" class="btn btn-primary btn-sm fw-semibold small"><i class="fa fa-plus-circle"></i>  Add Users</button></a>
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
            '.InputType("text", "username", "username", "", "class='form-control form-control-sm' placeholder='Cari UserID' ").'
            '.InputType("text", "nama", "nama", "", "class='form-control form-control-sm' placeholder='Cari Nama User' ").'
            <select class="form-control form-select-sm" id="level" name="level">
                <option value="">Level User</option>
                <option value="1">Admin</option>
                <option value="2">Operator</option>
            </select>
            <select class="form-control form-select-sm" id="status" name="status">
                <option value="">Status User</option>
                <option value="1">Enable</option>
                <option value="2">Disable</option>
            </select>
            <button type="button" onclick="refresh();" class="btn btn-secondary btn-sm small d-flex justify-content-center align-items-center"><i class="fa fa-search"></i>&nbsp;Cari</button>
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
                <tbody>
                    <tr class="small">
                        <td></td>
                        <td class="text-body-tertiary">abdul</td>
                        <td class="text-body-tertiary">Admin</td>
                        <td class="text-body-tertiary text-center">Enable</td>
                        <td class="text-body-tertiary text-center">Write</td>
                        <td class="text-body-tertiary text-center">Write</td>
                        <td class="text-body-tertiary text-center">Write</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
'
;

$err = "";

$content = implode('', $html);

echo json_encode(array('content' => $content, 'error' => $err));