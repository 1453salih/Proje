<?php include '../../session.php'; ?>

<?php
include '../../db.php';

$sql = "SELECT ilan_baslik, ilan_aciklama, konum, calisma_sekli, yayin_tarihi, ilan_gorseli, ilan_id
FROM ilanlar
ORDER BY yayin_tarihi DESC
LIMIT 5";
$stmt = $conn->query($sql);
$ilanlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html class="no-js" lang="tr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="manifest" href="site.webmanifest"> -->
    <!-- <link rel="shortcut icon" type="image/x-icon" href="../../img/favicon.png"> -->
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../../css/magnific-popup.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/themify-icons.css">
    <link rel="stylesheet" href="../../css/nice-select.css">
    <link rel="stylesheet" href="../../css/flaticon.css">
    <link rel="stylesheet" href="../../css/gijgo.css">
    <link rel="stylesheet" href="../../css/animate.min.css">
    <link rel="stylesheet" href="../../css/slicknav.css">

    <link rel="stylesheet" href="../../css/style.css">
    <!-- <link rel="stylesheet" href="css/responsive.css"> -->
    <?php
    $page_title = "Anasayfa - JobFind";
    include '../partials/header.php';
    ?>
</head>

<body>
    <header>
        <div class="header-area">
            <?php include '../partials/navbar.php'; ?>
        </div>
    </header>

    <div class="slider_area">
        <div class="single_slider  d-flex align-items-center slider_bg_1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-md-6">
                        <div class="slider_text">
                            <h5 class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".2s">2000+ İş İlanı
                            </h5>
                            <h3 class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">Hayalinizdeki İşi bulun
                            </h3>
                            <p class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".4s">Yapay zeka desteği ile kendinize en uygun işi bulun!</p>
                            <div class="sldier_btn wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".5s">
                                <a href="#" class="boxed-btn3">Kayıt Olarak Başla</a>
                                <!-- Eğer Oturum Açılmış Halde ise  -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ilstration_img wow fadeInRight d-none d-lg-block text-right" data-wow-duration="1s" data-wow-delay=".2s">
            <img src="../images/illustration.png" alt="">
        </div>
    </div>

    <div class="catagory_area">
        <div class="container">
            
            <div class="row">
                <div class="col-lg-12">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="job_listing_area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="section_title">
                        <h3>İş İlanları</h3>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="brouse_job text-end">
                        <a href="../pages/search.php" class="boxed-btn4">Daha Fazla İlana Bak</a>
                    </div>
                </div>
            </div>
            <div class="job_lists">
                <div class="row">
                    <?php foreach ($ilanlar as $ilan) : ?>
                        <div class="col-lg-12 col-md-12">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">
                                    <div class="thumb">
                                        <img style="width: 50px; height:50px;" src="<?php echo htmlspecialchars($ilan['ilan_gorseli']); ?>" alt="İlan Görseli">
                                    </div>
                                    <div class="jobs_conetent">
                                        <a onclick="goToJobDetails('<?php echo $ilan['ilan_id']; ?>')">
                                            <h4><?php echo htmlspecialchars($ilan['ilan_baslik']); ?></h4>
                                        </a>
                                        <div class="links_locat d-flex align-items-center">
                                            <div class="location">
                                                <p><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($ilan['konum']); ?></p>
                                            </div>
                                            <div class="location">
                                                <p><i class="fa fa-clock-o"></i> <?php echo htmlspecialchars($ilan['calisma_sekli']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jobs_right">
                                    <div class="apply_now">
                                        <a class="boxed-btn3" onclick="goToJobDetails('<?php echo $ilan['ilan_id']; ?>')">Şimdi Başvur</a>
                                    </div>
                                    <div class="date">
                                        <p>Date line: <?php echo htmlspecialchars($ilan['yayin_tarihi']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="job_searcing_wrap overlay">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 offset-lg-1 col-md-6">
                    <div class="searching_text">
                        <h3>İş mi arıyorsunuz?</h3>
                        <p>Başvur ve anında sana uygun ilanı bul.</p>
                        <a href="./search.php" class="boxed-btn3">İş Ara</a>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 col-md-6">
                    <div class="searching_text">
                        <h3>Ekip arkadaşı mı arıyorsunuz?</h3>
                        <p>Hızlı bir şekilde çevrimiçi ilanını anında oluştur.</p>
                        <a href="./job_advert.php" class="boxed-btn3">İlan Ver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="testimonial_area  ">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title text-center mb-40">
                        <h3>Yorumlar</h3>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="testmonial_active owl-carousel">
                        <div class="single_carousel">
                            <div class="row">
                                <div class="col-lg-11">
                                    <div class="single_testmonial d-flex align-items-center">
                                        <div class="thumb">
                                            <img src="img/testmonial/author.png" alt="">
                                            <div class="quote_icon">
                                                <i class="Flaticon flaticon-quote"></i>
                                            </div>
                                        </div>
                                        <div class="info">
                                            <p>"Anında CV'mi yükledim ve kendime uygun iş ilanını buldum. Hızlı bir süreç sonunda şuan çalışıyorum.
                                            </p>
                                            <span>- Salih Korkmaz</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../partials/footer.php'; ?>

    <script src="../../js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="../../js/vendor/jquery-1.12.4.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/owl.carousel.min.js"></script>
    <script src="../../js/isotope.pkgd.min.js"></script>
    <script src="../../js/ajax-form.js"></script>
    <script src="../../js/waypoints.min.js"></script>
    <script src="../../js/jquery.counterup.min.js"></script>
    <script src="../../js/imagesloaded.pkgd.min.js"></script>
    <script src="../../js/scrollIt.js"></script>
    <script src="../../js/jquery.scrollUp.min.js"></script>
    <script src="../../js/wow.min.js"></script>
    <script src="../../js/nice-select.min.js"></script>
    <script src="../../js/jquery.slicknav.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/plugins.js"></script>
    <script src="../../js/gijgo.min.js"></script>


    <script src="../../js/contact.js"></script>
    <script src="../../js/jquery.ajaxchimp.min.js"></script>
    <script src="../../js/jquery.form.js"></script>
    <script src="../../js/jquery.validate.min.js"></script>
    <script src="../../js/mail-script.js"></script>


    <script src="../../js/main.js"></script>
    <script>
        function goToJobDetails(id) {
            // Burada id'yi kullanarak ilgili iş ilanı detay sayfasına yönlendirilir
            window.location.href = `job_details.php?id=${id}`;
        }
    </script>
</body>

</html>