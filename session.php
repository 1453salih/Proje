<?php
// Oturum çerez parametreleri
ini_set('session.gc_maxlifetime', 3600); // 2 saat

// Çerez parametreleri
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => '', // Varsayılan olarak mevcut domain
    'secure' => false,
    'httponly' => true, // JavaScript'in çerezlere erişimini engeller
]);

// Eğer oturum zaten başlamışsa yeniden başlatmama
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Oturum Açılmadıysa Açması için Login'e Yönlendirme
if (!isset($_SESSION['uye_id'])) {
    // Kullanıcı giriş yapmamış, login sayfasına yönlendirir
    header("Location: login.php");
    exit();
}

// Oturum süresi dolduysa oturumu sonlandırır ve giriş sayfasına yönlendirir
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
    session_unset();
    session_destroy();
    header("Location: view/pages/login.php");
    exit();
}

// Son etkinlik zamanını günceller
$_SESSION['LAST_ACTIVITY'] = time();

// CSRF token oluşturma
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


?>