<?php
include '../../session.php';
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ilan_id'])) {
    $ilan_id = $_POST['ilan_id'];
    $session_uye_id = $_SESSION['uye_id'];

    // İlanı silme sorgusu
    $delete_sql = "DELETE FROM ilanlar WHERE ilan_id = :ilan_id AND uye_id = :uye_id";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bindParam(':ilan_id', $ilan_id, PDO::PARAM_INT);
    $delete_stmt->bindParam(':uye_id', $session_uye_id, PDO::PARAM_INT);

    if ($delete_stmt->execute()) {
        // Silme başarılı
        header('Location: ilanlarim.php?status=success');
    } else {
        // Silme başarısız
        header('Location: ilanlarim.php?status=error');
    }
} else {
    // Geçersiz istek
    header('Location: ilanlarim.php');
}
?>
