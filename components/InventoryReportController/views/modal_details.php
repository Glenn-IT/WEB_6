<div class="form-group">
    <label for="name">Amount</label>
    <input type="text" class="form-control" value="<?=($details)?$details["amount"]:''?>"  readonly>
</div>

<div class="form-group">
    <label for="reference_no">Reference No.</label>
    <input type="text" class="form-control" value="<?=($details)?$details["reference_no"]:''?>" readonly>
</div>

<div class="form-group">
    <label for="checkoutURL">Check URL</label>
    <input type="text" class="form-control" value="<?=($details)?$details["checkoutURL"]:''?>" readonly>
</div>

<div class="form-group">
    <label for="name">Proof Of Payment(Image)</label>
</div>


<input type="hidden" name="payment_id" value="<?=$id?>">
<div class="form-group">
    <label for="reference_no">Reference NO.</label>
    <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?=$details["reference_no"]?>" >
</div>


<img id="output" src="<?=$_ENV['URL_HOST'].$details["proof_of_payment"]?>" style="width:400px"/>
