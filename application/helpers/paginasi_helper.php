<?php

function paginasi($pageStart, $pagePerHalaman, $totalData)
{

    /** 
     * untuk handle counting data
    */
    $jmlHalaman = $totalData / $pagePerHalaman;
    $totalHalaman = intval($jmlHalaman);
    if($totalHalaman < $jmlHalaman)
    {
        $totalHalaman++;
    }

    $dataKe = $pageStart + 1;
    $dataSampai = ($dataKe - 1) + $pagePerHalaman;
    if($dataSampai > $totalData) 
    {
        $dataSampai = $totalData;
    }

    $shwoingAwal = '
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            <span class="small text-secondary">Data ke '.$dataKe.' - '.$dataSampai.' dari '.$totalData.' Total Data</span>
        </div>
        <nav class="" aria-label="...">
            <ul class="pagination pagination-sm">
    ';

    /**
     * untuk handle next, back, paginasi
     */

    //start
    $halamanAwal = '
        <li class="page-item disabled">
            <span class="page-link"><i class="fa fa-angle-double-left"></i></span>
        </li>
    ';

    //previeous
    $halamanSebelum = '
        <li class="page-item disabled">
            <span class="page-link"><i class="fa fa-angle-left"></i></span>
        </li>
    ';

    //next
    $halamanNext = '
        <li class="page-item disabled">
            <a class="page-link" href="#"><i class="fa fa-angle-right"></i></a>
        </li>
    ';

    //last
    $halamanAkhir = '
        <li class="page-item disabled">
            <a class="page-link" href="#"><i class="fa fa-angle-double-right"></i></a>
         </li>
    ';

    $daftarHalaman = "";
    $halamanAktif = 1;
    
    for($i = 1; $i <= $totalHalaman; $i++)
    {
        $halamanKe = ($i - 1) * $pagePerHalaman;
        if($halamanKe == $pageStart)
        {
            $halamanAktif = $i;
        }
    }

    for($i = 1; $i <= $totalHalaman; $i++)
    {
        $halAwal = 0 * $pagePerHalaman;
        $halamanKe = ($i - 1) * $pagePerHalaman;
        $halamanKeN = $i * $pagePerHalaman;
        $halAkhir = ceil ($totalHalaman * $pagePerHalaman) - $pagePerHalaman;
        if($halamanKe == $pageStart)
        {
            $daftarHalaman .= "<li class='page-item active'><a class='page-link'>" . $i . "</a></li>";
            if($i != 1)
            {
                $halamanAwal = "<li class='page-item'><a class='page-link' href='javascript:PindahHalaman(" . ($halAwal) . ");' ><i class='fa fa-angle-double-left'></i></a></li>";
                $halamanSebelum = "<li class='page-item'><a class='page-link' href='javascript:PindahHalaman(" . ($halamanKe - $pagePerHalaman) . ");' ><i class='fa fa-angle-left'></i></a></li>";
            }
            if($i != $totalHalaman)
            {
                $halamanNext = "<li class='page-item'><a class='page-link' href='javascript:PindahHalaman(" . ($halamanKeN) . ");' ><i class='fa fa-angle-right'></i></a></li>";
                $halamanAkhir = "<li class='page-item'><a class='page-link' href='javascript:PindahHalaman(" . ($halAkhir) . ");' ><i class='fa fa-angle-double-right'></i></a></li>";
            }
        }else{
            $tampil = true;
            $halBefore = 2;
            $halAfter = 2;
            if($halamanAktif == 1) {$halAfter += 1;}
            if($halamanAktif == 0) {$halAfter += 1;}

            if($halamanAktif + 1 > $totalHalaman) {
                $halBefore += 1 - ($totalHalaman - $halamanAktif);
            }

            // untuk mengatur link halaman yang ditampilkan
            if($halamanKe < $pageStart && $halamanAktif - $halBefore > $i) {$tampil = false;}
            if($halamanKe > $pageStart && $halamanAktif + $halAfter < $i) {$tampil = false;}

            if($tampil)
            {
                $daftarHalaman .= "<li class='page-item'><a class='page-link' href='javascript:PindahHalaman(" . $halamanKe . ");''>" . $i . "</a></li>";
            }
        }
    }

    $shwoingAkhir = '
            </ul>
        </nav>
    </div>
    ';

    return $shwoingAwal. $halamanAwal . $halamanSebelum . $daftarHalaman . $halamanNext .$halamanAkhir .$shwoingAkhir;
}
