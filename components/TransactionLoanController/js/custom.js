
const module = `${URL_BASED}component/transaction-loan/`;
const component = `component/transaction-loan/`;

$(document).on('click', '.openmodaldetails-modal', function() {

    var action = module + 'source';
    
    var dataObj  = {
        action : $(this).data('type'),
        id : $(this).data('id')
    };

    // Convert the data object into FormData
    var formData = new FormData();
    for (const key in dataObj) {
        if (dataObj.hasOwnProperty(key)) {
            formData.append(key, dataObj[key]);
        }   
    }

    var request = main.send_ajax(formData, action, 'POST', true);
    request.done(function (data) {

        action = component +  data.action;

        main.modalOpen(data.header, data.html,  data.button, action, data.size);


    });
  


});

$(document).on('click', '.delete', function() {


    var dataObj  = {
        id : $(this).data('id')
    };

    // Convert the data object into FormData
    var formData = new FormData();
    for (const key in dataObj) {
        if (dataObj.hasOwnProperty(key)) {
            formData.append(key, dataObj[key]);
        }   
    }

    main.confirmMessage('warning', 'DELETE RECORD', 'Are you sure you want to delete this record? ', 'deleteRecord' ,formData )


});



function deleteRecord(formData) {

    var action = module + 'delete';
    

    var request = main.send_ajax(formData, action, 'POST', true);
    request.done(function (data) {

        if(data.status) {
            main.confirmMessage('success', 'Successfully Deleted!', 'Are you sure you want to reload this page? ', 'reloadPage' ,'' )

        } else {
            main.alertMessage('danger','Failed to Delete!', '');

        }

    });
  
}



$(document).on('click', '.add_monthly_finance', function() {
        var rowCount = $('#finance_table_body tr').length + 1;
        // Define a new row with input fields or placeholders
        const newRow = `
            <tr>
                <td><button type="button" class="btn btn-danger btn-sm remove_row"><i class="fa fa-trash"></i></button></td>
                <td><input type="text" class="form-control " name="finance[`+rowCount+`][reference]" value=''  ></td>
                <td><input type="date" class="form-control " name="finance[`+rowCount+`][date]" value='' ></td>
                <td><input type="number" class="form-control amount" name="finance[`+rowCount+`][amount]" value=0  ></td>
           </tr>
        `;

        // Append the new row to the table body
        $('#finance_table_body').append(newRow);
    });


$(document).on('click', '.remove_row', function() {
    // Remove a row when the delete button is clicked
   $(this).closest('tr').remove();
});

$(document).on('change', '.qtys', function() {
   var parent = $(this).parent().parent();

   var monthly =    (parent.find('.interest').val() / 100) * parent.find('.amount').val()
   var months  = parent.find('.no_months').val();
   var getTotal = (parseFloat(parent.find('.amount').val()) + parseFloat(monthly)) * months ;

   parent.find('.monthly').val(monthly);
   parent.find('.total').val(getTotal); 
});

