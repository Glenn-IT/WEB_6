<div class="col-xl-12 col-md-12">
    <button class="btn waves-effect waves-light btn-primary openmodaldetails-modal" data-type="add">
        <i class="fa fa-plus"></i>&nbsp;Add Therapist
    </button>
    <div class="card table-card">
        <div class="card-header">
            <h5>Therapists / Staff</h5>
            <div class="card-header-right">
                <ul class="list-unstyled card-option">
                    <li><i class="fa fa fa-wrench open-card-option"></i></li>
                    <li><i class="fa fa-window-maximize full-card"></i></li>
                    <li><i class="fa fa-minus minimize-card"></i></li>
                    <li><i class="fa fa-refresh reload-card"></i></li>
                    <li><i class="fa fa-trash close-card"></i></li>
                </ul>
            </div>
        </div>
        <div class="card-block">
            <div class="table-responsive">
                <table id="maintable" class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Service Type</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($list) && count($list) > 0){
                            foreach ($list as $key => $value) {
                        ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td>
                                <?php if (!empty($value['photo'])): ?>
                                <img src="<?= $_ENV['URL_HOST'] ?><?= htmlspecialchars($value['photo']) ?>"
                                     style="width:50px;height:50px;object-fit:cover;border-radius:50%;"
                                     alt="<?= htmlspecialchars($value['name']) ?>">
                                <?php else: ?>
                                <div style="width:50px;height:50px;border-radius:50%;background:#e0e0e0;display:flex;align-items:center;justify-content:center;">
                                    <i class="fa fa-user" style="color:#999;font-size:20px;"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($value['name']) ?></td>
                            <td><?= htmlspecialchars($value['position'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($value['ser_type'] ?? '—') ?></td>
                            <td>
                                <button class="btn waves-effect waves-light btn-grd-primary btn-sm openmodaldetails-modal"
                                        data-type="edit" data-id="<?= $value['id'] ?>">
                                    <i class="fa fa-edit"></i>
                                </button>
                                |
                                <button class="btn waves-effect waves-light btn-grd-danger btn-sm delete"
                                        data-id="<?= $value['id'] ?>">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                        <?php
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Team / Group Photo Upload -->
    <div class="card">
        <div class="card-header">
            <h5><i class="fa fa-users"></i>&nbsp;Team Group Photo</h5>
        </div>
        <div class="card-block">
            <div class="row align-items-center">
                <div class="col-md-4 text-center mb-3">
                    <?php if (!empty($team_photo)): ?>
                        <img src="<?= $_ENV['URL_HOST'] ?><?= htmlspecialchars($team_photo['image_path']) ?>"
                             class="img-fluid rounded shadow-sm"
                             style="max-height:220px;object-fit:cover;"
                             alt="Team Photo">
                        <p class="text-muted mt-2 small"><?= htmlspecialchars($team_photo['caption'] ?? '') ?></p>
                    <?php else: ?>
                        <div class="text-muted p-4" style="border:2px dashed #ccc;border-radius:8px;">
                            <i class="fa fa-image fa-3x mb-2"></i><br>No team photo uploaded yet.
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <form action="<?= baseUrl('/component/therapist/uploadTeamPhoto') ?>"
                          method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label><strong>Upload New Team / Group Photo</strong></label>
                            <input type="file" name="team_photo[]" class="form-control" accept="image/*" required>
                            <small class="text-muted">Uploading a new photo will replace the current one.</small>
                        </div>
                        <div class="form-group">
                            <label>Caption <small class="text-muted">(optional)</small></label>
                            <input type="text" name="caption" class="form-control"
                                   placeholder="e.g. Our Dedicated Team"
                                   value="<?= !empty($team_photo) ? htmlspecialchars($team_photo['caption']) : 'Our Team' ?>">
                        </div>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            <i class="fa fa-upload"></i>&nbsp;Upload Photo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
