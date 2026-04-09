<div class="col-xl-12 col-md-12">
    <button class="btn waves-effect waves-light btn-primary openmodaldetails-modal" data-type="add"><i class="fa fa-plus"></i>&nbsp;Add New</button>

    <div class="card table-card">
        <div class="card-header">
            <h5>List of Sub-services</h5>
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
        <div class="card-block ">
            

         
                <div class="table-responsive p-2">
                    <table class="table table-hover" id="maintable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Service Type</th>
                            <th>Unit Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($list) && count($list) > 0){
                                    $status = [
                                        1 => '<label class="label label-success">ACTIVE</label>',
                                        0 => '<label class="label label-danger">INACTIVE</label>',
                                    ];
                                    $item_status = [
                                        0 => '<label class="label label-success">PRODUCT</label>',
                                        1 => '<label class="label label-info">BIDDING</label>',
                                        2 => '<label class="label label-primary">SERVICES</label>',
                                    ];
                                    foreach ($list as $key => $value) {
                                       
                                        ?>
                                        <tr>
                                            <td><?=$value["item_id"]?></td>
                                            <td><?=$value["item_code"]?></td>
                                            <td><?=$value["item_name"]?></td>
                                            <td><?=$value["item_description"]?></td>
                                            <td><?=$value["brand_name"]?></td>
                                            <td><?=number_format($value["price"],2)?></td>
                                            <td><?=$status[$value["status"]]?></td>
                                            <td>
                                                
                                                <?php 
                                                    ?>
                                                    <button class="btn waves-effect waves-light btn-grd-primary btn-sm openmodaldetails-modal" data-type="edit" data-id="<?=$value["item_id"]?>"><i class="fa fa-edit " ></i></button>
                                                    |
                                                    <button class="btn waves-effect waves-light btn-grd-danger btn-sm delete" data-id="<?=$value["item_id"]?>"><i class="fa fa-times " ></i></button>
                                                    <?php
                                                ?>
                                            
                                            </td>
                                        </tr>
    
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="4"> NO RECORD FOUND!</td>
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
