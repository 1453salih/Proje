<!-- SideNav -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Sidebar</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <!-- Sidebar content -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="#">Anasayfa</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../pages/search.php">İş Ara</a>
      </li>
      <li class="nav-item">
        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#collapseAdayGirisMobile" aria-expanded="false">
          Aday Giriş
        </button>
        <div class="collapse" id="collapseAdayGirisMobile">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="../pages/login.php" class="link-dark rounded">Giriş Yap</a></li>
            <li><a href="../pages/register.php" class="link-dark rounded">Üye Ol</a></li>
            <li><a href="#" class="link-dark rounded">Something else here</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <button class="btn btn-toggle align-items-center rounded collapsed" data-bs-toggle="collapse" data-bs-target="#collapseIsverenGirisMobile" aria-expanded="false">
          İşveren Giriş
        </button>
        <div class="collapse" id="collapseIsverenGirisMobile">
          <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
            <li><a href="#" class="link-dark rounded">Action</a></li>
            <li><a href="#" class="link-dark rounded">Another action</a></li>
            <li><a href="#" class="link-dark rounded">Something else here</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</div>

<script>
  var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasSidebar'));
  
  // İlgili buton veya link'e tıklandığında yan menüyü açar
  document.querySelector('.navbar-toggler').addEventListener('click', function () {
    offcanvas.show();
  });
</script>