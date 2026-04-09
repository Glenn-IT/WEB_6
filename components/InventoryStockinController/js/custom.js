
const module = `${URL_BASED}component/inventory-stockin/`;
const component = `component/inventory-stockin/`;
$(document).ready(function () {
    $('#maintable').DataTable();
});
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

        main.modalOpen(data.header, data.html,  data.button, action);


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

    main.confirmMessage('success', 'APPROVED', 'Are you sure you want to Approved this record? ', 'deleteRecord' ,formData )


});



function deleteRecord(formData) {

    var action = module + 'delete';
    

    var request = main.send_ajax(formData, action, 'POST', true);
    request.done(function (data) {

        if(data.status) {
            main.confirmMessage('success', 'Successfully APPROVED!', 'Are you sure you want to reload this page? ', 'reloadPage' ,'' )

        } else {
            main.alertMessage('danger','Failed to APPROVED!', '');

        }

    });
  
}



$(document).on('click', '.archive', function() {


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

    main.confirmMessage('warning', 'CANCEL', 'Are you sure you want to CANCEL this record? ', 'archiveRecord' ,formData )


});


function archiveRecord(formData) {

    var action = module + 'archived';
    

    var request = main.send_ajax(formData, action, 'POST', true);
    request.done(function (data) {

        if(data.status) {
            main.confirmMessage('success', 'Successfully CANCEL!', 'Are you sure you want to reload this page? ', 'reloadPage' ,'' )

        } else {
            main.alertMessage('danger','Failed to CANCEL!', '');

        }

    });
  
}