<?php 


class UserArchiveController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "UserArchiveController";
    }


    public function index() {

        $data = [];

        $data["list"] = $this->db->Select("select * from users where deleted = 2 and user_id != 1 and user_type != 5 ", array() );
        
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

        if(isset($user_id)) {
            //edit 

            $this->db->Update("update users SET username = ?, email = ?, password = ?, user_type = ?, status = ?, contact_no = ? WHERE user_id = ? ",
             array($username,  $email, $password, $user_type, $status, $contact_no, $user_id  ));
  
            header('Location: index?type=success&message=Successfully Updated!');
            exit();
        } else {
            $data = $this->db->Select("select email from users where email = ? and deleted = 0", array($email) );
            if(count($data) > 0){

                header('Location: index?type=warning&message=Email already exist!.Please user another email!');
                exit();

            } else {

                $token = generateToken();
                $this->db->Insert("INSERT INTO users (`email`,`username`,`password`, `token`, `user_type`, `contact_no`) VALUES (?,?,?,?,?,?)", [
                    $email,
                    $username,
                    $password,
                    $token,
                    $user_type,
                    $contact_no
                ]);
                
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

        $this->db->Update("update users SET deleted = 0 WHERE user_id = ? ", array( $id) );

        $res = [
            'status'=> true,
            'msg' => 'Successfully Restored!'
        ];

        echo json_encode($res);
    }


}
