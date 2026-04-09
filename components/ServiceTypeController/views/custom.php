<div class="col-xl-12 col-md-12">
    <button class="btn waves-effect waves-light btn-primary openmodaldetails-modal" data-type="add"><i class="fa fa-plus"></i>&nbsp;Add New</button>
    <div class="card table-card">
        <div class="card-header">
            <h5>Service Type</h5>
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
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($list) && count($list) > 0){
                                    foreach ($list as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?=$value["service_id"]?></td>
                                            <td><?=$value["code"]?></td>
                                            <td><?=$value["description"]?></td>
                                            <td>
                                                <button class="btn waves-effect waves-light btn-grd-primary btn-sm openmodaldetails-modal" data-type="edit" data-id="<?=$value["service_id"]?>"><i class="fa fa-edit " ></i></button>
                                                |
                                                <button class="btn waves-effect waves-light btn-grd-danger btn-sm delete" data-id="<?=$value["service_id"]?>"><i class="fa fa-times " ></i></button>
                                            </td>
                                        </tr>
    
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="4"> NO RECORD FOUND!</td>
                                    </tr>

                                    <?php
                                }
                            ?>
                        
                        </tbody>
                    </table>
                   
                </div>   



        </div>
    </div>
</div>
