<?php
include '../includes/db.php'; 

// Đếm bài viết
$sql_bv = "SELECT COUNT(*) AS tong_bai_viet FROM bai_viet";
$res_bv = mysqli_query($conn, $sql_bv);
$row_bv = mysqli_fetch_assoc($res_bv);

// Đếm bình luận
$sql_bl = "SELECT COUNT(*) AS tong_binh_luan FROM binh_luan";
$res_bl = mysqli_query($conn, $sql_bl);
$row_bl = mysqli_fetch_assoc($res_bl);

// Đếm người dùng role = 'khach'
$sql_nd = "SELECT COUNT(*) AS tong_user FROM user WHERE role = 'khach'";
$res_nd = mysqli_query($conn, $sql_nd);
$row_nd = mysqli_fetch_assoc($res_nd);
?>

<h1>Trang chủ quản trị</h1>
<p>Chào mừng bạn đến với hệ thống quản trị nội dung của website <strong>Khám phá Bình Định</strong>.</p>
<p>Sử dụng menu bên trái để thực hiện các chức năng như quản lý bài viết, bình luận, và nhiều hơn nữa.</p>

<div class="dashboard-stats">
  <div class="stat-box">
    <h3><?= $row_bv['tong_bai_viet'] ?></h3>
    <p>Bài viết</p>
  </div>
  <div class="stat-box">
    <h3><?= $row_bl['tong_binh_luan'] ?></h3>
    <p>Bình luận</p>
  </div>
  <div class="stat-box">
    <h3><?= $row_nd['tong_user'] ?></h3>
    <p>Người dùng</p>
  </div>
</div>

