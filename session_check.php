<?php
session_start();
$oturum_suresi = 2 * 60 * 60;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $oturum_suresi)) {
    echo json_encode(['session_active' => false]);
} else {
    $_SESSION['last_activity'] = time();
    echo json_encode(['session_active' => true]);
}
?>
