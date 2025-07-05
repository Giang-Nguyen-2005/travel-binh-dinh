<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Phân trang
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;
$total_query = "SELECT COUNT(*) as total FROM bai_viet WHERE trang_thai = 'cho_duyet'";
$total = mysqli_fetch_assoc(mysqli_query($conn, $total_query))['total'];
$total_pages = ceil($total / $per_page);

?>
<?php require_once '../includes/header.php'; ?>
<h2>Bài viết đang chờ duyệt</h2>
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
</div>
<input type="text" id="search-input" placeholder="Tìm kiếm bài viết...">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th class="danh-muc">Danh mục</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT bv.id, bv.tieu_de, dm.ten as danh_muc, bv.hinh_anh FROM bai_viet bv LEFT JOIN danh_muc dm ON bv.danh_muc_id = dm.id WHERE bv.trang_thai = 'cho_duyet' LIMIT $start, $per_page";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['tieu_de']}</td>
                <td class='danh-muc'>{$row['danh_muc']}</td>
                <td><img src='../../assets/img/{$row['hinh_anh']}' width='50'></td>
                <td>
                    <a href='admin_posts_details.php?id={$row['id']}&return_url=admin_posts_approval.php'>Xem chi tiết</a> | 
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
        echo "<a href='admin_posts_approval.php?page=" . ($page - 1) . "'>Trước</a>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $page ? 'class="active"' : '';
        echo "<a href='admin_posts_approval.php?page=$i' $active>$i</a>";
    }
    if ($page < $total_pages) {
        echo "<a href='admin_posts_approval.php?page=" . ($page + 1) . "'>Sau</a>";
    }
    ?>
</div>
<?php require_once '../includes/footer.php'; ?>