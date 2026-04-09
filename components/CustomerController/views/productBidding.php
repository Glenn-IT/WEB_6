<main>
    <div class="mb-md-1 pb-md-3"></div>
    <section class="product-single container">
      <div class="row">
        <div class="col-lg-7">
          <div class="product-single__media" data-media-type="vertical-thumbnail">
            <div class="product-single__image">
              <div class="swiper-container">
                <div class="swiper-wrapper">
                  
                  <?php 
                    $img = explode('|',$custome["img_700x700_product_details"]);

                    for ($i=0; $i < 4; $i++) { 
                      if(isset($img[$i])) {

                      ?>
                      <div class="swiper-slide product-single__image-item">
                        <img loading="lazy" class="h-auto" src="<?=$_ENV['URL_HOST'].(isset($img[$i]) ? $img[$i] : '')?>" width="674" height="674" alt="">
                      </div>
                      <?php
                      }

                    }
                  ?>
                  
                </div>
                <div class="swiper-button-prev"><svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_sm" /></svg></div>
                <div class="swiper-button-next"><svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_sm" /></svg></div>
              </div>
            </div>
            <div class="product-single__thumbnail">
              <div class="swiper-container">
                <div class="swiper-wrapper">
                <?php 

                  for ($i=0; $i < 4; $i++) { 
                    if(isset($img[$i])) {

                    ?>
                    <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto" src="<?=$_ENV['URL_HOST'].$img[$i]?>" width="104" height="104" alt=""></div>
                    <?php
                    }

                  }
                ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="d-flex justify-content-between mb-4 pb-md-2">
            
            
          </div>
          <h1 class="product-single__name"><?=$custome["item_name"]?></h1>
        
          <div class="product-single__price">
            <span class="current-price">Starting Bid: &#8369;<?=number_format($custome["start_amount"],2)?></span><br>

            <span class="current-price money price" style="color:green">Highest Bid: &#8369;<?=number_format($custome["max_quantity"],2)?></span><br>

            <?php 
              $amount = ($custome["max_quantity"] == 0) ? $custome["start_amount"]: $custome["max_quantity"];
              $min = $custome["increment_value"] +  $amount;
            ?>
            <span class="current-price money price" style="color:red">Increment Value: &#8369;<?=number_format($custome["increment_value"],2)?></span><br>

          </div>
          
          <div class="product-single__short-desc">
            <p><?=$custome["item_description"]?></p>
          </div>
          <form action="afterSubmit" method="post" id="myProductinBidding">
            <input type="hidden" name="method" value="processProductBidding">
            <input type="hidden" name="bidding_id" value="<?=$custome["bidding_id"]?>">
            <input type="hidden" name="user_id" value="<?=isset($_SESSION["user_id"])?$_SESSION["user_id"]:''?>">
            <input type="hidden" name="amount_greter" value="<?=$min?>">

            
           
            <div class="product-single__addtocart">
              <div class="qty-control position-relative" style="min-width: 16.25rem;">
                <input type="number" name="quantity" value="<?=$min?>" data-min="<?=$min?>"  class="qty-control__number text-center">
                <div class="qty-control__reduce" style="display: none;">-</div>
                <div class="qty-control__increase" style="display: none;">+</div>
              </div><!-- .qty-control -->
              <button type="button" class="btn btn-primary btn-addtocart"  >VIEW</button>
            </div>
          </form>
        

          
          <span class="text-primary">Sort by the highest Bid</span>
          <table class="table ">
              <thead>
              <tr>
                  <th scope="col">Date Bid</th>
                  <th scope="col">Customer Name</th>
                  <th scope="col">Bid Amount</th>
              </tr>
              </thead>
              <tbody>
              <?php 
                  if(count($list) > 0) {
                      foreach ($list as $key => $value) {
                          echo "<tr>";
                          echo "<td>".$value["bid_date"]."</td>";
                          echo "<td>".$value["obfuscated_column"]."</td>";
                          echo "<td>".number_format($value["quantity"],2)."</td>";
                          echo "</tr>";

                      }
                  }
              ?>
              </tbody>
          </table>
        
        </div>
      </div>
   
    </section>
    <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>

    <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>

    <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
  </main>