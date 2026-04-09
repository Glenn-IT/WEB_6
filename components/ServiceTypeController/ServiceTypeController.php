<?php 


class ServiceTypeController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "ServiceTypeController";
    }


    public function index() {
        $data = [];

        $data["list"] = $this->db->Select("select * from service_type where deleted = 0", array() );
        
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
            $result = $this->db->Select("select * from service_type where service_id = ?", array($id) )[0];
            $d["details"] = $result;
        }

        $res = [
            'header'=> (isset($action) && $action == "add") ? "Add Item Category" : 'Edit Item Category',
            "html" => loadView('components/'.$this->view.'/views/modal_details', $d),
            'button' => '<button class="btn btn-primary" type="submit">Submit form</button>',
            'action' => 'afterSubmit'
        ];

        echo json_encode($res);
    }

    public function afterSubmit(){
        $data = getRequestAll();

        extract($data);

        if(isset($service_id)) {
            //edit 

            $this->db->Update("update service_type SET code = ?, description = ? WHERE service_id = ? ",
             array($code,  $description, $service_id  ));
  
            header('Location: index?type=success&message=Successfully Updated!');
            exit();
        } else {
          

            $this->db->Insert("INSERT INTO service_type (`code`,`description`) VALUES (?,?)", [
                $code,
                $description
            ]);
            
            header('Location: index?type=success&message=Successfully Registered!');
            exit();
        }
        
    }

    public function delete(){
        $data = getRequestAll();

        extract($data);

        $this->db->Update("update service_type SET deleted = 1 WHERE service_id = ? ", array( $id) );


        $res = [
            'status'=> true,
            'msg' => 'Successfully deleted!'
        ];

        echo json_encode($res);
    }


  

}
