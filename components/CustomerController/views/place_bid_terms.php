<style>.fc-day-selected {
  background-color: #c1e1c1 !important; /* light green */
  position: relative;
}

.selected-label {
  position: absolute;
  top: 30px;
  right: 25px;
  background: #28a745;
  color: white;
  font-size: 10px;
  padding: 2px 4px;
  border-radius: 3px;
}
.fc-day-disabled {
      background-color: #ffcccc !important;
      cursor: not-allowed;
      pointer-events: none;
      opacity: 0.6;
    }

/* Additional styling for past dates */
.fc-day-past {
      background-color: #f8f9fa !important;
      color: #6c757d !important;
      cursor: not-allowed;
      pointer-events: none;
      opacity: 0.5;
    }

  .time-wrapper {
    display: flex;
    gap: 20px;
    margin-top: 20px;
  }
  .time-col {
    width: 100%;
  }
  .time-col h3 {
    margin-bottom: 15px;
    color: #333;
    font-size: 20px;
  }
  .time-grid {
    display: block;
  }
  .time-grid select {
    width: 100%;
    font-size: 16px;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
    transition: border-color 0.3s ease;
  }
  .time-grid select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
  }
  .time-grid select.selected-time {
    border-color: #28a745;
    background-color: #f8fff8;
  }
  .time-grid select option:disabled {
    color: #721c24 !important;
    background-color: #f8d7da !important;
    font-weight: bold;
  }
  
  /* Styling for fully occupied time slots (5/5 clients) */
  .time-grid select.fully-occupied {
    border-color: #dc3545 !important;
    background-color: #f8d7da !important;
    color: #721c24 !important;
    cursor: not-allowed;
    pointer-events: none;
  }
  
  .time-grid select.fully-occupied option {
    background-color: #f8d7da !important;
    color: #721c24 !important;
  }
  
  /* Style for time slots with limited availability (3-4/5 clients) */
  .time-grid select.limited-availability {
    border-color: #fd7e14 !important;
    background-color: #fff3cd !important;
    color: #856404;
  }
  
  .time-grid select.limited-availability option {
    background-color: #fff3cd !important;
    color: #856404 !important;
  }

  /* Therapist Selection Styles */
  .therapist-option {
    transition: all 0.3s ease !important;
  }
  
  .therapist-option:hover {
    border-color: #007bff !important;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
    transform: translateY(-1px);
  }
  
  .therapist-option input[type="radio"]:checked + div {
    color: #007bff;
  }
  
  .therapist-option input[type="radio"]:checked {
    accent-color: #007bff;
  }
  
  /* Modern browsers with :has() support */
  @supports selector(:has(*)) {
    .therapist-option:has(input[type="radio"]:checked) {
      border-color: #007bff !important;
      background-color: #f8f9ff !important;
      box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
    }
  }

  /* ===== DOWNPAYMENT FEATURE STYLES - COMMENTED OUT FOR FUTURE USE ===== */
  /* Downpayment Section Styles */
  /*
  .qr-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transition: all 0.3s ease;
  }
  
  .qr-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  
  #proceedDownpaymentBtn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
  }
  
  #proceedDownpaymentBtn:hover {
    background: linear-gradient(135deg, #218838 0%, #1ea584 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
  }
  
  .downpayment_amount {
    background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
    font-weight: bold;
    font-size: 1.1em;
    color: #155724;
    border: 2px solid #28a745;
  }
  
  #payment_confirmation:checked + label {
    color: #155724;
    font-weight: bold;
  }
  
  #reference_code:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
  }
  */
  /* ===== END DOWNPAYMENT FEATURE STYLES =====*/

  .wizard-container {max-width:100%; margin:auto; background:#fff; padding:25px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.1);}
  .progressbar {
      display:flex; justify-content:space-between;
      counter-reset: step;
      margin-bottom:30px;
  }
  .progressbar li {
      list-style:none; width:100%; position:relative;
      text-align:center; font-size:14px;
      color:#aaa; counter-increment:step;
  }
  .progressbar li::before {
      content:counter(step);
      width:30px; height:30px; line-height:30px;
      border:2px solid #aaa; display:block;
      text-align:center; margin:0 auto 10px;
      border-radius:50%; background:white;
  }
  .progressbar li::after {
      content:''; position:absolute; width:100%; height:2px;
      background:#aaa; top:15px; left:-50%;
      z-index:-1;
  }
  .progressbar li:first-child::after {content:none;}
  .progressbar li.active {color:#3498db;}
  .progressbar li.active::before {border-color:#3498db;}
  .progressbar li.active + li::after {background:#3498db;}

  .step {display:none;}
  .step.active {display:block;}
  .buttons {margin-top:20px; display:flex; justify-content:space-between;}
  button {
      background:#3498db; color:white; border:none; padding:10px 20px;
      border-radius:4px; cursor:pointer;
  }
  button[disabled] {background:#ccc; cursor:not-allowed;}
</style>



<input type="hidden" name="method" value="checkout">


<input type="hidden" name="user_id" value="<?=isset($_SESSION["user_id"])?$_SESSION["user_id"]:''?>">
<input type="hidden" name="order_no" value="<?=rand(1, 999999);?>">

<?php 

  foreach ($list as $key => $value) {
    echo ' <input type="hidden" name="cartlist[]" value="'.$value["id"].'">';
  }
?>



<div class="wizard-container">
  <ul class="progressbar">
    <li class="active">Schedule Date & Time</li>
    <li>Therapist</li>
    <li>Client Information</li>
    <li>Confirmation</li>
  </ul>

    <!-- Step 1 -->
    <div class="step active">
        <h2>Schedule Date & Time</h2>
        <p style="color: #666; margin-bottom: 15px;">Select your preferred appointment date, number of clients, and time. Past dates are disabled and cannot be selected.</p>
        
        <button type="button" class="btn btn-primary view_calendar "  style="width:100%;margin-bottom:10px">CLICK TO VIEW SCHEDULE</button>
        <div id="calendar" ></div>

        <input type="hidden" id="date" name="date" required >
        <input type="hidden" name="time" required >

        <h2>Number of Client</h2>
        <div class="row">
          <div class="col-md-12">
            <div class="form-floating my-3">
              <select class="form-control" id="no_ofhead" name="no_ofhead" required >
                <option value="1" selected>1 Client</option>
                <option value="2">2 Clients</option>
                <option value="3">3 Clients</option>
                <option value="4">4 Clients</option>
                <option value="5">5 Clients</option>
              </select>
              <label for="no_ofhead">Number of Client</label>
            </div>
          </div>
        </div>

        <span style="font-size: 24px; font-weight: bold; color: #333; display: block; margin: 20px 0 10px 0;">Select Time:</span>
        <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Choose either a morning (8:30 AM - 11:30 AM) or afternoon/evening (1:00 PM - 10:30 PM) time slot. Red highlighted times are fully booked (5/5 clients), orange indicates limited availability (3-4/5 clients).</p>

        <div class="time-wrapper">
          <div class="time-col">
            <h3>Morning (AM)</h3>
            <div id="amSlots" class="time-grid"></div>
          </div>
          <div class="time-col">
            <h3>Afternoon/Evening (PM)</h3>
            <div id="pmSlots" class="time-grid"></div>
          </div>
        </div>
    </div>

    <!-- Step 2 -->
    <div class="step">
      <h2>Select Therapist</h2>
      <p style="color: #666; margin-bottom: 20px;">
        <strong>NOTE:</strong> Based on your selected services, only therapists specializing in those areas are shown below. 
        If you've had an appointment before, please select the professional who previously assisted you. 
        If this is your first time, feel free to choose any available professional.
      </p>

      <?php 
      // Show selected services for transparency
      if($list && count($list) > 0) {
        $services = array_unique(array_column($list, 'service_category'));
        echo '<div style="background: #e7f3ff; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #007bff;">
          <h5 style="color: #0056b3; margin: 0 0 8px 0; font-size: 16px;">
            <i class="fa fa-heart" style="margin-right: 8px;"></i>
            Your Selected Services: ' . implode(', ', $services) . '
          </h5>
          <small style="color: #495057;">Therapists shown are specialized in these service categories</small>
        </div>';
      }
      ?>

      <div class="therapist-selection-container" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h4 style="color: #495057; margin-bottom: 15px; font-size: 18px;">
          <i class="fa fa-user-md" style="margin-right: 8px; color: #007bff;"></i>
          Available Therapists for Your Selected Services
        </h4>
        
        <div class="therapist-options" style="display: grid; gap: 12px;">
          <label class="therapist-option" style="display: flex; align-items: center; padding: 12px; background: white; border: 2px solid #e9ecef; border-radius: 6px; cursor: pointer; transition: all 0.3s ease;">
            <input type="radio" class="tehrapisht" name="therapist_id" value="0" data-val="Any Professional" checked style="margin-right: 12px; transform: scale(1.2);">
            <div>
              <strong style="color: #495057; font-size: 16px;">Any Professional</strong>
              <br>
              <small style="color: #6c757d;">Let us assign the best available therapist for your services</small>
            </div>
          </label>

          <?php 
          if($therapist && count($therapist) > 0) {
            foreach ($therapist as $key => $value) {
              echo '<label class="therapist-option" style="display: flex; align-items: center; padding: 12px; background: white; border: 2px solid #e9ecef; border-radius: 6px; cursor: pointer; transition: all 0.3s ease;">
                <input type="radio" class="tehrapisht" name="therapist_id" value="'.$value["id"].'" data-val="'.$value["name"].'" required style="margin-right: 12px; transform: scale(1.2);">
                <div>
                  <strong style="color: #495057; font-size: 16px;">'.$value["name"].'</strong>
                  <br>
                  <small style="color: #28a745; font-weight: 500;">Specializes in: '.$value["ser_type"].'</small>
                </div>
              </label>';
            }
          } else {
            echo '<div style="text-align: center; padding: 20px; color: #6c757d;">
              <i class="fa fa-info-circle" style="font-size: 24px; margin-bottom: 10px;"></i>
              <p>No specialized therapists available for your selected services. Please contact us for assistance.</p>
            </div>';
          }
          ?>
        </div>
      </div>
    </div>

    <!-- Step 3 -->
    <div class="step">
      <h2>Client Information</h2>

      <!-- Hidden service type passed from the picker -->
      <input type="hidden" name="service_type" id="service_type_field" value="walk-in">

      <div class="row">
        <div class="col-md-6">
          <div class="form-floating my-3">
            <input type="text" class="form-control" id="account_first_name" placeholder="First Name" value="<?=$_SESSION["account_first_name"]?>" disabled>
            <label for="account_first_name">First Name</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating my-3">
            <input type="text" class="form-control" id="account_last_name" placeholder="Last Name" value="<?=$_SESSION["account_last_name"]?>" disabled>
            <label for="account_last_name">Last Name</label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-floating my-3">
            <input type="text" class="form-control" id="account_display_name" placeholder="Display Name" value="<?=$_SESSION["username"]?>" disabled>
            <label for="account_display_name">Username</label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-floating my-3">
            <input type="email" class="form-control" id="email" placeholder="Email Address" value="<?=$_SESSION["email"]?>" readonly>
            <label for="email">Email Address</label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-floating my-3">
            <input type="text" class="form-control" value="<?=$_SESSION["contact_no"]?>" readonly>
            <label for="contact_no">Contact NO.</label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-floating my-3">
            <input type="text" class="form-control" id="gender" value="<?=$_SESSION["gender"]?>" readonly>
            <label for="gender">Gender</label>
          </div>
        </div>
      </div>

      <!-- ===== HOME SERVICE FIELDS ===== -->
      <div id="home-service-fields" style="display:none;">
        <div class="alert alert-info" style="border-left:4px solid #c8956c;background:#fff8f4;border-color:#c8956c;">
          <i class="fa fa-home" style="color:#c8956c;"></i>
          <strong style="color:#c8956c;"> Home Service</strong> — Please provide your home address below.
        </div>
        <div class="form-floating my-3">
          <input type="text" class="form-control" id="billing_address" name="billing_address"
                 placeholder="Complete Home Address" disabled>
          <label for="billing_address">Complete Home Address <span class="text-danger">*</span></label>
        </div>
      </div>

      <!-- ===== HOTEL SERVICE FIELDS ===== -->
      <div id="hotel-service-fields" style="display:none;">
        <div class="alert alert-info" style="border-left:4px solid #c8956c;background:#fff8f4;border-color:#c8956c;">
          <i class="fa fa-building" style="color:#c8956c;"></i>
          <strong style="color:#c8956c;"> Hotel Service</strong> — Please fill in your hotel details below.
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-floating my-3">
              <input type="text" class="form-control" id="hotel_name" name="hotel_name"
                     placeholder="Hotel Name" disabled>
              <label for="hotel_name">Hotel Name <span class="text-danger">*</span></label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-floating my-3">
              <input type="text" class="form-control" id="hotel_address" name="hotel_address"
                     placeholder="Hotel Address" disabled>
              <label for="hotel_address">Hotel Address <span class="text-danger">*</span></label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-floating my-3">
              <input type="text" class="form-control" id="hotel_room" name="hotel_room"
                     placeholder="Room No." disabled>
              <label for="hotel_room">Room Number <span class="text-danger">*</span></label>
            </div>
          </div>
        </div>
      </div>

    </div>


     <!-- Step 4 -->
    <div class="step">
      <h2>Confirmation</h2>
      <p>Review your details and click **Submit**.</p>
      
      <?php 
      // Show filtering summary for transparency
      if($list && count($list) > 0) {
        $services = array_unique(array_column($list, 'service_category'));
        echo '<div style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #28a745;">
          <h6 style="color: #155724; margin: 0 0 8px 0; font-size: 14px;">
            <i class="fa fa-check-circle" style="margin-right: 8px;"></i>
            Smart Therapist Matching Applied
          </h6>
          <small style="color: #495057;">Based on your selected services (' . implode(', ', $services) . '), we filtered and showed only specialized therapists for optimal service quality.</small>
        </div>';
      }
      ?>
      

      <div class="row">
        <div class="col-md-6">
          <div class="form-floating my-3">
            <input type="text" class="form-control" id="schedule_date" disabled>
            <label for="schedule_date">Schedule Date</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating my-3">
            <input type="text" class="form-control" id="schedule_time" disabled>
            <label for="schedule_time">Time</label>
          </div>
        </div>
        <!-- Service type summary row -->
        <div class="col-md-12">
          <div class="form-floating my-3">
            <input type="text" class="form-control" id="confirm_service_type" readonly>
            <label for="confirm_service_type">Service Type</label>
          </div>
        </div>
        <!-- Address summary (shown for home/hotel) -->
        <div class="col-md-12" id="confirm_address_row" style="display:none;">
          <div class="form-floating my-3">
            <input type="text" class="form-control" id="confirm_address" readonly>
            <label for="confirm_address">Address / Location</label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-floating my-3">
            <input type="text" class="form-control" id="Therapist_name" readonly>
            <label for="Therapist_name">Therapist Name</label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-floating my-3">
            <input type="contact_no" class="form-control" id="contact_no"  value="<?=$_SESSION["contact_no"]?>" readonly>
            <label for="contact_no">Contact NO.</label>
          </div>
        </div>

    


       <div class="table-responsive">
         <table id="bookingTable" class="table table-bordered">
          <thead>
            <tr id="headerRow">
              <th>Item</th>
              <!-- dynamic guest columns dito -->
            </tr>
          </thead>
          <tbody id="bodyRows">
            <!-- dynamic rows dito -->
          </tbody>
        </table>
       </div>


          <div class="col-md-12">
          <div class="form-floating my-3">
            <input type="text" class="form-control total_payment" name="total_payment" value="" readonly>
            <label for="text">Total</label>
          </div>
        </div>

        <!-- ===== DOWNPAYMENT FEATURE - COMMENTED OUT FOR FUTURE USE ===== -->
        <!-- Downpayment Section -->
        <!-- <div class="col-md-12" id="downpayment-section" style="display: none;">
          <div class="card mt-4" style="border: 2px solid #007bff; border-radius: 10px;">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">
                <i class="fa fa-credit-card me-2"></i>
                Downpayment Required (50%)
              </h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 text-center">
                  <h6 class="mb-3">Scan QR Code to Pay</h6>
                  <div class="qr-container" style="background: #f8f9fa; padding: 20px; border-radius: 8px; min-height: 250px; display: flex; align-items: center; justify-content: center; border: 2px dashed #ccc;">
                    <div style="text-align: center; color: #6c757d;">
                      <i class="fa fa-qrcode" style="font-size: 48px; margin-bottom: 10px;"></i>
                      <p>QR Code will be displayed here</p>
                      <small>Please contact admin to add QR code image</small>
                    </div>
                  </div>
                  <div class="mt-3">
                    <div class="form-floating">
                      <input type="text" class="form-control downpayment_amount" id="downpayment_amount" readonly>
                      <label for="downpayment_amount">Downpayment Amount (50%)</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <h6 class="mb-3">Payment Verification</h6>
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="reference_code" name="reference_code" placeholder="Enter reference code">
                    <label for="reference_code">Reference Code</label>
                  </div>
                  <div class="alert alert-info">
                    <small>
                      <strong>Instructions:</strong><br>
                      1. Scan the QR code and pay the downpayment amount<br>
                      2. Enter the reference code from your payment receipt<br>
                      3. Check the confirmation box below to proceed
                    </small>
                  </div>
                  <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="payment_confirmation" name="payment_confirmation">
                    <label class="form-check-label" for="payment_confirmation">
                      <strong>I confirm that I have successfully paid the downpayment and entered the correct reference code</strong>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
        <!-- ===== END DOWNPAYMENT FEATURE ===== -->
        
      </div>



    </div>
    <div class="buttons">
      <button type="button" id="prevBtn">Previous</button>
      <button type="button" id="nextBtn">Next</button>
      <!-- ===== DOWNPAYMENT FEATURE - COMMENTED OUT FOR FUTURE USE ===== -->
      <!-- <button type="button" id="proceedDownpaymentBtn" style="display: none;">Proceed to Downpayment</button> -->
      <!-- ===== END DOWNPAYMENT FEATURE ===== -->
      <button type="submit" id="submitBtn" style="display: none;">Submit Booking</button>
    </div>
</div>
<script>

  var itemlist = <?=json_encode($list)?>;
  var selectedTimeValue = ''; // Store the selected time value
  var selectedTimeElement = null; // Store the selected time element

(function(){
 
  
  const steps = document.querySelectorAll('.step');
  const progressItems = document.querySelectorAll('.progressbar li');
  let currentStep = 0;

  function showStep(n) {
    steps.forEach((s,i)=>s.classList.toggle('active', i===n));
    progressItems.forEach((p,i)=>p.classList.toggle('active', i<=n));
    document.getElementById('prevBtn').disabled = n === 0;
    document.getElementById('nextBtn').textContent = n === steps.length-1 ? 'Submit' : 'Next';

    // ===== DOWNPAYMENT FEATURE - COMMENTED OUT FOR FUTURE USE =====
    // Show/hide buttons based on current step and downpayment status
    /*
    if (n === steps.length-1) {
      // On step 4 (confirmation)
      document.getElementById('nextBtn').style.display = 'none';
      
      // Check if downpayment section is shown
      const downpaymentSection = document.getElementById('downpayment-section');
      const paymentConfirmation = document.getElementById('payment_confirmation');
      
      if (downpaymentSection.style.display === 'none') {
        // Show proceed to downpayment button initially
        document.getElementById('proceedDownpaymentBtn').style.display = 'inline-block';
        document.getElementById('submitBtn').style.display = 'none';
      } else if (paymentConfirmation.checked) {
        // Show submit button if payment is confirmed
        document.getElementById('proceedDownpaymentBtn').style.display = 'none';
        document.getElementById('submitBtn').style.display = 'inline-block';
      } else {
        // Hide both buttons if downpayment section is shown but not confirmed
        document.getElementById('proceedDownpaymentBtn').style.display = 'none';
        document.getElementById('submitBtn').style.display = 'none';
      }
    } else {
      // On other steps
      document.getElementById('nextBtn').style.display = 'inline-block';
      document.getElementById('proceedDownpaymentBtn').style.display = 'none';
      document.getElementById('submitBtn').style.display = 'none';
    }
    */
    // ===== END DOWNPAYMENT FEATURE =====

    // Original button logic (restored)
    document.getElementById('nextBtn').hidden = n === steps.length-1;
    document.getElementById('submitBtn').style.display = n === steps.length-1 ? 'inline-block' : 'none';
  }

  function nextPrev(dir) {
    // Simple HTML5 validation
    if (dir === 1) {
      // Special validation for step 1 (Schedule Date & Time)
      if (currentStep === 0) {
        const dateInput = document.getElementById('date');
        const timeInput = document.querySelector('input[name="time"]');
        
        if (!dateInput.value) {
          alert('Please select a date from the calendar.');
          return;
        }
        
        if (!timeInput.value) {
          alert('Please select a time slot.');
          return;
        }
      }
      
      // Special validation for step 3 (Client Information) - service type address
      if (currentStep === 2) {
        var stype = document.getElementById('service_type_field') ? document.getElementById('service_type_field').value : 'walk-in';
        if (stype === 'home') {
          var addr = document.getElementById('billing_address');
          if (!addr || !addr.value.trim()) {
            alert('Please enter your home address.');
            if (addr) addr.focus();
            return;
          }
        } else if (stype === 'hotel') {
          var hName = document.getElementById('hotel_name');
          var hAddr = document.getElementById('hotel_address');
          var hRoom = document.getElementById('hotel_room');
          if (!hName || !hName.value.trim()) { alert('Please enter the hotel name.'); if(hName) hName.focus(); return; }
          if (!hAddr || !hAddr.value.trim()) { alert('Please enter the hotel address.'); if(hAddr) hAddr.focus(); return; }
          if (!hRoom || !hRoom.value.trim()) { alert('Please enter the room number.'); if(hRoom) hRoom.focus(); return; }
        }
      }

      const inputs = steps[currentStep].querySelectorAll('input[required]');
      for (let inp of inputs) {
        // Skip validation for hidden time input since we handle it above
        if (inp.name === 'time') continue;
        if (!inp.reportValidity()) return;
      }
    }
    currentStep += dir;
    
    if (currentStep == (steps.length - 1)) {
        var no_ofhead = $('#no_ofhead').val();
        tableGuest(itemlist,no_ofhead);
        
        // Populate schedule information in confirmation step
        document.getElementById('schedule_date').value = document.getElementById('date').value;
        document.getElementById('schedule_time').value = document.querySelector('input[name="time"]').value;

        // Populate service type + address summary in confirmation step
        var stype = document.getElementById('service_type_field') ? document.getElementById('service_type_field').value : 'walk-in';
        var stypeLabels = {'walk-in':'Walk-in (Visit Spa)', 'home':'Home Service', 'hotel':'Hotel Service'};
        var confirmStypeEl = document.getElementById('confirm_service_type');
        if (confirmStypeEl) confirmStypeEl.value = stypeLabels[stype] || stype;

        var confirmAddrRow = document.getElementById('confirm_address_row');
        var confirmAddr    = document.getElementById('confirm_address');
        if (confirmAddrRow && confirmAddr) {
            if (stype === 'home') {
                var addr = document.getElementById('billing_address') ? document.getElementById('billing_address').value : '';
                confirmAddr.value = addr;
                confirmAddrRow.style.display = addr ? 'block' : 'none';
            } else if (stype === 'hotel') {
                var hName = document.getElementById('hotel_name')    ? document.getElementById('hotel_name').value    : '';
                var hAddr = document.getElementById('hotel_address') ? document.getElementById('hotel_address').value : '';
                var hRoom = document.getElementById('hotel_room')    ? document.getElementById('hotel_room').value    : '';
                var parts = [];
                if (hName) parts.push(hName);
                if (hAddr) parts.push(hAddr);
                if (hRoom) parts.push('Room ' + hRoom);
                confirmAddr.value = parts.join(', ');
                confirmAddrRow.style.display = parts.length ? 'block' : 'none';
            } else {
                confirmAddrRow.style.display = 'none';
            }
        }
    }
    showStep(currentStep);
  }

  document.getElementById('prevBtn').onclick = () => nextPrev(-1);
  document.getElementById('nextBtn').onclick = () => nextPrev(1);

  // Enhanced therapist selection handling
  document.addEventListener('change', function(e) {
    if (e.target.matches('input.tehrapisht')) {
      // Remove active state from all therapist options
      document.querySelectorAll('.therapist-option').forEach(option => {
        option.style.borderColor = '#e9ecef';
        option.style.backgroundColor = 'white';
        option.style.boxShadow = 'none';
      });
      
      // Add active state to selected option
      const selectedOption = e.target.closest('.therapist-option');
      if (selectedOption) {
        selectedOption.style.borderColor = '#007bff';
        selectedOption.style.backgroundColor = '#f8f9ff';
        selectedOption.style.boxShadow = '0 2px 8px rgba(0, 123, 255, 0.2)';
        
        // Update confirmation step with selected therapist
        document.getElementById('Therapist_name').value = e.target.dataset.val;
      }
    }
  });

  // Preserve time selection when number of clients changes
  document.getElementById('no_ofhead').addEventListener('change', function() {
    // If there's a previously selected time, restore it
    if (selectedTimeValue && selectedTimeElement) {
      selectedTimeElement.value = selectedTimeValue;
      selectedTimeElement.classList.add('selected-time');
      document.querySelector('input[name="time"]').value = selectedTimeValue;
    }
  });

  // Listen for time selection changes to store the selected value
  document.addEventListener('change', function(e) {
    if (e.target.matches('#amSlots select, #pmSlots select')) {
      if (e.target.value) {
        // Store the selected time
        selectedTimeValue = e.target.value;
        selectedTimeElement = e.target;
        
        // Clear other time selections
        document.querySelectorAll('#amSlots select, #pmSlots select').forEach(select => {
          if (select !== e.target) {
            select.value = '';
            select.classList.remove('selected-time');
          }
        });
        
        // Add selected class to current selection
        e.target.classList.add('selected-time');
        
        // Update hidden time input
        document.querySelector('input[name="time"]').value = e.target.value;
      }
    }
  });

  // ===== DOWNPAYMENT FEATURE JAVASCRIPT - COMMENTED OUT FOR FUTURE USE =====
  /*
  // Downpayment functionality
  document.getElementById('proceedDownpaymentBtn').onclick = function() {
    // Calculate 50% downpayment
    const totalPayment = parseFloat(document.querySelector('.total_payment').value) || 0;
    const downpaymentAmount = (totalPayment * 0.5).toFixed(2);
    
    // Show downpayment section
    document.getElementById('downpayment-section').style.display = 'block';
    document.getElementById('downpayment_amount').value = '₱' + downpaymentAmount;
    
    // Hide proceed button
    this.style.display = 'none';
    
    // Scroll to downpayment section
    document.getElementById('downpayment-section').scrollIntoView({ 
      behavior: 'smooth',
      block: 'center'
    });
  };

  // Payment confirmation checkbox handler
  document.getElementById('payment_confirmation').addEventListener('change', function() {
    const referenceCode = document.getElementById('reference_code').value.trim();
    
    if (this.checked) {
      if (!referenceCode) {
        alert('Please enter the reference code before confirming payment.');
        this.checked = false;
        document.getElementById('reference_code').focus();
        return;
      }
      
      // Show submit button when confirmed
      document.getElementById('submitBtn').style.display = 'inline-block';
    } else {
      // Hide submit button when unchecked
      document.getElementById('submitBtn').style.display = 'none';
    }
  });

  // Reference code validation
  document.getElementById('reference_code').addEventListener('input', function() {
    const paymentConfirmation = document.getElementById('payment_confirmation');
    
    // If checkbox is checked but reference code is empty, uncheck it
    if (paymentConfirmation.checked && !this.value.trim()) {
      paymentConfirmation.checked = false;
      document.getElementById('submitBtn').style.display = 'none';
    }
  });
  */
  // ===== END DOWNPAYMENT FEATURE JAVASCRIPT =====

})();
</script>

