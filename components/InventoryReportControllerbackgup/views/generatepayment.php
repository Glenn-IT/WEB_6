<?php 

    if($details) {
        echo '<input type="hidden"  name="id" value="'.$details["id"].'">';
    }

?>
<input type="hidden" name="action_type_process"  value="generatePayment" >
<div class="form-group">
    <label for="amount">Amount</label>
    <input type="text" class="form-control" id="amount" name="amount" value="<?=$details["price"]?>" readonly >
</div>

