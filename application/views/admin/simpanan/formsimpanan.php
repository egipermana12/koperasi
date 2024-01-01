<div class="container-fluid px-4">
    <div class="card my-4" style="width: 60%;">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            <?= $judul; ?>
        </div>
        <div class="card-body">
            <form id="simpananForm" name="simpananForm" enctype="multipart/form-data">
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
                    <?= LabelInput('no_simpanan', 'Nomor Transaksi'); ?>
                    <?= InputType('text', 'no_simpanan', 'no_simpanan', $no_simpanan, "class='form-control form-control-sm' placeholder='Nomor Simpanan' disabled style='width: 88% !important;' "); ?>
                </div>
                <div class="mb-3" style="width: 50%;">
                    <?= LabelInput('tgl_transaksi', 'Tanggal Transaksi', '*'); ?>
                    <?= InputType('text', 'tgl_transaksi', 'tgl_transaksi', $tgl_transaksi, "class='form-control form-control-sm tgl_datepicker' placeholder='Tanggal Transaksi'"); ?>
                </div>
                <div class="mb-3">
                    <?= LabelInput('simpanan', 'Pilih Jenis Simpanan'); ?>
                    <?= $refid_jns_simpanan; ?>
                </div>
                <div class="mb-3" style="width: 50%;">
                    <?= LabelInput('nominal', 'Nominal Simpanan'); ?>
                    <?= InputTypeUang('nominal', $nominal, "class='form-control form-control-sm' placeholder='Masukan Nominal'"); ?>
                </div>
                <div class="mb-3" style="width: 88%;">
                    <?= LabelInput('ket', 'Keterangan'); ?>
                    <textarea class="form-control form-control-sm" name="ket" id="ket" rows="3"><?= $ket; ?></textarea>
                </div>
                <div class="mt-4">
                    <?= InputType('hidden', 'id', 'id', $id, ""); ?>
                    <?= InputType('hidden', 'id_anggota', 'id_anggota', $id_anggota, ""); ?>
                    <a class="btn btn-sm btn-danger fw-semibold px-3" href="<?= base_url('simpanan') ?>"><i class='fas fa-fw fa fa-times'></i>&nbspBatal</a>
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

    pilihJnsSimpanan = function(){
        let id = $('#jns_simpanan').val();
        let url = "Referensi/simpanan/pilihSimpanan";
        loading();
        $.ajax({
            type:"POST",
            data:{id: id},
            url: base_url + url,
            success: function(data) {
                var res = JSON.parse(data);
                clearLoading();
                if(res.error === ""){
                    $('#nominal_Uang').val(res.content.nominal);
                    $('#nominal').val(res.content.nominal);
                }else{
                    alert(res.error);
                }
            }
        });
    }

    closeModal = function(){
        $('#closeModal').click();
        idPilih = "";
        DataPilih = 0;
    }

    $('#simpananForm').on('submit', function(e) {
        let url;
        let id = $('#id').val();
        if(id == "" || id == 0){
            url = base_url + 'simpanan/store';
        }else{
            url = base_url + 'simpanan/update';
        }
        e.preventDefault();
        let data = new FormData(this);
        data.append('no_simpanan', $('#no_simpanan').val());
        data.append('nama', $('#nama').val());
        data.append('nik', $('#nik').val());

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
                    alert_confirm('Success', res.messages, 'success', 'simpanan');
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
                        // alert_error('Form Belum Lengkap');
                    } else {
                        alert_error(res.messages);
                    }
                }
            }
        });
    });
</script>