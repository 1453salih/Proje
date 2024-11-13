<?php
ob_start();
session_start(); // Oturumu başlat
?>

<!DOCTYPE html>
<html lang="en">

<?php
$page_title = "Giriş Yap";
$page_css = "../css/login.css";
include '../partials/header.php';
?>

<body>
    <?php include '../partials/navbar.php'; ?>
    <div class="form_body"> <!--  100 Vh Kısmına Düzelt -->
        <!-- Main Content -->
        <div class="container-fluid">
            <div class="row main-content text-center">
                <div class="col-lg-6  text-center company__info">
                    <span class="content_img">
                        <img class="logo" src="../images/Jobhunt.gif"></img>
                    </span>
                </div>
                <div class="col-lg-6 col-md-12 col-xs-12 col-sm-12 login_form d-flex align-items-center">
                    <div class="container-fluid">
                        <div class="row text-start mb-3 mt-5">
                            <h1 class="form_title">İş Veren Üye Girişi</h1>
                        </div>
                        <div class="row">
                            <?php
                            $username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
                            ?>
                            <form method="POST" class="form-group">
                                <div class="row">
                                    <input type="text" name="username" id="username" class="form__input" placeholder="Kullanıcı Adı" value="<?php echo htmlspecialchars($username); ?>">
                                </div>
                                <div class="row d-flex flex-row input-group">
                                    <input type="password" name="password" id="passwordField" class="form__input" placeholder="Şifre">
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password" onclick="togglePasswordVisibility()">
                                            <i class="bi bi-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="row d-flex flex-row text-start">
                                    <label for="remember_me"><input type="checkbox" name="remember_me" id="remember_me" class="me-2">Beni Hatırla!</label>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <input type="submit" value="Giriş Yap" class="btn btn-submit">
                                </div>
                            </form>
                        </div>
                        <div class="row form_soru text-center">
                            <p>Üye değil misin ? <a href="./e_register.php"> Üye Ol.</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
    </div>
    <?php include '../partials/footer.php'; ?>
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("passwordField");
            var icon = document.querySelector(".toggle-password i");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                passwordField.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
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
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Veritabanı bağlantısı için gerekli bilgiler
        require_once '../../db.php';

        // Formdan gelen verileri al
        $username = $_POST['username'];
        $password = $_POST['password'];


        // Kullanıcı adı ve şifreyi doğrula işlemi
        $sql = "SELECT u.*, f.foto
            FROM uyeler u
            LEFT JOIN uye_bilgi f ON u.uye_id = f.uye_id
            WHERE u.kullanici_adi = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user) {
            if (password_verify($password, $user['sifre'])) { // Şifre doğruysa
                // Oturum başlar
                $_SESSION['username'] = $user['uye_adi'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['uye_id'] = $user['uye_id'];
                $_SESSION['photo_path'] = $user['foto'];
                // Kullanıcıyı hatırla işaretliyse, bir çerez oluşturur
                if (isset($_POST['remember_me'])) {
                    setcookie('username', $user['kullanici_adi'], time() + (86400 * 30), "/"); // 30 gün boyunca geçerli
                } else {
                    // Hatırlama işareti kaldırıldığında ilgili çerezleri siler
                    setcookie('username', '', time() - 3600, "/");
                }
                // Ana sayfaya yönlendirme
                echo "<div class='alert alert-success alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>Hoş geldiniz, " . $user['uye_adi'] . "
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
                echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 2000);</script>";
                exit();
            } else {
                echo "<div class='alert alert-danger alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>Kullanıcı adı veya şifre hatalı!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            }
        } else {
            echo "<div class='alert alert-danger alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>Kullanıcı bulunamadı!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
    }
    ?>
</body>

</html>

<?php
ob_end_flush(); // Çıktı tamponlamasını sonlandır ve çıktıyı gönder
?>