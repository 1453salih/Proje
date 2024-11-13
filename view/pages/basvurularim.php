<?php
include '../../session.php';

include '../../Router/auth.php';
checkUserType('normal_user'); // erişim tipi    

include '../../db.php';

$uye_id = $_SESSION['uye_id'];

// Kullanıcının başvurularını veritabanından çeker
$query = "
    SELECT b.*, i.ilan_baslik, i.konum, i.calisma_sekli, i.yayin_tarihi
    FROM basvurular b
    JOIN ilanlar i ON b.ilan_id = i.ilan_id
    WHERE b.uye_id = :uye_id
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':uye_id', $uye_id, PDO::PARAM_INT);
$stmt->execute();
$basvurular = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Başvurularım - JobFind";
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
    <div class="header-area">
        <?php include '../partials/navbar.php'; ?>
    </div>
    <div class="container mt-5">
        <h2>Başvurularım</h2>
        <?php if (count($basvurular) > 0) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">İlan Başlığı</th>
                        <th scope="col">Konum</th>
                        <th scope="col">Çalışma Şekli</th>
                        <th scope="col">Yayın Tarihi</th>
                        <th scope="col">Başvuru Tarihi</th>
                        <th scope="col">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($basvurular as $index => $basvuru) : ?>
                        <tr>
                            <th scope="row"><?php echo $index + 1; ?></th>
                            <td><?php echo htmlspecialchars($basvuru['ilan_baslik']); ?></td>
                            <td><?php echo htmlspecialchars($basvuru['konum']); ?></td>
                            <td><?php echo htmlspecialchars($basvuru['calisma_sekli']); ?></td>
                            <td><?php echo date('d.m.Y', strtotime($basvuru['yayin_tarihi'])); ?></td>
                            <td><?php echo date('d.m.Y', strtotime($basvuru['basvuru_tarihi'])); ?></td>
                            <td>
                                <form action="basvuru_sil.php" method="post" onsubmit="return confirm('Bu başvuruyu silmek istediğinizden emin misiniz?');">
                                    <input type="hidden" name="basvuru_id" value="<?php echo htmlspecialchars($basvuru['id']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Henüz bir başvuru yapmadınız.</p>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>