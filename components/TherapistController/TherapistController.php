<?php 


class TherapistController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "TherapistController";
    }


    public function index() {
        $data = [];

        $data["list"] = $this->db->Select("select t.*, b.`name` as ser_type from therapist t 
            left join brand b ON b.id = t.service_id
            where t.deleted = 0", array() );
        
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
            $result = $this->db->Select("select * from therapist where id = ?", array($id) )[0];
            $d["details"] = $result;
        }
        $d["brand"] = $this->db->Select("select id,  `name` from brand where deleted = ?", array(0) );

        $res = [
            'header'=> (isset($action) && $action == "add") ? "Add" : 'Edit',
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

        // Validate name input - trim whitespace and check if empty
        $name = isset($name) ? trim($name) : '';
        
        if(empty($name) || strlen($name) === 0 || !preg_match('/\S/', $name)) {
            if ($isAjax) {
                $res = [
                    'status' => false,
                    'type' => 'warning',
                    'title' => 'Validation Error',
                    'text' => 'Therapist name cannot be empty or contain only spaces!'
                ];
                echo json_encode($res);
                exit();
            } else {
                header('Location: index?type=error&message=Therapist name cannot be empty or contain only spaces!');
                exit();
            }
        }

        if(isset($id)) {
            //edit 
            // Check if combination of service_id and name already exists for other records (excluding current record)
            $existingTherapist = $this->db->Select("select * from therapist where service_id = ? AND name = ? AND id != ? AND deleted = 0", array($service_id, $name, $id));
            
            if(count($existingTherapist) > 0) {
                if ($isAjax) {
                    $res = [
                        'status' => false,
                        'type' => 'warning',
                        'title' => 'Duplicate Entry',
                        'text' => 'This therapist name already exists for the selected service type!'
                    ];
                    echo json_encode($res);
                    exit();
                } else {
                    header('Location: index?type=error&message=This therapist name already exists for the selected service type!');
                    exit();
                }
            }

            $this->db->Update("update therapist SET name = ?, service_id = ?  WHERE id = ? ",
             array($name,  $service_id, $id  ));
  
            if ($isAjax) {
                $res = [
                    'status' => true,
                    'type' => 'success',
                    'title' => 'Success',
                    'text' => 'Therapist updated successfully!',
                    'function' => 'reloadPage'
                ];
                echo json_encode($res);
                exit();
            } else {
                header('Location: index?type=success&message=Successfully Updated!');
                exit();
            }
        } else {
            // Check if combination of service_id and name already exists
            $existingTherapist = $this->db->Select("select * from therapist where service_id = ? AND name = ? AND deleted = 0", array($service_id, $name));
            
            if(count($existingTherapist) > 0) {
                if ($isAjax) {
                    $res = [
                        'status' => false,
                        'type' => 'warning',
                        'title' => 'Duplicate Entry',
                        'text' => 'This therapist name already exists for the selected service type!'
                    ];
                    echo json_encode($res);
                    exit();
                } else {
                    header('Location: index?type=error&message=This therapist name already exists for the selected service type!');
                    exit();
                }
            }

            $this->db->Insert("INSERT INTO therapist (`name`,`service_id`) VALUES (?,?)", [
                $name,$service_id
            ]);
            
            if ($isAjax) {
                $res = [
                    'status' => true,
                    'type' => 'success',
                    'title' => 'Success',
                    'text' => 'Therapist added successfully!',
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

        $this->db->Update("update therapist SET deleted = 1 WHERE id = ? ", array( $id) );


        $res = [
            'status'=> true,
            'msg' => 'Successfully deleted!'
        ];

        echo json_encode($res);
    }


  

}
