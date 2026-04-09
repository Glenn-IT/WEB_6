<main>
    <div class="mb-5 pb-4"></div>
    <section class="container mw-930 lh-30">
      <h2 class="section-title text-uppercase fw-bold mb-5">FREQUENTLY ASKED QUESTIONS</h2>
      <div id="faq_accordion" class="faq-accordion accordion mb-5">



        <?php 

          $faq = [
              [
                "title" => 'Do I need to register to place a bid and buy a car?',
                "content"=> 'Yes, you need to create an account to place a bid and purchase a car. Registration ensures secure transactions, allows you to track your bids, and provides access to your order history and payment details.',
              ],
              [
                "title" => 'How do I start bidding on a car?',
                "content"=> "Browse through the available cars on the \"Ongoing Bid\" section, and choose the one you're interested in. On the car’s listing page, you will find the \"Place Bid\" option. Enter your bid amount and submit it. You can adjust your bid any time before the auction ends",
              ],
              [
                "title" => 'Can I increase my bid?',
                "content"=> "Yes, you can place a new bid as long as the auction is still live. Your new bid must be higher than the previous one and the current highest bid amount.",
              ],
              [
                "title" => 'What happens if my bid wins?',
                "content"=> "If your bid is the highest when the auction ends, you will be notified and prompted to proceed with the transaction. Then click here to read the <a href='".$_ENV["URL_HOST"]."customer/customer/index?page=faq_checkout' style='color:blue;text-decoration: underline;' >CHECKOUT PROCESS</a>.",
              ],

              [
                "title" => 'How can I buy a car?',
                "content"=> "To process an order, start by initiating communication with the seller. Go to your profile and navigate to Messages or choose the car you're interested in and click Inquire Now. This will direct you to the chat feature, where you can discuss all the details of the car with the seller. Confirm important information such as the car’s price, specifications, and any other relevant details. If you’re not ready to purchase immediately, you can add the car to your cart to save it for later, allowing you to return to it when you're ready. However, if you're ready to proceed with the purchase, go to your cart and complete the checkout process. Then click here to read the <a href='".$_ENV["URL_HOST"]."customer/customer/index?page=faq_checkout' style='color:blue;text-decoration: underline;' >CHECKOUT PROCESS</a>.",
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
    </section>
  </main>