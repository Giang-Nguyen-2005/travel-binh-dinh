<?php
$heroImage = "../assets/img/diem_den.avif"; 
$heroTitle = "ĐIỂM ĐẾN";
$heroDesc = "Khám phá vẻ đẹp của Bình Định qua những điểm đến";
include '../includes/header.php';
include '../includes/hero_banner.php';
?>

<section class="explore-section">
    <h1>ĐIỂM ĐẾN NỔI BẬT</h1>
    <div class="explore-grid">
        <?php
        $link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
       $sql = "SELECT * FROM bai_viet WHERE danh_muc_id = 2 ORDER BY ngay_tao DESC";

        $result = mysqli_query($link, $sql);
        while ($row = mysqli_fetch_assoc($result)) :
        ?>
            <a style="text-decoration: none;" href="chi_tiet_bai_viet.php?id=<?php echo $row['id']; ?>">
                <div class="card-small">
                    <img src="../assets/img/<?php echo $row['hinh_anh']; ?>" alt="<?php echo $row['tieu_de']; ?>">
                    <div class="card-content">
                        <h2><?php echo $row['tieu_de']; ?></h2>
                        <p><?php echo mb_substr($row['mo_ta'], 0, 60); ?></p>
                    </div>
                </div>
            </a>

        <?php endwhile; ?>
    </div>

</section>

<?php include '../includes/dang_ky.php'; ?>
<?php include '../includes/footer.php'; ?>