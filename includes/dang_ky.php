<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ.";
    } else {
        $check = mysqli_query($link, "SELECT * FROM dang_ky_email WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email đã được đăng ký.";
        } else {
            $insert = mysqli_query($link, "INSERT INTO dang_ky_email (email) VALUES ('$email')");
            $success = $insert ? " Đăng ký thành công!" : "Lỗi khi lưu: " . mysqli_error($link);
        }
    }
}
?>

<section class="subscribe-section">
  <img src="../assets/img/anh-dep-binh-dinh-36.jpg" class="background-img">

  <div class="subscribe-content">
    <h2>Đăng ký để không bỏ lỡ điều thú vị</h2>
    <p>Nhận thông tin về sự kiện sắp tới, mẹo du lịch và những chia sẻ thú vị từ Bình Định.</p>

    <?php if ($error): ?>
      <p style="color:red;"><?php echo $error; ?></p>
    <?php elseif ($success): ?>
      <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form class="subscribe-form" method="POST">
      <input type="email" name="email" placeholder="email@gmail.com" required />
      <button type="submit">ĐĂNG KÝ</button>
    </form>
  </div>
</section>
