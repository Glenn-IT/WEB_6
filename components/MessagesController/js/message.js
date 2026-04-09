const module = `${URL_BASED}component/customer/`;
const component = `component/customer/`;

$(document).on("click", ".open_chat", function () {
  var id = $(this).data("id");
  var dataObj = {
    id: id,
  };

  $('input[name="message"]').attr("data-id", id);

  $(".my_message_sent").data("id", id);

  $(".name_user").text($(this).data("email"));
  $(".time_ago_user").text($(this).data("time_ago"));
  // Convert the data object into FormData
  var formData = new FormData();
  for (const key in dataObj) {
    if (dataObj.hasOwnProperty(key)) {
      formData.append(key, dataObj[key]);
    }
  }

  var action = module + "getmessage";

  var request = main.send_ajax(formData, action, "POST", true);

  request.done(function (data) {
    $(".getmessage_here").html(data.html);
  });
  request.fail(function (xhr, ajaxOptions, thrownError) {
    main.alertMessage(
      "warning",
      "System Error!",
      "Please Contact The Administrator."
    );
  });
});
$("#my_message_sent").on("keydown", function (event) {
  if (event.key === "Enter") {
    $(".sentmessagenow").trigger("click");
  }
});

$(document).on("click", ".sentmessagenow", function () {
  var id = $('input[name="message"]').data("id");
  var val = $('input[name="message"]').val();
  var dataObj = {
    id: id,
    val: val,
    action: "add",
  };
  console.log(dataObj);

  var formData = new FormData();
  for (const key in dataObj) {
    if (dataObj.hasOwnProperty(key)) {
      formData.append(key, dataObj[key]);
    }
  }

  var action = module + "getmessage";

  var request = main.send_ajax(formData, action, "POST", true);

  request.done(function (data) {
    $(".getmessage_here").html(data.html);
    $('input[name="message"]').val("");
  });
  request.fail(function (xhr, ajaxOptions, thrownError) {
    main.alertMessage(
      "warning",
      "System Error!",
      "Please Contact The Administrator."
    );
  });
});

function getLatestChat() {
  var id = $('input[name="message"]').data("id");
  console.log(id);
  if (id != "" && id != undefined) {
    var dataObj = {
      id: id,
    };

    // Convert the data object into FormData
    var formData = new FormData();
    for (const key in dataObj) {
      if (dataObj.hasOwnProperty(key)) {
        formData.append(key, dataObj[key]);
      }
    }

    var action = module + "getmessage";
    var request = main.send_ajax(formData, action, "POST", true);

    request.done(function (data) {
      $(".getmessage_here").html(data.html);
    });
  }
}

// Auto-refresh messages every 5 seconds (only when chat is active)
setInterval(() => {
  getLatestChat();
}, 30000); // 5 seconds - reduced server load
