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

    <?php
        $stype = !empty($details["service_type"]) ? $details["service_type"] : 'walk-in';
        $stype_labels = ['walk-in' => 'Walk-in', 'home' => 'Home Service', 'hotel' => 'Hotel Service'];
        $stype_colors = ['walk-in' => '#28a745', 'home' => '#007bff', 'hotel' => '#6f42c1'];
        $stype_label  = $stype_labels[$stype]  ?? ucfirst($stype);
        $stype_color  = $stype_colors[$stype]  ?? '#6c757d';
    ?>
    <div class="form-group col-md-12">
        <label>Service Type:</label><br>
        <span style="background:<?=$stype_color?>;color:#fff;padding:4px 14px;border-radius:5px;font-size:14px;font-weight:600;">
            <?=htmlspecialchars($stype_label)?>
        </span>
    </div>

    <?php if ($stype === 'home'): ?>
    <div class="form-group col-md-12">
        <label>Home Address:</label>
        <textarea class="form-control" rows="2" readonly><?=htmlspecialchars($details["billing_address"] ?? '')?></textarea>
    </div>
    <?php elseif ($stype === 'hotel'): ?>
    <div class="form-group col-md-6">
        <label>Hotel Name:</label>
        <input type="text" class="form-control" value="<?=htmlspecialchars($details["hotel_name"] ?? '')?>" readonly>
    </div>
    <div class="form-group col-md-6">
        <label>Room No.:</label>
        <input type="text" class="form-control" value="<?=htmlspecialchars($details["hotel_room"] ?? '')?>" readonly>
    </div>
    <?php endif; ?>
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

            <?php if ($stype === 'home'): ?>
            <div class="form-group col-md-12">
                <label>Home Address:</label>
                <textarea class="form-control" rows="2" readonly><?=htmlspecialchars($details["billing_address"] ?? '')?></textarea>
            </div>
            <?php elseif ($stype === 'hotel'): ?>
            <div class="form-group col-md-6">
                <label>Hotel Name:</label>
                <input type="text" class="form-control" value="<?=htmlspecialchars($details["hotel_name"] ?? '')?>" readonly>
            </div>
            <div class="form-group col-md-6">
                <label>Room No.:</label>
                <input type="text" class="form-control" value="<?=htmlspecialchars($details["hotel_room"] ?? '')?>" readonly>
            </div>
            <?php else: ?>
            <div class="form-group col-md-12">
                <label>Address:</label>
                <input type="text" class="form-control" value="In-store (Walk-in)" readonly>
            </div>
            <?php endif; ?>

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
