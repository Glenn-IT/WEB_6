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
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="item_type">Item type</label>
            <select name="item_type" id="item_type" class="form-control">
                <option value="0">Sell</option>
                <option value="1">Bid</option>
            </select>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="item_code">Item Code</label>
            <input type="text" class="form-control" id="item_code" name="item_code" value="<?=($details)?$details["item_code"]:''?>" placeholder="Item Code">
        </div>
    </div>
    <div class="col-sm">
        <div class="form-group">
            <label for="item_name">Item Name</label>
            <input type="text" class="form-control" id="item_name" name="item_name" value="<?=($details)?$details["item_name"]:''?>" placeholder="Item Name">
        </div>
    </div>
</div>




<div class="form-group">
    <label for="item_description">Item Description</label>
    <input type="text" class="form-control" id="item_description" name="item_description" value="<?=($details)?$details["item_description"]:''?>" placeholder="Item Description">
</div>


<div class="form-group">
    <label for="price">Unit Price</label>
    <input type="number" class="form-control" id="price" name="price" value="<?=($details)?$details["price"]:''?>" placeholder="0.00">
</div>



<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" id="category" class="form-control">
                <?php 
                    if(count($category) > 0) {
                        foreach ($category as $key => $value) {
                            echo "<option value=".$value["name"]." >".$value["name"]."</option>";
                        }
                    }
                ?>

            </select>
        </div>
    </div>
    <div class="col-sm-3">

        <div class="form-group">
            <label for="brand_id">Brand</label>
            <select name="brand_id" id="brand_id" class="form-control">
                <?php 
                    if(count($brand) > 0) {
                        foreach ($brand as $key => $value) {
                            echo "<option value=".$value["id"]." >".$value["name"]."</option>";
                        }
                    }
                ?>

            </select>
        </div>

    </div>

    <div class="col-sm-3">

        <div class="form-group">
            <label for="sizes">Sizes</label>
            <select name="sizes" id="sizes" class="form-control">
                <?php 
                    if(count($size) > 0) {
                        foreach ($size as $key => $value) {
                            echo "<option value=".$value["name"]." >".$value["name"]."</option>";
                        }
                    }
                ?>

            </select>
        </div>

    </div>

    <div class="col-sm-3">

        
        <div class="form-group">
            <label for="color">Color</label>
            <select name="color" id="color" class="form-control">
                <?php 
                    if(count($colors) > 0) {
                        foreach ($colors as $key => $value) {
                            echo "<option value=".$value["name"]." >".$value["name"]."</option>";
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
            <label for="tags">Tags</label>
            <input type="text" class="form-control" id="tags" name="tags" value="<?=($details)?$details["tags"]:''?>" placeholder="Tags">
        </div>
    </div>
    <div class="col-sm">
        <div class="form-group">
            <label for="Weight">Weight</label>
            <input type="text" class="form-control" id="Weight" name="Weight" value="<?=($details)?$details["Weight"]:''?>" placeholder="Weight">
        </div>
    </div>
</div>



<div class="form-group">
    <label for="Dimensions">Dimensions</label>
    <input type="text" class="form-control" id="Dimensions" name="Dimensions" value="<?=($details)?$details["Dimensions"]:''?>" placeholder="Dimensions">
</div>

<div class="form-group">
    <label for="Storage">Storage</label>
    <input type="text" class="form-control" id="Storage" name="Storage" value="<?=($details)?$details["Storage"]:''?>" placeholder="Storage">
</div>


<div class="form-group">
    <label for="img_255x200_home">Image Home Page(255x200)</label>
    <input type="file" class="form-control" id="img_255x200_home" required multiple name="img_255x200_home[]" value="<?=($details)?$details["img_255x200_home"]:''?>" placeholder="img_255x200_home">

</div>


<div class="form-group">
    <label for="img_400x541_shop">Image Shop(400x541)</label>
    <input type="file" class="form-control" id="img_400x541_shop" required multiple name="img_400x541_shop[]" value="<?=($details)?$details["img_400x541_shop"]:''?>" placeholder="img_400x541_shop">

</div>


<div class="form-group">
    <label for="img_120x120_cart">Image Cart(120x120)</label>
    <input type="file" class="form-control" id="img_120x120_cart" required multiple name="img_120x120_cart[]" value="<?=($details)?$details["img_120x120_cart"]:''?>" placeholder="img_120x120_cart">
</div>

<div class="form-group">
    <label for="img_700x700_product_details">Image Product Details(700x700)</label>
    <input type="file" class="form-control" id="img_700x700_product_details" required multiple name="img_700x700_product_details[]" value="<?=($details)?$details["img_700x700_product_details"]:''?>" placeholder="img_700x700_product_details">
</div>


