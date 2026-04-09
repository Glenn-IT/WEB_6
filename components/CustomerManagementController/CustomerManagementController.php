<?php 


class CustomerManagementController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "CustomerManagementController";
    }

    public function index() {

        $data = [];

        $data["list"] = $this->db->Select("select * from users where deleted = 0 and user_type = 5", array() );
        
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
            'header'=> 'Block Management',
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

            $this->db->Update("update users SET status = ? WHERE user_id = ? ",
             array( $status, $user_id  ));
            header('Location: index?type=success&message=Successfully Updated!');
            exit();
        } 
        
    }






}
