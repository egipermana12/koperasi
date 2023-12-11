<div class="container-fluid px-4">
    <div class="card my-4" style="width: 60%;">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tambah Anggota Koperasi
        </div>
        <div class="card-body">
            <form id="anggotaForm" name="anggotaForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <?= LabelInput('nik', 'Nomor Induk Kependudukan', '*'); ?>
                    <?= InputType('text', 'nik', 'nik', $nik, "class='form-control form-control-sm' placeholder='NIK sesuai KTP'"); ?>
                </div>
                <div class="mb-3">
                    <?= LabelInput('nama', 'Nama Anggota', '*'); ?>
                    <?= InputType('text', 'nama', 'nama', $nama, "class='form-control form-control-sm' placeholder='Nama sesuai KTP'"); ?>
                </div>
                <div class="mb-3" style="width: 40%;">
                    <?= LabelInput('tgl_lahir', 'Tanggal Lahir Anggota', '*'); ?>
                    <?= InputType('text', 'tgl_lahir', 'tgl_lahir', $tgl_lahir, "class='form-control form-control-sm tgl_datepicker' placeholder='Tanggal Lahir sesuai KTP'"); ?>
                </div>
                <div class="mb-3" style="width: 35%;">
                    <?= LabelInput('jns_kelamin', 'Jenis Kelamin', '*'); ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?= InputType('radio', 'jns_kelamin_L', 'jns_kelamin', 'L', "class='form-check-input'" . ($jns_kelamin == 'L' ? 'checked' : '')); ?>
                            <label class="form-check-label small" for="jns_kelamin_L">
                                Laki - Laki
                            </label>
                        </div>
                        <div>
                            <?= InputType('radio', 'jns_kelamin_P', 'jns_kelamin', 'P', "class='form-check-input'" . ($jns_kelamin == 'P' ? 'checked' : '')); ?>
                            <label class="form-check-label small" for="jns_kelamin_P">
                                Perempuan
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <?= LabelInput('alamat', 'Alamat Anggota'); ?>
                    <textarea class="form-control form-contro-sm" name="alamat" id="alamat" rows="3"><?= $alamat ?></textarea>
                </div>
                <div class="mb-3">
                    <?= LabelInput('kecamatan', 'Pilih Kecamatan'); ?>
                    <?= $cmbKecamatan; ?>
                </div>
                <div class="mb-3">
                    <?= LabelInput('Kelurahan', 'Pilih Kelurahan'); ?>
                    <span id="tukCmbKel">
                        <?= $cmbKelurahan; ?>
                    </span>
                </div>
                <div class="mb-3">
                    <?= LabelInput('pekerjaan', 'Pekerjaan Anggota'); ?>
                    <?= InputType('text', 'pekerjaan', 'pekerjaan', $pekerjaan, "class='form-control form-control-sm' placeholder='Pekerjaan sesuai KTP'"); ?>
                </div>
                <div class="mb-3" style="width: 40%;">
                    <?= LabelInput('tgl_gabung', 'Tanggal Bergabung', '*'); ?>
                    <?= InputType('text', 'tgl_gabung', 'tgl_gabung', $tgl_gabung, "class='form-control form-control-sm tgl_datepicker' placeholder='Tanggal Menjadi Anggota'"); ?>
                </div>
                <div class="mb-3" style="width: 25%;">
                    <?= LabelInput('status', 'Status Keanggotaan', '*'); ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?= InputType('radio', 'status_A', 'status', 'A', "class='form-check-input'" . ($status == 'A' ? 'checked' : '')); ?>
                            <label class="form-check-label small" for="status_A">
                                Aktif
                            </label>
                        </div>
                        <div>
                            <?= InputType('radio', 'status_N', 'status', 'N', "class='form-check-input'" . ($status == 'N' ? 'checked' : '')); ?>
                            <label class="form-check-label small" for="status_N">
                                Non Aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <?= LabelInput('file_KTP', 'File KTP Anggota'); ?>
                    <?= imagePrev(); ?>
                </div>
                <div class="mt-4">
                    <?= InputType('hidden', 'id', 'id', $id, ""); ?>
                    <a class="btn btn-sm btn-danger fw-semibold px-3" href="<?= base_url('anggota') ?>"><i class='fas fa-fw fa fa-times'></i>&nbspBatal</a>
                    <button type="submit" class="btn btn-sm btn-success fw-semibold px-3 btnSimpan" id="btnSimpan"><i class='fas fa-fw fa-save'></i>&nbspSimpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    // for image prev
    $("#file-input").change(function() {
        readUrl(this);
    });

    $('#anggotaForm').on('submit', function(e) {
        let url;
        if($('#id').val() === ""){
            url = base_url + 'anggota/store';
        }else{
            url = base_url + 'anggota/update';
        }
        e.preventDefault();
        let data = new FormData(this);
        // console_form(data);
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
                    alert_confirm('Success', 'Data Berhasil Disimpan', 'success', 'anggota');
                } else {
                    if (res.messages instanceof Object) {
                        $.each(res.messages, function(index, value) {
                            var key = $("#" + index);
                            key.closest('.form-control')
                            .removeClass('is-invalid')
                            .removeClass('is-valid')
                            .addClass(value.length > 0 ? 'is-invalid' : 'is-valid')
                            .siblings('.text-danger').remove();
                            if (key.hasClass('tgl_datepicker')) {
                                key.next('.ui-datepicker-trigger').after(value);
                            } else {
                                key.after(value);
                            }

                            if (value.length > 0) {
                                // Set focus only if the value is invalid
                                key.focus();
                            }
                        });
                        alert_error('Form Belum Lengkap');
                    } else {
                        alert_error(res.messages);
                    }
                }
            }
        });
    });
</script>