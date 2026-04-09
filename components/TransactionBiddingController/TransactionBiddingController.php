<?php 


use GuzzleHttp\Client;

// Include ComponentHelper for email functionality
require_once __DIR__ . '/../ComponentHelper/ComponentHelper.php';


class TransactionBiddingController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "TransactionBiddingController";
    }


    public function index() {
        $data = [];




        $data["list"] = $this->db->Select("select o.main_order_id, o.orrder_no as order_no, o.`date`, o.`time`, 
        DATE_FORMAT(o.`time`, '%h:%i %p') AS format_time,    
        o.val_stattus as val_status, o.no_ofhead , ifnull(t.`name`, 'Any Professional') therapistname  
        ,CONCAT(u.account_first_name, ' ', u.account_last_name) AS full_name,
        u.billing_address,
        u.contact_no,
        u.gender
        FROM main_order o 
        left join therapist t ON t.id = o.therapist_id
        inner join users u ON u.user_id = o.user_id
        where o.deleted = 0
        order by o.main_order_id desc", array() );
        
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




         $d["details"] = $this->db->Select("select o.main_order_id, o.orrder_no as order_no, o.`date`, o.`time`, 
        DATE_FORMAT(o.`time`, '%h:%i %p') AS format_time,    
        o.val_stattus as val_status, o.no_ofhead , ifnull(t.`name`, 'Any Professional') therapistname  
        ,CONCAT(u.account_first_name, ' ', u.account_last_name) AS full_name,
        u.billing_address,
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

        $html = loadView('components/'.$this->view.'/views/modal_details', $d);
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

        $this->db->Update("update main_order SET val_stattus = ? WHERE main_order_id = ? ", array( $status,$id) );

        $result = $this->db->Select("select mo.*, u.email, u.account_first_name, u.account_last_name from main_order mo 
            inner join users u ON u.user_id = mo.user_id 
            where mo.main_order_id = ?", array($id) )[0];

        $notification = [
            "sent_by" => $_SESSION["user_id"],
            "user_id" => $result["user_id"],
            "title" => "Order ID:".$result["orrder_no"],
            "message" => "Has been ". $status. ' <br> Datetime: '. date('Y/m/d H:i:s')
        ];
        $this->db->insertRequestBatchRquest($notification,'notification');

        // Send Gmail notification based on status
        $this->sendStatusNotificationEmail($result, $status);

        $clientName = $result['account_first_name'] . ' ' . $result['account_last_name'];
        $orderNo = $result['orrder_no'];
        
        $res = [
            'status'=> true,
            'msg' => 'Successfully Updated!',
            'clientName' => $clientName,
            'orderNo' => $orderNo,
            'newStatus' => $status
        ];

        echo json_encode($res);
    }

    /**
     * Send status notification email to client based on appointment status
     */
    private function sendStatusNotificationEmail($orderDetails, $status) {
        try {
            // Initialize ComponentHelper for email functionality
            $componentHelper = new ComponentHelper($this->db);
            
            // Prepare common email data
            $clientName = $orderDetails['account_first_name'] . ' ' . $orderDetails['account_last_name'];
            $orderNo = $orderDetails['orrder_no'];
            $appointmentDate = !empty($orderDetails['date']) ? date('F j, Y', strtotime($orderDetails['date'])) : 'N/A';
            $appointmentTime = !empty($orderDetails['time']) ? date('g:i A', strtotime($orderDetails['time'])) : 'N/A';
            
            // Customize email content based on status
            switch ($status) {
                case 'PROCESSED':
                    $this->sendConfirmationEmail($orderDetails, $componentHelper, $clientName, $orderNo, $appointmentDate, $appointmentTime);
                    break;
                    
                case 'COMPLETED':
                    $this->sendCompletionEmail($orderDetails, $componentHelper, $clientName, $orderNo, $appointmentDate, $appointmentTime);
                    break;
                    
                case 'DECLINED':
                    $this->sendCancellationEmail($orderDetails, $componentHelper, $clientName, $orderNo, $appointmentDate, $appointmentTime);
                    break;
                    
                default:
                    // For other statuses, just log
                    error_log("Status notification: Order #" . $orderNo . " status changed to " . $status);
                    break;
            }
            
        } catch (Exception $e) {
            error_log("Error sending status notification email: " . $e->getMessage());
        }
    }

    /**
     * Send confirmation email when appointment is processed/confirmed
     */
    private function sendConfirmationEmail($orderDetails, $componentHelper, $clientName, $orderNo, $appointmentDate, $appointmentTime) {
        $subject = "✅ Appointment Confirmed - Order #" . $orderNo;
        
        $emailBody = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h1 style='color: #27ae60; margin-bottom: 10px;'>Appointment Confirmed! ✅</h1>
                <p style='color: #7f8c8d; font-size: 16px;'>Your massage appointment has been successfully confirmed</p>
            </div>
            
            <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                <h2 style='color: #2c3e50; margin-top: 0;'>Dear {$clientName},</h2>
                <p style='color: #34495e; line-height: 1.6;'>
                    Great news! Your appointment has been <strong style='color: #27ae60;'>CONFIRMED</strong> by our team. 
                    We're excited to provide you with a relaxing and rejuvenating massage experience.
                </p>
            </div>
            
            <div style='background-color: #e8f5e8; padding: 20px; border-radius: 8px; border-left: 4px solid #27ae60; margin-bottom: 20px;'>
                <h3 style='color: #2c3e50; margin-top: 0;'>📅 Appointment Details</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Order Number:</td>
                        <td style='padding: 8px 0; color: #2c3e50;'>{$orderNo}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Date:</td>
                        <td style='padding: 8px 0; color: #2c3e50;'>{$appointmentDate}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Time:</td>
                        <td style='padding: 8px 0; color: #2c3e50;'>{$appointmentTime}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Status:</td>
                        <td style='padding: 8px 0; color: #27ae60; font-weight: bold;'>CONFIRMED</td>
                    </tr>
                </table>
            </div>
            
            <div style='background-color: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107; margin-bottom: 20px;'>
                <h3 style='color: #856404; margin-top: 0;'>⚠️ Important Reminders</h3>
                <ul style='color: #856404; margin: 0; padding-left: 20px;'>
                    <li>Please arrive 15 minutes before your scheduled time</li>
                    <li>Bring a valid ID for verification</li>
                    <li>Inform us of any health conditions or allergies</li>
                    <li>Feel free to contact us if you need to reschedule</li>
                </ul>
            </div>
            
            {$this->getEmailFooter()}
        </div>";
        
        $this->sendEmail($componentHelper, $orderDetails['email'], $subject, $emailBody, $orderNo, 'confirmation');
    }

    /**
     * Send completion email when appointment is completed
     */
    private function sendCompletionEmail($orderDetails, $componentHelper, $clientName, $orderNo, $appointmentDate, $appointmentTime) {
        $subject = "🎉 Thank you for your visit - Order #" . $orderNo;
        
        $emailBody = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h1 style='color: #27ae60; margin-bottom: 10px;'>Thank You for Your Visit! 🎉</h1>
                <p style='color: #7f8c8d; font-size: 16px;'>Your appointment has been completed successfully</p>
            </div>
            
            <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                <h2 style='color: #2c3e50; margin-top: 0;'>Dear {$clientName},</h2>
                <p style='color: #34495e; line-height: 1.6;'>
                    Thank you for choosing our spa services! We hope you had a wonderful and relaxing experience with us.
                    Your appointment has been marked as <strong style='color: #27ae60;'>COMPLETED</strong>.
                </p>
            </div>
            
            <div style='background-color: #e8f5e8; padding: 20px; border-radius: 8px; border-left: 4px solid #27ae60; margin-bottom: 20px;'>
                <h3 style='color: #2c3e50; margin-top: 0;'>📅 Completed Session Details</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Order Number:</td>
                        <td style='padding: 8px 0; color: #2c3e50;'>{$orderNo}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Date:</td>
                        <td style='padding: 8px 0; color: #2c3e50;'>{$appointmentDate}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Time:</td>
                        <td style='padding: 8px 0; color: #2c3e50;'>{$appointmentTime}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Status:</td>
                        <td style='padding: 8px 0; color: #27ae60; font-weight: bold;'>COMPLETED</td>
                    </tr>
                </table>
            </div>
            
            <div style='background-color: #e3f2fd; padding: 15px; border-radius: 8px; border-left: 4px solid #2196f3; margin-bottom: 20px;'>
                <h3 style='color: #1565c0; margin-top: 0;'>💝 We'd Love Your Feedback!</h3>
                <p style='color: #1565c0; margin: 0;'>
                    Your opinion matters to us! Please consider leaving a review or feedback about your experience.
                    This helps us improve our services and assist future clients.
                </p>
            </div>
            
            <div style='background-color: #f3e5f5; padding: 15px; border-radius: 8px; border-left: 4px solid #9c27b0; margin-bottom: 20px;'>
                <h3 style='color: #6a1b9a; margin-top: 0;'>🌟 Book Your Next Appointment</h3>
                <p style='color: #6a1b9a; margin: 0;'>
                    We'd love to see you again! Contact us to schedule your next relaxing session or explore our other services.
                </p>
            </div>
            
            {$this->getEmailFooter()}
        </div>";
        
        $this->sendEmail($componentHelper, $orderDetails['email'], $subject, $emailBody, $orderNo, 'completion');
    }

    /**
     * Send cancellation email when appointment is declined/cancelled
     */
    private function sendCancellationEmail($orderDetails, $componentHelper, $clientName, $orderNo, $appointmentDate, $appointmentTime) {
        $subject = "⚠️ Appointment Cancelled - Order #" . $orderNo;
        
        $emailBody = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
            <div style='text-align: center; margin-bottom: 30px;'>
                <h1 style='color: #e74c3c; margin-bottom: 10px;'>Appointment Cancelled ⚠️</h1>
                <p style='color: #7f8c8d; font-size: 16px;'>We regret to inform you about this cancellation</p>
            </div>
            
            <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                <h2 style='color: #2c3e50; margin-top: 0;'>Dear {$clientName},</h2>
                <p style='color: #34495e; line-height: 1.6;'>
                    We apologize, but your appointment has been <strong style='color: #e74c3c;'>CANCELLED</strong>. 
                    This may be due to schedule conflicts, unavailability, or other unforeseen circumstances.
                </p>
            </div>
            
            <div style='background-color: #ffebee; padding: 20px; border-radius: 8px; border-left: 4px solid #e74c3c; margin-bottom: 20px;'>
                <h3 style='color: #2c3e50; margin-top: 0;'>📅 Cancelled Appointment Details</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Order Number:</td>
                        <td style='padding: 8px 0; color: #2c3e50;'>{$orderNo}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Original Date:</td>
                        <td style='padding: 8px 0; color: #2c3e50;'>{$appointmentDate}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Original Time:</td>
                        <td style='padding: 8px 0; color: #2c3e50;'>{$appointmentTime}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; color: #34495e; font-weight: bold;'>Status:</td>
                        <td style='padding: 8px 0; color: #e74c3c; font-weight: bold;'>CANCELLED</td>
                    </tr>
                </table>
            </div>
            
            <div style='background-color: #e8f5e8; padding: 15px; border-radius: 8px; border-left: 4px solid #27ae60; margin-bottom: 20px;'>
                <h3 style='color: #27ae60; margin-top: 0;'>💚 We're Here to Help</h3>
                <p style='color: #27ae60; margin-bottom: 10px;'>Don't worry! We'd still love to serve you:</p>
                <ul style='color: #27ae60; margin: 0; padding-left: 20px;'>
                    <li>Contact us to reschedule for another available time</li>
                    <li>Explore alternative dates that work better for you</li>
                    <li>Ask about our other services and packages</li>
                    <li>Get priority booking for future appointments</li>
                </ul>
            </div>
            
            {$this->getEmailFooter()}
        </div>";
        
        $this->sendEmail($componentHelper, $orderDetails['email'], $subject, $emailBody, $orderNo, 'cancellation');
    }

    /**
     * Get common email footer
     */
    private function getEmailFooter() {
        return "
        <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;'>
            <p style='color: #7f8c8d; margin-bottom: 10px;'>Thank you for choosing our spa services!</p>
            <p style='color: #34495e; font-weight: bold; margin-bottom: 5px;'>" . $_ENV['APP_NAME'] . "</p>
            <p style='color: #7f8c8d; font-size: 14px; margin-bottom: 5px;'>" . $_ENV['APP_ADDRESS'] . "</p>
            <p style='color: #7f8c8d; font-size: 14px; margin-bottom: 5px;'>Phone: " . $_ENV['APP_NUMBER'] . "</p>
            <p style='color: #7f8c8d; font-size: 14px;'>Email: " . $_ENV['APP_EMAIL'] . "</p>
        </div>
        
        <div style='text-align: center; margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px;'>
            <p style='color: #7f8c8d; font-size: 12px; margin: 0;'>
                This is an automated message. Please do not reply to this email.<br>
                If you have any questions, please contact us directly.
            </p>
        </div>";
    }

    /**
     * Helper method to send email and handle logging
     */
    private function sendEmail($componentHelper, $email, $subject, $body, $orderNo, $type) {
        $emailSent = $componentHelper->sentToEmail($email, $subject, $body);
        
        if ($emailSent) {
            error_log("{$type} email sent successfully to: " . $email . " for Order #" . $orderNo);
        } else {
            error_log("Failed to send {$type} email to: " . $email . " for Order #" . $orderNo);
        }
    }


  

}
