<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Lấy thông tin người dùng theo username
    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($link, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        // So sánh mật khẩu nhập vào với mật khẩu đã mã hóa
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $username;
            header('Location: index.php');
            exit;
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
        }
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
}

?>

<?php include('../includes/header.php'); ?>
<link rel="stylesheet" href="../assets/css/style.css">

<div class="auth-form">
    <h2>Đăng nhập</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Mật khẩu:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Đăng nhập</button>
    </form>

    <div class="link">
        Chưa có tài khoản? <a href="register.php">Đăng ký</a>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
