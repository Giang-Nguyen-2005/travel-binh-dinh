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
    session_start();
    $id_user = isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : null;
    if (!$id_user) {
        echo "Không tìm thấy thông tin người dùng admin!";
        exit;
    }


    // Xử lý tải ảnh
    $hinh_anh = '';
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $target_dir = "../../assets/img/";
        $file_name = time() . '_' . basename($_FILES['hinh_anh']['name']);
        $target_file = $target_dir . $file_name;

        // Kiểm tra xem file có phải là ảnh hợp lệ không
        if (getimagesize($_FILES['hinh_anh']['tmp_name']) !== false) {
            if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) {
                $hinh_anh = $file_name;
            } else {
                echo "Lỗi khi tải ảnh lên.";
                exit;
            }
        } else {
            echo "File không phải là ảnh hợp lệ.";
            exit;
        }
    }

    $query = "INSERT INTO bai_viet (tieu_de, mo_ta, noi_dung, hinh_anh, danh_muc_id, trang_thai, id_user) 
              VALUES ('$tieu_de', '$mo_ta', '$noi_dung', '$hinh_anh', '$danh_muc_id', '$trang_thai', '$id_user')";
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
    $id_user = isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : null;
    if (!$id_user) {
        echo "Không tìm thấy thông tin người dùng admin!";
        exit;
    }


    // Xử lý tải ảnh mới (nếu có)
    $hinh_anh = $_POST['hinh_anh_cu'] ?? ''; // Giữ ảnh cũ nếu không tải ảnh mới
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $target_dir = "../../assets/img/";
        $file_name = time() . '_' . basename($_FILES['hinh_anh']['name']);
        $target_file = $target_dir . $file_name;

        // Kiểm tra xem file có phải là ảnh hợp lệ không
        if (getimagesize($_FILES['hinh_anh']['tmp_name']) !== false) {
            // Xóa ảnh cũ nếu tồn tại
            if (file_exists($target_dir . $hinh_anh)) {
                unlink($target_dir . $hinh_anh);
            }
            if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) {
                $hinh_anh = $file_name;
            }
        } else {
            echo "File không phải là ảnh hợp lệ.";
            exit;
        }
    }

    $query = "UPDATE bai_viet SET tieu_de='$tieu_de', mo_ta='$mo_ta', noi_dung='$noi_dung', hinh_anh='$hinh_anh', danh_muc_id='$danh_muc_id', trang_thai='$trang_thai', id_user='$id_user' WHERE id=$id";
    mysqli_query($conn, $query);
    header('Location: admin_posts.php');
}

// Phân trang và tìm kiếm/lọc
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

// Lấy tham số tìm kiếm và lọc từ URL
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter_danh_muc = isset($_GET['danh_muc']) ? mysqli_real_escape_string($conn, $_GET['danh_muc']) : '';
$filter_trang_thai = isset($_GET['trang_thai']) ? mysqli_real_escape_string($conn, $_GET['trang_thai']) : '';

// Xây dựng truy vấn SQL với tìm kiếm và lọc
$where = [];
if ($search) {
    $where[] = "(bv.tieu_de LIKE '%$search%' OR bv.mo_ta LIKE '%$search%' OR bv.noi_dung LIKE '%$search%')";
}
if ($filter_danh_muc) {
    $where[] = "dm.ten = '$filter_danh_muc'";
}
if ($filter_trang_thai) {
    $where[] = "bv.trang_thai = '$filter_trang_thai'";
}
$where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Truy vấn tổng số bài viết
$total_query = "SELECT COUNT(*) as total FROM bai_viet bv 
                LEFT JOIN danh_muc dm ON bv.danh_muc_id = dm.id 
                LEFT JOIN user u ON bv.id_user = u.id 
                $where_clause";
$total = mysqli_fetch_assoc(mysqli_query($conn, $total_query))['total'];
$total_pages = ceil($total / $per_page);

// Truy vấn danh sách bài viết
$query = "SELECT bv.id, bv.tieu_de, u.username as nguoi_dang, dm.ten as danh_muc, bv.trang_thai, bv.hinh_anh 
          FROM bai_viet bv 
          LEFT JOIN danh_muc dm ON bv.danh_muc_id = dm.id 
          LEFT JOIN user u ON bv.id_user = u.id 
          $where_clause 
          LIMIT $start, $per_page";
$result = mysqli_query($conn, $query);
?>
<?php require_once '../includes/header.php'; ?>
<h2>Quản lý Bài viết</h2>
<div class="filter-group">
    <label>Lọc theo danh mục:</label>
    <select id="filter-danh-muc" class="filter">
        <option value="">Tất cả</option>
        <?php
        $query = "SELECT * FROM danh_muc";
        $result_danh_muc = mysqli_query($conn, $query);
        $selected_danh_muc = isset($_GET['danh_muc']) ? $_GET['danh_muc'] : '';
        while ($row = mysqli_fetch_assoc($result_danh_muc)) {
            $selected = ($row['ten'] == $selected_danh_muc) ? 'selected' : '';
            echo "<option value='{$row['ten']}' $selected>{$row['ten']}</option>";
        }
        ?>
    </select>
    <label>Lọc theo trạng thái:</label>
    <select id="filter-trang-thai" class="filter">
        <option value="">Tất cả</option>
        <option value="cho_duyet" <?php if (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'cho_duyet') echo 'selected'; ?>>Chờ duyệt</option>
        <option value="da_duyet" <?php if (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'da_duyet') echo 'selected'; ?>>Đã duyệt</option>
        <option value="tu_choi" <?php if (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'tu_choi') echo 'selected'; ?>>Từ chối</option>
    </select>
</div>
<input type="text" id="search-input" placeholder="Tìm kiếm bài viết..." value="<?php echo isset($_GET['search']) ? ($_GET['search']) : ''; ?>">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Người đăng</th>
            <th class="danh-muc">Danh mục</th>
            <th class="trang-thai">Trạng thái</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['tieu_de']}</td>
                <td>{$row['nguoi_dang']}</td>
                <td class='danh-muc'>{$row['danh_muc']}</td>
                <td class='trang-thai'>{$row['trang_thai']}</td>
                <td><img src='../../assets/img/{$row['hinh_anh']}' width='50'></td>
                <td>
                    <a href='admin_posts_details.php?id={$row['id']}&return_url=admin_posts.php'>Xem chi tiết</a>| 
                    <a href='admin_posts.php?action=edit&id={$row['id']}#form_sua'>Sửa</a>
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
        echo "<a href='admin_posts.php?page=" . ($page - 1) . "&search=" . urlencode($search) . "&danh_muc=" . urlencode($filter_danh_muc) . "&trang_thai=" . urlencode($filter_trang_thai) . "'>Trước</a>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $page ? 'class="active"' : '';
        echo "<a href='admin_posts.php?page=$i&search=" . urlencode($search) . "&danh_muc=" . urlencode($filter_danh_muc) . "&trang_thai=" . urlencode($filter_trang_thai) . "' $active>$i</a>";
    }
    if ($page < $total_pages) {
        echo "<a href='admin_posts.php?page=" . ($page + 1) . "&search=" . urlencode($search) . "&danh_muc=" . urlencode($filter_danh_muc) . "&trang_thai=" . urlencode($filter_trang_thai) . "'>Sau</a>";
    }
    ?>
</div>

<?php if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT bv.*, dm.ten as danh_muc, u.username as nguoi_dang 
              FROM bai_viet bv 
              LEFT JOIN danh_muc dm ON bv.danh_muc_id = dm.id 
              LEFT JOIN user u ON bv.id_user = u.id 
              WHERE bv.id = $id";
    $post = mysqli_fetch_assoc(mysqli_query($conn, $query));
?>
    <h3 id="form_sua">Sửa bài viết</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?php echo ($post['id']); ?>">
        <input type="hidden" name="hinh_anh_cu" value="<?php echo ($post['hinh_anh']); ?>">
        <div class="form-group">
            <label>Tiêu đề</label>
            <input type="text" name="tieu_de" value="<?php echo ($post['tieu_de']); ?>" required>
        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="mo_ta" required><?php echo ($post['mo_ta']); ?></textarea>
        </div>
        <div class="form-group">
            <label>Nội dung</label>
            <textarea name="noi_dung" rows="10" required><?php echo ($post['noi_dung']); ?></textarea>
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
            <input type="file" name="hinh_anh" accept="image/*">
            <?php if ($post['hinh_anh']) { ?>
                <p>Hình ảnh hiện tại: <img src='../../assets/img/<?php echo ($post['hinh_anh']); ?>' width='50'></p>
            <?php } ?>
        </div>
        <button type="submit" class="btn">Cập nhật</button>
    </form>
<?php } else { ?>
    <h3>Thêm bài viết</h3>
    <form method="POST" enctype="multipart/form-data">
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
            <textarea name="noi_dung" rows="10" required></textarea>
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
            <input type="file" name="hinh_anh" accept="image/*" required>
        </div>
        <button type="submit" class="btn">Thêm</button>
    </form>
<?php } ?>
<?php require_once '../includes/footer.php'; ?>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('noi_dung');
</script>