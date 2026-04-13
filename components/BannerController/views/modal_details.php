<?php if ($details): ?>
    <input type="hidden" name="id" value="<?= $details['banner_id'] ?>">
<?php endif; ?>

<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="title">Banner Title / Caption</label>
            <input type="text" class="form-control" id="title" name="title"
                   value="<?= ($details) ? htmlspecialchars($details['title']) : '' ?>"
                   placeholder="e.g. Summer Promo">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="sort_order">Sort Order</label>
            <input type="number" class="form-control" id="sort_order" name="sort_order"
                   value="<?= ($details) ? (int)$details['sort_order'] : 0 ?>"
                   placeholder="0" min="0">
            <small class="text-muted">Lower number = appears first.</small>
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
    <label for="image_path">
        Banner Image <?= (!$details) ? '<span style="color:red;">*</span>' : '' ?>
    </label>
    <input type="file" class="form-control" id="image_path" name="image_path[]"
           accept="image/*" <?= (!$details) ? 'required' : '' ?>>
    <small class="text-muted">Recommended size: 1759 × 420 px. Accepted: JPG, PNG, WEBP.</small>

    <?php if ($details && !empty($details['image_path'])): ?>
        <div class="mt-2">
            <p class="mb-1"><strong>Current image:</strong></p>
            <img src="<?= $_ENV['URL_HOST'] . $details['image_path'] ?>"
                 style="max-width:100%;height:120px;object-fit:cover;border-radius:4px;"
                 alt="Current Banner">
        </div>
    <?php endif; ?>
</div>
