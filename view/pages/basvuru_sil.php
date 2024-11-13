<?php
include '../../session.php';


include '../../Router/auth.php';
checkUserType('normal_user'); // erişim tipi
echo "Bu sayfa sadece işverenler içindir.";

include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['basvuru_id'])) {
    $basvuru_id = $_POST['basvuru_id'];
    $uye_id = $_SESSION['uye_id']; // Kullanıcı id'si oturumdan alınıyor

    // Başvuruyu veritabanından sil
    $query = "DELETE FROM basvurular WHERE id = :basvuru_id AND uye_id = :uye_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':basvuru_id', $basvuru_id, PDO::PARAM_INT);
    $stmt->bindParam(':uye_id', $uye_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Başvuru silme başarılı
        header('Location: basvurularim.php'); // Başvurular sayfasına yönlendirme
        exit();
    } else {
        echo "Başvuru silinirken bir hata oluştu.";
    }
} else {
    echo "Geçersiz istek.";
}
?>
