$(document).on("click", ".open_chat", function () {
  var id = $(this).data("id");

  var dataObj = {
    id: id,
  };

  $('input[name="message"]').attr("data-id", id);

  $(".my_message_sent").data("id", id);

  $(".name_user").text("SELLER");
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
    // Hide welcome message when conversation is opened
    $(".welcome-message").hide();
  });
  request.fail(function (xhr, ajaxOptions, thrownError) {
    main.alertMessage(
      "warning",
      "System Error!",
      "Please Contact The Administrator."
    );
  });
});
// Trigger the click event on the .open_chat element if conversation exists
if ($(".open_chat").length > 0) {
  $(".open_chat").trigger("click");
} else {
  // Show welcome message if no conversations
  $(".welcome-message").show();
}

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
    // Hide welcome message after sending a message
    $(".welcome-message").hide();
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
      // Keep welcome message hidden during auto-refresh if chat is active
      $(".welcome-message").hide();
    });
  }
}

// Auto-refresh messages every 5 seconds (only when chat is active)
setInterval(() => {
  getLatestChat();
}, 30000); // 5 seconds - reduced server load

$("#flexCheckDefault2").on("click", function () {
  const passwordField = $("#customerPasswordInput");
  const type = passwordField.attr("type") === "password" ? "text" : "password";
  passwordField.attr("type", type);

  // Toggle button text
  $(this).text(type === "password" ? "Show" : "Hide");
});
