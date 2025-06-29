// Đợi DOM load xong
$(document).ready(function () {
  $(".card").on("mouseenter", function () {
    $(this).find("button").css({
      opacity: 1,
      transform: "translateY(-20px)"
    });
  });

  $(".card").on("mouseleave", function () {
    $(this).find("button").css({
      opacity: 0,
      transform: "translateY(20px)"
    });
  });
  // Navbar đổi style khi scroll
  $(window).on('scroll', function () {
    if ($(window).scrollTop() > 50) {
      $('#navbar').addClass('scrolled');
    } else {
      $('#navbar').removeClass('scrolled');
    }
  });

  // Khởi tạo Swiper
  new Swiper('.swiper', {
    loop: true,
    // autoplay: {
    //   delay: 5000,
    //   disableOnInteraction: false,
    // },
    pagination: {
      el: '.swiper-pagination',
      clickable: true
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev'
    }
  });

  // Scroll đến phần #intro khi click nút "Bắt đầu khám phá"
  $('.discover-btn').on('click', function () {
    $('html, body').animate({
      scrollTop: $('#intro').offset().top
    }, 800);
  });

  // Menu hamburger cho mobile
  $('#hamburger').on('click', function () {
    $(this).toggleClass('active');
    $('#nav-menu').toggleClass('open');
  });

  // Đóng menu khi click vào link
  $('.nav-menu a').on('click', function () {
    $('#nav-menu').removeClass('open');
    $('#hamburger').removeClass('active');
  });

  // Hiện .content-main sau 2 giây
  setTimeout(function () {
    $('.content-main').addClass('visible');
  }, 2000);

  // Hiện các .image và .text-content khi scroll đến
  function checkVisibility() {
    $('.image, .text-content').each(function () {
      const elementTop = $(this).offset().top;
      const windowBottom = $(window).scrollTop() + $(window).height();
      if (elementTop < windowBottom - 50) {
        $(this).addClass('visible');
      }
    });
  }

  // Gọi hàm kiểm tra visibility khi scroll và khi trang load
  $(window).on('scroll', checkVisibility);
  checkVisibility();
  $('#account-toggle').on('click', function (e) {
    e.preventDefault();
    $('#account-menu').toggleClass('show');
  });

  // Tự động ẩn khi click ra ngoài
  $(document).on('click', function (e) {
    if (!$(e.target).closest('#account-toggle, #account-menu').length) {
      $('#account-menu').removeClass('show');
    }
  });
});