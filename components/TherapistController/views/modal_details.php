<?php 

    if($details) {
        echo '<input type="hidden" name="id" value="'.$details["id"].'">';
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
                            $selected = ($details) && $details["service_id"] == $value["id"] ? 'selected' : '';
                            echo "<option value='".$value["id"]."' ".$selected.">".$value["name"]."</option>";
                        }
                    }
                ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name"
                   value="<?= ($details) ? htmlspecialchars($details['name']) : '' ?>"
                   placeholder="Enter therapist name" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="position">Position / Title</label>
            <input type="text" class="form-control" id="position" name="position"
                   value="<?= ($details) ? htmlspecialchars($details['position'] ?? '') : '' ?>"
                   placeholder="e.g. Senior Therapist">
        </div>
    </div>
</div>

<div class="form-group">
    <label for="bio">Short Bio / Description</label>
    <textarea class="form-control" id="bio" name="bio" rows="3"
              placeholder="Brief description about the therapist..."><?= ($details) ? htmlspecialchars($details['bio'] ?? '') : '' ?></textarea>
</div>

<div class="form-group">
    <label>Profile Photo</label>
    <?php if ($details && !empty($details['photo'])): ?>
        <div class="mb-2">
            <img src="<?= $_ENV['URL_HOST'] ?><?= htmlspecialchars($details['photo']) ?>"
                 style="width:80px;height:80px;object-fit:cover;border-radius:50%;border:2px solid #ddd;"
                 alt="Current Photo">
            <small class="text-muted d-block mt-1">Current photo. Upload a new one to replace it.</small>
        </div>
    <?php endif; ?>
    <input type="file" name="photo[]" class="form-control" accept="image/*">
    <small class="text-muted">Recommended: square image (JPG, PNG). Max 2MB.</small>
</div>
