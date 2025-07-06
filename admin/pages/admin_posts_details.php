<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: admin_posts.php');
    exit;
}

$id = $_GET['id'];
$query = "SELECT bv.*, dm.ten as danh_muc FROM bai_viet bv LEFT JOIN danh_muc dm ON bv.danh_muc_id = dm.id WHERE bv.id = $id";
$result = mysqli_query($conn, $query);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    header('Location: admin_posts.php');
    exit;
}

// Xử lý duyệt/từ chối/chờ duyệt bài viết
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['approve', 'reject', 'pending'])) {
    $trang_thai = $_POST['action'] == 'approve' ? 'da_duyet' : ($_POST['action'] == 'reject' ? 'tu_choi' : 'cho_duyet');
    $query = "UPDATE bai_viet SET trang_thai='$trang_thai' WHERE id=$id";
    mysqli_query($conn, $query);
    // Chuyển hướng về trang trước hoặc trang mặc định
    $return_url = isset($_GET['return_url']) ? $_GET['return_url'] : 'admin_posts.php';
    header("Location: $return_url");
    exit;
}

?>
<?php require_once '../includes/header.php'; ?>
<h2>Chi tiết bài viết</h2>
<div class="post-detail">
    <p><strong>Tiêu đề:</strong> <?php echo ($post['tieu_de']); ?></p>
    <p><strong>Mô tả:</strong> <?php echo ($post['mo_ta']); ?></p>
    <p><strong>Nội dung:</strong> <?php echo ($post['noi_dung']); ?></p>
    <p><strong>Danh mục:</strong> <?php echo ($post['danh_muc']); ?></p>
    <p><strong>Trạng thái:</strong> <?php echo ($post['trang_thai']); ?></p>
    <p><strong>Hình ảnh:</strong></p>
    <img src="../../assets/img/<?php echo ($post['hinh_anh']); ?>" alt="<?php echo ($post['tieu_de']); ?>">
</div>
<div class="actions">
    <form method="POST" style="display: inline;">
        <input type="hidden" name="action" value="approve">
        <button type="submit" class="btn approve-btn">Duyệt</button>
    </form>
    <form method="POST" style="display: inline;">
        <input type="hidden" name="action" value="reject">
        <button type="submit" class="btn reject-btn">Từ chối</button>
    </form>
    <form method="POST" style="display: inline;">
        <input type="hidden" name="action" value="pending">
        <button type="submit" class="btn pending-btn">Chờ duyệt</button>
    </form>
    <a href="<?php echo isset($_GET['return_url']) ? ($_GET['return_url']) : 'admin_posts.php'; ?>" class="btn">Quay lại</a>
</div>
<?php require_once '../includes/footer.php'; ?>