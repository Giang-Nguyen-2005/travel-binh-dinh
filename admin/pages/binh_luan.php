<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
$result = mysqli_query($link, "
    SELECT b.id, b.ten_nguoi_dung, b.noi_dung, b.ngay_tao, bv.tieu_de
    FROM binh_luan b
    JOIN bai_viet bv ON b.bai_viet_id = bv.id
    ORDER BY b.ngay_tao DESC
");
?>

<h2>💬 Danh sách bình luận</h2>
<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Bài viết</th>
        <th>Tên người dùng</th>
        <th>Nội dung</th>
        <th>Ngày</th>
        <th>Thao tác</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['tieu_de']) ?></td>
        <td><?= htmlspecialchars($row['ten_nguoi_dung']) ?></td>
        <td><?= htmlspecialchars($row['noi_dung']) ?></td>
        <td><?= $row['ngay_tao'] ?></td>
        <td><a href="xoa_binh_luan.php?id=<?= $row['id'] ?>" onclick="return confirm('Xóa bình luận này?')">Xoá</a></td>
    </tr>
    <?php endwhile; ?>
</table>
