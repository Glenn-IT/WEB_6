<div class="col-xl-3 col-md-6 report_summary" data-type="page" data-status="PENDING">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-blue"><?=$summary["on_going"]?></h4>
                        <h6 class="text-muted m-b-0">On-Going Booking</h6>
                    </div>
                    <div class="col-4 text-right">
                        <i class="fa fa-line-chart f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-blue">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0"></p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="fa fa-line-chart text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 report_summary" data-type="page" data-status="FAILED">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-red"><?=$summary["failed"]?></h4>
                        <h6 class="text-muted m-b-0">Cancelled Booking</h6>
                    </div>
                    <div class="col-4 text-right">
                        <i class="fa fa-hand-o-down f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-red">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0"></p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="fa fa-line-chart text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 report_summary" data-type="page" data-status="COMPLETED">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-green"><?=$summary["completed"]?></h4>
                        <h6 class="text-muted m-b-0">Completed Booking</h6>
                    </div>
                    <div class="col-4 text-right">
                        <i class="fa fa-check-circle f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-green">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0"></p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="fa fa-line-chart text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 report_summary" data-type="page" data-status="PENDING">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-yellow"><?=$summary["pending"]?></h4>
                        <h6 class="text-muted m-b-0">Pending Booking</h6>
                    </div>
                    <div class="col-4 text-right">
                        <i class="fa fa-clock-o f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-yellow">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0"></p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="fa fa-line-chart text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- task, page, download counter  end --> 

    <div class="col-xl-6 col-md-12">
        <div class="card ">
            <div class="card-header">
                <h5>Total Clients in a Year</h5>
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
                
                <div class="card-block">
                    <div id="morris-extra-area-totalclients"></div>
                </div>
               
            </div>
        </div>
    </div>
    

    <div class="col-xl-6 col-md-12">
        <div class="card ">
            <div class="card-header">
                <h5>Customer Identity</h5>
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
                
                <div class="card-block">
                <div id="donut-example-user-identity"></div>
                </div>
               
            </div>
        </div>
    </div>
   



    <div class="col-xl-12 col-md-12">
        <div class="card ">
            <div class="card-header">
                <h5>Top 5 Services</h5>
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
                
                <div class="card-block">
                <div id="morris-bar-chart-topseling"></div>
                </div>
               
            </div>
        </div>
    </div>
