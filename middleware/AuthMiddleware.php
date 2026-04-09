<?php

class AuthMiddleware {
    protected $db;
    protected $json_data;

    protected $allow = ["auth","authRegister","forgot_password","otp","changepassword","verify-security-answer","","/","customer" ];
    protected $config = ["changepassword" ];

    protected $template = ["component", "customer" ];


    public function __construct($db) {
        $this->db = $db;
    }

    public function handle() {
        $this->json_data =  getRequestAll();


        $this->viewpage();

        // Get current segments
        $segment1 = isset(getSegment()[1]) ? getSegment()[1] : '';
        $segment2 = isset(getSegment()[2]) ? getSegment()[2] : '';
        $segment3 = isset(getSegment()[3]) ? getSegment()[3] : '';

        // Check if accessing customer login/index page (should be allowed without auth)
        $isCustomerIndex = ($segment1 == "customer" && $segment2 == "customer" && $segment3 == "index");

        if (in_array($segment1, $this->allow) || $isCustomerIndex) {
            if($this->loginUserAuth()) {     
                
                if(!in_array($segment1, $this->template) && !in_array($segment1, $this->allow)) {
                    header('Location: ' . baseUrl('/component/dashboard/index'));
                    // if($_SESSION["user_type"]==5){
                    //     header('Location: ' . baseUrl('/customer/customer/index'));
                    // } else {
                    //     header('Location: ' . baseUrl('/component/dashboard/index'));
                    // };
                    exit;
                } 
            
                if($_SESSION["user_type"]!=5 && $segment1 == "customer"){
                    header('Location: ' . baseUrl('/component/dashboard/index'));
                    exit;
                }

            } 
    
        }  else {
            if(!$this->loginUserAuth()) {   
                $token = "";
                if(isset($_SESSION["viewpapgeauth"])) { 
                    $token = $_SESSION["viewpapgeauth"];
                }
                session_destroy();

                $_SESSION["viewpapgeauth"] = $token;

                header('Location: ' . baseUrl('/customer/customer/index'));
                exit();
            }

            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION["user_type"]) && $_SESSION["user_type"]==5 && $segment1 == "component") {
                header('Location: ' . baseUrl('/customer/customer/index'));
                exit();
            }

        }
        
    }

    public function viewpage(){
        $token = generateToken();

        if(!isset($_SESSION["viewpapgeauth"])) { 
            $_SESSION["viewpapgeauth"] = $token;

            $view_page = [
                "token" => $token
            ];

            $this->db->insertRequestBatchRquest($view_page,'view_page');
        }
    }

    private function loginUserAuth(){

        if(isset($_SESSION["token"])) {

            $data = $this->db->Select("select * from users where token = ? ", array($_SESSION["token"]) );
            if(count($data) > 0){
                return true;
            } else {
                return false;
            }



        }

        return false;


    }

}
