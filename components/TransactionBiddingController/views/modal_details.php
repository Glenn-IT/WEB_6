 <div class="form-row">
     <div class="form-group col-md-12">
        <label for="orderno">Status:</label>
        <input type="text" class="form-control" id="orderno" placeholder="" value="<?=($details)?$details["val_status"]:''?>" readonly>
    </div>
    <div class="form-group col-md-12">
        <label for="orderno">ORDER NO:</label>
        <input type="text" class="form-control" id="orderno" placeholder="" value="<?=($details)?$details["order_no"]:''?>" readonly>
    </div>

    <div class="form-group col-md-12">
        <label for="orderno">Date Scheduled:</label>
        <input type="text" class="form-control" id="orderno" placeholder="" value="<?=($details)?$details["date"]:''?>" readonly>
    </div>
    <div class="form-group col-md-12">
        <label for="orderno">Time:</label>
        <input type="text" class="form-control" id="orderno" placeholder="" value="<?=($details)?$details["format_time"]:''?>" readonly>
    </div>
</div>


<div class="form-row">
    <div class="form-group col-md-12">
        <label for="orderno">Therapist Name:</label>
        <input type="text" class="form-control" id="orderno" placeholder="" value="<?=($details)?$details["therapistname"]:''?>" readonly>
    </div>

    <div class="form-group col-md-12">
        <label for="orderno">Number of Client:</label>
        <input type="text" class="form-control" id="orderno" placeholder="" value="<?=($details)?$details["no_ofhead"]:''?>" readonly>
    </div>

</div>

<hr>

<div class="table-responsive">

    <table class="table">
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Cost</th>
                <?php 
                    for ($i=1; $i <= $details["no_ofhead"]; $i++) { 
                        echo '<th>Client '.$i.'<th>';
                    }
                ?>
            </tr>
        </thead>

        <tbody>
            <?php 

            $total = 0;
                foreach ($itemlist as $key => $value) {
                    echo '<tr>';
                    echo '<td>'.$value["item_name"].'<br>'.$value["description"].'</td>';
                    echo '<td>'.$value["cost"].'</td>';
                    if(isset($guestcheck[$value["id"]] ) ) {


                        for ($i=1; $i <= $details["no_ofhead"]; $i++) { 
                            $check = '';
                            if(isset($guestcheck[$value["id"]][$i])) {
                                $check = 'checked';

                                $total += $value["cost"];
                            } 
                            echo '<td><input type="checkbox"
                                    data-cost="'.$value["cost"].'"
                                    data-guest="'.$i.'"
                                    class="form-check-input guest-check" name="guest['.$i.'][]" value="'.$value["id"].'" '.$check.' disabled readonly><td>';
                        }
                    } else {
                        for ($i=1; $i <= $details["no_ofhead"]; $i++) { 
                            $check = 'disabled';
                            echo '<td><input type="checkbox"
                                    data-cost="'.$value["cost"].'"
                                    data-guest="'.$i.'"
                                    class="form-check-input guest-check" name="guest['.$i.'][]" value="'.$value["id"].'" '.$check.' ><td>';
                        }
                    }
                   
                    echo '</tr>';

                }
            ?>


        </tbody>
    </table>

</div>


<hr>


 <div class="card table-card">
        <div class="card-header">
            <h5>Total Payment</h5>
        </div>
        <div class="card-body">


        <div class="form-row">
            <div class="form-group col-md-12">
                <input type="text" class="form-control" id="orderno" placeholder="" value="<?=number_format($total,2)?>"  readonly>
            </div>

        </div>
            

    </div>
</div>

    <hr>
<div class="card table-card">
    <div class="card-header">
        <h5>Client Information</h5>
    </div>
    <div class="card-body">


        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="orderno">Name:</label>
                <input type="text" class="form-control" id="orderno" placeholder="" value="<?=($details)?$details["full_name"]:''?>" readonly>
            </div>

            <div class="form-group col-md-12">
                <label for="orderno">Address:</label>
                <input type="text" class="form-control" id="orderno" placeholder="" value="<?=($details)?$details["billing_address"]:''?>" readonly>
            </div>
            <div class="form-group col-md-12">
                <label for="orderno">Contact NO:</label>
                <input type="text" class="form-control" id="orderno" placeholder="" value="<?=($details)?$details["contact_no"]:''?>" readonly>
            </div>

            <div class="form-group col-md-12">
                <label for="orderno">Gender:</label>
                <input type="text" class="form-control" id="orderno" placeholder="" value="<?=($details)?$details["gender"]:''?>" readonly>
            </div>
        </div>
            

    </div>
</div>

<hr>
