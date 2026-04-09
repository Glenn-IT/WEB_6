<div class="page-content my-account__orders-list">
    <table class="orders-table">
        <thead>
        <tr>
            <th>Order</th>
            <th>Date</th>
            <th>Item Name</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Payment Type</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        if(count($list) > 0 ) {
            foreach ($list as $key => $value) {
                ?>
                <tr>
                    <td><?=$value["order_no"]?></td>
                    <td><?=!empty($value["created_at"]) ? date('Y/m/d', strtotime($value["created_at"])) : 'N/A'?></td>
                    <td><?=$value["item_name"]?></td>
                    <td><?=number_format($value["price"],2)?></td>
                    <td><?=number_format($value["quantity"],2)?></td>
                    <td><?=number_format($value["price"] * $value["quantity"],2)?></td>
                    <td>COD</td>
                    <td><?=$value["val_status"]?></td>
                   
                </tr>
                <?php
            }
        }
            
        ?>
        </tbody>
    </table>
</div>