<style>.big-check {
  width: 25px;
  height: 25px;
}</style>

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
            <span class="current-price">Unit Price: &#8369;<?=number_format($custome["price"],2)?></span><br>
          </div>
          
          <div class="product-single__short-desc">
            <p><?=$custome["item_description"]?></p>
          </div>

          <form action="afterSubmit" method="post" id="myProductinBidding">

          <table class="table ">
              <thead>
              <tr>
                  <th scope="col"></th>
                  <th scope="col">No. of Hours</th>
                  <th scope="col">Description</th>
                  <th scope="col">Unit Cost</th>
              </tr>
              </thead>
              <tbody>
              <?php 
                  if(count($list) > 0) {
                      foreach ($list as $key => $value) {
                          echo "<tr>";
                          echo '<td>
                                      <input class="form-check-input big-check financing_check" type="checkbox" id="exampleCheck'.$value["id"].'" name="item_finance" value="'.$value["id"].'">
                                    </td>';
                          echo "<td>".$value["months"]."</td>";
                          echo "<td>".$value["description"]."</td>";
                          echo "<td>".number_format($value["amount"],2)."</td>";
                          echo "</tr>";

                      }
                  }
              ?>
              </tbody>
          </table>

          

            <input type="hidden" name="method" value="processProductItem">
            <input type="hidden" name="item_id" value="<?=$custome["item_id"]?>">
            <input type="hidden" name="user_id" value="<?=isset($_SESSION["user_id"])?$_SESSION["user_id"]:''?>">
            <input type="hidden" name="order_no" value="<?=rand(1, 999999);?>">

            <input type="hidden" name="quantity" value="1">

            <input type="hidden" name="type_order" value="SELL" >
          
         

           <div class="product-single__addtocart">
              <button type="submit" class="btn btn-success btn-addtocart " name="name_submit" value="I" >INQUIRE NOW!</button>
              <button type="submit" class="btn btn-danger btn-addtocart " name="name_submit" value="C">Add to Cart</button>
              <!-- <button type="button" class="btn btn-primary btn-addtocart  " name="name_submit" data-aside="cartDrawer">BOOKNOW</button> -->
          </div>
        
          <div class="product-single__meta-info">
            <div class="meta-item">
              <label>Category:</label>
              <span><?=$custome["brand_name"]?></span>
            </div>
        
          </div>


            </form>
            
           </div>   

        </div>

      </div>
      <div class="mb-md-5 pb-md-3"></div>
   
      <div class="mb-md-5 pb-md-3"></div>

      <div class="mb-md-5 pb-md-3"></div>

    </section>
   
  </main>