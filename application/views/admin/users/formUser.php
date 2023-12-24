
<div class="modal fade shadow" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content overflow-x-auto">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel"><?= $judul; ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="userForm" name="userForm">
        <div class="modal-body px-5" style="overflow-y: auto;">
          <div class="row mb-2">
            <label for="username" class="col-sm-4 col-form-label col-form-label-sm">User Name</label>
            <div class="col-sm-8">
              <?= InputType('text', 'uid', 'uid', $username, "class='form-control form-control-sm' placeholder='Username'"); ?>
            </div>
          </div>
          <div class="row mb-2">
            <label for="nama" class="col-sm-4 col-form-label col-form-label-sm">Nama Lengkap</label>
            <div class="col-sm-8">
              <?= InputType('text', 'nama', 'nama', $nama, "class='form-control form-control-sm' placeholder='Nama Lengkap'"); ?>
            </div>
          </div>
          <div class="row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label col-form-label-sm">Password</label>
            <div class="col-sm-8">
              <?= InputType('password', 'password', 'password', $password, "class='form-control form-control-sm' placeholder='***********'"); ?>
            </div>
          </div>
          <fieldset class="row mb-2">
            <legend class="col-form-label col-sm-4 pt-0 col-form-label-sm">Level</legend>
            <div class="col-sm-8">
              <div class="form-check form-check-inline">
                <?= InputType('radio', 'level_1', 'level', '1', "class='form-check-input'" . ($level == '1' ? 'checked' : '')); ?>
                <label class="form-check-label small" for="level_1">Administrator</label>
              </div>
              <div class="form-check form-check-inline">
                <?= InputType('radio', 'level_2', 'level', '2', "class='form-check-input'" . ($level == '2' ? 'checked' : '')); ?>
                <label class="form-check-label small" for="level_2">Operator</label>
              </div>
            </div>
          </fieldset>
          <fieldset class="row mb-2">
            <legend class="col-form-label col-sm-4 pt-0 col-form-label-sm">Status User</legend>
            <div class="col-sm-8">
              <div class="form-check form-check-inline">
                <?= InputType('radio', 'status_2', 'status', '2', "class='form-check-input'" . ($status == '2' ? 'checked' : '')); ?>
                <label class="form-check-label small" for="status_2">Disabled/Blocked</label>
              </div>
              <div class="form-check form-check-inline">
                <?= InputType('radio', 'status_1', 'status', '1', "class='form-check-input'" . ($status == '1' ? 'checked' : '')); ?>
                <label class="form-check-label small" for="status_1">Enable</label>
              </div>
            </div>
          </fieldset>
          <p class="fw-bold mt-3">Hak Akases</p>
          <fieldset class="row mb-2">
            <legend class="col-form-label col-sm-4 pt-0 col-form-label-sm"><span style="margin-right: 12px;">1.</span>Modul Anggota</legend>
            <div class="col-sm-8">
              <div class="form-check form-check-inline">
                <?= InputType('radio', 'disable_01', 'modul_anggota', '0', "class='form-check-input'" . ($modul_anggota == '0' ? 'checked' : '')); ?>
                <label class="form-check-label small" for="DisableModul01">Disable</label>
              </div>
              <div class="form-check form-check-inline">
                <?= InputType('radio', 'write_01', 'modul_anggota', '1', "class='form-check-input'" . ($modul_anggota == '1' ? 'checked' : '')); ?>
                <label class="form-check-label small" for="write_01">Write</label>
              </div>
              <div class="form-check form-check-inline">
                <?= InputType('radio', 'read_01', 'modul_anggota', '2', "class='form-check-input'" . ($modul_anggota == '2' ? 'checked' : '')); ?>
                <label class="form-check-label small" for="read_01">Read</label>
              </div>
            </div>
          </fieldset>
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


  $('#userForm').on('submit', function(e){
    let url;
    if($('#id').val() === ""){
      url = base_url + 'users/store';
    }else{
      url = base_url + 'users/update';
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
        console.log(res);
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