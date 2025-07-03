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
$user = mysqli_fetch_assoc(mysqli_query($link, $sql_user));
$id_user = $user['id'];

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy bài viết cần sửa
$sql_post = "SELECT * FROM bai_viet WHERE id = $id AND id_user = $id_user";
$post = mysqli_fetch_assoc(mysqli_query($link, $sql_post));
if (!$post) {
    echo "Bài viết không tồn tại hoặc bạn không có quyền sửa.";
    exit();
}

$message = "";

// Nếu người dùng submit sửa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tieu_de = mysqli_real_escape_string($link, $_POST['tieu_de']);
    $mo_ta = mysqli_real_escape_string($link, $_POST['mo_ta']);
    $noi_dung = mysqli_real_escape_string($link, $_POST['noi_dung']);

    $hinh_anh = $post['hinh_anh'];
    if (!empty($_FILES['hinh_anh']['name']) && $_FILES['hinh_anh']['error'] == 0) {
        $hinh_anh = basename($_FILES['hinh_anh']['name']);
        move_uploaded_file($_FILES['hinh_anh']['tmp_name'], "../assets/img/" . $hinh_anh);
    }

    $sql_update = "
        UPDATE bai_viet SET 
        tieu_de = '$tieu_de',
        mo_ta = '$mo_ta',
        noi_dung = '$noi_dung',
        hinh_anh = '$hinh_anh',
        trang_thai = 'cho_duyet',
        ngay_tao = NOW()
        WHERE id = $id AND id_user = $id_user
    ";

    if (mysqli_query($link, $sql_update)) {
        header("Location: profile.php");
        exit();
    } else {
        $message = " Có lỗi xảy ra khi cập nhật.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="form-wrapper">
    <h2>✏️ Sửa bài viết</h2>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="tieu_de">Tiêu đề *</label>
        <input type="text" name="tieu_de" value="<?php echo htmlspecialchars($post['tieu_de']); ?>" required>

        <label for="mo_ta">Mô tả ngắn</label>
        <textarea name="mo_ta" rows="3"><?php echo htmlspecialchars($post['mo_ta']); ?></textarea>

        <label for="noi_dung">Nội dung *</label>
        <textarea name="noi_dung" id="noi_dung" rows="8" required><?php echo htmlspecialchars($post['noi_dung']); ?></textarea>

        <label for="hinh_anh">Ảnh đại diện (chọn nếu muốn thay)</label>
        <input type="file" name="hinh_anh">

        <?php if ($post['hinh_anh']): ?>
            <p><img src="../assets/img/<?php echo $post['hinh_anh']; ?>" width="150"></p>
        <?php endif; ?>

        <button type="submit">💾 Cập nhật</button>
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