<?php 


class TestTestController {

    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }


    public function index() {


        return ["content" => "test"];

    }
    public function js(){
        return [
            'TestTestController/js/custom.js',
        ];
    }

    public function css(){
        return [];
    }




    public function text() {


        echo json_encode(["test"]);

    }

  

}
