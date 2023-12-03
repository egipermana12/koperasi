<div class="container-fluid px-4">
  <div class="card my-4">
    <div class="card-header">
      <span class="fs-6 fw-semibold">Settings Aplikasi</span>
    </div>
    <div class="card-body">
      <div class="w-50 h-full">
        <?php
$labelMenu = "";
foreach ($settings as $key => $value) {
	if ($labelMenu != $value->label_menu) {
		echo "<div class='text-primary fw-semibold mt-3'>$value->label_menu </div>";
	}
	echo
	"
    <div class='row my-1 align-items-center'>
      <div class='col-md-5 small text-muted'>" . $value->label . "</div>
      <div class='col-md-7'>
        <div class='input-group'>
          <input type='text' class='form-control form-control-sm h-25 settings' data-id='" . $value->id . "' value='" . $value->value . "' disabled>
          <button class='btn btn-outline-secondary btn-sm edit-batal' type='button')><i class='as fa-fw fa fa-times'></i></button>
          <button class='btn btn-outline-secondary btn-sm edit-save' type='button')><i class='fas fa-fw fa-save'></i></button>
          <button class='btn btn-outline-secondary btn-sm edit-setting' type='button')><i class='fa fa-pencil'></i></button>
        </div>
      </div>
    </div>
  ";
	$labelMenu = $value->label_menu;
}
?>
      </div>
    </div>
  </div>
</div>

<script src="<?=base_url('js/settings.js');?> "></script>