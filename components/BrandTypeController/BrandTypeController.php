<?php 


class BrandTypeController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "BrandTypeController";
    }


    public function index() {
        $data = [];

        $data["list"] = $this->db->Select("select * from brand where deleted = 0", array() );
        
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
            $result = $this->db->Select("select * from brand where id = ?", array($id) )[0];
            $d["details"] = $result;
        }

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
                    'text' => 'Brand name cannot be empty or contain only spaces!'
                ];
                echo json_encode($res);
                exit();
            } else {
                header('Location: index?type=error&message=Brand name cannot be empty or contain only spaces!');
                exit();
            }
        }

        if(isset($id)) {
            //edit 
            // Check if name already exists for other records (excluding current record)
            $existingBrand = $this->db->Select("select * from brand where name = ? AND id != ? AND deleted = 0", array($name, $id));
            
            if(count($existingBrand) > 0) {
                if ($isAjax) {
                    // Return JSON response for AJAX calls
                    $res = [
                        'status' => false,
                        'type' => 'warning',
                        'title' => 'Duplicate Entry',
                        'text' => 'Brand name already exists!'
                    ];
                    echo json_encode($res);
                    exit();
                } else {
                    header('Location: index?type=error&message=Brand name already exists!');
                    exit();
                }
            }

            $this->db->Update("update brand SET name = ?  WHERE id = ? ",
             array($name, $id  ));

            if ($isAjax) {
                $res = [
                    'status' => true,
                    'type' => 'success',
                    'title' => 'Success',
                    'text' => 'Brand updated successfully!',
                    'function' => 'reloadPage'
                ];
                echo json_encode($res);
                exit();
            } else {
                header('Location: index?type=success&message=Successfully Updated!');
                exit();
            }
        } else {
            // Check if name already exists
            $existingBrand = $this->db->Select("select * from brand where name = ? AND deleted = 0", array($name));
            
            if(count($existingBrand) > 0) {
                if ($isAjax) {
                    // Return JSON response for AJAX calls
                    $res = [
                        'status' => false,
                        'type' => 'warning',
                        'title' => 'Duplicate Entry',
                        'text' => 'Brand name already exists!'
                    ];
                    echo json_encode($res);
                    exit();
                } else {
                    header('Location: index?type=error&message=Brand name already exists!');
                    exit();
                }
            }

            $this->db->Insert("INSERT INTO brand (`name`) VALUES (?)", [
                $name
            ]);

            if ($isAjax) {
                $res = [
                    'status' => true,
                    'type' => 'success',
                    'title' => 'Success',
                    'text' => 'Brand added successfully!',
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

        $this->db->Update("update brand SET deleted = 1 WHERE id = ? ", array( $id) );


        $res = [
            'status'=> true,
            'msg' => 'Successfully deleted!'
        ];

        echo json_encode($res);
    }


  

}
