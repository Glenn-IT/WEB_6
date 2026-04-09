<div class="page-content my-account__edit">
            <div class="my-account__edit-form">
              <form action="#" method="POST" id="passwordValidationForm">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" id="account_first_name" placeholder="First Name" value="<?=$_SESSION["account_first_name"]?>" disabled>
                      <label for="account_first_name">First Name</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" id="account_last_name" placeholder="Last Name" value="<?=$_SESSION["account_last_name"]?>" disabled>
                      <label for="account_last_name">Last Name</label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" id="account_display_name" placeholder="Display Name" value="<?=$_SESSION["username"]?>" disabled>
                      <label for="account_display_name">Username</label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="<?=$_SESSION["email"]?>" readonly>
                      <label for="email">Email Address</label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="contact_no" class="form-control" id="contact_no" name="contact_no"  value="<?=$_SESSION["contact_no"]?>" readonly>
                      <label for="contact_no">Contact NO.</label>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="gender" class="form-control" id="gender" name="gender"  value="<?=$_SESSION["gender"]?>" readonly>
                      <label for="gender">Gender</label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="my-3">
                      <h5 class="text-uppercase mb-0">Password Change</h5>
                      <p class="text-muted small">For security, please answer your security question first</p>
                    </div>
                  </div>

                  <!-- Security Question Section -->
                  <div id="securityQuestionSection">
                    <div class="col-md-12">
                      <div class="form-floating my-3">
                        <input type="text" class="form-control" id="security_question_display" value="<?=isset($_SESSION['security_question']) ? $_SESSION['security_question'] : 'No security question set'?>" readonly>
                        <label for="security_question_display">Your Security Question</label>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-floating my-3">
                        <input type="text" class="form-control" id="security_answer_input" name="security_answer" placeholder="Your Answer" required>
                        <label for="security_answer_input">Your Answer *</label>
                        <div class="invalid-feedback" id="security-answer-error" style="display: none;">Incorrect answer!</div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="my-3">
                        <button type="button" class="btn btn-success" onclick="verifySecurityAnswer()">
                          <i class="fa fa-shield-alt me-2"></i>Verify Security Answer
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Current Password Section (Hidden Initially) -->
                  <div id="currentPasswordSection" style="display: none;">
                    <div class="col-md-12">
                      <div class="alert alert-success">
                        <i class="fa fa-check-circle me-2"></i>Security question verified! You can now change your password.
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-floating my-3">
                        <input type="password" class="form-control" id="account_current_password" name="current_password" placeholder="Current password" required>
                        <label for="account_current_password">Current password</label>
                        <div class="invalid-feedback" id="password-error" style="display: none;">Incorrect current password!</div>
                      </div>
                    </div>
                    
                    <div class="col-md-12">
                      <div class="my-3">
                        <button type="button" class="btn btn-primary" onclick="validateCurrentPassword()">
                          <i class="fa fa-save me-2"></i>Save Changes
                        </button>
                        <button type="button" class="btn btn-secondary ms-2" onclick="debugTest()" style="display: none;">Debug Test</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>

<script>
function verifySecurityAnswer() {
    const securityAnswer = document.getElementById('security_answer_input').value;
    
    if (!securityAnswer) {
        swal("Error", "Please enter your security answer!", "error");
        return;
    }
    
    // Show loading
    swal({
        title: "Verifying...",
        text: "Please wait while we verify your security answer.",
        icon: "info",
        buttons: false,
        closeOnClickOutside: false,
        closeOnEsc: false
    });
    
    // Make AJAX request to validate security answer
    fetch('index', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=verifySecurityAnswer&security_answer=' + encodeURIComponent(securityAnswer)
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.text();
    })
    .then(text => {
        console.log('Raw response:', text);
        try {
            const data = JSON.parse(text);
            console.log('Parsed data:', data);
            
            if (data.status === true) {
                // Security answer is correct
                swal({
                    title: "Verified!",
                    text: "Security answer verified! You can now change your password.",
                    icon: "success",
                    timer: 1500,
                    buttons: false
                }).then(() => {
                    // Hide security question section
                    document.getElementById('securityQuestionSection').style.display = 'none';
                    // Show current password section
                    document.getElementById('currentPasswordSection').style.display = 'block';
                });
            } else {
                // Security answer is incorrect
                document.getElementById('security_answer_input').classList.add('is-invalid');
                document.getElementById('security-answer-error').style.display = 'block';
                swal("Error", data.message || "Incorrect security answer!", "error");
            }
        } catch (e) {
            console.error('JSON parse error:', e);
            swal("Error", "Invalid response from server. Please check your connection and try again.", "error");
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        swal("Error", "An error occurred. Please check your connection and try again.", "error");
    });
}

function validateCurrentPassword() {
    const currentPassword = document.getElementById('account_current_password').value;
    
    if (!currentPassword) {
        swal("Error", "Please enter your current password!", "error");
        return;
    }
    
    // Show loading
    swal({
        title: "Validating...",
        text: "Please wait while we verify your password.",
        icon: "info",
        buttons: false,
        closeOnClickOutside: false,
        closeOnEsc: false
    });
    
    // Make AJAX request to validate password
    fetch('index', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=validateCurrentPassword&current_password=' + encodeURIComponent(currentPassword)
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.text();
    })
    .then(text => {
        console.log('Raw response:', text);
        try {
            const data = JSON.parse(text);
            console.log('Parsed data:', data);
            
            if (data.status === true) {
                // Password is correct
                swal({
                    title: "Success!",
                    text: "Password verified! Redirecting to change password page...",
                    icon: "success",
                    timer: 2000,
                    buttons: false
                }).then(() => {
                    window.location.href = '<?=$_ENV['URL_HOST']?>changepassword?email=<?=$_SESSION["email"]?>';
                });
            } else {
                // Password is incorrect
                swal("Error", data.message || "Incorrect current password!", "error");
            }
        } catch (e) {
            console.error('JSON parse error:', e);
            swal("Error", "Invalid response from server. Please check your connection and try again.", "error");
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        swal("Error", "An error occurred. Please check your connection and try again.", "error");
    });
}

function debugTest() {
    swal({
        title: "Running Debug Test...",
        text: "Testing the connection and authentication...",
        icon: "info",
        buttons: false,
        closeOnClickOutside: false,
        closeOnEsc: false
    });
    
    fetch('index', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=debugTest&test=1'
    })
    .then(response => {
        console.log('Debug Response status:', response.status);
        return response.text();
    })
    .then(text => {
        console.log('Debug Raw response:', text);
        try {
            const data = JSON.parse(text);
            console.log('Debug Parsed data:', data);
            
            swal({
                title: "Debug Results",
                text: JSON.stringify(data, null, 2),
                icon: data.status ? "success" : "error",
                button: "OK"
            });
        } catch (e) {
            console.error('Debug JSON parse error:', e);
            swal("Debug Error", "Invalid response: " + text.substring(0, 500), "error");
        }
    })
    .catch(error => {
        console.error('Debug Fetch error:', error);
        swal("Debug Error", "Network error: " + error.message, "error");
    });
}

// Remove error styling when user starts typing in security answer
document.getElementById('security_answer_input').addEventListener('input', function() {
    this.classList.remove('is-invalid');
    const errorDiv = document.getElementById('security-answer-error');
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
});

// Remove error styling when user starts typing in password
if (document.getElementById('account_current_password')) {
    document.getElementById('account_current_password').addEventListener('input', function() {
        this.classList.remove('is-invalid');
        const errorDiv = document.getElementById('password-error');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    });
}
</script>