<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
mysqli_set_charset($link, "utf8");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Xử lý duyệt hoặc từ chối
if ($id && isset($_GET['action']) && in_array($_GET['action'], ['duyet', 'tu_choi'])) {
    $trang_thai = ($_GET['action'] === 'duyet') ? 'da_duyet' : 'tu_choi';
    $update = "UPDATE bai_viet SET trang_thai = '$trang_thai' WHERE id = $id";
    if (mysqli_query($link, $update)) {
        // Cập nhật xong thì redirect về lại chính trang chi tiết để tránh lặp lại hành động
        header("Location: ../index.php");
        exit;
    } else {
        echo "<div style='color:red;'>❌ Cập nhật thất bại: " . mysqli_error($link) . "</div>";
    }
}

// Lấy thông tin bài viết
$sql = "SELECT bv.*, u.username FROM bai_viet bv 
        JOIN user u ON bv.id_user = u.id 
        WHERE bv.id = $id";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<p style='padding: 50px; text-align: center;'>Không tìm thấy bài viết.</p>";
    exit;
}
?>

<h2><?= htmlspecialchars($row['tieu_de']) ?></h2>
<p><em>Người đăng: <strong><?= htmlspecialchars($row['username']) ?></strong></em></p>
<p><em>Ngày tạo: <?= date('d/m/Y H:i', strtotime($row['ngay_tao'])) ?></em></p>
<hr>
<p><strong>Mô tả:</strong></p>
<p><?= nl2br(htmlspecialchars($row['mo_ta'])) ?></p>

<p><strong>Nội dung bài viết:</strong></p>
<div style="background: #f9f9f9; padding: 20px;">
    <?= $row['noi_dung'] ?>
</div>

<?php if ($row['hinh_anh']): ?>
    <p><img src="../assets/img/<?= htmlspecialchars($row['hinh_anh']) ?>" width="300"></p>
<?php endif; ?>

<div style="margin-top: 20px;">
    <?php if ($row['trang_thai'] === 'cho_duyet'): ?>
        <a href="pages/xem_bai_duyet.php?id=<?= $id ?>&action=duyet"
            style="padding: 10px 20px; background: green; color: white; text-decoration: none;">✅ Duyệt</a>
        <a href="pages/xem_bai_duyet.php?id=<?= $id ?>&action=tu_choi"
            style="padding: 10px 20px; background: red; color: white; text-decoration: none;">❌ Từ chối</a>
    <?php else: ?>
        <span style="color: green;">Bài viết đã được xử lý (<?= $row['trang_thai'] ?>)</span>
        <br>
        <button onclick="quayLaiDanhSach()" style="margin-top: 15px; padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            ← Quay lại danh sách
        </button>

    <?php endif; ?>
</div>
<script>
function quayLaiDanhSach() {
    const page = 'pages/quan_ly_bai_viet.php';
    $('#main-content').load(page);
    localStorage.setItem('currentPage', page);
}
</script>
