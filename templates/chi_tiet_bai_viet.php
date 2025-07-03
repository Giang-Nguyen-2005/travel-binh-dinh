<?php
include '../includes/header.php';

$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM bai_viet WHERE id = $id LIMIT 1";
$result = mysqli_query($link, $sql);

if ($row = mysqli_fetch_array($result)) {
    $heroImage = "../assets/img/" . $row['hinh_anh'];
    $heroTitle = $row['tieu_de'];
    $heroDesc = $row['mo_ta'];
    $danh_muc_id = $row['danh_muc_id'];
    include '../includes/hero_banner.php';
?>
    <section class="content-wrapper">
        <div class="text-left-content">
            <h1><?php echo $row['tieu_de']; ?></h1>
            <p><?php echo $row['noi_dung']; ?></p>
        </div>

        <aside class="right-sidebar">
            <h3>Bài viết khác cùng chủ đề</h3>
            <?php
            $sql_khac = "SELECT id, tieu_de, hinh_anh FROM bai_viet WHERE id != $id AND danh_muc_id = $danh_muc_id AND trang_thai='da_duyet'
                         ORDER BY ngay_tao DESC LIMIT 5";
            $result_khac = mysqli_query($link, $sql_khac);
            while ($khac = mysqli_fetch_array($result_khac)) :
            ?>
                <div class="mini-card">
                    <img src="../assets/img/<?php echo $khac['hinh_anh']; ?>" alt="<?php echo $khac['tieu_de']; ?>">
                    <div class="mini-card-content">
                        <h4><?php echo htmlspecialchars($khac['tieu_de']); ?></h4>
                        <a href="chi_tiet_bai_viet.php?id=<?php echo $khac['id']; ?>">Xem chi tiết →</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </aside>

    </section>


    <?php include '../includes/comment.php'; ?>

<?php
} else {
    echo "<div style='padding: 100px; text-align: center; font-size: 24px;'>Bài viết không tồn tại.</div>";
}

include '../includes/dang_ky.php';
include '../includes/footer.php';
?>