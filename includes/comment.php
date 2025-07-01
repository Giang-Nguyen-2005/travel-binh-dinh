<?php
$link = mysqli_connect("localhost", "root", "", "travel_binh_dinh", 3307);
mysqli_set_charset($link, "utf8");

if (session_status() === PHP_SESSION_NONE) session_start();

$error = '';
$success = '';
$ds_binh_luan = [];

$bai_viet_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$check = mysqli_query($link, "SELECT id FROM bai_viet WHERE id = $bai_viet_id");
if (mysqli_num_rows($check) == 0) {
    $error = " Bài viết không tồn tại hoặc đã bị xóa.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['gui_binh_luan']) && !$error) {
    if (!isset($_SESSION['user'])) {
        $error = "⚠️ Bạn cần đăng nhập để bình luận.";
    } else {
        $username = $_SESSION['user'];
        $res_user = mysqli_query($link, "SELECT id FROM user WHERE username = '$username' LIMIT 1");
        $user = mysqli_fetch_assoc($res_user);

        if (!$user) {
            $error = " Không tìm thấy người dùng.";
        } else {
            $noi_dung = trim($_POST['noi_dung']);
            if ($noi_dung === '') {
                $error = " Nội dung không được để trống.";
            } else {
                $sql = "INSERT INTO binh_luan (bai_viet_id, id_user, noi_dung)
                        VALUES ($bai_viet_id, {$user['id']}, '$noi_dung')";
                $success = mysqli_query($link, $sql) ? " Bình luận thành công!" : " Lỗi khi lưu bình luận.";
            }
        }
    }
}

$sql = "SELECT bl.noi_dung, bl.ngay_tao, u.ho_ten
        FROM binh_luan bl
        JOIN user u ON bl.id_user = u.id
        WHERE bl.bai_viet_id = $bai_viet_id
        ORDER BY bl.ngay_tao DESC";
$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_assoc($result)) $ds_binh_luan[] = $row;
?>

<section class="comment-section">
    <h3>🗨️ Để lại bình luận</h3>

    <?php if ($error) echo "<div style='color:red;'>$error</div>"; ?>
    <?php if ($success) echo "<div style='color:green;'>$success</div>"; ?>

    <form method="POST" class="comment-form">
        <textarea name="noi_dung" rows="4" placeholder="Viết bình luận tại đây..." required></textarea>
        <button type="submit" name="gui_binh_luan">Gửi bình luận</button>
    </form>

    <hr style="margin: 40px 0; border-color: #ddd;">
    <h3>Bình luận trước đó</h3>

    <?php if ($ds_binh_luan): ?>
        <?php foreach ($ds_binh_luan as $bl): ?>
            <div style="margin-bottom: 20px;">
                <strong><?php echo htmlspecialchars($bl['ho_ten']); ?></strong>
                <small style="color: gray;">
                    (<?php echo date('d/m/Y H:i', strtotime($bl['ngay_tao'])); ?>)
                </small>
                <p><?php echo nl2br(htmlspecialchars($bl['noi_dung'])); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Chưa có bình luận nào.</p>
    <?php endif; ?>
</section>