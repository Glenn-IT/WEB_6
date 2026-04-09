<div class="col-xl-12 col-md-12">
    <button class="btn waves-effect waves-light btn-primary openmodaldetails-modal" data-type="add"><i class="fa fa-plus"></i>&nbsp;Add New</button>
    <div class="card table-card">
        <div class="card-header">
            <h5>Stocking</h5>
            <div class="card-header-right">
                <ul class="list-unstyled card-option">
                    <li><i class="fa fa fa-wrench open-card-option"></i></li>
                    <li><i class="fa fa-window-maximize full-card"></i></li>
                    <li><i class="fa fa-minus minimize-card"></i></li>
                    <li><i class="fa fa-refresh reload-card"></i></li>
                    <li><i class="fa fa-trash close-card"></i></li>
                </ul>
            </div>
        </div>
        <div class="card-block">
            

         
                <div class="table-responsive p-2">
                    <table class="table table-hover" id="maintable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Code</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(count($list) > 0){
                                    $i = 1;
                                    foreach ($list as $key => $value) {
                                        $status = [
                                            "PENDING" => '<label class="label label-primary">PENDING</label>',
                                            "APPROVED" => '<label class="label label-success">APPROVED</label>',
                                            "CANCELED" => '<label class="label label-danger">CANCELED</label>',
                                        ];
                                        ?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td><?=$value["item_code"]?></td>
                                            <td><?=$value["item_name"]?></td>
                                            <td><?=$value["brand_name"]?></td>
                                            <td><?=number_format($value["price"],2)?></td>
                                            <td><?=$value["quantity"]?></td>
                                            <td><?=isset($status[$value["val_status"]]) ?$status[$value["val_status"]]: $value["val_status"] ?></td>
                                            </td>
                                            <td>
                                                <button class="btn waves-effect waves-light btn-grd-primary btn-sm openmodaldetails-modal" data-type="edit" data-id="<?=$value["id"]?>"><i class="fa fa-edit " ></i></button>
                                                <?php 
                                                    if($value["val_status"] == "PENDING") {
                                                        ?>
                                                        |
                                                        <button class="btn waves-effect waves-light btn-grd-success btn-sm delete" data-id="<?=$value["id"]?>">APPROVED</button>
                                                        |
                                                        <button class="btn waves-effect waves-light btn-grd-danger btn-sm archive" data-id="<?=$value["id"]?>">CANCEL</button>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
    
                                        <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="8"> NO RECORD FOUND!</td>
                                    </tr>

                                    <?php
                                }
                            ?>
                        
                        </tbody>
                    </table>
                   
                </div>   



        </div>
    </div>
</div>
