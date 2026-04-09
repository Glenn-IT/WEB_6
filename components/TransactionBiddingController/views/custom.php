<div class="col-xl-12 col-md-12">
    <div class="card table-card">
        <div class="card-header">
            <h5>Booking List</h5>
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
                            <th>Order No.</th>
                            <th>Customer Name</th>
                            <th>Therapist Nam</th>
                            <th>Date & Time Schedule</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($list) && count($list) > 0){
                                    $status = [
                                        'PENDING' => '<label class="label label-primary">PENDING</label>',
                                        'DECLINED' => '<label class="label label-danger">DECLINED</label>',
                                        'COMPLETED' => '<label class="label label-success">COMPLETED</label>',
                                        'PROCESSED' => '<label class="label label-warning">PROCESSED</label>',
                                        'CANCELLED' => '<label class="label label-danger">DECLINED</label>',
                                    ];

                                    foreach ($list as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?=$value["main_order_id"]?></td>
                                            <td><?=$value["order_no"]?></td>
                                            <td><?=$value["full_name"]?></td>
                                            <td><?=$value["therapistname"]?></td>
                                            <td><?=$value["date"].' '.$value["format_time"]?></td>
                                            <td><?=$status[$value["val_status"]]?></td>
                                            <td>
                                                <button class="btn waves-effect waves-light btn-grd-primary btn-sm openmodaldetails-modal" data-type="edit" data-id="<?=$value["main_order_id"]?>"><i class="fa fa-eye"></i> VIEW</button>
                                                
                                                <?php 
                                                if($value["val_status"] == "PENDING") {
                                                    ?>
                                                    <button class="btn waves-effect waves-light btn-grd-warning btn-sm updateORder" data-status="PROCESSED" data-id="<?=$value["main_order_id"]?>">PROCESSED</button>
                                                    |
                                                    <button class="btn waves-effect waves-light btn-grd-danger btn-sm updateORder" data-status="DECLINED" data-id="<?=$value["main_order_id"]?>">DECLINE</button>
                                                    <?php
                                                } elseif ($value["val_status"] == "PROCESSED") {
                                                    ?>
                                                    <button class="btn waves-effect waves-light btn-grd-success btn-sm updateORder" data-status="COMPLETED" data-id="<?=$value["main_order_id"]?>">COMPLETE</button>
                                                    |
                                                    <button class="btn waves-effect waves-light btn-grd-danger btn-sm updateORder" data-status="DECLINED" data-id="<?=$value["main_order_id"]?>">DECLINE</button>
                                                    <?php
                                                }
                                                // CANCELLED appointments are treated same as DECLINED - no additional actions needed
                                                ?>
                                         
                                            </td>
                                        </tr>
    
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td > NO RECORD FOUND!</td>
                                        <td > </td>
                                        <td > </td>
                                        <td > </td>
                                        <td > </td>
                                        <td > </td>
                                        <td > </td>

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
