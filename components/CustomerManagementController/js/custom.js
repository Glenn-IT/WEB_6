
const module = `${URL_BASED}component/customer-management/`;
const component = `component/customer-management/`;
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
