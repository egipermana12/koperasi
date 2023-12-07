<div class="container-fluid px-4">
  <div class="card my-4">
    <div class="card-header">
      <span class="fs-6 fw-semibold">Settings Aplikasi</span>
    </div>
    <div class="card-body">

     <!-- alert -->
     <div class="alert alert-light d-flex align-items-center" role="alert">
      <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
      <div class="px-3 small">
        Untuk mengganti gambar silahkan upload terlebih dahulu pada bagian Pengaturan Gambar, <br>
        setelah gambar berhasil diupload inputkan nama gambar pada pangaturan yang berkaitan dengan image, <br>
        Gambar yang diupload sebaikanya ber extensi png.
      </div>
    </div>
    <!-- alert -->

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
  <div class="mt-5 w-full">
    <hr>
    <div class="d-flex justify-content-between w-50 align-items-center mb-3">
      <span class="fs-6 fw-semibold text-primary">Pengaturan Gambar</span>
      <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Upload Image</button>
    </div>
    <div class="row">
      <div class="col-12 col-md-7">
        <div class="card" style="height: 320px;">
          <div class="card-body">
            <div class="row text-center text-lg-start">
              <?php foreach ($images as $key => $val) {
               $dt = json_encode($val->id);
               echo "
               <div class='col-lg-3 col-md-4 col-6'>
               <a href='javascript:DetailImages(" . $dt . ")' class='d-block mb-4 h-100'>
               <img class='img-fluid img-thumbnail' src='" . base_url('assets/img/') . $val->nama_image . "' alt=''>
               </a>
               </div>
               ";
             }?>
           </div>
         </div>
       </div>
     </div>
     <div class="col-12 col-md-5">
      <div class="card" style="height: 320px;">
        <div class="card-body">

        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>




<script src="<?=base_url('assets/js/settings.js');?> "></script>