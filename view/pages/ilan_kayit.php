<?php
include('../../session.php');

include '../../Router/auth.php';
checkUserType('employer'); // erişim tipi
echo "Bu sayfa sadece işverenler içindir.";   

include('../../db.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['uye_id'])) {
        echo "<div class='alert alert-danger'>Oturum açma bilgileri bulunamadı. Lütfen tekrar oturum açın.</div>";
        exit;
    }

    $ilan_basligi = $_POST['ilan_basligi'];
    $ilan_icerik = $_POST['ilan_icerik'];
    $konum = $_POST['myCountry'];
    $calisma_sekli = isset($_POST['calisma_sekli']) ? $_POST['calisma_sekli'] : null;
    $calisma_tercihi = isset($_POST['calisma_tercihi']) ? $_POST['calisma_tercihi'] : null;
    $konum = $_POST['state'];

    if (empty($calisma_sekli) || empty($calisma_tercihi)) {
        echo "<div class='alert alert-danger'>Çalışma şekli ve çalışma tercihi alanları doldurulmalıdır.</div>";
        exit;
    }

    $uye_id = $_SESSION['uye_id'];

    // Dosya yükleme işlemi
    $target_dir = "../../gorsel_data/";
    $target_file = $target_dir . basename($_FILES["ilan_gorseli"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Dosya bir görüntü mü?
    $check = getimagesize($_FILES["ilan_gorseli"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<div class='alert alert-danger'>Dosya bir görüntü değil.</div>";
        $uploadOk = 0;
    }

    // Dosya zaten mevcut mu?
    if (file_exists($target_file)) {
        echo "<div class='alert alert-danger'>Üzgünüz, dosya zaten mevcut.</div>";
        $uploadOk = 0;
    }

    // Dosya boyutunu kontrol eder
    if ($_FILES["ilan_gorseli"]["size"] > 500000) { // 500KB
        echo "<div class='alert alert-danger'>Üzgünüz, dosyanız çok büyük.</div>";
        $uploadOk = 0;
    }

    // Sadece belirli dosya türlerine izin verilir
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<div class='alert alert-danger'>Üzgünüz, sadece JPG, JPEG, PNG ve GIF dosyalarına izin verilir.</div>";
        $uploadOk = 0;
    }

    // Dosya yükleme hatalarını kontrol eder
    if ($uploadOk == 0) {
        echo "<div class='alert alert-danger'>Üzgünüz, dosyanız yüklenmedi.</div>";
    } else {
        if (move_uploaded_file($_FILES["ilan_gorseli"]["tmp_name"], $target_file)) {
            $gorsel_yolu = $target_file;

            // İlanı veritabanına eklee
            $query = "INSERT INTO ilanlar (uye_id, ilan_baslik, ilan_aciklama, calisma_sekli, calisma_tercihi, yayin_tarihi, ilan_gorseli, konum) 
                      VALUES (:uye_id, :ilan_basligi, :ilan_icerik, :calisma_sekli, :calisma_tercihi, NOW(), :gorsel_yolu, :konum)";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':uye_id', $uye_id);
            $stmt->bindParam(':ilan_basligi', $ilan_basligi);
            $stmt->bindParam(':ilan_icerik', $ilan_icerik);
            $stmt->bindParam(':calisma_sekli', $calisma_sekli);
            $stmt->bindParam(':calisma_tercihi', $calisma_tercihi);
            $stmt->bindParam(':gorsel_yolu', $gorsel_yolu);
            $stmt->bindParam(':konum', $konum);

            if ($stmt->execute()) {
                // İlan başarıyla oluşturulduktan sonra son eklenen ilan_id'yi alır
                $ilan_id = $conn->lastInsertId();

                // Seçilen konumları alın
                $selectedLocations = json_decode($_POST['selectedLocations'], true);

                // Her bir konum için ilan_konumları tablosuna ekleme yapar
                if (is_array($selectedLocations) && !empty($selectedLocations)) {
                    $konumQuery = "INSERT INTO ilan_konumlari (ilan_id, konum) VALUES (:ilan_id, :konum)";
                    $konumStmt = $conn->prepare($konumQuery);

                    foreach ($selectedLocations as $konum) {
                        $konumStmt->bindParam(':ilan_id', $ilan_id);
                        $konumStmt->bindParam(':konum', $konum);
                        $konumStmt->execute();
                    }
                }

                // Meslek tercihlerini ilan_meslek tablosuna ekler
                if (isset($_POST['meslek_secim'])) {
                    $meslek_secim = $_POST['meslek_secim'];

                    // Her bir meslek için ilan_meslek tablosuna ekleme yapar
                    $meslekQuery = "INSERT INTO ilan_meslek (ilan_id, meslek_id) VALUES (:ilan_id, :meslek_id)";
                    $meslekStmt = $conn->prepare($meslekQuery);

                    foreach ($meslek_secim as $meslek_id) {
                        $meslekStmt->bindParam(':ilan_id', $ilan_id);
                        $meslekStmt->bindParam(':meslek_id', $meslek_id);
                        $meslekStmt->execute();
                    }
                }

                echo "<div id='success-alert' class='alert alert-success alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>
                        <i class='bi bi-check-circle me-3'></i> İlan başarıyla oluşturuldu.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      </div>";
            } else {
                echo "<div id='error-alert' class='alert alert-danger alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>
                        İlan oluşturulurken bir hata oluştu.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      </div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Üzgünüz, dosya yüklenirken bir hata oluştu.</div>";
        }
    }
}
?>
