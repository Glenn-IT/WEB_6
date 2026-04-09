<?php 


class ColorsTypeController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "ColorsTypeController";
    }


    public function index() {
        $data = [];

        $data["list"] = $this->db->Select("select * from color where deleted = 0", array() );
        
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
            $result = $this->db->Select("select * from color where id = ?", array($id) )[0];
            $d["details"] = $result;
        }

        $res = [
            'header'=> (isset($action) && $action == "add") ? "Add Color" : 'Edit Color',
            "html" => loadView('components/'.$this->view.'/views/modal_details', $d),
            'button' => '<button class="btn btn-primary" type="submit">Submit form</button>',
            'action' => 'afterSubmit'
        ];

        echo json_encode($res);
    }

    public function afterSubmit(){
        $data = getRequestAll();

        extract($data);

        if(isset($id)) {
            //edit 

            $this->db->Update("update color SET name = ?  WHERE id = ? ",
             array($name, $id  ));
  
            header('Location: index?type=success&message=Successfully Updated!');
            exit();
        } else {
          

            $this->db->Insert("INSERT INTO color (`name`) VALUES (?)", [
                $name
            ]);
            
            header('Location: index?type=success&message=Successfully Registered!');
            exit();
        }
        
    }

    public function delete(){
        $data = getRequestAll();

        extract($data);

        $this->db->Update("update color SET deleted = 1 WHERE id = ? ", array( $id) );


        $res = [
            'status'=> true,
            'msg' => 'Successfully deleted!'
        ];

        echo json_encode($res);
    }


  

}
