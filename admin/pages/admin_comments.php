<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Xử lý xóa bình luận
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM binh_luan WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: admin_comments.php');
}

// Phân trang
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;
$total_query = "SELECT COUNT(*) as total FROM binh_luan";
$total = mysqli_fetch_assoc(mysqli_query($conn, $total_query))['total'];
$total_pages = ceil($total / $per_page);
?>
<?php require_once '../includes/header.php'; ?>
<h2>Quản lý Bình luận</h2>
<input type="text" id="search-input" placeholder="Tìm kiếm bình luận...">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nội dung</th>
            <th>Bài viết</th>
            <th>Người dùng</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT bl.id, bl.noi_dung, bv.tieu_de, u.ho_ten, bl.ngay_tao FROM binh_luan bl LEFT JOIN bai_viet bv ON bl.bai_viet_id = bv.id LEFT JOIN user u ON bl.id_user = u.id LIMIT $start, $per_page";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['noi_dung']}</td>
                <td>{$row['tieu_de']}</td>
                <td>{$row['ho_ten']}</td>
                <td>{$row['ngay_tao']}</td>
                <td><a href='admin_comments.php?action=delete&id={$row['id']}' onclick='return confirm(\"Xóa bình luận?\")'>Xóa</a></td>
              </tr>";
    }
    ?>
    </tbody>
</table>
<div class="pagination">
    <?php
    if ($page > 1) {
        echo "<a href='admin_comments.php?page=" . ($page - 1) . "'>Trước</a>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $page ? 'class="active"' : '';
        echo "<a href='admin_comments.php?page=$i' $active>$i</a>";
    }
    if ($page < $total_pages) {
        echo "<a href='admin_comments.php?page=" . ($page + 1) . "'>Sau</a>";
    }
    ?>
</div>
<?php require_once '../includes/footer.php'; ?>