<!DOCTYPE html>
<html lang="en">
<?php
$page_title = "Kayıt Ol";
$page_css = "../css/register.css";
include '../partials/header.php';
?>

<body>
    <header>
        <?php include '../partials/navbar.php'; ?>
    </header>
    <?php $breadcrumbs = [
        ['title' => 'Kayıt Ol']
    ];
    include '../partials/breadcrumb.php'; ?>
    <div class="main_body container  mt-5  ">
        <!-- Main Content -->
        <div class="content_left col-lg-6 ">
            <div class="card mb-3" style="max-width: 650px;">
                <div class="row g-0">
                    <div class="col-md-4 icon_div">
                        <span class="icon_loc p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-person-check img-fluid" viewBox="0 0 16 16" style="color:#004aad;">
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                                <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
                            </svg>
                        </span>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Kayıt Ol</h5>
                            <p class="card-text">Kayıt ol ve işletmene ey uygun yetkinliğe sahip yol arkadaşını bul!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card my-3" style="max-width: 650px;">
                <div class="row g-0">
                    <div class="col-md-4 icon_div">
                        <span class="icon_loc p-4">
                            <img src="../images/approved.png" class="img-fluid" alt="...">
                        </span>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">CV Okumaya Son</h5>
                            <p class="card-text">CV'leri yapay zeka okusun sen ise en uygun adayı tek tıkla görüntüle.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card my-3" style="max-width: 650px;">
                <div class="row g-0">
                    <div class="col-md-4 icon_div">
                        <span class="icon_loc p-4">
                            <img src="../images/business.png" class="img-fluid" alt="...">
                        </span>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Uygun Adayı Bul</h5>
                            <p class="card-text">İşletmenizin hedeflerini uygun ekip arkdaşlarınızla 12'den vurun!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-xs-12 col-sm-12 register_form mb-5">
            <div class="row text-start mb-5 mt-3">
                <h1 class="form_title">İş Veren Kayıt</h1>
            </div>
            <div class="row">
                <form class="form-group" method="POST">
                    <div class="row">
                        <input type="text" name="user_name" id="user_name" class="form__input form-control" placeholder="Kullanıcı Adı" required>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="text" name="name" id="name" class="form__input form-control" placeholder="Adınız" required>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" name="surname" id="surname" class="form__input form-control" placeholder="Soyadınız" required>
                        </div>
                    </div>
                    <div class="row">
                        <input type="email" name="email" id="email" class="form__input form-control" placeholder="E-Posta" required>
                    </div>
                    <div class="row d-flex align-items-center">
                        <div class="col-lg-12">
                            <div class="input-icon-wrapper">
                                <i class="bi bi-telephone-plus phone-icon"></i>
                                <input type="tel" name="phone" id="phone-number-input-a" class="form__input " placeholder="(555) 555-1212" maxlength="14" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <input type="password" name="password" id="password" class="form__input form-control" placeholder="Şifre" required>
                    </div>
                    <div class="row">
                        <input type="password" name="password_confirm" id="password_confirm form-control" class="form__input" placeholder="Şifreni Doğrula" required>
                    </div>
                    
                    <div class="checkbox d-flex flex-row mb-3">
                        <input type="checkbox" name="sozlesme" id="sozlesme" class="form__check me-2">
                        <label for="sozlesme"><a href="#">Üyelik sözleşmesini</a> kabul ediyorum.</label>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <input type="submit" value="Kayıt Ol" class="btn mb-4">
                    </div>
                </form>

            </div>
            <div class="row form_soru mt-3 text-center">
                <p>Zaten üye misin?<a href="./e_login.php"> Giriş Yap</a></p>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php include '../partials/footer.php'; ?>
    <script>
        const phoneInput = document.getElementById('phone-number-input-a');

        function maskInput(input) {
            const phoneNumber = input.value.replace(/\D/g, '');

            const phoneNumberPattern = /^(\d{3})(\d{3})(\d{4})$/;

            if (phoneNumberPattern.test(phoneNumber)) {
                input.value = phoneNumber.replace(phoneNumberPattern, '($1) $2-$3');
            }
        }

        phoneInput.addEventListener('input', () => {
            maskInput(phoneInput);
        });
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
    // Veritabanı bağlantısı
    require_once '../../db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Formdan gelen verileri al
        $user_name = $_POST['user_name'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $sozlesme = isset($_POST['sozlesme']) ? 1 : 0;
        $user_type = 'employer'; // Normal kullanıcı olarak ayarlar

        // Şifreleri doğrular
        if ($password !== $password_confirm) {
            echo "<div id='error-alert' class='alert alert-danger alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>Şifreler eşleşmiyor! Lütfen kontrol edin.
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
            echo "<script>document.getElementById('password_confirm').classList.add('is-invalid');</script>";
            exit();
        }

        // Şifreyi hash'ler
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Veritabanına ekleme sorgusu
        $sql = "INSERT INTO uyeler (uye_adi, uye_soyadi, uye_tel, eposta, sifre, user_type, kullanici_adi) VALUES (:name, :surname, :phone, :email, :password, :user_type, :user_name)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':user_type', $user_type); 
        $stmt->bindParam(':user_name', $user_name); 

        // Kullanıcı adının veritabanında mevcut olup olmadığını kontrol eder
        $check_username_sql = "SELECT COUNT(*) FROM uyeler WHERE kullanici_adi = :user_name";
        $check_username_stmt = $conn->prepare($check_username_sql);
        $check_username_stmt->bindParam(':user_name', $user_name);
        $check_username_stmt->execute();
        $username_exists = $check_username_stmt->fetchColumn();

        // Eğer kullanıcı adı mevcutsa, form alanına "is-invalid" sınıfını ekler
        if ($username_exists) {
            echo "<script>document.getElementById('user_name').classList.add('is-invalid');</script>";
            exit("<div id='error-alert' class='alert alert-danger alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>Kullanıcı adı zaten mevcut. Lütfen farklı bir kullanıcı adı seçin.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>");
        }

        try {
            $stmt->execute();
            echo "<div id='success-alert' class='alert alert-success alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>Kullanıcı başarıyla kaydedildi. Şimdi giriş yapabilirsiniz.
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
            // 2 saniye bekleyip sonra yönlendirme işlemi  
            echo "<script>setTimeout(function(){ window.location.href = 'e_login.php'; }, 2000);</script>";
        } catch (PDOException $e) {
            echo "Hata: " . $e->getMessage();
        }
    }
    ?>


</body>

</html>