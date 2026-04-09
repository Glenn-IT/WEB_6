<div class="page-content my-account__orders-list">
    <table class="orders-table">
        <thead>
        <tr>
            <th></th>
            <th>Item Name</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        if(count($list) > 0 ) {
            foreach ($list as $key => $value) {
                ?>
                <tr>
                    <td><button class="btn btn-danger btn-sm"><a href="<?=$_ENV['URL_HOST'].'customer/customer/checkout?deleted=0&id='.$value["id"]?>"> <i class="fa fa-trash"></i> </a></button></td>
                    <td><?=$value["item_name"]?></td>
                    <td><?=number_format($value["price"],2)?></td>
                    <td><?=number_format($value["quantity"],2)?></td>
                    <td><?=number_format($value["price"] * $value["quantity"],2)?></td>
                    <!-- <td><button class="btn btn-primary btn-acknowledgebid" data-href="<?=$_ENV['URL_HOST'].'customer/customer/checkout?id='.$value["id"]?>">CHECKOUT</button></td> -->
                     <td><input type="checkbox" class="form-check" name="checkbox[]" value=""></td>
                </tr>
                <?php
            }
        }
            
        ?>
        </tbody>
    </table>
</div>
<button class="btn btn-primary btn-acknowledgebid" data-href="<?=$_ENV['URL_HOST'].'customer/customer/checkout?id='.$value["id"]?>">CHECKOUT</button>
