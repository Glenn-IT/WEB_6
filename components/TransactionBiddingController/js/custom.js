const module = `${URL_BASED}component/transaction-bidding/`;
const component = `component/transaction-bidding/`;
$(document).ready(function () {
  $("#maintable").DataTable({
    order: [[0, "desc"]],
  });
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
  });
});

$(document).on("click", ".updateORder", function () {
  var dataObj = {
    id: $(this).data("id"),
    status: $(this).data("status"),
  };

  // Convert the data object into FormData
  var formData = new FormData();
  for (const key in dataObj) {
    if (dataObj.hasOwnProperty(key)) {
      formData.append(key, dataObj[key]);
    }
  }

  main.confirmMessage(
    "info",
    "UPDATE RECORD",
    "Are you sure you want to " + $(this).data("status") + " this record? ",
    "updateRecord",
    formData
  );
});

function updateRecord(formData) {
  var action = module + "updateStatus";

  var request = main.send_ajax(formData, action, "POST", true);
  request.done(function (data) {
    if (data.status) {
      // Create status update modal content
      var statusText =
        data.newStatus === "PROCESSED"
          ? "processed"
          : data.newStatus === "COMPLETED"
          ? "completed"
          : data.newStatus === "DECLINED"
          ? "declined"
          : data.newStatus.toLowerCase();

      var statusColor =
        data.newStatus === "PROCESSED"
          ? "warning"
          : data.newStatus === "COMPLETED"
          ? "success"
          : data.newStatus === "DECLINED"
          ? "danger"
          : "info";

      var statusIcon =
        data.newStatus === "PROCESSED"
          ? "clock-o"
          : data.newStatus === "COMPLETED"
          ? "check-circle"
          : data.newStatus === "DECLINED"
          ? "times-circle"
          : "info-circle";

      var modalContent = `
                <div class="text-center" style="padding: 20px;">
                    <div class="mb-4">
                        <i class="fa fa-${statusIcon} text-${statusColor}" style="font-size: 64px; margin-bottom: 20px;"></i>
                    </div>
                    <h3 class="mb-4" style="color: #2c3e50;">Status Updated Successfully!</h3>
                    <div class="alert alert-${statusColor}" style="border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                        <div style="font-size: 16px;">
                            <strong>Client:</strong> ${data.clientName}<br>
                            <strong>Order #:</strong> ${data.orderNo}<br>
                            <strong>New Status:</strong> <span class="text-uppercase font-weight-bold">${statusText}</span>
                        </div>
                    </div>
                    <p style="font-size: 16px; color: #34495e; margin-bottom: 0;">
                        The client <strong>${data.clientName}</strong> has been successfully updated to <strong>${statusText}</strong> status.
                    </p>
                </div>
            `;

      var modalButtons = `
                <button type="button" class="btn btn-primary" onclick="reloadPage()">
                    <i class="fa fa-refresh"></i> Reload Page
                </button>
            `;

      // Show the status update modal
      main.modalOpen(
        "🎉 Booking Status Updated",
        modalContent,
        modalButtons,
        "",
        "modal-md"
      );
    } else {
      main.alertMessage("danger", "Failed to Update!", "");
    }
  });
}
