<main>
    <div class="mb-5 pb-4"></div>
    <section class="container mw-930 lh-30">
      <h2 class="section-title text-uppercase fw-bold mb-5">CHECKOUT PROCESS</h2>
      <h6 >Once you’ve won an auction or decided to purchase a car directly, follow these steps to complete your order:</h6>
      <div id="faq_accordion" class="faq-accordion accordion mb-5 mt-5">



        <?php 

          $faq = [
              [
                "title" => 'Review Order Details',
                "content"=> 'Go to your cart or the auction you’ve won and double-check the details of the car, including the agreed-upon price, payment terms (down payment or full payment), and any other important information.',
              ],
              [
                "title" => 'Agree on Payment Terms with the Seller',
                "content"=> "Before proceeding, communicate with the seller to confirm the payment terms, whether you’ll pay the full amount or just a down payment. You can discuss this directly via the platform’s messaging system.",
              ],
              [
                "title" => 'Proceed to Checkout',
                "content"=> "Once you’ve agreed on the payment terms with the seller, click Proceed to Checkout. This will allow the seller to generate a payment link based on the agreed amount.",
              ],
              [
                "title" => 'Complete Your Payment',
                "content"=> "Follow the payment link to make your payment. Choose your preferred method and complete the transaction.",
              ],
              [
                "title" => 'Screenshot Your Payment',
                "content"=> "After you’ve made the payment, take a screenshot of the transaction confirmation as proof of payment.",
              ],
              [
                "title" => 'Submit Payment Proof',
                "content"=> "Navigate to My Profile and go to the Orders section. Find the order you just checked out and click on Upload Payment Proof. Upload the screenshot of your payment confirmation.",
              ],
              [
                "title" => 'Order Processing',
                "content"=> "Once the seller verifies your payment, they will begin processing your order and preparing the car.",
              ],
            ];

            $i = 0;
            foreach ($faq as $key => $value) {
              ?>
  
              <div class="accordion-item">
                <h5 class="accordion-header" id="faq-accordion-heading-<?=$i?>">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-accordion-collapse-<?=$i?>" aria-expanded="false" aria-controls="faq-accordion-collapse-<?=$i?>">
                    <?=$value["title"]?>
                    <svg class="accordion-button__icon" viewBox="0 0 14 14"><g aria-hidden="true" stroke="none" fill-rule="evenodd"><path class="svg-path-vertical" d="M14,6 L14,8 L0,8 L0,6 L14,6"></path><path class="svg-path-horizontal" d="M14,6 L14,8 L0,8 L0,6 L14,6"></path></g></svg>
                  </button>
                </h5>
                <div id="faq-accordion-collapse-<?=$i?>" class="accordion-collapse collapse" aria-labelledby="faq-accordion-heading-<?=$i?>" data-bs-parent="#faq_accordion">
                  <div class="accordion-body">
                    <p><?=$value["content"]?></p>
                  </div>
                </div>
              </div><!-- /.accordion-item -->


              <?php
              $i++;
            }
            
        
        ?>



      </div>

      <h6>By following these steps, you ensure a smooth and secure checkout process, allowing the seller to process your order once payment is verified.
      </h6>

    </section>
  </main>