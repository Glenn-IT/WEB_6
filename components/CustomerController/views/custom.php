<div class="mb-2"></div>

<section class="full-width_padding">
    <div class="shop-banner position-relative">
      <div class="banner-slideshow position-relative" style="height: 420px; overflow: hidden;">

        <?php
        // Use DB banners if available, fall back to static images
        $bannerImages = (isset($banners) && count($banners) > 0) ? $banners : [
            ['image_path' => 'src/images/banner/Hero 1.jpg', 'title' => 'Banner 1'],
            ['image_path' => 'src/images/banner/Hero 2.jpg', 'title' => 'Banner 2'],
            ['image_path' => 'src/images/banner/Hero 3.jpg', 'title' => 'Banner 3'],
            ['image_path' => 'src/images/banner/Hero 4.jpg', 'title' => 'Banner 4'],
            ['image_path' => 'src/images/banner/Hero 5.jpg', 'title' => 'Banner 5'],
        ];
        foreach ($bannerImages as $bIdx => $banner):
        ?>
        <div class="banner-slide <?= $bIdx === 0 ? 'active' : '' ?>"
             style="position: absolute; width: 100%; height: 100%; <?= $bIdx !== 0 ? 'opacity: 0;' : '' ?> transition: opacity 1s ease-in-out;">
          <img loading="lazy"
               src="<?= $_ENV['URL_HOST'] . $banner['image_path'] ?>"
               width="1759" height="420"
               alt="<?= htmlspecialchars($banner['title']) ?>"
               class="slideshow-bg__img object-fit-cover"
               style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <?php endforeach; ?>

        <!-- Navigation Arrows -->
        <button class="banner-prev" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; padding: 15px 20px; cursor: pointer; z-index: 10; border-radius: 5px; font-size: 20px;">❮</button>
        <button class="banner-next" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; padding: 15px 20px; cursor: pointer; z-index: 10; border-radius: 5px; font-size: 20px;">❯</button>

        <!-- Indicators/Dots -->
        <div class="banner-indicators" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 10px; z-index: 10;">
          <?php foreach ($bannerImages as $bIdx => $banner): ?>
          <span class="indicator <?= $bIdx === 0 ? 'active' : '' ?>" data-slide="<?= $bIdx ?>"
                style="width: 12px; height: 12px; border-radius: 50%; background: white; cursor: pointer; opacity: <?= $bIdx === 0 ? '1' : '0.5' ?>;"></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div><!-- /.shop-banner position-relative -->
</section><!-- /.full-width_padding-->

<script>
(function() {
  let currentSlide = 0;
  const slides = document.querySelectorAll('.banner-slide');
  const indicators = document.querySelectorAll('.indicator');
  const totalSlides = slides.length;
  let autoSlideInterval;

  function showSlide(index) {
    slides.forEach(slide => { slide.style.opacity = '0'; });
    indicators.forEach(indicator => { indicator.style.opacity = '0.5'; indicator.classList.remove('active'); });
    slides[index].style.opacity = '1';
    indicators[index].style.opacity = '1';
    indicators[index].classList.add('active');
    currentSlide = index;
  }

  function nextSlide() { showSlide((currentSlide + 1) % totalSlides); }
  function prevSlide() { showSlide((currentSlide - 1 + totalSlides) % totalSlides); }
  function startAutoSlide() { autoSlideInterval = setInterval(nextSlide, 5000); }
  function stopAutoSlide()  { clearInterval(autoSlideInterval); }

  document.querySelector('.banner-prev').addEventListener('click', function() { stopAutoSlide(); prevSlide(); startAutoSlide(); });
  document.querySelector('.banner-next').addEventListener('click', function() { stopAutoSlide(); nextSlide(); startAutoSlide(); });

  indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', function() { stopAutoSlide(); showSlide(index); startAutoSlide(); });
  });

  document.querySelector('.banner-slideshow').addEventListener('mouseenter', stopAutoSlide);
  document.querySelector('.banner-slideshow').addEventListener('mouseleave', startAutoSlide);

  startAutoSlide();
})();
</script>



<div class="mb-3 mb-xl-5 pt-1 pb-5"></div>

<?php if (isset($promos) && count($promos) > 0): ?>
<!-- ══════════════════════════════════════════════════════════
     PROMOS SECTION  – rendered from site_promos DB table
     ══════════════════════════════════════════════════════════ -->
<section class="promos-section container mb-5">
  <h2 class="section-title text-uppercase text-center mb-1 mb-md-3 pb-xl-2 mb-xl-4">
    Current <strong>Promotions</strong>
  </h2>
  <div class="row justify-content-center">
    <?php foreach ($promos as $promo): ?>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
      <?php
        $promoLink = '#our-services';
        if (!empty($promo['linked_item_id'])) {
            $promoLink = $_ENV['URL_HOST'] . 'customer/customer/index?page=productItem&id=' . $promo['linked_item_id'];
        }
      ?>
      <a href="<?= $promoLink ?>" class="text-decoration-none">
        <div class="promo-card card h-100 shadow-sm border-0" style="border-radius:12px;overflow:hidden;transition:transform .2s;">
          <?php if (!empty($promo['image_path'])): ?>
          <div style="height:180px;overflow:hidden;">
            <img src="<?= $_ENV['URL_HOST'] . $promo['image_path'] ?>"
                 alt="<?= htmlspecialchars($promo['title']) ?>"
                 style="width:100%;height:100%;object-fit:cover;">
          </div>
          <?php endif; ?>
          <div class="card-body p-3">
            <?php if (!empty($promo['discount_text'])): ?>
            <span class="badge" style="background:#c8906e;color:#fff;font-size:13px;border-radius:20px;padding:4px 12px;margin-bottom:8px;display:inline-block;">
              <?= htmlspecialchars($promo['discount_text']) ?>
            </span>
            <?php endif; ?>
            <h6 class="fw-bold mb-1" style="color:#333;"><?= htmlspecialchars($promo['title']) ?></h6>
            <?php if (!empty($promo['description'])): ?>
            <p class="text-muted mb-0" style="font-size:13px;"><?= htmlspecialchars($promo['description']) ?></p>
            <?php endif; ?>
          </div>
        </div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<style>
.promo-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12) !important; }
</style>

<div class="mb-3 mb-xl-5 pt-1 pb-3"></div>
<?php endif; ?>

<div id="our-services"></div>

    <section class="products-carousel container">
       <h2 class="section-title text-uppercase text-center mb-1 mb-md-3 pb-xl-2 mb-xl-4">Our  <strong>Services</strong></h2>
      <ul class="nav nav-tabs mb-3 mb-xl-5 justify-content-center" id="collections-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore active" id="collections-tab-0-trigger" data-bs-toggle="tab" href="#collections-tab-0" role="tab" aria-controls="collections-tab-1" aria-selected="true">All</a>
        </li>

        <?php 
        $i = 2;
        foreach ($header_services as $key => $value) {
          ?>
          <li class="nav-item" role="presentation">
            <a class="nav-link nav-link_underscore" id="collections-tab-<?=$value["id"]?>-trigger" data-bs-toggle="tab" href="#collections-tab-<?=$value["id"]?>" role="tab" aria-controls="collections-tab-<?=$value["id"]?>" aria-selected="true"><?=ucfirst($value["name"])?></a>
          </li>
          <?php
        }
        ?>

      </ul>

      <div class="tab-content" id="collections-tab-content">


        <div class="tab-pane fade show active" id="collections-tab-0" role="tabpanel" aria-labelledby="collections-tab-0-trigger">
          <div class="position-relative">
            <div class="swiper-container js-swiper-slider"
              data-settings='{
                "autoplay": {
                  "delay": 5000
                },
                "slidesPerView": 5,
                "slidesPerGroup": 5,
                "effect": "none",
                "loop": false,
                "pagination": {
                  "el": "#collections-tab-0 .products-pagination",
                  "type": "bullets",
                  "clickable": true
                },
                "navigation": {
                  "nextEl": "#collections-tab-0 .products-carousel__next",
                  "prevEl": "#collections-tab-0 .products-carousel__prev"
                },
                "breakpoints": {
                  "320": {
                    "slidesPerView": 2,
                    "slidesPerGroup": 2,
                    "spaceBetween": 14
                  },
                  "768": {
                    "slidesPerView": 3,
                    "slidesPerGroup": 3,
                    "spaceBetween": 20
                  },
                  "992": {
                    "slidesPerView": 4,
                    "slidesPerGroup": 1,
                    "spaceBetween": 24,
                    "pagination": false
                  },
                  "1200": {
                    "slidesPerView": 5,
                    "slidesPerGroup": 1,
                    "spaceBetween": 28,
                    "pagination": false
                  }
                }
              }'>
              <div class="swiper-wrapper">

                <?php 
                if(count($listofserveice) > 0) {
                  foreach ($listofserveice as $key => $value) {
                  ?>     
                    <div class="swiper-slide product-card">
                      <div class="pc__img-wrapper">
                        <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>">
                          <img loading="lazy" src="<?=$_ENV['URL_HOST'].$value["img_255x200_home"]?>" width="260" height="315" alt="Cropped Faux leather Jacket" class="pc__img">
                        <button class="pc__atc btn btn-lg anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium " data-aside="cartDrawer" title="Add To Cart">VIEW</button>
                       
                      </div>
                      </a>
                      <div class="pc__info position-relative">
                        <p class="pc__category"><?=ucfirst($value["brand_name"])?></p>
                        <h6 class="pc__title"><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>"><?=ucfirst($value["item_name"])?></a></h6>
                        <div class="product-card__price d-flex">
                          <span class="money price">&#8369;<?=number_format($value["price"],2)?></span>
                        </div>
                      </div>
                    </div>


                  <?php
                  }
                }
                ?>
              </div><!-- /.swiper-wrapper -->
            </div><!-- /.swiper-container js-swiper-slider -->
    
            <div class="products-carousel__prev type2 position-absolute top-50 d-flex align-items-center justify-content-center">
              <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_md" /></svg>
            </div><!-- /.products-carousel__prev -->
            <div class="products-carousel__next type2 position-absolute top-50 d-flex align-items-center justify-content-center">
              <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_md" /></svg>
            </div><!-- /.products-carousel__next -->
    
            <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
            <!-- /.products-pagination -->
          </div><!-- /.position-relative -->
        </div><!-- /.tab-pane fade show-->
        
        <?php 
        foreach ($header_services as $key => $v) {
          ?>
           <div class="tab-pane fade " id="collections-tab-<?=$v["id"]?>" role="tabpanel" aria-labelledby="collections-tab-<?=$v["id"]?>-trigger">
            <div class="position-relative">
              <div class="swiper-container js-swiper-slider"
                data-settings='{
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 5,
                  "slidesPerGroup": 5,
                  "effect": "none",
                  "loop": false,
                  "pagination": {
                    "el": "#collections-tab-<?=$v["id"]?> .products-pagination",
                    "type": "bullets",
                    "clickable": true
                  },
                  "navigation": {
                    "nextEl": "#collections-tab-<?=$v["id"]?> .products-carousel__next",
                    "prevEl": "#collections-tab-<?=$v["id"]?> .products-carousel__prev"
                  },
                  "breakpoints": {
                    "320": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 2,
                      "spaceBetween": 14
                    },
                    "768": {
                      "slidesPerView": 3,
                      "slidesPerGroup": 3,
                      "spaceBetween": 20
                    },
                    "992": {
                      "slidesPerView": 4,
                      "slidesPerGroup": 1,
                      "spaceBetween": 24,
                      "pagination": false
                    },
                    "1200": {
                      "slidesPerView": 5,
                      "slidesPerGroup": 1,
                      "spaceBetween": 28,
                      "pagination": false
                    }
                  }
                }'>
                <div class="swiper-wrapper">

                  <?php 
                  if(count($listofserveice) > 0) {
                    foreach ($listofserveice as $key => $value) {
                      if($v["id"] == $value["brand_id"]) {

                    ?>     
                      <div class="swiper-slide product-card">
                        <div class="pc__img-wrapper">
                          <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>">
                            <img loading="lazy" src="<?=$_ENV['URL_HOST'].$value["img_255x200_home"]?>" width="260" height="315" alt="Cropped Faux leather Jacket" class="pc__img">
                          <button class="pc__atc btn btn-lg anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium" data-aside="cartDrawer" title="Add To Cart">VIEW</button>
                          <div class="anim_appear-right position-absolute top-0 mt-2 me-2">
                            <button class="btn btn-round-sm btn-hover-red d-block border-0 text-uppercase mb-2 js-quick-view" data-bs-toggle="modal" data-bs-target="#quickView" title="Quick view">
                              <svg class="d-inline-block" width="14" height="14" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg"><use href="#icon_view" /></svg>
                            </button>
                            <button class="btn btn-round-sm btn-hover-red d-block border-0 text-uppercase js-add-wishlist" title="Add To Wishlist">
                              <svg width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_heart" /></svg>
                            </button>
                          </div>
                        </div>
                        </a>

                        <div class="pc__info position-relative">
                          <p class="pc__category"><?=ucfirst($value["brand_name"])?></p>
                          <h6 class="pc__title"><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>"><?=ucfirst($value["item_name"])?></a></h6>
                          <div class="product-card__price d-flex">
                            <span class="money price">&#8369;<?=number_format($value["price"],2)?></span>
                          </div>
                        </div>
                      </div>


                    <?php
                      }

                    }
                  }
                  ?>
                </div><!-- /.swiper-wrapper -->
              </div><!-- /.swiper-container js-swiper-slider -->
      
              <div class="products-carousel__prev type2 position-absolute top-50 d-flex align-items-center justify-content-center">
                <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_md" /></svg>
              </div><!-- /.products-carousel__prev -->
              <div class="products-carousel__next type2 position-absolute top-50 d-flex align-items-center justify-content-center">
                <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_md" /></svg>
              </div><!-- /.products-carousel__next -->
      
              <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
              <!-- /.products-pagination -->
            </div><!-- /.position-relative -->
          </div><!-- /.tab-pane fade show-->
          <?php
        }
        ?>
        
       
      </div><!-- /.tab-content pt-2 -->
    </section><!-- /.products-grid -->

<!-- 


 <div class="mb-3 mb-xl-2 pb-3 pt-1 pb-xl-2"></div>




 
 <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>


 <section class="featured-products container">
    <div class="d-flex align-items-center justify-content-md-between flex-wrap mb-3 mb-xl-4">
      <h2 class="section-title fw-semi-bold fs-30 theme-color text-uppercase">SERVICES</h2>

    </div>

    <div class="tab-content pt-2" id="collections-tab-content">
      <div class="tab-pane fade show active" id="collections-tab-1" role="tabpanel" aria-labelledby="collections-tab-1-trigger">
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5">
             
          <?php 
          if(count($listofserveice) > 0) {
            foreach ($listofserveice as $key => $value) {
            ?>     
            <div class="product-card-wrapper mb-2">
              <div class="product-card product-card_style9 border rounded-3 mb-3 mb-md-4 bg-white">
                <div class="position-relative pb-3">
                  <div class="pc__img-wrapper pc__img-wrapper_wide3">
                    <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>">
                      <img loading="lazy" src="<?=$_ENV['URL_HOST'].$value["img_255x200_home"]?>" width="255" height="200" alt="Cropped Faux leather Jacket" class="pc__img">
                    </a>
                  </div>
                  <div class="anim_appear-bottom position-absolute w-100 text-center">
                    <button class="btn btn-round btn-hover-red border-0 text-uppercase me-2 js-quick-view d-inline-flex align-items-center justify-content-center" title="Quick view">
                      <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>">
                        <svg class="d-inline-block" width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg"><use href="#icon_view" /></svg>
                      </a>
                    </button>
                  
                  </div>
                </div>

                <div class="pc__info position-relative">
                  <p class="pc__category fs-13 fw-medium"><?=ucfirst($value["brand_name"])?></p>
                  <h6 class="pc__title fs-16 mb-2"><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>"><?=ucfirst($value["item_name"])?></a></h6>
                 
                  <div class="product-card__price d-flex">
                    <span class="money price fs-16 fw-semi-bold">&#8369;<?=number_format($value["price"],2)?></span>
                  </div>
                </div>
              </div>
            </div>
            <?php
            }
          }
          ?>

         

        </div>
      </div><!-- /.tab-pane fade show-->

      <div class="tab-pane fade show" id="collections-tab-2" role="tabpanel" aria-labelledby="collections-tab-2-trigger">
      </div>

      <div class="tab-pane fade show" id="collections-tab-3" role="tabpanel" aria-labelledby="collections-tab-3-trigger">
      </div>
    </div>
 </section>
 <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
 <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
  <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
   <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
    <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
     <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
      <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div> -->