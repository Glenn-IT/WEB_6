<?php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dsn = "mysql:host=".$_ENV['DBHOST'].";dbname=".$_ENV['DBNAME'];
$pdo = new PDO($dsn, $_ENV['DBUSER'], $_ENV['DBPWD'] ?? '');

// Simulate exactly what source() runs
$stmt = $pdo->prepare("select o.main_order_id, o.orrder_no as order_no, o.`date`, o.`time`, 
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
    where o.orrder_no = ?
    order by o.main_order_id desc");
$stmt->execute(['841378']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo "<h2>source() result for order 841378:</h2><pre>";
print_r($row);
echo "</pre>";
