<?php 

    if($details) {
        echo '<input type="hidden"  name="service_id" value="'.$details["service_id"].'">';
    }

?>

<div class="form-group">
    <label for="code">Code</label>
    <input type="text" class="form-control" id="code" name="code" value="<?=($details)?$details["code"]:''?>" placeholder="Code">
</div>
<div class="form-group">
    <label for="description">Description</label>
    <input type="text" class="form-control" id="description" name="description" value="<?=($details)?$details["description"]:''?>" placeholder="Description">
</div>