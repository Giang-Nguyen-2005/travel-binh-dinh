<link rel="stylesheet" href="../assets/css/admin-style.css">
<div class="admin-form">
    <?php
    $link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
    $thong_bao = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tieu_de = mysqli_real_escape_string($link, $_POST['tieu_de']);
        $mo_ta = mysqli_real_escape_string($link, $_POST['mo_ta']);
        $noi_dung = mysqli_real_escape_string($link, $_POST['noi_dung']);
        $ngay_tao = date('Y-m-d H:i:s');

        // Xử lý ảnh
        $hinh_anh = '';
        if (!empty($_FILES['hinh_anh']['name'])) {
            $hinh_anh = basename($_FILES['hinh_anh']['name']);
            move_uploaded_file($_FILES['hinh_anh']['tmp_name'], "../../assets/img/" . $hinh_anh);
        }

        $sql = "INSERT INTO bai_viet (tieu_de, mo_ta, noi_dung, hinh_anh, ngay_tao)
            VALUES ('$tieu_de', '$mo_ta', '$noi_dung', '$hinh_anh', '$ngay_tao')";
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
        <textarea name="noi_dung" rows="8" required></textarea><br><br>

        <label>Hình ảnh:</label><br>
        <input type="file" name="hinh_anh"><br><br>

        <button type="submit">Lưu bài viết</button>
    </form>
</div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('noi_dung');
</script>

