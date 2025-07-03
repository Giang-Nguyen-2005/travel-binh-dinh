<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KHÁM PHÁ BÌNH ĐỊNH</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />

  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://unpkg.com/swiper@9/swiper-bundle.min.css" />

  <style>
    .swiper-slide {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>
</head>

<body>


  <!-- Navbar -->
  <?php session_start(); ?>
  <nav id="navbar">
    <a href="../templates/index.php" class="logo">BÌNH ĐỊNH</a>
    <ul class="nav-menu" id="nav-menu">
      <li><a href="../templates/van_hoa.php">VĂN HÓA</a></li>
      <li><a href="../templates/diem_den.php">ĐIỂM ĐẾN</a></li>
      <li><a href="../templates/am_thuc.php">ẨM THỰC</a></li>
      <li><a href="../templates/blog.php">CHIA SẺ</a></li>
      <li> <form class="search-form" method="GET" action="../templates/search.php">
      <input type="text" name="q" placeholder="Tìm bài viết..." required>
      <button type="submit"><i class="fas fa-search"></i></button>
    </form></li>

      <li class="account-link">
        <a href="#" id="account-toggle"><i class="fas fa-user-circle"></i></a>
        <ul class="dropdown-menu" id="account-menu">
          <?php if (isset($_SESSION['user'])): ?>
            <li><a href="../templates/profile.php"><i class="fas fa-user"></i> Hồ sơ</a></li>
            <li><a href="../templates/logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
          <?php else: ?>
            <li><a href="../templates/login.php"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a></li>
            <li><a href="../templates/register.php"><i class="fas fa-user-plus"></i> Đăng ký</a></li>
          <?php endif; ?>
        </ul>
      </li>

    </ul>


    <div class="hamburger" id="hamburger">
      <span></span><span></span><span></span>
    </div>
  </nav>