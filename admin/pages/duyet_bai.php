<?php
// Kết nối MySQL
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
if (!$link) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Xử lý duyệt hoặc từ chối
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'duyet') {
        $trang_thai = 'da_duyet';
    } elseif ($action === 'tu_choi') {
        $trang_thai = 'tu_choi';
    }

    $sql_update = "UPDATE bai_viet SET trang_thai = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql_update);
    mysqli_stmt_bind_param($stmt, 'si', $trang_thai, $id);
    mysqli_stmt_execute($stmt);
    header("Location: duyet_bai.php");
    exit();
}

// Lọc theo trạng thái
$trang_thai_filter = isset($_GET['trang_thai']) ? $_GET['trang_thai'] : '';
$valid_trang_thai = ['cho_duyet', 'da_duyet', 'tu_choi'];

if ($trang_thai_filter && in_array($trang_thai_filter, $valid_trang_thai)) {
    $sql = "SELECT * FROM bai_viet WHERE danh_muc_id = 6 AND trang_thai = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $trang_thai_filter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    // Mặc định: hiển thị tất cả trạng thái
    $sql = "SELECT * FROM bai_viet WHERE danh_muc_id = 6";
    $result = mysqli_query($link, $sql);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Duyệt bài chia sẻ</title>
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>
<div class="main-content">
    <h2>Duyệt bài chia sẻ</h2>

    <form method="GET" style="margin-bottom: 20px;">
        <label for="trang_thai">Lọc theo trạng thái: </label>
        <select name="trang_thai" id="trang_thai" onchange="this.form.submit()">
            <option value="">-- Tất cả --</option>
            <option value="cho_duyet" <?php if ($trang_thai_filter == 'cho_duyet') echo 'selected'; ?>>Chờ duyệt</option>
            <option value="da_duyet" <?php if ($trang_thai_filter == 'da_duyet') echo 'selected'; ?>>Đã duyệt</option>
            <option value="tu_choi" <?php if ($trang_thai_filter == 'tu_choi') echo 'selected'; ?>>Từ chối</option>
        </select>
    </form>
    
    <table border="1" cellspacing="0" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Nội dung</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['tieu_de']); ?></td>
            <td><?php echo htmlspecialchars(mb_strimwidth($row['noi_dung'], 0, 100, "...")); ?></td>
            <td><?php echo $row['trang_thai']; ?></td>
            <td>
                <?php if ($row['trang_thai'] == 'cho_duyet') { ?>
                    <a href="?action=duyet&id=<?php echo $row['id']; ?>" style="color: green;">Duyệt</a> |
                    <a href="?action=tu_choi&id=<?php echo $row['id']; ?>" style="color: red;">Từ chối</a>
                <?php } else {
                    echo 'Đã xử lý';
                } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include_once('../includes/footer.php'); ?>
</body>
</html>