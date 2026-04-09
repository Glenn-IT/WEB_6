<?php 


class UserManagementController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "UserManagementController";
    }


    public function index() {

        $data = [];

        $data["list"] = $this->db->Select("select * from users where deleted = 0 and user_id != 1 and user_type != 5 ", array() );
        
        return ["content" => loadView('components/'.$this->view.'/views/custom', $data)];

    }
    public function js(){
        return [
            $this->view.'/js/custom.js',
        ];
    }

    public function css(){
        return [];
    }

    public function source() {
        $data = getRequestAll();

        extract($data);

        $d["details"] = false;

        if($action == "edit" && ($id != '' || $id != 'undefined') ) {
            $result = $this->db->Select("select * from users where user_id = ?", array($id) )[0];
            $d["details"] = $result;
        }

        $res = [
            'header'=> (isset($action) && $action == "add") ? "Add User" : 'Edit User',
            "html" => loadView('components/'.$this->view.'/views/modal_details', $d),
            'button' => '<button class="btn btn-primary" type="submit">Submit form</button>',
            'action' => 'afterSubmit'
        ];

        echo json_encode($res);
    }

    public function afterSubmit(){
        $data = getRequestAll();

        extract($data);

        // Check if it's an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        // Validate required fields
        $username = isset($username) ? trim($username) : '';
        $email = isset($email) ? trim($email) : '';
        $password = isset($password) ? trim($password) : '';
        $confirm_password = isset($confirm_password) ? trim($confirm_password) : '';
        $contact_no = isset($contact_no) ? trim($contact_no) : '';
        $security_question = isset($security_question) ? trim($security_question) : '';
        $security_answer = isset($security_answer) ? trim($security_answer) : '';

        // Check for empty fields
        if(empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($contact_no) || empty($security_question) || empty($security_answer)) {
            if ($isAjax) {
                $res = [
                    'status' => false,
                    'type' => 'warning',
                    'title' => 'Validation Error',
                    'text' => 'All fields are required! Please fill in all fields.'
                ];
                echo json_encode($res);
                exit();
            } else {
                header('Location: index?type=error&message=All fields are required!');
                exit();
            }
        }

        // Check if passwords match
        if($password !== $confirm_password) {
            if ($isAjax) {
                $res = [
                    'status' => false,
                    'type' => 'warning',
                    'title' => 'Validation Error',
                    'text' => 'Passwords do not match! Please enter the same password in both fields.'
                ];
                echo json_encode($res);
                exit();
            } else {
                header('Location: index?type=error&message=Passwords do not match!');
                exit();
            }
        }

        // Validate security answer length
        if(strlen($security_answer) < 2) {
            if ($isAjax) {
                $res = [
                    'status' => false,
                    'type' => 'warning',
                    'title' => 'Validation Error',
                    'text' => 'Security answer must be at least 2 characters long.'
                ];
                echo json_encode($res);
                exit();
            } else {
                header('Location: index?type=error&message=Security answer must be at least 2 characters long!');
                exit();
            }
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($isAjax) {
                $res = [
                    'status' => false,
                    'type' => 'warning',
                    'title' => 'Validation Error',
                    'text' => 'Please enter a valid email address!'
                ];
                echo json_encode($res);
                exit();
            } else {
                header('Location: index?type=error&message=Please enter a valid email address!');
                exit();
            }
        }

        // Validate contact number (11 digits)
        $contact_no = preg_replace('/[^0-9]/', '', $contact_no);
        if (strlen($contact_no) !== 11) {
            if ($isAjax) {
                $res = [
                    'status' => false,
                    'type' => 'warning',
                    'title' => 'Validation Error',
                    'text' => 'Contact number must be exactly 11 digits!'
                ];
                echo json_encode($res);
                exit();
            } else {
                header('Location: index?type=error&message=Contact number must be exactly 11 digits!');
                exit();
            }
        }

        if(isset($user_id)) {
            //edit 
            
            // Check if email already exists for other users (excluding current user)
            $existingEmail = $this->db->Select("select * from users where email = ? and user_id != ? and deleted = 0", array($email, $user_id));
            if(count($existingEmail) > 0){
                if ($isAjax) {
                    $res = [
                        'status' => false,
                        'type' => 'warning',
                        'title' => 'Duplicate Entry',
                        'text' => 'Email already exists! Please use another email.'
                    ];
                    echo json_encode($res);
                    exit();
                } else {
                    header('Location: index?type=error&message=Email already exists! Please use another email.');
                    exit();
                }
            }

            // Check if username already exists for other users (excluding current user)
            $existingUsername = $this->db->Select("select * from users where username = ? and user_id != ? and deleted = 0", array($username, $user_id));
            if(count($existingUsername) > 0){
                if ($isAjax) {
                    $res = [
                        'status' => false,
                        'type' => 'warning',
                        'title' => 'Duplicate Entry',
                        'text' => 'Username already exists! Please use another username.'
                    ];
                    echo json_encode($res);
                    exit();
                } else {
                    header('Location: index?type=error&message=Username already exists! Please use another username.');
                    exit();
                }
            }

            $this->db->Update("update users SET username = ?, email = ?, password = ?, user_type = ?, status = ?, contact_no = ?, security_question = ?, security_answer = ? WHERE user_id = ? ",
             array($username,  $email, $password, $user_type, $status, $contact_no, $security_question, $security_answer, $user_id  ));
  
            if ($isAjax) {
                $res = [
                    'status' => true,
                    'type' => 'success',
                    'title' => 'Success',
                    'text' => 'User updated successfully!',
                    'function' => 'reloadPage'
                ];
                echo json_encode($res);
                exit();
            } else {
                header('Location: index?type=success&message=Successfully Updated!');
                exit();
            }
        } else {
            // Check if email already exists
            $existingEmail = $this->db->Select("select email from users where email = ? and deleted = 0", array($email) );
            if(count($existingEmail) > 0){
                if ($isAjax) {
                    $res = [
                        'status' => false,
                        'type' => 'warning',
                        'title' => 'Duplicate Entry',
                        'text' => 'Email already exists! Please use another email.'
                    ];
                    echo json_encode($res);
                    exit();
                } else {
                    header('Location: index?type=warning&message=Email already exist!.Please user another email!');
                    exit();
                }
            }

            // Check if username already exists
            $existingUsername = $this->db->Select("select username from users where username = ? and deleted = 0", array($username));
            if(count($existingUsername) > 0){
                if ($isAjax) {
                    $res = [
                        'status' => false,
                        'type' => 'warning',
                        'title' => 'Duplicate Entry',
                        'text' => 'Username already exists! Please use another username.'
                    ];
                    echo json_encode($res);
                    exit();
                } else {
                    header('Location: index?type=warning&message=Username already exists! Please use another username.');
                    exit();
                }
            }

            $token = generateToken();
            $this->db->Insert("INSERT INTO users (`email`,`username`,`password`, `token`, `user_type`, `contact_no`, `security_question`, `security_answer`) VALUES (?,?,?,?,?,?,?,?)", [
                $email,
                $username,
                $password,
                $token,
                $user_type,
                $contact_no,
                $security_question,
                $security_answer
            ]);
            
            if ($isAjax) {
                $res = [
                    'status' => true,
                    'type' => 'success',
                    'title' => 'Success',
                    'text' => 'User added successfully!',
                    'function' => 'reloadPage'
                ];
                echo json_encode($res);
                exit();
            } else {
                header('Location: index?type=success&message=Successfully Registered!');
                exit();
            }
        }
        
    }

    public function delete(){
        $data = getRequestAll();

        extract($data);

        $this->db->Update("update users SET deleted = 1 WHERE user_id = ? ", array( $id) );


        $res = [
            'status'=> true,
            'msg' => 'Successfully deleted!'
        ];

        echo json_encode($res);
    }


    

    public function archived(){
        $data = getRequestAll();

        extract($data);

        $this->db->Update("update users SET deleted = 2 WHERE user_id = ? ", array( $id) );

        $res = [
            'status'=> true,
            'msg' => 'Successfully Archived!'
        ];

        echo json_encode($res);
    }


}
