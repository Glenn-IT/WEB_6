
<input type="hidden" name="order_id" value=<?=$orderid?> >   
<div class="row">
    <div class="col-sm-12">

    <div class="table-responsive">
        <table class="table table-hover" id="finance_table_body">
            <thead>
                <tr>
                    <th>
                    <button type="button" class="btn waves-effect waves-light btn-grd-primary btn-sm add_monthly_finance" ><i class="fa fa-plus" ></i></button>
                    </th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if(isset($list) && count($list) > 0){
                    $i= 0;
                    foreach ($list as $key => $value) {
                        ?>
                        <tr>
                            <input type="hidden" name="finance[<?=$i?>][primary_id]" value=<?=$value["id"]?> >    
                            <td><button type="button" class="btn btn-danger btn-sm remove_row"><i class="fa fa-trash"></i></button></td>
                            <td><input type="text" class="form-control " name="finance[<?=$i?>][reference]" value=<?=$value["reference"]?> ></td>
                            <td><input type="date" class="form-control " name="finance[<?=$i?>][date]" value=<?=$value["date"]?> ></td>
                            <td><input type="number" class="form-control " name="finance[<?=$i?>][amount]" value=<?=$value["amount"]?> ></td>
                        </tr>
                        <?php
                        $i++;
                    }
                } 
            ?>
            
            </tbody>
        </table>
        
    </div>   

    </div>
    
</div>

