<?php
$heroImage = "../assets/img/dac-san-binh-dinh-5.jpg"; 
$heroTitle = "CẨM NANG ẨM THỰC";
$heroDesc = "Khám phá tinh hoa ẩm thực Bình Định qua những món ăn đậm chất miền Trung.";

include '../includes/header.php';
include '../includes/hero_banner.php';
?>

<section class="explore-section">
    <h1>ẨM THỰC BÌNH ĐỊNH</h1>
    <div class="explore-grid">
        <?php
        // Kết nối CSDL
        $link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
        if (!$link) {
            die("Kết nối thất bại: " . mysqli_connect_error());
        }
        mysqli_set_charset($link, "utf8");

       
        $sql = "SELECT * FROM bai_viet WHERE danh_muc_id = 3 ORDER BY ngay_tao DESC";
        $result = mysqli_query($link, $sql);

        while ($row = mysqli_fetch_assoc($result)) :
        ?>
            <a style="text-decoration: none;" href="chi_tiet_bai_viet.php?id=<?php echo $row['id']; ?>">
                <div class="card-small">
                    <img src="../assets/img/<?php echo $row['hinh_anh']; ?>" alt="<?php echo $row['tieu_de']; ?>">
                    <div class="card-content">
                        <h2><?php echo $row['tieu_de']; ?></h2>
                        <p><?php echo mb_substr($row['mo_ta'], 0, 60, 'UTF-8'); ?>...</p>
                    </div>
                </div>
            </a>
        <?php endwhile; ?>
        <?php mysqli_close($link); ?>
    </div>
</section>

<?php include '../includes/dang_ky.php'; ?>
<?php include '../includes/footer.php'; ?>
