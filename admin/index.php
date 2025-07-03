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
  $(document).ready(function () {
    // Kiểm tra xem có trang lưu trong localStorage không
    const savedPage = localStorage.getItem('currentPage') || 'pages/home.php';
    $('#main-content').load(savedPage);

    // Bắt sự kiện click menu sidebar
    $('.admin-sidebar a').click(function (e) {
      e.preventDefault();
      const page = $(this).attr('href');

      // Load nội dung & lưu vào localStorage
      $('#main-content').load(page);
      localStorage.setItem('currentPage', page);
    });

    $(document).on('click', '.ajax-link', function (e) {
      e.preventDefault();
      const page = $(this).attr('href');

      $('#main-content').load(page);
      localStorage.setItem('currentPage', page);
    });
  });
</script>


</body>

</html>