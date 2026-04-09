<?php 

    if($details) {
        echo '<input type="hidden"  name="id" value="'.$details["item_id"].'">';
    }

?>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="1" <?=($details) && $details["status"] == 1?'selected':''?> >Active</option>
                <option value="0" <?=($details) && $details["status"] == 0?'selected':''?> >Inactive</option>
            </select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="status">Type</label>
            <select name="item_type" id="item_type" class="form-control">
                <option value="2" <?=($details) && $details["item_type"] == 2?'selected':''?> >SERVICES</option>
            </select>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-sm-12">

        <div class="form-group">
            <label for="brand_id">Service Type</label>
            <select name="brand_id" id="brand_id" class="form-control">
                <option value="">-- Select Service Type --</option>
                <?php 
                    if(count($brand) > 0) {
                        foreach ($brand as $key => $value) {
                            $selected = ($details && $details["brand_id"] == $value["id"]) ? 'selected' : '';
                            echo "<option value=".$value["id"]." ".$selected.">".$value["name"]."</option>";
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
            <label for="item_code">Code <span style="color: red;">*</span></label>
            <input type="text" class="form-control" id="item_code" name="item_code" value="<?=($details)?$details["item_code"]:''?>" placeholder="Item Code" required>
        </div>
    </div>
    <div class="col-sm">
        <div class="form-group">
            <label for="item_name">Name <span style="color: red;">*</span></label>
            <input type="text" class="form-control" id="item_name" name="item_name" value="<?=($details)?$details["item_name"]:''?>" placeholder="Item Name" required>
        </div>
    </div>
</div>




<div class="form-group">
    <label for="item_description">Description</label>
    <input type="text" class="form-control" id="item_description" name="item_description" value="<?=($details)?$details["item_description"]:''?>" placeholder="Item Description">
</div>


<div class="form-group">
    <label for="price">Unit Price</label>
    <input type="number" class="form-control" id="price" name="price" value="<?=($details)?$details["price"]:''?>" placeholder="0.00">
</div>

<!-- Hours, Description, Unit Cost section - Only visible for Massage service type -->
<div id="massage_details_section" style="display: none;">
<div class="row">
    <div class="col-sm-12">

    <div class="table-responsive">
        <table class="table table-hover" id="finance_table_body">
            <thead>
                <tr>
                    <th>
                    <button type="button" class="btn waves-effect waves-light btn-grd-primary btn-sm add_monthly_finance" ><i class="fa fa-plus" ></i></button>
                    </th>
                    <th>No. of Hours</th>
                    <th>Description</th>
                    <th>Unit Cost</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if(isset($list) && count($list) > 0){
                    $i= 0;
                    foreach ($list as $key => $value) {
                        ?>
                        <tr>
                            <td><button type="button" class="btn btn-danger btn-sm remove_row"><i class="fa fa-trash"></i></button></td>
                            <td>
                            <input type="hidden" name="finance[<?=$i?>][primary_id]" value=<?=$value["id"]?>>    
                            <input type="text" class="form-control no_months qtys" name="finance[<?=$i?>][months]" value=<?=$value["months"]?> placeholder="No. of Hours"></td>
                            <td><input type="text" class="form-control " name="finance[<?=$i?>][description]" value="<?=$value["description"]?>" ></td>
                            <td><input type="number" class="form-control amount qtys" name="finance[<?=$i?>][amount]" value=<?=$value["amount"]?> placeholder="Unit Cost" step="0.01"></td>
                        </tr>
                        <?php
                        $i++;
                    }
                } 
            ?>
            
            </tbody>
        </table>
        
    </div>   

    </div>
    
</div>
</div>



<div class="form-group">
    <label for="img_255x200_home">Image Home Page(255x200)</label>
    <input type="file" class="form-control" id="img_255x200_home"  multiple name="img_255x200_home[]" value="<?=($details)?$details["img_255x200_home"]:''?>" placeholder="img_255x200_home">
    <br>;
    <div class="card-group">
    <?php 
    $img_255x200_home = ($details ) ? explode('|', $details["img_255x200_home"] ) :  false ;
    if( ($img_255x200_home) && count($img_255x200_home) > 0) {
        for ($i=0; $i < count($img_255x200_home); $i++) { 
            ?>
            <div class="card" >
                <img class="card-img-top" src="<?=$_ENV['URL_HOST'].$img_255x200_home[$i]?>" style="width: 200px;height:130px">
            </div>
            <?php
        }
    }
    
    ?>
    </div>

</div>


<div class="form-group">
    <label for="img_400x541_shop">Image Shop(400x541)</label>
    <input type="file" class="form-control" id="img_400x541_shop"  multiple name="img_400x541_shop[]" value="<?=($details)?$details["img_400x541_shop"]:''?>" placeholder="img_400x541_shop">
    <br>;
    <div class="card-group">
    <?php 


 
    $img_400x541_shop = ($details ) ? explode('|', $details["img_400x541_shop"] ) :  false ;
    if( ($img_400x541_shop) && count($img_400x541_shop) > 0) {
        for ($i=0; $i < count($img_400x541_shop); $i++) { 
            ?>
            <div class="card" >
                <img class="card-img-top" src="<?=$_ENV['URL_HOST'].$img_400x541_shop[$i]?>" style="width: 200px;height:130px">
            </div>
            <?php
        }
    }
    
    ?>
    </div>
</div>


<div class="form-group">
    <label for="img_700x700_product_details">Image Product Details(700x700)</label>
    <input type="file" class="form-control" id="img_700x700_product_details"  multiple name="img_700x700_product_details[]" value="<?=($details)?$details["img_700x700_product_details"]:''?>" placeholder="img_700x700_product_details">
    <br>;
    <div class="card-group">
    <?php 

    $img_700x700_product_details = ($details ) ? explode('|', $details["img_700x700_product_details"] ) :  false ;
    if( ($img_700x700_product_details) && count($img_700x700_product_details) > 0) {
        for ($i=0; $i < count($img_700x700_product_details); $i++) { 
            ?>
            <div class="card" >
                <img class="card-img-top" src="<?=$_ENV['URL_HOST'].$img_700x700_product_details[$i]?>" style="width: 200px;height:130px">
            </div>
            <?php
        }
    }
    
    ?>

</div>


