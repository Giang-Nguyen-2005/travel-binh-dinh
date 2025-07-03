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

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['tieu_de'], $_POST['noi_dung'])) {
    $tieu_de = mysqli_real_escape_string($link, $_POST['tieu_de']);
    $noi_dung = mysqli_real_escape_string($link, $_POST['noi_dung']);
    $mo_ta = mysqli_real_escape_string($link, $_POST['mo_ta']);
    $hinh_anh = '';

    // Tạo thư mục nếu chưa có
    $upload_dir = "../assets/img/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] === 0) {
        $file_name = basename($_FILES['hinh_anh']['name']);
        $target_path = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_path)) {
            $hinh_anh = $file_name;
        }
    }

    $sql = "INSERT INTO bai_viet (tieu_de, noi_dung, mo_ta, hinh_anh, danh_muc_id, id_user, trang_thai, ngay_tao) 
            VALUES ('$tieu_de', '$noi_dung', '$mo_ta', '$hinh_anh', 6, $id_user, 'cho_duyet', NOW())";

    $message = mysqli_query($link, $sql)
        ? "✅ Bài viết đã được gửi chờ admin duyệt!"
        : "❌ Có lỗi xảy ra khi đăng bài.";
}
?>

<?php include '../includes/header.php'; ?>

<div class="form-wrapper">
    <h2>📝 Tạo bài viết chia sẻ</h2>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="tieu_de">Tiêu đề bài viết *</label>
        <input type="text" name="tieu_de" id="tieu_de" required>

        <label for="mo_ta">Mô tả ngắn</label>
        <textarea name="mo_ta" id="mo_ta" rows="3"></textarea>

        <label for="noi_dung">Nội dung *</label>
        <textarea name="noi_dung" id="noi_dung" rows="8" required></textarea>

        <label for="hinh_anh">Ảnh đại diện</label>
        <input type="file" name="hinh_anh" id="hinh_anh">

        <button type="submit">📤 Gửi bài viết</button>
    </form>

    <div style="margin-top: 15px; text-align: center;">
        <a href="profile.php" style="text-decoration: none;"><button>← Quay lại hồ sơ</button></a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('noi_dung');
</script>
