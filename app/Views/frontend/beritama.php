<?= $this->extend('frontend/template') ?>
<?= $this->section('content') ?>
<section id="breadcrumbs" class="breadcrumbs">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
        </div>
    </div>
</section>

<section id="blog" class="blog">
    <div class="container" data-aos="fade-up">

        <div class="section-title">
            <h2>Berita</h2>
            <?php if (empty($informasi)) : ?>
                <p>Belum ada data untuk ditampilkan</p>
            <?php else : ?>
                <p>Temukan berita paling populer tentang Dianas Ketenagakerjaan dan Transmigrasi Kabupaten Manokwari</p>
            <?php endif; ?>
        </div>
        <div class="row d-flex align-items-center justify-content-center">
            <?php foreach ($informasi as $item) : ?>
                <div class="col-lg-4 entries mt-3">
                    <article class="entry p-2 card">
                        <div class="entry-img mt-0 position-relative">
                            <img src="<?= base_url('uploads/berita/' . $item['gambar']) ?>" alt="<?= $item['judul'] ?>" class="img-fluid">
                            <a href="<?= base_url('berita/detail_berita/' . $item['slug']) ?>" class="overlay text-info">
                                <i class="bi bi-eye"></i>
                                <span>Baca Selengkapnya</span>
                            </a>
                        </div>
                        <h2 class="entry-title">
                            <a href="<?= base_url('berita/detail_berita/' . $item['slug']) ?>"><?= substr(strip_tags($item['judul']), 0, 40) ?> ...</a>
                        </h2>
                        <div class="entry-content">
                            <p class="mb-0">
                                <?= substr(strip_tags($item['isi']), 0, 100) ?> ...
                            </p>
                        </div>
                        <hr>
                        <div class="entry-meta">
                            <ul>
                                <li class="d-flex align-items-center"><i class="bi bi-person"></i><?= $item['namalengkap']; ?></li>
                                <li class="d-flex align-items-center">
                                    <i class="bi bi-calendar"></i>
                                    <time datetime="<?= $item['tgl_publikasi']; ?>"><?= date('M d, Y', strtotime($item['tgl_publikasi'])); ?>
                                    </time>
                                </li>
                            </ul>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>

        </div>
        <div class="row mt-4">
            <div class="blog-pagination">
                <ul class="justify-content-center">
                    <?php if ($pager) : ?>
                        <li><?= $pager->links() ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</section>


<?= $this->endSection() ?>