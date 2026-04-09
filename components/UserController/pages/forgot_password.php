<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

<head>
    <style>
        .form-gap {
            padding-top: 70px;
        }
        .alert {
            margin-bottom: 15px;
        }
        .error-message {
            color: #d9534f;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>

<div class="form-gap"></div>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="text-center">
                  <h3><i class="fa fa-lock fa-4x"></i></h3>
                  <h2 class="text-center">Forgot Password?</h2>
                  <p>You can reset your password here.</p>
                  
                  <!-- Display error or success messages -->
                  <?php if(isset($_GET['type']) && isset($_GET['message'])): ?>
                    <div class="alert alert-<?php echo $_GET['type'] == 'error' ? 'danger' : 'success'; ?> alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                  <?php endif; ?>
                  
                  <div class="panel-body">
    
                    <form action="<?=$_ENV['URL_HOST'].'forgot_password' ?>" method="POST" id="forgotPasswordForm">
    
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                          <input name="email" id="email" placeholder="Enter your Gmail address!" class="form-control" type="email" required>
                        </div>
                        <div id="emailError" class="error-message" style="display: none;"></div>
                      </div>
                      <div class="form-group">
                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                      </div>
                      
                    </form>
    
                  </div>
                </div>
              </div>
            </div>
          </div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    $('#forgotPasswordForm').on('submit', function(e) {
        var email = $('#email').val().trim();
        var emailError = $('#emailError');
        
        // Reset error message
        emailError.hide();
        
        // Validate email format
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            emailError.text('Please enter a valid email address.').show();
            e.preventDefault();
            return false;
        }
        
        // Check if email contains gmail (optional check)
        if (!email.toLowerCase().includes('@gmail.com')) {
            emailError.text('Please enter a valid Gmail address.').show();
            e.preventDefault();
            return false;
        }
        
        return true;
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>