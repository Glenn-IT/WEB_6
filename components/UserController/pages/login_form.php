<!DOCTYPE html>
<html lang="en">

<head>
    <title><?=$_ENV['APP_NAME']?> | Login</title>
 
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="description" content="<?=$_ENV['URL_HOST']?>" />
      <meta name="keywords" content="<?=$_ENV['URL_HOST']?>" />
      <meta name="author" content="<?=$_ENV['URL_HOST']?>" />

      <link rel="icon" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/images/favicon.ico" type="image/x-icon">
      <!-- Google font-->     
      <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
      <!-- Required Fremwork -->
      <link rel="stylesheet" type="text/css" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/css/bootstrap/css/bootstrap.min.css">
      <!-- waves.css -->
      <link rel="stylesheet" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/pages/waves/css/waves.min.css" type="text/css" media="all">
      <!-- themify-icons line icon -->
      <link rel="stylesheet" type="text/css" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/icon/themify-icons/themify-icons.css">
      <!-- ico font -->
      <link rel="stylesheet" type="text/css" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/icon/icofont/css/icofont.css">
      <!-- Font Awesome -->
      <link rel="stylesheet" type="text/css" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/icon/font-awesome/css/font-awesome.min.css">
      <!-- Style.css -->
      <link rel="stylesheet" type="text/css" href="<?=$_ENV['URL_HOST']?>public/admin_template/assets/css/style.css">
      <style>
         .warning{
            padding:20px;
            margin: 20px;
            border-radius: 3%;
            background-color: #FF4B2B;
            color: white;
        }

        .success{
            padding:20px;
            margin: 20px;
            border-radius: 3%;
            background-color: #0fb90d;
            color: white;
        }
        
        /* Login lockout styles for admin */
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

        .admin-login-form.locked {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            cursor: not-allowed !important;
            opacity: 0.8;
        }

        .admin-login-form.locked:hover {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
        }

        .lockout-countdown {
            font-size: 0.875rem;
            text-align: center;
            border-radius: 6px;
            animation: pulse 2s infinite;
            padding: 10px;
            margin: 10px 0;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        /* Modal animations */
        .modal.fade .modal-dialog {
            transform: translateY(-50px);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }

        .success-icon-animation {
            animation: successBounce 0.6s ease-in-out;
        }

        @keyframes successBounce {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
      </style>
  </head>

  <body themebg-pattern="theme1">


  <!-- Pre-loader start -->
  <div class="theme-loader">
      <div class="loader-track">
          <div class="preloader-wrapper">
              <div class="spinner-layer spinner-blue">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
              <div class="spinner-layer spinner-red">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
            
              <div class="spinner-layer spinner-yellow">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
            
              <div class="spinner-layer spinner-green">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
          </div>
      </div>
  </div>


  <!-- Pre-loader end -->
  <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                    
                        <form action="<?=$_ENV['URL_HOST'].'auth' ?>" method="POST" class="md-float-material form-material" id="admin_login_form">
                            <div class="text-center">
                                <!-- <img src="<?=$_ENV['URL_HOST']?>public/admin_template/assets/images/logo.png" alt="logo.png"> -->
                            </div>
                            <div class="auth-box card">
                                <div class="card-block">
                                    <div class="row m-b-20">
                                        <div class="col-md-12">
                                            <h3 class="text-center">Sign In</h3>
                                        </div>
                                    </div>
                                    <div class="form-group form-primary">
                                        <input type="text" name="email" id="admin_email" class="form-control" required="">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Your Email Address</label>
                                    </div>
                                    <div class="form-group form-primary" style="position: relative;">
                                        <input type="password" name="password" id="admin_password" class="form-control" required="">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Password</label>
                                        <span id="togglePassword" style="position: absolute; right: 10px; top: 15px; cursor: pointer; color: #666; font-size: 18px;">
                                            <i class="fa fa-eye" id="eyeIcon"></i>
                                        </span>
                                    </div>
                                    <div class="row m-t-25 text-left">
                                        <div class="col-12">
                                            <div class="checkbox-fade fade-in-primary d-">
                                                <label>
                                                    <input type="checkbox" value="">
                                                    <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                                    <span class="text-inverse">Remember me</span>
                                                </label>
                                            </div>
                                            <div class="forgot-phone text-right f-right">
                                                <a href="<?=$_ENV['URL_HOST'].'forgot_password' ?>" class="text-right f-w-600"> Forgot Password?</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-t-30">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20 admin-login-form">Sign in</button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="boxings <?=isset($_GET["type"])?$_GET["type"]:''?>">
                                        <?=isset($_GET["message"])?$_GET["message"]:''?>
                                    </div>
                                  
                                </div>
                            </div>
                        </form>
                        <!-- end of form -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>

    <!-- Success Modal -->
    <div class="modal fade" id="passwordResetSuccessModal" tabindex="-1" role="dialog" aria-labelledby="passwordResetSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #28a745; color: white; border-bottom: none;">
                    <h5 class="modal-title" id="passwordResetSuccessModalLabel">
                        <i class="fa fa-check-circle" style="margin-right: 10px;"></i>
                        Password Reset Successful
                    </h5>
                </div>
                <div class="modal-body text-center" style="padding: 30px;">
                    <div style="margin-bottom: 20px;">
                        <i class="fa fa-check-circle success-icon-animation" style="font-size: 4rem; color: #28a745;"></i>
                    </div>
                    <h4 style="color: #333; margin-bottom: 15px;">Success!</h4>
                    <p style="color: #666; font-size: 16px; margin-bottom: 20px;">
                        Your password has been changed successfully. You can now login with your new password.
                    </p>
                    <div style="background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                        <strong style="color: #155724;">
                            <i class="fa fa-info-circle" style="margin-right: 5px;"></i>
                            Please use your new password to login
                        </strong>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; padding: 20px; text-align: center;">
                    <button type="button" class="btn btn-success btn-lg" data-dismiss="modal" style="min-width: 120px;">
                        <i class="fa fa-thumbs-up" style="margin-right: 8px;"></i>
                        Got it!
                    </button>
                </div>
            </div>
        </div>
    </div>
  

    <script type="text/javascript" src="<?=$_ENV['URL_HOST']?>public/admin_template/assets/js/jquery/jquery.min.js"></script>     <script type="text/javascript" src="<?=$_ENV['URL_HOST']?>public/admin_template/assets/js/jquery-ui/jquery-ui.min.js "></script>     <script type="text/javascript" src="<?=$_ENV['URL_HOST']?>public/admin_template/assets/js/popper.js/popper.min.js"></script>     <script type="text/javascript" src="<?=$_ENV['URL_HOST']?>public/admin_template/assets/js/bootstrap/js/bootstrap.min.js "></script>
    <script src="<?=$_ENV['URL_HOST']?>public/admin_template/assets/pages/waves/js/waves.min.js"></script>
    <script type="text/javascript" src="<?=$_ENV['URL_HOST']?>public/admin_template/assets/js/jquery-slimscroll/jquery.slimscroll.js "></script>
    <script type="text/javascript" src="<?=$_ENV['URL_HOST']?>public/admin_template/assets/js/SmoothScroll.js"></script>     <script src="<?=$_ENV['URL_HOST']?>public/admin_template/assets/js/jquery.mCustomScrollbar.concat.min.js "></script>
    <script type="text/javascript" src="bower_components/i18next/js/i18next.min.js"></script>
    <script type="text/javascript" src="bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js"></script>
    <script type="text/javascript" src="bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src="bower_components/jquery-i18next/js/jquery-i18next.min.js"></script>
    <script type="text/javascript" src="<?=$_ENV['URL_HOST']?>public/admin_template/assets/js/common-pages.js"></script>
    
    <!-- Password Toggle Script -->
    <script>
        // Set base URL for redirects
        const BASE_URL = '<?=$_ENV['URL_HOST']?>';
        
        $(document).ready(function() {
            // Check for password reset success modal
            checkPasswordResetSuccess();
            
            // Password toggle functionality
            $('#togglePassword').on('click', function() {
                const passwordField = $('#admin_password');
                const eyeIcon = $('#eyeIcon');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Admin login form submission with lockout functionality
            $('.admin-login-form').on('click', function(e) {
                e.preventDefault();
                
                // Check if form is currently locked
                if ($(this).hasClass('locked')) {
                    return false;
                }
                
                // Check if already processing
                if ($(this).prop('disabled')) {
                    return false;
                }
                
                var form = $('#admin_login_form')[0];
                var action = $(form).attr('action');
                var formData = new FormData(form);
                
                // Add source parameter to indicate AJAX request
                formData.append('source', 'ajax');
                
                var loginButton = $(this);
                var originalText = loginButton.text();
                
                // Show loading state
                loginButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Signing in...');
                
                $.ajax({
                    url: action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'text', // Changed from 'json' to 'text' to handle both JSON and HTML responses
                    timeout: 15000, // 15 second timeout for the AJAX request
                    success: function(response) {
                        // Reset button state
                        loginButton.prop('disabled', false).text(originalText);
                        
                        // Try to parse as JSON first
                        try {
                            var data = JSON.parse(response);
                            
                            if(data.code == 404) {
                                // User not found - don't increment attempts
                                showAdminAlert('warning', '⚠️ ' + data.message);
                                
                            } else if(data.code == 401) {
                                // Invalid credentials - update client-side attempt counter
                                if (data.attempts) {
                                    setAdminLoginAttempts(data.attempts);
                                }
                                
                                // Show enhanced message with email notification info
                                var message = data.message;
                                if (data.attempts) {
                                    if (data.attempts >= 3) {
                                        message = '🚨 ' + message;
                                        // Trigger lockout UI
                                        lockAdminLoginForm(30);
                                    } else if (data.attempts >= 2) {
                                        message = '⚠️ ' + message;
                                    } else {
                                        message = '📧 ' + message;
                                    }
                                }
                                showAdminAlert('warning', message);
                                
                            } else if(data.code == 429) {
                                // Server-side lockout - sync attempt counter
                                setAdminLoginAttempts(3);
                                handleAdminServerLockout(data.lockout_time || 30);
                                showAdminAlert('warning', '🔒 ' + data.message);
                            } else if(data.code == 200) {
                                // Successful login - reset attempt counter
                                resetAdminLoginAttempts();
                                showAdminAlert('success', 'Successfully Login!');
                                setTimeout(function() {
                                    window.location.href = BASE_URL + 'component/dashboard/index';
                                }, 1000);
                            }
                        } catch (e) {
                            // Response is not JSON, probably HTML redirect
                            console.log('Non-JSON response received:', response);
                            
                            // Check if it's a successful redirect (HTML page)
                            if (response.includes('<!DOCTYPE') || response.includes('<html')) {
                                // Successful login with redirect - clean up and follow
                                resetAdminLoginAttempts();
                                showAdminAlert('success', 'Login successful! Redirecting...');
                                setTimeout(function() {
                                    window.location.href = BASE_URL + 'component/dashboard/index';
                                }, 1000);
                            } else {
                                // Unknown response format
                                showAdminAlert('warning', 'Unexpected response format. Please try again.');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Reset button state
                        loginButton.prop('disabled', false).text(originalText);
                        
                        // Handle timeout specifically
                        if (status === 'timeout') {
                            showAdminAlert('warning', 'Request timeout. The server took too long to respond. Please try again.');
                            console.error('Login timeout after 15 seconds');
                            return;
                        }
                        
                        // Handle actual errors
                        console.error('Login error:', error);
                        console.error('Response status:', xhr.status);
                        console.error('Response text:', xhr.responseText);
                        
                        // Check if response is HTML (redirect)
                        if (xhr.responseText && xhr.responseText.includes('<!DOCTYPE')) {
                            // Server returned HTML instead of JSON - likely a redirect
                            showAdminAlert('warning', 'Session issue detected. Refreshing page...');
                            setTimeout(function() {
                                window.location.href = BASE_URL + 'auth';
                            }, 1000);
                        } else if (xhr.status === 0) {
                            // Network error
                            showAdminAlert('warning', 'Network error. Please check your connection and try again.');
                        } else {
                            showAdminAlert('warning', 'An error occurred. Please try again.');
                        }
                    }
                });
            });

            // Check for existing lockout on page load
            checkAdminLockoutOnLoad();
        });

        // Function to handle failed admin login attempts
        function handleAdminFailedLogin() {
            var attempts = getAdminLoginAttempts();
            attempts++;
            setAdminLoginAttempts(attempts);
            
            if (attempts >= 3) {
                lockAdminLoginForm(30); // Lock for 30 seconds
            }
        }

        // Function to handle server-side lockout for admin
        function handleAdminServerLockout(seconds) {
            lockAdminLoginForm(seconds);
        }

        // Function to lock the admin login form
        function lockAdminLoginForm(seconds) {
            var form = $('#admin_login_form');
            var emailInput = form.find('#admin_email');
            var passwordInput = form.find('#admin_password');
            var loginButton = $('.admin-login-form');
            
            // Disable form elements
            emailInput.prop('disabled', true);
            passwordInput.prop('disabled', true);
            loginButton.addClass('locked').prop('disabled', true);
            
            // Add visual feedback
            form.find('.form-group').addClass('lockout-mode');
            
            // Create or update countdown display
            var countdownElement = form.find('.lockout-countdown');
            if (countdownElement.length === 0) {
                countdownElement = $('<div class="lockout-countdown"></div>');
                form.find('.card-block').append(countdownElement);
            }
            
            // Start countdown
            var remainingTime = seconds;
            var originalButtonText = loginButton.text();
            
            function updateAdminCountdown() {
                if (remainingTime > 0) {
                    countdownElement.html(
                        '<i class="fa fa-lock" style="margin-right: 8px;"></i>' +
                        '<strong>Account Locked!</strong><br>' +
                        'Please wait <strong>' + remainingTime + '</strong> seconds before trying again.<br>' +
                        '<small><i class="fa fa-envelope" style="margin-right: 5px;"></i>A security notification has been sent to your email.</small>'
                    );
                    loginButton.text('Locked (' + remainingTime + 's)');
                    remainingTime--;
                    setTimeout(updateAdminCountdown, 1000);
                } else {
                    unlockAdminLoginForm(originalButtonText);
                }
            }
            
            updateAdminCountdown();
        }

        // Function to unlock the admin login form
        function unlockAdminLoginForm(originalButtonText = 'Sign in') {
            var form = $('#admin_login_form');
            var emailInput = form.find('#admin_email');
            var passwordInput = form.find('#admin_password');
            var loginButton = $('.admin-login-form');
            var countdownElement = form.find('.lockout-countdown');
            
            // Re-enable form elements
            emailInput.prop('disabled', false);
            passwordInput.prop('disabled', false);
            loginButton.removeClass('locked').prop('disabled', false).text(originalButtonText);
            
            // Remove visual feedback
            form.find('.form-group').removeClass('lockout-mode');
            countdownElement.remove();
            
            // Reset client-side attempt counter
            resetAdminLoginAttempts();
        }

        // Function to get admin login attempts from localStorage
        function getAdminLoginAttempts() {
            var attempts = localStorage.getItem('admin_login_attempts');
            var lastAttempt = localStorage.getItem('admin_last_attempt_time');
            var now = new Date().getTime();
            
            // Reset if more than 30 seconds have passed
            if (lastAttempt && (now - parseInt(lastAttempt)) > 30000) {
                resetAdminLoginAttempts();
                return 0;
            }
            
            return attempts ? parseInt(attempts) : 0;
        }

        // Function to set admin login attempts in localStorage
        function setAdminLoginAttempts(attempts) {
            localStorage.setItem('admin_login_attempts', attempts.toString());
            localStorage.setItem('admin_last_attempt_time', new Date().getTime().toString());
        }

        // Function to reset admin login attempts
        function resetAdminLoginAttempts() {
            localStorage.removeItem('admin_login_attempts');
            localStorage.removeItem('admin_last_attempt_time');
        }

        // Check for existing lockout on page load
        function checkAdminLockoutOnLoad() {
            var attempts = getAdminLoginAttempts();
            var lastAttempt = localStorage.getItem('admin_last_attempt_time');
            
            if (attempts >= 3 && lastAttempt) {
                var elapsed = (new Date().getTime() - parseInt(lastAttempt)) / 1000;
                var remaining = 30 - elapsed;
                
                if (remaining > 0) {
                    lockAdminLoginForm(Math.ceil(remaining));
                }
            }
        }

        // Function to show admin alerts
        function showAdminAlert(type, message) {
            var alertClass = type === 'success' ? 'success' : 'warning';
            var alertElement = $('.boxings');
            alertElement.removeClass('warning success').addClass(alertClass);
            
            // Add fade-in animation
            alertElement.hide();
            
            // Format message with HTML and icons
            if (type === 'success') {
                alertElement.html('<i class="fa fa-check-circle" style="margin-right: 8px;"></i>' + message);
            } else {
                // For warning messages, preserve emojis and add structure
                alertElement.html(message);
            }
            
            alertElement.fadeIn(500);
            
            // Auto-hide success messages after 3 seconds
            if (type === 'success') {
                setTimeout(function() {
                    alertElement.fadeOut(500);
                }, 3000);
            }
        }

        // Function to check and show password reset success modal
        function checkPasswordResetSuccess() {
            const urlParams = new URLSearchParams(window.location.search);
            const showModal = urlParams.get('show_modal');
            const type = urlParams.get('type');
            
            if (showModal === 'true' && type === 'success') {
                // Show the modal
                $('#passwordResetSuccessModal').modal('show');
                
                // Clear the URL parameters after showing modal
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
                
                // Hide any existing alert messages
                $('.boxings').hide();
            }
        }
    </script>
</body>

</html>
