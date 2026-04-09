<input type="hidden" name="payment_id" value="<?=$id?>">
<div class="form-group">
    <label for="reference_no">Reference NO.</label>
    <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?=$details["reference_no"]?>" >
</div>


<div class="form-group">
    <label for="reference_no">Upload Proof Of Payment</label>
    <input type="file" accept="image/*" name="file" onchange="loadFile(event)" class="form-control">
</div>

<img id="output" src="<?=$_ENV['URL_HOST'].$details["proof_of_payment"]?>"/>
<script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
</script>