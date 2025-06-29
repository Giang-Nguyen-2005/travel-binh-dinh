<?php
session_start();
// Xoá thông tin đăng nhập của người dùng
unset($_SESSION['user']);

// Lấy đường dẫn trang trước đó (nếu có), nếu không thì về trang chủ
if (isset($_SERVER['HTTP_REFERER'])) {
    $quay_lai = $_SERVER['HTTP_REFERER']; // Trang trước
} else {
    $quay_lai = 'index.php'; // Nếu không có thì về trang chủ
}

// Chuyển hướng người dùng về trang trước (hoặc trang chủ)
header("Location: $quay_lai");

exit(); // Dừng chương trình ngay sau khi chuyển hướng
?>
