<?php 


class TransactionLoanController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "TransactionLoanController";
    }


    public function index() {
        $data = [];

        $data["list"] = $this->db->Select(" 
        select o.val_status, o.id, o.order_no, o.created_at,o.type_order , t.item_name,  t.item_code, o.loan_status,
     case when o.type_order = 'SELL' THEN ifnull(dp.dp_amount, t.price) ELSE o.total_payment end price
        , u.email, p.checkoutURL , dp.interest, dp.months, dp.total_amount, dp.total_with_interest
        , (select sum(amount) from payment_loan where order_id = o.id) paid_total

        FROM orders o
        inner join items t on t.item_id = o.item_id
        inner join users u on u.user_id = o.user_id
        inner join payment p ON p.order_id = o.id
        inner join cart c ON c.order_no = o.order_no
        inner join details_financing dp ON dp.id = c.item_finance 
        where  o.val_status = 'PROCESSED'
        order by o.id desc
        ", array() );
        
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
            $result = $this->db->Select("select * from payment_loan where order_id = ?", array($id) );
            $d["list"] =$result;
            $d["orderid"] = $id;

        }

      

        $res = [
            'header'=> "Transaction",
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
         //edit 
        $table = "payment_loan";
        foreach ($finance as $key => $value) {
            $value["order_id"] =  $order_id;

            if(isset($value["primary_id"])) {

                $where = [
                    'id' => $value["primary_id"],
                ];

                unset($value["primary_id"]);

                $this->updateItem($table, $value, $where);
            } else {
                $this->db->insertRequestBatchRquest($value,'payment_loan');
            }

        }
        header('Location: index?type=success&message=Successfully Updated!');
        exit();
        
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
