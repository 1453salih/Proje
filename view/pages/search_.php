<?php include '../../session.php'; 

// include '../../Router/auth.php';
// checkUserType('normal_user'); // erişim tipi

?>

<?php
include '../../db.php';

// Sayfalama işlemleri
$ilan_per_page = isset($_GET['ilan_per_page']) ? (int)$_GET['ilan_per_page'] : 10; // Sayfa başına ilan sayısı
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$start = ($page - 1) * $ilan_per_page; 

// Veritabanından ilanları çek
$sql = "SELECT * FROM ilanlar LIMIT :start, :ilan_per_page";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':ilan_per_page', $ilan_per_page, PDO::PARAM_INT);
$stmt->execute();
$ilanlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Toplam ilan sayısını al
$total_ilan_sql = "SELECT COUNT(*) as count FROM ilanlar";
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
        <div class="col-2 sidebar me-5">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Anahtar Kelime
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="search-frame">
                                <input id="tag_input" type="search" placeholder="Ara...">
                                <button id="search_button_tag" type="submit"><span class="bi bi-search"></span></i></button>
                            </div>
                            <div id="tag_container"></div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Şehir
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="search-frame">
                                <input id="myInput" type="search" name="myCountry" placeholder="İl-İlçe Ara">
                                <button id="search_button" type="button"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Çalışma Tercihi
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    İş Yerinde
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Uzaktan
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Hibrit
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Pozisyon
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="search-frame">
                                <input id="searchInput1" type="search" placeholder="Ara...">
                                <button id="search_button" type="submit"><i class="bi bi-search"></i></button>
                            </div>
                            <div class="checkbox-container">
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault1">
                                    <label class="form-check-label" for="flexCheckDefault1">
                                        Default checkbox
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault2">
                                    <label class="form-check-label" for="flexCheckDefault2">
                                        Checked checkbox
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault3">
                                    <label class="form-check-label" for="flexCheckDefault3">
                                        Checked checkbox
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault4">
                                    <label class="form-check-label" for="flexCheckDefault4">
                                        Checked checkbox
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Firma Sektörü
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="search-frame">
                                <input id="searchInput2" type="search" placeholder="Ara...">
                                <button id="search_button" type="submit"><i class="bi bi-search"></i></button>
                            </div>
                            <div class="checkbox-container">
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault1">
                                    <label class="form-check-label" for="flexCheckDefault1">
                                        Bilgisayar Mühendisliği
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault2">
                                    <label class="form-check-label" for="flexCheckDefault2">
                                        Bilgi İşlem
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault3">
                                    <label class="form-check-label" for="flexCheckDefault3">
                                        Bilgi Teknolojileri
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault4">
                                    <label class="form-check-label" for="flexCheckDefault4">
                                        Bilgisayar Donanım
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault5">
                                    <label class="form-check-label" for="flexCheckDefault5">
                                        Elektirik Elektronik
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault6">
                                    <label class="form-check-label" for="flexCheckDefault6">
                                        Makine Mühendisliği
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault6">
                                    <label class="form-check-label" for="flexCheckDefault6">
                                        Makine Mühendisliği
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault6">
                                    <label class="form-check-label" for="flexCheckDefault6">
                                        Makine Mühendisliği
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input form-check-input-square" type="checkbox" value="" id="flexCheckDefault6">
                                    <label class="form-check-label" for="flexCheckDefault6">
                                        Makine Mühendisliği
                                    </label>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            İlan Tarihi
                        </button>
                    </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                    
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            Çalışma Şekli
                        </button>
                    </h2>
                    <div id="collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main_frame container mt-3">

            <div class="main border ">
                <div class="main-top d-flex justify-content-between mb-5">
                    <div class="title">
                        <h2>İş İlanları</h2>
                    </div>
                    <div class="main-top-right d-flex">
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
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Sırala
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item active" href="#">Güncellenme Tarihine Göre</a></li>
                                <li><a class="dropdown-item" href="#">Yayınlanma Tarihine Göre</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if (count($ilanlar) > 0) : ?>
                    <?php foreach ($ilanlar as $ilan) : ?>
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
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="no-results">
                        <p>Aramanıza uygun ilan bulunamadı!</p>
                    </div>
                <?php endif; ?>

                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&ilan_per_page=<?php echo $ilan_per_page; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</body>
<script>
    function goToJobDetails(id) {
        window.location.href = `job_details.php?id=${id}`;
    }
    const places = [
        "İskilip, Çorum",
        "İspir, Erzurum",
        "İskenderun, Hatay",
        "İslahiye, Gaziantep",
        "Isparta (Tüm İlçeler)"
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
                    b.addEventListener("click", function() {
                        inp.value = this.getElementsByTagName("input")[0].value;
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
    document.getElementById('searchInput1').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let checkboxes = document.querySelectorAll('.form-check');

        checkboxes.forEach(function(checkbox) {
            let label = checkbox.querySelector('.form-check-label').innerText.toLowerCase();
            if (label.includes(filter)) {
                checkbox.style.display = "";
            } else {
                checkbox.style.display = "none";
            }
        });
    });

    document.getElementById('searchInput2').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let checkboxes = document.querySelectorAll('.form-check');

        checkboxes.forEach(function(checkbox) {
            let label = checkbox.querySelector('.form-check-label').innerText.toLowerCase();
            if (label.includes(filter)) {
                checkbox.style.display = "";
            } else {
                checkbox.style.display = "none";
            }
        });
    });

    autocomplete(document.getElementById("myInput"), places);

    // Etiket ekleme işlevi
    function addTag() {
        var input = document.getElementById("tag_input");
        var tag = input.value.trim();

        if (tag !== "") {
            var tagContainer = document.getElementById("tag_container");
            var tagElement = document.createElement("div");
            tagElement.className = "tag";
            tagElement.innerHTML = tag + '<button class="close_tag" onclick="removeTag(this)">x</button>';
            tagContainer.appendChild(tagElement);
            input.value = "";
        }
    }

    // Etiket kaldırma işlevi
    function removeTag(tagElement) {
        tagElement.parentNode.remove();
    }

    // Ekleme butonuna tıklama olayı
    document.getElementById("search_button_tag").addEventListener("click", addTag);

    // Icona tıklama olayı
    document.querySelector('#search_button_tag .bi-search').addEventListener("click", addTag);

    // Enter tuşuna basılınca da etiket ekleme
    document.getElementById("tag_input").addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            addTag();
        }
    });
</script>
<?php include '../partials/footer.php'; ?>

</html>