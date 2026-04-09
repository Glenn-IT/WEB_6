<?php 

    if($details) {
        echo '<input type="hidden"  name="id" value="'.$details["id"].'">';
    }

?>
<div class="form-group">
    <label for="item_id">Product Name</label>
    <select name="item_id" id="item_id" class="form-control">
        <?php 
            foreach ($listofitems as $key => $value) {
                $selected =( ($details) && ($value["item_id"] == $details["item_id"]))?'selected':'';
                echo '<option value="'.$value["item_id"].'" '.$selected.'>'.$value["item_name"].'</option>';
            }
       
        ?>
      
    </select>
</div>

<div class="form-group">
    <label for="quantity">Quantity</label>
    <input type="nunber" class="form-control" id="quantity" name="quantity" value="<?=($details)?$details["quantity"]:''?>" placeholder="">
</div>
