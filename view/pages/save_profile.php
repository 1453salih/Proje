<?php
include '../../session.php';

try {
    include '../../db.php';
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

$userId = $_SESSION['uye_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Save Personal Info
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'] ?? null;
    $github = $_POST['github'];
    $linkedin = $_POST['linkedin'];
    $facebook = $_POST['facebook'];

    $profilePhotoPath = isset($_POST['profilePhotoPath']) ? $_POST['profilePhotoPath'] : '../../images/default_user.png';

    $query = $conn->prepare("INSERT INTO uyeler (uye_id, uye_adi, uye_soyadi, user_type) VALUES (:user_id, :first_name, :last_name, 'user') ON DUPLICATE KEY UPDATE uye_adi = VALUES(uye_adi), uye_soyadi = VALUES(uye_soyadi)");
    $query->execute(['user_id' => $userId, 'first_name' => $firstName, 'last_name' => $lastName]);

    $query = $conn->prepare("INSERT INTO uye_bilgi (uye_id, cinsiyet, dogum_tarihi, foto, github, linkedin, facebook) VALUES (:user_id, :gender, :dob, :photo, :github, :linkedin, :facebook) ON DUPLICATE KEY UPDATE cinsiyet = VALUES(cinsiyet), dogum_tarihi = VALUES(dogum_tarihi), foto = VALUES(foto), github = VALUES(github), linkedin = VALUES(linkedin), facebook = VALUES(facebook)");
    $query->execute(['user_id' => $userId, 'dob' => $dob, 'gender' => $gender, 'photo' => $profilePhotoPath, 'github' => $github, 'linkedin' => $linkedin, 'facebook' => $facebook]);


    function cleanPhoneNumber($phoneNumber)
    {
        // numeric olmayanları siler
        return preg_replace('/\D/', '', $phoneNumber);
    }


    $ulke = $_POST['ulke'];
    $sehir = $_POST['il'];
    $ilce = $_POST['ilce'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $acik_adres = $_POST['address'];

    $cleanedPhoneNumber = cleanPhoneNumber($telefon);

    $query = $conn->prepare("INSERT INTO uye_iletisim (uye_id, ulke, sehir, ilce, acik_adres) 
                         VALUES (:user_id, :ulke, :sehir, :ilce, :acik_adres) 
                         ON DUPLICATE KEY UPDATE ulke = :ulke, sehir = :sehir, ilce = :ilce, acik_adres = :acik_adres");
    $query->execute([
        'user_id' => $userId,
        'ulke' => $ulke,
        'sehir' => $sehir,
        'ilce' => $ilce,
        'acik_adres' => $acik_adres
    ]);



    $query = $conn->prepare("UPDATE uyeler SET eposta = :email, uye_tel = :telefon WHERE uye_id = :user_id");
    $query->execute(['email' => $email, 'telefon' => $cleanedPhoneNumber, 'user_id' => $userId]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    // Dosya yükleme işlemi
    if (isset($_FILES['userCv']) && $_FILES['userCv']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['userCv']['tmp_name'];
        $fileName = $_FILES['userCv']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('pdf', 'doc', 'docx');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = '../../cv/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $ozgecmis = $dest_path;

                $stmt = $conn->prepare('SELECT ozgecmis FROM uye_cv WHERE uye_id = :uye_id');
                $stmt->execute(['uye_id' => $userId]);
                $existingCv = $stmt->fetchColumn();

                if ($existingCv) {
                    // Güncelleme işlemi
                    $stmt = $conn->prepare('UPDATE uye_cv SET ozgecmis = :ozgecmis WHERE uye_id = :uye_id');
                    $stmt->execute(['ozgecmis' => $ozgecmis, 'uye_id' => $userId]);
                } else {
                    // Yeni kayıt işlemi
                    $stmt = $conn->prepare('INSERT INTO uye_cv (uye_id, ozgecmis) VALUES (:uye_id, :ozgecmis)');
                    $stmt->execute(['uye_id' => $userId, 'ozgecmis' => $ozgecmis]);
                }

                echo 'Dosya başarıyla yüklendi ve veritabanına kaydedildi.';
            } else {
                echo 'Dosya yükleme sırasında bir hata oluştu.';
            }
        } else {
            echo 'Yalnızca PDF, DOC ve DOCX dosyaları yüklenebilir.';
        }
    } else {
        $stmt = $conn->prepare('SELECT ozgecmis FROM uye_cv WHERE uye_id = :uye_id');
        $stmt->execute(['uye_id' => $userId]);
        $existingCv = $stmt->fetchColumn();

        if ($existingCv) {
            echo 'Mevcut dosya korundu: ' . htmlspecialchars($existingCv, ENT_QUOTES, 'UTF-8');
        } else {
            echo 'Dosya seçilmedi ve daha önce kayıtlı bir dosya bulunamadı.';
        }
    }

    header('Location: ./profil_details.php'); 
    exit();
}
