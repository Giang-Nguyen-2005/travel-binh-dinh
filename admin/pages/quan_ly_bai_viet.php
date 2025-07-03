<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
mysqli_set_charset($link, "utf8");

// Lấy bộ lọc
$danh_muc_id = isset($_GET['danh_muc_id']) ? intval($_GET['danh_muc_id']) : 0;
$trang_thai = isset($_GET['trang_thai']) ? $_GET['trang_thai'] : '';

// Điều kiện WHERE
$conditions = [];
if ($danh_muc_id > 0) $conditions[] = "danh_muc_id = $danh_muc_id";
if (in_array($trang_thai, ['cho_duyet', 'da_duyet', 'tu_choi'])) $conditions[] = "trang_thai = '$trang_thai'";

$where = count($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

$sql = "SELECT * FROM bai_viet $where ORDER BY ngay_tao DESC";
$result = mysqli_query($link, $sql);

// Gọi phân trang
$limit = 8;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$base_url = "pages/quan_ly_bai_viet.php?danh_muc_id=$danh_muc_id&trang_thai=$trang_thai";
$sql_base = "SELECT * FROM bai_viet $where ORDER BY ngay_tao DESC";

include '../includes/phan_trang_xu_ly.php';
$result = $GLOBALS['result_pagination'];
$total_pages = $GLOBALS['total_pages'];

?>

<h2>Danh sách bài viết</h2>

<div style="margin-bottom: 15px;">
    <label>Lọc theo danh mục:</label>
    <select id="danh_muc_select">
        <option value="0">-- Tất cả --</option>
        <option value="1" <?= $danh_muc_id == 1 ? 'selected' : '' ?>>Ẩm thực</option>
        <option value="2" <?= $danh_muc_id == 2 ? 'selected' : '' ?>>Văn hóa</option>
        <option value="3" <?= $danh_muc_id == 3 ? 'selected' : '' ?>>Lễ hội</option>
        <option value="4" <?= $danh_muc_id == 4 ? 'selected' : '' ?>>Cẩm nang</option>
        <option value="5" <?= $danh_muc_id == 5 ? 'selected' : '' ?>>Điểm đến</option>
        <option value="6" <?= $danh_muc_id == 6 ? 'selected' : '' ?>>Chia sẻ</option>
    </select>

    <label style="margin-left: 20px;">Trạng thái:</label>
    <select id="trang_thai_select">
        <option value="">-- Tất cả --</option>
        <option value="cho_duyet" <?= $trang_thai == 'cho_duyet' ? 'selected' : '' ?>>Chờ duyệt</option>
        <option value="da_duyet" <?= $trang_thai == 'da_duyet' ? 'selected' : '' ?>>Đã duyệt</option>
        <option value="tu_choi" <?= $trang_thai == 'tu_choi' ? 'selected' : '' ?>>Từ chối</option>
    </select>

    <a style="float: right;" href="pages/them_bai_viet.php" class="btn-add ajax-link">+ Thêm bài viết mới</a>
</div>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Tiêu đề</th>
        <th>Ngày tạo</th>
        <th>Hình ảnh</th>
        <th>Trạng thái</th>
        <th>Thao tác</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['tieu_de']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($row['ngay_tao'])) ?></td>
            <td>
                <?php if (!empty($row['hinh_anh'])): ?>
                    <img src="../assets/img/<?= htmlspecialchars($row['hinh_anh']) ?>" width="100">
                <?php else: ?>
                    Không có
                <?php endif; ?>
            </td>
            <td>
                <?php
                switch ($row['trang_thai']) {
                    case 'cho_duyet':
                        echo "Chờ duyệt";
                        break;
                    case 'da_duyet':
                        echo "Đã duyệt";
                        break;
                    case 'tu_choi':
                        echo "Từ chối";
                        break;
                    default:
                        echo "-";
                }
                ?>
            </td>
            <td>
                <?php if ($row['trang_thai'] === 'cho_duyet'): ?>
                    <a href="pages/xem_bai_duyet.php?id=<?= $row['id'] ?>" class="ajax-link" style="color: #007bff;"> Xem để duyệt</a> |
                <?php endif; ?>
                <a href="pages/sua_bai_viet.php?id=<?= $row['id'] ?>" class="ajax-link">Sửa</a> |
                <a href="pages/xoa_bai_viet.php?id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xoá?')">Xoá</a>
            </td>

        </tr>
    <?php endwhile; ?>
</table>
<?php include '../includes/phan_trang_hien_thi.php';
 ?>

<!-- Script lọc -->
<script>
    $(document).on('change', '#danh_muc_select, #trang_thai_select', function() {
        const danh_muc_id = $('#danh_muc_select').val();
        const trang_thai = $('#trang_thai_select').val();
        const page = `pages/quan_ly_bai_viet.php?danh_muc_id=${danh_muc_id}&trang_thai=${trang_thai}`;
        $('#main-content').load(page);
        localStorage.setItem('currentPage', page);
    });
</script>