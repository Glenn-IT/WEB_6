const promoModule = `${URL_BASED}component/promo/`;
const promoComponent = `component/promo/`;

$(document).ready(function () {
  if ($.fn.DataTable) {
    $("#maintable").DataTable({
      language: { emptyTable: "NO RECORD FOUND!" },
      columnDefs: [{ orderable: false, targets: [1, 6] }]
    });
  }
});

/* Open add / edit modal */
$(document).on("click", ".openmodaldetails-modal", function () {
  var formData = new FormData();
  formData.append("action", $(this).data("type"));
  formData.append("id", $(this).data("id") || "");

  var req = main.send_ajax(formData, promoModule + "source", "POST", true);
  req.done(function (data) {
    main.modalOpen(data.header, data.html, data.button, promoComponent + data.action, data.size || "");
  });
});

/* Toggle visible / hidden */
$(document).on("click", ".toggle-status", function () {
  var btn = $(this);
  var formData = new FormData();
  formData.append("id", btn.data("id"));

  var req = main.send_ajax(formData, promoModule + "toggleStatus", "POST", true);
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
$(document).on("click", ".delete-promo", function () {
  var formData = new FormData();
  formData.append("id", $(this).data("id"));

  main.confirmMessage("warning", "Delete Promo", "Are you sure you want to delete this promo?", "doPromoDelete", formData);
});

function doPromoDelete(formData) {
  var req = main.send_ajax(formData, promoModule + "delete", "POST", true);
  req.done(function (data) {
    if (data.status) {
      main.confirmMessage("success", "Deleted!", "Reload the page?", "reloadPage", "");
    } else {
      main.alertMessage("danger", "Failed to delete!", "");
    }
  });
}