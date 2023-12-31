<div class="modal fade shadow" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content overflow-x-auto">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel"><?= $judul; ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="refSimpananForm" name="refSimpananForm">
        <div class="modal-body px-4" style="overflow-y: auto;">
          <div class="mb-3">
            <?= LabelInput('jns_simpanan', 'Jenis Simpanan', '*'); ?>
            <?= InputType('text', 'jns_simpanan', 'jns_simpanan', $jns_simpanan, "class='form-control form-control-sm' placeholder='Masukan Jenis Simpanan'"); ?>
          </div>
          <div class="mb-3">
            <?= LabelInput('nominal', 'Nominal Simpanan'); ?>
            <?= InputTypeUang('nominal', $nominal, "class='form-control form-control-sm' placeholder='Masukan Nominal'"); ?>
          </div>
          <div class="mb-3" style="width: 35%;">
            <?= LabelInput('tampil', 'Satus Tampil'); ?>
            <div class="d-flex justify-content-between align-items-center">
              <label class="bg-primary bg-opacity-10 px-4 py-3 rounded border border-2  border-dashed border-primary form-check-label small d-flex gap-2" for="tampil_Y">
                <?= InputType('radio', 'tampil_Y', 'tampil', 'Y', "class='form-check-input'" . ($tampil == 'Y' ? 'checked' : '')); ?>
                  Tampilkan
              </label>
              <label class="bg-primary bg-opacity-10 px-4 py-3 rounded border border-2 border-dashed border-primary form-check-label small d-flex gap-2" for="tampil_N">
                <?= InputType('radio', 'tampil_N', 'tampil', 'N', "class='form-check-input'" . ($tampil == 'N' ? 'checked' : '')); ?>
                  Tidak
              </label>
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

<script>
  $('#refSimpananForm').on('submit', function(e){
    let url;
    if($('#id').val() === ""){
      url = base_url + 'Referensi/simpanan/store';
    }else{
      url = base_url + 'Referensi/simpanan/update';
    }
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