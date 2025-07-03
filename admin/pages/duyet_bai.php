<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
mysqli_set_charset($link, "utf8");

// Xử lý duyệt hoặc từ chối bài viết
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'duyet') {
        $trang_thai = 'da_duyet';
    } elseif ($action === 'tu_choi') {
        $trang_thai = 'tu_choi';
    }

    if (isset($trang_thai)) {
        $stmt = mysqli_prepare($link, "UPDATE bai_viet SET trang_thai = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $trang_thai, $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Cập nhật thành công!'); window.location.href = 'duyet_bai.php';</script>";
            exit();
        } else {
            echo "<div style='color: red;'>Lỗi cập nhật: " . mysqli_error($link) . "</div>";
        }
    }
}

// Truy vấn danh sách các bài viết đang chờ duyệt và username
$sql= "SELECT bv.id, bv.tieu_de, bv.mo_ta, bv.ngay_tao, u.username 
             FROM bai_viet bv 
             JOIN user u ON bv.id_user = u.id 
             WHERE bv.trang_thai = 'cho_duyet'
             ORDER BY bv.ngay_tao DESC";
$result = mysqli_query($link, $sql);


?>

<div class="main-content">
    <h2>Danh sách bài viết chờ duyệt</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table border="1" cellspacing="0" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Ngày tạo</th>
                <th>Người đăng</th>
                <th>Hành động</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['tieu_de']) ?></td>
                    <td><?= htmlspecialchars($row['mo_ta']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($row['ngay_tao'])) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td>
                        <a href="pages/xem_bai_duyet.php?id=<?= $row['id'] ?>" class="ajax-link" style="color: #0a7bff;">🔍 Xem bài</a>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>
    <?php else: ?>
        <p>Không có bài viết nào đang chờ duyệt.</p>
    <?php endif; ?>
</div>