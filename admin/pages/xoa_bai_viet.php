<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    mysqli_query($link, "DELETE FROM bai_viet WHERE id = $id");
}
header("Location: quan_ly_bai_viet.php");
exit;
