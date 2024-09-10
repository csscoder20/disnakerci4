<?= $this->extend('frontend/template') ?>
<?= $this->section('content') ?>
<style>
    .bg-info,
    .btn-info {
        background-color: #116db6 !important;
        border: 0;
    }

    .text-info {
        color: #116db6 !important;
    }

    .sidebar li {
        list-style: none;
    }

    .sidebar ul {
        padding: 0;
    }

    .mr-3 {
        margin-right: 3px;
    }


    .ribbon-wrapper {
        height: 70px;
        overflow: hidden;
        position: absolute;
        right: -2px;
        top: -2px;
        width: 70px;
        z-index: 10;
    }

    .ribbon-wrapper.ribbon-lg {
        height: 120px;
        width: 120px;
    }

    .ribbon-wrapper.ribbon-lg .ribbon {
        right: 0;
        top: 26px;
        width: 160px;
    }


    .ribbon-wrapper .ribbon {
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
        font-size: 0.8rem;
        line-height: 100%;
        padding: 0.375rem 0;
        position: relative;
        right: -2px;
        text-align: center;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.4);
        text-transform: uppercase;
        top: 10px;
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
        width: 90px;
    }

    .ribbon-wrapper .ribbon::before,
    .ribbon-wrapper .ribbon::after {
        border-left: 3px solid transparent;
        border-right: 3px solid transparent;
        border-top: 3px solid #9e9e9e;
        bottom: -3px;
        content: "";
        position: absolute;
    }

    .ribbon-wrapper .ribbon::before {
        left: 0;
    }

    .ribbon-wrapper .ribbon::after {
        right: 0;
    }

    .bi.bi-calendar-check-fill {
        margin-right: 3px;
    }

    .bi.bi-clock-fill {
        margin: 0 3px 0 15px;
    }
</style>
<section id="breadcrumbs" class="breadcrumbs">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
        </div>
    </div>
</section>

<section id="blog" class="blog">
    <div class="container" data-aos="fade-up">
        <div class="row">
            <div class="col-lg-8 entries">
                <article class="entry entry-single py-0">
                    <div class="entry-img">
                        <img src="<?= base_url('uploads/pelatihan/' . $pelatihan['gambar']) ?>" alt="<?= $pelatihan['judul'] ?>" class="w-100">
                    </div>
                    <h2 class="entry-title">
                        <?= $pelatihan['judul'] ?>
                    </h2>
                    <div class="entry-meta">
                        <ul>
                            <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="#"><?= $pelatihan['namalengkap'] ?></a></li>
                            <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="#"><time datetime="<?= $pelatihan['tanggal'] ?>"><?= date('M d, Y', strtotime($pelatihan['tanggal'])) ?></time></a></li>
                            <li class="d-flex align-items-center"><i class="bi bi-tag"></i> <a href="#"><?= $pelatihan['pelatihan'] ?></a></li>
                        </ul>
                    </div>
                    <hr>
                    <div class="entry-content mb-4">
                        <h6><strong>Deskripsi Pelatihan</strong></h6>
                        <?= $pelatihan['deskripsi'] ?>
                    </div>
                    <hr>
                    <div class="entry-content mb-4">
                        <h6><strong>Apa yang akan kamu pelajari?</strong></h6>
                        <p>Ringkasan umum materi yang akan kamu pelajari, sebagai berikut:</p>
                        <?= $pelatihan['materi'] ?>
                    </div>

                    <div class="entry-footer">
                        <i class="bi bi-folder"></i>
                        <ul class="cats">
                            <li><?= $pelatihan['pelatihan'] ?></li>
                        </ul>
                    </div>
                </article>
            </div>

            <div class="col-lg-4">
                <div class="sidebar position-relative p-3 bg-gray">
                    <div class="card p-3">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon bg-info text-light">
                                GRATIS
                            </div>
                        </div>
                        <div class="card-header px-0">
                            <h3 class="sidebar-title">Yuk, Bergabung Sekarang!</h3>
                            <p class="text-secondary">Anda dapat mengikuti pelatihan ini dengan cara klik tombol di bawah ini.</p>
                        </div>
                        <div class="card-body px-0">
                            <p class="text-secondary">Program ini sudah termasuk </p>
                            <ul>
                                <li class="text-secondary"><i class="bi bi-check mr-3"></i>Sertifikat</li>
                                <li class="text-secondary"><i class="bi bi-check mr-3"></i>Materi</li>
                            </ul>
                            <a href="<?= $pelatihan['link'] ?>" class="btn btn-info text-light text-small mt-3" target="_blank">Ikuti Pelatihan</a>
                        </div>
                    </div>

                    <hr>

                    <div class="card p-3">
                        <div class="card-header px-0">
                            <h3 class="sidebar-title mt-3">Jadwal Pelatihan</h3>
                            <p class="text-secondary">Jangan sampai ketinggalan. Yuk, catat waktu pelaksanaan pelatihan ini.</p>
                        </div>
                        <div class="card-body px-0">
                            <?php
                            // Misal $pelatihan['tanggal'] = '2024-08-14 14:30:00';

                            // Memisahkan tanggal dan waktu
                            $tanggal = date('Y-m-d', strtotime($pelatihan['tgl_pelatihan'])); // Output: 2024-08-14
                            $jam = date('H:i', strtotime($pelatihan['tgl_pelatihan']));       // Output: 14:30
                            ?>

                            <!-- Menampilkan Tanggal dan Waktu Secara Terpisah -->
                            <i class="bi bi-calendar-check-fill text-info"></i>
                            <time datetime="<?= $tanggal; ?>"><?= $tanggal; ?></time>
                            <i class="bi bi-clock-fill text-info"></i>
                            <time datetime="<?= $jam; ?>"><?= $jam; ?></time>
                        </div>
                    </div>
                    <hr>

                    <div class="card p-3">
                        <div class="card-header px-0">
                            <h3 class="sidebar-title mt-3">Pelatihan Sejenis</h3>
                            <?php if (!empty($recentPosts)) : ?>
                                <p class="text-secondary">Di bawah ini adalah pelatihan dengan jenis yang sama.</p>
                            <?php endif; ?>
                        </div>
                        <div class="card-body px-0">
                            <div class="sidebar-item recent-posts">
                                <?php if (empty($recentPosts)) : ?>
                                    <p class="text-muted">Belum ada data untuk ditampilkan di halaman ini.</p>
                                <?php else : ?>
                                    <?php foreach ($recentPosts as $post) : ?>
                                        <div class="post-item clearfix">
                                            <img src="<?= base_url('uploads/pelatihan/' . $post['gambar']); ?>" alt="<?= $post['judul']; ?>">
                                            <h4><a href="<?= base_url('pelatihan/detail_pelatihan/' . $post['slug']); ?>"><?= substr(strip_tags($post['judul']), 0, 40) ?> ...</a></h4>
                                            <time datetime="<?= $post['tanggal']; ?>"><?= date('M d, Y', strtotime($post['tanggal'])); ?></time>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</section>
<?= $this->endSection() ?>