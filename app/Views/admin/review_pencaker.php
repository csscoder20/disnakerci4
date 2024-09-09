<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url(); ?>frontend/assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url(); ?>frontend/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url(); ?>frontend/assets/img/favicon/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @page {
            size: A4;
        }
    </style>
    <style type="text/css">
        * {
            font-size: 12px;
        }

        td,
        tr,
        th {
            padding: 0 !important;
            margin: 0 !important;
        }

        h4 {
            padding: 0 !important;
            font-size: 16px;
            margin: 0 !important;
            font-weight: bold;
        }

        .div1 {
            width: 100%;
            height: 60px;
            border: 1px solid gray;
        }

        ul.bahasa li {
            display: inline;
            padding-right: 20px;
        }

        .table-bordered>:not(caption)>* {
            border-width: 1px 0;
            vertical-align: middle;
            text-align: center;
        }

        ul.bahasa {
            padding-left: 0;
        }

        img.float-end {
            object-fit: cover;
        }

        img.pasfoto {
            height: auto !important;
        }
    </style>
</head>

<body class="A4">

    <section class="sheet padding-10mm">

        <div class="row">
            <div class="col-2 border-bottom">
                <img width="70" height="70" src="<?php echo base_url('frontend/assets/img/favicon/android-chrome-512x512.png'); ?>">
            </div>
            <div class="col-10 border-bottom">
                <h2 class="text-medium">PEMERINTAH KABUPATEN MANOKWARI<br>DINAS TENAGA KERJA DAN TRANSMIGRASI</h2>
                <p class="text-small">Jalan Percetakan Negara No. 7 Manokwari Papua Barat</p>
            </div>
        </div>

        <hr>

        <div class="col-12 border-bottom">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td width="30%">NOMOR PENDAFTARAN</td>
                        <td width="2%">:</td>
                        <td class="fw-bold" width="68%">
                            <?php echo isset($pencaker['nopendaftaran']) ? $pencaker['nopendaftaran'] : '-'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>TANGGAL PENDAFTARAN</td>
                        <td>:</td>
                        <td class="fw-bold">
                            <?php echo isset($user['created_at']) ? tanggal_indo($user['created_at']) : '-'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>NOMOR INDUK KEPENDUDUKAN</td>
                        <td>:</td>
                        <td class="fw-bold"><?php echo $pencaker['nik']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr>

        <div class="col-12 border-bottom">
            <div class="row">
                <div class="col-8">
                    <h4>KETERANGAN UMUM</h4>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td width="30%">Nama Lengkap</td>
                                <td width="2%">:</td>
                                <td width="68%" class="fw-bold"><?php echo strtoupper($pencaker['namalengkap']); ?></td>
                            </tr>
                            <tr>
                                <td>Tempat, Tanggal Lahir</td>
                                <td>:</td>
                                <td class="fw-bold">
                                    <?php echo strtoupper($pencaker['tempatlahir']) . ", " . $pencaker['tgllahir']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Tinggi Badan</td>
                                <td>:</td>
                                <td class="fw-bold"><?php echo $pencaker['tinggibadan'] . " CM"; ?></td>
                            </tr>
                            <tr>
                                <td>Berat Badan</td>
                                <td>:</td>
                                <td class="fw-bold"><?php echo $pencaker['beratbadan'] . " KG"; ?></td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td class="fw-bold"><?php echo ($pencaker['jenkel'] == 'L') ? 'LAKI-LAKI' : 'PEREMPUAN'; ?></td>
                            </tr>
                            <tr>
                                <td>Agama</td>
                                <td>:</td>
                                <td class="fw-bold"><?php echo strtoupper($pencaker['agama']); ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td class="fw-bold"><?php echo strtoupper($pencaker['alamat']); ?></td>
                            </tr>
                            <tr>
                                <td>Nomor HP</td>
                                <td>:</td>
                                <td class="fw-bold"><?php echo $pencaker['nohp']; ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td class="fw-bold"><?php echo $user['email']; ?></td>
                            </tr>
                            <tr>
                                <td>Kode Pos</td>
                                <td>:</td>
                                <td class="fw-bold"><?php echo $pencaker['kodepos']; ?></td>
                            </tr>
                            <tr>
                                <td>Status Pernikahan</td>
                                <td>:</td>
                                <td class="fw-bold"><?php echo strtoupper($pencaker['statusnikah']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-2 ms-auto d-flex justify-content-center align-items-center">
                    <?php
                    // Tentukan URL gambar default
                    $default_image_url = base_url('uploads/dokumen_pencaker/default.webp');
                    ?>

                    <?php if (isset($dokpasfoto['namadokumen']) && !empty($dokpasfoto['namadokumen'])) : ?>
                        <img class="w-100 pasfoto" src="<?php echo base_url('uploads/dokumen_pencaker/') . $dokpasfoto['nik'] . '/' . $dokpasfoto['namadokumen'] ?>" alt="Dokumen Pencaker">
                    <?php else : ?>
                        <img class="w-100 pasfoto" src="<?php echo $default_image_url; ?>" alt="Gambar Default">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <hr>

        <div class="col-12 border-bottom">
            <h4>PENDIDIKAN FORMAL</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="10px" style="padding: 3px !important;">No</th>
                        <th>Tahun Masuk</th>
                        <th>Tahun Lulus</th>
                        <th>Jenjang</th>
                        <th>Nama Satuan Pendidikan</th>
                        <th>NEM/NUN/IPK</th>
                        <th>Keterampilan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($pendidikan as $pd) : ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo isset($pd['tahunmasuk']) ? $pd['tahunmasuk'] : '-'; ?></td>
                            <td><?php echo isset($pd['tahunlulus']) ? $pd['tahunlulus'] : '-'; ?></td>
                            <td><?php echo isset($pd['jenjang']) ? $pd['jenjang'] : '-'; ?></td>
                            <td><?php echo isset($pd['nama_sekolah']) ? $pd['nama_sekolah'] : '-'; ?></td>
                            <td><?php echo isset($pd['ipk']) ? $pd['ipk'] : '-'; ?></td>
                            <td><?php echo isset($pd['keterampilan']) ? $pd['keterampilan'] : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>




        <hr>

        <div class="col-12 border-bottom">
            <h4>BAHASA YANG DIKUASAI</h4>
            <ul class="bahasa">
                <?php
                // Mendapatkan keterampilan bahasa dan bahasa lainnya, kemudian memisahkannya menjadi array
                $ket_bahasa = array_filter(explode(',', $pencaker['keterampilan_bahasa'] ?? ''));
                $bahasa_lainnya = array_filter(explode(',', $pencaker['bahasa_lainnya'] ?? ''));

                // Menggabungkan kedua array menjadi satu
                $all_languages = array_merge($ket_bahasa, $bahasa_lainnya);

                // Inisialisasi urutan
                $urut = 1;

                // Iterasi hanya pada elemen yang memiliki nilai
                foreach ($all_languages as $language) {
                    echo '<li>' . $urut++ . '. ' . strtoupper($language) . '</li>';
                }
                ?>
            </ul>
        </div>

        <hr>

        <div class="col-12 border-bottom">
            <h4>PENGALAMAN KERJA</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="10px" style="padding: 3px !important;">No</th>
                        <th>Tahun Masuk</th>
                        <th>Tahun Keluar</th>
                        <th>Nama Perusahaan/Instansi</th>
                        <th>Jabatan</th>
                        <th>Alasan Keluar</th>
                        <th>Pendapatan Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($pengalaman as $pk) : ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo isset($pk['tahunmasuk']) ? $pk['tahunmasuk'] : '-'; ?></td>
                            <td><?php echo isset($pk['tahunkeluar']) ? $pk['tahunkeluar'] : '-'; ?></td>
                            <td><?php echo isset($pk['instansi']) ? $pk['instansi'] : '-'; ?></td>
                            <td><?php echo isset($pk['jabatan']) ? $pk['jabatan'] : '-'; ?></td>
                            <td><?php echo isset($pk['alasan_berhenti']) ? $pk['alasan_berhenti'] : '-'; ?></td>
                            <td><?php echo isset($pk['pendapatan_terakhir']) ? $pk['pendapatan_terakhir'] : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <hr>
    </section>


    <section class="sheet padding-10mm d-flex justify-content-center align-items-center">
        <?php
        // Tentukan URL gambar default
        $default_image_url = base_url('uploads/dokumen_pencaker/noimageavailable.webp');
        ?>

        <?php if (isset($dokpasfoto['namadokumen']) && !empty($dokpasfoto['namadokumen'])) : ?>
            <img class="w-50 pasfoto" src="<?php echo base_url('uploads/dokumen_pencaker/') . $dokpasfoto['nik'] . '/' . $dokpasfoto['namadokumen'] ?>" alt="Dokumen Pencaker">
        <?php else : ?>
            <img class="w-50 pasfoto" src="<?php echo $default_image_url; ?>" alt="Gambar Default">
        <?php endif; ?>
    </section>

    <?php

    foreach ($alldokumen as $dp) :
        if ($dp['namadokumen'] != null && $dp['jenis_dokumen'] != 'PAS FOTO') { ?>
            <section class="sheet padding-10mm">
                <?php
                $url = base_url('uploads/dokumen_pencaker/') . $dp['nik'] . '/' . $dp['namadokumen'];
                $url = parse_url($url);
                $ext  = pathinfo($url['path'], PATHINFO_EXTENSION);
                if ($ext == 'pdf') {
                ?>
                    <iframe src="<?php echo base_url('uploads/dokumen_pencaker/') . $dp['nik'] . '/' . $dp['namadokumen'] ?>" frameborder="0" style="overflow:hidden;overflow-x:hidden;overflow-y:hidden;height:100%;width:100%;position:absolute;top:0px;left:0px;right:0px;bottom:0px" height="100%" width="100%"></iframe>

                <?php } else { ?>
                    <img class="text-center" src="<?php echo base_url('uploads/dokumen_pencaker/') . $dp['nik'] . '/' . $dp['namadokumen'] ?>" width="80%">
                <?php } ?>
            </section>
    <?php }
    endforeach;

    ?>
</body>

</html>