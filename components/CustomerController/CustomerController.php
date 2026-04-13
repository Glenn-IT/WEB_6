<?php 


class CustomerController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "CustomerController";
    }


    public function index() {

        $data = getRequestAll();
        extract($data);
        
        // Handle POST requests first
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($action)) {
                switch($action) {
                    case 'validateCurrentPassword':
                        return $this->validateCurrentPassword();
                    case 'verifySecurityAnswer':
                        return $this->verifySecurityAnswer();
                    case 'debugTest':
                        return $this->debugTest();
                    case 'cancelAppointment':
                        return $this->cancelAppointment();
                    default:
                        // Handle other POST actions
                        return $this->POST();
                }
            }
            return $this->POST();
        }
        
        $data = [];

        $data["header_services"] = $this->db->Select("select * from brand where deleted = 0", array() );


        $data["list"] = $this->db->Select("
        WITH getBiddAmount AS (
            SELECT bidding_id, MAX(quantity) AS max_quantity , count(bidding_id) total_bid_part
            FROM customer_bidding
            GROUP BY bidding_id
        )
        select b.*, i.*, 
        cb.max_quantity,
        ifnull(cb.total_bid_part,0) part
        from bidding b 
        inner join items i ON i.item_id = b.item_id 
        left join getBiddAmount cb ON cb.bidding_id = b.bidding_id
        where  b.deleted = 0 and b.val_status != 'ACKNOWLEDGED'  and TIMESTAMPDIFF(MINUTE, NOW(), b.end_date) > 0 and  b.start_date <= now()", array() );



        $where = "";

        // Add search functionality
        $search_fil = '';
        if(isset($search) && !empty(trim($search))) {
            $search_term = trim($search);
            $search_fil = "and (t.item_name LIKE '%".$search_term."%' OR b.name LIKE '%".$search_term."%')";
        }

        $limit = isset($page) && $page == "shop" ? '' : '';

        $brand_fil = '';
        if(isset($brand_filtering)) {
            // Convert the array into a comma-separated string
            $brand_filtering_values = implode(",", $brand_filtering);
            $brand_fil =  "and b.id in (".$brand_filtering_values.") ";
        }
        $price_fil = '';
        if(isset($price_range)) {
            $value = $price_range;

            // Split the string into two parts
            list($min_price, $max_price) = explode(",", $value);

            // Trim any extra spaces
            $min_price = trim($min_price);
            $max_price = trim($max_price);

            // Construct the SQL query
            $price_fil = "and t.price BETWEEN $min_price AND $max_price";
        }

        $data["listofitems"] = $this->db->Select("select t.* , b.`name` as brand_name ,
        (select sum(c.quantity) from  cart c  INNER JOIN orders o ON o.order_no = c.order_no where o.val_status = 'COMPLETED' and c.item_id = t.item_id ) total_sold,
        (select sum(c.quantity) from  stock_in c    where c.val_status = 'APPROVED' and c.deleted = 0 and c.item_id = t.item_id ) total_in
        FROM items t 
        inner join brand b ON t.brand_id = b.id where t.status = 1 ".$where." and t.item_type = 0 and t.deleted = 0  ".$brand_fil."   ".$price_fil."   ".$search_fil."   order by item_id desc ". $limit, array() );



        $data["listofserveice"] = $this->db->Select("select t.* , b.`name` as brand_name FROM items t 
        inner join brand b ON t.brand_id = b.id where t.status = 1 ".$where."  and t.deleted = 0  ".$brand_fil."   ".$price_fil."   ".$search_fil."   order by item_id desc ". $limit, array() );



        $page = isset($page) && $page != '' ? $page : "custom";


        if(isset($id) &&  $page == "productBidding") {
            $data["list"] = $this->db->Select("
            select  c.quantity,   c.created_at bid_date,   u.email , c.val_status, c.no_attemp_email,
               CONCAT(
                SUBSTRING(u.email, 1, 1), 
                REPEAT('*', CHAR_LENGTH(u.email) - 2), 
                SUBSTRING(u.email, CHAR_LENGTH(u.email), 1)
            ) AS obfuscated_column
            from bidding b 
            inner join items i 
            ON i.item_id = b.item_id 
            inner join customer_bidding c ON c.bidding_id = b.bidding_id
            inner join users u ON u.user_id = c.user_id
            where b.deleted = 0 and b.bidding_id = ? order by c.quantity desc", array($id) );
        }

        if(isset($id) &&  $page == "productItem") {
            $data["list"] = $this->db->Select("select * from details_financing where item_id = ?", array($id) );
          

        }
       

        
        if(isset($type) &&  $page == "specificshop") {
       
            $data["listofserveice"] = $this->db->Select("select t.* , b.`name` as brand_name FROM items t 
            inner join brand b ON t.brand_id = b.id where t.status = 1 ".$where."  and t.deleted = 0  ".$brand_fil."   ".$price_fil." ".$search_fil." and  b.name = '".$type."'  order by item_id desc ". $limit, array() );


        }


        if(isset($_SESSION["user_id"])) {
            $data["bookinglist"] =  $this->db->Select("
                SELECT o.main_order_id, o.orrder_no, o.`date`, o.`time`,  o.val_stattus val_status, o.no_ofhead , ifnull(t.`name`, 'Any Professional') th_name  FROM main_order o 
                left join therapist t ON t.id = o.therapist_id
                where o.user_id = ? and o.deleted = 0 and o.val_stattus not in ('COMPLETED', 'DECLINED')
                order by o.main_order_id desc limit 5
                ", array($_SESSION["user_id"]) );
        } 
    
       


        $view_list = [
            'Messages'=> [ "title" => 'Messages', 'active' => 'menu-link_active', ],
            'Bidding'=> [ "title" => 'Bidding', 'active' => 'menu-link_active', ],
            'Orders'=> [ "title" => 'Orders', 'active' => 'menu-link_active', ],
            'Addresses'=> [ "title" => 'Addresses', 'active' => 'menu-link_active', ],
            'Account'=> [ "title" => 'Account Details', 'active' => 'menu-link_active', ],
            'Wishlist'=> [ "title" => 'Wishlist', 'active' => 'menu-link_active', ],
        ];

        $data["view"] = isset($_SESSION["user_id"]) && (isset($view) && $view !='' && (method_exists($this, $view))) ? $this->{$view}() : [];

        $data["custome"] = (method_exists($this, $page)) ? $this->{$page}() : [];

        // Load dynamic banners & promos (used on the home page)
        $data["banners"] = $this->db->Select(
            "SELECT * FROM site_banners WHERE deleted = 0 AND is_active = 1 ORDER BY sort_order ASC",
            []
        );
        $data["promos"] = $this->db->Select(
            "SELECT p.*, i.item_id AS linked_item_id
             FROM site_promos p
             LEFT JOIN items i ON i.item_id = p.link_service_id
             WHERE p.deleted = 0 AND p.is_active = 1
             ORDER BY p.promo_id ASC",
            []
        );
        
        return ["content" => loadView('components/'.$this->view.'/views/'.$page, $data)];
    }

    public function shop() {
        $brand = $this->db->Select("select * from brand where deleted = 0", array() );
        $price = $this->db->Select("select min(price) min, max(price) max FROM items where deleted = 0 ", array() )[0];
        $res["brand"] = $brand; 
        $res["price"] = $price; 
        return $res;
    }

    public function about() {
        // About page data – loaded from database
        $res = [];
        $rows = $this->db->Select("SELECT section_key, content FROM site_about ORDER BY about_id ASC", []);
        $sections = [];
        foreach ($rows as $row) {
            $sections[$row['section_key']] = $row['content'];
        }

        $res["contact_info"] = [
            "address"   => isset($sections['contact_address']) ? $sections['contact_address'] : 'JPF7+M72, Diversion Road, Tuguegarao City, Cagayan',
            "phone"     => isset($sections['contact_phone'])   ? $sections['contact_phone']   : '09356724821',
            "email"     => isset($sections['contact_email'])   ? $sections['contact_email']   : 'touchandcaremassageandspa@gmail.com',
            "map_embed" => isset($sections['map_embed'])       ? $sections['map_embed']       : '',
        ];

        $res["about_sections"] = $sections;

        // Load therapists for "Who We Are" section
        $res["therapists"] = $this->db->Select(
            "SELECT t.*, b.name AS ser_type
             FROM therapist t
             LEFT JOIN brand b ON b.id = t.service_id
             WHERE t.deleted = 0
             ORDER BY t.id ASC",
            []
        );

        // Team / group photo
        $teamPhoto = $this->db->Select(
            "SELECT * FROM site_team_photo WHERE deleted = 0 AND is_active = 1 ORDER BY id DESC LIMIT 1", []
        );
        $res["team_photo"] = count($teamPhoto) > 0 ? $teamPhoto[0] : null;
        
        $res["developers"] = [
            [
                "name" => "Jude Manangan",
                "contact" => "09157609077",
                "facebook" => "https://www.facebook.com/share/16D3GofTni/",
                "email" => "judegmanangan13@gmail.com"
            ],
            [
                "name" => "Maricris Gaducio",
                "contact" => "09550272754",
                "facebook" => "https://www.facebook.com/share/1EPa2cXCRU/",
                "email" => "mgaducio@gmail.com"
            ]
        ];
        
        return $res;
    }

    public function search() {
        $data = getRequestAll();
        extract($data);
        
        // Initialize search filters
        $search_fil = '';
        if(isset($search) && !empty(trim($search))) {
            $search_term = trim($search);
            $search_fil = "and (t.item_name LIKE '%".$search_term."%' OR b.name LIKE '%".$search_term."%')";
        }
        
        $res["listofitems"] = [];
        $res["listofserveice"] = [];
        
        // Only search if there's a search term
        if(!empty($search_fil)) {
            // Search in items (products)
            $res["listofitems"] = $this->db->Select("select t.* , b.`name` as brand_name ,
            (select sum(c.quantity) from  cart c  INNER JOIN orders o ON o.order_no = c.order_no where o.val_status = 'COMPLETED' and c.item_id = t.item_id ) total_sold,
            (select sum(c.quantity) from  stock_in c    where c.val_status = 'APPROVED' and c.deleted = 0 and c.item_id = t.item_id ) total_in
            FROM items t 
            inner join brand b ON t.brand_id = b.id 
            where t.status = 1 and t.item_type = 0 and t.deleted = 0 ".$search_fil." 
            order by item_id desc", array() );

            // Search in services
            $res["listofserveice"] = $this->db->Select("select t.* , b.`name` as brand_name 
            FROM items t 
            inner join brand b ON t.brand_id = b.id 
            where t.status = 1 and t.deleted = 0 ".$search_fil." 
            order by item_id desc", array() );
        }
        
        return $res;
    }

    public function productBidding(){

        $data = getRequestAll();

        extract($data);

        $r = $this->db->Select("
            WITH getBiddAmount AS (
                SELECT bidding_id, MAX(quantity) AS max_quantity
                FROM customer_bidding
                GROUP BY bidding_id
            )
            select b.*, i.* ,
            CONCAT(
            GREATEST(TIMESTAMPDIFF(DAY, NOW(), b.end_date), 0), ' days, ',
            GREATEST(MOD(TIMESTAMPDIFF(HOUR, NOW(), b.end_date), 24), 0), ' hours, ',
            GREATEST(MOD(TIMESTAMPDIFF(MINUTE, NOW(), b.end_date), 60), 0), ' minutes'
            ) AS remaining_time,
            cb.max_quantity
            from bidding b 
            inner join items i 
            ON i.item_id = b.item_id 
            left join getBiddAmount cb ON cb.bidding_id = b.bidding_id
            where b.deleted = 0 and b.bidding_id = ? ", array($id) )[0];

        return $r;
    }

    public function productItem(){

        $data = getRequestAll();

        extract($data);

        $r = $this->db->Select("select t.* , b.`name` as brand_name,
            (select sum(c.quantity) from  cart c  INNER JOIN orders o ON o.order_no = c.order_no where o.val_status = 'COMPLETED' and c.item_id = t.item_id ) total_sold,
            (select sum(c.quantity) from  stock_in c    where c.val_status = 'APPROVED' and c.deleted = 0 and c.item_id = t.item_id ) total_in
            FROM items t
            inner join brand b ON t.brand_id = b.id where t.deleted = 0 and t.item_id = ?", array($id) )[0];

        return $r;
    }

    public function js(){
        return [
            $this->view.'/js/custom.js',
            $this->view.'/js/message.js',
            $this->view.'/js/search.js',

        ];
    }

    public function css(){
        return [
            $this->view.'/css/search.css',
            $this->view.'/css/appointments.css',
        ];
    }

    // public function source() {
    //     $data = getRequestAll();

    //     extract($data);

    //     $d["details"] = false;

    //     if($action == "edit" && ($id != '' || $id != 'undefined') ) {
    //         $result = $this->db->Select("select * from items where item_id = ?", array($id) )[0];
    //         $d["details"] = $result;
    //     }

    //     $brand = $this->db->Select("select id,  `name` from brand where deleted = ?", array(0) );
    //     $colors = $this->db->Select("select id,`name`  from color where deleted = ?", array(0) );
    //     $category = $this->db->Select("select service_id id ,description as `name`  from service_type where deleted = ?", array(0) );
    //     $size = $this->db->Select("select id , `name`  from size where deleted = ?", array(0) );

    //     $d["brand"] = $brand;
    //     $d["colors"] = $colors;
    //     $d["category"] = $category;
    //     $d["size"] = $size;

    //     $res = [
    //         'header'=> (isset($action) && $action == "add") ? "Add items" : 'Edit items',
    //         "html" => loadView('components/'.$this->view.'/views/modal_details', $d),
    //         'button' => '<button class="btn btn-primary" type="submit">Submit form</button>',
    //         'action' => 'afterSubmit',
    //         'size' => 'modal-lg'
    //     ];

    //     echo json_encode($res);
    // }


      public function source() {
        $data = getRequestAll();

        extract($data);


        $d["details"] = $this->db->Select("select o.main_order_id, o.orrder_no as order_no, o.`date`, o.`time`, 
        DATE_FORMAT(o.`time`, '%h:%i %p') AS format_time,    
        o.val_stattus as val_status, o.no_ofhead , ifnull(t.`name`, 'Any Professional') therapistname  
        ,CONCAT(u.account_first_name, ' ', u.account_last_name) AS full_name,
        o.service_type,
        o.billing_address,
        o.hotel_name,
        o.hotel_room,
        u.contact_no,
        u.gender
        FROM main_order o 
        left join therapist t ON t.id = o.therapist_id
        inner join users u ON u.user_id = o.user_id
        where o.main_order_id = ? 
        order by o.main_order_id desc", array($id) )[0];


        $d["itemlist"] = $this->db->Select("select c.*, 
            it.item_name,
            df.`description`,
            IFNULL(df.amount, it.price) as cost
            FROM cart c
            join items it ON it.item_id = c.item_id
            left join details_financing df ON df.id = c.item_finance
            where c.main_order_id = ?", array($d["details"]["main_order_id"]) );

        $result = $this->db->Select("select * from main_order_client where main_order_id = ? and deleted = 0 order by cart_id,client_id asc", array($d["details"]["main_order_id"]) );


        $d['guestcheck'] = [];
        foreach ($result as $key => $value) {
            $row= [
                    'id' => $value["id"],
                    'client_id' => $value["client_id"],
                    'cart_id' => $value["cart_id"],
            ];
            
           $d['guestcheck'][$value["cart_id"]][$value["client_id"]] = $row;;
           
        }

        $html = loadView('components/TransactionBiddingController/views/modal_details', $d);
        $header = "Booking Details";
        $button = '';
     

        $res = [
            'header'=> $header,
            "html" => $html,
            'button' => $button,
            'action' => 'afterSubmit',
            'size' =>'modal-lg'
        ];

        echo json_encode($res);
    }

    public function afterSubmit(){
        $data = getRequestAll();
        extract($data);


        // dd($data);
     
        $exit = (method_exists($this, $method)) ? $this->{$method}() : [];

       
        header('Location: '. $exit );
        exit();
        
    }

    public function processProductBidding(){
        $data = getRequestAll();

        extract($data);


        unset($data["method"]);


        if(isset($_SESSION["user_id"])) {

            if($_SESSION["no_of_block"] == 3 ) {
                return '../../customer/customer/index?type=warning&message=Failed to Bid! User has been blocked!';
            }

            $checkExist =  $this->db->Select("select * from customer_bidding where bidding_id = ? and user_id = ?", array($bidding_id, $user_id) );


            if($amount_greter >= $quantity ) {
                return '../../customer/customer/index?type=warning&message=Failed to Bid! Bid should be greater than min bid!';
            }


            if(count($checkExist) > 0 ) {

                $this->db->Update("update customer_bidding SET quantity = ? WHERE c_bid_id = ? ", array( $quantity , $checkExist[0]["c_bid_id"]) );
                return '../../customer/customer/index?page=myorders&view=Bidding&type=success&message=Successfully Updated!';


            } else {

                unset($data["amount_greter"]);

                $this->db->insertRequestBatchRquest($data,'customer_bidding');
                return '../../customer/customer/index?page=myorders&view=Bidding&type=success&message=Successfully Registered!';
            }

           

        }

        return '../../customer/customer/index?type=warning&message=Failed to Bid! Please try it to login!';
     
    }

    public function processProductItem(){
        $data = getRequestAll();


        
        unset($data["method"]);



        if(isset($_SESSION["user_id"])) {

            if($data["name_submit"] == "I") {
                // Get product information for the message
                $product_info = $this->db->Select("select t.item_name, b.name as brand_name FROM items t inner join brand b ON t.brand_id = b.id where t.deleted = 0 and t.item_id = ?", array($data["item_id"]));
                
                $product_name = "Unknown Product";
                $brand_name = "Unknown Brand";
                
                if(count($product_info) > 0) {
                    $product_name = $product_info[0]["item_name"];
                    $brand_name = $product_info[0]["brand_name"];
                }
                
                $product_url = $_ENV['URL_HOST'].'customer/customer/index?page=productItem&id='.$data["item_id"];
                
                // Check if this is the customer's first message to trigger auto-greeting
                $previous_messages = $this->db->Select("
                    SELECT COUNT(*) as message_count 
                    FROM message 
                    WHERE user_id = ? AND bussiness_owner_id = 1
                ", [$_SESSION["user_id"]]);
                
                $sms = [
                    "user_id" => $_SESSION["user_id"],
                    "bussiness_owner_id" => 1,
                    "description" => "Customer is inquiring about: <strong>" . $product_name . "</strong> (Brand: " . $brand_name . ") <a href='" . $product_url . "' target='_blank' style='color:white'>View Product</a>",
                ];
                $this->db->insertRequestBatchRquest($sms,'message');
                
                // If this is the first message from customer, send auto-greeting from admin
                if($previous_messages[0]['message_count'] == 0) {
                    // Add a small delay for the auto-greeting to appear more natural
                    $this->db->Insert("
                    INSERT INTO `message`
                    (
                    `user_id`, `bussiness_owner_id`, `description`, `datetime`)
                    VALUES (?,?,?, DATE_ADD(NOW(), INTERVAL 2 SECOND))", 
                    [
                       1, $_SESSION["user_id"], "Hang on a minute, wait for the seller response"
                    ]);
                }

                return 'index?page=myorders&view=Messages&type=success&message=Successfully Added!';

            } else {
                unset($data["name_submit"]);

                // $checkExist =  $this->db->Select("select * from cart where item_id = ? and user_id = ? and deleted = 0", array($data["item_id"], $_SESSION["user_id"]) );

                // if(count($checkExist) <= 0) {
                //     $this->db->insertRequestBatchRquest($data,'cart');
                //     return 'index?page=myorders&view=Cart&type=success&message=Successfully Added!';
                // }
                // return 'index?page=myorders&view=Cart&type=warning&message=Failed! Already add the item!';

                $this->db->insertRequestBatchRquest($data,'cart');
                return 'index?page=myorders&view=Cart&type=success&message=Successfully Added!';


            }
        }

        return 'index?type=warning&message=Failed! Please try to login!';
     
    }

    public function delete(){
        $data = getRequestAll();
        extract($data);

        // Debug logging - let's see what data we're getting
        error_log("Delete method called with data: " . print_r($data, true));
        error_log("Session user_id: " . (isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : "NOT SET"));
        error_log("ID parameter: " . (isset($id) ? $id : "NOT SET"));

        // Check if user is logged in
        if(!isset($_SESSION["user_id"])) {
            $res = [
                'status'=> false,
                'msg' => 'User not logged in!'
            ];
            echo json_encode($res);
            return;
        }

        // Check if ID is provided
        if(!isset($id) || empty($id)) {
            $res = [
                'status'=> false,
                'msg' => 'Invalid cart item ID!'
            ];
            echo json_encode($res);
            return;
        }

        try {
            // First check if this ID exists in the cart table for this user
            $cartCheck = $this->db->Select("SELECT id FROM cart WHERE id = ? AND user_id = ? AND deleted = 0", array($id, $_SESSION["user_id"]));
            
            error_log("Cart check result: " . print_r($cartCheck, true));
            
            if(count($cartCheck) > 0) {
                // It's a cart item deletion - update the cart table
                $this->db->Update("UPDATE cart SET deleted = 1 WHERE id = ? AND user_id = ?", array($id, $_SESSION["user_id"]));
                
                error_log("Cart item with ID $id marked as deleted for user " . $_SESSION["user_id"]);
                
                $res = [
                    'status'=> true,
                    'msg' => 'Item successfully removed from cart!'
                ];
            } else {
                // Not a cart item or doesn't belong to user
                error_log("Cart item not found or doesn't belong to user");
                $res = [
                    'status'=> false,
                    'msg' => 'Cart item not found or access denied!'
                ];
            }
        } catch (Exception $e) {
            error_log("Error in delete method: " . $e->getMessage());
            $res = [
                'status'=> false,
                'msg' => 'Database error occurred!'
            ];
        }

        echo json_encode($res);
    }


    public function Messages(){

        $data = $this->db->Select("select u.user_id, u.email, m.description,  m.datetime, m.is_seen  , 
        CASE 
            WHEN TIMESTAMPDIFF(SECOND, m.datetime, NOW()) < 60 THEN 'Just now'
            WHEN TIMESTAMPDIFF(MINUTE, m.datetime, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, m.datetime, NOW()), ' minutes ago')
            WHEN TIMESTAMPDIFF(HOUR, m.datetime, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, m.datetime, NOW()), ' hours ago')
            ELSE DATE_FORMAT(m.datetime, '%Y-%m-%d %H:%i:%s')
        END AS time_ago
        
        from users u
        inner join  (
                select sent_id, max(id) id from (
                    SELECT bussiness_owner_id sent_id, max(message_id) id FROM message where user_id = ? group by bussiness_owner_id
                    union 
                    SELECT user_id sent_id , max(message_id) id FROM message where bussiness_owner_id = ? and user_id not in (?) group by user_id
                ) tb group by sent_id 
        ) ct on ct.sent_id = u.user_id
        inner join message m ON m.message_id = ct.id where u.user_id != ? and u.user_id = 1
        ", array($_SESSION["user_id"], $_SESSION["user_id"], $_SESSION["user_id"], $_SESSION["user_id"]) );
 
        $d["list"] = $data;
        $res = [
            "html" => loadView('components/'.$this->view.'/views/accounts/Messages', $d)
        ];

        return  $res;
    }


    public function getmessage(){

        $data = getRequestAll();
        
        extract($data);

        $user_id = $_SESSION["user_type"] != 5 ?  1 : $_SESSION["user_id"];

        if(isset($action) && $action == "add") {
            // Check if this is a customer (user_type = 5) sending their first message to admin
            if($_SESSION["user_type"] == 5) {
                // Check if customer has ever sent a message to admin before
                $previous_messages = $this->db->Select("
                    SELECT COUNT(*) as message_count 
                    FROM message 
                    WHERE user_id = ? AND bussiness_owner_id = 1
                ", [$user_id]);
                
                // Insert the customer's message first
                $this->db->Insert("
                INSERT INTO `message`
                (
                `user_id`, `bussiness_owner_id`, `description`)
                VALUES (?,?,?)", 
                [
                   $user_id, $id, $val
                ]);
                
                // If this is the first message from customer, send auto-greeting from admin
                if($previous_messages[0]['message_count'] == 0) {
                    // Add a small delay for the auto-greeting to appear more natural
                    // Insert with datetime a few seconds later
                    $this->db->Insert("
                    INSERT INTO `message`
                    (
                    `user_id`, `bussiness_owner_id`, `description`, `datetime`)
                    VALUES (?,?,?, DATE_ADD(NOW(), INTERVAL 2 SECOND))", 
                    [
                       1, $user_id, "Hang on a minute, wait for the seller response"
                    ]);
                }
            } else {
                // Admin sending message - no auto-greeting needed
                $this->db->Insert("
                INSERT INTO `message`
                (
                `user_id`, `bussiness_owner_id`, `description`)
                VALUES (?,?,?)", 
                [
                   $user_id, $id, $val
                ]);
            }
        }


        $data = $this->db->Select("select *,
        CASE 
            WHEN TIMESTAMPDIFF(SECOND, datetime, NOW()) < 60 THEN 'Just now'
            WHEN TIMESTAMPDIFF(MINUTE, datetime, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, datetime, NOW()), ' minutes ago')
            WHEN TIMESTAMPDIFF(HOUR, datetime, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, datetime, NOW()), ' hours ago')
            ELSE DATE_FORMAT(datetime, '%Y-%m-%d %H:%i:%s')
        END AS time_ago
        FROM (
        SELECT * FROM message where user_id = ? and bussiness_owner_id = ?
        union 
        SELECT * FROM message where user_id = ? and bussiness_owner_id = ?
        ) TB order by datetime asc", array($user_id,$id, $id, $user_id) );
        

        $d["list"] = $data;


        // Load the view with data
       
        $res = [
            'html' =>   loadView('components/'.$this->view.'/views/accounts/getMessage', $d)
        ];
        echo json_encode($res);
    }

    public function notification(){


        if(isset($_SESSION["user_id"])) {
            $d["list"] = $this->db->Select("
                SELECT 
                u.username,
                n.*
                FROM notification n
                left join users u ON u.user_id = n.sent_by 
                where n.user_id = ? order by n.id desc
            ", array($_SESSION["user_id"]) );
    
            $res = [
                "html" => loadView('components/'.$this->view.'/views/accounts/notification', $d)
            ];
        } else {
            $res = [
                "html" =>''
            ];
        }

        
     
        return  $res;
    }

    public function searchSuggestions(){
        // Set JSON header immediately
        header('Content-Type: application/json');
        
        $data = getRequestAll();
        extract($data);
        
        $suggestions = [];
        
        if(isset($query) && !empty(trim($query))) {
            $search_term = trim($query);
            
            try {
                // Search for items and get suggestions
                $items = $this->db->Select("select DISTINCT item_name FROM items t 
                inner join brand b ON t.brand_id = b.id 
                where t.status = 1 and t.deleted = 0 
                and (t.item_name LIKE '%".$search_term."%' OR b.name LIKE '%".$search_term."%')
                LIMIT 8", array());
                
                foreach($items as $item) {
                    $suggestions[] = $item['item_name'];
                }
                
                // Also search brand names
                $brands = $this->db->Select("select DISTINCT b.name FROM brand b 
                inner join items t ON t.brand_id = b.id 
                where b.deleted = 0 and t.status = 1 and t.deleted = 0
                and b.name LIKE '%".$search_term."%'
                LIMIT 4", array());
                
                foreach($brands as $brand) {
                    $suggestions[] = $brand['name'];
                }
            } catch (Exception $e) {
                // If there's an error, just return empty suggestions
                error_log("Search suggestions error: " . $e->getMessage());
            }
        }
        
        // Remove duplicates and limit results
        $suggestions = array_unique($suggestions);
        $suggestions = array_slice($suggestions, 0, 10);
        
        echo json_encode(['suggestions' => array_values($suggestions)]);
        exit(); // Important to exit after sending JSON
    }

    public function Orders(){


        if(isset($_SESSION["user_id"])) {
            $d["list"] = $this->db->Select("
                SELECT o.val_status, o.id, o.order_no, o.created_at,o.type_order , t.item_name,  p.checkoutURL, p.payment_id,
                case when o.type_order = 'SELL' THEN ifnull(dp.dp_amount, t.price) ELSE o.total_payment end price, c.quantity
                FROM orders o
                inner join items t on t.item_id = o.item_id
                left join payment p ON p.order_id = o.id 
                inner join cart c ON c.order_no = o.order_no
                left join details_financing dp ON dp.id = c.item_finance where o.user_id = ?
            ", array($_SESSION["user_id"]) );
    
            $res = [
                "html" => loadView('components/'.$this->view.'/views/accounts/Orders', $d)
            ];
        } else {
            $res = [
                "html" =>''
            ];
        }

        
     
        return  $res;
    }

    

    public function Bidding(){

        $d["list"] = $this->db->Select("
        SELECT o.main_order_id, o.orrder_no, o.`date`, o.`time`,  o.val_stattus val_status, o.no_ofhead , ifnull(t.`name`, 'Any Professional') th_name  FROM main_order o 
        left join therapist t ON t.id = o.therapist_id
        where o.user_id = ? and o.deleted = 0
        order by o.main_order_id desc
        ", array($_SESSION["user_id"]) );

        $res = [
            "html" => loadView('components/'.$this->view.'/views/accounts/Bidding', $d)
        ];

        return  $res;
    }

    public function Cart(){

        $d["list"] = $this->db->Select("select c.id, c.user_id, c.val_status, c.item_id, c.item_finance, 
        t.item_name, t.price,   t.img_255x200_home , df.`description` ,df.months, ifnull(df.amount,t.price)  as unit_cost
        FROM cart c
        inner join items t on t.item_id = c.item_id
        left join details_financing df ON df.id = c.item_finance
        where  c.deleted = 0  and c.user_id = ? and c.main_order_id is  null", array($_SESSION["user_id"]) );

        $res = [
            "html" => loadView('components/'.$this->view.'/views/accounts/Cart', $d)
        ];

        return  $res;
    }

    public function checkout(){
        $data = getRequestAll();
        extract($data);
        
        // ===== DOWNPAYMENT FEATURE - COMMENTED OUT FOR FUTURE USE =====
        // Calculate downpayment amount (50% of total)
        // $downpayment_amount = 0;
        // if (isset($total_payment) && is_numeric($total_payment)) {
        //     $downpayment_amount = floatval($total_payment) * 0.5;
        // }
        // ===== END DOWNPAYMENT FEATURE =====
        
        $svc_type = isset($service_type) ? $service_type : 'walk-in';

        // For hotel service, billing_address comes from hotel_address field
        // For home service, billing_address comes from billing_address field
        if ($svc_type === 'hotel') {
            $final_billing_address = isset($hotel_address) ? $hotel_address : (isset($billing_address) ? $billing_address : '');
        } else {
            $final_billing_address = isset($billing_address) ? $billing_address : '';
        }

        $insert = [
            "user_id"         => $_SESSION["user_id"],
            "date"            => $date,
            "orrder_no"       => $order_no,
            'time'            => $time,
            'therapist_id'    => $therapist_id,
            'no_ofhead'       => $no_ofhead,
            'service_type'    => $svc_type,
            'billing_address' => $final_billing_address,
            'hotel_name'      => isset($hotel_name) ? $hotel_name : '',
            'hotel_room'      => isset($hotel_room) ? $hotel_room : '',
        ];

        $this->db->insertRequestBatchRquest($insert,'main_order');

        $r = $this->db->Select(" SELECT * FROM main_order  where orrder_no = ? ", array($order_no) )[0];


        foreach ($cartlist as $value) {
           $this->db->Update("update cart SET main_order_id = ? WHERE id = ? ", array($r["main_order_id"], $value) );
        }
   

        foreach ($guest as $key => $value) {
            foreach ($value as $V) {
               $insert = [
                    "main_order_id" => $r["main_order_id"],
                    "client_id" => $key,
                    'cart_id' => $V,
                ];
                $this->db->insertRequestBatchRquest($insert,'main_order_client');
            }
           
        }
     
        // ===== EMAIL NOTIFICATION TO ADMIN =====
        try {
            // Get customer details
            $customer = $this->db->Select("SELECT account_first_name, account_last_name, email, contact_no FROM users WHERE user_id = ?", array($_SESSION["user_id"]));
            
            // Get therapist name if specified
            $therapist_name = "Any Professional";
            if (!empty($therapist_id) && $therapist_id != '0') {
                $therapist = $this->db->Select("SELECT name FROM therapist WHERE id = ?", array($therapist_id));
                if (count($therapist) > 0) {
                    $therapist_name = $therapist[0]['name'];
                }
            }
            
            // Get booked services
            $services = $this->db->Select("
                SELECT c.*, it.item_name, df.description, IFNULL(df.amount, it.price) as cost
                FROM cart c
                JOIN items it ON it.item_id = c.item_id
                LEFT JOIN details_financing df ON df.id = c.item_finance
                WHERE c.main_order_id = ?
            ", array($r["main_order_id"]));
            
            // Build services list for email
            $services_html = "<ul style='list-style: none; padding: 0;'>";
            $total_amount = 0;
            foreach ($services as $service) {
                $services_html .= "<li style='padding: 5px 0; border-bottom: 1px solid #eee;'>";
                $services_html .= "<strong>" . htmlspecialchars($service['item_name']) . "</strong>";
                if (!empty($service['description'])) {
                    $services_html .= " (" . htmlspecialchars($service['description']) . ")";
                }
                $services_html .= " - ₱" . number_format($service['cost'], 2);
                $services_html .= "</li>";
                $total_amount += $service['cost'];
            }
            $services_html .= "</ul>";
            
            // Format date and time
            $formatted_date = !empty($date) ? date('F d, Y', strtotime($date)) : 'N/A';
            $formatted_time = !empty($time) ? date('h:i A', strtotime($time)) : 'N/A';
            
            // Build email body
            if (count($customer) > 0) {
                $customer_name = $customer[0]['account_first_name'] . ' ' . $customer[0]['account_last_name'];
                $customer_email = $customer[0]['email'];
                $customer_contact = $customer[0]['contact_no'] ?? 'N/A';
                
                $email_body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                        .content { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 5px 5px; }
                        .info-row { padding: 10px 0; border-bottom: 1px solid #ddd; }
                        .info-label { font-weight: bold; color: #555; display: inline-block; width: 150px; }
                        .info-value { color: #333; }
                        .footer { text-align: center; margin-top: 20px; padding: 10px; font-size: 12px; color: #777; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2 style='margin: 0;'>🔔 New Appointment Booking</h2>
                        </div>
                        <div class='content'>
                            <p style='font-size: 16px; margin-bottom: 20px;'>A new appointment has been booked by a customer.</p>
                            
                            <h3 style='color: #4CAF50; border-bottom: 2px solid #4CAF50; padding-bottom: 5px;'>Booking Details</h3>
                            <div class='info-row'>
                                <span class='info-label'>Order Number:</span>
                                <span class='info-value'>" . htmlspecialchars($order_no) . "</span>
                            </div>
                            <div class='info-row'>
                                <span class='info-label'>Date:</span>
                                <span class='info-value'>" . $formatted_date . "</span>
                            </div>
                            <div class='info-row'>
                                <span class='info-label'>Time:</span>
                                <span class='info-value'>" . $formatted_time . "</span>
                            </div>
                            <div class='info-row'>
                                <span class='info-label'>Number of Guests:</span>
                                <span class='info-value'>" . htmlspecialchars($no_ofhead) . "</span>
                            </div>
                            <div class='info-row'>
                                <span class='info-label'>Therapist:</span>
                                <span class='info-value'>" . htmlspecialchars($therapist_name) . "</span>
                            </div>
                            
                            <h3 style='color: #4CAF50; border-bottom: 2px solid #4CAF50; padding-bottom: 5px; margin-top: 20px;'>Service Information</h3>
                            <div class='info-row'>
                                <span class='info-label'>Service Type:</span>
                                <span class='info-value'>" . (function() use ($svc_type) {
                                    $labels = ['walk-in' => 'Walk-in', 'home' => 'Home Service', 'hotel' => 'Hotel Service'];
                                    $colors = ['walk-in' => '#28a745', 'home' => '#007bff', 'hotel' => '#6f42c1'];
                                    $label = $labels[$svc_type] ?? ucfirst($svc_type);
                                    $color = $colors[$svc_type] ?? '#6c757d';
                                    return "<span style='background:{$color};color:#fff;padding:2px 8px;border-radius:4px;font-size:13px;'>{$label}</span>";
                                })() . "</span>
                            </div>" .
                            ((!empty($final_billing_address) && $svc_type === 'home') ? "
                            <div class='info-row'>
                                <span class='info-label'>Home Address:</span>
                                <span class='info-value'>" . htmlspecialchars($final_billing_address) . "</span>
                            </div>" : "") .
                            (($svc_type === 'hotel') ? "
                            <div class='info-row'>
                                <span class='info-label'>Hotel Name:</span>
                                <span class='info-value'>" . htmlspecialchars($insert['hotel_name'] ?? 'N/A') . "</span>
                            </div>
                            <div class='info-row'>
                                <span class='info-label'>Hotel Address:</span>
                                <span class='info-value'>" . htmlspecialchars($final_billing_address ?: 'N/A') . "</span>
                            </div>
                            <div class='info-row'>
                                <span class='info-label'>Room No.:</span>
                                <span class='info-value'>" . htmlspecialchars($insert['hotel_room'] ?? 'N/A') . "</span>
                            </div>" : "") . "
                            
                            <h3 style='color: #4CAF50; border-bottom: 2px solid #4CAF50; padding-bottom: 5px; margin-top: 20px;'>Customer Information</h3>
                            <div class='info-row'>
                                <span class='info-label'>Name:</span>
                                <span class='info-value'>" . htmlspecialchars($customer_name) . "</span>
                            </div>
                            <div class='info-row'>
                                <span class='info-label'>Email:</span>
                                <span class='info-value'>" . htmlspecialchars($customer_email) . "</span>
                            </div>
                            <div class='info-row'>
                                <span class='info-label'>Contact:</span>
                                <span class='info-value'>" . htmlspecialchars($customer_contact) . "</span>
                            </div>
                            
                            <h3 style='color: #4CAF50; border-bottom: 2px solid #4CAF50; padding-bottom: 5px; margin-top: 20px;'>Services Booked</h3>
                            " . $services_html . "
                            <div style='text-align: right; margin-top: 15px; padding: 10px; background-color: #fff; border-radius: 5px;'>
                                <strong style='font-size: 16px;'>Total Amount: ₱" . number_format($total_amount, 2) . "</strong>
                            </div>
                            
                            <p style='margin-top: 20px; padding: 15px; background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 3px;'>
                                <strong>⚠️ Action Required:</strong> Please review this booking and update the status in the admin panel.
                            </p>
                        </div>
                        <div class='footer'>
                            <p>This is an automated notification from Touch and Care Massage and Spa</p>
                            <p>© " . date('Y') . " Touch and Care Massage and Spa. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                // Get admin email (user_type = 1)
                $admin = $this->db->Select("SELECT email FROM users WHERE user_type = 1 AND deleted = 0 AND status = 1 LIMIT 1", array());
                
                if (count($admin) > 0) {
                    $admin_email = $admin[0]['email'];
                    
                    // Send email using ComponentHelper
                    require_once 'components/ComponentHelper/ComponentHelper.php';
                    $helper = new ComponentHelper($this->db);
                    
                    $email_sent = $helper->sentToEmail(
                        $admin_email,
                        "New Appointment Booking - " . $formatted_date . " at " . $formatted_time,
                        $email_body
                    );
                    
                    if ($email_sent) {
                        error_log("Admin notification email sent successfully to: " . $admin_email);
                    } else {
                        error_log("Failed to send admin notification email to: " . $admin_email);
                    }
                } else {
                    error_log("No admin email found for booking notification");
                }
            }
        } catch (Exception $e) {
            // Log error but don't stop the booking process
            error_log("Error sending booking notification email: " . $e->getMessage());
        }
        // ===== END EMAIL NOTIFICATION TO ADMIN =====

        header('Location: ../../customer/customer/index?page=myorders&view=Bidding&type=success&message=Successfully Added!');
        exit();
       
    }

    public function AKCNOWLEDGE(){
        $data = getRequestAll();
        
        extract($data);



        $r = $this->db->Select("
        SELECT 
        `bidding`.`item_id`,
        `customer_bidding`.`bidding_id`,
        `customer_bidding`.`quantity`,
        `customer_bidding`.`user_id`,
        'BIDDING' as bid_type
        
        FROM customer_bidding 
        inner join bidding on bidding.bidding_id =    `customer_bidding`.`bidding_id`
        where `customer_bidding`.c_bid_id = ?
        ", array($id) )[0];

        $this->db->Update("update bidding SET val_status = 'ACKNOWLEDGED', awarded_user_id = ?  WHERE bidding_id = ? ", array( $_SESSION["user_id"] ,$r["bidding_id"]) );
        $this->db->Update("update customer_bidding SET val_status = 'ACKNOWLEDGED' WHERE c_bid_id = ? ", array($id) );


        $val = [];
        foreach ($r as $key => $value) {
             $val[] = $value;
        }
        $val[] = rand(1, 999999);

        
        $this->db->Insert("
        INSERT INTO `orders`
        (
        `item_id`,
        `bidding_id`,
        `total_payment`,
        `user_id`,
        `type_order`,
        `order_no`
        )
        VALUES (?,?,?,?,?,?)",  $val ) ;

        // Load the view with data
        header('Location: index?page=myorders&view=Orders&type=success&message=Successfully Added!' );
        exit();
    }

    public function Addresses(){

        $d = [];
        $res = [
            "html" => loadView('components/'.$this->view.'/views/accounts/Addresses', $d)
        ];

        return  $res;
    }

    public function Account(){

        $d = [];

        $res = [
            "html" => loadView('components/'.$this->view.'/views/accounts/Account', $d)
        ];

        return  $res;
    }

    public function paymentAction(){
        $data = getRequestAll();

        extract($data);
        $d["id"] = $id;

        
        $d["details"] = $this->db->Select("select * from payment where payment_id = ?", array($id) )[0];

        $res = [
            'header'=> 'Upload Proof Of Payment',
            "html" => loadView('components/'.$this->view.'/views/accounts/uploadpayments', $d),
            'button' => '<button class="btn btn-primary" type="submit">Upload</button>',
            'action' => 'paymentUploadReference',
            'size' => 'modal-lg'
        ];

        echo json_encode($res);
    }

    public function paymentUploadReference() {
        $data = getRequestAll();

        extract($data);



        // Directory where uploaded images will be stored
        $targetDir = "src/images/payments/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create the directory if it doesn't exist
        }

        // File upload path
        $targetFile = $targetDir . basename($_FILES["file"]["name"]);

        // Allowed file types
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the uploaded file is an image
        if (isset($_POST) && isset($_FILES["file"])) {
            $check = getimagesize($_FILES["file"]["tmp_name"]);
            if ($check === false) {
                // Load the view with data
                header('Location: ../../customer/customer/index?page=myorders&view=Orders&type=warning&message=File Not Image!');
                exit();
            }

            // Check file type
            if (!in_array($fileType, $allowedTypes)) {
                 // Load the view with data
                 header('Location: ../../customer/customer/index?page=myorders&view=Orders&type=warning&message=Error: Only JPG, JPEG, PNG & GIF files are allowed.!');
                 exit();
            }

            // Check file size (limit: 5MB)
            if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
                 // Load the view with data
                 header('Location: ../../customer/customer/index?page=myorders&view=Orders&type=warning&message=Error: File size exceeds 5MB.');
                 exit();
            }

            // Attempt to upload file
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {

                $proof_of_payment = $targetFile;


                $this->db->Update("update payment SET proof_of_payment = ?, reference_no = ? WHERE payment_id = ? ", array($proof_of_payment, $reference_no, $payment_id) );
         
                // Load the view with data
                header('Location: ../../customer/customer/index?page=myorders&view=Orders&type=success&message=Successfully Added!');
                exit();
                

            } else {
                 
                // Load the view with data
                header('Location: ../../customer/customer/index?page=myorders&view=Orders&type=warning&message=Failed to uplaod!');
                exit();
            }
        } else {
                
            // Load the view with data
            header('Location: ../../customer/customer/index?page=myorders&view=Orders&type=warning&message=Failed to uplaod!');
            exit();
        }

    
    }


    public function serviceTypeModal() {
        $data = getRequestAll();
        // Pass checkout IDs through so the next step can use them
        $res = [
            'header' => 'Choose Service Type',
            'html'   => loadView('components/'.$this->view.'/views/service_type_modal', $data),
            'button' => '',
            'action' => '',
            'size'   => 'modal-md'
        ];
        echo json_encode($res);
    }

    public function terms_and_condition() {
        $data = getRequestAll();

        extract($data);

        $d = $data;
        
        // Gawing "?, ?, ?, ?"
        $placeholders = implode(', ', array_fill(0, count($checkoutIDS), '?'));

        $list = $this->db->Select("select c.id, c.user_id, c.val_status, c.item_id, c.item_finance, 
        t.item_name, t.price, t.brand_id, t.img_255x200_home , df.`description` ,df.months, ifnull(df.amount,t.price)  as unit_cost, b.`name` as service_category
        FROM cart c
        inner join items t on t.item_id = c.item_id
        inner join brand b on b.id = t.brand_id
        left join details_financing df ON df.id = c.item_finance
        where c.id in (".$placeholders.")", $checkoutIDS );

        // Get unique service categories from selected items
        $service_categories = array_unique(array_column($list, 'brand_id'));
        
        // Filter therapists based on the service categories of selected items
        if (!empty($service_categories)) {
            $category_placeholders = implode(', ', array_fill(0, count($service_categories), '?'));
            $d["therapist"] = $this->db->Select("select t.*, b.`name` as ser_type from therapist t 
            left join brand b ON b.id = t.service_id
            where t.deleted = 0 AND t.service_id IN (".$category_placeholders.")", $service_categories );
            
            // Add debug info for development
            $d["debug_info"] = [
                'selected_categories' => $service_categories,
                'total_therapists_found' => count($d["therapist"]),
                'filtering_applied' => true
            ];
        } else {
            // Fallback - show all therapists if no specific categories found
            $d["therapist"] = $this->db->Select("select t.*, b.`name` as ser_type from therapist t 
            left join brand b ON b.id = t.service_id
            where t.deleted = 0", array() );
            
            $d["debug_info"] = [
                'selected_categories' => [],
                'total_therapists_found' => count($d["therapist"]),
                'filtering_applied' => false
            ];
        }


        $d["list"] = [];
        foreach ($list as $key => $value) {
            $d["list"][$key]["id"] = $value["id"];
            $d["list"][$key]["name"] = $value["item_name"];
            $d["list"][$key]["unit_cost"] = $value["unit_cost"];
            $d["list"][$key]["description"] = $value["description"];
            $d["list"][$key]["months"] = $value["months"];
            $d["list"][$key]["image"] = $value["img_255x200_home"];
            $d["list"][$key]["service_category"] = $value["service_category"];
            $d["list"][$key]["brand_id"] = $value["brand_id"];
        }

        $res = [
            'header'=> 'Book Appointment',
            "html" => loadView('components/'.$this->view.'/views/place_bid_terms', $d),
            'button' => '', // Remove the submit button from modal footer
            'action' => 'afterSubmit',
            'size' => 'modal-lg'
        ];

        echo json_encode($res);
    }
    public function terms_and_condition_acknowleedge() {
        $data = getRequestAll();

        extract($data);

        $d = $data;

        $res = [
            'header'=> '',
            "html" => loadView('components/'.$this->view.'/views/AKCNOWLEDGE_bid_terms', $d),
            'button' => '<button class="btn btn-primary"  ><a  href="'.$link.'" style="color:white">SUBMIT</a></button>',
            'action' => '',
            'size' => 'modal-lg'
        ];

        echo json_encode($res);
    }

    public function getTimeSchedule(){
        $data = getRequestAll();

        extract($data);

        // Get sum of clients (no_ofhead) for each time slot on the selected date
        // Exclude CANCELLED and DECLINED appointments so those time slots become available again
        $res = $this->db->Select("select `time`, SUM(no_ofhead) as total_clients FROM main_order WHERE `date` = ? AND val_stattus NOT IN ('CANCELLED', 'DECLINED') AND deleted = 0 GROUP BY `time`", array($selected_date) );

        $timeSlotCounts = [];
        if($res) {
            foreach ($res as $key => $value) {
                $timeSlotCounts[$value["time"]] = $value["total_clients"];
            }
        }

        // Add current time information to help frontend block past times
        // Set timezone to ensure consistent time
        date_default_timezone_set('Asia/Manila'); // Adjust to your timezone
        
        $currentTime = date('H:i:s');
        $currentDate = date('Y-m-d');
        
        $response = [
            'timeSlotCounts' => $timeSlotCounts,
            'currentDate' => $currentDate,
            'currentTime' => $currentTime,
            'selectedDate' => $selected_date
        ];

        // Debug logging - remove after testing
        error_log("getTimeSchedule Debug - Current Time: $currentTime, Current Date: $currentDate, Selected Date: $selected_date");

        echo json_encode($response);
    }

    public function POST(){
        $data = getRequestAll();
        extract($data);
        
        // Add debug logging
        error_log("POST method called with data: " . print_r($data, true));
        
        // Route POST requests based on the action or type parameter
        if(isset($action)) {
            error_log("Action detected: " . $action);
            switch($action) {
                case 'deleteCart':
                    return $this->deleteCartItem();
                case 'validateCurrentPassword':
                    error_log("Routing to validateCurrentPassword");
                    return $this->validateCurrentPassword();
                case 'debugTest':
                    return $this->debugTest();
                default:
                    error_log("Unknown action: " . $action);
                    return $this->handleGenericPost();
            }
        }
        
        // Default POST handling
        error_log("No action parameter, using default handler");
        return $this->handleGenericPost();
    }
    
    public function deleteCartItem(){
        $data = getRequestAll();
        extract($data);

        if(isset($id) && !empty($id)) {
            $this->db->Update("UPDATE cart SET deleted = 1 WHERE id = ? AND user_id = ?", array($id, $_SESSION["user_id"]));
            
            $res = [
                'status' => true,
                'msg' => 'Item successfully removed from cart!'
            ];
        } else {
            $res = [
                'status' => false,
                'msg' => 'Invalid cart item ID!'
            ];
        }

        echo json_encode($res);
    }
    
    public function handleGenericPost(){
        // Handle other POST requests here
        $res = [
            'status' => false,
            'msg' => 'POST request received but no specific handler found'
        ];
        
        echo json_encode($res);
    }

    public function testDelete(){
        // Simple test method to check if our delete functionality works
        $data = getRequestAll();
        extract($data);
        
        if(!isset($_SESSION["user_id"])) {
            echo "User not logged in";
            return;
        }
        
        echo "User ID: " . $_SESSION["user_id"] . "<br>";
        
        if(isset($id)) {
            echo "Cart ID to delete: " . $id . "<br>";
            
            // Check if cart item exists
            $cartCheck = $this->db->Select("SELECT * FROM cart WHERE id = ? AND user_id = ? AND deleted = 0", array($id, $_SESSION["user_id"]));
            echo "Cart items found: " . count($cartCheck) . "<br>";
            
            if(count($cartCheck) > 0) {
                echo "Cart item details: <pre>" . print_r($cartCheck, true) . "</pre>";
                
                // Perform the delete
                $this->db->Update("UPDATE cart SET deleted = 1 WHERE id = ? AND user_id = ?", array($id, $_SESSION["user_id"]));
                echo "Delete operation completed<br>";
                
                // Check if it was actually updated
                $afterCheck = $this->db->Select("SELECT * FROM cart WHERE id = ? AND user_id = ?", array($id, $_SESSION["user_id"]));
                echo "After deletion: <pre>" . print_r($afterCheck, true) . "</pre>";
            } else {
                echo "No cart item found with that ID for this user";
            }
        } else {
            echo "No ID provided";
        }
    }

    public function validateCurrentPassword(){
        // Add debugging headers
        error_log("validateCurrentPassword method called");
        
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        
        $data = getRequestAll();
        extract($data);
        
        error_log("POST data: " . print_r($data, true));
        
        // Check if user is logged in
        if(!isset($_SESSION["user_id"])) {
            $response = [
                'status' => false,
                'message' => 'User not logged in!'
            ];
            error_log("User not logged in");
            echo json_encode($response);
            exit();
        }
        
        // Check if current password is provided
        if(!isset($current_password) || empty($current_password)) {
            $response = [
                'status' => false,
                'message' => 'Current password is required!'
            ];
            error_log("Current password not provided");
            echo json_encode($response);
            exit();
        }
        
        try {
            // Get user's current password from database
            $user = $this->db->Select("SELECT password FROM users WHERE user_id = ?", array($_SESSION["user_id"]));
            
            error_log("User query result: " . print_r($user, true));
            
            if(count($user) > 0) {
                $storedPassword = $user[0]['password'];
                error_log("Stored password length: " . strlen($storedPassword));
                error_log("Input password length: " . strlen($current_password));
                
                // Try both hashed and plain text comparison
                $isHashedValid = password_verify($current_password, $storedPassword);
                $isPlainValid = ($current_password === $storedPassword);
                
                error_log("Hashed verification: " . ($isHashedValid ? 'true' : 'false'));
                error_log("Plain text verification: " . ($isPlainValid ? 'true' : 'false'));
                
                if($isHashedValid || $isPlainValid) {
                    $response = [
                        'status' => true,
                        'message' => 'Password verified successfully!',
                        'method' => $isHashedValid ? 'hashed' : 'plain'
                    ];
                    error_log("Password verification successful");
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Incorrect current password!'
                    ];
                    error_log("Password verification failed");
                }
            } else {
                $response = [
                    'status' => false,
                    'message' => 'User not found!'
                ];
                error_log("User not found in database");
            }
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => 'An error occurred during validation: ' . $e->getMessage()
            ];
            error_log("Password validation error: " . $e->getMessage());
        }
        
        error_log("Final response: " . json_encode($response));
        echo json_encode($response);
        exit();
    }

    public function verifySecurityAnswer(){
        // Add debugging headers
        error_log("verifySecurityAnswer method called");
        
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        
        $data = getRequestAll();
        extract($data);
        
        error_log("POST data: " . print_r($data, true));
        
        // Check if user is logged in
        if(!isset($_SESSION["user_id"])) {
            $response = [
                'status' => false,
                'message' => 'User not logged in!'
            ];
            error_log("User not logged in");
            echo json_encode($response);
            exit();
        }
        
        // Check if security answer is provided
        if(!isset($security_answer) || empty($security_answer)) {
            $response = [
                'status' => false,
                'message' => 'Security answer is required!'
            ];
            error_log("Security answer not provided");
            echo json_encode($response);
            exit();
        }
        
        try {
            // Get user's security answer from database
            $user = $this->db->Select("SELECT security_answer, security_question FROM users WHERE user_id = ?", array($_SESSION["user_id"]));
            
            error_log("User query result: " . print_r($user, true));
            
            if(count($user) > 0) {
                $storedAnswer = $user[0]['security_answer'];
                $storedQuestion = $user[0]['security_question'];
                
                // Check if user has a security question set
                if(empty($storedAnswer) || empty($storedQuestion)) {
                    $response = [
                        'status' => false,
                        'message' => 'No security question set for this account. Please contact support.'
                    ];
                    error_log("No security question set");
                } else {
                    error_log("Stored answer: " . $storedAnswer);
                    error_log("Input answer: " . $security_answer);
                    
                    // Compare answers (case-insensitive and trimmed)
                    $isValid = (strtolower(trim($security_answer)) === strtolower(trim($storedAnswer)));
                    
                    error_log("Answer verification: " . ($isValid ? 'true' : 'false'));
                    
                    if($isValid) {
                        $response = [
                            'status' => true,
                            'message' => 'Security answer verified successfully!'
                        ];
                        error_log("Security answer verification successful");
                    } else {
                        $response = [
                            'status' => false,
                            'message' => 'Incorrect security answer!'
                        ];
                        error_log("Security answer verification failed");
                    }
                }
            } else {
                $response = [
                    'status' => false,
                    'message' => 'User not found!'
                ];
                error_log("User not found in database");
            }
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => 'An error occurred during validation: ' . $e->getMessage()
            ];
            error_log("Security answer validation error: " . $e->getMessage());
        }
        
        error_log("Final response: " . json_encode($response));
        echo json_encode($response);
        exit();
    }

    public function debugTest(){
        header('Content-Type: application/json');
        
        $response = [
            'status' => true,
            'message' => 'Debug test successful!',
            'session_data' => [
                'user_id' => $_SESSION["user_id"] ?? 'not set',
                'email' => $_SESSION["email"] ?? 'not set',
                'username' => $_SESSION["username"] ?? 'not set'
            ],
            'post_data' => getRequestAll(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        error_log("Debug test response: " . json_encode($response));
        echo json_encode($response);
        exit();
    }

    public function cancelAppointment(){
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        
        $data = getRequestAll();
        extract($data);
        
        error_log("Cancel appointment called with data: " . print_r($data, true));
        
        // Check if user is logged in
        if(!isset($_SESSION["user_id"])) {
            $response = [
                'status' => false,
                'message' => 'User not logged in!'
            ];
            error_log("User not logged in");
            echo json_encode($response);
            exit();
        }
        
        // Check if appointment ID is provided
        if(!isset($appointment_id) || empty($appointment_id)) {
            $response = [
                'status' => false,
                'message' => 'Appointment ID is required!'
            ];
            error_log("Appointment ID not provided");
            echo json_encode($response);
            exit();
        }
        
        try {
            // First check if this appointment exists and belongs to the user
            $appointment = $this->db->Select("SELECT main_order_id, val_stattus, user_id, orrder_no FROM main_order WHERE main_order_id = ? AND user_id = ? AND deleted = 0", array($appointment_id, $_SESSION["user_id"]));
            
            error_log("Appointment query result: " . print_r($appointment, true));
            
            if(count($appointment) == 0) {
                $response = [
                    'status' => false,
                    'message' => 'Appointment not found or access denied!'
                ];
                error_log("Appointment not found or doesn't belong to user");
                echo json_encode($response);
                exit();
            }
            
            $appointmentData = $appointment[0];
            
            // Check if appointment is still in PENDING status
            if($appointmentData['val_stattus'] !== 'PENDING') {
                $response = [
                    'status' => false,
                    'message' => 'Cannot cancel appointment. Current status: ' . $appointmentData['val_stattus']
                ];
                error_log("Appointment cannot be cancelled, status: " . $appointmentData['val_stattus']);
                echo json_encode($response);
                exit();
            }
            
            // Update the appointment status to CANCELLED
            $this->db->Update("UPDATE main_order SET val_stattus = 'CANCELLED', updated_at = NOW() WHERE main_order_id = ? AND user_id = ?", array($appointment_id, $_SESSION["user_id"]));
            
            error_log("Appointment with ID $appointment_id cancelled for user " . $_SESSION["user_id"]);
            
            $response = [
                'status' => true,
                'message' => 'Appointment successfully cancelled!',
                'appointment_id' => $appointment_id,
                'order_no' => $appointmentData['orrder_no']
            ];
            
        } catch (Exception $e) {
            error_log("Error in cancelAppointment method: " . $e->getMessage());
            $response = [
                'status' => false,
                'message' => 'An error occurred while cancelling the appointment: ' . $e->getMessage()
            ];
        }
        
        error_log("Cancel appointment response: " . json_encode($response));
        echo json_encode($response);
        exit();
    }

}
