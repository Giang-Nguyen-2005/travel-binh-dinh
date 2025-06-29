<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "travel_binh_dinh";
$port = 3307;

$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}
?>
