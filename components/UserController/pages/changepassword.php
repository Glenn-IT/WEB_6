<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

<head>
    <style>
        .form-gap {
            padding-top: 70px;
        }
        .error-message {
            color: #d9534f;
            font-size: 14px;
            margin-top: 5px;
        }
        .success-message {
            color: #5cb85c;
            font-size: 14px;
            margin-top: 5px;
        }
        .alert {
            margin-bottom: 15px;
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
                  <h2 class="text-center">Change Password</h2>
                  <p>Please enter your new password below.</p>
                  
                  <!-- Display error or success messages -->
                  <?php if(isset($_GET['type']) && isset($_GET['message'])): ?>
                    <div class="alert alert-<?php echo $_GET['type'] == 'error' ? 'danger' : 'success'; ?> alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                  <?php endif; ?>
                  
                  <div class="panel-body">
    
                    <form action="<?=$_ENV['URL_HOST'].'changepassword' ?>" method="POST" id="changePasswordForm">
    
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                          <input id="email" name="email" placeholder="email address" class="form-control" type="email" value="<?=$email?>" readonly>
                        </div>
                      </div>
                      
                      <!-- Step 1: Security Question Verification -->
                      <div id="securityQuestionSection">
                        <div class="form-group">
                          <label for="security_question">Security Question <span style="color: red;">*</span></label>
                          <select id="security_question" name="security_question" class="form-control" required>
                            <option value="">Select your security question</option>
                            <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                            <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                            <option value="What was the name of your elementary school?">What was the name of your elementary school?</option>
                            <option value="What is your favorite food?">What is your favorite food?</option>
                            <option value="In what city were you born?">In what city were you born?</option>
                          </select>
                        </div>
                        
                        <div class="form-group">
                          <label for="security_answer">Security Answer <span style="color: red;">*</span></label>
                          <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                            <input id="security_answer" name="security_answer_verify" class="form-control" type="text" placeholder="Enter your security answer" required>
                          </div>
                          <div id="securityAnswerError" class="error-message" style="display: none;"></div>
                        </div>
                        
                        <div class="form-group">
                          <button type="button" id="verifySecurityBtn" class="btn btn-lg btn-primary btn-block">Verify Security Answer</button>
                        </div>
                      </div>
                      
                      <!-- Step 2: Password Change (Hidden initially) -->
                      <div id="passwordChangeSection" style="display: none;">
                        <div class="alert alert-success">
                          <i class="fa fa-check-circle"></i> Security question verified successfully!
                        </div>
                        
                        <div class="form-group">
                          <label for="password">New Password</label>
                          <div class="input-group" style="position: relative;">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="password" name="password" class="form-control" type="password" placeholder="Enter new password" minlength="6" style="padding-right: 35px;">
                            <i class="fa fa-eye" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;"></i>
                          </div>
                          <div id="passwordError" class="error-message" style="display: none;"></div>
                        </div>
                        
                        <div class="form-group">
                          <label for="confirm_password">Confirm Password</label>
                          <div class="input-group" style="position: relative;">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="confirm_password" name="confirm_password" class="form-control" type="password" placeholder="Confirm new password" minlength="6" style="padding-right: 35px;">
                            <i class="fa fa-eye" id="toggleConfirmPassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;"></i>
                          </div>
                          <div id="confirmPasswordError" class="error-message" style="display: none;"></div>
                          <div id="passwordMatch" class="success-message" style="display: none;">✓ Passwords match</div>
                        </div>
                     
                        <div class="form-group">
                          <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Change Password" type="submit">
                        </div>
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
    // Verify Security Question Answer
    $('#verifySecurityBtn').on('click', function() {
        var email = $('#email').val();
        var securityQuestion = $('#security_question').val();
        var securityAnswer = $('#security_answer').val().trim();
        var errorDiv = $('#securityAnswerError');
        
        // Reset error message
        errorDiv.hide();
        
        // Validate inputs
        if (!securityQuestion) {
            errorDiv.text('Please select a security question.').show();
            return;
        }
        
        if (!securityAnswer || securityAnswer.length < 2) {
            errorDiv.text('Please provide an answer to the security question.').show();
            return;
        }
        
        // Disable button and show loading
        $(this).prop('disabled', true).text('Verifying...');
        
        // Send AJAX request to verify security answer
        $.ajax({
            url: '<?=$_ENV['URL_HOST']?>verify-security-answer',
            method: 'POST',
            data: {
                email: email,
                security_question: securityQuestion,
                security_answer: securityAnswer
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    // Hide security section and show password section
                    $('#securityQuestionSection').slideUp(300, function() {
                        $('#passwordChangeSection').slideDown(300);
                        // Make password fields required
                        $('#password, #confirm_password').prop('required', true);
                    });
                } else {
                    errorDiv.text(response.message || 'Incorrect security answer. Please try again.').show();
                    $('#verifySecurityBtn').prop('disabled', false).text('Verify Security Answer');
                }
            },
            error: function() {
                errorDiv.text('An error occurred. Please try again.').show();
                $('#verifySecurityBtn').prop('disabled', false).text('Verify Security Answer');
            }
        });
    });
    
    // Toggle Password Visibility for Password field
    $('#togglePassword').on('click', function() {
        var passwordInput = $('#password');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Toggle Password Visibility for Confirm Password field
    $('#toggleConfirmPassword').on('click', function() {
        var confirmPasswordInput = $('#confirm_password');
        
        if (confirmPasswordInput.attr('type') === 'password') {
            confirmPasswordInput.attr('type', 'text');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            confirmPasswordInput.attr('type', 'password');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    function validatePasswords() {
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();
        var passwordError = $('#passwordError');
        var confirmPasswordError = $('#confirmPasswordError');
        var passwordMatch = $('#passwordMatch');
        
        // Reset all messages
        passwordError.hide();
        confirmPasswordError.hide();
        passwordMatch.hide();
        
        var isValid = true;
        
        // Validate password length
        if (password.length > 0 && password.length < 6) {
            passwordError.text('Password must be at least 6 characters long.').show();
            isValid = false;
        }
        
        // Validate password confirmation
        if (confirmPassword.length > 0) {
            if (password !== confirmPassword) {
                confirmPasswordError.text('Passwords do not match.').show();
                isValid = false;
            } else if (password.length >= 6) {
                passwordMatch.show();
            }
        }
        
        return isValid;
    }
    
    // Real-time validation
    $('#password, #confirm_password').on('keyup blur', function() {
        validatePasswords();
    });
    
    // Form submission validation
    $('#changePasswordForm').on('submit', function(e) {
        // Only validate if password section is visible
        if ($('#passwordChangeSection').is(':visible')) {
            var password = $('#password').val().trim();
            var confirmPassword = $('#confirm_password').val().trim();
            
            if (password.length < 6) {
                $('#passwordError').text('Password must be at least 6 characters long.').show();
                e.preventDefault();
                return false;
            }
            
            if (password !== confirmPassword) {
                $('#confirmPasswordError').text('Passwords do not match.').show();
                e.preventDefault();
                return false;
            }
            
            if (!validatePasswords()) {
                e.preventDefault();
                return false;
            }
            
            return true;
        } else {
            // Prevent form submission if security question not verified
            e.preventDefault();
            $('#securityAnswerError').text('Please verify your security answer first.').show();
            return false;
        }
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>