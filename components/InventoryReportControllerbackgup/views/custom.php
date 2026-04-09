<div class="col-xl-12 col-md-12">
    <div class="card table-card">
        <div class="card-header">
            <h5>INVENTORY</h5>
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
                            <th>ITEM CODE</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Unit Price</th>
                            <th>STOCK IN</th>
                            <th>STOCK OUT</th>
                            <th>BALANCE</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                $item_status = [
                                    0 => '<label class="label label-warning">AVAILABLE</label>',
                                    1 => '<label class="label label-danger">OUT OF STOCK</label>',
                                ];
                                if(isset($list) && count($list) > 0){
                                

                                    foreach ($list as $key => $value) {
                                        $balance = $value["total_in"] - $value["total_sold"];
                                        $status = $balance > 0 ? $item_status[0] : $item_status[1];
                                        ?>
                                        <tr>
                                            <td><?=$value["item_code"]?></td>
                                            <td><?=$value["item_name"]?></td>
                                            <td><?=$value["brand_name"]?></td>
                                            <td><?=number_format($value["price"],2)?></td>
                                            <td><?=number_format($value["total_in"],2)?></td>
                                            <td><?=number_format($value["total_sold"],2)?></td>
                                            <td><?=number_format($value["total_in"] - $value["total_sold"],2)?></td>
                                            <td><?=$status?></td>
                                            </td>
                                        </tr>
    
                                        <?php
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
