<?php if (isset($_GET['status'])): ?>
    <div class="">
        <?php echo $_GET['status'] == 'success' ? "<div id='success-alert' class='alert alert-success alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>
                       <i class='bi bi-check-circle me-3'></i> İlan başarıyla silindi.
                       <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                     </div>" : "<div id='error-alert' class='alert alert-error alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>
                       <i class='bi bi-check-circle me-3'></i> İlan silinirken hata oluştu.
                       <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                     </div>"; ?>
    </div>
<?php endif; ?>


<?php
include '../../session.php';
include '../../db.php';

// Sayfalama işlemleri
$ilan_per_page = isset($_GET['ilan_per_page']) ? (int)$_GET['ilan_per_page'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $ilan_per_page;

// Filtreleme işlemleri
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';
$work_preference = isset($_GET['work_preference']) ? $_GET['work_preference'] : '';
$position = isset($_GET['position']) ? $_GET['position'] : '';
$sector = isset($_GET['sector']) ? $_GET['sector'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$work_type = isset($_GET['work_type']) ? $_GET['work_type'] : '';

// Meslekleri veritabanından çekme
$meslekler_sql = "SELECT id, meslek FROM meslekler";
$meslekler_stmt = $conn->prepare($meslekler_sql);
$meslekler_stmt->execute();
$meslekler = $meslekler_stmt->fetchAll(PDO::FETCH_ASSOC);

// Sektörleri çekmek için SQL sorgusu hazırlar
$query = "SELECT id, sektor FROM sektorler";
$stmt = $conn->prepare($query);
$stmt->execute();
$sektorler = $stmt->fetchAll(PDO::FETCH_ASSOC);

$session_uye_id = $_SESSION['uye_id'];

// SQL sorgusunu oluşturma
$sql = "SELECT DISTINCT ilanlar.* FROM ilanlar 
        LEFT JOIN ilan_meslek ON ilanlar.ilan_id = ilan_meslek.ilan_id 
        LEFT JOIN meslekler ON ilan_meslek.meslek_id = meslekler.id
        LEFT JOIN sektorler ON meslekler.sektor_id = sektorler.id
        WHERE ilanlar.uye_id = :uye_id";

if ($keyword) {
    $sql .= " AND (ilan_baslik LIKE :keyword OR ilan_aciklama LIKE :keyword)";
}
if ($city) {
    $sql .= " AND konum LIKE :city";
}
if ($work_preference) {
    $sql .= " AND calisma_tercihi = :work_preference";
}
if ($position) {
    $sql .= " AND meslekler.meslek = :position";
}
if ($sector) {
    $sql .= " AND sektorler.sektor = :sector";
}
if ($start_date && $end_date) {
    $sql .= " AND yayin_tarihi BETWEEN :start_date AND :end_date";
}
if ($work_type) {
    $sql .= " AND calisma_sekli = :work_type";
}

$sql .= " ORDER BY ilanlar.yayin_tarihi DESC";
$sql .= " LIMIT :start, :ilan_per_page";
$stmt = $conn->prepare($sql);

$stmt->bindValue(':uye_id', $session_uye_id, PDO::PARAM_INT);
if ($keyword) {
    $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
}
if ($city) {
    $stmt->bindValue(':city', "%$city%", PDO::PARAM_STR);
}
if ($work_preference) {
    $stmt->bindValue(':work_preference', $work_preference, PDO::PARAM_STR);
}
if ($position) {
    $stmt->bindValue(':position', $position, PDO::PARAM_STR);
}
if ($sector) {
    $stmt->bindValue(':sector', $sector, PDO::PARAM_STR);
}
if ($start_date && $end_date) {
    $stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
    $stmt->bindValue(':end_date', $end_date, PDO::PARAM_STR);
}
if ($work_type) {
    $stmt->bindValue(':work_type', $work_type, PDO::PARAM_STR);
}

$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':ilan_per_page', $ilan_per_page, PDO::PARAM_INT);
$stmt->execute();
$ilanlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Toplam ilan sayısını alır
$total_ilan_sql = "SELECT COUNT(*) as count FROM ilanlar 
                   LEFT JOIN ilan_meslek ON ilanlar.ilan_id = ilan_meslek.ilan_id 
                   LEFT JOIN meslekler ON ilan_meslek.meslek_id = meslekler.id
                   LEFT JOIN sektorler ON meslekler.sektor_id = sektorler.id
                   WHERE ilanlar.uye_id = :uye_id";

if ($keyword) {
    $total_ilan_sql .= " AND (ilan_baslik LIKE :keyword OR ilan_aciklama LIKE :keyword)";
}
if ($city) {
    $total_ilan_sql .= " AND konum LIKE :city";
}
if ($work_preference) {
    $total_ilan_sql .= " AND calisma_tercihi = :work_preference";
}
if ($position) {
    $total_ilan_sql .= " AND meslekler.meslek = :position";
}
if ($sector) {
    $total_ilan_sql .= " AND sektorler.sektor = :sector";
}
if ($start_date && $end_date) {
    $total_ilan_sql .= " AND yayin_tarihi BETWEEN :start_date AND :end_date";
}
if ($work_type) {
    $total_ilan_sql .= " AND calisma_sekli = :work_type";
}

$total_stmt = $conn->prepare($total_ilan_sql);
$total_stmt->bindValue(':uye_id', $session_uye_id, PDO::PARAM_INT);
if ($keyword) {
    $total_stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
}
if ($city) {
    $total_stmt->bindValue(':city', "%$city%", PDO::PARAM_STR);
}
if ($work_preference) {
    $total_stmt->bindValue(':work_preference', $work_preference, PDO::PARAM_STR);
}
if ($position) {
    $total_stmt->bindValue(':position', $position, PDO::PARAM_STR);
}
if ($sector) {
    $total_stmt->bindValue(':sector', $sector, PDO::PARAM_STR);
}
if ($start_date && $end_date) {
    $total_stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
    $total_stmt->bindValue(':end_date', $end_date, PDO::PARAM_STR);
}
if ($work_type) {
    $total_stmt->bindValue(':work_type', $work_type, PDO::PARAM_STR);
}
$total_stmt->execute();
$total_ilan_count = $total_stmt->fetch(PDO::FETCH_ASSOC)['count'];
$total_pages = ceil($total_ilan_count / $ilan_per_page);


?>

<!DOCTYPE html>
<html lang="en">

<?php
$page_title = "İlanlar - JobFind";
$page_css = "../css/ilanlarim.css";
include '../partials/header.php';
?>

<body>
    <header>
        <?php include '../partials/navbar.php'; ?>
    </header>
    <?php $breadcrumbs = [
        ['title' => 'İlanlarım']
    ];
    include '../partials/breadcrumb.php'; ?>
    <div class="main_frame container mt-3 ">
        <form method="GET" action="" class="search-form">
            <div class="col-2 sidebar me-5">
                <div class="accordion" id="accordionExample">
                    <!-- Filtreleme Seçenekleri -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Anahtar Kelime
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="search-frame">
                                    <input id="tag_input" name="keyword" type="search" placeholder="Ara..." value="<?php echo htmlspecialchars($keyword); ?>">
                                    <button id="search_button_tag" type="submit"><span class="bi bi-search"></span></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Şehir -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Şehir
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="search-frame">
                                    <input id="myInput" type="search" name="city" placeholder="İl-İlçe Ara" value="<?php echo htmlspecialchars($city); ?>">
                                    <button id="search_button" type="button"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Çalışma Tercihi -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Çalışma Tercihi
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_preference" id="tercih1" value="İş Yerinde" <?php if ($work_preference == 'İş Yerinde') echo 'checked'; ?>>
                                    <label class="form-check-label" for="tercih1">
                                        İş Yerinde
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_preference" id="tercih2" value="Uzaktan" <?php if ($work_preference == 'Uzaktan') echo 'checked'; ?>>
                                    <label class="form-check-label" for="tercih2">
                                        Uzaktan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_preference" id="tercih3" value="Hibrit" <?php if ($work_preference == 'Hibrit') echo 'checked'; ?>>
                                    <label class="form-check-label" for="tercih3">
                                        Hibrit
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Çalışma Şekli -->
                    <!-- Çalışma Şekli -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Çalışma Şekli
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_type" id="sekli1" value="Tam Zamanlı" <?php if ($work_type == 'Tam Zamanlı') echo 'checked'; ?>>
                                    <label class="form-check-label" for="sekli1">
                                        Tam Zamanlı
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_type" id="sekli2" value="Yarı Zamanlı" <?php if ($work_type == 'Yarı Zamanlı') echo 'checked'; ?>>
                                    <label class="form-check-label" for="sekli2">
                                        Yarı Zamanlı
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_type" id="sekli3" value="Sözleşmeli" <?php if ($work_type == 'Sözleşmeli') echo 'checked'; ?>>
                                    <label class="form-check-label" for="sekli3">
                                        Sözleşmeli
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_type" id="sekli4" value="Serbest Çalışma" <?php if ($work_type == 'Serbest Çalışma') echo 'checked'; ?>>
                                    <label class="form-check-label" for="sekli4">
                                        Serbest Çalışma
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_type" id="sekli5" value="Staj" <?php if ($work_type == 'Staj') echo 'checked'; ?>>
                                    <label class="form-check-label" for="sekli5">
                                        Staj
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pozisyon -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Pozisyon
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <select name="position" class="form-select">
                                    <option value="">Pozisyon Seç</option>
                                    <?php foreach ($meslekler as $meslek) : ?>
                                        <option value="<?php echo $meslek['meslek']; ?>" <?php if ($position == $meslek['meslek']) echo 'selected'; ?>><?php echo $meslek['meslek']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Sektör -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Sektör
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <select name="sector" class="form-select">
                                    <option value="">Sektör Seç</option>
                                    <!-- Veritabanından çekilen sektörleri listele -->
                                    <?php foreach ($sektorler as $sektor) : ?>
                                        <option value="<?php echo htmlspecialchars($sektor['sektor']); ?>" <?php if ($sektor['sektor'] == $sector) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($sektor['sektor']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Tarih Aralığı -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                Yayın Tarihi
                            </button>
                        </h2>
                        <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="d-flex">
                                    <input type="date" name="start_date" class="form-control me-2" value="<?php echo htmlspecialchars($start_date); ?>">
                                    <input type="date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($end_date); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-grid mt-3">
                    <button class="btn btn-primary" type="submit">Filtrele</button>
                    <a class="btn btn-secondary mt-2" href="./ilanlarim.php">Sıfırla</a>
                </div>
            </div>
        </form>

        <!-- İlanlar Listesi -->
        <div class="col-9 ilan-listesi">
            <div class="main-top-right d-flex justify-content-end mb-2">
                <div class="dropdown me-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        İlan Sayfa Sayısı
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php echo $ilan_per_page == 10 ? 'active' : ''; ?>" href="?ilan_per_page=10">10 İlan / Sayfa</a></li>
                        <li><a class="dropdown-item <?php echo $ilan_per_page == 20 ? 'active' : ''; ?>" href="?ilan_per_page=20">20 İlan / Sayfa</a></li>
                        <li><a class="dropdown-item <?php echo $ilan_per_page == 50 ? 'active' : ''; ?>" href="?ilan_per_page=50">50 İlan / Sayfa</a></li>
                    </ul>
                </div>
            </div>
            <?php if ($ilanlar) : ?>
                <div class="ilanlar">
                    <?php foreach ($ilanlar as $ilan) :

                        // Şirket adını getirmek için sorgu
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
                        $sirket_adi_stmt->bindParam(':id', $ilan["ilan_id"], PDO::PARAM_INT);
                        $sirket_adi_stmt->execute();
                        $sirket = $sirket_adi_stmt->fetch(PDO::FETCH_ASSOC); // Tek satır için fetch kullanıyoruz
                    ?>
                        <div class="job-postings d-flex mb-3" onclick="goToAdvertDetails('<?php echo $ilan['ilan_id']; ?>')">
                            <div class="job-image">
                                <img id="job-image" src="<?php echo htmlspecialchars($ilan['ilan_gorseli']); ?>" alt="İlan Görseli">
                            </div>
                            <div class="job-info w-100">
                                <div class="posting-title ms-5">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <h3><?php echo $ilan['ilan_baslik']; ?></h3>
                                        </div>
                                        <div class="col-lg-4 d-flex p-1">
                                            <div class="mb-1 me-1">
                                                <button class="btn btn-outline-info advert-buttons" onclick="goToAdvertDetails('<?php echo $ilan['ilan_id']; ?>')">Başvuranlar</button>
                                            </div>
                                            <div class="mb-1">
                                                <form action="delete_ilan.php" method="POST" onsubmit="return confirm('Bu ilanı silmek istediğinize emin misiniz?');">
                                                    <input type="hidden" name="ilan_id" value="<?php echo $ilan['ilan_id']; ?>">
                                                    <button type="submit" class="btn btn-outline-danger advert-buttons">Sil</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <p><i class="bi bi-building"></i><?php echo htmlspecialchars($sirket["sirket_adi"]); ?></p>
                                </div>
                                <div class="posting-details ms-5 d-flex justify-content-between">
                                    <p><i class="bi bi-clock me-1"></i><?php echo $ilan['calisma_sekli']; ?></p>
                                    <p><i class="bi bi-geo-alt me-1"></i><?php echo $ilan['konum']; ?></p>
                                    <p><i class="bi bi-calendar3 me-1"></i><?php echo date('d.m.Y', strtotime($ilan['yayin_tarihi'])); ?></p>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <nav>
                        <ul class="pagination">
                            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&ilan_per_page=<?php echo $ilan_per_page; ?>" aria-label="Önceki">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                <li class="page-item <?php if ($page == $i) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>&ilan_per_page=<?php echo $ilan_per_page; ?>"><?php echo $i; ?></a></li>
                            <?php endfor; ?>
                            <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&ilan_per_page=<?php echo $ilan_per_page; ?>" aria-label="Sonraki">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php else : ?>
                <div class="alert alert-warning" role="alert">
                    İlan bulunamadı.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <?php include '../partials/footer.php'; ?>
    </footer>
    <script>
        function goToJobDetails(id) {
            window.location.href = `job_details.php?id=${id}`;
        }

        function goToAdvertDetails(id) {
            window.location.href = `basvuranlar.php?id=${id}`;
        }
        //! -------------------Başarı mesajı için zamanlayıcı-----------------------
        setTimeout(function() {
            var successAlert = document.getElementById('success-alert');
            if (successAlert) {
                var alert = new bootstrap.Alert(successAlert);
                alert.close();
            }
        }, 5000); // 5 saniye sonra kapanır

        // Hata mesajı için zamanlayıcı
        setTimeout(function() {
            var errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                var alert = new bootstrap.Alert(errorAlert);
                alert.close();
            }
        }, 5000); // 5 saniye sonra kapanır
    </script>
</body>

</html>