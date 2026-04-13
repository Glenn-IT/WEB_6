<style>
  .service-type-card {
    border: 2px solid #e9ecef;
    border-radius: 14px;
    padding: 24px 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s ease;
    background: #fff;
    margin-bottom: 12px;
  }
  .service-type-card:hover {
    border-color: #c8956c;
    box-shadow: 0 6px 20px rgba(200,149,108,.18);
    transform: translateY(-3px);
  }
  .service-type-card.selected {
    border-color: #c8956c;
    background: #fff8f4;
    box-shadow: 0 6px 20px rgba(200,149,108,.22);
  }
  .service-type-card .icon {
    font-size: 2.4rem;
    margin-bottom: 10px;
    color: #c8956c;
  }
  .service-type-card h5 { font-weight: 700; color: #2c3e50; margin-bottom: 4px; }
  .service-type-card p  { font-size: .83rem; color: #6c757d; margin: 0; }
  #btn-proceed-service-type { display: none; margin-top: 8px; width: 100%; }
</style>

<!-- Hidden checkout IDs passed from cart -->
<?php foreach ((array)($checkoutIDS ?? []) as $cid): ?>
<input type="hidden" class="stm-checkout-id" value="<?= htmlspecialchars($cid) ?>">
<?php endforeach; ?>

<div class="p-2">
  <p class="text-center text-muted mb-4" style="font-size:.93rem;">
    How would you like to receive your service?
  </p>

  <div class="row justify-content-center">

    <!-- Walk-in -->
    <div class="col-sm-4">
      <div class="service-type-card" data-type="walk-in">
        <div class="icon"><i class="fa fa-map-marker"></i></div>
        <h5>Walk-in</h5>
        <p>Visit us at our spa location</p>
      </div>
    </div>

    <!-- Home Service -->
    <div class="col-sm-4">
      <div class="service-type-card" data-type="home">
        <div class="icon"><i class="fa fa-home"></i></div>
        <h5>Home Service</h5>
        <p>We come to your home address</p>
      </div>
    </div>

    <!-- Hotel Service -->
    <div class="col-sm-4">
      <div class="service-type-card" data-type="hotel">
        <div class="icon"><i class="fa fa-building"></i></div>
        <h5>Hotel Service</h5>
        <p>We come to your hotel room</p>
      </div>
    </div>

  </div>

  <button type="button" id="btn-proceed-service-type" class="btn btn-primary btn-lg">
    <i class="fa fa-arrow-right"></i>&nbsp;Proceed to Book Appointment
  </button>
</div>
