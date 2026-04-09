<?php 


class InventoryStockinController {

    protected $db;
    protected $view;

    public function __construct($db) {
        $this->db = $db;
        $this->view = "InventoryStockinController";
    }


    public function index() {

        $data = [];

        $data["list"] = $this->db->Select("
        select 
            it.*,
            b.name  brand_name,
            s.quantity,
            s.val_status ,
            s.id
            from stock_in s
            inner join items it ON it.item_id = s.item_id
            inner join brand b ON  b.id = it.brand_id
            WHERE it.status = 1 and it.deleted = 0 and it.item_type = 0
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

        if($action == "edit" && ($id != '' || $id != 'undefined') ) {
            $result = $this->db->Select("select * from stock_in where id = ?", array($id) )[0];
            $d["details"] = $result;
        }


        $d["listofitems"] = $this->db->Select("
        select 
            it.*,
            b.name  brand_name
            from  items it 
            inner join brand b ON  b.id = it.brand_id
            WHERE it.status = 1 and it.deleted = 0 and it.item_type = 0
        ", array() );

        $res = [
            'header'=> (isset($action) && $action == "add") ? "Add " : 'Edit ',
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
            $this->db->Update("update stock_in SET quantity = ".$quantity." , item_id = ".$item_id." WHERE id = ? ", array( $id) );
  
            header('Location: index?type=success&message=Successfully Updated!');
            exit();
        } else {

            $this->db->insertRequestBatchRquest($data,'stock_in');
            header('Location: index?type=success&message=Successfully Added!');
            exit();
        }
        
    }

    public function delete(){
        $data = getRequestAll();

        extract($data);

        $this->db->Update("update stock_in SET val_status = 'APPROVED' WHERE id = ? ", array( $id) );


        $res = [
            'status'=> true,
            'msg' => 'Successfully APPROVED!'
        ];

        echo json_encode($res);
    }


    

    public function archived(){
        $data = getRequestAll();

        extract($data);

        $this->db->Update("update stock_in SET val_status = 'CANCELED' WHERE id = ?", array( $id) );

        $res = [
            'status'=> true,
            'msg' => 'Successfully CANCELED!'
        ];

        echo json_encode($res);
    }


}
