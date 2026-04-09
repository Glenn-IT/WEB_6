<div class="page-content my-account__orders-list">
    <table class="orders-table">
        <thead>
        <tr>
            <th></th>
            <th>Order No.</th>
            <th>Date</th>
            <th>Time</th>
            <th>Therapist Name</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        if(count($list) > 0 ) {
            foreach ($list as $key => $value) {
                ?>
                <tr>
                    <td>
                        <?php 
                            if($value["val_status"]=="PENDING") {
                                ?>
                                <button class="btn btn-warning btn-sm cancel-appointment-btn me-1" 
                                        data-id="<?=$value["main_order_id"]?>" 
                                        data-order-no="<?=$value["orrder_no"]?>" 
                                        title="Cancel Appointment"
                                        style="margin-right: 5px;">
                                    <i class="fa fa-times"></i> Cancel
                                </button>
                                <?php
                            } else if($value["val_status"]=="CANCELLED") {
                                ?>
                                <span class="badge bg-secondary">Cancelled</span>
                                <?php
                            }
                        ?>
                        <button class="btn btn-info btn-sm openmodaldetails-modal" 
                                data-type="edit" 
                                data-id="<?=$value["main_order_id"]?>" 
                                title="View Details">
                            <i class="fa fa-eye"></i> View
                        </button>
                    </td>
                    <td><?=$value["orrder_no"]?></td>
                    <td><?=$value["date"]?></td>
                    <td><?=$value["time"]?></td>
                    <td><?=$value["th_name"]?></td>
                    <td>
                        <?php 
                        $statusClass = '';
                        $statusText = $value["val_status"];
                        switch($value["val_status"]) {
                            case 'PENDING':
                                $statusClass = 'badge bg-warning text-dark';
                                break;
                            case 'COMPLETED':
                                $statusClass = 'badge bg-success';
                                break;
                            case 'CANCELLED':
                                $statusClass = 'badge bg-danger';
                                break;
                            case 'DECLINED':
                                $statusClass = 'badge bg-secondary';
                                break;
                            default:
                                $statusClass = 'badge bg-light text-dark';
                        }
                        ?>
                        <span class="<?=$statusClass?>"><?=$statusText?></span>
                    </td>
                  
                </tr>
                <?php
            }
        }
            
        ?>
        </tbody>
    </table>
</div>