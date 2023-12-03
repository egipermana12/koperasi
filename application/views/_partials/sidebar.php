<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <?php foreach ($menu as $val) {
                $kategori = $val['kategori'];
                if ($kategori['show_title'] == 'Y') {
                    echo '<div class="sb-sidenav-menu-heading">' . $kategori['nama_kategori'] . '</div>';
                }
                $menu_list = menu_list($val['menu']);
                echo build_menu($menu_list);
            } ?>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        Start Bootstrap
    </div>
</nav>