<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
mysqli_set_charset($link, "utf8");

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
$sql_user = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
$result_user = mysqli_query($link, $sql_user);
$user = mysqli_fetch_assoc($result_user);
$id_user = $user['id'];

// Lấy tổng số bài
$sql_count = "SELECT COUNT(*) AS tong_bai FROM bai_viet WHERE id_user = $id_user";
$tong_bai = mysqli_fetch_assoc(mysqli_query($link, $sql_count))['tong_bai'];

// Lấy danh sách bài viết
$posts = mysqli_query($link, "SELECT * FROM bai_viet WHERE id_user = $id_user ORDER BY ngay_tao DESC");
?>

<?php include '../includes/header.php'; ?>

<div class="profile-card">
    <h2>👤 Hồ sơ người dùng</h2>
    <p><strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Số bài viết đã gửi:</strong> <?php echo $tong_bai; ?></p>

    <a href="viet_bai.php" class="btn-vietbai">➕ Tạo bài viết chia sẻ</a>

    <h3 style="margin-top: 30px;">📄 Bài viết đã gửi</h3>
    <?php if (mysqli_num_rows($posts) > 0): ?>
        <table class="bai-viet-table">
            <tr>
                <th>Tiêu đề</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th>Ảnh</th>
                <th>Hành động</th> 
            </tr>
            <?php while ($row = mysqli_fetch_assoc($posts)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['tieu_de']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['ngay_tao'])); ?></td>
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
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($row['hinh_anh']): ?>
                            <img src="../assets/img/<?php echo $row['hinh_anh']; ?>" width="100">
                        <?php else: ?>
                            Không có
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['trang_thai'] === 'da_duyet'): ?>
                            <a href="chi_tiet_bai_viet.php?id=<?php echo $row['id']; ?>" style="color: #0a7bff; text-decoration: none;">
                                🔍 Xem
                            </a> |
                        <?php endif; ?>

                        <a href="sua_bai.php?id=<?php echo $row['id']; ?>" style="color: orange;">
                            ✏ Sửa
                        </a> |
                        <a href="xoa_bai.php?id=<?php echo $row['id']; ?>" style="color: red;" onclick="return confirm('Bạn có chắc muốn xóa bài viết này không?');">
                            🗑 Xóa
                        </a>
                    </td>


                </tr>
            <?php endwhile; ?>
        </table>

    <?php else: ?>
        <p>Bạn chưa có bài viết nào.</p>
    <?php endif; ?>
</div>
<?php include '../includes/dang_ky.php'; ?>
<?php include '../includes/footer.php'; ?>