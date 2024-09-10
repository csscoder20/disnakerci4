<?= $this->extend('frontend/template') ?>

<?= $this->section('content') ?>
<section id="breadcrumbs" class="breadcrumbs">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
        </div>
    </div>
</section>

<div class="map-section">
    <iframe style="border:0; width: 100%; height: 350px;" src="<?php echo $settings['maps']; ?>" frameborder="0" allowfullscreen></iframe>
</div>

<section id="contact" class="contact">
    <div class="container">

        <div class="row justify-content-center" data-aos="fade-up">

            <div class="col-lg-10">

                <div class="info-wrap">
                    <div class="row">
                        <div class="col-lg-4 info">
                            <i class="bi bi-geo-alt"></i>
                            <h4>Lokasi:</h4>
                            <p><?php echo $settings['company_address']; ?></p>
                        </div>

                        <div class="col-lg-4 info mt-4 mt-lg-0">
                            <i class="bi bi-envelope"></i>
                            <h4>Email:</h4>
                            <p><?php echo $settings['company_email']; ?></p>
                        </div>

                        <div class="col-lg-4 info mt-4 mt-lg-0">
                            <i class="bi bi-phone"></i>
                            <h4>Telepon:</h4>
                            <p><?php echo $settings['company_phone1']; ?>, <?php echo $settings['company_phone2']; ?></p>
                        </div>
                    </div>
                </div>

            </div>


        </div>

        <div class="row mt-5 justify-content-center" data-aos="fade-up">
            <div class="col-lg-10">
                <form action="forms/contact.php" method="post" role="form" class="php-email-form">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-md-6 form-group mt-3 mt-md-0">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <input type="text" class="form-control" name="subject" id="subject" placeholder="Judul Pesan" required>
                    </div>
                    <div class="form-group mt-3">
                        <textarea class="form-control" name="message" rows="5" placeholder="Isi Pesan" required></textarea>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div class="form-group">
                            <label for="message" class="control-label">Isi</label>
                            <textarea class="form-control" name="message" rows="4"></textarea>
                            <span class="text-danger"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 mt-3">
                            <button id="submitBtn" type="submit" class="btn btn-info text-light w-25">
                                <span id="btnText">Kirim</span>
                                <span id="btnLoading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                    <div class="text-center"><button type="submit">Kirim Pesan</button></div>
                </form>
            </div>
        </div>

    </div>
</section>
<?= $this->endSection() ?>