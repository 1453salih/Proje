<?php
include '../../session.php';
include '../../db.php';

$il_json = file_get_contents('../../turkiye_sehirler/il.json');
$ilce_json = file_get_contents('../../turkiye_sehirler/ilce.json');

$iller = json_decode($il_json, true)[2]['data'];
$ilceler = json_decode($ilce_json, true)[2]['data'];

$il_isimleri = array();
foreach ($iller as $il) {
    $il_isimleri[$il['id']] = $il['name'];
}

$ilce_isimleri = array();
foreach ($ilceler as $ilce) {
    $ilce_isimleri[$ilce['id']] = $ilce['name'];
}

if (!isset($_GET['id'])) {
    die("Geçersiz üye ID.");
}

$uye_id = $_GET['id'];

$query = "SELECT * FROM uyeler WHERE uye_id = :uye_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':uye_id', $uye_id, PDO::PARAM_INT);
$stmt->execute();
$uye = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$uye) {
    die("Kullanıcı bulunamadı.");
}

$query = "SELECT * FROM uye_bilgi WHERE uye_id = :uye_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':uye_id', $uye_id, PDO::PARAM_INT);
$stmt->execute();
$uye_bilgi = $stmt->fetch(PDO::FETCH_ASSOC);

$query = "SELECT * FROM uye_cv WHERE uye_id = :uye_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':uye_id', $uye_id, PDO::PARAM_INT);
$stmt->execute();
$uye_cv = $stmt->fetch(PDO::FETCH_ASSOC);

$query = "SELECT * FROM uye_iletisim WHERE uye_id = :uye_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':uye_id', $uye_id, PDO::PARAM_INT);
$stmt->execute();
$uye_iletisim = $stmt->fetch(PDO::FETCH_ASSOC);

$sehir_ismi = "Bilinmiyor";
$ilce_ismi = "Bilinmiyor";

if ($uye_iletisim) {
    $sehir_ismi = isset($il_isimleri[$uye_iletisim['sehir']]) ? $il_isimleri[$uye_iletisim['sehir']] : "Bilinmiyor";
    $ilce_ismi = isset($ilce_isimleri[$uye_iletisim['ilce']]) ? $ilce_isimleri[$uye_iletisim['ilce']] : "Bilinmiyor";
}

$page_title = "Üye Profili - JobFind";
include '../partials/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
</head>

<body>
    <header>
        <?php include '../partials/navbar.php'; ?>
    </header>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-4 col-md-12 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <?php if (isset($uye_bilgi['foto']) && !empty($uye_bilgi['foto'])) : ?>
                            <img src="<?php echo htmlspecialchars($uye_bilgi['foto']); ?>" class="img-fluid rounded-circle mb-3" alt="Profil Fotoğrafı">
                        <?php else : ?>
                            <img src="../images/default_user.png" class="img-fluid rounded-circle mb-3" alt="Profil Fotoğrafı">
                        <?php endif; ?>
                        <h4><?php echo htmlspecialchars($uye['uye_adi'] . ' ' . $uye['uye_soyadi']); ?></h4>
                        <p><?php echo htmlspecialchars($uye['kullanici_adi']); ?></p>
                    </div>
                </div>
                <div class="w-100 d-flex align-items-center justify-content-center mt-2">
                    <?php if ($_SESSION['uye_id'] == $uye_id) : ?>
                        <button id="editProfileBtn" class="button-style-1">Profili Düzenle</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Kişisel Bilgiler</h5>
                        <p><strong>Ad:</strong> <?php echo htmlspecialchars($uye['uye_adi']); ?></p>
                        <p><strong>Soyad:</strong> <?php echo htmlspecialchars($uye['uye_soyadi']); ?></p>
                        <p><strong>Telefon:</strong> <?php echo htmlspecialchars($uye['uye_tel']); ?></p>
                        <p><strong>E-posta:</strong> <?php echo htmlspecialchars($uye['eposta']); ?></p>
                        <p><strong>Cinsiyet:</strong> <?php echo isset($uye_bilgi['cinsiyet']) ? htmlspecialchars($uye_bilgi['cinsiyet']) : "-"; ?></p>
                        <p><strong>Doğum Tarihi:</strong> <?php echo isset($uye_bilgi['dogum_tarihi']) ? htmlspecialchars($uye_bilgi['dogum_tarihi']) : "-"; ?></p>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">İletişim Bilgileri</h5>
                        <p><strong>Ülke:</strong> <?php echo isset($uye_iletisim['ulke']) ? htmlspecialchars($uye_iletisim['ulke']) : "-"; ?></p>
                        <p><strong>Şehir:</strong> <?php echo htmlspecialchars($sehir_ismi); ?></p>
                        <p><strong>İlçe:</strong> <?php echo htmlspecialchars($ilce_ismi); ?></p>
                        <p><strong>Açık Adres:</strong> <?php echo isset($uye_iletisim['acik_adres']) ? htmlspecialchars($uye_iletisim['acik_adres']) : "-"; ?></p>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Sosyal Medya</h5>
                        <p><strong>Instagram:</strong> <?php echo isset($uye_bilgi['instagram']) ? '<a href="' . htmlspecialchars($uye_bilgi['instagram']) . '" target="_blank">' . htmlspecialchars($uye_bilgi['instagram']) . '</a>' : '-'; ?></p>
                        <p><strong>LinkedIn:</strong> <?php echo isset($uye_bilgi['linkedin']) ? '<a href="' . htmlspecialchars($uye_bilgi['linkedin']) . '" target="_blank">' . htmlspecialchars($uye_bilgi['linkedin']) . '</a>' : '-'; ?></p>
                        <p><strong>Facebook:</strong> <?php echo isset($uye_bilgi['facebook']) ? '<a href="' . htmlspecialchars($uye_bilgi['facebook']) . '" target="_blank">' . htmlspecialchars($uye_bilgi['facebook']) . '</a>' : '-'; ?></p>
                        <p><strong>GitHub:</strong> <?php echo isset($uye_bilgi['github']) ? '<a href="' . htmlspecialchars($uye_bilgi['github']) . '" target="_blank">' . htmlspecialchars($uye_bilgi['github']) . '</a>' : '-'; ?></p>
                    </div>
                </div>
                <div class="card mb-5">
                    <div class="card-body">
                        <h5 class="card-title">Özgeçmiş</h5>
                        <?php if (isset($uye_cv) && $uye_cv) : ?>
                            <embed src="<?php echo htmlspecialchars($uye_cv['ozgecmis']); ?>" type="application/pdf" width="100%" height="1200px" />
                        <?php else : ?>
                            <p>Özgeçmiş bulunmamaktadır.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('editProfileBtn').addEventListener('click', function() {
            <?php if ($_SESSION['user_type'] == 'normal_user') : ?>
                window.location.href = 'profil_details.php';
            <?php elseif ($_SESSION['user_type'] == 'employer') : ?>
                window.location.href = 'e_profil_details.php';
            <?php endif; ?>
        });
    </script>
</body>

</html>