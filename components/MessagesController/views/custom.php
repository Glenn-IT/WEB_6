<div class="col-xl-12 col-md-12">
    <div class="card table-card">
        <div class="card-header">
            <h5>Messages</h5>
            <div class="card-header-right">
                <ul class="list-unstyled card-option">
                    <li><i class="fa fa fa-wrench open-card-option"></i></li>
                    <li><i class="fa fa-window-maximize full-card"></i></li>
                    <li><i class="fa fa-minus minimize-card"></i></li>
                    <li><i class="fa fa-refresh reload-card"></i></li>
                    <li><i class="fa fa-trash close-card"></i></li>
                </ul>
            </div>
        </div>
        <div class="card-block">
            
            <section >

                <div class="row">
                <div class="col-md-12">

                    <div class="card" id="chat3" style="border-radius: 15px;">
                    <div class="card-body">

                        <div class="row">
                        <div class="col-md-6 col-lg-5 col-xl-5 mb-4 mb-md-0">

                            <div class="p-3">

                            <div class="input-group rounded mb-3">
                                <input type="search" id="searchMessages" class="form-control rounded" placeholder="Search by email address" aria-label="Search"
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
                                            <li class="p-2 border-bottom message-item" 
                                                data-email="<?=strtolower($value["email"])?>" 
                                                data-description="<?=strtolower(strip_tags($value["description"] ?? ''))?>"
                                                data-datetime="<?=$value["datetime"] ?? ''?>"
                                                data-is-seen="<?=$value["is_seen"] ?? ''?>">
                                                <a href="#!" class="d-flex justify-content-between open_chat" data-id="<?=$value["user_id"]?>" data-email="<?=$value["email"]?>" data-time_ago="<?=$value["time_ago"] ?? ''?>">
                                                <div class="d-flex flex-row">
                                                    <div class="pt-1">
                                                    <p class="fw-bold mb-0"><?=$value["email"]?></p>
                                                    <p class="small text-muted"><?=$value["description"] ?? ''?></p>
                                                    </div>
                                                </div>
                                                <div class="pt-1">
                                                    <p class="small text-muted mb-1"><?=$value["time_ago"] ?? ''?></p>
                                                    <p class="small text-muted mb-1" style="font-size: 10px;"><?=!empty($value["datetime"]) ? date('M d, Y h:i A', strtotime($value["datetime"])) : '—'?></p>
                                                    <?php 
                                                        if(isset($value["is_seen"]) && $value["is_seen"] == 0) {
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

                        <div class="col-md-6 col-lg-7 col-xl-7">
                        
                            <div class="text-muted d-flex justify-content-start align-items-center pe-3 pt-3 mt-2">
                                <a href="#!" class="d-flex justify-content-between ">
                                    <div class="d-flex flex-row">
                                        <div class="pt-1">
                                        <p class="fw-bold mb-0 name_user" ></p>
                                        <p class="small text-muted time_ago_user"></p>
                                        </div>
                                    </div>
                                </a>
                            </div>     

                            <div class="pt-3 pe-3 getmessage_here"  style="position: relative; height: 400px;overflow: auto;">

                            

                            </div>
                                        
                            <div class="text-muted d-flex justify-content-start align-items-center pe-3 pt-3 mt-2">
                            <input type="text" class="form-control form-control-lg my_message_sent" id="my_message_sent"
                                placeholder="Type message" name="message" data-id="">
                            <a class="ms-3 sentmessagenow" href="#!"><i class="fas fa-paper-plane" style="font-size: 20px;"></i></a>
                            </div>

                        </div>
                        </div>

                    </div>
                    </div>

                </div>
                </div>

            </section>


        </div>
    </div>
</div>
