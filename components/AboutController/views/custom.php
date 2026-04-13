<div class="col-xl-12 col-md-12">

    <form action="<?= baseUrl('/component/about/afterSubmit') ?>" method="POST" id="about-form">
        <div class="card table-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5>About Page Content</h5>
                <button type="submit" class="btn btn-primary waves-effect waves-light">
                    <i class="fa fa-save"></i>&nbsp;Save Changes
                </button>
            </div>
            <div class="card-block">

                <?php
                $labels = [
                    'main_title'      => 'Page Title',
                    'paragraph_1'     => 'Paragraph 1',
                    'paragraph_2'     => 'Paragraph 2',
                    'paragraph_3'     => 'Paragraph 3',
                    'paragraph_4'     => 'Paragraph 4',
                    'contact_address' => 'Contact Address',
                    'contact_phone'   => 'Contact Phone',
                    'contact_email'   => 'Contact Email',
                    'map_embed'       => 'Google Maps Embed URL',
                ];
                ?>

                <div class="row">
                    <?php foreach ($labels as $key => $label): ?>
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="section_<?= $key ?>"><strong><?= $label ?></strong></label>
                            <?php
                            $val = isset($sections[$key]) ? htmlspecialchars($sections[$key]['content']) : '';
                            $isLong = in_array($key, ['paragraph_1','paragraph_2','paragraph_3','paragraph_4','map_embed']);
                            if ($isLong):
                            ?>
                            <textarea class="form-control" id="section_<?= $key ?>"
                                      name="sections[<?= $key ?>]" rows="4"><?= $val ?></textarea>
                            <?php else: ?>
                            <input type="text" class="form-control" id="section_<?= $key ?>"
                                   name="sections[<?= $key ?>]" value="<?= $val ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <hr>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        <i class="fa fa-save"></i>&nbsp;Save Changes
                    </button>
                </div>

            </div><!-- /.card-block -->
        </div><!-- /.card -->
    </form>

</div>
