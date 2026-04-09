const module = `${URL_BASED}component/item-master/`;
const component = `component/item-master/`;

$(document).ready(function () {
  $("#maintable").DataTable();
});

// Show/Hide massage details section based on service type selection
$(document).on("change", "#brand_id", function () {
  var selectedText = $(this).find("option:selected").text();
  var massageSection = $("#massage_details_section");

  if (selectedText.toLowerCase().includes("massage")) {
    massageSection.show();
  } else {
    massageSection.hide();
    // Clear the table when hiding
    $("#finance_table_body tbody").empty();
  }
});

// Initialize the visibility on page load for edit mode
$(document).on("DOMContentLoaded", function () {
  var selectedText = $("#brand_id").find("option:selected").text();
  var massageSection = $("#massage_details_section");

  if (selectedText.toLowerCase().includes("massage")) {
    massageSection.show();
  }
});

// Validation for duplicate item code and item name
function validateDuplicates(itemCode, itemName, itemId = null) {
  var isValid = true;
  var validationData = {
    item_code: itemCode,
    item_name: itemName,
  };

  if (itemId) {
    validationData.item_id = itemId;
  }

  var formData = new FormData();
  for (const key in validationData) {
    if (validationData.hasOwnProperty(key)) {
      formData.append(key, validationData[key]);
    }
  }

  $.ajax({
    url: module + "validateDuplicate",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    async: false,
    success: function (response) {
      var data = JSON.parse(response);
      if (!data.valid) {
        main.alertMessage("danger", "Validation Error", data.message);
        isValid = false;
      }
    },
    error: function () {
      main.alertMessage("danger", "Error", "Failed to validate data");
      isValid = false;
    },
  });

  return isValid;
}

// Override the form submission to include validation
$(document).on("submit", "#modalForm", function (e) {
  var itemCode = $("#item_code").val();
  var itemName = $("#item_name").val();
  var itemId = $('input[name="id"]').val() || null;

  if (!itemCode || !itemName) {
    e.preventDefault();
    main.alertMessage(
      "danger",
      "Validation Error",
      "Item Code and Item Name are required"
    );
    return false;
  }

  if (!validateDuplicates(itemCode, itemName, itemId)) {
    e.preventDefault();
    return false;
  }
});

// Real-time validation for item code
$(document).on("blur", "#item_code", function () {
  var itemCode = $(this).val();
  var itemId = $('input[name="id"]').val() || null;

  if (itemCode) {
    var validationData = {
      item_code: itemCode,
    };

    if (itemId) {
      validationData.item_id = itemId;
    }

    var formData = new FormData();
    for (const key in validationData) {
      if (validationData.hasOwnProperty(key)) {
        formData.append(key, validationData[key]);
      }
    }

    $.ajax({
      url: module + "validateDuplicate",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        var data = JSON.parse(response);
        if (!data.valid) {
          $("#item_code").addClass("is-invalid");
          if ($("#item_code").next(".invalid-feedback").length === 0) {
            $("#item_code").after(
              '<div class="invalid-feedback">' + data.message + "</div>"
            );
          } else {
            $("#item_code").next(".invalid-feedback").text(data.message);
          }
        } else {
          $("#item_code").removeClass("is-invalid");
          $("#item_code").next(".invalid-feedback").remove();
        }
      },
    });
  }
});

// Real-time validation for item name
$(document).on("blur", "#item_name", function () {
  var itemName = $(this).val();
  var itemId = $('input[name="id"]').val() || null;

  if (itemName) {
    var validationData = {
      item_name: itemName,
    };

    if (itemId) {
      validationData.item_id = itemId;
    }

    var formData = new FormData();
    for (const key in validationData) {
      if (validationData.hasOwnProperty(key)) {
        formData.append(key, validationData[key]);
      }
    }

    $.ajax({
      url: module + "validateDuplicate",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        var data = JSON.parse(response);
        if (!data.valid) {
          $("#item_name").addClass("is-invalid");
          if ($("#item_name").next(".invalid-feedback").length === 0) {
            $("#item_name").after(
              '<div class="invalid-feedback">' + data.message + "</div>"
            );
          } else {
            $("#item_name").next(".invalid-feedback").text(data.message);
          }
        } else {
          $("#item_name").removeClass("is-invalid");
          $("#item_name").next(".invalid-feedback").remove();
        }
      },
    });
  }
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

    main.modalOpen(data.header, data.html, data.button, action, data.size);

    // Check service type visibility after modal opens
    setTimeout(function () {
      var selectedText = $("#brand_id").find("option:selected").text();
      var massageSection = $("#massage_details_section");

      if (selectedText.toLowerCase().includes("massage")) {
        massageSection.show();
      } else {
        massageSection.hide();
      }
    }, 100);
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

$(document).on("click", ".add_monthly_finance", function () {
  var rowCount = $("#finance_table_body tr").length + 1;
  // Define a new row with input fields or placeholders
  const newRow =
    `
            <tr>
                <td><button type="button" class="btn btn-danger btn-sm remove_row"><i class="fa fa-trash"></i></button></td>
                <td><input type="text" class="form-control no_months qtys" name="finance[` +
    rowCount +
    `][months]" value=0 placeholder="No. of Hours"></td>
                <td><input type="text" class="form-control " name="finance[` +
    rowCount +
    `][description]" value=0 ></td>
                <td><input type="number" class="form-control amount qtys" name="finance[` +
    rowCount +
    `][amount]" value=0 placeholder="Unit Cost" step="0.01"></td>
           </tr>
        `;

  // Append the new row to the table body
  $("#finance_table_body").append(newRow);
});

$(document).on("click", ".remove_row", function () {
  // Remove a row when the delete button is clicked
  $(this).closest("tr").remove();
});

// $(document).on('change', '.qtys', function() {
//    var parent = $(this).parent().parent();

//    var monthly =    (parent.find('.interest').val() / 100) * parent.find('.amount').val()
//    var months  = parent.find('.no_months').val();
//    var getTotal = (parseFloat(parent.find('.amount').val()) + parseFloat(monthly)) * months ;

//    parent.find('.monthly').val(monthly);
//    parent.find('.total').val(getTotal);
// });
