<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Xử lý thêm bài viết
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $tieu_de = mysqli_real_escape_string($conn, $_POST['tieu_de']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    $noi_dung = mysqli_real_escape_string($conn, $_POST['noi_dung']);
    $danh_muc_id = $_POST['danh_muc_id'];
    $trang_thai = $_POST['trang_thai'];
    $hinh_anh = $_POST['hinh_anh'];
    $query = "INSERT INTO bai_viet (tieu_de, mo_ta, noi_dung, hinh_anh, danh_muc_id, trang_thai) VALUES ('$tieu_de', '$mo_ta', '$noi_dung', '$hinh_anh', '$danh_muc_id', '$trang_thai')";
    mysqli_query($conn, $query);
    header('Location: admin_posts.php');
}

// Xử lý xóa bài viết
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM bai_viet WHERE id = $id";
    mysqli_query($conn, $query);
    header('Location: admin_posts.php');
}

// Xử lý sửa bài viết
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['id'];
    $tieu_de = mysqli_real_escape_string($conn, $_POST['tieu_de']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    $noi_dung = mysqli_real_escape_string($conn, $_POST['noi_dung']);
    $danh_muc_id = $_POST['danh_muc_id'];
    $trang_thai = $_POST['trang_thai'];
    $hinh_anh = $_POST['hinh_anh'];
    $query = "UPDATE bai_viet SET tieu_de='$tieu_de', mo_ta='$mo_ta', noi_dung='$noi_dung', hinh_anh='$hinh_anh', danh_muc_id='$danh_muc_id', trang_thai='$trang_thai' WHERE id=$id";
    mysqli_query($conn, $query);
    header('Location: admin_posts.php');
}

// Phân trang
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;
$total_query = "SELECT COUNT(*) as total FROM bai_viet";
$total = mysqli_fetch_assoc(mysqli_query($conn, $total_query))['total'];
$total_pages = ceil($total / $per_page);
?>
<?php require_once '../includes/header.php'; ?>
<h2>Quản lý Bài viết</h2>
<div class="filter-group">
    <label>Lọc theo danh mục:</label>
    <select id="filter-danh-muc" class="filter">
        <option value="">Tất cả</option>
        <?php
        $query = "SELECT * FROM danh_muc";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['ten']}'>{$row['ten']}</option>";
        }
        ?>
    </select>
    <label>Lọc theo trạng thái:</label>
    <select id="filter-trang-thai" class="filter">
        <option value="">Tất cả</option>
        <option value="cho_duyet">Chờ duyệt</option>
        <option value="da_duyet">Đã duyệt</option>
        <option value="tu_choi">Từ chối</option>
    </select>
</div>
<input type="text" id="search-input" placeholder="Tìm kiếm bài viết...">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th class="danh-muc">Danh mục</th>
            <th class="trang-thai">Trạng thái</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT bv.id, bv.tieu_de, dm.ten as danh_muc, bv.trang_thai, bv.hinh_anh FROM bai_viet bv LEFT JOIN danh_muc dm ON bv.danh_muc_id = dm.id LIMIT $start, $per_page";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['tieu_de']}</td>
                <td class='danh-muc'>{$row['danh_muc']}</td>
                <td class='trang-thai'>{$row['trang_thai']}</td>
                <td><img src='../../assets/img/{$row['hinh_anh']}' width='50'></td>
                <td>
                    <a href='admin_posts_details.php?id={$row['id']}&return_url=admin_posts.php'>Xem chi tiết</a> | 
                    <a href='admin_posts.php?action=edit&id={$row['id']}'>Sửa</a> | 
                    <a href='admin_posts.php?action=delete&id={$row['id']}' onclick='return confirm(\"Xóa bài viết?\")'>Xóa</a>
                </td>
              </tr>";
    }
    ?>
    </tbody>
</table>
<div class="pagination">
    <?php
    if ($page > 1) {
        echo "<a href='admin_posts.php?page=" . ($page - 1) . "'>Trước</a>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $page ? 'class="active"' : '';
        echo "<a href='admin_posts.php?page=$i' $active>$i</a>";
    }
    if ($page < $total_pages) {
        echo "<a href='admin_posts.php?page=" . ($page + 1) . "'>Sau</a>";
    }
    ?>
</div>

<?php if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM bai_viet WHERE id = $id";
    $post = mysqli_fetch_assoc(mysqli_query($conn, $query));
?>
<h3>Sửa bài viết</h3>
<form method="POST">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">
    <div class="form-group">
        <label>Tiêu đề</label>
        <input type="text" name="tieu_de" value="<?php echo htmlspecialchars($post['tieu_de']); ?>" required>
    </div>
    <div class="form-group">
        <label>Mô tả</label>
        <textarea name="mo_ta" required><?php echo htmlspecialchars($post['mo_ta']); ?></textarea>
    </div>
    <div class="form-group">
        <label>Nội dung</label>
        <textarea id="noi_dung" name="noi_dung" rows="10" required><?php echo htmlspecialchars($post['noi_dung']); ?></textarea>
    </div>
    <div class="form-group">
        <label>Danh mục</label>
        <select name="danh_muc_id" required>
            <?php
            $query = "SELECT * FROM danh_muc";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $selected = $row['id'] == $post['danh_muc_id'] ? 'selected' : '';
                echo "<option value='{$row['id']}' $selected>{$row['ten']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label>Trạng thái</label>
        <select name="trang_thai" required>
            <option value="cho_duyet" <?php if ($post['trang_thai'] == 'cho_duyet') echo 'selected'; ?>>Chờ duyệt</option>
            <option value="da_duyet" <?php if ($post['trang_thai'] == 'da_duyet') echo 'selected'; ?>>Đã duyệt</option>
            <option value="tu_choi" <?php if ($post['trang_thai'] == 'tu_choi') echo 'selected'; ?>>Từ chối</option>
        </select>
    </div>
    <div class="form-group">
        <label>Hình ảnh</label>
        <select name="hinh_anh" required>
            <?php
            $images = scandir('../../assets/img');
            foreach ($images as $image) {
                if ($image != '.' && $image != '..') {
                    $selected = $image == $post['hinh_anh'] ? 'selected' : '';
                    echo "<option value='$image' $selected>$image</option>";
                }
            }
            ?>
        </select>
    </div>
    <button type="submit" class="btn">Cập nhật</button>
</form>
<?php } else { ?>
<h3>Thêm bài viết</h3>
<form method="POST">
    <input type="hidden" name="action" value="add">
    <div class="form-group">
        <label>Tiêu đề</label>
        <input type="text" name="tieu_de" required>
    </div>
    <div class="form-group">
        <label>Mô tả</label>
        <textarea name="mo_ta" required></textarea>
    </div>
    <div class="form-group">
        <label>Nội dung</label>
        <textarea id="noi_dung" name="noi_dung" rows="10" required></textarea>
    </div>
    <div class="form-group">
        <label>Danh mục</label>
        <select name="danh_muc_id" required>
            <?php
            $query = "SELECT * FROM danh_muc";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>{$row['ten']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label>Trạng thái</label>
        <select name="trang_thai" required>
            <option value="cho_duyet">Chờ duyệt</option>
            <option value="da_duyet">Đã duyệt</option>
            <option value="tu_choi">Từ chối</option>
        </select>
    </div>
    <div class="form-group">
        <label>Hình ảnh</label>
        <select name="hinh_anh" required>
            <?php
            $images = scandir('../../assets/img');
            foreach ($images as $image) {
                if ($image != '.' && $image != '..') {
                    echo "<option value='$image'>$image</option>";
                }
            }
            ?>
        </select>
    </div>
    <button type="submit" class="btn">Thêm</button>
</form>
<?php } ?>
<?php require_once '../includes/footer.php'; ?>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('noi_dung');
</script>