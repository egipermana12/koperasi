<?php
    $disabled = 'disabled';
    $status_pencairan == "sudah" ? $disabled = 'disabled' : $disabled = '';
?>
<div class="container-fluid px-4">
    <div class="card my-4" style="width: 70%;">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            <?= $judul; ?>
        </div>
        <div class="card-body">
            <?php if($status_pencairan == "sudah") {
                echo '<div class="alert alert-danger small" role="alert">Data tidak bisa diubah, sudah pencairan!</div>';
            } ?>
            <form id="pengajuanForm" name="pengajuanForm" enctype="multipart/form-data">
                <div class="modal-body px-4" style="overflow-y: auto;">
                    <div class="">
                    <?= LabelInput('nik', 'Data Anggota'); ?>
                </div>
                <div class="d-flex justify-cotent-start gap-2 mb-3">
                    <div class="" style="width: 37% !important;">
                        <?= InputType('text', 'nik', 'nik', $nik, "class='form-control form-control-sm' placeholder='NIK sesuai KTP' disabled"); ?>
                    </div>
                    <div class="w-50">
                        <?= InputType('text', 'nama', 'nama', $nama, "class='form-control form-control-sm' placeholder='Nama sesuai KTP' disabled "); ?>
                    </div>
                    <div class="">
                        <a href="javascript:TombolCari();" class="btn btn-sm btn-success form-control form-control-sm"><i class="fas fa-search"></i> <span class="font-weight-bold">Cari</span></a>
                    </div>
                </div>
                <div class="mb-3">
                    <?= LabelInput('no_pengajuan', 'Nomor Pengajuan'); ?>
                    <?= InputType('text', 'no_pengajuan', 'no_pengajuan', $no_pengajuan, "class='form-control form-control-sm' placeholder='Nomor Simpanan' disabled style='width: 88% !important;' "); ?>
                </div>
                <div class="mb-3" style="width: 40%;">
                    <?= LabelInput('tgl_pengajuan', 'Tanggal Transaksi', '*'); ?>
                    <div class="input-group mb-3">
                      <?= InputType('text', 'tgl_pengajuan', 'tgl_pengajuan', $tgl_pengajuan, "class='form-control form-control-sm jqueryui-marker-datepicker' placeholder='Tanggal Transaksi' $disabled "); ?>
                    </div>
                </div>
                <div class="mb-3">
                    <?= LabelInput('waktuangsuran', 'Pilih Lama Angsuran'); ?>
                    <?= $refid_waktu_angsuran; ?>
                </div>
                <div class="mb-3" style="width: 50%;">
                    <?= LabelInput('nominal', 'Nominal Pinjaman'); ?>
                    <?= InputTypeUang('nominal', $nominal, "class='form-control form-control-sm' placeholder='Masukan Nominal' $disabled"); ?>
                </div>
                <div class="mb-3" style="width: 88%;">
                    <?= LabelInput('ket', 'Keterangan'); ?>
                    <textarea class="form-control form-control-sm" name="ket" id="ket" rows="3" <?=$disabled ?>><?= $ket; ?></textarea>
                </div>
                <div class="mt-4">
                    <?= InputType('hidden', 'id', 'id', $id, ""); ?>
                    <?= InputType('hidden', 'id_anggota', 'id_anggota', $id_anggota, ""); ?>
                    <a class="btn btn-sm btn-danger fw-semibold px-3" href="<?= base_url('Pinjaman/pengajuan') ?>"><i class='fas fa-fw fa fa-times'></i>&nbspBatal</a>
                    <button type="submit" class="btn btn-sm btn-success fw-semibold px-3 btnSimpan" id="btnSimpan"><i class='fas fa-fw fa-save'></i>&nbspSimpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- coba modal -->
<!-- Button trigger modal -->
<button type="button" id="btn-modal-cari" class="d-none btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Cari Anggota</h5>
        <button type="button" id="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="col-sm-12" id="PinjamanElem"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    if(!Form)
    {
        var filter = 1;
        var Form = new FormData();
        Form.append("Start", 0);
    }

    getData = function(pageStart = 0)
    {
        $("#pageStart").val(pageStart);
        if(filter == 1){
            setForm();
        }
        let element = $('#PinjamanElem');
        getAllData(base_url +'anggota/view'+ '/1', element, Form);
    };

    TombolCari = function() {
        $("#btn-modal-cari").click();
        loading();
        $.ajax({
            type: "POST",
            url: base_url + 'anggota/view' + '/1',
            data: Form,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function (data) {
                var res = JSON.parse(data);
                clearLoading();
                if (res.error === "") {
                    $('#PinjamanElem').html(res.content);
                } else {
                    console.log(res.error);
                }
            },
        });
    }

    getDataAfter = function(res) {
        $("#id_anggota").val(res.content.id);
        $("#nama").val(res.content.nama);
        $("#nik").val(res.content.nik);
        closeModal();
    }

    closeModal = function(){
        $('#closeModal').click();
        idPilih = "";
        DataPilih = 0;
    }

    $('#pengajuanForm').on('submit', function(e) {
        let url;
        let id = $('#id').val();
        if(id == "" || id == 0){
            url = base_url + 'Pinjaman/pengajuan/store';
        }else{
            url = base_url + 'Pinjaman/pengajuan/update';
        }
        e.preventDefault();
        let data = new FormData(this);
        data.append('no_pengajuan', $('#no_pengajuan').val());
        data.append('nama', $('#nama').val());
        data.append('nik', $('#nik').val());

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            dataType: "JSON",
            beforeSend: function() {
                $('#btnSimpan').attr('disabled', 'disabled');
                $('#btnSimpann').html('<div class="spinner-border spinner-border-sm text-light" role="status"><span class="visually-hidden">Loading...</span></div>&nbspsaving');
            },
            complete: function() {
                $('#btnSimpan').removeAttr('disabled');
                $('#btnSimpan').html('<i class="fas fa-fw fa-save"></i>&nbspSimpan');
            },
            success: function(res) {
                if (res.success === true) {
                    alert_confirm('Success', res.messages, 'success', 'Pinjaman/pengajuan');
                } else {
                    if (res.messages instanceof Object) {
                        $.each(res.messages, function(index, value) {
                            var key = $("#" + index);
                            key.closest('.form-control')
                            .removeClass('is-invalid')
                            .removeClass('is-valid')
                            .addClass(value.length > 0 ? 'is-invalid' : 'is-valid')
                            .siblings('.text-danger').remove();
                            if (key.hasClass('jqueryui-marker-datepicker')) {
                                key.next('.ui-datepicker-trigger').after(value);
                            }else if(index == "nominal"){
                                key.after("");
                            }
                            else {
                                key.after(value);
                            }
                            if (value.length > 0) {
                                // Set focus only if the value is invalid
                                key.focus();
                            }
                        });
                        // alert_error('Form Belum Lengkap');
                    } else {
                        alert_error(res.messages);
                    }
                }
            }
        });
    });
</script>