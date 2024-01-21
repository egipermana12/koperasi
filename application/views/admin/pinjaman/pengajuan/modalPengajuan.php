<div class="modal fade shadow" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content overflow-x-auto">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel"><?= $judul; ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formAccPengajuan" name="formAccPengajuan">
        <div class="modal-body px-4" style="overflow-y: auto;">
          <div class="mb-3">
            <?= LabelInput('no_pengajuan', 'Nomor Pengajuan'); ?>
            <?= InputType('text', 'no_pengajuan', 'no_pengajuan', $no_pengajuan, "class='form-control form-control-sm' onkeypress='return isNumberKey(event)' disabled placeholder='Nomor Pengajuan'"); ?>
          </div>
          <div class="mb-3" style="width: 55%;">
            <?= LabelInput('status', 'Status Pengajuan'); ?>
            <div class="d-flex justify-content-between align-items-center">
              <label class="bg-warning bg-opacity-10 px-4 py-3 rounded border border-2  border-warning form-check-label small d-flex gap-2" for="pending">
                <?= InputType('radio', 'pending', 'status', 'pending', "class='form-check-input' " . ($status == 'pending' ? 'checked' : '')); ?>
                Pending
              </label>
              <label class="bg-primary bg-opacity-10 px-4 py-3 rounded border border-2 border-primary form-check-label small d-flex gap-2" for="acc">
                <?= InputType('radio', 'acc', 'status', 'acc', "class='form-check-input'" . ($status == 'acc' ? 'checked' : '')); ?>
                Setujui
              </label>
              <label class="bg-danger bg-opacity-10 px-4 py-3 rounded border border-2 border-danger form-check-label small d-flex gap-2" for="tolak">
                <?= InputType('radio', 'tolak', 'status', 'tolak', "class='form-check-input'" . ($status == 'tolak' ? 'checked' : '')); ?>
                Di Tolak
              </label>
            </div>
          </div>
          <div class="mt-3 bg-secondary bg-opacity-10 border border-secondary rounded px-2 py-2"  id="show">
            <div class="mb-3" style="width: 55%;">
              <?= LabelInput('status_pencairan', 'Status Pencairan'); ?>
              <div class="d-flex justify-content-between align-items-center">
                <label class="bg-primary bg-opacity-10 px-4 py-3 rounded border border-2 border-primary form-check-label small d-flex gap-2" for="sudah">
                  <?= InputType('radio', 'sudah', 'status_pencairan', 'sudah', "class='form-check-input'" . ($status_pencairan == 'sudah' ? 'checked' : '')); ?>
                  Sudah Pencairan
                </label>
                <label class="bg-danger bg-opacity-10 px-4 py-3 rounded border border-2 border-danger form-check-label small d-flex gap-2" for="belum">
                  <?= InputType('radio', 'belum', 'status_pencairan', 'belum', "class='form-check-input'" . ($status_pencairan == 'belum' ? 'checked' : '')); ?>
                  Belum Pencairan
                </label>
              </div>
            </div>
            <div class="mb-3" style="width: 40%;">
              <?= LabelInput('tgl_pengajuan', 'Tanggal Transaksi', '*'); ?>
              <div class="input-group mb-3">
                <?= InputType('text', 'tgl_pengajuan', 'tgl_pengajuan', $tgl_pencairan, "class='form-control form-control-sm jqueryui-marker-datepicker' placeholder='Tanggal Transaksi' "); ?>
              </div>
            </div>
            <div class="mb-3" style="width: 40%;">
              <?= LabelInput('penerima_uang', 'Nama Penerima'); ?>
              <?= InputType('text', 'penerima_uang', 'penerima_uang', $penerima_uang, "class='form-control form-control-sm' placeholder='Masukan Nama Penerima Uang'"); ?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <?= InputType('hidden', 'id', 'id', $id, ""); ?>
          <button type="button" class="btn btn-sm btn-secondary" id="batal" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-sm btn-success fw-semibold px-3" id="btnSimpan"><i class='fas fa-fw fa-save'></i>&nbspSimpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="<?=base_url('assets/js/scripts.js') . '?v=' . time();?> "></script>
<script>

  $('#show').hide();

  $('input[type=radio][name=status]').change(function() {
    var selectedValue = this.value;
    if(selectedValue == "acc"){
      $('#show').show();
    }else{
      $('#show').hide();
    }
  });

  $('#formAccPengajuan').on('submit', function(e){
    let url = base_url + "Pinjaman/pengajuan/setujuiAction";
    e.preventDefault();
    let data = new FormData(this);
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
      success: function(res){
        if (res.success === true) {
          alert_success(res.messages);
          removeModal();
          getData();
        } else {
          if (res.messages instanceof Object) {
            $.each(res.messages, function(index, value) {
              var key = $("#" + index);
              key.closest('.form-control')
              .removeClass('is-invalid')
              .removeClass('is-valid')
              .addClass(value.length > 0 ? 'is-invalid' : 'is-valid')
              .siblings('.text-danger').remove();

              key.after(value);

              if (value.length > 0) {
                // Set focus only if the value is invalid
                key.focus();
              }
            });
          }else{
            alert_error(res.messages);
          }
        }
      }
    });

  });

  // remove modal
  $('#staticBackdrop').on('hidden.bs.modal', function () {
    $('.modal').remove();
    idPilih = "";
    DataPilih = 0;
  });

  function removeModal(){
    $('#batal').click();
    idPilih = "";
    DataPilih = 0;
  }

</script>