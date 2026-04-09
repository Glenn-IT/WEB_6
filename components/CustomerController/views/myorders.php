<main>
    <div class="mb-4 pb-4"></div>

    <?php 

      $view_list = [
        'Cart'=> [ "title" => 'Cart', 'active' => 'menu-link_active', ],
        'Messages'=> [ "title" => 'Messages', 'active' => 'menu-link_active', ],
        'Bidding'=> [ "title" => 'Booking', 'active' => 'menu-link_active', ],
        'Orders'=> [ "title" => 'Orders', 'active' => 'menu-link_active', ],
        'Addresses'=> [ "title" => 'Addresses', 'active' => 'menu-link_active', ],
        'Account'=> [ "title" => 'Account Details', 'active' => 'menu-link_active', ],
        'notification'=> [ "title" => 'Notitfication', 'active' => 'menu-link_active', ],

      ];
    
    ?>

    <section class="my-account container">
      <h2 class="page-title"><?=$view_list[$_GET["view"]]['title']?></h2>
      <div class="row">
        <div class="col-lg-3">
          <ul class="account-nav">
            <li><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=myorders&view=notification'?>" class="menu-link menu-link_us-s <?= ($_GET["view"]=="notification") ?  $view_list[$_GET["view"]]['active'] :''?> ">Notitfication</a></li>
            <li><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=myorders&view=Cart'?>" class="menu-link menu-link_us-s <?= ($_GET["view"]=="Cart") ?  $view_list[$_GET["view"]]['active'] :''?> ">Cart</a></li>
            <li><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=myorders&view=Messages'?>" class="menu-link menu-link_us-s <?= ($_GET["view"]=="Messages") ?  $view_list[$_GET["view"]]['active'] :''?> ">Messages</a></li>
            <li><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=myorders&view=Bidding'?>" class="menu-link menu-link_us-s <?= ($_GET["view"]=="Bidding") ?  $view_list[$_GET["view"]]['active'] :''?> ">Booking</a></li>
            <!-- <li><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=myorders&view=Orders'?>" class="menu-link menu-link_us-s <?= ($_GET["view"]=="Orders") ?  $view_list[$_GET["view"]]['active'] :''?> ">Orders</a></li> -->
            <li><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=myorders&view=Account'?>" class="menu-link menu-link_us-s <?= ($_GET["view"]=="Account") ?  $view_list[$_GET["view"]]['active'] :''?> ">Account Details</a></li>
            <?php 
            if(isset($_SESSION["user_id"])) {
              ?>
              <li><a href="<?=$_ENV['URL_HOST'].'userLogout'?>" class="menu-link menu-link_us-s">Logout</a></li>
              <?php
            }
            ?>
          </ul>
        </div>
        <div class="col-lg-9">
          <?=isset($view["html"])?$view["html"]:''?>
     
        </div>
      </div>
    </section>
  </main>

  <div class="mb-5 pb-xl-5"></div>
