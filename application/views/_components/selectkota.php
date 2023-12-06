<div class="mb-3">
    <?= LabelInput('kecamatan', 'Pilih kecamatan'); ?>
    <?= cmbQuery("kode_kecamatan", "0", $daftar_kec, "kode_kecamatan", "nama", "class='form-select form-select-sm'", "Pilih Kecamatan", "0","1", 1, 0); ?>
</div>