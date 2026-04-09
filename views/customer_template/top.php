  <!-- Mobile Header -->
  <div class="header-mobile header_sticky">
    <div class="container d-flex align-items-center h-100">
      <a class="mobile-nav-activator d-block position-relative" href="#">
        <svg class="nav-icon" width="25" height="18" viewBox="0 0 25 18" xmlns="http://www.w3.org/2000/svg"><use href="#icon_nav" /></svg>
        <span class="btn-close-lg position-absolute top-0 start-0 w-100"></span>
      </a>

      <div class="logo">
        <a href="">
          <!-- <img src="../images/logo.png" alt="Uomo" class="logo__image d-block"> -->
        </a>
      </div><!-- /.logo -->

      <a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=myorders&view=Cart" class="header-tools__item header-tools__cart " data-aside="cartDrawer">
        <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_cart" /></svg>
      </a>

       <div class="header-tools__item hover-container">
        <a class="header-tools__item js-open-aside" href="#" data-aside="customerForms">
          <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_user" /></svg>
        </a>
      </div>
    </div><!-- /.container -->

    <nav class="header-mobile__navigation navigation d-flex flex-column w-100 position-absolute top-100 bg-body overflow-auto">
      <div class="container">
        <div class="overflow-hidden">
          <ul class="navigation__list list-unstyled position-relative">
            <li class="navigation__item">
              <a href="<?=$_ENV['URL_HOST']?>customer/customer/index" class="navigation__link  d-flex align-items-center">Home<svg class="ms-auto" width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_sm" /></svg></a>
            </li>
            <li class="navigation__item">
              <a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=shop" class="navigation__link  d-flex align-items-center">Shop<svg class="ms-auto" width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_sm" /></svg></a>
            </li>
            <li class="navigation__item">
              <a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=search" class="navigation__link  d-flex align-items-center">Search<svg class="ms-auto" width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_sm" /></svg></a>
            </li>
            <li class="navigation__item">
              <a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=about" class="navigation__link  d-flex align-items-center">About Us<svg class="ms-auto" width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_sm" /></svg></a>
            </li>


            <li class="navigation__item">
              <a href="#" class="navigation__link js-nav-right d-flex align-items-center">My Profile<svg class="ms-auto" width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_sm" /></svg></a>
              <div class="sub-menu position-absolute top-0 start-100 w-100 d-none">
                <a href="#" class="navigation__link js-nav-left d-flex align-items-center border-bottom mb-2"><svg class="me-2" width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_sm" /></svg>Pages</a>
                <ul class="list-unstyled">
                  <li class="sub-menu__item"><a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=myorders&view=Account" class="menu-link menu-link_us-s">My Account</a></li>
                  <li class="sub-menu__item"><a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=myorders&view=notification" class="menu-link menu-link_us-s">Notification</a></li>
                  <li class="sub-menu__item"><a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=myorders&view=Cart" class="menu-link menu-link_us-s">Cart</a></li>
                  <li class="sub-menu__item"><a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=myorders&view=Messages" class="menu-link menu-link_us-s">Messages</a></li>
                  <li class="sub-menu__item"><a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=myorders&view=Orders" class="menu-link menu-link_us-s">Orders</a></li>
                   <?php 
                    if(isset($_SESSION["user_id"])) {
                      ?>
                      <li class="sub-menu__item"><a href="<?=$_ENV['URL_HOST']?>userLogout" class="menu-link menu-link_us-s">Logout</a></li>
                      <?php
                    } else {
                      ?>
                      <li class="sub-menu__item"><a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=myorders&view=Bidding" class="menu-link menu-link_us-s">Bidding</a></li>
                      <?php
                    }
                    ?>

              
                </ul>
              </div>
            </li>

       
          </ul><!-- /.navigation__list -->
        </div><!-- /.overflow-hidden -->
      </div><!-- /.container -->

     
    </nav><!-- /.navigation -->
  </div><!-- /.header-mobile -->
  
  
  <!-- Header Type 6 -->
  <header id="header" class="header sticky_disabled header_sticky-bg_dark w-100 theme-bg-color">
   
    <div class="header-desk_type_6 style2">
      <div class="header-middle border-0 position-relative py-4">
        <div class="container d-flex align-items-center">
          <div class="logo">
            <a href="<?=$_ENV['URL_HOST']?>customer/customer/index">
              <!-- <img src="<?=$_ENV['URL_HOST']?>public/customer_template/images/logo_ash-removebg-preview.png" alt="Uomo" class="logo__image"> -->
            </a>
          </div><!-- /.logo -->

          <nav class="navigation flex-grow-1 fs-15 fw-semi-bold">
            <ul class="navigation__list list-unstyled d-flex">

              <li class="navigation__item">
                <a href="<?=$_ENV['URL_HOST']?>customer/customer/index" class="navigation__link">Home</a>
              </li>


              <!-- <li class="navigation__item">
                <a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=shop" class="navigation__link">Shop</a>
              </li> -->


              <li class="navigation__item">
                <a href="#" class="navigation__link">Services</a>
                <ul class="default-menu list-unstyled">
                  <?php 
                  foreach ($header_services as $key => $value) {
                    ?>
                    <li class="sub-menu__item"><a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=specificshop&type=<?=$value["name"]?>" class="menu-link menu-link_us-s"><?=$value["name"]?></a></li>
                    <?php
                  }
                  ?>
                </ul><!-- /.box-menu -->
              </li>



               <li class="navigation__item">
                <a href="#" class="navigation__link">About</a>
                <ul class="default-menu list-unstyled">
                  <li class="sub-menu__item"><a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=about" class="menu-link menu-link_us-s">Touch and Care Massage and Spa</a></li>
                
                </ul><!-- /.box-menu -->
              </li>

              <!-- <li class="navigation__item">
                <a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=faq" class="navigation__link">FAQs</a>
              </li> -->
             
              <li class="navigation__item">
                <a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=myorders&view=Account" class="navigation__link">My Profile</a>
              </li>

              <li class="navigation__item">
                <a href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=myorders&view=Bidding" class="navigation__link">Booking History</a>
              </li>
             
            </ul><!-- /.navigation__list -->
          </nav><!-- /.navigation -->

          <div class="header-tools d-flex align-items-center me-0">
            <div class="header-tools__item text-white d-none d-xxl-block">
              <span class="fs-15 "><?=isset($_SESSION["username"]) ? 'Hello! ' . $_SESSION["username"] : ''?></span>
            </div>

            <!-- Search Icon -->
            <a class="header-tools__item" href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=search" title="Search">
              <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.7549 14.255L12.6062 11.106C13.6463 9.92231 14.253 8.38843 14.253 6.7265C14.253 3.02319 11.2298 0 7.5265 0C3.82319 0 0.799988 3.02319 0.799988 6.7265C0.799988 10.4298 3.82319 13.453 7.5265 13.453C9.18843 13.453 10.7223 12.8463 11.906 11.8062L15.0547 14.9549C15.1603 15.0605 15.3026 15.1133 15.4449 15.1133C15.5872 15.1133 15.7295 15.0605 15.8351 14.9549C16.0463 14.7437 16.0463 14.4663 15.7549 14.255ZM7.5265 11.9531C4.6508 11.9531 2.29999 9.60229 2.29999 6.7265C2.29999 3.85071 4.6508 1.5 7.5265 1.5C10.4023 1.5 12.753 3.85071 12.753 6.7265C12.753 9.60229 10.4023 11.9531 7.5265 11.9531Z" fill="currentColor"/>
              </svg>
            </a>

            <a class="header-tools__item" href="<?=$_ENV['URL_HOST']?>customer/customer/index?page=myorders&view=notification">
              <i class="fas fa-bell" style="font-size: 24px;"></i>
            </a>

            <div class="header-tools__item hover-container">
              <a class="header-tools__item js-open-aside" href="#" data-aside="customerForms">
                <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_user" /></svg>
              </a>
            </div>
            
    
    
        
          </div><!-- /.header__tools -->
        </div>
      </div><!-- /.header-middle -->

    </div><!-- /.header-desk header-desk_type_6 -->
  </header><!-- End Header Type 6 -->
