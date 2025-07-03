<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
mysqli_set_charset($link, "utf8");

$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$result = [];

if ($search !== '') {
    $query = "SELECT * FROM bai_viet WHERE tieu_de LIKE '%$search%' OR noi_dung LIKE '%$search%' AND trang_thai = 'đã duyệt' ORDER BY ngay_tao DESC";
    $result = mysqli_query($link, $query);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Kết quả tìm kiếm - <?= htmlspecialchars($search) ?></title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="content-wrapper">
  <div class="text-left-content">
    <h1>Kết quả cho: "<?= htmlspecialchars($search) ?>"</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="mini-card">
          <img src="../assets/img/<?= $row['hinh_anh'] ?>" alt="Ảnh bài viết">
          <div class="mini-card-content">
            <h4><?= htmlspecialchars($row['tieu_de']) ?></h4>
            <a href="chi_tiet_bai_viet.php?id=<?= $row['id'] ?>">Xem chi tiết</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Không tìm thấy bài viết nào phù hợp.</p>
    <?php endif; ?>
  </div>
</div>
<?php include '../includes/dang_ky.php'; ?>
<?php include '../includes/footer.php'; ?>
</body>
</html>
