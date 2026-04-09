<?php 


class DashboardController {

    protected $db;
    protected $Name;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->Name = "dashboard";
        $this->view = "DashboardController";
    }


    public function index() {

        $req = getRequestAll();

        extract($req);

        $summary = $this->db->Select("select 
                (select count(main_order_id) from main_order where val_stattus = 'PROCESSED' ) as on_going,
                (select count(main_order_id) from main_order where val_stattus IN ('DECLINED', 'CANCELLED') ) as failed,
                (select count(main_order_id) from main_order where val_stattus = 'COMPLETED' ) as completed,
                (select count(main_order_id) from main_order where val_stattus = 'PENDING' ) as pending", [])[0];

        $data["topitem"] = $this->db->Select("select t.* FROM orders o inner join items t ON t.item_id = o.item_id where val_status = 'PROCESSED' order by total_payment desc limit 5 ", array() );


        $data["payments"] = $this->db->Select("select o.*, u.username from orders o 
                    inner join users u ON u.user_id = o.user_id
                    where o.deleted = 0 and o.val_status = 'PENDING' ", array() );
        $data["summary"] = $summary;


        $date = '';

        if(isset($from) && isset($to) && $from != '' && $to != '') {
            $from = $from . ' 00:00:00';
            $to = $to . ' 23:59:59';

            $date = ' and (o.created_at) between "'.$from.'" and "'.$to.'" ';
        }

        $data["list"] = $this->db->Select(" 
        select o.val_status, o.id, o.order_no, o.created_at,o.type_order , t.item_name, 
        case when o.type_order = 'SELL' THEN ifnull(dp.dp_amount, t.price) ELSE o.total_payment end price
            , u.email, p.checkoutURL
            FROM orders o
            inner join items t on t.item_id = o.item_id
            inner join users u on u.user_id = o.user_id
            left join payment p ON p.order_id = o.id
            inner join cart c ON c.order_no = o.order_no
            left join details_financing dp ON dp.id = c.item_finance 
            where o.val_status = 'PROCESSED' $date
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




    public function text() {


        echo json_encode(["test"]);

    }

    public function dailysales(){

        $predication = $this->db->Select("select `year`, sum(sales) as sales from (
            SELECT (`date`) `year`, sum(Unit_Price * Units_Sold) as sales FROM `rows` 
            group by (`date`)
            union all
            SELECT (c.`date`) `year` , sum(t.price) sales FROM cart c
            inner join items t ON t.item_id = c.item_id
            where c.val_status ='COMPLETED' and t.item_type = 2 
            group by (c.`date`)

            union all
            SELECT (o.`created_at`) `year` , sum(t.price * c.quantity) sales FROM cart c
            inner join items t ON t.item_id = c.item_id
            inner join orders o ON o.order_no = c.order_no
            where o.val_status ='COMPLETED' and t.item_type = 0 
            group by (o.`created_at`)

            ) m
            group by `year`
            ; ", array() );
    
        $data = [];
        foreach ($predication as $row) {
            $data[] = array(
                'y' => ''.$row['year'].'',
                'actual' => $row['sales']
            );
        }

        echo json_encode($data);

    }
    public function predictiveAnalytic(){

        $predication = $this->db->Select("select `year`, sum(sales) as sales from (
            SELECT YEAR(`date`) `year`, sum(Unit_Price * Units_Sold) as sales FROM `rows` 
            group by YEAR(`date`)
            union all
            SELECT YEAR(c.`date`) `year` , sum(t.price) sales FROM cart c
            inner join items t ON t.item_id = c.item_id
            where c.val_status ='COMPLETED' and t.item_type = 2
            group by YEAR(c.`date`)

            union all
            SELECT YEAR(o.`created_at`) `year` , sum(t.price * c.quantity) sales FROM cart c
            inner join items t ON t.item_id = c.item_id
            inner join orders o ON o.order_no = c.order_no
            where o.val_status ='COMPLETED' and t.item_type = 0
            group by YEAR(o.`created_at`)

            ) m
            group by `year`
            ; ", array() );
    
        $data = [];
        foreach ($predication as $row) {
            $data[] = array(
                'y' => ''.$row['year'].'',
                'actual' => $row['sales'],
                'predictive' => null
            );
        }

        echo json_encode($data);

    }
    public function totalClientsPerYear(){

        $result = $this->db->Select("
            SELECT MONTH(m.created_at) as month, COUNT(DISTINCT m.user_id) as client_count 
            FROM main_order m 
            WHERE YEAR(m.created_at) = YEAR(NOW()) 
            AND m.deleted = 0 
            GROUP BY MONTH(m.created_at) 
            ORDER BY MONTH(m.created_at)", array() );
    
        $hold = [];
        foreach ($result as $row) {
            $hold[$row['month']] = $row['client_count'];
        }

        // Build data for 12 months
        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthName = date("M", mktime(0, 0, 0, $month, 1)); // Jan, Feb, etc.
            $data[] = [
                'y' => ''.$monthName.'',
                'clients' => isset($hold[$month]) ? $hold[$month] : 0
            ];
        }

        echo json_encode($data);

    }

    public function topseelingProducts(){

        $result = $this->db->Select("
            SELECT i.item_name, COUNT(c.item_id) as booking_count
            FROM cart c
            INNER JOIN items i ON i.item_id = c.item_id
            INNER JOIN main_order m ON m.main_order_id = c.main_order_id
            WHERE m.val_stattus IN ('COMPLETED', 'PROCESSED') 
            AND m.deleted = 0
            GROUP BY c.item_id, i.item_name
            ORDER BY booking_count DESC
            LIMIT 5
            ", array() );
    
        $data = [];
        foreach ($result as $row) {
            $data["label"][] = $row["item_name"];
            $data["total"][] = $row["booking_count"];  
        }

        echo json_encode($data);

    }

    public function userIDentity(){

     
      $result = $this->db->Select("
        select gender, (total_user / total) * 100 as percent 
        from ( 
            select gender, count(user_id) total_user, (SELECT count(u.user_id) total 
            FROM users u where u.user_type = 5) as  total from users 
            where user_type = 5 group by gender 
        ) main;
        ", array() );
    
         $data = [];
        foreach ($result as $row) {
            $data[] = array(
                'label' => $row['gender'] == '' ? 'Undecided' : $row['gender'],
                'value' => number_format($row['percent'],2),
            );
        }
        

        echo json_encode($data);

    }
    
}
