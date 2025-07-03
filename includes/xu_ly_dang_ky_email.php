<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ.";
    } else {
        $stmt = mysqli_prepare($link, "SELECT * FROM dang_ky_email WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error = "Email đã được đăng ký.";
        } else {
            // Thêm email vào CSDL
            $stmt_insert = mysqli_prepare($link, "INSERT INTO dang_ky_email (email) VALUES (?)");
            mysqli_stmt_bind_param($stmt_insert, "s", $email);
            if (mysqli_stmt_execute($stmt_insert)) {
                $success = "Đăng ký thành công!";
            } else {
                $error = "Lỗi khi lưu: " . mysqli_error($link);
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Trả về kết quả HTML
    echo '<div id="responseMessage">';
    if ($error) echo '<p class="glow error">' . $error . '</p>';
    elseif ($success) echo '<p class="glow success">' . $success . '</p>';
    echo '</div>';
}
