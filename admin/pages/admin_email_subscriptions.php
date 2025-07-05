<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Xử lý xóa email
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM dang_ky_email WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: admin_email_subscriptions.php');
}

// Phân trang
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;
$total_query = "SELECT COUNT(*) as total FROM dang_ky_email";
$total = mysqli_fetch_assoc(mysqli_query($conn, $total_query))['total'];
$total_pages = ceil($total / $per_page);
?>
<?php require_once '../includes/header.php'; ?>
<h2>Quản lý Email đăng ký</h2>
<input type="text" id="search-input" placeholder="Tìm kiếm email...">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Ngày đăng ký</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT * FROM dang_ky_email LIMIT $start, $per_page";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['email']}</td>
                <td>{$row['ngay_dang_ky']}</td>
                <td><a href='admin_email_subscriptions.php?action=delete&id={$row['id']}' onclick='return confirm(\"Xóa email?\")'>Xóa</a></td>
              </tr>";
    }
    ?>
    </tbody>
</table>
<div class="pagination">
    <?php
    if ($page > 1) {
        echo "<a href='admin_email_subscriptions.php?page=" . ($page - 1) . "'>Trước</a>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $page ? 'class="active"' : '';
        echo "<a href='admin_email_subscriptions.php?page=$i' $active>$i</a>";
    }
    if ($page < $total_pages) {
        echo "<a href='admin_email_subscriptions.php?page=" . ($page + 1) . "'>Sau</a>";
    }
    ?>
</div>
<?php require_once '../includes/footer.php'; ?>