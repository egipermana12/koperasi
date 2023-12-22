<div class="modal fade shadow" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 1000;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><?= $judul; ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form>
                <div class="modal-body" style="height: 500px; overflow-y: auto;">
                <form>
  <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-2 col-form-label col-form-label-sm">User Name</label>
    <div class="col-sm-10">
      <input type="email" class="form-control form-control-sm" id="inputEmail3">
    </div>
  </div>
      <div class="row mb-3">
    <label for="inputEmail3" class="col-sm-2 col-form-label col-form-label-sm">Nama Lengkap</label>
    <div class="col-sm-10">
      <input type="email" class="form-control form-control-sm" id="inputEmail3">
    </div>
  </div>
  <div class="row mb-3">
    <label for="inputPassword3" class="col-sm-2 col-form-label col-form-label-sm">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control form-control-sm" id="inputPassword3">
    </div>
  </div>
      <fieldset class="row mb-3">
    <legend class="col-form-label col-sm-2 pt-0 col-form-label-sm">Level</legend>
    <div class="col-sm-10">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="level" id="inlineCheckbox1" value="option1" checked>
        <label class="form-check-label small" for="inlineCheckbox1">Administrator</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="level" id="inlineCheckbox1" value="option1">
        <label class="form-check-label small" for="inlineCheckbox1">Operator</label>
      </div>
    </div>
  </fieldset>
      <fieldset class="row mb-3">
    <legend class="col-form-label col-sm-2 pt-0 col-form-label-sm">Status User</legend>
    <div class="col-sm-10">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="status" id="inlineCheckbox1" value="option1" checked>
        <label class="form-check-label small" for="inlineCheckbox1">Disabled/Blocked</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="status" id="inlineCheckbox1" value="option1">
        <label class="form-check-label small" for="inlineCheckbox1">Aktif</label>
      </div>
    </div>
  </fieldset>
      <p>Hak Akases</p>
  <fieldset class="row mb-3">
    <legend class="col-form-label col-sm-2 pt-0 col-form-label-sm"><span style="margin-right: 12px;">1.</span>Modul Anggota</legend>
    <div class="col-sm-8">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="modul_anggota" id="inlineCheckbox1" value="option1" checked>
        <label class="form-check-label small" for="inlineCheckbox1">Disable</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="modul_anggota" id="inlineCheckbox1" value="option1">
        <label class="form-check-label small" for="inlineCheckbox1">Write</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="modul_anggota" id="inlineCheckbox1" value="option1">
        <label class="form-check-label small" for="inlineCheckbox1">Read</label>
      </div>
    </div>
  </fieldset> 
</form>
                </div>
                <div class="modal-footer">
                </div>
            </form>
        </div>
    </div>
</div>