<link rel="stylesheet" href="../assets/css/admin-style.css">
<div class="admin-form">
<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
mysqli_set_charset($link, "utf8");

$thong_bao = '';

// Lấy danh sách danh mục
$danh_muc_result = mysqli_query($link, "SELECT * FROM danh_muc");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tieu_de = mysqli_real_escape_string($link, $_POST['tieu_de']);
    $mo_ta = mysqli_real_escape_string($link, $_POST['mo_ta']);
    $noi_dung = mysqli_real_escape_string($link, $_POST['noi_dung']);
    $danh_muc_id = intval($_POST['danh_muc_id']);
    $ngay_tao = date('Y-m-d H:i:s');
    $trang_thai = 'cho_duyet'; // Mặc định

    // Xử lý ảnh
    $hinh_anh = '';
    if (!empty($_FILES['hinh_anh']['name'])) {
        $hinh_anh = basename($_FILES['hinh_anh']['name']);
        move_uploaded_file($_FILES['hinh_anh']['tmp_name'], "../../assets/img/" . $hinh_anh);
    }

    $sql = "INSERT INTO bai_viet (tieu_de, mo_ta, noi_dung, hinh_anh, danh_muc_id, ngay_tao, trang_thai)
            VALUES ('$tieu_de', '$mo_ta', '$noi_dung', '$hinh_anh', $danh_muc_id, '$ngay_tao', '$trang_thai')";
    mysqli_query($link, $sql);
    header("Location: ../index.php");
    exit;
}
?>

<h2>Thêm bài viết mới</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Tiêu đề:</label><br>
    <input type="text" name="tieu_de" required><br><br>

    <label>Mô tả:</label><br>
    <textarea name="mo_ta" rows="4" required></textarea><br><br>

    <label>Nội dung:</label><br>
    <textarea id="noi_dung" name="noi_dung" rows="8" required></textarea><br><br>

    <label>Danh mục:</label><br>
    <select name="danh_muc_id" required>
        <option value="">-- Chọn danh mục --</option>
        <?php while ($dm = mysqli_fetch_assoc($danh_muc_result)): ?>
            <option value="<?= $dm['id'] ?>"><?= htmlspecialchars($dm['ten']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Hình ảnh:</label><br>
    <input type="file" name="hinh_anh"><br><br>

    <button type="submit">Lưu bài viết</button>
</form>
</div>

<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('noi_dung');
</script>
