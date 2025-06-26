<section id="hero">
  <div class="swiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img src="<?php echo $heroImage; ?>" alt="Hero Image" class="hero-background" />
        <div class="overlay"></div>
        <div class="hero-nav">
          <a href="javascript:history.back()" class="back-btn">←</a>
        </div>
        <div class="content">
          <h1><?php echo $heroTitle; ?></h1>
          <p><?php echo $heroDesc; ?></p>
        </div>
      </div>
    </div>
  </div>
</section>
