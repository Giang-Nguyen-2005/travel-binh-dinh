<?php
$heroImage = "../assets/img/diem_den.jpg"; 
$heroTitle = "ĐIỂM ĐẾN";
$heroDesc = "Khám phá vẻ đẹp của Bình Định qua những điểm đến";
include '../includes/header.php';
include '../includes/hero_banner.php';
include '../includes/db.php';
include '../includes/pagination.php';
?>

<section class="explore-section">
    <h1>ĐIỂM ĐẾN NỔI BẬT</h1>
    <div class="explore-grid">
        <?php
        $pagination_data = get_paginated_posts($link, 2);
        $result = $pagination_data['posts'];
        $current_page = $pagination_data['current_page'];
        $total_pages = $pagination_data['total_pages'];

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
        <?php 
        endwhile;
        mysqli_free_result($result);
        mysqli_close($link);
        ?>
    </div>
    <?php display_pagination($current_page, $total_pages, 'diem_den.php'); ?>
</section>

<?php include '../includes/dang_ky.php'; ?>
<?php include '../includes/footer.php'; ?>