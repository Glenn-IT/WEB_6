

  <main>

    <div class="mb-4 pb-lg-3"></div>

    <section class="shop-main container">
      <div class="d-flex justify-content-between mb-4 pb-md-2">
        <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
          <a href="<?=$_ENV['URL_HOST']?>" class="menu-link menu-link_us-s text-uppercase fw-medium" >Home</a>
          <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
          <a href="?page=specificshop&type=<?=$_GET["type"]?>" class="menu-link menu-link_us-s text-uppercase fw-medium" ><?=$_GET["type"]?></a>
        </div><!-- /.breadcrumb -->

        <div class="shop-acs d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">

          <div class="shop-asc__seprator mx-3 bg-light d-none d-md-block order-md-0"></div>

          <div class="col-size align-items-center order-1 d-none d-lg-flex">
            <span class="text-uppercase fw-medium me-2">View</span>
            <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid" data-cols="2">2</button>
            <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid" data-cols="3">3</button>
            <button class="btn-link fw-medium js-cols-size" data-target="products-grid"  data-cols="4">4</button>
          </div><!-- /.col-size -->

          <div class="shop-asc__seprator mx-3 bg-light d-none d-lg-block order-md-1"></div>

         
        </div><!-- /.shop-acs -->
      </div><!-- /.d-flex justify-content-between -->

      <div class="products-grid row row-cols-2 row-cols-md-3 row-cols-lg-4" id="products-grid" >

        <?php 
        if(count($listofserveice) > 0) {
            foreach ($listofserveice as $key => $value) {
            ?> 
            
            <div class="product-card-wrapper">
                <div class="product-card mb-3 mb-md-4 mb-xxl-5">
                    <div class="pc__img-wrapper">
                    <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                        <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>"><img loading="lazy" src="<?=$_ENV['URL_HOST'].$value["img_255x200_home"]?>" width="330" height="400" alt="Cropped Faux leather Jacket" class="pc__img"></a>
                        </div><!-- /.pc__img-wrapper -->
                        <div class="swiper-slide">
                            <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>"><img loading="lazy" src="<?=$_ENV['URL_HOST'].$value["img_255x200_home"]?>" width="330" height="400" alt="Cropped Faux leather Jacket" class="pc__img"></a>
                        </div><!-- /.pc__img-wrapper -->
                        </div>
                        <span class="pc__img-prev"><svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_sm" /></svg></span>
                        <span class="pc__img-next"><svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_sm" /></svg></span>
                    </div>
                    <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>">
                    <button class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium " data-aside="cartDrawer" title="Add To Cart">Add To Cart</button>
                    </a>
                    </div>

                    <div class="pc__info position-relative">
                    <p class="pc__category"><?=ucfirst($value["brand_name"])?></p>
                    <h6 class="pc__title"><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>"><?=ucfirst($value["item_name"])?></a></h6>
                    <div class="product-card__price d-flex">
                        <span class="money price"><?=number_format($value["price"],2)?></span>
                    </div>
                    <!-- <div class="product-card__review d-flex align-items-center">
                        <div class="reviews-group d-flex">
                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg"><use href="#icon_star" /></svg>
                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg"><use href="#icon_star" /></svg>
                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg"><use href="#icon_star" /></svg>
                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg"><use href="#icon_star" /></svg>
                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg"><use href="#icon_star" /></svg>
                        </div>
                        <span class="reviews-note text-lowercase text-secondary ms-1">8k+ reviews</span>
                    </div> -->

                    <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist" title="Add To Wishlist">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_heart" /></svg>
                    </button>
                    </div>
                </div>
            </div>






            <?php
            }
        }
        ?>

     
    </div><!-- /.products-grid row -->
<!-- 
      <p class="mb-1 text-center fw-medium">SHOWING 36 of 497 items</p>
      <div class="progress progress_uomo mb-3 ms-auto me-auto" style="width: 300px;">
        <div class="progress-bar" role="progressbar" style="width: 39%;" aria-valuenow="39" aria-valuemin="0" aria-valuemax="100"></div>
      </div> -->

      <div class="text-center">
        <a class="btn-link btn-link_lg text-uppercase fw-medium" href="#">Show More</a>
      </div>
    </section><!-- /.shop-main container -->
  </main>
