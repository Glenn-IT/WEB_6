
const module = `${URL_BASED}component/user-archive/`;
const component = `component/user-archive/`;

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

    main.confirmMessage('warning', 'DATA RESTORE', 'Are you sure you want to Restore this record? ', 'archiveRecord' ,formData )


});


function archiveRecord(formData) {

    var action = module + 'archived';
    

    var request = main.send_ajax(formData, action, 'POST', true);
    request.done(function (data) {

        if(data.status) {
            main.confirmMessage('success', 'Successfully Restored!', 'Are you sure you want to reload this page? ', 'reloadPage' ,'' )

        } else {
            main.alertMessage('danger','Failed to Restored!', '');

        }

    });
  
}