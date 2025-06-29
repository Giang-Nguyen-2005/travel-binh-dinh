<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <title>Trang Quản Trị</title>
  <link rel="stylesheet" href="assets/css/admin-style.css" />
  <script src="../assets/js/jquery-3.7.1.min.js"></script>
</head>

<body>

  <?php include 'includes/sidebar.php'; ?>

  <main class="admin-main" id="main-content">
  </main>

  <?php include 'includes/footer.php'; ?>

  <script>
    $(document).ready(function() {
      // Load mặc định trang chủ nếu cần
       $('#main-content').load('pages/home.php');

      // Load nội dung tương ứng khi click menu
      $('.admin-sidebar a').click(function(e) {
        e.preventDefault();
        const page = $(this).attr('href');
        $('#main-content').load(page);
      });
      $(document).on('click', '.ajax-link', function(e) {
        e.preventDefault();
        const page = $(this).attr('href');
        $('#main-content').load(page);
      });

    });
  </script>

</body>

</html>