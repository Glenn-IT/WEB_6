<?php 

    if($details) {
        echo '<input type="hidden"  name="user_id" value="'.$details["user_id"].'">';
    }

?>

<div class="form-group">
    <label for="username">User Name</label>
    <input type="text" class="form-control" id="username" name="username" value="<?=($details)?$details["username"]:''?>" placeholder="User Name">
</div>
<div class="form-group">
    <label for="email">Email</label>
    <input type="text" class="form-control" id="email" name="email" value="<?=($details)?$details["email"]:''?>" placeholder="Email">
</div>
<div class="form-group">
    <label for="contact_no">Contact No.</label>
    <input type="text" class="form-control" id="contact_no" name="contact_no" value="<?=($details)?$details["contact_no"]:''?>" placeholder="Contact No">
</div>
<div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password" value="<?=($details)?$details["password"]:''?>" placeholder="Another input">
</div>
<div class="form-group">
    <label for="user_type">User Role</label>
    <select name="user_type" id="user_type" class="form-control">
        <?php 
            $u = [
                1 => 'Admin',
                2 => 'Employee',
            ];
            foreach ($u as $key => $value) {
                $selected =( ($details) && ($key == $details["user_type"]))?'selected':'';
                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
            }
       
        ?>
      
    </select>
</div>

<div class="form-group">
    <label for="status">Status</label>
    <select name="status" id="status" class="form-control">
    <?php 
            $u = [
                1 => 'Active',
                0 => 'Inactive',
            ];
            foreach ($u as $key => $value) {
                $selected =( ($details) && ($key == $details["status"]))?'selected':'';
                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
            }
       
        ?>
    </select>
</div>