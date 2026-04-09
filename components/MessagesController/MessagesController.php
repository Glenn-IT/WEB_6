<?php 


class MessagesController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "MessagesController";
    }


    public function index() {
        $data = $this->db->Select("select u.user_id, u.email, m.description,  m.datetime, m.is_seen  , 
        CASE 
            WHEN TIMESTAMPDIFF(SECOND, m.datetime, NOW()) < 60 THEN 'Just now'
            WHEN TIMESTAMPDIFF(MINUTE, m.datetime, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, m.datetime, NOW()), ' minutes ago')
            WHEN TIMESTAMPDIFF(HOUR, m.datetime, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, m.datetime, NOW()), ' hours ago')
            ELSE DATE_FORMAT(m.datetime, '%Y-%m-%d %H:%i:%s')
        END AS time_ago
        
        from users u
        left join  (
                select sent_id, max(id) id from (
                    SELECT bussiness_owner_id sent_id, max(message_id) id FROM message where user_id = ? group by bussiness_owner_id
                    union 
                    SELECT user_id sent_id , max(message_id) id FROM message where bussiness_owner_id = ? and user_id not in (?) group by user_id
                ) tb group by sent_id 
        ) ct on ct.sent_id = u.user_id
        left join message m ON m.message_id = ct.id where u.user_id != ? and u.user_type = 5
        ", array(1, 1, 1, 1) );
 
        $d["list"] = $data;
        
        return ["content" => loadView('components/'.$this->view.'/views/custom', $d)];
    }
    public function js(){
        return [
            $this->view.'/js/custom.js',
            $this->view.'/js/message.js',

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
            'header'=> (isset($action) && $action == "add") ? "Add Brand" : 'Edit Brand',
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

            $this->db->Update("update brand SET name = ?  WHERE id = ? ",
             array($name, $id  ));
  
            header('Location: index?type=success&message=Successfully Updated!');
            exit();
        } else {
          

            $this->db->Insert("INSERT INTO brand (`name`) VALUES (?)", [
                $name
            ]);
            
            header('Location: index?type=success&message=Successfully Registered!');
            exit();
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

    public function getProductDetails() {
        $data = getRequestAll();
        extract($data);
        
        if (!isset($item_id) || empty($item_id)) {
            echo json_encode(['status' => false, 'message' => 'Item ID is required']);
            return;
        }
        
        try {
            $product = $this->db->Select("
                SELECT 
                    t.item_id,
                    t.item_name,
                    t.description,
                    t.price,
                    t.status,
                    t.img_255x200_home,
                    b.name as brand_name,
                    (SELECT SUM(c.quantity) FROM stock_in c WHERE c.val_status = 'APPROVED' AND c.deleted = 0 AND c.item_id = t.item_id) as total_in,
                    (SELECT SUM(c.quantity) FROM cart c INNER JOIN orders o ON o.order_no = c.order_no WHERE o.val_status = 'COMPLETED' AND c.item_id = t.item_id) as total_sold
                FROM items t
                INNER JOIN brand b ON t.brand_id = b.id
                WHERE t.deleted = 0 AND t.item_id = ?
            ", array($item_id));
            
            if (count($product) > 0) {
                echo json_encode(['status' => true, 'data' => $product[0]]);
            } else {
                echo json_encode(['status' => false, 'message' => 'Product not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
}
