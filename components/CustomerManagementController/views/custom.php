<div class="col-xl-12 col-md-12">
    <div class="card table-card">
        <div class="card-header">
            <h5>List of Customer</h5>
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
            

         
                <div class="table-responsive p-2">
                    <table class="table table-hover " id="maintable">
                        <thead>
                        <tr>
                            <th>User ID</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Date Registered</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(count($list) > 0){
                                    foreach ($list as $key => $value) {

                                        $status = [
                                            1 => '<label class="label label-success">ACTIVE</label>',
                                            0 => '<label class="label label-danger">INACTIVE</label>',
                                        ];
                                        ?>
                                        <tr>
                                            <td><?=$value["user_id"]?></td>
                                            <td><?=$value["username"]?></td>
                                            <td><?=$value["email"]?></td>
                                            <td><?=$value["created_at"]?></td>
                                            <td><?=$status[$value["status"]]?></td>
                                            <td>
                                                <button class="btn waves-effect waves-light btn-grd-primary btn-sm openmodaldetails-modal" data-type="edit" data-id="<?=$value["user_id"]?>"><i class="fa fa-edit " ></i></button>
                                            </td>
                                        </tr>
    
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7"> NO RECORD FOUND!</td>
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
