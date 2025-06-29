<?php
include '../includes/db.php';

$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);
?>

<h2>Quản lý người dùng</h2>

<table border="1" cellspacing="0" cellpadding="8" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>User Name</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['ho_ten'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['role'] ?></td>
            <td>
                <a href="#">Sửa</a> |
                <a href="#">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
