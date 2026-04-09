<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ComponentHelper {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }


    public function sentToEmail($recipient, $subject, $body) {
        try {
            error_log("=== PHPMAILER DEBUG START ===");
            error_log("Recipient: " . $recipient);
            error_log("Subject: " . $subject);
            
            // Check environment variables
            error_log("EMAIL_EMAIL: " . ($_ENV['EMAIL_EMAIL'] ?? 'NOT SET'));
            error_log("EMAIL_APP_PASSWORD: " . (isset($_ENV['EMAIL_APP_PASSWORD']) ? 'SET (length: ' . strlen($_ENV['EMAIL_APP_PASSWORD']) . ')' : 'NOT SET'));

            $mail = new PHPMailer(true);

            // Server settings
            $mail->SMTPDebug  = 0;                                      // 0=off, 2=debug
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['EMAIL_EMAIL'];
            $mail->Password   = $_ENV['EMAIL_APP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;             // SSL via port 465
            $mail->Port       = 465;
            $mail->Timeout    = 30;                                     // 30s — InfinityFree can be slow
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ];

            error_log("PHPMailer configuration set (port 587 STARTTLS)");

            // Email content
            $mail->isHTML(true);
            $appName = $_ENV['APP_NAME'] ?? 'Touch & Care Spa';
            $mail->setFrom($_ENV['EMAIL_EMAIL'], $appName);
            $mail->addAddress($recipient);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            error_log("About to send email...");

            if (!$mail->send()) {
                error_log("Email sending FAILED: " . $mail->ErrorInfo);
                error_log("=== PHPMAILER DEBUG END ===");
                return false;
            } else {
                error_log("Email sending SUCCESS");
                error_log("=== PHPMAILER DEBUG END ===");
                return true;
            }
        } catch (Exception $e) {
            error_log("EMAIL EXCEPTION: " . $e->getMessage());
            error_log("Exception trace: " . $e->getTraceAsString());
            error_log("=== PHPMAILER DEBUG END ===");
            return false;
        }
    }

    
    public function sentSMS($number, $message){
        $send_data = [];

        //START - Parameters to Change
        //Put the SID here
        $send_data['sender_id'] = "PhilSMS";
        //Put the number or numbers here separated by comma w/ the country code +63
        $send_data['recipient'] = $number;
        //Put message content here
        $send_data['message'] = $message;
        //Put your API TOKEN here
        // $token = "991|KUk2Y8TdT3mgtJvDT1B7E5nwo8bnqcAMBkKXJ3Hq";
        $token = "1169|2rHPKCPe2tS6UWGb9qutIRAblvICOJnNt1teSdnv";
        //END - Parameters to Change
         
        //No more parameters to change below.
        $parameters = json_encode($send_data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://app.philsms.com/api/v3/sms/send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $headers = [];
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $get_sms_status = curl_exec($ch);
        
        return json_encode($get_sms_status);
        // var_dump($get_sms_status);
        // return true;
    }


    public function generateRandomString($length = 10) {
        $randomNumber = mt_rand(100000, 999999);
        return $randomNumber;
    }


}
