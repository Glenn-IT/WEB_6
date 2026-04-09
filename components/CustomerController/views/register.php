<main>
    <div class="mb-4 pb-4"></div>
    <section class="login-register container">
      <h2 class="d-none">Register</h2>
      <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore active" id="register-tab" data-bs-toggle="tab" href="#tab-item-register" role="tab" aria-controls="tab-item-register" aria-selected="false">Register</a>
        </li>
      </ul>
      <div class="tab-content pt-2" id="login_register_tab_content">
        <div class="tab-pane fade show active" id="tab-item-register" role="tabpanel" aria-labelledby="register-tab">
          <div class="register-form">
            <form action="<?=$_ENV['URL_HOST'].'authRegister' ?>" method="POST" class="needs-validation" novalidate>
              <input type="hidden" name="user_type" value="5">
              <div class="form-floating mb-3">
                <input name="account_first_name" type="text" class="form-control form-control_gray" id="account_first_name" placeholder="First Name" required>
                <label for="account_first_name">First Name</label>
              </div>

              <div class="form-floating mb-3">
                <input name="account_last_name" type="text" class="form-control form-control_gray" id="account_last_name" placeholder="Last Name" required>
                <label for="account_last_name">Last Name</label>
              </div>

              <div class="form-floating mb-3">
                <select name="gender" id="gender" class="form-control form-control_gray" >
                  <option value="MALE">MALE</option>
                  <option value="FEMALE">FEMALE</option>
                </select>
                <label for="account_last_name">Gender</label>
              </div>

              <div class="form-floating mb-3">
                <input name="username" type="text" class="form-control form-control_gray" id="customerNameRegisterInput" placeholder="Username" required>
                <label for="customerNameRegisterInput">Username</label>
              </div>
    
              <div class="pb-3"></div>

              <div class="form-floating mb-3">
                <input name="email" type="email" class="form-control form-control_gray" id="customerEmailRegisterInput" placeholder="Email address *" required>
                <label for="customerEmailRegisterInput">Email address *</label>
                <div class="invalid-feedback">
                  Please enter a valid email address.
                </div>
              </div>
              <div class="form-floating mb-3">
                <input name="contact_no" type="text" class="form-control form-control_gray" id="customerPhoneRegisterInput" placeholder="Contact No. *" maxlength="11" pattern="[0-9]{11}" required>
                <label for="customerPhoneRegisterInput">Phone NO. * (11 digits)</label>
                <div class="invalid-feedback">
                  Phone number must be exactly 11 digits (numbers only).
                </div>
              </div>
    
              <div class="pb-3"></div>
    
              <div class="form-floating mb-3 position-relative">
                <input name="password" type="password" class="form-control form-control_gray" id="customerPasswodRegisterInput" placeholder="Password *" required style="padding-right: 45px;">
                <label for="customerPasswodRegisterInput">Password *</label>
                <i class="fa fa-eye position-absolute" id="togglePassword" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;"></i>
              </div>

              <div class="form-floating mb-3 position-relative">
                <input name="confirm_password" type="password" class="form-control form-control_gray" id="customerConfirmPasswordInput" placeholder="Confirm Password *" required style="padding-right: 45px;">
                <label for="customerConfirmPasswordInput">Confirm Password *</label>
                <i class="fa fa-eye position-absolute" id="toggleConfirmPassword" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;"></i>
                <div class="invalid-feedback">
                  Passwords do not match.
                </div>
              </div>

              <div class="pb-3"></div>

              <div class="form-floating mb-3">
                <select name="security_question" id="securityQuestionSelect" class="form-control form-control_gray" required>
                  <option value="">Select a Security Question *</option>
                  <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                  <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                  <option value="What was the name of your elementary school?">What was the name of your elementary school?</option>
                  <option value="What is your favorite food?">What is your favorite food?</option>
                  <option value="In what city were you born?">In what city were you born?</option>
                </select>
                <label for="securityQuestionSelect">Security Question *</label>
                <div class="invalid-feedback">
                  Please select a security question.
                </div>
              </div>

              <div class="form-floating mb-3">
                <input name="security_answer" type="text" class="form-control form-control_gray" id="securityAnswerInput" placeholder="Your Answer *" required>
                <label for="securityAnswerInput">Security Answer *</label>
                <div class="invalid-feedback">
                  Please provide an answer to the security question.
                </div>
              </div>
    
              <div class="d-flex align-items-center mb-3 pb-2">
                <p class="m-0">Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our privacy policy.</p>
              </div>
    
              <button class="btn btn-primary w-100 text-uppercase" type="submit" id="registerBtn">Register</button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <script>
    // Add custom CSS for validation
    const style = document.createElement('style');
    style.textContent = `
        .form-control.is-valid {
            border-color: #28a745;
            background-color: #f8fff9;
        }
        .form-control.is-invalid {
            border-color: #dc3545;
            background-color: #fff5f5;
        }
        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
        }
        .form-control.is-invalid ~ .invalid-feedback {
            display: block;
        }
        .valid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #28a745;
        }
        .form-control.is-valid ~ .valid-feedback {
            display: block;
        }
    `;
    document.head.appendChild(style);

    // Toggle Password Visibility for Password field
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('customerPasswodRegisterInput');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });

    // Toggle Password Visibility for Confirm Password field
    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const confirmPasswordInput = document.getElementById('customerConfirmPasswordInput');
        
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            confirmPasswordInput.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });

    // Password match validation
    function validatePasswordMatch() {
        const password = document.getElementById('customerPasswodRegisterInput').value;
        const confirmPassword = document.getElementById('customerConfirmPasswordInput').value;
        const confirmPasswordInput = document.getElementById('customerConfirmPasswordInput');
        
        if (confirmPassword.length > 0) {
            if (password === confirmPassword) {
                confirmPasswordInput.classList.remove('is-invalid');
                confirmPasswordInput.classList.add('is-valid');
                return true;
            } else {
                confirmPasswordInput.classList.remove('is-valid');
                confirmPasswordInput.classList.add('is-invalid');
                return false;
            }
        } else {
            confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
            return false;
        }
    }

    // Add event listeners for password matching
    document.getElementById('customerPasswodRegisterInput').addEventListener('input', validatePasswordMatch);
    document.getElementById('customerConfirmPasswordInput').addEventListener('input', validatePasswordMatch);

    // Phone number validation - only allow numbers
    document.getElementById('customerPhoneRegisterInput').addEventListener('input', function(e) {
        // Remove any non-digit characters
        let value = e.target.value.replace(/[^0-9]/g, '');
        
        // Limit to 11 digits
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        
        e.target.value = value;
        
        // Visual validation feedback
        if (value.length === 11) {
            e.target.classList.remove('is-invalid');
            e.target.classList.add('is-valid');
        } else {
            e.target.classList.remove('is-valid');
            if (value.length > 0) {
                e.target.classList.add('is-invalid');
            }
        }
    });

    // Email validation
    document.getElementById('customerEmailRegisterInput').addEventListener('input', function(e) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const value = e.target.value;
        
        if (emailRegex.test(value)) {
            e.target.classList.remove('is-invalid');
            e.target.classList.add('is-valid');
        } else {
            e.target.classList.remove('is-valid');
            if (value.length > 0) {
                e.target.classList.add('is-invalid');
            }
        }
    });

    // Security question validation
    document.getElementById('securityQuestionSelect').addEventListener('change', function(e) {
        if (e.target.value !== '') {
            e.target.classList.remove('is-invalid');
            e.target.classList.add('is-valid');
        } else {
            e.target.classList.remove('is-valid');
            e.target.classList.add('is-invalid');
        }
    });

    // Security answer validation
    document.getElementById('securityAnswerInput').addEventListener('input', function(e) {
        const value = e.target.value.trim();
        if (value.length >= 2) {
            e.target.classList.remove('is-invalid');
            e.target.classList.add('is-valid');
        } else {
            e.target.classList.remove('is-valid');
            if (value.length > 0) {
                e.target.classList.add('is-invalid');
            }
        }
    });

    // Form submission validation
    document.querySelector('.needs-validation').addEventListener('submit', function(e) {
        const phoneInput = document.getElementById('customerPhoneRegisterInput');
        const emailInput = document.getElementById('customerEmailRegisterInput');
        const passwordInput = document.getElementById('customerPasswodRegisterInput');
        const confirmPasswordInput = document.getElementById('customerConfirmPasswordInput');
        const securityQuestionInput = document.getElementById('securityQuestionSelect');
        const securityAnswerInput = document.getElementById('securityAnswerInput');
        const phone = phoneInput.value.replace(/[^0-9]/g, '');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        let isValid = true;
        
        // Validate phone number
        if (phone.length !== 11) {
            phoneInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate email
        if (!emailRegex.test(emailInput.value)) {
            emailInput.classList.add('is-invalid');
            isValid = false;
        }

        // Validate password match
        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.classList.add('is-invalid');
            isValid = false;
        }

        // Validate security question
        if (securityQuestionInput.value === '') {
            securityQuestionInput.classList.add('is-invalid');
            isValid = false;
        }

        // Validate security answer
        if (securityAnswerInput.value.trim().length < 2) {
            securityAnswerInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        this.classList.add('was-validated');
    });

    // Prevent non-numeric input on phone field
    document.getElementById('customerPhoneRegisterInput').addEventListener('keypress', function(e) {
        // Allow only numbers, backspace, delete, tab, escape, enter
        if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Escape', 'Enter'].includes(e.key)) {
            e.preventDefault();
        }
    });
    </script>
  </main>