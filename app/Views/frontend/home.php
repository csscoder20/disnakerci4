<?= $this->extend('frontend/template') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

<style>
    .cta {
        background: linear-gradient(rgba(40, 58, 90, 0.9), rgba(40, 58, 90, 0.9)), url(../img/cta-bg.jpg) fixed center center;
        background-size: cover;
        padding: 120px 0;
    }

    .cta * {
        color: #fff;
    }

    .cta-btn {
        border: 2px solid #fff;
        background: #116db6;
        padding: 10px 24px;
        color: #fff;
        transition: 0.4s;
        border-radius: 10px;
    }

    #team .owl-nav {
        display: none;
    }

    section#services .container.aos-init.aos-animate {
        background-color: #116db6;
        padding: 15px;
        border-radius: 15px;
    }

    hr {
        margin: 5px 0;
    }
</style>

<section id="hero">
    <div id="heroCarousel" data-bs-interval="5000" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner" role="listbox">
            <!-- Default Slider -->
            <div class="carousel-item active" style="background-image: url(uploads/sliders/default.jpg);">
                <div class="carousel-container">
                    <div class="carousel-content animate__animated animate__fadeInUp d-flex align-items-center justify-content-center w-100" style="flex-direction: column; height: 100%; vertical-align: middle;">
                        <h2 class="text-center">Selamat Datang di <span>Website</span></h2>
                        <h1 class="text-center">DISNAKERTRANS MANOKWARI</h1>
                        <p class="text-center"> Jalan Percetakan Negara - Sanggeng <br> Manokwari - Papua Barat</p>
                        <div class="text-center"><a href="<?= base_url('kartu_ak1') ?>" class="btn-get-started">KARTU PENCARI KERJA (KARTU AK/1)</a></div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Sliders -->
            <?php
            $activeSliders = array_filter($sliderData, function ($slide) {
                return $slide['status'] == 1;
            });
            $activeSliders = array_values($activeSliders);
            $intervalSlider = 2000;
            foreach ($activeSliders as $index => $slide) : ?>
                <div class="carousel-item" data-bs-interval="<?= $intervalSlider; ?>" style="background-image: url(<?= base_url('uploads/sliders/' . $slide['gambar']); ?>);">
                    <div class="carousel-container">
                        <div class="carousel-content animate__animated animate__fadeInUp">
                            <h2><?= $slide['judul']; ?></h2>
                        </div>
                    </div>
                </div>
                <?php $intervalSlider += 2000; ?>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true">
                <i class="bi bi-chevron-left"></i>
            </span>
            <span class="visually-hidden">Previous</span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true">
                <i class="bi bi-chevron-right"></i>
            </span>
            <span class="visually-hidden">Next</span>
        </button>

        <ol class="carousel-indicators" id="hero-carousel-indicators">
        </ol>
    </div>
</section>


<section id="services" class="services section-bg bg-dark">
    <div class="container" data-aos="fade-up">
        <div class="row">
            <div class="col-lg-4 d-flex justify-content-center align-items-center">
                <div class="section-title text-white" style="flex-direction: column;">
                    <h1><strong>Layanan Kami</strong></h1>
                    <p>Dapatkan kemudahan akses ke berbagai layanan kami. Berpartisipasilah! Kami menanti Anda!</p>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-6 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon-box iconbox-blue">
                            <div class="icon">
                                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,521.0016835830174C376.1290562159157,517.8887921683347,466.0731472004068,529.7835943286574,510.70327084640275,468.03025145048787C554.3714126377745,407.6079735673963,508.03601936045806,328.9844924480964,491.2728898941984,256.3432110539036C474.5976632858925,184.082847569629,479.9380746630129,96.60480741107993,416.23090153303,58.64404602377083C348.86323505073057,18.502131276798302,261.93793281208167,40.57373210992963,193.5410806939664,78.93577620505333C130.42746243093433,114.334589627462,98.30271207620316,179.96522072025542,76.75703585869454,249.04625023123273C51.97151888228291,328.5150500222984,13.704378332031375,421.85034740162234,66.52175969318436,486.19268352777647C119.04800174914682,550.1803526380478,217.28368757567262,524.383925680826,300,521.0016835830174"></path>
                                </svg>
                                <i class="bx bxl-dribbble"></i>
                            </div>
                            <h4><a>Kartu Pencari Kerja</a></h4>
                            <p>Kartu Ak/1 (kartu Kuning) dikeluarkan oleh lembaga pemerintah seperti Dinas Ketenagakerjaan dengan tujuan untuk pendataan para pencari kerja.</p>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
                        <div class="icon-box iconbox-orange ">
                            <div class="icon">
                                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,582.0697525312426C382.5290701553225,586.8405444964366,449.9789794690241,525.3245884688669,502.5850820975895,461.55621195738473C556.606425686781,396.0723002908107,615.8543463187945,314.28637112970534,586.6730223649479,234.56875336149918C558.9533121215079,158.8439757836574,454.9685369536778,164.00468322053177,381.49747125262974,130.76875717737553C312.15926192815925,99.40240125094834,248.97055460311594,18.661163978235184,179.8680185752513,50.54337015887873C110.5421016452524,82.52863877960104,119.82277516462835,180.83849132639028,109.12597500060166,256.43424936330496C100.08760227029461,320.3096726198365,92.17705696193138,384.0621239912766,124.79988738764834,439.7174275375508C164.83382741302287,508.01625554203684,220.96474134820875,577.5009287672846,300,582.0697525312426"></path>
                                </svg>
                                <i class="bx bx-file"></i>
                            </div>
                            <h4><a>Pengaduan</a></h4>
                            <p>Diperuntukkan bagi pekerja/buruh/karyawan terkait masalah ketenagakerjaan, seperti Upah, Pesangon, Jaminan Kesehatan, PHK dan masalah lainnya.</p>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="300">
                        <div class="icon-box iconbox-pink mt-4">
                            <div class="icon">
                                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,541.5067337569781C382.14930387511276,545.0595476570109,479.8736841581634,548.3450877840088,526.4010558755058,480.5488172755941C571.5218469581645,414.80211281144784,517.5187510058486,332.0715597781072,496.52539010469104,255.14436215662573C477.37192572678356,184.95920475031193,473.57363656557914,105.61284051026155,413.0603344069578,65.22779650032875C343.27470386102294,18.654635553484475,251.2091493199835,5.337323636656869,175.0934190732945,40.62881213300186C97.87086631185822,76.43348514350839,51.98124368387456,156.15599469081315,36.44837278890362,239.84606092416172C21.716077023791087,319.22268207091537,43.775223500013084,401.1760424656574,96.891909868211,461.97329694683043C147.22146801428983,519.5804099606455,223.5754009179313,538.201503339737,300,541.5067337569781"></path>
                                </svg>
                                <i class="bx bx-tachometer"></i>
                            </div>
                            <h4><a>Pemagangan</a></h4>
                            <p>Menyiapkan angkatan kerja yang memiliki keterampilan sehingga mudah terserap dunia kerja.</p>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon-box iconbox-yellow mt-4">
                            <div class="icon">
                                <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,503.46388370962813C374.79870501325706,506.71871716319447,464.8034551963731,527.1746412648533,510.4981551193396,467.86667711651364C555.9287308511215,408.9015244558933,512.6030010748507,327.5744911775523,490.211057578863,256.5855673507754C471.097692560561,195.9906835881958,447.69079081568157,138.11976852964426,395.19560036434837,102.3242989838813C329.3053358748298,57.3949838291264,248.02791733380457,8.279543830951368,175.87071277845988,42.242879143198664C103.41431057327972,76.34704239035025,93.79494320519305,170.9812938413882,81.28167332365135,250.07896920659033C70.17666984294237,320.27484674793965,64.84698225790005,396.69656628748305,111.28512138212992,450.4950937839243C156.20124167950087,502.5303643271138,231.32542653798444,500.4755392045468,300,503.46388370962813"></path>
                                </svg>
                                <i class="bx bx-layer"></i>
                            </div>
                            <h4><a>Pelatihan & Sertifikasi</a></h4>
                            <p>Memungkinkan angkatan kerja membuka peluang kerja sendiri dan berwirausaha secara mandiri.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</section>


<section id="about-us" class="about-us">
    <div class="container" data-aos="fade-up">
        <div class="row content">
            <div class="col-lg-6" data-aos="fade-right">
                <img class="w-100" src="<?= base_url() ?>/uploads/tujuan.png" alt="Tujuan">
            </div>
            <div class="col-lg-6 pt-4 pt-lg-0" data-aos="fade-left">
                <h4>Tujuan</h4>
                <p>
                    Dengan berpedoman pada Visi dan Misi, Tujuan Dinas Tenaga Kerja dan Transmigrasi Kabupaten Manokwari dapat dirumuskan sebagai berikut:
                </p>
                <ul>
                    <li><i class="ri-check-double-line"></i> Meningkatkan perluasan dan kesempatan kerja;</li>
                    <li><i class="ri-check-double-line"></i> Meningkatkan kerjasama hubungan industrial dan Perlindungn tenaga kerja dengan pihak terkait;</li>
                    <li><i class="ri-check-double-line"></i> Terbangunnya rumah layak huni bagi transmigrasi lokal;</li>
                    <li><i class="ri-check-double-line"></i> Meningkatkan infrastruktur lokasi transmigrasi;</li>
                    <li><i class="ri-check-double-line"></i> Meingkatkan profesionalisme aparatur;</li>
                    <li><i class="ri-check-double-line"></i> Meningkatkan sarana dan prasarana aparat;</li>
                    <li><i class="ri-check-double-line"></i> Meningkatkan kualitas administrasi keuangan, teknis perencanaan dan pelaporan.</li>
                </ul>
            </div>
        </div>

    </div>
</section>

<section id="about-us" class="about-us">
    <div class="container" data-aos="fade-up">
        <div class="row content">
            <div class="col-lg-6 pt-4 pt-lg-0" data-aos="fade-left">
                <h4>Sasaran Strategis</h4>
                <p>
                    Dinas Tenaga Kerja dan Transmigrasi Kabupaten Manokwari telah merumuskan tujuan yang merupakan bagian integral dalam proses Rencana Strategis Dinas Tenaga Kerja Dan Transmigrasi Kabupaten Manokwari Tahun 2021 – 2025 untuk mencapai visi.
                </p>
                <ul>
                    <li><i class="ri-check-double-line"></i> Mendidik dan melatih para pencari kerja agar dapat mencari nafkahnya sendiri sehingga dapat mengurangi angka pengangguran dan mengurangi </li> kemiskinan;
                    <li><i class="ri-check-double-line"></i> Meningkatkan kerja sama hubungan industrial dan perlindungan Tenaga Kerja dengan pihak terkait;</li>
                    <li><i class="ri-check-double-line"></i> Meningkatnya Kualitas pemukiman yang layak huni, layak usaha, layak berkembang serta layak lingkungan;</li>
                    <li><i class="ri-check-double-line"></i> Meningkatkan kualitas Aparatur Dinas Nakertrans;</li>
                    <li><i class="ri-check-double-line"></i> Meningkatkan kualitas dan Kuantitas administrasi keuangan, perencanaan serta pelaporan Program dan Data.</li>
                </ul>
            </div>
            <div class="col-lg-6" data-aos="fade-right">
                <img class="w-100" src="<?= base_url() ?>/uploads/sasaran.webp" alt="Sasaran">
            </div>
        </div>

    </div>
</section>

<section id="cta" class="cta">
    <div class="container aos-init aos-animate" data-aos="zoom-in">

        <div class="row">
            <div class="col-lg-9 text-center text-lg-start">
                <h3>Kartu Pencari Kerja (Kartu Ak/1)</h3>
                <p>Proses pembuatan kartu kuning dimulai dengan pendaftaran akun untuk selanjutnya mengisi formulir Ak/1 dan mengunggah dokumen. Adapun dokumen yang dibutuhkan diantaranya: Pas Foto, KTP, Ijazah Terakhir, Transkrip Nilai, Riwayat Hidup, SKCK dan Suket. Sehat</p>
            </div>
            <div class="col-lg-3 cta-btn-container text-center d-flex align-items-center justify-content-end">

                <?php if (logged_in()) : ?>
                    <?php if (in_groups('administrator')) : ?>
                        <a class="btn-buy btn btn-success text-center" href="<?= url_to('admin_v2/dashboard') ?>">Masuk Panel Admin</a>
                    <?php elseif (in_groups('pencaker')) : ?>
                        <a class="btn-buy btn btn-success text-center" href="<?= url_to('pencaker/dashboard') ?>">Masuk Panel Pencaker</a>
                    <?php endif; ?>
                <?php else : ?>
                    <a class="cta-btn text-center btn btn-secondary" href="<?= url_to('register') ?>">Buat Akun Sekarang</a>
                <?php endif; ?>

            </div>
        </div>

    </div>
</section>

<section id="skills" class="skills">
    <div class="container" data-aos="fade-up">
        <div class="section-title">
            <h2>Statistik Pencari Kerja</h2>
            <p>
                Statistik pencari kerja di Kabupaten Manokwari berdasarkan Jenjang Pendidikan Terakhir dan Rentang Umur
            </p>
        </div>
        <div class="row">
            <div class="col-lg-6 pt-4 pt-lg-0 content" data-aos="fade-left" data-aos-delay="100">
                <div class="skills-content">
                    <h4>Berdasarkan Jenjang Pendidikan Terakhir</h4>
                    <?php foreach ($c_pendidikan_terakhir as $pt) : ?>
                        <div class="progress">
                            <span class="skill"><?= $pt->jenjang; ?> <i class="val"><?= $pt->total; ?> orang</i></span>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" role="progressbar" aria-valuenow="<?= $pt->total; ?>" aria-valuemin="0" aria-valuemax="<?= $max_umur; ?>"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-6 pt-4 pt-lg-0 content" data-aos="fade-left" data-aos-delay="100">
                <div class="skills-content">
                    <h4>Berdasarkan rentang umur</h4>
                    <?php
                    $u1 = 0;
                    $u2 = 0;
                    $u3 = 0;
                    $u4 = 0;
                    $u5 = 0;
                    $u6 = 0;
                    foreach ($c_umur as $u) :
                        if ($u->umur < 15) {
                            $u1 += $u->jumlah;
                        }
                        if ($u->umur >= 15 && $u->umur < 24) {
                            $u2 += $u->jumlah;
                        }
                        if ($u->umur >= 25 && $u->umur < 34) {
                            $u3 += $u->jumlah;
                        }
                        if ($u->umur >= 35 && $u->umur < 44) {
                            $u4 += $u->jumlah;
                        }
                        if ($u->umur >= 45 && $u->umur < 54) {
                            $u5 += $u->jumlah;
                        }
                        if ($u->umur > 54) {
                            $u6 += $u->jumlah;
                        }
                    endforeach;
                    ?>

                    <div class="progress">
                        <span class="skill">
                            < 15 th <i class="val"><?= $u1; ?> orang</i>
                        </span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="<?= $u1; ?>" aria-valuemin="0" aria-valuemax="<?= $max_umur; ?>"></div>
                        </div>
                    </div>

                    <div class="progress">
                        <span class="skill">15 - 24 th <i class="val"><?= $u2; ?> orang</i></span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="<?= $u2; ?>" aria-valuemin="0" aria-valuemax="<?= $max_umur; ?>"></div>
                        </div>
                    </div>

                    <div class="progress">
                        <span class="skill">25 - 34 th<i class="val"><?= $u3; ?> orang</i></span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="<?= $u3; ?>" aria-valuemin="0" aria-valuemax="<?= $max_umur; ?>"></div>
                        </div>
                    </div>

                    <div class="progress">
                        <span class="skill">35 - 44 th<i class="val"><?= $u4; ?> orang</i></span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="<?= $u4; ?>" aria-valuemin="0" aria-valuemax="<?= $max_umur; ?>"></div>
                        </div>
                    </div>

                    <div class="progress">
                        <span class="skill">45 - 54 th <i class="val"><?= $u5; ?> orang</i></span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="<?= $u5; ?>" aria-valuemin="0" aria-valuemax="<?= $max_umur; ?>"></div>
                        </div>
                    </div>

                    <div class="progress">
                        <span class="skill">> 54 th<i class="val"><?= $u6; ?> orang</i></span>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" role="progressbar" aria-valuenow="<?= $u6; ?>" aria-valuemin="0" aria-valuemax="<?= $max_umur; ?>"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section id="portfolio" class="portfolio">
    <div class="container">
        <div class="section-title">
            <h2>Galeri</h2>
            <p>Galeri Kegiatan Bidang Transmigrasi dan Tenaga Kerja</p>
        </div>
        <div class="row" data-aos="fade-up">
            <div class="col-lg-12 d-flex justify-content-center">
                <ul id="portfolio-flters">
                    <li data-filter="*" class="filter-active">Semua</li>
                    <?php foreach ($galleries as $category => $images) : ?>
                        <li data-filter=".filter-<?= $category; ?>"><?= ucfirst($category); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="row portfolio-container" data-aos="fade-up">
            <?php foreach ($galleries as $category => $images) : ?>
                <?php foreach ($images as $image) : ?>
                    <div class="col-lg-4 col-md-6 portfolio-item filter-<?= $category; ?>">
                        <img src="<?= $image['url']; ?>" class="img-fluid portfolio-lightbox preview-link">
                        <div class="portfolio-info">
                            <h4><?= $image['name']; ?></h4>
                            <p><?= ucfirst($category); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<section id="team" class="team section-bg bg-dark bg-gradient">
    <div class="container">
        <div class="section-title text-white" data-aos="fade-up">
            <h2>Tim <strong>Kerja</strong></h2>
            <p>Orang-orang hebat yang siap melayani Anda.</p>
        </div>
        <div class="owl-carousel owl-theme">
            <div class="item">
                <div class="member" data-aos="fade-up">
                    <div class="member-img">
                        <img src="<?= base_url(); ?>uploads/team/kadis.jpg" class="img-fluid" alt="">
                        <div class="social">
                            <a href=""><i class="bi bi-twitter"></i></a>
                            <a href=""><i class="bi bi-facebook"></i></a>
                            <a href=""><i class="bi bi-instagram"></i></a>
                            <a href=""><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                    <div class="member-info">
                        <h4>YUSAK DOWANSIBA, SH.MA.</h4>
                        <span>Kepala Dinas</span>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="member" data-aos="fade-up" data-aos-delay="100">
                    <div class="member-img">
                        <img src="<?= base_url(); ?>uploads/team/sekdis.jpg" class="img-fluid" alt="">
                        <div class="social">
                            <a href=""><i class="bi bi-twitter"></i></a>
                            <a href=""><i class="bi bi-facebook"></i></a>
                            <a href=""><i class="bi bi-instagram"></i></a>
                            <a href=""><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                    <div class="member-info">
                        <h4>JOLANDA HERLANI KWA, SE.M.Si.</h4>
                        <span>Sekretaris</span>
                    </div>
                </div>
            </div>

            <div class="item">
                <div class="member" data-aos="fade-up" data-aos-delay="200">
                    <div class="member-img">
                        <img src="<?= base_url(); ?>uploads/team/kabid_pentaker.jpg" class="img-fluid" alt="">
                        <div class="social">
                            <a href=""><i class="bi bi-twitter"></i></a>
                            <a href=""><i class="bi bi-facebook"></i></a>
                            <a href=""><i class="bi bi-instagram"></i></a>
                            <a href=""><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                    <div class="member-info">
                        <h4>EMA A.M RUMSAYOR, S.STP.</h4>
                        <span>Kabid Pentaker</span>
                    </div>
                </div>
            </div>

            <div class="item">
                <div class="member" data-aos="fade-up" data-aos-delay="300">
                    <div class="member-img">
                        <img src="<?= base_url(); ?>uploads/team/kabid_p2kt.jpg" class="img-fluid" alt="">
                        <div class="social">
                            <a href=""><i class="bi bi-twitter"></i></a>
                            <a href=""><i class="bi bi-facebook"></i></a>
                            <a href=""><i class="bi bi-instagram"></i></a>
                            <a href=""><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                    <div class="member-info">
                        <h4>HADI EKA PUTRA, SE.</h4>
                        <span>Kabid P2KT</span>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="member" data-aos="fade-up" data-aos-delay="300">
                    <div class="member-img">
                        <img src="<?= base_url(); ?>uploads/team/kabid_hubin.jpeg" class="img-fluid" alt="">
                        <div class="social">
                            <a href=""><i class="bi bi-twitter"></i></a>
                            <a href=""><i class="bi bi-facebook"></i></a>
                            <a href=""><i class="bi bi-instagram"></i></a>
                            <a href=""><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                    <div class="member-info">
                        <h4>Dra. SARAH SAMBARA</h4>
                        <span>KAbid HUBIN</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="faq section-bg">
    <div class="container" data-aos="fade-up">
        <div class="row">
            <div class="col-lg-4 d-flex justify-content-center align-items-center">
                <div class="section-title">
                    <h3><strong class="text-info">Pertanyaan yang sering ditanyakan user</strong></h3>
                    <p class="text-secondary mt-3">Temukan jawaban untuk pertanyaan yang sering ditanyakan seputar layanan yang disediakan oleh Disnakertrans Kab. Manokwari</p>
                    <hr>
                    <p class="mt-5">Tidak menemukan pertanyaan yang dicari?
                        <a href="<?php echo base_url('kontak'); ?>"><strong>Kunjungi Pusat Bantuan</strong></a>
                    </p>
                </div>
            </div>

            <div class="col-lg-8 d-flex justify-content-center align-items-center">
                <div class="faq-list">
                    <ul>
                        <li data-aos="fade-up">
                            <a data-bs-toggle="collapse" class="collapse px-0" data-bs-target="#faq-list-1">Bagaimana caranya membuat kartu pencari kerja (Kartu Ak/1)? <i class="bi bi-arrow-down-short icon-show"></i><i class="bi bi-arrow-up-short icon-close"></i></a>
                            <div id="faq-list-1" class="collapse show" data-bs-parent=".faq-list">
                                <p class="text-secondary">
                                    Pada website disnakertransmkw.com, pilih menu Layanan - Kartu Pencari Kerja (Kartu Ak/1), kemudian membuat akun dan menyiapkan berkas berupa file Ijazah terakhir, KTP, Riwayat Hidup, SKCK, Surat Keterangan Kesehatan.
                                </p>
                            </div>
                        </li>
                        <hr>

                        <li data-aos="fade-up" data-aos-delay="100">
                            <a data-bs-toggle="collapse" data-bs-target="#faq-list-2" class="collapsed px-0">Bagaimana cara memperoleh informasi lowongan pekerjaan di wilayah Manokwari? <i class="bi bi-arrow-down-short icon-show"></i><i class="bi bi-arrow-up-short icon-close"></i></a>
                            <div id="faq-list-2" class="collapse" data-bs-parent=".faq-list">
                                <p class="text-secondary">
                                    Cari sendiri lah
                                </p>
                            </div>
                        </li>

                        <hr>
                        <li data-aos="fade-up" data-aos-delay="200">
                            <a data-bs-toggle="collapse" data-bs-target="#faq-list-3" class="collapsed px-0">Apakah setelah mendapatkan pekerjaan, harus melapor ke Disnakertrans? <i class="bi bi-arrow-down-short icon-show"></i><i class="bi bi-arrow-up-short icon-close"></i></a>
                            <div id="faq-list-3" class="collapse" data-bs-parent=".faq-list">
                                <p class="text-secondary">
                                    Mmbetullll sekali.
                                </p>
                            </div>
                        </li>
                        <hr>
                        <li data-aos="fade-up" data-aos-delay="300">
                            <a data-bs-toggle="collapse" data-bs-target="#faq-list-4" class="collapsed px-0">Apakah ada program pelatihan/magang yang diselenggarakan Disnakertrans? <i class="bi bi-arrow-down-short icon-show"></i><i class="bi bi-arrow-up-short icon-close"></i></a>
                            <div id="faq-list-4" class="collapse" data-bs-parent=".faq-list">
                                <p class="text-secondary">
                                    Kayaknya ada deh.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
    $(document).ready(function() {
        $(".owl-carousel").owlCarousel({
            items: 4, // jumlah item yang ditampilkan dalam satu tampilan
            loop: true, // mengulang carousel secara berulang
            margin: 10, // jarak antar item
            nav: true, // menampilkan navigasi (sebelumnya dan selanjutnya)
            responsive: {
                0: {
                    items: 1
                },
                300: {
                    items: 2
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        });
    });
</script>

<?= $this->endSection() ?>