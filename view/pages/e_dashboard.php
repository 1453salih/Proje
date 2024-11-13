<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/e_dashboard.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Bildirimler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Çıkış</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">İlanlar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Başvurular</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Meslekler</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Bölümler</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">İlanlar</h1>
                </div>

                <!-- Ilanlar Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Başlık</th>
                                <th>Açıklama</th>
                                <th>Konum</th>
                                <th>Çalışma Şekli</th>
                                <th>Çalışma Tercihi</th>
                                <th>Yayın Tarihi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- PHP ile ilan verilerini çekiyoruz -->
                            <?php
                            include '../../db.php';
                            session_start();
                            $uye_id = $_SESSION['uye_id'];
                            $stmt = $conn->prepare("SELECT * FROM ilanlar WHERE uye_id = :uye_id");
                            $stmt->execute(['uye_id' => $uye_id]);
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                    <td>{$row['ilan_id']}</td>
                                    <td>{$row['ilan_baslik']}</td>
                                    <td>{$row['ilan_aciklama']}</td>
                                    <td>{$row['konum']}</td>
                                    <td>{$row['calisma_sekli']}</td>
                                    <td>{$row['calisma_tercihi']}</td>
                                    <td>{$row['yayin_tarihi']}</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
