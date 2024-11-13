<?php 
function checkUserType($requiredType) {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != $requiredType) {
        header("Location: index.php");
        exit();
    }
}
?>