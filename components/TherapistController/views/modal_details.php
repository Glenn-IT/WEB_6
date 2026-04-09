<?php 

    if($details) {
        echo '<input type="hidden"  name="id" value="'.$details["id"].'">';
    }

?>

<div class="row">
    <div class="col-sm-12">

        <div class="form-group">
            <label for="service_id">Service Type</label>
            <select name="service_id" id="service_id" class="form-control">
                <?php 
                    if(count($brand) > 0) {
                        foreach ($brand as $key => $value) {
                            $selected = ($details) && $details["service_id"]== $value["id"]?'selected':'';
                            echo "<option value=".$value["id"]." ".$selected." >".$value["name"]."</option>";
                        }
                    }
                ?>

            </select>
        </div>

    </div>

    
</div>

<div class="form-group">
    <label for="name">Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="name" name="name" value="<?=($details)?$details["name"]:''?>" placeholder="Enter therapist name" required>
</div>