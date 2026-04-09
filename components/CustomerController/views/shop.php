<main>
    
    <div class="mb-3 pb-xl-3"></div>
      <form action="index" method="POST">
        <input type="hidden" name="page" value="shop">
    <section class="shop-main container d-flex">
      <div class="shop-sidebar side-sticky bg-body" id="shopFilter">
        <div class="aside-header d-flex d-lg-none align-items-center">
          <h3 class="text-uppercase fs-6 mb-0">Filter By</h3>
          <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
        </div><!-- /.aside-header -->

        <div class="pt-4 pt-lg-0"></div>


        <div class="accordion" id="brand-filters">
          <div class="accordion-item mb-4 pb-3">
            <h5 class="accordion-header" id="accordion-heading-brand">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-filter-brand" aria-expanded="true" aria-controls="accordion-filter-brand">
                Category's
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                  <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                    <path d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z"/>
                  </g>
                </svg>
              </button>
            </h5>
            <div id="accordion-filter-brand" class="accordion-collapse collapse show border-0" aria-labelledby="accordion-heading-brand" data-bs-parent="#brand-filters">
              <div class="search-field multi-select accordion-body px-0 pb-0">
                <select class="d-none" multiple name="brand_filtering[]">
                  <?php 
                    if(count($custome["brand"]) > 0)  {
                      foreach ($custome["brand"] as $key => $value) {
                      ?>
                      <option value="<?=$value["id"]?>"><?=$value["name"]?></option>
                      <?php
                      }
                    }
                  ?>
                </select>
              
                <ul class="multi-select__list list-unstyled">
                  <?php 
                    if(count($custome["brand"]) > 0)  {
                      foreach ($custome["brand"] as $key => $value) {
                      ?>
                      <li class="search-suggestion__item multi-select__item text-primary js-search-select js-multi-select">
                        <span class="me-auto"><?=$value["name"]?></span>
                      </li>
                      <?php
                      }
                    }
                  ?>
                </ul>
                  
              </div>
            </div>
          </div><!-- /.accordion-item -->
        </div><!-- /.accordion -->


        <div class="accordion" id="price-filters">
          <div class="accordion-item mb-4">
            <h5 class="accordion-header mb-2" id="accordion-heading-price">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-filter-price" aria-expanded="true" aria-controls="accordion-filter-price">
                Price
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                  <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                    <path d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z"/>
                  </g>
                </svg>
              </button>
            </h5>
            <div id="accordion-filter-price" class="accordion-collapse collapse show border-0" aria-labelledby="accordion-heading-price" data-bs-parent="#price-filters">
              <input class="price-range-slider" type="text" name="price_range" value="" data-slider-min="<?=$custome["price"]["min"]?>" data-slider-max="<?=$custome["price"]["max"]?>" data-slider-step="5" data-slider-value="[<?=$custome["price"]["min"]?>,<?=$custome["price"]["max"]?>]" data-currency="">
              <div class="price-range__info d-flex align-items-center mt-2">
                <div class="me-auto">
                  <span class="text-secondary">Min Price: </span>
                  <span class="price-range__min">&#8369;<?=$custome["price"]["min"]?></span>
                </div>
                <div>
                  <span class="text-secondary">Max Price: </span>
                  <span class="price-range__max">&#8369;<?=$custome["price"]["max"]?></span>
                </div>
              </div>
            </div>
          </div><!-- /.accordion-item -->
        </div><!-- /.accordion -->

          <button type="submit" class="btn btn-primary " style="width: 100%;" >SUBMIT</button>

          </form>

      </div><!-- /.shop-sidebar -->

      <div class="shop-list flex-grow-1">
        <!-- Search Bar -->
        <div class="search-section mb-4">
          <form action="index" method="GET" class="d-flex align-items-center">
            <input type="hidden" name="page" value="shop">
            <div class="search-field flex-grow-1 me-3">
              <input type="text" name="search" class="form-control" placeholder="Search for items, brands, or services..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            </div>
            <button type="submit" class="btn btn-primary">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.7549 14.255L12.6062 11.106C13.6463 9.92231 14.253 8.38843 14.253 6.7265C14.253 3.02319 11.2298 0 7.5265 0C3.82319 0 0.799988 3.02319 0.799988 6.7265C0.799988 10.4298 3.82319 13.453 7.5265 13.453C9.18843 13.453 10.7223 12.8463 11.906 11.8062L15.0547 14.9549C15.1603 15.0605 15.3026 15.1133 15.4449 15.1133C15.5872 15.1133 15.7295 15.0605 15.8351 14.9549C16.0463 14.7437 16.0463 14.4663 15.7549 14.255ZM7.5265 11.9531C4.6508 11.9531 2.29999 9.60229 2.29999 6.7265C2.29999 3.85071 4.6508 1.5 7.5265 1.5C10.4023 1.5 12.753 3.85071 12.753 6.7265C12.753 9.60229 10.4023 11.9531 7.5265 11.9531Z" fill="white"/>
              </svg>
              Search
            </button>
          </form>
          <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
            <div class="search-info mt-2">
              <p class="text-muted mb-0">
                Search results for: <strong>"<?= htmlspecialchars($_GET['search']) ?>"</strong>
                <a href="index?page=shop" class="btn btn-sm btn-outline-secondary ms-2">Clear Search</a>
              </p>
            </div>
          <?php endif; ?>
        </div>
        
        <div class="d-flex justify-content-between mb-4 pb-md-2">
         

          <div class="shop-acs d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
          

            <div class="shop-asc__seprator mx-3 bg-light d-none d-md-block order-md-0"></div>

            <div class="shop-filter d-flex align-items-center order-0 order-md-3 d-lg-none">
              <button class="btn-link btn-link_f d-flex align-items-center ps-0 js-open-aside" data-aside="shopFilter">
                <svg class="d-inline-block align-middle me-2" width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_filter" /></svg>
              <span class="text-uppercase fw-medium d-inline-block align-middle">Filter</span>
              </button>
            </div><!-- /.col-size d-flex align-items-center ms-auto ms-md-3 -->
          </div><!-- /.shop-acs -->
        </div><!-- /.d-flex justify-content-between -->

        <div class="products-grid row row-cols-3 row-cols-md-3" id="products-grid" >
        <?php 
          if(count($listofitems) > 0) {
             $status = [
                  "A" => '<span class="border-0 fw-semi-bold text-uppercase theme-bg-color-secondary border-radius-8 p-1 text-primary">AVAILABLE</span>',
                  "C" => '<span class="border-0 fw-semi-bold text-uppercase theme-bg-color border-radius-8 p-1 " style="color:white">OUT OF STOCK</span>',
              ];
            foreach ($listofitems as $key => $value) {
                $s  = ($value["total_in"] - $value["total_sold"]) > 0 ? '<span class="border-0 fw-semi-bold text-uppercase theme-bg-color-secondary border-radius-8 p-1 text-primary">Stock: '.($value["total_in"] - $value["total_sold"]) .'</span>' :$status["C"];
            ?>     
              <div class="product-card-wrapper">
                <div class="product-card mb-3 mb-md-4 mb-xxl-5">
                  <div class="pc__img-wrapper">
                    <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                      <div class="swiper-wrapper">
                        <div class="swiper-slide">
                          <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>"><img loading="lazy" src="<?=$_ENV['URL_HOST'].$value["img_400x541_shop"]?>" width="330" height="400" alt="Cropped Faux leather Jacket" class="pc__img"></a>
                        </div><!-- /.pc__img-wrapper -->
                        <div class="swiper-slide">
                          <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>"><img loading="lazy" src="<?=$_ENV['URL_HOST'].$value["img_400x541_shop"]?>" width="330" height="400" alt="Cropped Faux leather Jacket" class="pc__img"></a>
                        </div><!-- /.pc__img-wrapper -->
                      </div>
                      <span class="pc__img-prev"><svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_sm" /></svg></span>
                      <span class="pc__img-next"><svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_sm" /></svg></span>
                    </div>
                    <button class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium " >
                      <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>">
                      VIEW
                      </a>
                    </button>
                  </div>

                  <div class="pc__info position-relative">
                    <p class="pc__category"><?=ucfirst($value["brand_name"])?></p>
                    <h6 class="pc__title"><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$value["item_id"]?>"><?=($value["item_name"])?></a></h6>
         
                   
          
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



          

          
        </div><!-- /.products-grid row -->

        <nav class="shop-pages d-flex justify-content-between mt-3" aria-label="Page navigation">
          <a href="#" class="btn-link d-inline-flex align-items-center">
            <svg class="me-1" width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_sm" /></svg>
            <span class="fw-medium">PREV</span>
          </a>
          <ul class="pagination mb-0">
            <li class="page-item"><a class="btn-link px-1 mx-2 btn-link_active" href="#">1</a></li>
            <li class="page-item"><a class="btn-link px-1 mx-2" href="#">2</a></li>
            <li class="page-item"><a class="btn-link px-1 mx-2" href="#">3</a></li>
            <li class="page-item"><a class="btn-link px-1 mx-2" href="#">4</a></li>
          </ul>
          <a href="#" class="btn-link d-inline-flex align-items-center">
            <span class="fw-medium me-1">NEXT</span>
            <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_sm" /></svg>
          </a>
        </nav>
      </div>
    </section><!-- /.shop-main container -->
  </main>