const bannerModule    = `${URL_BASED}component/banner/`;
const bannerComponent = `component/banner/`;

$(document).ready(function () {
  if ($.fn.DataTable) {
    $("#maintable").DataTable({
      language: { emptyTable: "NO RECORD FOUND!" },
      columnDefs: [{ orderable: false, targets: [1, 4, 5] }]
    });
  }
});

/* Open add / edit modal */
$(document).on("click", ".openmodaldetails-modal", function () {
  var formData = new FormData();
  formData.append("action", $(this).data("type"));
  formData.append("id", $(this).data("id") || "");

  var req = main.send_ajax(formData, bannerModule + "source", "POST", true);
  req.done(function (data) {
    main.modalOpen(data.header, data.html, data.button, bannerComponent + data.action);
  });
});

/* Toggle visible / hidden */
$(document).on("click", ".toggle-status", function () {
  var btn = $(this);
  var formData = new FormData();
  formData.append("id", btn.data("id"));

  var req = main.send_ajax(formData, bannerModule + "toggleStatus", "POST", true);
  req.done(function (data) {
    if (data.status) {
      main.alertMessage("success", "Updated!", data.msg);
      setTimeout(function () { location.reload(); }, 1200);
    } else {
      main.alertMessage("danger", "Error", data.msg);
    }
  });
});

/* Delete */
$(document).on("click", ".delete-banner", function () {
  var formData = new FormData();
  formData.append("id", $(this).data("id"));

  main.confirmMessage("warning", "Delete Banner", "Are you sure you want to delete this banner?", "doBannerDelete", formData);
});

function doBannerDelete(formData) {
  var req = main.send_ajax(formData, bannerModule + "delete", "POST", true);
  req.done(function (data) {
    if (data.status) {
      main.confirmMessage("success", "Deleted!", "Reload the page?", "reloadPage", "");
    } else {
      main.alertMessage("danger", "Failed to delete!", "");
    }
  });
}