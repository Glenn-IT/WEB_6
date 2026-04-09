<?php 

require_once __DIR__ . '/../ComponentHelper/ComponentHelper.php';

class UserController {

    protected $db;
    protected $componentHelper;

    public function __construct($db) {
        $this->db = $db;
        $this->componentHelper = new ComponentHelper($db);
    }


    public function index() {


        $data =  $this->db->Select("SELECT * FROM `system_info` limit 1");

        $data = [
            'title' => $data[0]["title"],
            'content' => 'This is the homepage.',
        ];
        

        // Load the view with data

        if(getSegment()[1] == "") {

            if($_SESSION["user_type"]==5){
                header('Location: ' . baseUrl('/customer/customer/index'));
            } else {
                header('Location: ' . baseUrl('/component/dashboard/index'));
            };
            exit();
        } else {
            echo loadView('components/UserController/pages/login_form', $data);

        }

    }

    public function forgot_password(){

        $data = getRequestAll();

        extract($data);

        if(isset($email)) {
            // Validate email format only
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header('Location: ' . baseUrl('/forgot_password?type=error&message=Please enter a valid email address.'));
                exit();
            }
            
            // Check if email exists for any user type (removed admin-only restriction)
            $data = $this->db->Select("select * from users where email = ? AND deleted = 0", array($email) );
            if(count($data) > 0){
    
                $token =  $this->componentHelper->generateRandomString(6);
                $this->db->Update("update users SET code = ? WHERE user_id = ? ", array($token,$data[0]["user_id"]));


                $Body = ' Code : ' .$token;
                $res = $this->componentHelper->sentSMS($data[0]["contact_no"], $Body) ;

				$Body = '<h2>Your Password Reset Code: '.$token.'</h2><p>Please use this code to reset your password.</p>';
                $this->componentHelper->sentToEmail($data[0]["email"], "Forgot Password", $Body) ;
				
            


                header('Location: ' . baseUrl('/otp?email='.$data[0]["email"]));
                exit();

            } else {
                // Email not found in system
                header('Location: ' . baseUrl('/forgot_password?type=error&message=Email address not found. Please check your email and try again.'));
                exit();
            }
    
    
        }
       

        $data =  $this->db->Select("SELECT * FROM `system_info` limit 1");

        $data = [
            'title' => $data[0]["title"],
            'content' => 'This is the homepage.',
        ];

        // Load the view with data
        echo loadView('components/UserController/pages/forgot_password', $data);
    }

    public function otp(){
        $data = getRequestAll();

        extract($data);
        
        if(isset($pin)) {
            $code = implode('', $pin);
            $data = $this->db->Select("select * from users where email = ? and code = ?", array($email, $code) );
           
            if(count($data) > 0){
                if(isset($type) && $type == "verify") {
                    header('Location: ' . baseUrl('/'));

                } else {
                    header('Location: ' . baseUrl('/changepassword?email='.$email));
                }
                exit();
            }
        }

        $data =  $this->db->Select("SELECT * FROM `system_info` limit 1");

        $data = [
            'title' => $data[0]["title"],
            'email' => $email,
            'content' => 'This is the homepage.',
        ];

        // Load the view with data
        echo loadView('components/UserController/pages/otp', $data);
    }

    public function changepassword(){
        $data = getRequestAll();

        extract($data);
        
        if(isset($password)) {
            // Validate password
            if (strlen($password) < 6) {
                header('Location: ' . baseUrl('/changepassword?email='.$email.'&type=error&message=Password must be at least 6 characters long.'));
                exit();
            }
            
            // Validate password confirmation
            if (!isset($confirm_password) || empty($confirm_password)) {
                header('Location: ' . baseUrl('/changepassword?email='.$email.'&type=error&message=Please confirm your password.'));
                exit();
            }
            
            if ($password !== $confirm_password) {
                header('Location: ' . baseUrl('/changepassword?email='.$email.'&type=error&message=Passwords do not match. Please try again.'));
                exit();
            }
            
            $data = $this->db->Select("select * from users where email = ? ", array($email) );
            if(count($data) > 0){

                $this->db->Update("update users SET password = ? WHERE user_id = ? ", array($password,$data[0]["user_id"]));

                if($data[0]["user_type"] ==5 ) {
                    header('Location: ' . baseUrl('/?type=success&message=Password changed successfully! Please login with your new password.&show_modal=true'));

                } else {
                    header('Location: ' . baseUrl('/auth?type=success&message=Password changed successfully! Please login with your new password.&show_modal=true'));
                }
                exit();
            } else {
                header('Location: ' . baseUrl('/changepassword?email='.$email.'&type=error&message=User not found. Please try again.'));
                exit();
            }
        }

        $data =  $this->db->Select("SELECT * FROM `system_info` limit 1");

        $data = [
            'title' => $data[0]["title"],
            'email' => $email,
            'content' => 'This is the homepage.',
        ];

        // Load the view with data
        echo loadView('components/UserController/pages/changepassword', $data);
    }

    public function verifySecurityAnswer() {
        header('Content-Type: application/json');
        
        $data = getRequestAll();
        extract($data);
        
        // Validate required fields
        if (!isset($email) || !isset($security_question) || !isset($security_answer)) {
            echo json_encode([
                'status' => false,
                'message' => 'Missing required fields.'
            ]);
            exit();
        }
        
        // Fetch user data
        $userData = $this->db->Select("SELECT security_question, security_answer FROM users WHERE email = ? AND deleted = 0", array($email));
        
        if (count($userData) === 0) {
            echo json_encode([
                'status' => false,
                'message' => 'User not found.'
            ]);
            exit();
        }
        
        $user = $userData[0];
        
        // Check if user has security question set
        if (empty($user['security_question']) || empty($user['security_answer'])) {
            echo json_encode([
                'status' => false,
                'message' => 'No security question found for this account.'
            ]);
            exit();
        }
        
        // Verify security question and answer (case-insensitive comparison for answer)
        if ($user['security_question'] === $security_question && 
            strcasecmp(trim($user['security_answer']), trim($security_answer)) === 0) {
            echo json_encode([
                'status' => true,
                'message' => 'Security answer verified successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Incorrect security question or answer. Please try again.'
            ]);
        }
        exit();
    }

    public function userLogin() {
        $data = getRequestAll();
        extract($data);

        // Ensure email and password are set
        if (!isset($email) || !isset($password)) {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
        }

        $response  = [
            'status' => 'success',
            'code' => '200',
            'message' => 'Loging Succefully'
        ];

        $user_type = (isset($login_type) && $login_type == "customer") ? ' and user_type = 5' : ' and user_type in(1,2)';

        $login_type = (isset($login_type) && $login_type == "customer") ? 'customer' : 'admin';


        // Initialize tracking if not set
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = 0;
        }

        // Store the email being attempted for security notifications (for all login types)
        if (isset($email)) {
            $_SESSION['attempted_email'] = $email;
        }

      
        if ($_SESSION['login_attempts'] >= 3 && (time() - $_SESSION['last_attempt_time']) < 30 ) {

            $remainingTime = 30 - (time() - $_SESSION['last_attempt_time']);
            $lockoutMessage = 'Too many login attempts. Your account is locked for ' . $remainingTime . ' seconds. A security notification has been sent to your email.';

            // Only send email once when lockout first happens (not on subsequent attempts during lockout)
            $emailToWarn = isset($email) ? $email : (isset($_SESSION['attempted_email']) ? $_SESSION['attempted_email'] : null);
            $shouldSendLockoutEmail = ($_SESSION['login_attempts'] == 3) && $emailToWarn && !isset($_SESSION['lockout_email_sent']);

            if(isset($source)) {
                $response['code'] = 429;
                $response['message'] = $lockoutMessage;
                $response['lockout_time'] = $remainingTime;
                
                // Send response first
                echo json_encode($response);
                
                // Flush output buffers to send response immediately
                if (function_exists('fastcgi_finish_request')) {
                    fastcgi_finish_request();
                } else {
                    ob_flush();
                    flush();
                }
                
                // Send lockout email only once
                if ($shouldSendLockoutEmail) {
                    error_log("=== LOCKOUT EMAIL - Sending notification (first lockout) ===");
                    $this->sendLockoutWarningEmail($emailToWarn);
                    $_SESSION['lockout_email_sent'] = true;
                }
                
                exit();
            } else {

                $link  = baseUrl('/customer/customer/index');
                if($login_type=="admin"){
                    $link  = baseUrl('/auth');
                }

                header('Location: '.$link.'?type=warning&message=' . urlencode($lockoutMessage));
                
                // Send lockout email only once
                if ($shouldSendLockoutEmail) {
                    error_log("=== LOCKOUT EMAIL - Sending notification (first lockout) ===");
                    $this->sendLockoutWarningEmail($emailToWarn);
                    $_SESSION['lockout_email_sent'] = true;
                }
                
                exit();

            }
        }


       
    

        // First get the user by email/username only
        $user = $this->db->Select("select * from users where status = 1 and deleted = 0 and (email = ? OR username = ?) $user_type ", array($email,$email));
        
        // Check if user exists in database first
        if(count($user) == 0){
            // User doesn't exist - show error without incrementing attempts
            $notFoundMessage = 'Gmail address not found. Please check your email and try again.';
            
            if($login_type == 'admin') {
                if(isset($source)) {
                    $response['code'] = 404;
                    $response['message'] = $notFoundMessage;
                    echo json_encode($response);
                    exit();
                } else {
                    $link = baseUrl('/auth');
                    header('Location: '.$link.'?type=warning&message=' . urlencode($notFoundMessage));
                    exit();
                }
            } else {
                if(isset($source)) {
                    $response['code'] = 404;
                    $response['message'] = $notFoundMessage;
                    echo json_encode($response);
                    exit();
                } else {
                    $link = baseUrl('/customer/customer/index');
                    header('Location: '.$link.'?type=warning&message=' . urlencode($notFoundMessage));
                    exit();
                }
            }
        }
        
        // User exists, now check password
        $data = array();
        if(count($user) > 0){
            $storedPassword = $user[0]['password'];
            
            // Check password with case-sensitive comparison
            // Support both plain text and MD5 hashed passwords
            if($password === $storedPassword || md5($password) === $storedPassword || password_verify($password, $storedPassword)){
                $data = $user;
            }
        }
        


   
    
        
        if(count($data) > 0){

            $token = generateToken();
            $this->db->Update("update users SET token = ? WHERE user_id = ? ", array($token,$data[0]["user_id"]));


            $data = $this->db->Select("select * from users where user_id = ? ", array($data[0]["user_id"]) );


            foreach ($data[0] as $key => $value) {
                $_SESSION[$key] = $value;
            }


            $_SESSION["user_active"] = true;

            $_SESSION['login_attempts'] = 0;
            unset($_SESSION['attempted_email']); // Clear attempted email on successful login
            unset($_SESSION['lockout_email_sent']); // Clear lockout email flag on successful login

            if(in_array($_SESSION["user_type"], [5])) {

                if(isset($source)) {

                    echo json_encode($response);
                    exit();
                } else {
                header('Location: ' . baseUrl('/customer/customer/index'));

                }
            } else {
                if(isset($source)) {
                    // AJAX request - return JSON
                    echo json_encode($response);
                    exit();
                } else {
                    // Regular form submission - redirect
                    if($_SESSION["user_type"] ==2 ) {
                        header('Location: ' . baseUrl('/component/messages/index'));
                    } else {
                        header('Location: ' . baseUrl('/component/dashboard/index'));
                    }
                }
            }

            exit();

        } else {
            
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            
            // Store email for sending ONLY on 3rd attempt (lockout)
            $emailToWarn = isset($email) ? $email : (isset($_SESSION['attempted_email']) ? $_SESSION['attempted_email'] : null);
            $shouldSendEmail = ($_SESSION['login_attempts'] == 3 && $emailToWarn); // Only send on 3rd attempt
            
            // Prepare messages based on attempt count
            $attemptsRemaining = 3 - $_SESSION['login_attempts'];
            $warningMessage = 'Invalid Credentials! Please try again.';
            
            if ($_SESSION['login_attempts'] >= 3) {
                $warningMessage = 'Too many failed attempts! Your account has been temporarily locked for 30 seconds. A security notification has been sent to your email.';
            } elseif ($_SESSION['login_attempts'] == 2) {
                $warningMessage = 'Invalid Credentials! You have 1 more attempt before your account is locked.';
            } elseif ($_SESSION['login_attempts'] == 1) {
                $warningMessage = 'Invalid Credentials! You have 2 more attempts before your account is locked.';
            }
            
            if($login_type == 'admin') {
                if(isset($source)) {
                    $response['code'] = 401;
                    $response['message'] = $warningMessage;
                    $response['attempts'] = $_SESSION['login_attempts'];
                    $response['attempts_remaining'] = max(0, $attemptsRemaining);

                    // Send response first, then email (only on 3rd attempt)
                    echo json_encode($response);
                    
                    // Flush output buffers to send response immediately
                    if (function_exists('fastcgi_finish_request')) {
                        fastcgi_finish_request();
                    } else {
                        ob_flush();
                        flush();
                    }
                    
                    // Now send email in background ONLY on 3rd attempt (won't block user)
                    if ($shouldSendEmail) {
                        error_log("=== LOCKOUT EMAIL - Attempt 3 detected, sending notification ===");
                        $this->sendLockoutWarningEmail($emailToWarn);
                    }
                    
                    exit();

                } else {
                    header('Location: ' . baseUrl('/auth?type=warning&message=' . urlencode($warningMessage)));
                    
                    // Send email after redirect (only on 3rd attempt)
                    if ($shouldSendEmail) {
                        error_log("=== LOCKOUT EMAIL - Attempt 3 detected, sending notification ===");
                        $this->sendLockoutWarningEmail($emailToWarn);
                    }
                }
            } else {
                
                if(isset($source)) {
                    $response['code'] = 401;
                    $response['message'] = $warningMessage;
                    $response['attempts'] = $_SESSION['login_attempts'];
                    $response['attempts_remaining'] = max(0, $attemptsRemaining);
                    
                    // Send response first, then email (only on 3rd attempt)
                    echo json_encode($response);
                    
                    // Flush output buffers to send response immediately
                    if (function_exists('fastcgi_finish_request')) {
                        fastcgi_finish_request();
                    } else {
                        ob_flush();
                        flush();
                    }
                    
                    // Now send email in background ONLY on 3rd attempt (won't block user)
                    if ($shouldSendEmail) {
                        error_log("=== LOCKOUT EMAIL - Attempt 3 detected, sending notification ===");
                        $this->sendLockoutWarningEmail($emailToWarn);
                    }
                    
                    exit();

                } else {
                   header('Location: ' . baseUrl('/customer/customer/index?type=warning&message=' . urlencode($warningMessage)));
                   
                   // Send email after redirect (only on 3rd attempt)
                   if ($shouldSendEmail) {
                       error_log("=== LOCKOUT EMAIL - Attempt 3 detected, sending notification ===");
                       $this->sendLockoutWarningEmail($emailToWarn);
                   }
                }

            }
            exit();
        }


    }

    public function authRegister(){
        $data = getRequestAll();
        extract($data);

        // Validate email address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if(isset($user_type) && $user_type == 5) {
                header('Location: ' . baseUrl('/customer/customer/index?page=register&type=warning&message=Please enter a valid email address.'));
            } else {
                header('Location: ' . baseUrl('/auth?type=warning&message=Please enter a valid email address.'));
            }
            exit();
        }

        // Validate phone number (11 digits only, no letters)
        if(isset($contact_no)) {
            // Remove any spaces, dashes, or parentheses
            $contact_no = preg_replace('/[^0-9]/', '', $contact_no);
            
            // Check if it's exactly 11 digits
            if (strlen($contact_no) !== 11 || !ctype_digit($contact_no)) {
                if(isset($user_type) && $user_type == 5) {
                    header('Location: ' . baseUrl('/customer/customer/index?page=register&type=warning&message=Phone number must be exactly 11 digits with no letters or special characters.'));
                } else {
                    header('Location: ' . baseUrl('/auth?type=warning&message=Phone number must be exactly 11 digits with no letters or special characters.'));
                }
                exit();
            }
        }

        $data = $this->db->Select("select * from users where email = ? and deleted = 0", array($email) );
        if(count($data) > 0){


            session_destroy();

            
            if(isset($user_type) && $user_type == 5) {
                header('Location: ' . baseUrl('/customer/customer/index?page=register&type=warning&message=Email already exist!.Please user another email!'));
            } else {
                header('Location: ' . baseUrl('/auth?type=warning&message=Email already exist!.Please user another email!'));

            }
            exit();

        } else {

            
            $token = generateToken();
          
            
            if(isset($user_type) && $user_type == 5) {
                $this->db->Insert("INSERT INTO users (`email`,`username`,`password`, `token`, `user_type`, `account_first_name`, `account_last_name`,  `contact_no`, `gender`, `security_question`, `security_answer`) VALUES (?,?,?,?,?,?,?,?,?,?,?)", [
                    $email,
                    $username,
                    $password,
                    $token,
                    $user_type,
                    $account_first_name,
                    $account_last_name,
                    $contact_no,
                    $gender,
                    isset($security_question) ? $security_question : null,
                    isset($security_answer) ? $security_answer : null
                ]);
            } else {
                $this->db->Insert("INSERT INTO users (`email`,`username`,`password`, `token`, `user_type`) VALUES (?,?,?,?,?)", [
                    $email,
                    $username,
                    $password,
                    $token,
                    $user_type,
                ]);
            }
            
            session_destroy();


            if(isset($user_type) && $user_type == 5) {

                $token =  $this->componentHelper->generateRandomString(6);
                $this->db->Update("update users SET code = ? WHERE email = ? ", array($token,$email));

				$Body = '<h2>Your Verification Code: '.$token.'</h2><p>Please use this code to verify your account.</p>';


                $this->componentHelper->sentToEmail($email, "USER VERIFICATION", $Body) ;

                header('Location: ' . baseUrl('/otp?type=verify&email='.$email));

            } else {
                header('Location: ' . baseUrl('/auth?type=success&message=Successfully Registered!'));
            }

            exit();
        }
    }

    public function userLogout() {
        $getype =isset($_SESSION["user_type"]) ? $_SESSION["user_type"] : 5;



        session_destroy();
        
        if($getype == 5) {
            header('Location: ' . baseUrl('/customer/customer/index'));
        } else {
            header('Location: ' . baseUrl('/auth'));

        }
        
        exit();

    }

    
    public function sendLoginAttemptNotification($email, $attemptNumber) {
        try {
            // Debug logging
            error_log("=== LOGIN ATTEMPT EMAIL DEBUG START ===");
            error_log("Attempting to send login attempt notification to: " . $email);
            error_log("Attempt number: " . $attemptNumber);
            
            // First check if the email exists in the database
            $user = $this->db->Select("SELECT * FROM users WHERE email = ? AND deleted = 0", array($email));
            error_log("Database query result count: " . count($user));
            
            if (count($user) > 0) {
                // Email exists in database, proceed with sending notification
                $userData = $user[0];
                $userName = isset($userData['account_first_name']) ? $userData['account_first_name'] : 'User';
                $currentTime = date('Y-m-d H:i:s');
                $userIP = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Browser';
                
                error_log("User found - Name: " . $userName . ", Email: " . $email);
                error_log("IP: " . $userIP . ", Time: " . $currentTime);
                
                // Determine the alert level based on attempt number
                $alertLevel = 'info';
                $alertColor = '#3498db';
                $alertIcon = '🔐';
                $alertTitle = 'Login Attempt Detected';
                
                if ($attemptNumber >= 3) {
                    $alertLevel = 'critical';
                    $alertColor = '#e74c3c';
                    $alertIcon = '🚨';
                    $alertTitle = 'Multiple Failed Login Attempts!';
                } elseif ($attemptNumber >= 2) {
                    $alertLevel = 'warning';
                    $alertColor = '#f39c12';
                    $alertIcon = '⚠️';
                    $alertTitle = 'Suspicious Login Activity';
                }
                
                $subject = "Security Alert: Login Attempt on Your Account";
                
                $emailBody = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9;">
                    <div style="background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <div style="text-align: center; margin-bottom: 30px;">
                            <div style="background-color: ' . $alertColor . '; width: 80px; height: 80px; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                                <span style="color: white; font-size: 40px;">' . $alertIcon . '</span>
                            </div>
                        </div>
                        
                        <h2 style="color: #333; text-align: center; margin-bottom: 20px;">' . $alertTitle . '</h2>
                        
                        <p style="color: #666; font-size: 16px; line-height: 1.6;">Hello ' . htmlspecialchars($userName) . ',</p>
                        
                        <p style="color: #666; font-size: 16px; line-height: 1.6;">
                            We detected a login attempt on your account. This is attempt number <strong>' . $attemptNumber . '</strong>.
                        </p>
                        
                        <div style="background-color: ' . ($alertLevel === 'critical' ? '#fee' : ($alertLevel === 'warning' ? '#fff3cd' : '#e7f3ff')) . '; border: 1px solid ' . $alertColor . '; border-radius: 8px; padding: 20px; margin: 20px 0;">
                            <h4 style="color: ' . $alertColor . '; margin: 0 0 15px 0;">
                                <span style="margin-right: 10px;">📍</span>Login Details:
                            </h4>
                            <ul style="color: ' . $alertColor . '; margin: 0; padding-left: 20px;">
                                <li><strong>Time:</strong> ' . $currentTime . '</li>
                                <li><strong>IP Address:</strong> ' . htmlspecialchars($userIP) . '</li>
                                <li><strong>Browser:</strong> ' . htmlspecialchars($userAgent) . '</li>
                                <li><strong>Attempt Number:</strong> ' . $attemptNumber . '</li>
                            </ul>
                        </div>';
                
                // Add specific messages based on attempt number
                if ($attemptNumber >= 3) {
                    $emailBody .= '
                        <div style="background-color: #fee; border: 1px solid #e74c3c; border-radius: 8px; padding: 20px; margin: 20px 0;">
                            <h4 style="color: #c0392b; margin: 0 0 15px 0;">
                                <span style="margin-right: 10px;">🔒</span>Account Locked:
                            </h4>
                            <p style="color: #c0392b; margin: 0;">
                                Your account has been temporarily locked for 30 seconds due to multiple failed login attempts. 
                                This is a security measure to protect your account.
                            </p>
                        </div>';
                }
                
                $emailBody .= '
                        <div style="background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; padding: 20px; margin: 20px 0;">
                            <h4 style="color: #0c5460; margin: 0 0 15px 0;">
                                <span style="margin-right: 10px;">🛡️</span>What to do:
                            </h4>
                            <ul style="color: #0c5460; margin: 0; padding-left: 20px;">
                                <li><strong>If this was you:</strong> No action needed. You can continue logging in.</li>
                                <li><strong>If this wasn\'t you:</strong> Change your password immediately and enable two-factor authentication.</li>
                                <li><strong>Forgot password?</strong> Use the "Forgot Password" feature on the login page.</li>
                            </ul>
                        </div>
                        
                        <div style="background-color: #f8f9fa; border-radius: 8px; padding: 15px; margin: 20px 0;">
                            <p style="color: #666; font-size: 14px; margin: 0;">
                                <strong>Tip:</strong> Always use a strong, unique password and never share your credentials with anyone.
                            </p>
                        </div>
                        
                        <p style="color: #999; font-size: 14px; text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            This is an automated security notification from ' . ($_ENV['APP_NAME'] ?? 'Your Application') . '. Please do not reply to this email.
                        </p>
                    </div>
                </div>';
                
                error_log("About to send login attempt email with subject: " . $subject);
                
                // Check if componentHelper exists
                if (!isset($this->componentHelper)) {
                    error_log("ERROR: componentHelper is not initialized!");
                    return false;
                }
                
                // Send the email using the existing email helper
                $emailResult = $this->componentHelper->sentToEmail($email, $subject, $emailBody);
                error_log("Email sending result: " . ($emailResult ? "SUCCESS" : "FAILED"));
                
                // Log the notification
                error_log("Login attempt notification sent to: " . $email . " at " . $currentTime);
                error_log("=== LOGIN ATTEMPT EMAIL DEBUG END ===");
                
                return $emailResult;
                
            } else {
                // Email doesn't exist in database, don't send email to prevent spam
                error_log("Login attempt with non-existent email: " . $email . " - Notification not sent");
                error_log("=== LOGIN ATTEMPT EMAIL DEBUG END ===");
                return false;
            }
            
        } catch (Exception $e) {
            // Log error but don't break the login process
            error_log("ERROR: Failed to send login attempt notification: " . $e->getMessage());
            error_log("ERROR: Stack trace: " . $e->getTraceAsString());
            error_log("=== LOGIN ATTEMPT EMAIL DEBUG END ===");
            return false;
        }
    }

    public function sendLockoutWarningEmail($email) {
        try {
            // Debug logging
            error_log("=== EMAIL DEBUG START ===");
            error_log("Attempting to send lockout warning to: " . $email);
            
            // First check if the email exists in the database to prevent spam
            $user = $this->db->Select("SELECT * FROM users WHERE email = ? AND deleted = 0", array($email));
            error_log("Database query result count: " . count($user));
            
            if (count($user) > 0) {
                // Email exists in database, proceed with sending warning
                $userData = $user[0];
                $userName = isset($userData['account_first_name']) ? $userData['account_first_name'] : 'User';
                $currentTime = date('Y-m-d H:i:s');
                $userIP = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Browser';
                
                error_log("User found - Name: " . $userName . ", Email: " . $email);
                error_log("IP: " . $userIP . ", Time: " . $currentTime);
                
                $subject = "Security Alert: Multiple Login Attempts Detected";
                
                $emailBody = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9;">
                    <div style="background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <div style="text-align: center; margin-bottom: 30px;">
                            <div style="background-color: #ff6b6b; width: 80px; height: 80px; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                                <span style="color: white; font-size: 40px;">⚠️</span>
                            </div>
                        </div>
                        
                        <h2 style="color: #333; text-align: center; margin-bottom: 20px;">Security Alert</h2>
                        
                        <p style="color: #666; font-size: 16px; line-height: 1.6;">Hello ' . htmlspecialchars($userName) . ',</p>
                        
                        <p style="color: #666; font-size: 16px; line-height: 1.6;">
                            We detected multiple failed login attempts to your account. For your security, we have temporarily locked login attempts from this location.
                        </p>
                        
                        <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0;">
                            <h4 style="color: #856404; margin: 0 0 15px 0;">
                                <span style="margin-right: 10px;">🔒</span>Security Details:
                            </h4>
                            <ul style="color: #856404; margin: 0; padding-left: 20px;">
                                <li><strong>Time:</strong> ' . $currentTime . '</li>
                                <li><strong>IP Address:</strong> ' . htmlspecialchars($userIP) . '</li>
                                <li><strong>Browser:</strong> ' . htmlspecialchars($userAgent) . '</li>
                                <li><strong>Action:</strong> Account temporarily locked for 30 seconds</li>
                            </ul>
                        </div>
                        
                        <div style="background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; padding: 20px; margin: 20px 0;">
                            <h4 style="color: #0c5460; margin: 0 0 15px 0;">
                                <span style="margin-right: 10px;">🛡️</span>What to do next:
                            </h4>
                            <ul style="color: #0c5460; margin: 0; padding-left: 20px;">
                                <li>If this was you, please wait 30 seconds and try again with the correct password</li>
                                <li>If this wasn\'t you, consider changing your password immediately</li>
                                <li>If you forgot your password, use the "Forgot Password" feature</li>
                            </ul>
                        </div>
                        
                        
                        
                        <p style="color: #999; font-size: 14px; text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            This is an automated security message from ' . $_ENV['APP_NAME'] . '. Please do not reply to this email.
                        </p>
                    </div>
                </div>';
                
                error_log("About to send email with subject: " . $subject);
                error_log("Email body length: " . strlen($emailBody) . " characters");
                
                // Check if componentHelper exists
                if (!isset($this->componentHelper)) {
                    error_log("ERROR: componentHelper is not initialized!");
                    return false;
                }
                
                // Send the email using the existing email helper
                $emailResult = $this->componentHelper->sentToEmail($email, $subject, $emailBody);
                error_log("Email sending result: " . ($emailResult ? "SUCCESS" : "FAILED"));
                
                // Log the warning email sent (optional)
                error_log("Security warning email sent to: " . $email . " at " . $currentTime);
                error_log("=== EMAIL DEBUG END ===");
                
                return $emailResult;
                
            } else {
                // Email doesn't exist in database, don't send email to prevent spam
                error_log("Login attempt with non-existent email: " . $email . " - Warning email not sent");
                error_log("=== EMAIL DEBUG END ===");
                return false;
            }
            
        } catch (Exception $e) {
            // Log error but don't break the login process
            error_log("ERROR: Failed to send lockout warning email: " . $e->getMessage());
            error_log("ERROR: Stack trace: " . $e->getTraceAsString());
            error_log("=== EMAIL DEBUG END ===");
            return false;
        }
    }
    
    public function test() {
       echo "123";
    }

    // Test method to check email functionality
    public function testEmail() {
        echo "<h2>Email System Test</h2>";
        
        // Test 1: Check if ComponentHelper is available
        echo "<h3>Test 1: ComponentHelper Check</h3>";
        if (isset($this->componentHelper)) {
            echo "✅ ComponentHelper is initialized<br>";
            
            // Check if the sentToEmail method exists
            if (method_exists($this->componentHelper, 'sentToEmail')) {
                echo "✅ sentToEmail method exists<br>";
            } else {
                echo "❌ sentToEmail method does NOT exist<br>";
                echo "Available methods: " . implode(', ', get_class_methods($this->componentHelper)) . "<br>";
            }
        } else {
            echo "❌ ComponentHelper is NOT initialized<br>";
        }
        
        // Test 2: Check database connection
        echo "<h3>Test 2: Database Check</h3>";
        try {
            $testQuery = $this->db->Select("SELECT COUNT(*) as count FROM users WHERE deleted = 0");
            echo "✅ Database connection working - Found " . $testQuery[0]['count'] . " active users<br>";
        } catch (Exception $e) {
            echo "❌ Database error: " . $e->getMessage() . "<br>";
        }
        
        // Test 3: Environment variables
        echo "<h3>Test 3: Environment Variables</h3>";
        echo "APP_NAME: " . ($_ENV['APP_NAME'] ?? 'NOT SET') . "<br>";
        echo "URL_HOST: " . ($_ENV['URL_HOST'] ?? 'NOT SET') . "<br>";
        
        // Test 4: Check PHP error logs location
        echo "<h3>Test 4: Error Logs</h3>";
        echo "Error log file: " . ini_get('error_log') . "<br>";
        echo "Log errors enabled: " . (ini_get('log_errors') ? 'YES' : 'NO') . "<br>";
        
        // Test 5: Manual email test (if GET parameter is provided)
        if (isset($_GET['test_email']) && !empty($_GET['test_email'])) {
            echo "<h3>Test 5: Manual Email Test</h3>";
            $testEmail = $_GET['test_email'];
            echo "Testing email to: " . htmlspecialchars($testEmail) . "<br>";
            
            $result = $this->sendLockoutWarningEmail($testEmail);
            echo "Email test result: " . ($result ? "✅ SUCCESS" : "❌ FAILED") . "<br>";
            echo "Check error logs for detailed information.<br>";
        } else {
            echo "<h3>Test 5: Manual Email Test</h3>";
            echo "To test email, add ?test_email=your@email.com to the URL<br>";
        }
        
        // Test 6: Session information
        echo "<h3>Test 6: Session Information</h3>";
        echo "Login attempts: " . ($_SESSION['login_attempts'] ?? 'Not set') . "<br>";
        echo "Last attempt time: " . ($_SESSION['last_attempt_time'] ?? 'Not set') . "<br>";
        echo "Attempted email: " . ($_SESSION['attempted_email'] ?? 'Not set') . "<br>";
        
        echo "<hr>";
        echo "<p><strong>Instructions:</strong></p>";
        echo "<ol>";
        echo "<li>Check the error logs for detailed debug information</li>";
        echo "<li>Test email by visiting: <code>/test?test_email=your@email.com</code></li>";
        echo "<li>Make sure the email exists in your database</li>";
        echo "<li>Check your spam folder</li>";
        echo "</ol>";
    }

}
