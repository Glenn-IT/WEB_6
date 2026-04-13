<div class="col-xl-12 col-md-12">
    <div class="card table-card">
        <div class="card-header">
            <h5>Transactions Order</h5>
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
                            <th>Product Name</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Service Type</th>
                            <th>Type of Delivery</th>
                            <th>Location / Address</th>
                            <th>Transaction Date</th>
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
                                        'PROCESSED' => '<label class="label label-success">PROCESSED</label>',
                                        'CANCELLED' => '<label class="label label-danger">DECLINED</label>',
                                    ];
                                    $i=1;
                                    foreach ($list as $K => $V) {
                                          $item_name = '';
                                          $price = '';
                                          $qty = '';
                                          $total = '';
                                          foreach ($V as $key => $value) {
                                            $item_name .= $value["item_name"].'<br><hr>';
                                            $price .= number_format($value["price"],2).'<br><hr>';
                                            $qty .= $value["quantity"].'<br><hr>';
                                            $total .= number_format($value["price"] * $value["quantity"],2).'<br><hr>';

                                          }
                                        ?>
                                        <tr>
                                            <td><?=$i++?></td>
                                            <td><?=$value["order_no"]?></td>
                                            <td><?=$value["account_first_name"]. ' '.$value["account_last_name"]?></td>
                                            <td><?=$item_name?></td>
                                            <td><?=$price?></td>
                                            <td><?=$qty?></td>
                                            <td><?=$total?></td>
                                            <td>
                                                <?php
                                                    $stype = isset($value["service_type"]) ? $value["service_type"] : 'walk-in';
                                                    $stype_badges = [
                                                        'walk-in'      => '<span class="label" style="background:#28a745;color:#fff;padding:3px 8px;border-radius:4px;">Walk-in</span>',
                                                        'home'         => '<span class="label" style="background:#007bff;color:#fff;padding:3px 8px;border-radius:4px;">Home Service</span>',
                                                        'hotel'        => '<span class="label" style="background:#6f42c1;color:#fff;padding:3px 8px;border-radius:4px;">Hotel Service</span>',
                                                    ];
                                                    echo isset($stype_badges[$stype]) ? $stype_badges[$stype] : '<span class="label" style="background:#6c757d;color:#fff;padding:3px 8px;border-radius:4px;">'.htmlspecialchars($stype).'</span>';
                                                ?>
                                            </td>
                                            <td><?=$value["type_of_payment"]?></td>
                                            <td>
                                                <?php
                                                    if ($stype === 'hotel') {
                                                        if (!empty($value["hotel_name"])) {
                                                            echo '<strong>Hotel:</strong> ' . htmlspecialchars($value["hotel_name"]) . '<br>';
                                                        }
                                                        if (!empty($value["hotel_room"])) {
                                                            echo '<strong>Room:</strong> ' . htmlspecialchars($value["hotel_room"]);
                                                        }
                                                    } elseif ($stype === 'home') {
                                                        echo !empty($value["billing_address"]) ? htmlspecialchars($value["billing_address"]) : '<span class="text-muted">—</span>';
                                                    } else {
                                                        echo '<span class="text-muted">In-store</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td><?=!empty($value["created_at"]) ? date('F j, Y', strtotime($value["created_at"])) : 'N/A'?></td>
                                            <td><?=isset($status[$value["val_stattus"]])?$status[$value["val_stattus"]]:$value["val_stattus"]?></td>
                                            <td>
                                            <?php 
                                                if($_SESSION["user_type"] == 2) {

                                                    if($value["val_stattus"] == "PENDING") {
                                                        ?>
                                                        <button class="btn waves-effect waves-light btn-grd-success btn-sm updateORder" data-status="PROCESSED" data-id="<?=$K?>">PROCESSED</button>
                                                        |
                                                        <button class="btn waves-effect waves-light btn-grd-danger btn-sm updateORder" data-status="DECLINED" data-id="<?=$K?>">DECLINE</button>
                                                        <?php
                                                    } elseif ($value["val_stattus"] == "PROCESSED") {
                                                        ?>
                                                        <button class="btn waves-effect waves-light btn-grd-success btn-sm updateORder" data-status="COMPLETED" data-id="<?=$K?>">COMPLETED</button>
                                                        |
                                                        <button class="btn waves-effect waves-light btn-grd-danger btn-sm updateORder" data-status="DECLINED" data-id="<?=$K?>">CANCEL</button>
                                                        <?php
                                                    }
                                                    // CANCELLED and DECLINED appointments show no action buttons
                                                }
                                                ?>

                                            </td>
                                        </tr>
    
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="13"> NO RECORD FOUND!</td>
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
