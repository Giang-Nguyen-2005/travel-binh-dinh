<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
$result = mysqli_query($link, "SELECT * FROM bai_viet ORDER BY ngay_tao DESC");
?>

<h2>Danh sách bài viết</h2>
<a href="pages/them_bai_viet.php" class="btn-add ajax-link">+ Thêm bài viết mới</a>
<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Tiêu đề</th>
        <th>Ngày tạo</th>
        <th>Hình ảnh</th>
        <th>Thao tác</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo ($row['tieu_de']); ?></td>
            <td><?php echo $row['ngay_tao']; ?></td>
            <td><img src="../assets/img/<?php echo $row['hinh_anh']; ?>" width="100"></td>
            <td>
                <a href="pages/sua_bai_viet.php?id=<?php echo $row['id']; ?>" class="ajax-link">Sửa</a> |
                <a href="pages/xoa_bai_viet.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc muốn xoá?')">Xoá</a>

            </td>
        </tr>
    <?php endwhile; ?>
</table>