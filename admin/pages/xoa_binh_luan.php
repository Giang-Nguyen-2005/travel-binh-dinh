<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
$id = intval($_GET['id']);
mysqli_query($link, "DELETE FROM binh_luan WHERE id = $id");
header("Location: binh_luan.php");
exit;
?>
