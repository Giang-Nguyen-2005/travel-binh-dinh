<section class="subscribe-section">
  <img src="../assets/img/anh-dep-binh-dinh-36.jpg" class="background-img">

  <div class="subscribe-content">
    <h2>Đăng ký để không bỏ lỡ điều thú vị</h2>
    <p>Nhận thông tin về sự kiện sắp tới, mẹo du lịch và những chia sẻ thú vị từ Bình Định.</p>

    <div id="responseMessage"></div>

    <form class="subscribe-form" id="subscribeForm" method="POST">
      <input type="email" name="email" placeholder="email@gmail.com" required />
      <button type="submit">ĐĂNG KÝ</button>
    </form>
  </div>
</section>

<!-- jQuery -->
<script src="../assets/js/jquery-3.7.1.min.js"></script>

<!-- AJAX xử lý -->
<script>
  $(document).ready(function () {
    $('#subscribeForm').submit(function (e) {
      e.preventDefault();

      $.ajax({
        type: 'POST',
        url: '../includes/xu_ly_dang_ky_email.php',
        data: $(this).serialize(),
        success: function (response) {
          $('#responseMessage').html(response);
        },
        error: function () {
          $('#responseMessage').html('<p style="color:red;">Đã có lỗi xảy ra. Vui lòng thử lại sau.</p>');
        }
      });
    });
  });
</script>
