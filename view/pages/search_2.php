<?php
include '../../session.php';
include '../../db.php';

// Sayfalama işlemleri
$ilan_per_page = isset($_GET['ilan_per_page']) ? (int)$_GET['ilan_per_page'] : 20; // Default olarak 20 ilan göster
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

$meslekler_sql = "SELECT id, meslek FROM meslekler";
$meslekler_stmt = $conn->prepare($meslekler_sql);
$meslekler_stmt->execute();
$meslekler = $meslekler_stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT ilanlar.* FROM ilanlar 
        LEFT JOIN ilan_meslek ON ilanlar.ilan_id = ilan_meslek.ilan_id 
        LEFT JOIN meslekler ON ilan_meslek.meslek_id = meslekler.id
        LEFT JOIN sektorler ON meslekler.sektor_id = sektorler.id
        WHERE 1=1";

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

$sql .= " ORDER BY yayin_tarihi DESC LIMIT :start, :ilan_per_page";
$stmt = $conn->prepare($sql);

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

// Toplam ilan sayısını al
$total_ilan_sql = "SELECT COUNT(*) as count FROM ilanlar WHERE 1=1";
if ($keyword) {
    $total_ilan_sql .= " AND (ilan_baslik LIKE '%$keyword%' OR ilan_aciklama LIKE '%$keyword%')";
}
if ($city) {
    $total_ilan_sql .= " AND konum LIKE '%$city%'";
}
if ($work_preference) {
    $total_ilan_sql .= " AND calisma_tercihi = '$work_preference'";
}
if ($position) {
    $total_ilan_sql .= " AND ilanlar.ilan_id IN (SELECT ilan_id FROM ilan_meslek WHERE meslek_id IN (SELECT id FROM meslekler WHERE meslek = '$position'))";
}
if ($sector) {
    $total_ilan_sql .= " AND ilanlar.ilan_id IN (SELECT ilan_id FROM ilan_meslek WHERE meslek_id IN (SELECT id FROM meslekler WHERE sektor_id = (SELECT id FROM sektorler WHERE sektor = '$sector')))";
}
if ($start_date && $end_date) {
    $total_ilan_sql .= " AND yayin_tarihi BETWEEN '$start_date' AND '$end_date'";
}
if ($work_type) {
    $total_ilan_sql .= " AND calisma_sekli = '$work_type'";
}
$total_ilan_result = $conn->query($total_ilan_sql);
$total_ilan_count = $total_ilan_result->fetch(PDO::FETCH_ASSOC)['count'];
$total_pages = ceil($total_ilan_count / $ilan_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<?php
$page_title = "İlanlar - JobFind";
$page_css = "../css/search.css";
include '../partials/header.php';
?>

<body>
    <header>
        <?php include '../partials/navbar.php'; ?>
    </header>
    <?php $breadcrumbs = [
        ['title' => 'İş İlanları']
    ];
    include '../partials/breadcrumb.php'; ?>
    <div class="main_frame container mt-3 ">
        <form method="GET" action="">
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
                                    <input class="form-check-input" type="radio" name="work_preference" id="tercih2" value="Hibrit" <?php if ($work_preference == 'Hibrit') echo 'checked'; ?>>
                                    <label class="form-check-label" for="tercih2">
                                        Hibrit
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_preference" id="tercih3" value="Uzaktan" <?php if ($work_preference == 'Uzaktan') echo 'checked'; ?>>
                                    <label class="form-check-label" for="tercih3">
                                        Uzaktan
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pozisyon -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Pozisyon
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="search-frame">
                                    <input id="myInput" type="search" name="position" placeholder="Pozisyon Ara" value="<?php echo htmlspecialchars($position); ?>">
                                    <button id="search_button" type="button"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sektör -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Sektör
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="search-frame">
                                    <input id="myInput" type="search" name="sector" placeholder="Sektör Ara" value="<?php echo htmlspecialchars($sector); ?>">
                                    <button id="search_button" type="button"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Yayın Tarihi -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Yayın Tarihi
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <input type="date" class="form-control" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                                <input type="date" class="form-control mt-2" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                            </div>
                        </div>
                    </div>
                    <!-- Çalışma Şekli -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                Çalışma Şekli
                            </button>
                        </h2>
                        <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_type" id="work_type1" value="Tam Zamanlı" <?php if ($work_type == 'Tam Zamanlı') echo 'checked'; ?>>
                                    <label class="form-check-label" for="work_type1">
                                        Tam Zamanlı
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_type" id="work_type2" value="Yarı Zamanlı" <?php if ($work_type == 'Yarı Zamanlı') echo 'checked'; ?>>
                                    <label class="form-check-label" for="work_type2">
                                        Yarı Zamanlı
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="work_type" id="work_type3" value="Serbest" <?php if ($work_type == 'Serbest') echo 'checked'; ?>>
                                    <label class="form-check-label" for="work_type3">
                                        Serbest
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Filtrele</button>
                </div>
            </div>
        </form>
        <div class="col-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="me-2">Sayfa Başına:</span>
                    <select name="ilan_per_page" onchange="this.form.submit()">
                        <option value="10" <?php if ($ilan_per_page == 10) echo 'selected'; ?>>10</option>
                        <option value="20" <?php if ($ilan_per_page == 20) echo 'selected'; ?>>20</option>
                        <option value="50" <?php if ($ilan_per_page == 50) echo 'selected'; ?>>50</option>
                        <option value="100" <?php if ($ilan_per_page == 100) echo 'selected'; ?>>100</option>
                    </select>
                </div>
                <div>
                    <?php if ($page > 1) { ?>
                        <a href="?page=<?php echo $page - 1; ?>&ilan_per_page=<?php echo $ilan_per_page; ?>" class="btn btn-primary">Önceki</a>
                    <?php } ?>
                    <span class="mx-2">Sayfa <?php echo $page; ?> / <?php echo $total_pages; ?></span>
                    <?php if ($page < $total_pages) { ?>
                        <a href="?page=<?php echo $page + 1; ?>&ilan_per_page=<?php echo $ilan_per_page; ?>" class="btn btn-primary">Sonraki</a>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <?php foreach ($ilanlar as $ilan) { ?>
                    <div class="job-postings d-flex mb-3" onclick="goToJobDetails('<?php echo $ilan['ilan_id']; ?>')">
                        <div class="job-image">
                            <img id="job-image" src="<?php echo htmlspecialchars($ilan['ilan_gorseli']); ?>" alt="İlan Görseli">
                        </div>
                        <div class="job-info w-100">
                            <div class="posting-title ms-5">
                                <h3><?php echo $ilan['ilan_baslik']; ?></h3>
                                <p><i class="bi bi-building"></i>JobFind</p>
                            </div>
                            <div class="posting-details ms-5 d-flex justify-content-between">
                                <p><i class="bi bi-clock me-1"></i><?php echo $ilan['calisma_sekli']; ?></p>
                                <p><i class="bi bi-geo-alt me-1"></i><?php echo $ilan['konum']; ?></p>
                                <p><i class="bi bi-calendar3 me-1"></i><?php echo date('d.m.Y', strtotime($ilan['yayin_tarihi'])); ?></p>
                            </div>
                        </div>

                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php include '../partials/footer.php'; ?>
</body>

</html>