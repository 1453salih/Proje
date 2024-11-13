<?php
include '../../session.php';

// include '../../Router/auth.php';
// checkUserType('normal_user'); // erişim tipi


include '../../db.php';

?>

<!DOCTYPE html>
<html lang="en">
<?php
$page_title = "İlanlar - JobFind";
$page_css = "../css/job_details.css";
include '../partials/header.php';
?>

<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
        }
    </style>
</head>

<body>
    <header>
        <?php include '../partials/navbar.php'; ?>
    </header>

    <?php
    // Önceki sayfanın URL'sini alır şuan lazım değil
    $previous_page = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

    // $_GET ile ilan_id'yi alır
    if (isset($_GET['id'])) {
        $ilan_id = $_GET['id'];

        // Veritabanından ilan verilerini çeker
        $stmt = $conn->prepare("SELECT * FROM ilanlar WHERE ilan_id = :id");
        $stmt->bindParam(':id', $ilan_id, PDO::PARAM_INT);
        $stmt->execute();
        $ilan = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ilan) {
            die("İlan bulunamadı.");
        }

        // Kullanıcının bu ilana başvurup başvurmadığını kontrol eder
        if (isset($_SESSION['uye_id'])) {
            $uye_id = $_SESSION['uye_id'];
            $basvuru_stmt = $conn->prepare("SELECT COUNT(*) FROM basvurular WHERE ilan_id = :ilan_id AND uye_id = :uye_id");
            $basvuru_stmt->bindParam(':ilan_id', $ilan_id, PDO::PARAM_INT);
            $basvuru_stmt->bindParam(':uye_id', $uye_id, PDO::PARAM_INT);
            $basvuru_stmt->execute();
            $basvuru_yapildi = $basvuru_stmt->fetchColumn() > 0;
        } else {
            $basvuru_yapildi = false;
        }

        //İlan konumlarını çeker
        $konum_stmt = $conn->prepare("SELECT konum FROM ilan_konumlari WHERE ilan_id = :id");
        $konum_stmt->bindParam(':id', $ilan_id, PDO::PARAM_INT);
        $konum_stmt->execute();
        $konumlar = $konum_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Şirket bilgilerini çeker
        $sirket_adi_stmt = $conn->prepare("
        SELECT
            sirketler.sirket_adi,
            sirketler.sirket_adresi
        FROM 
            ilanlar
        JOIN 
            sirketler
        ON 
            ilanlar.uye_id = sirketler.uye_id
        WHERE
            ilanlar.ilan_id = :id
        ");
        $sirket_adi_stmt->bindParam(':id', $ilan_id, PDO::PARAM_INT);
        $sirket_adi_stmt->execute();
        $sirket = $sirket_adi_stmt->fetch(PDO::FETCH_ASSOC); 

        // Şirket adı null ise "JobFind" kullanır
        $sirket_adi = $sirket['sirket_adi'] ?? 'JobFind';

        // Veritabanından ilan mesleklerini çeker
        $meslek_stmt = $conn->prepare("SELECT im.*, m.meslek, m.sektor_id FROM ilan_meslek im JOIN meslekler m ON im.meslek_id = m.id WHERE im.ilan_id = :id");
        $meslek_stmt->bindParam(':id', $ilan_id, PDO::PARAM_INT);
        $meslek_stmt->execute();
        $meslekler = $meslek_stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        die("Geçersiz ilan ID.");
    }

    $breadcrumbs = [
        [
            'title' => 'İlanlar',
            'link'  => '../pages/search.php'
        ],
        [
            'title' => 'İlan Detay'
        ]
    ];
    include '../partials/breadcrumb.php';
    ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12 mt-5">
                <div class="job-postings d-flex mb-3">
                    <div class="job-image">
                        <img id="job-image" src="<?php echo htmlspecialchars($ilan['ilan_gorseli']); ?>" alt="İlan Görseli">
                    </div>
                    <div class="job-info w-100">
                        <div class="posting-title ms-5">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h3><?php echo htmlspecialchars($ilan['ilan_baslik']); ?></h3>
                                </div>
                                <?php if ($basvuru_yapildi) : ?>
                                    <div class="col-lg-4">
                                        <button class="btn btn-secondary" disabled>Başvuru yapıldı</button>
                                    </div>
                                <?php else : ?>
                                    <div class="col-lg-4 text-end">
                                        <form action="./basvuru_yap.php" method="post">
                                            <input type="hidden" name="ilan_id" value="<?php echo htmlspecialchars($ilan['ilan_id']); ?>">
                                            <button class="btn btn-success" type="submit">Başvur</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p><i class="bi bi-building"></i><?php echo htmlspecialchars($sirket['sirket_adi']); ?></p>
                        </div>
                        <div class="posting-details ms-5 d-flex justify-content-between">
                            <p><i class="bi bi-clock me-1"></i><?php echo htmlspecialchars($ilan['calisma_sekli']); ?></p>
                            <p><i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($ilan['konum']); ?></p>
                            <p><i class="bi bi-calendar3 me-1"></i><?php echo date('d.m.Y', strtotime($ilan['yayin_tarihi'])); ?></p>
                        </div>
                    </div>

                </div>
                <div class="job-description">
                    <h2>İş Tanımı & Aranan Nitelikler</h2>
                    <div>
                        <?php echo $ilan['ilan_aciklama']; ?> 
                    </div>
                </div>
            </div>


            <div class="col-lg-4 col-md-12 mt-5">
                <div class="job-summary">
                    <h2>İş Özeti</h2>
                    <p><strong>Yayınlanma Tarihi:</strong> <?php echo date('d M, Y', strtotime($ilan['yayin_tarihi'])); ?></p>
                    <p><strong>Çalışma Şekli:</strong> <?php echo htmlspecialchars($ilan['calisma_sekli']); ?></p>
                    <p><strong>Çalışma Tercihi:</strong> <?php echo htmlspecialchars($ilan['calisma_tercihi']); ?></p>
                    <p><strong>Konum:</strong> <?php echo htmlspecialchars($ilan['konum']); ?></p>
                </div>
                <div class="map-error">
                    <div id="map"></div>
                </div>
            </div>
        </div>


        <!-- Boş alanın altına yeni div ekleniyor -->
        <div class="row">
            <div class="col-lg-8 col-md-12 mt-2">
                <div class="konum_tercihleri">
                    <h4>Konum Önceliği:</h4>
                    <?php if (!empty($konumlar)) : ?>
                        <?php foreach ($konumlar as $konum) : ?>
                            <span class="badge badge-info"><?php echo htmlspecialchars($konum['konum']); ?></span>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>Konum bilgisi bulunmamaktadır.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-12 mt-2">
                <div class="konum_tercihleri">
                    <h4>Meslek Grubu</h4>
                    <?php if (!empty($meslekler)) : ?>
                        <?php foreach ($meslekler as $meslek) : ?>
                            <span class="badge badge-info"><?php echo htmlspecialchars($meslek['meslek']); ?></span>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>Meslek bilgisi bulunmamaktadır.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Harita oluşturma
        var map = L.map('map').setView([0, 0], 16); 

        // Leaflet harita sağlayıcısı
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Şirket adresini alır
        var companyAddress = "<?php echo htmlspecialchars($sirket['sirket_adresi']); ?>";

        // Adresi koordinatlara çevirme (geocode) - OpenStreetMap Nominatim kullanımı
        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(companyAddress))
            .then(response => response.json())
            .then(data => {
                var companyLatLng = [parseFloat(data[0].lat), parseFloat(data[0].lon)];

                // Haritayı günceller
                map.setView(companyLatLng, 16);
                L.marker(companyLatLng).addTo(map)
                    .bindPopup(companyAddress)
                    .openPopup();
            });
    </script>


    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'div.job-description div',
            inline: true,
            readonly: true,
            plugins: 'autolink lists media table',
            toolbar: false
        });
    </script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>