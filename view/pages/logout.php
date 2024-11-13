<?php
session_start();

// Kullanıcı tipi kontrolü
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;

// Session'ı sonlandır
session_unset();
session_destroy();

// Kullanıcı tipine göre yönlendirme
if ($user_type == 'normal') {
    header("Location: ../pages/login.php");
} elseif ($user_type == 'employer') {
    header("Location: ../pages/e_login.php");
} else {
    // Varsayılan yönlendirme
    header("Location: ../pages/index.php");
}
exit();
?>