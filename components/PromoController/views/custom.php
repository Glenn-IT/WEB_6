<div class="col-xl-12 col-md-12">
    <button class="btn waves-effect waves-light btn-primary openmodaldetails-modal" data-type="add">
        <i class="fa fa-plus"></i>&nbsp;Add New Promo
    </button>

    <div class="card table-card mt-3">
        <div class="card-header">
            <h5>Promo Management</h5>
            <div class="card-header-right">
                <ul class="list-unstyled card-option">
                    <li><i class="fa fa-wrench open-card-option"></i></li>
                    <li><i class="fa fa-window-maximize full-card"></i></li>
                    <li><i class="fa fa-minus minimize-card"></i></li>
                    <li><i class="fa fa-refresh reload-card"></i></li>
                    <li><i class="fa fa-trash close-card"></i></li>
                </ul>
            </div>
        </div>
        <div class="card-block">
            <div class="table-responsive">
                <table class="table table-hover" id="maintable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Preview</th>
                            <th>Title</th>
                            <th>Discount</th>
                            <th>Linked Service</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($list) && count($list) > 0) {
                            foreach ($list as $key => $value) {
                        ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td>
                                <?php if (!empty($value['image_path'])): ?>
                                    <img src="<?= $_ENV['URL_HOST'] . $value['image_path'] ?>"
                                         style="width:100px;height:60px;object-fit:cover;border-radius:4px;"
                                         alt="Promo">
                                <?php else: ?>
                                    <span class="label label-default">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($value['title']) ?></strong>
                                <?php if (!empty($value['description'])): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars(mb_strimwidth($value['description'], 0, 60, '...')) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($value['discount_text'])): ?>
                                    <span class="label label-warning"><?= htmlspecialchars($value['discount_text']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($value['item_name'])): ?>
                                    <?= htmlspecialchars($value['item_name']) ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($value['is_active'] == 1): ?>
                                    <label class="label label-success">Visible</label>
                                <?php else: ?>
                                    <label class="label label-danger">Hidden</label>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn waves-effect waves-light btn-grd-primary btn-sm openmodaldetails-modal"
                                        data-type="edit" data-id="<?= $value['promo_id'] ?>">
                                    <i class="fa fa-edit"></i>
                                </button>
                                |
                                <button class="btn waves-effect waves-light btn-sm toggle-status
                                        <?= $value['is_active'] == 1 ? 'btn-grd-warning' : 'btn-grd-success' ?>"
                                        data-id="<?= $value['promo_id'] ?>"
                                        title="<?= $value['is_active'] == 1 ? 'Hide Promo' : 'Show Promo' ?>">
                                    <i class="fa <?= $value['is_active'] == 1 ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                </button>
                                |
                                <button class="btn waves-effect waves-light btn-grd-danger btn-sm delete-promo"
                                        data-id="<?= $value['promo_id'] ?>">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="7">NO RECORD FOUND!</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
