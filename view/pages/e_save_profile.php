<?php
include '../../session.php';

include '../../Router/auth.php';
checkUserType('employer'); // erişim tipi
echo "Bu sayfa sadece işverenler içindir.";

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


    // Save Address Info    
    $ulke = $_POST['ulke'];
    $sehir = $_POST['il'];
    $ilce = $_POST['ilce'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];

    $cleanedPhoneNumber = cleanPhoneNumber($telefon);

    $query = $conn->prepare("INSERT INTO uye_iletisim (uye_id, ulke, sehir, ilce, acik_adres) VALUES (:user_id, :ulke, :sehir, :ilce, '') ON DUPLICATE KEY UPDATE ulke = VALUES(ulke), sehir = VALUES(sehir), ilce = VALUES(ilce)");
    $query->execute(['user_id' => $userId, 'ulke' => $ulke, 'sehir' => $sehir, 'ilce' => $ilce]);
    

    $query = $conn->prepare("UPDATE uyeler SET eposta = :email, uye_tel = :telefon WHERE uye_id = :user_id");
    $query->execute(['email' => $email, 'telefon' => $cleanedPhoneNumber, 'user_id' => $userId]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

    // Save Company Info
    $companyName = $_POST['companyName'];
    $jobTitle = $_POST['jobTitle'];
    $companyPhone = $_POST['companyPhone'];
    $companyAddress = $_POST['companyAddress'];
    $registerNo = $_POST['registerNo'];
    $taxNumber = $_POST['taxNumber'];

    $query = $conn->prepare("INSERT INTO sirketler (sirket_id, uye_id, sektor_id, sirket_adi, sicil_no, vergi_no, sirket_adresi, sirket_telefon) VALUES (NULL, :user_id, :jobTitle, :companyName, :registerNo, :taxNumber, :companyAddress, :companyPhone) ON DUPLICATE KEY UPDATE sektor_id = VALUES(sektor_id), sirket_adi = VALUES(sirket_adi), sicil_no = VALUES(sicil_no), vergi_no = VALUES(vergi_no), sirket_adresi = VALUES(sirket_adresi), sirket_telefon = VALUES(sirket_telefon)");
    $query->execute(['user_id' => $userId, 'jobTitle' => $jobTitle, 'companyName' => $companyName, 'registerNo' => $registerNo, 'taxNumber' => $taxNumber, 'companyAddress' => $companyAddress, 'companyPhone' => $companyPhone]);

    header('Location: ./e_profil_details.php'); 
    exit();
}   
?>
