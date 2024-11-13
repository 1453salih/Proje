<?php
include '../../session.php';
include '../../db.php';

// Flask uygulamasını başlatır
function start_flask_app() {
    // çalışıp çalışmadığını kontrol eder
    $output = null;
    $retval = null;
    exec("tasklist | findstr python", $output, $retval);

    // Flask uygulaması çalışmıyorsa, çalıştırır
    if ($retval != 0) {
        $command = 'start /B python C:\xampp\htdocs\Proje\flask_app.py';
        exec($command);
    }
}

// Flask uygulamasını başlatır
start_flask_app();

$uye_id = $_SESSION['uye_id'];

if (!isset($_GET['id'])) {
    die("Geçersiz ilan ID.");
}

$ilan_id = $_GET['id'];

$query = "SELECT * FROM ilanlar WHERE ilan_id = :ilan_id AND uye_id = :uye_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':ilan_id', $ilan_id, PDO::PARAM_INT);
$stmt->bindParam(':uye_id', $uye_id, PDO::PARAM_INT);
$stmt->execute();
$ilan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ilan) {
    die("Bu ilan size ait değil veya ilan bulunamadı.");
}

$query = "
    SELECT b.*, u.uye_adi, u.uye_soyadi, u.eposta, cv.ozgecmis
    FROM basvurular b
    JOIN uyeler u ON b.uye_id = u.uye_id
    JOIN uye_cv cv ON b.uye_id = cv.uye_id
    WHERE b.ilan_id = :ilan_id
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':ilan_id', $ilan_id, PDO::PARAM_INT);
$stmt->execute();
$basvuranlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ilan_aciklama = $ilan['ilan_aciklama'];
$page_title = "Başvuranlar - JobFind";
include '../partials/header.php';

function get_similarity_score($resume_path, $job_description) {
    $url = 'http://localhost:5000/calculate_similarity';
    $data = array('resume_path' => $resume_path, 'job_description' => $job_description);
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        error_log("Flask API çağrısı sırasında hata: " . error_get_last()['message']);
        return "Hata";
    }
    $response = json_decode($result, true);
    if (isset($response['error'])) {
        error_log("Flask API'den dönen hata: " . $response['error']);
        return "Hata";
    }
    return $response['similarity_score'] . '%';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <style>
        .spinner {
           position: fixed;
           top: 50%;
           left: 50%;
           transform: translate(-50%, -50%);
           width: 20px;
           height: 20px;
           display: block; /* Loader başlangıçta görünür olmalı */
        }

        .spinner div {
           width: 100%;
           height: 100%;
           background-color: #474bff;
           border-radius: 50%;
           animation: spinner-4t3wzl 1.25s infinite backwards;
        }

        .spinner div:nth-child(1) {
           animation-delay: 0.15s;
           background-color: rgba(71,75,255,0.9);
        }

        .spinner div:nth-child(2) {
           animation-delay: 0.3s;
           background-color: rgba(71,75,255,0.8);
        }

        .spinner div:nth-child(3) {
           animation-delay: 0.45s;
           background-color: rgba(71,75,255,0.7);
        }

        .spinner div:nth-child(4) {
           animation-delay: 0.6s;
           background-color: rgba(71,75,255,0.6);
        }

        .spinner div:nth-child(5) {
           animation-delay: 0.75s;
           background-color: rgba(71,75,255,0.5);
        }

        @keyframes spinner-4t3wzl {
           0% {
              transform: rotate(0deg) translateY(-200%);
           }

           60%, 100% {
              transform: rotate(360deg) translateY(-200%);
           }
        }

        #content {
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <?php include '../partials/navbar.php'; ?>
    </header>

    <div class="spinner">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>

    <div class="container mt-5" id="content">
        <h2><?php echo htmlspecialchars($ilan['ilan_baslik']); ?> İlanına Başvuranlar</h2>
        <?php if (count($basvuranlar) > 0) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Ad</th>
                        <th scope="col">Soyad</th>
                        <th scope="col">Email</th>
                        <th scope="col">Başvuru Tarihi</th>
                        <th scope="col">Profili Görüntüle</th>
                        <th scope="col">Puan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($basvuranlar as $index => $basvuran) : ?>
                        <tr>
                            <th scope="row"><?php echo $index + 1; ?></th>
                            <td><?php echo htmlspecialchars($basvuran['uye_adi']); ?></td>
                            <td><?php echo htmlspecialchars($basvuran['uye_soyadi']); ?></td>
                            <td><?php echo htmlspecialchars($basvuran['eposta']); ?></td>
                            <td><?php echo date('d.m.Y', strtotime($basvuran['basvuru_tarihi'])); ?></td>
                            <td>
                                <a href="uye_profil.php?id=<?php echo htmlspecialchars($basvuran['uye_id']); ?>" class="btn btn-primary">Profili Görüntüle</a>
                            </td>
                            <td>
                                <?php
                                $resume_path = $_SERVER['DOCUMENT_ROOT'] . '/Proje/cv/' . basename($basvuran['ozgecmis']);
                                if (file_exists($resume_path)) {
                                    echo get_similarity_score($resume_path, $ilan_aciklama);
                                } else {
                                    echo "Hata: Dosya yolu bulunamadı: " . htmlspecialchars($resume_path);
                                }
                                ?>
                            </td>
                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Bu ilana henüz kimse başvurmadı.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Spinner başlangıçta görünür olacak
            $(".spinner").show();
            $("#content").hide();

            // Tüm sayfa yüklendiğinde içerik gösterilir ve loader gizlenir
            $(window).on('load', function() {
                $(".spinner").hide();
                $("#content").show();
            });
        });
    </script>
</body>
</html>
