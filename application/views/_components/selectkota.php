<div class="mb-3">
    <label for="Kecamatan" class="form-label small text-gray-800 fw-semibold">Pilih Kecamatan</label>
    <select class="form-select form-select-sm" aria-label="Small select example" id="kd_kec" name="kd_kec">
        <option value="" selected>Pilih Kecamatan</option>
        <?php foreach ($daftar_kec as $key => $val) : ?>
            <option value="<?= $key; ?>"><?= $val ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="mb-3">
    <label for="Kecamatan" class="form-label small text-gray-800 fw-semibold">Pilih Desa</label>
    <select class="form-select form-select-sm" aria-label="Small select example" name="kd_desa" id="kd_desa">
        <option value="">Pilih Desa</option>
    </select>
</div>

<script>
    $('#kd_kec').change(function(e) {
        e.preventDefault();
    });
</script>