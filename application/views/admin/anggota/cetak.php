<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("_partials/head.php") ?>
    <style>
        .rangkacetak {
            scrollbar-track-color: white;
            margin: 0;
            padding: 0;
        }
        .kop-wrapper {
            width: 100% !important;
        }
        h4,h5 {
            margin-bottom: .3rem !important;
        }
        hr.bd-atas{
            border: 2px solid black;
            opacity: 1 !important;
        }
        hr.bd-bawah{
            margin-top: -13px !important;
            border: hairline solid black;
            opacity: 1 !important;
        }
        table {
            border-color: black !important;
        }
        table tr.lap-td {
            background-color: #DBDBDB !important;
            font-size: 10pt;
            -webkit-print-color-adjust: exact;
        }
    </style>
</head>
<body>
    <div class="rangkacetak" style="width: 32cm;">
        <div class="kop-wrapper px-2">
            <div class="logo position-absolute float-left" style="top: 0; left: 160px;">
                <img src="<?= base_url("assets/img/") . $logo ?>" height="100px" width="100px" alt="">
            </div>
            <div class="text-center">
                <h5 class="text-uppercase" style="font-weight: bold; font-size: 14pt;">koperasi simpan pinjam</h5>
                <h5 class="text-uppercase" style="font-weight: bold; font-size: 16pt;">"<?= $this->ModelUtama->SETTING("NAMA_INSTANSI") ?>"</h5>
                <h5 class="text-uppercase" style="font-weight: semibold; font-size: 11pt;">Badan Hukum No : <?= $this->ModelUtama->SETTING("NO_BADAN_HUKUM") ?></h5>
                <h5 class="text-capitalize" style="font-weight: normal; font-size: 9pt;">Kantor : <?= $this->ModelUtama->SETTING("ALAMAT") ?></h5>
            </div>
        </div>
        <hr class="bd-atas">
        <hr class="bd-bawah">
        <div class="my-3"></div>
        <div class="text-center w-100">
            <h5 class="text-uppercase mb-3 fw-bold">Daftar Anggota Koperasi</h5>
        </div>
        <table class="table table-sm table-bordered">
            <thead>
                <tr class="lap-td">
                    <th scope="col" class="text-center" width="40">No</th>
                    <th scope="col" class="text-center" width="180">NIK Anggota</th>
                    <th scope="col" class="text-center" width="200">Nama Anggota</th>
                    <th scope="col" class="text-center" width="280">Alamat</th>
                    <th scope="col" class="text-center" width="120">Tanggal Gabung</th>
                    <th scope="col" class="text-center" width="80">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 0; foreach($dataAnggota as $data) :  $no++; ?>
                <tr style="font-size: 10pt;">
                    <td scope="row" class="text-center"><?= $no ?></td>
                    <td><?= $data->nik; ?></td>
                    <td><?= $data->nama; ?></td>
                    <td class="text-capitalize"><?= $data->alamat; ?> <br> Desa: <?= $data->desa ?> <br> Kecamatan : <?= $data->kecamatan ?> <br> Kab: KUNINGAN </td>
                    <td><?= tgl_long($data->tgl_gabung); ?></td>
                    <td class="text-center"><?= convertStatusHelper($data->status); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="py-3">
        <table>
            <tr>
                <td width="100" class="text-center" colspan>&nbsp;</td>
                <td width="300"></td>
                <td width="400" colpan>&nbsp;</td>
                <td width="300" class="text-center">
                    <b>
                        <input type="text" value=" <?= $this->ModelUtama->SETTING("LOKASI_TTD") .", " . tgl_long(date("Y-m-d")) ?> " style="background:none;border:none;text-align:center;font-weight:bold;font-size: 10pt;" size="50">
                    </b>
                    <b>
                        <input type="text" value="KETUA ORGANISASI" style="background:none;border:none;text-align:center;font-weight:bold;font-size: 10pt;" size="50">
                    </b>
                    <br>
                    <br>
                    <br>
                    <br>
                    <b>
                        <input type="text" class="text-decoration-underline" value="( <?= $this->ModelUtama->SETTING("NAMA_KETUA") ?> )" style="background:none;border:none;text-align:center;font-weight:bold;font-size: 10pt;" size="50">
                    </b>
                    <b>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>