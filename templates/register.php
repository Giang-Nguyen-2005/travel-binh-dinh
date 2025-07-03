<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password_raw = trim($_POST['password']);
    $password = password_hash($password_raw, PASSWORD_DEFAULT);
    $ho_ten = trim($_POST['ho_ten']);
    $email = trim($_POST['email']);

    // Kiểm tra trùng tên đăng nhập hoặc email
    $check = mysqli_query($link, "SELECT * FROM user WHERE username = '$username' OR email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Tên đăng nhập hoặc email đã tồn tại.";
    } else {

        $sql = "INSERT INTO user (username, password, ho_ten, email, role) 
        VALUES ('$username', '$password', '$ho_ten', '$email', 'khach')";
        if (mysqli_query($link, $sql)) {
            $success = "Đăng ký thành công! Bạn có thể đăng nhập.";
        } else {
            $error = "Lỗi khi đăng ký: " . mysqli_error($link);
        }
    }
}
?>
<?php include('../includes/header.php'); ?>
<link rel="stylesheet" href="../assets/css/style.css">
<div class="auth-form">
    <h2>Đăng ký tài khoản</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="ho_ten">Họ và tên:</label>
        <input type="text" name="ho_ten" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="username">Tên đăng nhập:</label>
        <input type="text" name="username" required>

        <label for="password">Mật khẩu:</label>
        <input type="password" name="password" required>

        <button type="submit">Đăng ký</button>
    </form>

    <div class="link">
        Đã có tài khoản? <a href="login.php">Đăng nhập</a>
    </div>
</div>
<?php include('../includes/footer.php'); ?>