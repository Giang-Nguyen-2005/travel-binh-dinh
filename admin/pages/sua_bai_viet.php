<link rel="stylesheet" href="../assets/css/admin-style.css">
<div class="admin-form">
    <?php
    $link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
        echo "ID không hợp lệ.";
        exit;
    }

    $result = mysqli_query($link, "SELECT * FROM bai_viet WHERE id = $id");
    $row = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tieu_de = mysqli_real_escape_string($link, $_POST['tieu_de']);
        $mo_ta = mysqli_real_escape_string($link, $_POST['mo_ta']);
        $noi_dung = mysqli_real_escape_string($link, $_POST['noi_dung']);

        $hinh_anh = $row['hinh_anh'];
        if (!empty($_FILES['hinh_anh']['name'])) {
            $hinh_anh = basename($_FILES['hinh_anh']['name']);
            move_uploaded_file($_FILES['hinh_anh']['tmp_name'], "../../assets/img/" . $hinh_anh);
        }

        $sql = "UPDATE bai_viet SET 
            tieu_de = '$tieu_de',
            mo_ta = '$mo_ta',
            noi_dung = '$noi_dung',
            hinh_anh = '$hinh_anh'
            WHERE id = $id";
        mysqli_query($link, $sql);
        header("Location: ../index.php");
        exit;
    }
    ?>

    <h2>Sửa bài viết</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Tiêu đề:</label><br>
        <input type="text" name="tieu_de" value="<?php echo $row['tieu_de']; ?>" required><br><br>

        <label>Mô tả:</label><br>
        <textarea name="mo_ta" rows="4" required><?php echo $row['mo_ta']; ?></textarea><br><br>

        <label>Nội dung:</label><br>
        <textarea name="noi_dung" rows="8" required><?php echo $row['noi_dung']; ?></textarea><br><br>

        <label>Hình ảnh:</label><br>
        <input type="file" name="hinh_anh"><br>
        <img src="../../assets/img/<?php echo $row['hinh_anh']; ?>" width="150"><br><br>

        <button type="submit">Cập nhật bài viết</button>
    </form>
</div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('noi_dung');
</script>

