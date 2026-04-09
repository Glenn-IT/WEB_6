<style>.big-check {
  width: 25px;
  height: 25px;
}</style>
<div class="page-content my-account__orders-list">
    <form method="POST" action="<?=$_ENV['URL_HOST'].'customer/customer/POST'?>" id="getlsitCartt">
    <table class="orders-table">
        <thead>
        <tr>
            <th></th>
            <th>Item Name</th>
            <th>Specification</th>
            <th>Unit Price</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        if(count($list) > 0 ) {
            foreach ($list as $key => $value) {
                ?>
                <tr>
                    <td><button type="button" class="btn btn-danger btn-sm delete" data-id="<?=$value["id"]?>"><i class="fa fa-trash"></i></button></td>
                    <td>
                        <img src="<?=$_ENV["URL_HOST"].'/'.$value["img_255x200_home"]?>" alt="" style="width:100px;height:50px;display:inline">
                    <?=$value["item_name"]?></td>
                    <td><?=$value["description"]?></td>
                    <td><?=number_format($value["unit_cost"],2)?></td>
                    <td>
                      <input type="checkbox" class="form-check-input big-check" name="checkoutIDS[]" value="<?=$value["id"]?>">
                    </td>
                </tr>
                <?php
            }
        }
            
        ?>
        </tbody>
    </table>
</div>
<div style="text-align: right;">
<button  type="button" class="btn btn-primary btn-checkouttocartbooking  " name="name_submit" data-aside="cartDrawer">CHECKOUT</button>

</form>
</div>
