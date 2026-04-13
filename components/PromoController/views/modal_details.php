<?php if ($details): ?>
    <input type="hidden" name="id" value="<?= $details['promo_id'] ?>">
<?php endif; ?>

<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <label for="title">Promo Title <span style="color:red;">*</span></label>
            <input type="text" class="form-control" id="title" name="title"
                   value="<?= ($details) ? htmlspecialchars($details['title']) : '' ?>"
                   placeholder="e.g. Summer Body Scrub Promo" required>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="discount_text">Discount Label</label>
            <input type="text" class="form-control" id="discount_text" name="discount_text"
                   value="<?= ($details) ? htmlspecialchars($details['discount_text']) : '' ?>"
                   placeholder="e.g. 20% OFF">
        </div>
    </div>
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="3"
              placeholder="Short description of the promo..."><?= ($details) ? htmlspecialchars($details['description']) : '' ?></textarea>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="link_service_id">Link to Service (optional)</label>
            <select name="link_service_id" id="link_service_id" class="form-control">
                <option value="">— No linked service —</option>
                <?php foreach ($services as $svc): ?>
                    <option value="<?= $svc['item_id'] ?>"
                        <?= ($details && $details['link_service_id'] == $svc['item_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($svc['item_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="text-muted">Clicking the promo card will scroll to this service.</small>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="is_active">Status</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" <?= ($details && $details['is_active'] == 1) ? 'selected' : '' ?>>Visible</option>
                <option value="0" <?= ($details && $details['is_active'] == 0) ? 'selected' : '' ?>>Hidden</option>
            </select>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="image_path">Promo Image</label>
    <input type="file" class="form-control" id="image_path" name="image_path[]" accept="image/*">
    <small class="text-muted">Recommended: 600 × 400 px. Accepted: JPG, PNG, WEBP.</small>

    <?php if ($details && !empty($details['image_path'])): ?>
        <div class="mt-2">
            <p class="mb-1"><strong>Current image:</strong></p>
            <img src="<?= $_ENV['URL_HOST'] . $details['image_path'] ?>"
                 style="max-width:100%;height:120px;object-fit:cover;border-radius:4px;"
                 alt="Current Promo Image">
        </div>
    <?php endif; ?>
</div>
