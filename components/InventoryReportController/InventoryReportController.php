<?php 


use GuzzleHttp\Client;



class InventoryReportController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "InventoryReportController";
    }


    public function index() {
    $data = getRequestAll(); // Assuming this fetches $_GET or request data

    // Build WHERE clause for date filtering
    $whereDate = '';
    if (!empty($data["from"]) && !empty($data["to"])) {
        $from = $data["from"];
        $to = $data["to"];
        $whereDate = " AND o.`date` BETWEEN '$from' AND '$to' ";
    }

    // Build WHERE clause for service type filtering
    $whereServiceType = '';
    if (!empty($data["service_type"])) {
        $serviceType = $data["service_type"];
        $whereServiceType = " AND b.`name` = '$serviceType' ";
    }

    // Build ORDER BY clause
    $orderBy = " order by o.date desc, c.item_id asc ";
    if (!empty($data["sort_by"])) {
        switch ($data["sort_by"]) {
            case 'date_asc':
                $orderBy = " order by o.date asc, c.item_id asc ";
                break;
            case 'date_desc':
                $orderBy = " order by o.date desc, c.item_id asc ";
                break;
            case 'service_type_asc':
                $orderBy = " order by b.`name` asc, o.date desc ";
                break;
            case 'service_type_desc':
                $orderBy = " order by b.`name` desc, o.date desc ";
                break;
            case 'customer_asc':
                $orderBy = " order by CONCAT(u.account_first_name, ' ', u.account_last_name) asc, o.date desc ";
                break;
            case 'customer_desc':
                $orderBy = " order by CONCAT(u.account_first_name, ' ', u.account_last_name) desc, o.date desc ";
                break;
            default:
                $orderBy = " order by o.date desc, c.item_id asc ";
                break;
        }
    }

    // Get service types for dropdown
    $serviceTypes = $this->db->Select("SELECT DISTINCT b.id, b.name FROM brand b WHERE b.deleted = 0 ORDER BY b.name ASC", array());
    $data["service_types"] = $serviceTypes;

    $result = $this->db->Select("select c.item_id, count(c.item_id) total, o.val_stattus, o.date, o.main_order_id,
            max(it.item_name) item_name, max(b.`name`) service_type,
            max(CONCAT(u.account_first_name, ' ', u.account_last_name)) customer_name
            FROM main_order o 
            join cart c ON c.main_order_id = o.main_order_id
            join items it ON it.item_id = c.item_id
            join brand b on b.id = it.brand_id
            join users u on u.user_id = o.user_id
            where o.deleted = 0 and c.deleted = 0 ".$whereDate.$whereServiceType."
            group by c.item_id, o.val_stattus, o.date, o.main_order_id ".$orderBy."
            ;", array() );


    $data["list"] = [];

    foreach ($result as $key => $value) {
       $row_key = $value["main_order_id"] . "_" . $value["item_id"];
       $row[$row_key]["details"] = [
        "item_name" => $value["item_name"],
        "service_type" => $value["service_type"],
        "date" => $value["date"],
        "customer_name" => $value["customer_name"],
        "main_order_id" => $value["main_order_id"]
       ];
       $row[$row_key][$value["val_stattus"]] = $value["total"];

       $data["list"] = $row;
    }


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


        $result = $this->db->Select("select p.* FROM  orders o
            left join  payment p  ON o.id = p.order_id
            where o.id = ?", array($id) )[0];
        $d["details"] = $result;

        $html = loadView('components/'.$this->view.'/views/modal_details', $d);
        $header = "Proof of Payment";
        $button = '';
        if($action == "generatepayment") {
            

            $result = $this->db->Select(" 
             select o.val_status, o.id, o.order_no, o.created_at,o.type_order , t.item_name, 
            case when o.type_order = 'SELL' THEN ifnull(dp.dp_amount, t.price) ELSE o.total_payment end price
            , u.email, p.checkoutURL
            FROM orders o
            inner join items t on t.item_id = o.item_id
            inner join users u on u.user_id = o.user_id
            left join payment p ON p.order_id = o.id
            inner join cart c ON c.order_no = o.order_no
            left join details_financing dp ON dp.id = c.item_finance where o.id = ? ",  array($id) )[0];

            $d["details"] = $result;
            $header = "Generate Payment Order ID ." . $id;
            $html = loadView('components/'.$this->view.'/views/generatepayment', $d);
            $button = '<button class="btn btn-primary" type="submit">Generate Payment</button>';
        }

        $res = [
            'header'=> $header,
            "html" => $html,
            'button' => $button,
            'action' => 'afterSubmit'
        ];

        echo json_encode($res);
    }

    public function afterSubmit(){
        $data = getRequestAll();

        extract($data);
        if(isset($action_type_process)) {

            $r = (method_exists($this, $action_type_process)) ? $this->{$action_type_process}() : [];
        

            header('Location: index?type='.$r["status"].'&message='.$r["message"].'');
            exit();
        }

        // if(isset($id)) {
        //     //edit 

        //     $this->db->Update("update brand SET name = ?  WHERE id = ? ",
        //      array($name, $id  ));
  
        //     header('Location: index?type=success&message=Successfully Updated!');
        //     exit();
        // } else {
          

        //     $this->db->Insert("INSERT INTO brand (`name`) VALUES (?)", [
        //         $name
        //     ]);
            
        //     header('Location: index?type=success&message=Successfully Registered!');
        //     exit();
        // }
        
    }

    public function generatePayment(){
        $data = getRequestAll();
        extract($data);


        $payment = [
            "amount" => $amount,
            "order_id" => $id,
            "added_by" => $_SESSION["user_id"],
            "payment_no" => '',
            "payload" => '',
            "checkoutURL" => ''
        ];


     
        $amount = $amount > 100 ? $amount : $amount * 10;
        $payload = [
            'body' => json_encode([
                'data' => [
                    'attributes' => [
                        'amount' => ($amount * 100),
                        'description' => 'Payment for Oder ID.'.$id,
                        'remarks' => ''
                    ]
                ]
            ]),
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic c2tfdGVzdF9mOWJSeG5nTFVFa2FZU3d5UVpmRThnMm86Tml4YXNob3RvMjAwNSE=',
                'content-type' => 'application/json',
            ],
        ];

        try {
            $client = new Client();


            $response = $client->request('POST', 'https://api.paymongo.com/v1/links', $payload);

            // Get and display response body

            $responseBody = json_decode($response->getBody());
            $checkoutUrl = $responseBody->data->attributes->checkout_url;
            $payment["payload"] = json_encode($responseBody);
            $payment["payment_no"] = $responseBody->data->attributes->reference_number;
            $payment["checkoutURL"] = $checkoutUrl;

            
            $this->db->insertRequestBatchRquest($payment,'payment');

            $this->db->Update("update orders SET total_payment = ? WHERE id = ? ", array( $amount,$id) );


            $result = $this->db->Select("select * from orders where id = ?", array($id) )[0];

            $notification = [
                "sent_by" => $_SESSION["user_id"],
                "user_id" => $result["user_id"],
                "title" => "Payment for Order ID:".$result["order_no"],
                "message" => $checkoutUrl
            ];

            $this->db->insertRequestBatchRquest($notification,'notification');

        } catch (\Exception $e) {
            'Request failed: ' . $e->getMessage();

        }


    }

    public function updateStatus(){
        $data = getRequestAll();

        extract($data);

        $this->db->Update("update orders SET val_status = ? WHERE id = ? ", array( $status,$id) );

        $result = $this->db->Select("select * from orders where id = ?", array($id) )[0];

        $notification = [
            "sent_by" => $_SESSION["user_id"],
            "user_id" => $result["user_id"],
            "title" => "Payment for Order ID:".$result["order_no"],
            "message" => "Has been ". $status. ' <br> Datetime: '. date('Y/m/d H:i:s')
        ];
        $this->db->insertRequestBatchRquest($notification,'notification');


        // if($status == "PROCESSED") {
        //     $this->db->Update("update items SET item_type = 2 WHERE item_id = ? ", array( $result["item_id"]) );
        //     $this->db->Update("update items SET status = 0 WHERE item_id = ? ", array($result["item_id"]) );

        // }
        // if($status == "DECLINED") {
        //     $this->db->Update("update items SET status = 1 WHERE item_id = ? ", array($result["item_id"]) );
        // }

        

        $res = [
            'status'=> true,
            'msg' => 'Successfully deleted!'
        ];

        echo json_encode($res);
    }


  

}
