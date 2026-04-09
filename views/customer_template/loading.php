
<style>
/* Login lockout styles */
.lockout-mode {
    opacity: 0.6;
    position: relative;
}

.lockout-mode::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.3);
    pointer-events: none;
    z-index: 1;
}

.lockout-mode input {
    color: #999 !important;
    background-color: #f5f5f5 !important;
    cursor: not-allowed !important;
}

.login_form.locked {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    cursor: not-allowed !important;
    opacity: 0.8;
}

.login_form.locked:hover {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
}

.lockout-countdown {
    font-size: 0.875rem;
    text-align: center;
    border-radius: 6px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}
</style>


  <div class="mt-3 mt-xl-4 pb-3 pt-1 pb-xl-4"></div>

  <!-- Footer Type 1 -->
  <footer class="footer footer_type_1 dark theme-bg-color">
    <div class="footer-bottom container">
      <div style=" text-align: center; ">
        <span>©2024 <?=$_ENV['APP_NAME']?></span> <br>
        <span><?=$_ENV['APP_ADDRESS']?></span>
        
      </div><!-- /.d-flex -->
    </div><!-- /.footer-bottom container -->
  </footer><!-- /.footer footer_type_1 -->
  <!-- End Footer Type 1 -->

 

  <!-- Customer Login Form -->
  <div class="aside aside_right overflow-hidden customer-forms" id="customerForms">
      <div class="customer__login">
        <div class="aside-header d-flex align-items-center">
          <h3 class="text-uppercase fs-6 mb-0"><?=isset($_SESSION["user_id"]) ? 'My Profile' :'Logins' ?></h3>
          <button class="btn-close-lg js-close-aside ms-auto"></button>
        </div><!-- /.aside-header -->


        <?php 
          if(isset($_SESSION["user_id"])) {
            ?>
              <button class="btn btn-primary w-100 text-uppercase" type="button"><a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=myorders&view=Account'?>" style="color:white">Profile</a></button>
              <hr>
              <button class="btn btn-primary w-100 text-uppercase" type="button"><a href="<?=$_ENV['URL_HOST'].'userLogout'?>" style="color:white">Logout</a></button>
            
            <?php
          } else {
            ?>
            <form action="<?=$_ENV['URL_HOST'].'auth' ?>" method="POST" id="login_form" class="aside-content">
              <input type="hidden" name="login_type" value="customer">
              <input type="hidden" name="source" value="api">

              <div class="form-floating mb-3">
                <input name="email" type="text" id="customerNameEmailInput" class="form-control form-control_gray" >
                <label for="customerNameEmailInput">Username or email address *</label>
              </div>
    
              <div class="pb-3"></div>
    
              <div class="form-label-fixed mb-3">
                <label for="customerPasswordInput" class="form-label">Password *</label>
                <input name="password" id="customerPasswordInput" class="form-control form-control_gray" type="password" placeholder="********">
              </div>


              <div class="d-flex align-items-center ">
                <div class="form-check mb-0">
                  <input name="remember" class="form-check-input form-check-input_fill" type="checkbox" value="" id="flexCheckDefault2">
                  <label class="form-check-label text-secondary" for="flexCheckDefault2">Show Password</label>
                </div>
              </div>
    
              <div class="d-flex align-items-center mb-3 pb-2">
                <div class="form-check mb-0">
                  <input name="remember" class="form-check-input form-check-input_fill" type="checkbox" value="" id="flexCheckDefault">
                  <label class="form-check-label text-secondary" for="flexCheckDefault">Remember me</label>
                </div>
                <a href="<?=$_ENV['URL_HOST'].'forgot_password' ?>" class="btn-text ms-auto">Forgot Password?</a>
              </div>
    
              <button class="btn btn-primary w-100 text-uppercase login_form" type="button">Log In</button>
    
              <div class="customer-option mt-4 text-center">
                <span class="text-secondary">No account yet?</span>
                <a href="<?=$_ENV['URL_HOST'].'customer/customer/index?page=register' ?>" class="btn-text js-show-register">Create Account</a>
              </div>
            </form>

            <?php
          }
        ?>
        
      </div><!-- /.customer__login -->

  </div><!-- /.aside aside_right -->

  <!-- Success Modal for Customer -->
  <div class="modal fade" id="customerPasswordResetSuccessModal" tabindex="-1" aria-labelledby="customerPasswordResetSuccessModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; border-bottom: none;">
                  <h5 class="modal-title" id="customerPasswordResetSuccessModalLabel">
                      <i class="fa fa-check-circle" style="margin-right: 10px;"></i>
                      Password Reset Successful
                  </h5>
              </div>
              <div class="modal-body text-center" style="padding: 40px 30px;">
                  <div style="margin-bottom: 25px;">
                      <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #28a745, #20c997); margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                          <i class="fa fa-check" style="font-size: 2.5rem; color: white;"></i>
                      </div>
                  </div>
                  <h4 style="color: #333; margin-bottom: 20px; font-weight: 600;">Password Updated Successfully!</h4>
                  <p style="color: #666; font-size: 16px; margin-bottom: 25px; line-height: 1.5;">
                      Your password has been changed successfully. You can now login to your account using your new password.
                  </p>
                  <div style="background: linear-gradient(135deg, #d4edda, #d1ecf1); border: 1px solid #bee5eb; border-radius: 10px; padding: 20px; margin-bottom: 25px;">
                      <strong style="color: #0c5460;">
                          <i class="fa fa-info-circle" style="margin-right: 8px;"></i>
                          Please use your new password to access your account
                      </strong>
                  </div>
              </div>
              <div class="modal-footer" style="border-top: none; padding: 30px; text-align: center;">
                  <button type="button" class="btn btn-success btn-lg" data-bs-dismiss="modal" style="min-width: 150px; border-radius: 25px; padding: 12px 30px;">
                      <i class="fa fa-thumbs-up" style="margin-right: 10px;"></i>
                      Perfect!
                  </button>
              </div>
          </div>
      </div>
  </div>

  <script>
  // Check for password reset success modal on customer page
  document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const showModal = urlParams.get('show_modal');
      const type = urlParams.get('type');
      
      if (showModal === 'true' && type === 'success') {
          // Show the modal
          const modal = new bootstrap.Modal(document.getElementById('customerPasswordResetSuccessModal'));
          modal.show();
          
          // Clear the URL parameters after showing modal
          const newUrl = window.location.pathname;
          window.history.replaceState({}, document.title, newUrl);
      }
  });
  </script>

