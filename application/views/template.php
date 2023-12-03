<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("_partials/head.php") ?>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <?php $this->load->view("_partials/navbar.php") ?>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">

            <?php $this->load->view("_partials/sidebar.php") ?>
        </div>
        <div id="layoutSidenav_content">
            <main class="" style="background-color: #F1F1F2;">
                <!-- untuk loading -->
                <div id="loadingContent"></div>
                <!-- untuk loading -->
                <div id="contents"><?= $contents ?></div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <?php $this->load->view("_partials/footer.php") ?>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>