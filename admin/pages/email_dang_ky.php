<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$link = mysqli_connect('localhost', 'root', '', 'travel_binh_dinh', 3307)
    or die('Không kết nối được CSDL');

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($link, "DELETE FROM dang_ky_email WHERE id = $id");
}

if (isset($_POST['update'])) {
    $id   = (int)$_POST['id'];
    $em   = mysqli_real_escape_string($link, $_POST['email']);
    $date = mysqli_real_escape_string($link, $_POST['ngay_dang_ky']);

    mysqli_query($link, "
        UPDATE dang_ky_email 
        SET email = '$em', ngay_dang_ky = '$date' 
        WHERE id = $id
    ");
}

if (isset($_GET['edit'])) {
    $id  = (int)$_GET['edit'];
    $row = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM dang_ky_email WHERE id = $id"));
    ?>

    <h2 style="text-align:center">Sửa Email #<?php echo $row['id'] ?></h2>
    <form class="ajax-form" action="pages/email_dang_ky.php" method="post"
          style="max-width:400px;margin:20px auto">
        <input type="hidden" name="update" value="1">
        <input type="hidden" name="id" value="<?php echo $row['id'] ?>">

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $row['email'] ?>" required>

        <label>Ngày đăng ký:</label>
        <input type="date" name="ngay_dang_ky" value="<?php echo $row['ngay_dang_ky'] ?>" required>

        <button type="submit">Cập nhật</button>
        <a class="ajax-link" href="pages/email_dang_ky.php">Hủy</a>
    </form>

    <?php exit;
}

// Lấy danh sách email
$res = mysqli_query($link, "SELECT * FROM dang_ky_email ORDER BY id DESC");
?>

<h2 style="text-align:center;margin:20px 0">Danh sách Email đăng kí </h2>
<table border="1" cellpadding="8" cellspacing="0"
       style="width:90%;margin:auto;border-collapse:collapse">
    <tr style="background:#f2f2f2">
        <th>#</th>
        <th>Email</th>
        <th>Ngày</th>
        <th>Sửa</th>
        <th>Xóa</th>
    </tr>

    <?php if (mysqli_num_rows($res) === 0): ?>
        <tr>
            <td colspan="5" style="text-align:center">Chưa có email.</td>
        </tr>
    <?php else: while ($r = mysqli_fetch_assoc($res)): ?>
        <tr>
            <td><?php echo $r['id'] ?></td>
            <td><?php echo htmlspecialchars($r['email']) ?></td>
            <td><?php echo $r['ngay_dang_ky'] ?></td>
            <td style="text-align:center">
                <a class="ajax-link" href="pages/email_dang_ky.php?edit=<?php echo $r['id'] ?>">Sửa</a>
            </td>
            <td style="text-align:center">
                <a class="ajax-link" href="pages/email_dang_ky.php?delete=<?php echo $r['id'] ?>"
                   onclick="return confirm('Xóa email này?')">Xóa</a>
            </td>
        </tr>
    <?php endwhile; endif; ?>
</table>
