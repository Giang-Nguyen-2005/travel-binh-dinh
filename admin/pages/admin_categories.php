<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Xử lý thêm danh mục
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    $query = "INSERT INTO danh_muc (ten, mo_ta) VALUES ('$ten', '$mo_ta')";
    mysqli_query($conn, $query);
    header('Location: admin_categories.php');
}

// Xử lý xóa danh mục
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM danh_muc WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: admin_categories.php');
}

// Xử lý sửa danh mục
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['id'];
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    $query = "UPDATE danh_muc SET ten='$ten', mo_ta='$mo_ta' WHERE id=$id";
    mysqli_query($conn, $query);
    header('Location: admin_categories.php');
}

// Phân trang
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;
$total_query = "SELECT COUNT(*) as total FROM danh_muc";
$total = mysqli_fetch_assoc(mysqli_query($conn, $total_query))['total'];
$total_pages = ceil($total / $per_page);
?>
<?php require_once '../includes/header.php'; ?>
<h2>Quản lý Danh mục</h2>
<input type="text" id="search-input" placeholder="Tìm kiếm danh mục...">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT * FROM danh_muc LIMIT $start, $per_page";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['ten']}</td>
                <td>{$row['mo_ta']}</td>
                <td><a href='admin_categories.php?action=edit&id={$row['id']}'>Sửa</a> | <a href='admin_categories.php?action=delete&id={$row['id']}' onclick='return confirm(\"Xóa danh mục?\")'>Xóa</a></td>
              </tr>";
    }
    ?>
    </tbody>
</table>
<div class="pagination">
    <?php
    if ($page > 1) {
        echo "<a href='admin_categories.php?page=" . ($page - 1) . "'>Trước</a>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $page ? 'class="active"' : '';
        echo "<a href='admin_categories.php?page=$i' $active>$i</a>";
    }
    if ($page < $total_pages) {
        echo "<a href='admin_categories.php?page=" . ($page + 1) . "'>Sau</a>";
    }
    ?>
</div>
<?php if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM danh_muc WHERE id = $id";
    $category = mysqli_fetch_assoc(mysqli_query($conn, $query));
?>
<h3>Sửa danh mục</h3>
<form method="POST">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div class="form-group">
        <label>Tên</label>
        <input type="text" name="ten" value="<?php echo $category['ten']; ?>" required>
    </div>
    <div class="form-group">
        <label>Mô tả</label>
        <textarea name="mo_ta" required><?php echo $category['mo_ta']; ?></textarea>
    </div>
    <button type="submit" class="btn">Cập nhật</button>
</form>
<?php } else { ?>
<h3>Thêm danh mục</h3>
<form method="POST">
    <input type="hidden" name="action" value="add">
    <div class="form-group">
        <label>Tên</label>
        <input type="text" name="ten" required>
    </div>
    <div class="form-group">
        <label>Mô tả</label>
        <textarea name="mo_ta" required></textarea>
    </div>
    <button type="submit" class="btn">Thêm</button>
</form>
<?php } ?>
<?php require_once '../includes/footer.php'; ?>