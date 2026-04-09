<?php 

    if($details) {
        echo '<input type="hidden"  name="user_id" value="'.$details["user_id"].'">';
    }

?>

<div class="form-group">
    <label for="username">User Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="username" name="username" value="<?=($details)?$details["username"]:''?>" placeholder="Enter username" required>
</div>
<div class="form-group">
    <label for="email">Email <span class="text-danger">*</span></label>
    <input type="email" class="form-control" id="email" name="email" value="<?=($details)?$details["email"]:''?>" placeholder="Enter email address" required>
</div>
<div class="form-group">
    <label for="contact_no">Contact No. <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="contact_no" name="contact_no" value="<?=($details)?$details["contact_no"]:''?>" placeholder="11 digits (e.g., 09123456789)" maxlength="11" pattern="[0-9]{11}" required>
    <small class="form-text text-muted">Must be exactly 11 digits</small>
</div>
<div class="form-group">
    <label for="password">Password <span class="text-danger">*</span></label>
    <div class="input-group" style="position: relative;">
        <input type="password" class="form-control" id="password" name="password" value="<?=($details)?$details["password"]:''?>" placeholder="Enter password" required style="padding-right: 40px;">
        <span id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
            <i class="fa fa-eye" id="eyeIcon"></i>
        </span>
    </div>
</div>
<div class="form-group">
    <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
    <div class="input-group" style="position: relative;">
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="<?=($details)?$details["password"]:''?>" placeholder="Confirm password" required style="padding-right: 40px;">
        <span id="toggleConfirmPassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
            <i class="fa fa-eye" id="confirmEyeIcon"></i>
        </span>
    </div>
    <small class="text-muted">Passwords must match</small>
</div>
<div class="form-group">
    <label for="security_question">Security Question <span class="text-danger">*</span></label>
    <select name="security_question" id="security_question" class="form-control" required>
        <option value="">Select a Security Question *</option>
        <option value="What was the name of your first pet?" <?=($details && $details["security_question"] == "What was the name of your first pet?") ? 'selected' : ''?>>What was the name of your first pet?</option>
        <option value="What is your mother's maiden name?" <?=($details && $details["security_question"] == "What is your mother's maiden name?") ? 'selected' : ''?>>What is your mother's maiden name?</option>
        <option value="What was the name of your elementary school?" <?=($details && $details["security_question"] == "What was the name of your elementary school?") ? 'selected' : ''?>>What was the name of your elementary school?</option>
        <option value="What is your favorite food?" <?=($details && $details["security_question"] == "What is your favorite food?") ? 'selected' : ''?>>What is your favorite food?</option>
        <option value="In what city were you born?" <?=($details && $details["security_question"] == "In what city were you born?") ? 'selected' : ''?>>In what city were you born?</option>
    </select>
</div>
<div class="form-group">
    <label for="security_answer">Security Answer <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="security_answer" name="security_answer" value="<?=($details)?$details["security_answer"]:''?>" placeholder="Your answer" required>
    <small class="form-text text-muted">Minimum 2 characters</small>
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