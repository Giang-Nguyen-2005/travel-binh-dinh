<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Xử lý thêm người dùng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $ho_ten = mysqli_real_escape_string($conn, $_POST['ho_ten']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'];
    $query = "INSERT INTO user (username, password, ho_ten, email, role) VALUES ('$username', '$password', '$ho_ten', '$email', '$role')";
    mysqli_query($conn, $query);
    header('Location: admin_users.php');
}

// Xử lý xóa người dùng
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM user WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: admin_users.php');
}

// Xử lý sửa người dùng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['id'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $ho_ten = mysqli_real_escape_string($conn, $_POST['ho_ten']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'];
    $query = "UPDATE user SET username='$username', ho_ten='$ho_ten', email='$email', role='$role' WHERE id=$id";
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $query = "UPDATE user SET username='$username', password='$password', ho_ten='$ho_ten', email='$email', role='$role' WHERE id=$id";
    }
    mysqli_query($conn, $query);
    header('Location: admin_users.php');
}

// Phân trang
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;
$total_query = "SELECT COUNT(*) as total FROM user";
$total = mysqli_fetch_assoc(mysqli_query($conn, $total_query))['total'];
$total_pages = ceil($total / $per_page);
?>
<?php require_once '../includes/header.php'; ?>
<h2>Quản lý Người dùng</h2>
<div class="filter-group">
    <label>Lọc theo vai trò:</label>
    <select id="filter-role" class="filter">
        <option value="">Tất cả</option>
        <option value="admin">Admin</option>
        <option value="khach">Khách</option>
    </select>
</div>
<input type="text" id="search-input" placeholder="Tìm kiếm người dùng...">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th class="role">Vai trò</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT * FROM user LIMIT $start, $per_page";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['ho_ten']}</td>
                <td>{$row['email']}</td>
                <td class='role'>{$row['role']}</td>
                <td><a href='admin_users.php?action=edit&id={$row['id']}'>Sửa</a> | <a href='admin_users.php?action=delete&id={$row['id']}' onclick='return confirm(\"Xóa người dùng?\")'>Xóa</a></td>
              </tr>";
    }
    ?>
    </tbody>
</table>
<div class="pagination">
    <?php
    if ($page > 1) {
        echo "<a href='admin_users.php?page=" . ($page - 1) . "'>Trước</a>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $page ? 'class="active"' : '';
        echo "<a href='admin_users.php?page=$i' $active>$i</a>";
    }
    if ($page < $total_pages) {
        echo "<a href='admin_users.php?page=" . ($page + 1) . "'>Sau</a>";
    }
    ?>
</div>
<?php if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM user WHERE id = $id";
    $user = mysqli_fetch_assoc(mysqli_query($conn, $query));
?>
<h3>Sửa người dùng</h3>
<form method="POST">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div class="form-group">
        <label>Tên đăng nhập</label>
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
    </div>
    <div class="form-group">
        <label>Mật khẩu (để trống nếu không đổi)</label>
        <input type="password" name="password">
    </div>
    <div class="form-group">
        <label>Họ tên</label>
        <input type="text" name="ho_ten" value="<?php echo $user['ho_ten']; ?>" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
    </div>
    <div class="form-group">
        <label>Vai trò</label>
        <select name="role" required>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="khach" <?php if ($user['role'] == 'khach') echo 'selected'; ?>>Khách</option>
        </select>
    </div>
    <button type="submit" class="btn">Cập nhật</button>
</form>
<?php } else { ?>
<h3>Thêm người dùng</h3>
<form method="POST">
    <input type="hidden" name="action" value="add">
    <div class="form-group">
        <label>Tên đăng nhập</label>
        <input type="text" name="username" required>
    </div>
    <div class="form-group">
        <label>Mật khẩu</label>
        <input type="password" name="password" required>
    </div>
    <div class="form-group">
        <label>Họ tên</label>
        <input type="text" name="ho_ten" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Vai trò</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="khach">Khách</option>
        </select>
    </div>
    <button type="submit" class="btn">Thêm</button>
</form>
<?php } ?>
<?php require_once '../includes/footer.php'; ?>