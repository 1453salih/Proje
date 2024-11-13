<nav class="navbar navbar-expand-lg bg-body-tertiary main-header-area sticky" id="sticky-header">
  <div class="container">
    <a class="navbar-brand" href="#">
      <img src="../images/logo_4.png" alt="Logo" style="width:150px;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars" style="color:white;"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link normal-nav me-1 active" aria-current="page" href="../pages/index.php">Anasayfa</a>
        </li>
        <li class="nav-item">
          <a class="nav-link normal-nav me-1" href="../pages/search.php">İş Ara</a>
        </li>
        <?php if (isset($_SESSION['username'])) : ?>
          <?php
          if ($_SESSION['user_type'] == 'employer') {
              $username = htmlspecialchars($_SESSION['username']);
              $photo_path = isset($_SESSION['photo_path']) ? htmlspecialchars($_SESSION['photo_path']) : "../images/default_user.png";
          ?>
            <li class="nav-item dropdown user-dropdown">
              <a class="nav-link dropdown-toggle me-1 d-flex align-items-center username-container" href="#" id="dropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo $photo_path; ?>" alt="Profil Fotoğrafı" class="profile-img">
                <span class="username"><?php echo $username; ?></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-dark user-dropdown" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item" onclick="goToProfilDetails('<?php echo $_SESSION['uye_id']; ?>')"><i class="bi bi-person" style="margin-right: 8px; vertical-align:0px; color: #004aad;"></i>Profil</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="../pages/ilanlarim.php"><i class="bi bi-megaphone" style="margin-right: 8px; vertical-align:0px; color:yellow;"></i>İlanlarım</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="../pages/logout.php"><i class="bi bi-x-circle" style="margin-right: 8px; vertical-align:0px; color:red;"></i>Çıkış Yap</a></li>
              </ul>
            </li>
            <a href="../pages/job_advert.php" class="btn btn-success ms-1" id="ilan_ver_button">İlan Ver</a>
          <?php
          } else {
              $username = htmlspecialchars($_SESSION['username']);
              $photo_path = isset($_SESSION['photo_path']) ? htmlspecialchars($_SESSION['photo_path']) : "../images/default_user.png";
          ?>
            <li class="nav-item dropdown user-dropdown">
              <a class="nav-link dropdown-toggle me-1 d-flex align-items-center username-container" href="#" id="dropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo $photo_path; ?>" alt="Profil Fotoğrafı" class="profile-img">
                <span class="username"><?php echo $username; ?></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-dark user-dropdown" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item" onclick="goToProfilDetails('<?php echo $_SESSION['uye_id']; ?>')"><i class="bi bi-person" style="margin-right: 8px; vertical-align:0px; color: #004aad;"></i>Profil</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="../pages/basvurularim.php"><img src="../images/resume (2).png" style="width:16px; margin-right: 8px; vertical-align:0px;"></img>Başvurularım</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="../pages/logout.php"><i class="bi bi-x-circle" style="margin-right: 8px; vertical-align:0px; color:red;"></i>Çıkış Yap</a></li>
              </ul>
            </li>
          <?php
          }
          ?>
        <?php else : ?>
          <li class="nav-item dropdown">
            <a class="nav-link normal-nav dropdown-toggle me-1" href="#" id="dropdownAdayGiris" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Aday
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownAdayGiris">
              <li><a class="dropdown-item" href="../pages/login.php">Giriş Yap</a></li>
              <li><a class="dropdown-item" href="../pages/register.php">Üye Ol</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link normal-nav dropdown-toggle" href="#" id="dropdownIsverenGiris" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              İşveren
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownIsverenGiris">
              <li><a class="dropdown-item" href="../pages/e_login.php">Giriş Yap</a></li>
              <li><a class="dropdown-item" href="../pages/e_register.php">Üye Ol</a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menü</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="../pages/index.php">Anasayfa</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../pages/search.php">İş Ara</a>
      </li>
      <?php if (isset($_SESSION['username'])) : ?>
        <?php
        if ($_SESSION['user_type'] == 'employer') {
            $username = htmlspecialchars($_SESSION['username']);
            $photo_path = isset($_SESSION['photo_path']) ? htmlspecialchars($_SESSION['photo_path']) : "../images/default_user.png";
        ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle me-1 d-flex align-items-center" href="#" id="dropdownUserMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="<?php echo $photo_path; ?>" alt="Profil Fotoğrafı" class="profile-img">
              <span class="username"><?php echo $username; ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark user-dropdown" aria-labelledby="dropdownUserMobile">
              <li><a class="dropdown-item" href="../pages/e_profil_details.php"><i class="bi bi-person" style="margin-right: 8px; vertical-align:0px; color: #004aad;"></i>Profil</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../pages/ilanlarim.php"><i class="bi bi-megaphone" style="margin-right: 8px; vertical-align:0px; color:yellow;"></i>İlanlarım</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../pages/logout.php"><i class="bi bi-x-circle" style="margin-right: 8px; vertical-align:0px; color:red;"></i>Çıkış Yap</a></li>
            </ul>
          </li>
        <?php
        } else {
            $username = htmlspecialchars($_SESSION['username']);
            $photo_path = isset($_SESSION['photo_path']) ? htmlspecialchars($_SESSION['photo_path']) : "../images/default_user.png";
        ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle me-1 d-flex align-items-center" href="#" id="dropdownUserMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="<?php echo $photo_path; ?>" alt="Profil Fotoğrafı" class="profile-img">
              <span class="username"><?php echo $username; ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark user-dropdown" aria-labelledby="dropdownUserMobile">
              <li><a class="dropdown-item" href="../pages/profil_details.php"><i class="bi bi-person" style="margin-right: 8px; vertical-align:0px; color: #004aad;"></i>Profil</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../pages/basvurularim.php"><img src="../images/resume (2).png" style="width:16px; margin-right: 8px; vertical-align:0px;"></img>Başvurularım</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../pages/logout.php"><i class="bi bi-x-circle" style="margin-right: 8px; vertical-align:0px; color:red;"></i>Çıkış Yap</a></li>
            </ul>
          </li>
        <?php
        }
        ?>
      <?php else : ?>
        <li class="nav-item">
          <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAdayGirisMobile" aria-expanded="false">
            Aday Giriş
          </button>
          <div class="collapse" id="collapseAdayGirisMobile">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
              <li><a href="../pages/login.php" class="link-dark rounded">Giriş Yap</a></li>
              <li><a href="../pages/register.php" class="link-dark rounded">Üye Ol</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#collapseIsverenGirisMobile" aria-expanded="false">
            İşveren Giriş
          </button>
          <div class="collapse" id="collapseIsverenGirisMobile">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
              <li><a href="../pages/e_login.php" class="link-dark rounded">Giriş Yap</a></li>
              <li><a href="../pages/e_register.php" class="link-dark rounded">Üye Ol</a></li>
            </ul>
          </div>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
  $(document).ready(function() {
    $('.nav-item').click(function() {
      $('.dropdown').css('background-color', 'dimgrey;');
    });
  });
  function goToProfilDetails(id) {
            window.location.href = `uye_profil.php?id=${id}`;
        }
</script>
