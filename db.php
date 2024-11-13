<?php
$servername = "localhost";
$username = "root";
$password = "1453";
$dbname = "jobfind";
$port = "3306"; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;port=$port", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
    die();
}

?>
