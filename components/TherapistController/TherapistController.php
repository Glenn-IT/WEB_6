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

        $data["list"] = $this->db->Select("SELECT t.*, b.`name` AS ser_type FROM therapist t 
            LEFT JOIN brand b ON b.id = t.service_id
            WHERE t.deleted = 0 ORDER BY t.id ASC", array() );

        // Current group / team photo
        $data["team_photo"] = $this->db->Select(
            "SELECT * FROM site_team_photo WHERE deleted = 0 AND is_active = 1 ORDER BY id DESC LIMIT 1", []
        );
        $data["team_photo"] = count($data["team_photo"]) > 0 ? $data["team_photo"][0] : null;

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

        $folder   = 'src/images/therapist/uploads/';
        $bio      = isset($bio)      ? trim($bio)      : '';
        $position = isset($position) ? trim($position) : '';

        // Handle photo upload
        $photoPath = null;
        if (isset($photo) && is_array($photo) && !empty($photo['name'][0])) {
            $uploaded = $this->db->handleMultipleFileUpload($photo, $folder);
            if (!empty($uploaded)) {
                $photoPath = $uploaded[0];
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

            if ($photoPath) {
                $this->db->Update("UPDATE therapist SET name = ?, service_id = ?, bio = ?, position = ?, photo = ? WHERE id = ?",
                    array($name, $service_id, $bio, $position, $photoPath, $id));
            } else {
                $this->db->Update("UPDATE therapist SET name = ?, service_id = ?, bio = ?, position = ? WHERE id = ?",
                    array($name, $service_id, $bio, $position, $id));
            }
  
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

            $this->db->Insert("INSERT INTO therapist (`name`, `service_id`, `bio`, `position`, `photo`) VALUES (?,?,?,?,?)", [
                $name, $service_id, $bio, $position, $photoPath
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

    /* -------------------------------------------------------
     * uploadTeamPhoto – upload / replace the group team photo
     * ------------------------------------------------------- */
    public function uploadTeamPhoto() {
        $data = getRequestAll();
        extract($data);

        $folder = 'src/images/therapist/team/';

        if (!isset($team_photo) || !is_array($team_photo) || empty($team_photo['name'][0])) {
            header('Location: index?type=warning&message=Please select a photo to upload!');
            exit();
        }

        $uploaded = $this->db->handleMultipleFileUpload($team_photo, $folder);
        if (empty($uploaded)) {
            header('Location: index?type=warning&message=Photo upload failed!');
            exit();
        }

        // Soft-delete previous team photos
        $this->db->Update("UPDATE site_team_photo SET deleted = 1", []);

        $caption = isset($caption) ? trim($caption) : 'Our Team';
        $this->db->Insert(
            "INSERT INTO site_team_photo (image_path, caption, is_active) VALUES (?, ?, 1)",
            [$uploaded[0], $caption]
        );

        header('Location: index?type=success&message=Team photo updated successfully!');
        exit();
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
