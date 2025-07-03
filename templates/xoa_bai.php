<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
mysqli_set_charset($link, "utf8");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
$sql_user = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
$result_user = mysqli_query($link, $sql_user);
$user = mysqli_fetch_assoc($result_user);
$id_user = $user['id'];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Kiểm tra quyền sở hữu bài viết
    $check = mysqli_query($link, "SELECT * FROM bai_viet WHERE id = $id AND id_user = $id_user");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($link, "DELETE FROM bai_viet WHERE id = $id");
    }
}

header("Location: profile.php");
exit();
