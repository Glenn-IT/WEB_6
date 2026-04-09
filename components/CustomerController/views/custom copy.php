


 <div class="mb-3 mb-xl-2 pb-3 pt-1 pb-xl-2"></div>


 <section class="featured-products container">
    <div class="d-flex align-items-center justify-content-md-between flex-wrap mb-3 mb-xl-4">
      <h2 class="section-title fw-semi-bold fs-30 theme-color text-uppercase">New Arrivals</h2>

    </div>

    <div class="tab-content pt-2" id="collections-tab-content">
      <div class="tab-pane fade show active" id="collections-tab-1" role="tabpanel" aria-labelledby="collections-tab-1-trigger">
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5">
             
          <?php 
          if(count($listofitems) > 0) {
              $status = [
                  "A" => '<span class="border-0 fw-semi-bold text-uppercase theme-bg-color-secondary border-radius-8 p-1 text-primary">AVAILABLE</span>',
                  "C" => '<span class="border-0 fw-semi-bold text-uppercase theme-bg-color border-radius-8 p-1 " style="color:white">OUT OF STOCK</span>',
              ];
            foreach ($listofitems as $key => $value) {
                $s  = ($value["total_in"] - $value["total_sold"]) > 0 ? '<span class="border-0 fw-semi-bold text-uppercase theme-bg-color-secondary border-radius-8 p-1 text-primary">Stock: '.($value["total_in"] - $value["total_sold"]) .'</span>' :$status["C"];

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
                  <p class=" "><?=$s?></p>

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
