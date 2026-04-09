
const module = `${URL_BASED}component/transaction-order/`;
const component = `component/transaction-order/`;


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

$(document).on('click', '.updateORder', function() {


    var dataObj  = {
        id : $(this).data('id'),
        status : $(this).data('status')
    };

    // Convert the data object into FormData
    var formData = new FormData();
    for (const key in dataObj) {
        if (dataObj.hasOwnProperty(key)) {
            formData.append(key, dataObj[key]);
        }   
    }

    main.confirmMessage('info', 'UPDATE RECORD', 'Are you sure you want to '+$(this).data('status')+' this record? ', 'updateRecord' ,formData )


});



function updateRecord(formData) {

    var action = module + 'updateStatus';

    var request = main.send_ajax(formData, action, 'POST', true);
    request.done(function (data) {

        if(data.status) {
            main.confirmMessage('success', 'Successfully Updated!', 'Are you sure you want to reload this page? ', 'reloadPage' ,'' )

        } else {
            main.alertMessage('danger','Failed to Update!', '');

        }

    });
  
}