<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "travel_binh_dinh";

$conn = mysqli_connect($servername, $username, $password, $dbname,3307);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
?>