<section class="full-width_padding" style="background-color: #faf9f8">
    <div class="shop-categories position-relative">
    <h2 class="h3 pb-3 mb-4 fw-normal text-uppercase text-center">OUR SERVICES</h2>

    <div class="shop-categories__list d-flex align-items-center flex-wrap justify-content-center">
       
    
    
    
        <?php 
            if(count($listofserveice) > 0) {
                foreach ($listofserveice as $key => $value) {
                    $input = ucfirst($value["item_name"]);
                    $parts = explode(" ", $input);
                    $output = implode("<br>", $parts);
                ?>     
                <a href="#" class="shop-categories__item mb-3">
                <img loading="lazy" src="<?=$_ENV['URL_HOST'].$value["img_255x200_home"]?>" width="120" height="120" alt="<?=ucfirst($value["item_name"])?>" class="shop-categories__item-img rounded-circle">
                <h6 class="pt-1 mt-3 mt-xl-4 mb-0 text-center"><?=$output?></h6>
                </a>
                <?php
                }
            }
        ?>
       
    </div>
    </div><!-- /.shop-categories position-relative -->
</section><!-- /.full-width_padding-->


<section class="full-width_padding" style="background-color: #faf9f8;">
    <div class="position-relative">

    <div class="flex-wrap justify-content-center">
       

     <?php 
        if(count($listofserveice) > 0) {
            foreach ($listofserveice as $key => $value) {
                $input = ucfirst($value["item_name"]);
                $parts = explode(" ", $input);
                $output = implode("<br>", $parts);
            ?>     
            <div class="card border-secondary mb-3" >
                <div class="card-header"><?=ucfirst($value["item_name"])?></div>
                <div class="card-body text-secondary">
                    <h5 class="card-title"><?=ucfirst($value["item_description"])?></h5>
                    <p class="card-text" style="color:green">&#8369;<?=number_format($value["price"],2)?></p>
                </div>
                <button type="button" class="btn btn-outline-secondary">BOOK NOW!</button>
            </div>
            <?php
            }
        }
    ?>
       
       
    </div>
    </div><!-- /.shop-categories position-relative -->
</section><!-- /.full-width_padding-->

<div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
<div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
<div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
<div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
<div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
<div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>
<div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div> 