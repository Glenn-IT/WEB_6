const module = `${URL_BASED}component/therapist/`;
const component = `component/therapist/`;

// Handle form submission with validation
$(document).on("submit", ".modalOpenCustom form", function (e) {
  e.preventDefault();

  // Get the name input value and trim whitespace
  var nameValue = $(this).find('input[name="name"]').val().trim();

  // Check if name is empty or contains only whitespace
  if (nameValue === "" || nameValue.length === 0) {
    // Show error modal
    main.alertMessage(
      "warning",
      "Validation Error",
      "Therapist name cannot be empty or contain only spaces!"
    );
    // Focus on the name input
    $(this).find('input[name="name"]').focus();
    return false;
  }

  // Check if name contains only spaces (additional check)
  if (!/\S/.test(nameValue)) {
    main.alertMessage(
      "warning",
      "Validation Error",
      "Therapist name must contain at least one non-space character!"
    );
    $(this).find('input[name="name"]').focus();
    return false;
  }

  // Update the input value with trimmed value
  $(this).find('input[name="name"]').val(nameValue);

  main.form_ajax(this);
});

$(document).on("click", ".openmodaldetails-modal", function () {
  var action = module + "source";

  var dataObj = {
    action: $(this).data("type"),
    id: $(this).data("id"),
  };

  // Convert the data object into FormData
  var formData = new FormData();
  for (const key in dataObj) {
    if (dataObj.hasOwnProperty(key)) {
      formData.append(key, dataObj[key]);
    }
  }

  var request = main.send_ajax(formData, action, "POST", true);
  request.done(function (data) {
    action = component + data.action;

    main.modalOpen(data.header, data.html, data.button, action);
  });
});

$(document).on("click", ".delete", function () {
  var dataObj = {
    id: $(this).data("id"),
  };

  // Convert the data object into FormData
  var formData = new FormData();
  for (const key in dataObj) {
    if (dataObj.hasOwnProperty(key)) {
      formData.append(key, dataObj[key]);
    }
  }

  main.confirmMessage(
    "warning",
    "DELETE RECORD",
    "Are you sure you want to delete this record? ",
    "deleteRecord",
    formData
  );
});

function deleteRecord(formData) {
  var action = module + "delete";

  var request = main.send_ajax(formData, action, "POST", true);
  request.done(function (data) {
    if (data.status) {
      main.confirmMessage(
        "success",
        "Successfully Deleted!",
        "Are you sure you want to reload this page? ",
        "reloadPage",
        ""
      );
    } else {
      main.alertMessage("danger", "Failed to Delete!", "");
    }
  });
}
