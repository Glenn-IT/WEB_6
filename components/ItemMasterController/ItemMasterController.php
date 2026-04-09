<?php 


class ItemMasterController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "ItemMasterController";
    }


    public function index() {
        $data = [];

        $data["list"] = $this->db->Select("select t.* , b.`name` as brand_name
        FROM items t
        inner join brand b ON t.brand_id = b.id where t.deleted = 0", array() );
        
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
        $d["list"] = [];
        if($action == "edit" && ($id != '' || $id != 'undefined') ) {
            $result = $this->db->Select("select * from items where item_id = ?", array($id) )[0];
            $d["details"] = $result;
            $result = $this->db->Select("select * from details_financing where item_id = ?", array($id) );
            $d["list"] =$result;
        }

        $brand = $this->db->Select("select id,  `name` from brand where deleted = ?", array(0) );
        $colors = $this->db->Select("select id,`name`  from color where deleted = ?", array(0) );
        $category = $this->db->Select("select service_id id ,description as `name`  from service_type where deleted = ?", array(0) );
        $size = $this->db->Select("select id , `name`  from size where deleted = ?", array(0) );

        $d["brand"] = $brand;
        $d["colors"] = $colors;
        $d["category"] = $category;
        $d["size"] = $size;

        $res = [
            'header'=> (isset($action) && $action == "add") ? "Sub-service" : 'Sub-service',
            "html" => loadView('components/'.$this->view.'/views/modal_details', $d),
            'button' => '<button class="btn btn-primary" type="submit">Submit form</button>',
            'action' => 'afterSubmit',
            'size' => 'modal-lg'
        ];

        echo json_encode($res);
    }

    public function afterSubmit(){
        $data = getRequestAll();
        
        extract($data);

        // Server-side validation for duplicates
        if (!empty($item_code)) {
            $codeQuery = "SELECT item_id FROM items WHERE item_code = ? AND deleted = 0";
            $codeParams = array($item_code);
            
            if (isset($id) && !empty($id)) {
                $codeQuery .= " AND item_id != ?";
                $codeParams[] = $id;
            }
            
            $codeResult = $this->db->Select($codeQuery, $codeParams);
            
            if (count($codeResult) > 0) {
                header('Location: index?type=error&message=Item Code "' . $item_code . '" already exists!');
                exit();
            }
        }
        
        if (!empty($item_name)) {
            $nameQuery = "SELECT item_id FROM items WHERE item_name = ? AND deleted = 0";
            $nameParams = array($item_name);
            
            if (isset($id) && !empty($id)) {
                $nameQuery .= " AND item_id != ?";
                $nameParams[] = $id;
            }
            
            $nameResult = $this->db->Select($nameQuery, $nameParams);
            
            if (count($nameResult) > 0) {
                header('Location: index?type=error&message=Item Name "' . $item_name . '" already exists!');
                exit();
            }
        }

        if(isset($id)) {
            //edit 
            $table = "items";
            $update = [
                'status' =>  $status,
                'item_type' =>  $item_type,
                'item_code' =>  $item_code,
                'item_name' =>  $item_name,
                'item_description' =>  $item_description,
                'price' =>  $price,
                'brand_id' =>  $brand_id,
            ];

            $folder = 'src/images/products/uploads/';

            $uploadedPaths = $this->db->handleMultipleFileUpload($img_255x200_home, $folder);
            $uploadedNew = implode('|', $uploadedPaths); // Concatenate paths with "|"
            if(count($uploadedPaths) > 0) {
                $update["img_255x200_home"] = $uploadedNew;
            }

            $img_400x541_shop_new = $this->db->handleMultipleFileUpload($img_400x541_shop, $folder);
            $uploadedNew_shop = implode('|', $img_400x541_shop_new); // Concatenate paths with "|"
            if(count($img_400x541_shop_new) > 0) {
                $update["img_400x541_shop"] = $uploadedNew_shop;
            }


            $img_700x700_product_details_new = $this->db->handleMultipleFileUpload($img_700x700_product_details, $folder);
            $uploadedNew_prod = implode('|', $img_700x700_product_details_new); // Concatenate paths with "|"
            if(count($img_700x700_product_details_new) > 0) {
                $update["img_700x700_product_details"] = $uploadedNew_prod;
            }
            $where = [
                'item_id' => $id,
            ];
            $this->updateItem($table, $update, $where);


            $table = "details_financing";
            foreach ($finance as $key => $value) {
                $value["item_id"] = $id; 

                if(isset($value["primary_id"])) {

                    $where = [
                        'id' => $value["primary_id"],
                    ];

                    unset($value["primary_id"]);
                    unset($value["item_id"]);

                    $this->updateItem($table, $value, $where);
                } else {
                    $this->db->insertRequestBatchRquest($value,'details_financing');
                }

            }


  
            header('Location: index?type=success&message=Successfully Updated!');
            exit();
        } else {
            $finance = isset($data["finance"]) ? $data["finance"] : [];
          
            unset($data["finance"]);

            $this->db->insertRequestBatchRquest($data,'items','src/images/products/uploads/');


            $result = $this->db->Select("select * FROM items where item_code = ?  ", array($data["item_code"]) )[0];

            // $table = "details_financing";
            // foreach ($finance as $key => $value) {
            //     $value["item_id"] =  $result["item_id"];

            //     $this->db->insertRequestBatchRquest($value,'details_financing');
            // }

            header('Location: index?type=success&message=Successfully Registered!');
            exit();
        }
        
    }

    public function delete(){
        $data = getRequestAll();

        extract($data);

        $this->db->Update("update items SET deleted = 1 WHERE item_id = ? ", array( $id) );


        $res = [
            'status'=> true,
            'msg' => 'Successfully deleted!'
        ];

        echo json_encode($res);
    }

    public function validateDuplicate() {
        $data = getRequestAll();
        extract($data);
        
        $response = ['valid' => true, 'message' => ''];
        
        // Check for duplicate item_code
        if (!empty($item_code)) {
            $codeQuery = "SELECT item_id FROM items WHERE item_code = ? AND deleted = 0";
            $codeParams = array($item_code);
            
            // If editing, exclude current item from check
            if (!empty($item_id)) {
                $codeQuery .= " AND item_id != ?";
                $codeParams[] = $item_id;
            }
            
            $codeResult = $this->db->Select($codeQuery, $codeParams);
            
            if (count($codeResult) > 0) {
                $response['valid'] = false;
                $response['message'] = 'Item Code "' . $item_code . '" already exists. Please use a different code.';
                echo json_encode($response);
                return;
            }
        }
        
        // Check for duplicate item_name
        if (!empty($item_name)) {
            $nameQuery = "SELECT item_id FROM items WHERE item_name = ? AND deleted = 0";
            $nameParams = array($item_name);
            
            // If editing, exclude current item from check
            if (!empty($item_id)) {
                $nameQuery .= " AND item_id != ?";
                $nameParams[] = $item_id;
            }
            
            $nameResult = $this->db->Select($nameQuery, $nameParams);
            
            if (count($nameResult) > 0) {
                $response['valid'] = false;
                $response['message'] = 'Item Name "' . $item_name . '" already exists. Please use a different name.';
                echo json_encode($response);
                return;
            }
        }
        
        echo json_encode($response);
    }

    public function updateItem($table, $data, $where) {
        // Validate the inputs
        if (empty($table) || empty($data) || empty($where)) {
            throw new Exception("Table, data, and where clause are required.");
        }
    
        // Dynamically build the update query
        $fields = [];
        $values = [];
        foreach ($data as $field => $value) {
            $fields[] = "$field = ?";
            $values[] = $value;
        }
    
        // Add the WHERE clause values
        foreach ($where as $key => $value) {
            $values[] = $value;
        }
    
        // Construct the query
        $query = "UPDATE $table SET " . implode(', ', $fields) . " WHERE " . implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($where)));
    
        // Execute the query using the database instance
        return $this->db->update($query, $values);
    }


}
