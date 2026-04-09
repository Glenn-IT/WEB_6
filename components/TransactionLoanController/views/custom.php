<div class="col-xl-12 col-md-12">
    <div class="card table-card">
        <div class="card-header">
            <h5>Loan Management</h5>
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
            

         
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Order No.</th>
                            <th>Customer Email</th>
                            <th>Amount</th>
                            <th>No. Of Months</th>
                            <th>Monthly</th>
                            <th>Paid Amount</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                                if(isset($list) && count($list) > 0){
                                    $status = [
                                        '1' => '<label class="label label-primary">ACTIVE</label>',
                                        '2' => '<label class="label label-success">PAID</label>',
                                        '3' => '<label class="label label-danger">INACTIVE</label>',
                                    ];

                                    foreach ($list as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?=$value["id"]?></td>
                                            <td><?=$value["item_name"]?></td>
                                            <td><?=$value["item_code"]?></td>
                                            <td><?=$value["order_no"]?></td>
                                            <td><?=$value["email"]?></td>
                                            <td><?=number_format($value["price"],2)?></td>
                                            <td><?=number_format($value["months"],2)?></td>
                                            <td><?=number_format($value["total_with_interest"],2)?></td>
                                            <td><?=number_format($value["paid_total"],2)?></td>

                                            <td><?=number_format(($value["price"] - $value["paid_total"]),2)?></td>
                                            
                                            <td><?=$status[$value["loan_status"]]?></td>
                                            <td>
                                            <button class="btn waves-effect waves-light btn-grd-primary btn-sm openmodaldetails-modal" data-type="edit" data-id="<?=$value["id"]?>"><i class="fa fa-edit " ></i></button>
                                            </td>
                                        </tr>
    
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="12"> NO RECORD FOUND!</td>
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
