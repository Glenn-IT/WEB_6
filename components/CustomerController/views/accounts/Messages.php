<section >

    <div class="row">
      <div class="col-md-12">

        <div class="card" id="chat3" style="border-radius: 15px;">
          <div class="card-body">

            <div class="row">
              <div class="col-md-6 col-lg-5 col-xl-5 mb-4 mb-md-0" style="display: none;">

                <div class="p-3">

                  <div class="input-group rounded mb-3">
                    <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                      aria-describedby="search-addon" />
                    <span class="input-group-text border-0" id="search-addon">
                      <i class="fas fa-search"></i>
                    </span>
                  </div>

                  <div  style="position: relative; height: 400px;overflow: auto;">
                    <ul class="list-unstyled mb-0">

                        <?php 
                            foreach ($list as $key => $value) {
                              ?>
                                <li class="p-2 border-bottom">
                                    <a href="#!" class="d-flex justify-content-between open_chat" data-id="<?=$value["user_id"]?>" data-email="<?=$value["email"]?>" data-time_ago="<?=$value["time_ago"]?>">
                                    <div class="d-flex flex-row">
                                        <div class="pt-1">
                                        <p class="fw-bold mb-0"><?=$value["email"]?></p>
                                        <p class="small text-muted"><?=$value["description"]?></p>
                                        </div>
                                    </div>
                                    <div class="pt-1">
                                        <p class="small text-muted mb-1"><?=$value["time_ago"]?></p>
                                        <?php 
                                            if($value["is_seen"] == 0) {
                                                ?>
                                                <span class="badge bg-success rounded-pill float-end">NEW</span>
                                                <?php
                                            }
                                         ?>
                                    </div>
                                    </a>
                                </li>
                              <?php
                            }
                        ?>

                      
                    </ul>
                  </div>

                </div>

              </div>

              <div class="col-md-6 col-lg-12 col-xl-12">
               
                <div class="text-muted d-flex justify-content-start align-items-center pe-3 pt-3 mt-2">
                    <a href="#!" class="d-flex justify-content-between ">
                        <div class="d-flex flex-row">
                            <div class="pt-1">
                            <p class="fw-bold mb-0 name_user" >SELLER</p>
                            <p class="small text-muted time_ago_user"></p>
                            </div>
                        </div>
                    </a>
                </div>     

                <div class="pt-3 pe-3 getmessage_here"  style="position: relative; height: 400px;overflow: auto;">

                  <!-- Welcome Message - shown when no conversation is selected -->
                  <div class="welcome-message text-center d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
                    <div class="mb-4">
                      <i class="fas fa-comments" style="font-size: 60px; color: #6c757d;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Welcome to CARE MASSAGE AND SPA!</h4>
                    <p class="text-muted mb-2">👋 Hi there! How can I help you today?</p>
                    <p class="text-muted" style="max-width: 400px;">
                      Feel free to ask any questions about our services, products, or anything else. 
                      I'm here to assist you!
                    </p>
                  </div>

                </div>
                            
                <div class="text-muted d-flex justify-content-start align-items-center pe-3 pt-3 mt-2">
                  <input type="text" class="form-control form-control-lg my_message_sent" id="my_message_sent"
                    placeholder="Type your message here..." name="message" data-id="1">
                  <a class="ms-3 sentmessagenow" href="#!"><i class="fas fa-paper-plane" style="font-size: 20px;"></i></a>
                </div>

              </div>
            </div>

          </div>
        </div>

      </div>
    </div>

</section>