<?php include '../../session.php';
include '../../Router/auth.php';
checkUserType('employer'); // erişim tipi
?>

<!DOCTYPE html>
<html lang="en">
<?php
$page_title = "İlan Oluştur";
$page_css = "../css/job_advert.css";
include '../partials/header.php';
?>
<script src="https://cdn.tiny.cloud/1/80lola0mmhpcvcfw5icfif6fyghaxzj1l3qve3i3hmwt6xab/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<body>

    <header id="header">
        <?php include '../partials/navbar.php'; ?>
    </header>
    <?php $breadcrumbs = [
        ['title' => 'İlan Oluştur']
    ];
    include '../partials/breadcrumb.php'; ?>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="ilan_basligi">İlan Başlığı:</label><br>
            <input type="text" id="ilan_basligi" name="ilan_basligi" class="input-field"><br>
            <label for="ilan_icerik">İlan İçeriği:</label><br>
            <textarea id="ilan_icerik" name="ilan_icerik" class="input-field"></textarea><br>
            <!-- Dosya Yükleme Alanı -->
            <label for="ilan_gorseli">İlan Görseli:</label><br>
            <input type="file" id="ilan_gorseli" name="ilan_gorseli" class="input-field"><br>
            <div class="d-flex row align-items-start">
                <?php
                $json_data = file_get_contents('../../turkiye_sehirler/il-ilce.json');
                $il_ilce_data = json_decode($json_data, true);
                ?>

                <div class="col-lg-6">
                    <label for="ilan_gorseli" class="mb-2">Şirket Konumu:</label><br>
                    <select class="js-example-basic-single js-states form-control select2-hidden-accessible" tabindex="-1" aria-hidden="true" style="width: 100%" name="konum" data-placeholder="Seçiniz..">
                        <?php
                        // İl ve ilçe verileri döngü ile eklenir
                        foreach ($il_ilce_data['data'] as $il) {
                            echo '<optgroup label="' . $il['il_adi'] . '">';
                            foreach ($il['ilceler'] as $ilce) {
                                echo '<option value="' . $ilce['ilce_adi'] . '">' . $ilce['ilce_adi'] . '</option>';
                            }
                            echo '</optgroup>';
                        }
                        ?>
                    </select>
                </div>

                <div class="search-frame col-lg-6">
                    <label for="ilan_icerik" class="mb-2">Aday Konumları:</label><br>
                    <input id="myInput" class="locations" type="text" name="myCountry" placeholder="İl-İlçe Ara">
                    <div id="selectedItems" class="autocomplete-selected"></div>
                    <input type="hidden" id="selectedLocations" name="selectedLocations">
                </div>

            </div>
            <div class="d-flex row align-items-start my-4">
                <div class="search-frame col-lg-6">
                    <label for=""> Çalışma Şekli:</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="radio" name="calisma_sekli" id="tam_zamanli" value="Tam Zamanlı">
                        <label class="form-check-label" for="tam_zamanli">
                            Tam Zamanlı
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input " type="radio" name="calisma_sekli" id="yari_zamanli" value="Yarı Zamanlı">
                        <label class="form-check-label" for="yari_zamanli">
                            Yarı Zamanlı
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="calisma_sekli" id="sozlesmeli" value="Sözleşmeli">
                        <label class="form-check-label" for="sozlesmeli">
                            Sözleşmeli
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="calisma_sekli" id="serbest_calisma" value="Serbest Çalışma">
                        <label class="form-check-label" for="serbest_calisma">
                            Serbest Çalışma
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="calisma_sekli" id="staj" value="Staj">
                        <label class="form-check-label" for="staj">
                            Staj
                        </label>
                    </div>
                </div>
                <div class="search-frame col-lg-6">
                    <label for=""> Çalışma Tercihi:</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="radio" name="calisma_tercihi" id="is_yerinde" value="İş Yerinde">
                        <label class="form-check-label" for="is_yerinde">
                            İş Yerinde
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="calisma_tercihi" id="uzaktan" value="Uzaktan">
                        <label class="form-check-label" for="uzaktan">
                            Uzaktan
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="calisma_tercihi" id="hibrit" value="Hibrit">
                        <label class="form-check-label" for="hibrit">
                            Hibrit
                        </label>
                    </div>
                </div>
            </div>
            <?php include 'meslek_tercihleri.php'; ?>
            <input type="submit" value="İlanı Gönder" id="submit-button">
        </form>
    </div>

    <?php include '../partials/footer.php'; ?>
    <?php
    include '../../db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_SESSION['uye_id'])) {
            echo "<div class='alert alert-danger'>Oturum açma bilgileri bulunamadı. Lütfen tekrar oturum açın.</div>";
            exit;
        }

        $ilan_basligi = $_POST['ilan_basligi'];
        $ilan_icerik = $_POST['ilan_icerik'];
        $konum = $_POST['myCountry'];
        $calisma_sekli = isset($_POST['calisma_sekli']) ? $_POST['calisma_sekli'] : null;
        $calisma_tercihi = isset($_POST['calisma_tercihi']) ? $_POST['calisma_tercihi'] : null;
        $konum = $_POST['konum'];

        if (empty($calisma_sekli) || empty($calisma_tercihi)) {
            echo "<div class='alert alert-danger'>Çalışma şekli ve çalışma tercihi alanları doldurulmalıdır.</div>";
            exit;
        }

        $uye_id = $_SESSION['uye_id'];

        // Dosya yükleme işlemi
        $target_dir = "../../gorsel_data/";
        $target_file = $target_dir . basename($_FILES["ilan_gorseli"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Dosya bir görüntü mü?
        $check = getimagesize($_FILES["ilan_gorseli"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "<div class='alert alert-danger'>Dosya bir görüntü değil.</div>";
            $uploadOk = 0;
        }

        // Dosya zaten mevcut mu?
        if (file_exists($target_file)) {
            echo "<div class='alert alert-danger'>Üzgünüz, dosya zaten mevcut.</div>";
            $uploadOk = 0;
        }

        // Dosya boyutunu kontrol eder
        if ($_FILES["ilan_gorseli"]["size"] > 500000) { // 500KB
            echo "<div class='alert alert-danger'>Üzgünüz, dosyanız çok büyük.</div>";
            $uploadOk = 0;
        }

        // Sadece belirli dosya türlerine izin verir
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "<div class='alert alert-danger'>Üzgünüz, sadece JPG, JPEG, PNG ve GIF dosyalarına izin verilir.</div>";
            $uploadOk = 0;
        }

        // Dosya yükleme hatalarını kontrol eder
        if ($uploadOk == 0) {
            echo "<div class='alert alert-danger'>Üzgünüz, dosyanız yüklenmedi.</div>";
        } else {
            if (move_uploaded_file($_FILES["ilan_gorseli"]["tmp_name"], $target_file)) {
                $gorsel_yolu = $target_file;

                // İlanı veritabanına ekler
                $query = "INSERT INTO ilanlar (uye_id, ilan_baslik, ilan_aciklama, calisma_sekli, calisma_tercihi, yayin_tarihi, ilan_gorseli, konum) 
                     VALUES (:uye_id, :ilan_basligi, :ilan_icerik, :calisma_sekli, :calisma_tercihi, NOW(), :gorsel_yolu, :konum)";
                $stmt = $conn->prepare($query);

                $stmt->bindParam(':uye_id', $uye_id);
                $stmt->bindParam(':ilan_basligi', $ilan_basligi);
                $stmt->bindParam(':ilan_icerik', $ilan_icerik);
                $stmt->bindParam(':calisma_sekli', $calisma_sekli);
                $stmt->bindParam(':calisma_tercihi', $calisma_tercihi);
                $stmt->bindParam(':gorsel_yolu', $gorsel_yolu);
                $stmt->bindParam(':konum', $konum);

                if ($stmt->execute()) {
                    // İlan başarıyla oluşturulduktan sonra son eklenen ilan_id'yi alır
                    $ilan_id = $conn->lastInsertId();

                    // Seçilen konumları alır
                    $selectedLocations = json_decode($_POST['selectedLocations']);

                    // Her bir konum için ilan_konumları tablosuna ekleme yapar
                    if (!empty($selectedLocations)) {
                        $konumQuery = "INSERT INTO ilan_konumlari (ilan_id, konum) VALUES (:ilan_id, :konum)";
                        $konumStmt = $conn->prepare($konumQuery);

                        foreach ($selectedLocations as $konum) {
                            $konumStmt->bindParam(':ilan_id', $ilan_id);
                            $konumStmt->bindParam(':konum', $konum);
                            $konumStmt->execute();
                        }
                    }

                    // Meslek tercihlerini ilan_meslek tablosuna ekler
                    if (isset($_POST['meslek_secim']) && is_array($_POST['meslek_secim'])) { // is_array ile kontrol ekledim
                        $meslek_secim = $_POST['meslek_secim'];

                        // Her bir meslek için ilan_meslek tablosuna ekleme yapar
                        $meslekQuery = "INSERT INTO ilan_meslek (ilan_id, meslek_id) VALUES (:ilan_id, :meslek_id)";
                        $meslekStmt = $conn->prepare($meslekQuery);

                        foreach ($meslek_secim as $meslek_id) {
                            $meslekStmt->bindParam(':ilan_id', $ilan_id);
                            $meslekStmt->bindParam(':meslek_id', $meslek_id);
                            $meslekStmt->execute();
                        }
                    } else {
                        echo "Meslek seçimi yapılmadı veya yanlış formatta.";
                    }

                    echo "<div id='success-alert' class='alert alert-success alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>
                       <i class='bi bi-check-circle me-3'></i> İlan başarıyla oluşturuldu.
                       <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                     </div>";
                } else {
                    echo "<div id='error-alert' class='alert alert-danger alert-dismissible fade show fixed-top mt-4 ms-4 me-4' role='alert'>
                       İlan oluşturulurken bir hata oluştu.
                       <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                     </div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Üzgünüz, dosya yüklenirken bir hata oluştu.</div>";
            }
        }
    }
    ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        places = [
            "İstanbul-(Avrupa), İstanbul",
            "İstanbul-(Anadolu), İstanbul",
            "Adalar, İstanbul",
            "Arnavutköy, İstanbul",
            "Ataşehir, İstanbul",
            "Avcılar, İstanbul",
            "Bağcılar, İstanbul",
            "Bahçelievler, İstanbul",
            "Bakırköy, İstanbul",
            "Başakşehir, İstanbul",
            "Bayrampaşa, İstanbul",
            "Beşiktaş, İstanbul",
            "Beykoz, İstanbul",
            "Beylikdüzü, İstanbul",
            "Beyoğlu, İstanbul",
            "Büyükçekmece, İstanbul",
            "Çatalca, İstanbul",
            "Çekmeköy, İstanbul",
            "Esenler, İstanbul",
            "Esenyurt, İstanbul",
            "Eyüpsultan, İstanbul",
            "Fatih, İstanbul",
            "Gaziosmanpaşa, İstanbul",
            "Güngören, İstanbul",
            "Kadıköy, İstanbul",
            "Kağıthane, İstanbul",
            "Kartal, İstanbul",
            "Küçükçekmece, İstanbul",
            "Maltepe, İstanbul",
            "Pendik, İstanbul",
            "Sancaktepe, İstanbul",
            "Sarıyer, İstanbul",
            "Silivri, İstanbul",
            "Sultanbeyli, İstanbul",
            "Sultangazi, İstanbul",
            "Şile, İstanbul",
            "Şişli, İstanbul",
            "Tuzla, İstanbul",
            "Ümraniye, İstanbul",
            "Üsküdar, İstanbul",
            "Zeytinburnu, İstanbul",

            "Altındağ, Ankara",
            "Ayaş, Ankara",
            "Bala, Ankara",
            "Beypazarı, Ankara",
            "Çamlıdere, Ankara",
            "Çankaya, Ankara",
            "Çubuk, Ankara",
            "Elmadağ, Ankara",
            "Güdül, Ankara",
            "Haymana, Ankara",
            "Kalecik, Ankara",
            "Kızılcahamam, Ankara",
            "Nallıhan, Ankara",
            "Polatlı, Ankara",
            "Şereflikoçhisar, Ankara",
            "Yenimahalle, Ankara",
            "Gölbaşı, Ankara",
            "Keçiören, Ankara",
            "Mamak, Ankara",
            "Sincan, Ankara",
            "Kazan, Ankara",
            "Akyurt, Ankara",
            "Etimesgut, Ankara",
            "Evren, Ankara",
            "Pursaklar, Ankara",

            "Aliağa, İzmir",
            "Bayındır, İzmir",
            "Bergama, İzmir",
            "Bornova, İzmir",
            "Çeşme, İzmir",
            "Dikili, İzmir",
            "Foça, İzmir",
            "Karaburun, İzmir",
            "Karşıyaka, İzmir",
            "Kemalpaşa, İzmir",
            "Kınık, İzmir",
            "Kiraz, İzmir",
            "Menemen, İzmir",
            "Ödemiş, İzmir",
            "Seferihisar, İzmir",
            "Selçuk, İzmir",
            "Tire, İzmir",
            "Torbalı, İzmir",
            "Urla, İzmir",
            "Beydağ, İzmir",
            "Buca, İzmir",
            "Konak, İzmir",
            "Menderes, İzmir",
            "Balçova, İzmir",
            "Çiğli, İzmir",
            "Gaziemir, İzmir",
            "Narlıdere, İzmir",
            "Güzelbahçe, İzmir",

            "Büyükorhan, Bursa",
            "Gemlik, Bursa",
            "Gürsu, Bursa",
            "Harmancık, Bursa",
            "İnegöl, Bursa",
            "İznik, Bursa",
            "Karacabey, Bursa",
            "Keles, Bursa",
            "Kestel, Bursa",
            "Mudanya, Bursa",
            "Mustafakemalpaşa, Bursa",
            "Orhaneli, Bursa",
            "Orhangazi, Bursa",
            "Osmangazi, Bursa",
            "Nilüfer, Bursa",
            "Yenişehir, Bursa",
            "Yıldırım, Bursa",

            "Akseki, Antalya",
            "Aksu, Antalya",
            "Alanya, Antalya",
            "Demre, Antalya",
            "Döşemealtı, Antalya",
            "Elmalı, Antalya",
            "Finike, Antalya",
            "Gazipaşa, Antalya",
            "Gündoğmuş, Antalya",
            "İbradı, Antalya",
            "Kaş, Antalya",
            "Kemer, Antalya",
            "Kepez, Antalya",
            "Konyaaltı, Antalya",
            "Korkuteli, Antalya",
            "Kumluca, Antalya",
            "Manavgat, Antalya",
            "Muratpaşa, Antalya",
            "Serik, Antalya",

            "Aladağ, Adana",
            "Ceyhan, Adana",
            "Çukurova, Adana",
            "Feke, Adana",
            "İmamoğlu, Adana",
            "Karaisalı, Adana",
            "Karataş, Adana",
            "Kozan, Adana",
            "Pozantı, Adana",
            "Saimbeyli, Adana",
            "Sarıçam, Adana",
            "Seyhan, Adana",
            "Tufanbeyli, Adana",
            "Yumurtalık, Adana",
            "Yüreğir, Adana"
        ];


        function autocomplete(inp, arr) {
            let currentFocus;
            inp.addEventListener("input", function() {
                let a, b, i, val = this.value;
                closeAllLists();
                if (!val) {
                    return false;
                }
                currentFocus = -1;
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                this.parentNode.appendChild(a);
                for (i = 0; i < arr.length; i++) {
                    if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                        b = document.createElement("DIV");
                        b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                        b.innerHTML += arr[i].substr(val.length);
                        b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                        b.addEventListener("click", function(e) {
                            let selectedValue = this.getElementsByTagName("input")[0].value;
                            updateSelectedItems(selectedValue);
                            inp.value = "";
                            closeAllLists();
                        });
                        a.appendChild(b);
                    }
                }
            });

            inp.addEventListener("keydown", function(e) {
                let x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) {
                    currentFocus++;
                    addActive(x);
                } else if (e.keyCode == 38) {
                    currentFocus--;
                    addActive(x);
                } else if (e.keyCode == 13) {
                    e.preventDefault();
                    if (currentFocus > -1) {
                        if (x) x[currentFocus].click();
                    }
                }
            });

            function addActive(x) {
                if (!x) return false;
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (x.length - 1);
                x[currentFocus].classList.add("autocomplete-active");
            }

            function removeActive(x) {
                for (let i = 0; i < x.length; i++) {
                    x[i].classList.remove("autocomplete-active");
                }
            }

            function closeAllLists(elmnt) {
                let x = document.getElementsByClassName("autocomplete-items");
                for (let i = 0; i < x.length; i++) {
                    if (elmnt != x[i] && elmnt != inp) {
                        x[i].parentNode.removeChild(x[i]);
                    }
                }
            }

            document.addEventListener("click", function(e) {
                closeAllLists(e.target);
            });
        }

        function updateSelectedItems(value) {
            let selectedItemsDiv = document.getElementById('selectedItems');

            // Seçilen konumların olduğu div içinde bu konum zaten var mı kontrol et
            let existingItems = selectedItemsDiv.getElementsByClassName('autocomplete-selected-item');
            for (let i = 0; i < existingItems.length; i++) {
                if (existingItems[i].getAttribute('data-value') === value) {
                    return; // Bu konum zaten seçilmiş, eklemeyi durdur
                }
            }

            // Yeni seçilen konumu ekleyin
            let selectedItem = document.createElement("div");
            selectedItem.setAttribute("class", "autocomplete-selected-item");
            selectedItem.setAttribute("data-value", value);
            selectedItem.innerText = value;

            let removeBtn = document.createElement("span");
            removeBtn.setAttribute("class", "remove-btn");
            removeBtn.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i>';
            removeBtn.addEventListener("click", function() {
                selectedItemsDiv.removeChild(selectedItem);
                updateHiddenInput();
            });

            selectedItem.appendChild(removeBtn);
            selectedItemsDiv.appendChild(selectedItem);
            updateHiddenInput();
        }

        function updateHiddenInput() {
            let selectedItemsDiv = document.getElementById('selectedItems');
            let items = selectedItemsDiv.getElementsByClassName('autocomplete-selected-item');
            let selectedValues = [];
            for (let i = 0; i < items.length; i++) {
                selectedValues.push(items[i].getAttribute('data-value'));
            }
            document.getElementById('selectedLocations').value = JSON.stringify(selectedValues);
        }


        document.addEventListener("DOMContentLoaded", function() {
            autocomplete(document.getElementById("myInput"), places);
        });

        // !---------------------Text Editör------------------ 
        tinymce.init({
            selector: 'textarea',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
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
        // !------------------Şirket Konum Select------------------------
        $(document).ready(function() {
            $(".js-example-basic-single").select2({
                placeholder: "Seçiniz...",
                allowClear: true,
            });
        });
        $(document).ready(function() {
            $(".js-example-basic-multiple").select2({
                placeholder: "Seçiniz...",
                allowClear: true,
            });
        });
        // Varsayılan seçimi kaldırır
        $(".js-example-basic-single").val(null).trigger('change');
        $(".js-example-basic-multiple").val(null).trigger('change');
    </script>
</body>


</html>