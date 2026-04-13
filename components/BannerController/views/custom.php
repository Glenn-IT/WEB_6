<div class="col-xl-12 col-md-12">
    <button class="btn waves-effect waves-light btn-primary openmodaldetails-modal" data-type="add">
        <i class="fa fa-plus"></i>&nbsp;Add New Banner
    </button>

    <div class="card table-card mt-3">
        <div class="card-header">
            <h5>Banner Management</h5>
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
                            <th>Sort Order</th>
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
                                <img src="<?= $_ENV['URL_HOST'] . $value['image_path'] ?>"
                                     style="width:120px;height:60px;object-fit:cover;border-radius:4px;"
                                     alt="Banner Preview">
                            </td>
                            <td><?= htmlspecialchars($value['title']) ?></td>
                            <td><?= $value['sort_order'] ?></td>
                            <td>
                                <?php if ($value['is_active'] == 1): ?>
                                    <label class="label label-success">Visible</label>
                                <?php else: ?>
                                    <label class="label label-danger">Hidden</label>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn waves-effect waves-light btn-grd-primary btn-sm openmodaldetails-modal"
                                        data-type="edit" data-id="<?= $value['banner_id'] ?>">
                                    <i class="fa fa-edit"></i>
                                </button>
                                |
                                <button class="btn waves-effect waves-light btn-sm toggle-status
                                        <?= $value['is_active'] == 1 ? 'btn-grd-warning' : 'btn-grd-success' ?>"
                                        data-id="<?= $value['banner_id'] ?>"
                                        title="<?= $value['is_active'] == 1 ? 'Hide' : 'Show' ?>">
                                    <i class="fa <?= $value['is_active'] == 1 ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                </button>
                                |
                                <button class="btn waves-effect waves-light btn-grd-danger btn-sm delete-banner"
                                        data-id="<?= $value['banner_id'] ?>">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="6">NO RECORD FOUND!</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
