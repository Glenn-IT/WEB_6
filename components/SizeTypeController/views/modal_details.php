<?php 

    if($details) {
        echo '<input type="hidden"  name="id" value="'.$details["id"].'">';
    }

?>

<div class="form-group">
    <label for="name">Size</label>
    <input type="text" class="form-control" id="name" name="name" value="<?=($details)?$details["name"]:''?>" placeholder="name">
</div>