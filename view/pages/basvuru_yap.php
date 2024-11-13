<?php
include '../../session.php';
include('../../db.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ilan_id'])) {
    $ilan_id = $_POST['ilan_id'];
    $uye_id = $_SESSION['uye_id']; 

    if (isset($uye_id) && isset($ilan_id)) {
        // CV'nin olup olmadığını kontrol eder
        $cv_query = "SELECT ozgecmis FROM uye_cv WHERE uye_id = :uye_id LIMIT 1";
        $cv_stmt = $conn->prepare($cv_query);
        $cv_stmt->bindParam(':uye_id', $uye_id, PDO::PARAM_INT);
        $cv_stmt->execute();
        $cv_result = $cv_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cv_result || empty($cv_result['ozgecmis'])) {
            // CV alanı boşsa veya kayıt yoksa profil_details.php sayfasına yönlendirir
            $_SESSION['cv_yuklenmedi'] = true;
            header('Location:./profil_details.php');
            exit();
        }

        // CV alanı doluysa başvuru işlemini gerçekleştirir
        $query = "INSERT INTO basvurular (ilan_id, uye_id) VALUES (:ilan_id, :uye_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':ilan_id', $ilan_id, PDO::PARAM_INT);
        $stmt->bindParam(':uye_id', $uye_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>Başvurunuz başarıyla yapıldı.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
            header('Location: job_details.php?id=' . $ilan_id);
            exit();
        } else {
            echo "Başvuru sırasında bir hata oluştu.";
        }
    }
} else {
    echo "Geçersiz istek.";
}
?>
