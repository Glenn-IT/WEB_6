<?php 

    if($details) {
        echo '<input type="hidden"  name="user_id" value="'.$details["user_id"].'">';
    }

?>
<div class="form-group">
    <label for="status">Block No.</label>
    <select name="status" id="status" class="form-control">
        <?php 
            $u = [
                1 => 'ACTIVE',
                0 => 'INACTIVE'
            ];
            foreach ($u as $key => $value) {
                $selected =( ($details) && ($key == $details["status"]))?'selected':'';
                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
            }
       
        ?>
      
    </select>
</div>