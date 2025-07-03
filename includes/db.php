<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
if (!$link) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
mysqli_set_charset($link, "utf8mb4");
?>