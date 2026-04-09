<?php 

    if($details) {
        echo '<input type="hidden"  name="id" value="'.$details["id"].'">';
    }

?>

<div class="form-group">
    <label for="name">Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="name" name="name" value="<?=($details)?$details["name"]:''?>" placeholder="Enter brand name" required>
</div>