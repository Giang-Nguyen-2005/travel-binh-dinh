<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Lấy số liệu thống kê
$total_posts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bai_viet"))['total'];
$total_comments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM binh_luan"))['total'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM user"))['total'];
$total_subscriptions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM dang_ky_email"))['total'];
$total_pending_posts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bai_viet WHERE trang_thai = 'cho_duyet'"))['total'];
?>
<?php require_once '../includes/header.php'; ?>
<h2>Tổng quan</h2>
<div class="dashboard-stats">
    <div class="stat">
        <h3>Bài viết</h3>
        <p><?php echo $total_posts; ?></p>
    </div>
    <div class="stat">
        <h3>Bình luận</h3>
        <p><?php echo $total_comments; ?></p>
    </div>
    <div class="stat">
        <h3>Người dùng</h3>
        <p><?php echo $total_users; ?></p>
    </div>
    <div class="stat">
        <h3>Email đăng ký</h3>
        <p><?php echo $total_subscriptions; ?></p>
    </div>
</div>
<h3>Bài viết đang chờ duyệt (<?php echo $total_pending_posts; ?>)</h3>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Danh mục</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT bv.id, bv.tieu_de, dm.ten as danh_muc, bv.hinh_anh FROM bai_viet bv LEFT JOIN danh_muc dm ON bv.danh_muc_id = dm.id WHERE bv.trang_thai = 'cho_duyet' LIMIT 5";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['tieu_de']}</td>
                <td>{$row['danh_muc']}</td>
                <td><img src='../../assets/img/{$row['hinh_anh']}' width='50'></td>
                <td><a href='admin_posts_details.php?id={$row['id']}&return_url=admin_dashboard.php'>Xem chi tiết</a></td>
              </tr>";
    }
    ?>
    </tbody>
</table>
<a href="admin_posts_approval.php" class="btn">Xem tất cả bài viết chờ duyệt</a>
<?php require_once '../includes/footer.php'; ?>