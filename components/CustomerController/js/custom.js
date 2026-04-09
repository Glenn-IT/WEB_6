const module = `${URL_BASED}component/customer/`;
const component = `component/customer/`;

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

// Cancel appointment functionality
$(document).on("click", ".cancel-appointment-btn", function () {
  var $button = $(this);
  var appointmentId = $button.data("id");
  var orderNo = $button.data("order-no");

  // Prevent multiple clicks
  if ($button.hasClass("loading")) {
    return false;
  }

  var dataObj = {
    action: "cancelAppointment",
    appointment_id: appointmentId,
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
    "CANCEL APPOINTMENT",
    "Are you sure you want to cancel appointment " +
      orderNo +
      "? This action cannot be undone.",
    "cancelAppointmentConfirm",
    formData
  );
});

function cancelAppointmentConfirm(formData) {
  var action = module + "index"; // Using the main index method to handle POST requests

  // Find the button and add loading state
  var appointmentId = formData.get("appointment_id");
  var $button = $('.cancel-appointment-btn[data-id="' + appointmentId + '"]');

  // Add loading state
  $button.addClass("loading").prop("disabled", true);
  $button.html('<i class="fa fa-spinner fa-spin"></i> Cancelling...');

  var request = main.send_ajax(formData, action, "POST", true);
  request
    .done(function (data) {
      // Remove loading state
      $button.removeClass("loading").prop("disabled", false);

      if (data.status) {
        // Success - update button to show cancelled state
        $button
          .closest("tr")
          .find("td:last")
          .html('<span class="badge bg-danger">CANCELLED</span>');
        $button.replaceWith(
          '<span class="badge bg-secondary">Cancelled</span>'
        );

        main.confirmMessage(
          "success",
          "Appointment Cancelled!",
          "Your appointment has been successfully cancelled. The page will reload to show updated status.",
          "reloadPage",
          ""
        );
      } else {
        // Error - restore button
        $button.html('<i class="fa fa-times"></i> Cancel');
        main.alertMessage(
          "danger",
          "Failed to Cancel Appointment!",
          data.message || "An error occurred."
        );
      }
    })
    .fail(function (xhr, status, error) {
      // Remove loading state on error
      $button.removeClass("loading").prop("disabled", false);
      $button.html('<i class="fa fa-times"></i> Cancel');

      console.error("AJAX Error:", xhr.responseText);

      // Try to parse error response
      var errorMessage = "An error occurred while processing your request.";
      try {
        var errorData = JSON.parse(xhr.responseText);
        if (errorData.message) {
          errorMessage = errorData.message;
        }
      } catch (e) {
        // Use default error message
      }

      main.alertMessage("danger", "Error!", errorMessage);
    });
}

function updateCountdowns() {
  const countdownElements = document.querySelectorAll(".countdown");

  countdownElements.forEach((element) => {
    const endDate = element.getAttribute("data-enddate");
    const now = new Date().getTime();
    const targetTime = new Date(endDate).getTime();
    const timeRemaining = targetTime - now;

    if (timeRemaining <= 0) {
      element.innerHTML = "Time is up!";
      return;
    }

    const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
    const hours = Math.floor(
      (timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    const minutes = Math.floor(
      (timeRemaining % (1000 * 60 * 60)) / (1000 * 60)
    );
    const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

    element.innerHTML = `${days} days, ${hours} hours, ${minutes} minutes, ${seconds} seconds`;
  });
}

// Update countdowns every second
setInterval(updateCountdowns, 1000);

$(document).on("click", ".modalPayment", function () {
  var action = module + "paymentAction";

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

  var request = main.send_ajax(formData, action, "POST", true);
  request.done(function (data) {
    action = component + data.action;

    main.modalOpen(data.header, data.html, data.button, action, data.size);
  });
});

$(document).on(
  "change",
  ".qty_control, .qty-control__reduce, .qty-control__increase",
  function () {
    var min = parseInt($(".qty-control__number").attr("min"));
    var max = parseInt($(".qty-control__number").attr("max"));
    var val = parseInt($(".qty-control__number").val());

    if (val > max || val < min) {
      main.alertMessage("danger", "Exceeded Limit", "");
      $(".qty-control__number").val(min);
    }
  }
);

$(document).on("click", ".btn-addtocart", function () {
  var action = module + "terms_and_condition";

  const calendar = false;

  var dataObj = {}; // Initialize the empty object

  // Iterate through each input field in #myProductinBidding
  $("#myProductinBidding input").each(function () {
    const inputName = $(this).attr("name"); // Get the name attribute of the input
    const inputValue = $(this).val(); // Get the value of the input field

    // Append the input name and value to the dataObj
    dataObj[inputName] = inputValue;
  });

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
var calendar = false;

function generateScheduleCalendar() {
  var calendarEl = document.getElementById("calendar");
  var timeSlotsEl = document.getElementById("timeSlots");

  // Set fully booked dates here
  var fullyBookedDates = ["2025-05-10", "2024-05-12", "2024-05-15"];
  let selectedDateEl = null; // track previously selected date element
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    validRange: {
      start: new Date().toISOString().split("T")[0], // Disable past dates
    },
    dateClick: function (info) {
      console.log(info.dateStr);

      // Check if the clicked date is in the past
      var clickedDate = new Date(info.dateStr);
      var today = new Date();
      today.setHours(0, 0, 0, 0); // Reset time to start of day

      if (clickedDate < today) {
        alert("You cannot select past dates.");
        return;
      }

      if (fullyBookedDates.includes(info.dateStr)) {
        alert("This date is fully booked.");
        return;
      }

      // Remove previous "selected" highlight
      if (selectedDateEl) {
        selectedDateEl.classList.remove("fc-day-selected");
        selectedDateEl.querySelector(".selected-label")?.remove();
      }

      // Highlight the new selected day
      selectedDateEl = info.dayEl;
      selectedDateEl.classList.add("fc-day-selected");

      // Add label
      const label = document.createElement("div");
      label.textContent = "Selected";
      label.classList.add("selected-label");
      selectedDateEl.appendChild(label);

      // Update form
      document.getElementById("date").value = info.dateStr;

      document.getElementById("schedule_date").value = info.dateStr;

      dateClickChange();
    },
    dayCellDidMount: function (info) {
      var dateStr = info.date.toLocaleDateString("en-CA"); // Correctly matches YYYY-MM-DD
      var cellDate = new Date(dateStr);
      var today = new Date();
      today.setHours(0, 0, 0, 0);

      // Disable past dates
      if (cellDate < today) {
        info.el.classList.add("fc-day-disabled");
        info.el.style.backgroundColor = "#f0f0f0";
        info.el.style.color = "#ccc";
        info.el.style.cursor = "not-allowed";
      }

      // Disable fully booked dates
      if (fullyBookedDates.includes(dateStr)) {
        info.el.classList.add("fc-day-disabled");
      }
    },
  });

  calendar.render();

  // // Generate time slots from 8:30 AM to 5:00 PM in 30-minute steps
  // var startHour = 8;
  // var startMin = 30;
  // var endHour = 17;
  // let slots = '';
  // for (let h = startHour; h <= endHour; h++) {
  //   for (let m = 0; m < 60; m += 30) {
  //     if (h === startHour && m < startMin) continue;
  //     if (h === endHour && m > 0) break;

  //     var timeVal = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
  //     var ampm = h < 12 ? 'AM' : 'PM';
  //     var displayHour = ((h + 11) % 12 + 1); // Convert to 12-hour format
  //     var displayTime = `${displayHour}:${String(m).padStart(2, '0')} ${ampm}`;
  //     slots += `<label style="font-size:28px;"><input type="radio"  name="time" value="${timeVal}" required> ${displayTime}</label><br>`;
  //   }
  // }
  // timeSlotsEl.innerHTML = slots;
}

function dateClickChange() {
  var selected_date = $("#date").val();

  var action = module + "getTimeSchedule";

  var dataObj = {
    selected_date: selected_date,
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
    // Handle new response format that includes time slot counts and current time info
    var timeSlotCounts, currentDate, currentTime, selectedDate;

    if (data && typeof data === "object" && data.timeSlotCounts) {
      // New format with time information
      timeSlotCounts = data.timeSlotCounts;
      currentDate = data.currentDate;
      currentTime = data.currentTime;
      selectedDate = data.selectedDate;
    } else {
      // Legacy format (just time slot counts)
      timeSlotCounts = data;
      currentDate = new Date().toISOString().split("T")[0]; // fallback
      currentTime = new Date().toTimeString().split(" ")[0]; // fallback
      selectedDate = null;
    }

    // Store current time info globally for time slot filtering
    window.currentTimeInfo = { currentDate, currentTime, selectedDate };

    // Morning: start at 8:30 AM, end 11:30 AM
    const amOptions = buildSelectOptions(timeSlotCounts, 8, 30, 11);

    // Afternoon/Evening: start at 1:00 PM (13:00), end 10:30 PM (22:30)
    const pmOptions = buildSelectOptions(timeSlotCounts, 13, 0, 22);

    // Create select elements for AM and PM
    const amSelectHtml = `
      <select class="form-select timescheulde" name="time_am" style="font-size: 18px; padding: 10px;">
        <option value="">Select AM Time</option>
        ${amOptions.join("")}
      </select>
    `;

    const pmSelectHtml = `
      <select class="form-select timescheulde" name="time_pm" style="font-size: 18px; padding: 10px;">
        <option value="">Select PM Time</option>
        ${pmOptions.join("")}
      </select>
    `;

    document.getElementById("amSlots").innerHTML = amSelectHtml;
    document.getElementById("pmSlots").innerHTML = pmSelectHtml;

    // Apply styling to select elements based on booking counts
    applyTimeSlotStyling(timeSlotCounts);

    // Store time slot counts globally for recalculation
    window.currentTimeSlotCounts = timeSlotCounts;
  });

  function applyTimeSlotStyling(timeSlotCounts) {
    // Initial styling is handled through CSS classes and the change event handler
    // This function can be used for any additional initialization if needed
  }

  function updateTimeSlotAvailability() {
    if (!window.currentTimeSlotCounts) return;

    const numberOfClients = parseInt($("#no_ofhead").val()) || 1;
    const timeSlotCounts = window.currentTimeSlotCounts;

    // Regenerate the time slot options with updated availability
    const amOptions = buildSelectOptionsWithClientCount(
      timeSlotCounts,
      8,
      30,
      11,
      numberOfClients
    );
    const pmOptions = buildSelectOptionsWithClientCount(
      timeSlotCounts,
      13,
      0,
      22,
      numberOfClients
    );

    // Update the select elements
    const amSelectHtml = `
      <select class="form-select timescheulde" name="time_am" style="font-size: 18px; padding: 10px;">
        <option value="">Select AM Time</option>
        ${amOptions.join("")}
      </select>
    `;

    const pmSelectHtml = `
      <select class="form-select timescheulde" name="time_pm" style="font-size: 18px; padding: 10px;">
        <option value="">Select PM Time</option>
        ${pmOptions.join("")}
      </select>
    `;

    document.getElementById("amSlots").innerHTML = amSelectHtml;
    document.getElementById("pmSlots").innerHTML = pmSelectHtml;
  }

  function buildSelectOptionsWithClientCount(
    timeSlotCounts,
    startHour,
    startMinute,
    endHour,
    numberOfClients
  ) {
    const options = [];
    const MAX_CLIENTS_PER_SLOT = 5;

    // Get current time info for filtering past times
    const timeInfo = window.currentTimeInfo || {};
    const isToday = timeInfo.selectedDate === timeInfo.currentDate;
    const currentHour = isToday
      ? parseInt(timeInfo.currentTime?.split(":")[0] || "0")
      : 0;
    const currentMinute = isToday
      ? parseInt(timeInfo.currentTime?.split(":")[1] || "0")
      : 0;

    // Also get browser's current time for comparison
    const browserTime = new Date();
    const browserHour = browserTime.getHours();
    const browserMinute = browserTime.getMinutes();

    // Debug logging - remove this after testing
    console.log("Time Info Debug:", {
      selectedDate: timeInfo.selectedDate,
      currentDate: timeInfo.currentDate,
      serverTime: timeInfo.currentTime,
      browserTime: `${browserHour}:${browserMinute}`,
      isToday: isToday,
      timeRange: "AM: 8:30-11:30, PM: 13:00-22:30",
    });

    for (let h = startHour; h <= endHour; h++) {
      for (let m = h === startHour ? startMinute : 0; m < 60; m += 30) {
        if (h === endHour && m > 30) break;

        const time24 = `${String(h).padStart(2, "0")}:${String(m).padStart(
          2,
          "0"
        )}:00`;

        // Check if this time slot is in the past for today's date
        // Use browser time as primary check since it's more reliable for the user's context
        const browserIsPastTime =
          isToday &&
          (h < browserHour || (h === browserHour && m < browserMinute));
        const serverIsPastTime =
          isToday &&
          (h < currentHour || (h === currentHour && m < currentMinute));

        // Use browser time for the final decision
        const isPastTime = browserIsPastTime;

        // Enhanced debug logging for all time slots when it's today
        if (isToday) {
          const timeType = h < 12 ? "AM" : "PM";
          const displayHour = ((h + 11) % 12) + 1;
          console.log(
            `${timeType} Slot Debug: ${displayHour}:${String(m).padStart(
              2,
              "0"
            )} ${timeType} (${h}:${String(m).padStart(
              2,
              "0"
            )}) - Browser: ${browserHour}:${String(browserMinute).padStart(
              2,
              "0"
            )} - Server: ${currentHour}:${String(currentMinute).padStart(
              2,
              "0"
            )} - isPast: ${isPastTime}`
          );
        }

        // Get the count of existing bookings for this time slot
        const existingBookings = timeSlotCounts[time24] || 0;
        const availableSlots = MAX_CLIENTS_PER_SLOT - existingBookings;
        const wouldExceedLimit = numberOfClients > availableSlots;
        const isFullyOccupied =
          existingBookings >= MAX_CLIENTS_PER_SLOT || wouldExceedLimit;
        const hasLimitedAvailability =
          !isFullyOccupied &&
          (existingBookings >= 3 ||
            existingBookings + numberOfClients > MAX_CLIENTS_PER_SLOT);

        const dispHr = ((h + 11) % 12) + 1;
        const ampm = h < 12 ? "AM" : "PM";

        // Generate status text for availability
        let availabilityText = "";
        if (isPastTime) {
          availabilityText = " (Past Time)";
        } else if (isFullyOccupied) {
          if (wouldExceedLimit && existingBookings < MAX_CLIENTS_PER_SLOT) {
            availabilityText = ` (Only ${availableSlots} left)`;
          } else {
            availabilityText = " (FULL)";
          }
        } else if (hasLimitedAvailability) {
          availabilityText = ` (${availableSlots} left)`;
        }

        // Disable if it's a past time or fully occupied
        const isDisabled = isPastTime || isFullyOccupied;

        options.push(
          `<option value="${time24}" ${
            isDisabled ? "disabled" : ""
          } data-booking-count="${existingBookings}" data-available-slots="${availableSlots}" data-is-past="${isPastTime}">
            ${dispHr}:${String(m).padStart(2, "0")} ${ampm}${availabilityText}
          </option>`
        );
      }
    }
    return options;
  }

  function buildSelectOptions(timeSlotCounts, startHour, startMinute, endHour) {
    // Initially build with 1 client (default)
    return buildSelectOptionsWithClientCount(
      timeSlotCounts,
      startHour,
      startMinute,
      endHour,
      1
    );
  }
}

$(document).on("click", ".view_calendar", function () {
  generateScheduleCalendar();
});

$(document).on("change", ".timescheulde", function () {
  var val = $(this).val();
  if (val) {
    // Check if the selected time slot is in the past
    const selectedOption = this.options[this.selectedIndex];
    const isPastTime = selectedOption.getAttribute("data-is-past") === "true";

    if (isPastTime) {
      alert(
        "You cannot schedule an appointment for a past time. Please select a future time slot."
      );
      this.value = ""; // Reset selection
      return;
    }

    // Check if the selected time slot can accommodate the number of clients
    const numberOfClients = parseInt($("#no_ofhead").val()) || 1;
    const availableSlots =
      parseInt(selectedOption.getAttribute("data-available-slots")) || 0;

    if (numberOfClients > availableSlots) {
      alert(
        `This time slot only has ${availableSlots} spots available, but you selected ${numberOfClients} clients. Please choose a different time or reduce the number of clients.`
      );
      this.value = ""; // Reset selection
      return;
    }

    // Clear the other time select when one is chosen
    if ($(this).attr("name") === "time_am") {
      $('select[name="time_pm"]').val("");
      $('select[name="time_pm"]').removeClass("selected-time");
      $(this).addClass("selected-time");
    } else if ($(this).attr("name") === "time_pm") {
      $('select[name="time_am"]').val("");
      $('select[name="time_am"]').removeClass("selected-time");
      $(this).addClass("selected-time");
    }

    // Set the hidden time input
    $('input[name="time"]').val(val);
    $("#schedule_time").val(val);

    // Apply styling based on booking count and client count
    if (selectedOption && selectedOption.value) {
      const bookingCount =
        parseInt(selectedOption.getAttribute("data-booking-count")) || 0;
      const MAX_CLIENTS_PER_SLOT = 5;
      const remainingAfterBooking =
        MAX_CLIENTS_PER_SLOT - bookingCount - numberOfClients;

      // Remove all previous styling
      $(this).removeClass("fully-occupied limited-availability");

      if (remainingAfterBooking <= 0) {
        $(this).addClass("fully-occupied");
      } else if (remainingAfterBooking <= 2) {
        $(this).addClass("limited-availability");
      } else {
        // Add visual feedback for normal selection
        $(this).css("border-color", "#28a745");
      }
    }
  } else {
    $(this).removeClass("selected-time");
    $(this).css("border-color", "#ddd");
  }
});

// Handle number of clients change
$(document).on("change", "#no_ofhead", function () {
  // Clear any selected time when number of clients changes
  $(".timescheulde").val("").removeClass("selected-time");
  $('input[name="time"]').val("");
  $("#schedule_time").val("");

  // Update time slot availability based on new client count
  updateTimeSlotAvailability();
});

$(document).on("change", ".tehrapisht", function () {
  var val = $(this).data("val");
  $("#Therapist_name").val(val);
});

$(document).on("click", ".btn-acknowledgebid", function () {
  var action = module + "terms_and_condition_acknowleedge";

  var dataObj = {
    link: $(this).data("href"),
  }; // Initialize the empty object

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

$(document).on("change", ".financing_check", function (e) {
  // Uncheck all other checkboxes
  $(".financing_check").not(this).prop("checked", false);
});

// $(document).on('change', '.qty_control', function(e) {
//     var m = $('.qty-control__number');
//     var val = m.val();
//     var max = m.attr('max');
//     console.log(val, max );

//     if(val>max){
//         alert('Exceeded Limit');
//         return false;
//     }

// });

$(document).on("click", ".checkout-steps__item", function (e) {
  $(this).addClass("active");
});

$(document).on("click", ".login_form", function (e) {
  // Check if form is currently locked
  if ($(this).hasClass("locked")) {
    return false;
  }

  var form = $("#login_form")[0]; // Get the DOM element
  var action = $(form).attr("action");

  var dataObj = {}; // Initialize the empty object
  // Convert form into FormData
  var formData = new FormData(form);

  // Append any custom data
  for (const key in dataObj) {
    if (dataObj.hasOwnProperty(key)) {
      formData.append(key, dataObj[key]);
    }
  }

  var request = main.send_ajax(formData, action, "POST", true);

  // Disable button to prevent double submission
  var loginButton = $(this);
  var originalText = loginButton.text();
  loginButton
    .prop("disabled", true)
    .html('<i class="fa fa-spinner fa-spin"></i> Logging in...');

  request.done(function (data) {
    // Re-enable button
    loginButton.prop("disabled", false).text(originalText);

    if (data.code == 404) {
      // User not found - don't increment attempts
      main.alertMessage("warning", "⚠️ " + data.message, "");
    } else if (data.code == 401) {
      // Invalid credentials - sync client-side attempt counter with server
      if (data.attempts) {
        setLoginAttempts(data.attempts);

        // If 3 attempts, trigger lockout UI
        if (data.attempts >= 3) {
          lockLoginForm(30);
        }
      }

      // Show enhanced message with email notification info
      var message = data.message;
      if (data.attempts) {
        if (data.attempts >= 3) {
          message = "🚨 " + message;
        } else if (data.attempts >= 2) {
          message = "⚠️ " + message;
        } else {
          message = "📧 " + message;
        }
      }
      main.alertMessage("warning", message, "");
    } else if (data.code == 429) {
      // Server-side lockout - sync attempt counter
      setLoginAttempts(3);
      handleServerLockout(data.lockout_time || 30);
      main.alertMessage("warning", "🔒 " + data.message, "");
    } else {
      // Successful login - reset attempt counter
      resetLoginAttempts();
      main.alertMessage("success", "Successfully Login!", "");
      setTimeout(function () {
        window.location.reload();
      }, 2500);
    }
  });

  request.fail(function (xhr, status, error) {
    // Re-enable button
    loginButton.prop("disabled", false).text(originalText);

    if (status === "timeout") {
      main.alertMessage("warning", "Request timeout. Please try again.", "");
    } else {
      main.alertMessage("warning", "An error occurred. Please try again.", "");
    }
  });
});

// Function to handle failed login attempts
function handleFailedLogin() {
  var attempts = getLoginAttempts();
  attempts++;
  setLoginAttempts(attempts);

  if (attempts >= 3) {
    lockLoginForm(30); // Lock for 30 seconds
  }
}

// Function to handle server-side lockout
function handleServerLockout(seconds) {
  lockLoginForm(seconds);
}

// Function to lock the login form
function lockLoginForm(seconds) {
  var form = $("#login_form");
  var emailInput = form.find('input[name="email"]');
  var passwordInput = form.find('input[name="password"]');
  var loginButton = form.find(".login_form");

  // Disable form elements
  emailInput.prop("disabled", true);
  passwordInput.prop("disabled", true);
  loginButton.addClass("locked").prop("disabled", true);

  // Add visual feedback
  form.find(".form-floating, .form-label-fixed").addClass("lockout-mode");

  // Create or update countdown display
  var countdownElement = form.find(".lockout-countdown");
  if (countdownElement.length === 0) {
    countdownElement = $(
      '<div class="lockout-countdown alert alert-warning mt-2"></div>'
    );
    form.append(countdownElement);
  }

  // Start countdown
  var remainingTime = seconds;
  var originalButtonText = loginButton.text();

  function updateCountdown() {
    if (remainingTime > 0) {
      countdownElement.html(
        '<i class="fa fa-lock" style="margin-right: 8px;"></i>' +
          "<strong>Account Locked!</strong><br>" +
          "Please wait <strong>" +
          remainingTime +
          "</strong> seconds before trying again.<br>" +
          '<small><i class="fa fa-envelope" style="margin-right: 5px;"></i>A security notification has been sent to your email.</small>'
      );
      loginButton.text("Locked (" + remainingTime + "s)");
      remainingTime--;
      setTimeout(updateCountdown, 1000);
    } else {
      unlockLoginForm(originalButtonText);
    }
  }

  updateCountdown();
}

// Function to unlock the login form
function unlockLoginForm(originalButtonText = "Log In") {
  var form = $("#login_form");
  var emailInput = form.find('input[name="email"]');
  var passwordInput = form.find('input[name="password"]');
  var loginButton = form.find(".login_form");
  var countdownElement = form.find(".lockout-countdown");

  // Re-enable form elements
  emailInput.prop("disabled", false);
  passwordInput.prop("disabled", false);
  loginButton
    .removeClass("locked")
    .prop("disabled", false)
    .text(originalButtonText);

  // Remove visual feedback
  form.find(".form-floating, .form-label-fixed").removeClass("lockout-mode");
  countdownElement.remove();

  // Reset client-side attempt counter
  resetLoginAttempts();
}

// Function to get login attempts from localStorage
function getLoginAttempts() {
  var attempts = localStorage.getItem("login_attempts");
  var lastAttempt = localStorage.getItem("last_attempt_time");
  var now = new Date().getTime();

  // Reset if more than 30 seconds have passed
  if (lastAttempt && now - parseInt(lastAttempt) > 30000) {
    resetLoginAttempts();
    return 0;
  }

  return attempts ? parseInt(attempts) : 0;
}

// Function to set login attempts in localStorage
function setLoginAttempts(attempts) {
  localStorage.setItem("login_attempts", attempts.toString());
  localStorage.setItem("last_attempt_time", new Date().getTime().toString());
}

// Function to reset login attempts
function resetLoginAttempts() {
  localStorage.removeItem("login_attempts");
  localStorage.removeItem("last_attempt_time");
}

// Check for existing lockout on page load
$(document).ready(function () {
  var attempts = getLoginAttempts();
  var lastAttempt = localStorage.getItem("last_attempt_time");

  if (attempts >= 3 && lastAttempt) {
    var elapsed = (new Date().getTime() - parseInt(lastAttempt)) / 1000;
    var remaining = 30 - elapsed;

    if (remaining > 0) {
      lockLoginForm(Math.ceil(remaining));
    }
  }
});

$(document).on("click", ".btn-checkouttocartbooking", function () {
  var action = module + "terms_and_condition";

  const calendar = false;

  var dataObj = {}; // Initialize the empty object
  var form = document.getElementById("getlsitCartt");
  var formData = new FormData(form);
  // Append any custom data
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
var calendar = false;

function tableGuest(itemlist, no_head) {
  const guestCount = no_head;
  const items = itemlist;

  // Build header
  let headerHtml = "<th>Item</th>";
  headerHtml += "<th>Cost</th>";
  for (let g = 1; g <= guestCount; g++) {
    headerHtml += `<th>Client ${g}</th>`;
  }
  $("#headerRow").html(headerHtml);

  // Build body
  let bodyHtml = "";
  items.forEach((item) => {
    let row = `<tr><td>${item.name}</td><td>${item.unit_cost}</td>`;
    for (let g = 1; g <= guestCount; g++) {
      row += `<td>
                <input type="checkbox"
                       data-cost="${item.unit_cost}"
                       data-guest="${g}"
                       class="form-check-input guest-check" name="guest[${g}][]" value="${item.id}">
            </td>`;
    }
    row += "</tr>";
    bodyHtml += row;
  });

  // Add totals row
  bodyHtml += `<tr><td></td><td><b>Total:</b></td>`;
  for (let g = 1; g <= guestCount; g++) {
    bodyHtml += `<td >
            <input type="text" id="total-${g}" 
                   class="total_grand form-control" readonly style="width:100px">
        </td>`;
  }
  bodyHtml += `</tr>`;

  $("#bodyRows").html(bodyHtml);

  // Reset totals
  for (let g = 1; g <= guestCount; g++) {
    $(`#total-${g}`).val(0);
  }

  // Add event listener for checkbox clicks
  $(document).on("change", ".guest-check", function () {
    const guest = $(this).data("guest");
    let total = 0;

    // Sum lahat ng naka-check para sa guest na ito
    $(`.guest-check[data-guest=${guest}]:checked`).each(function () {
      total += parseFloat($(this).data("cost"));
    });

    // Update input field
    $(`#total-${guest}`).val(total.toFixed(2));

    var total_grand = 0;
    $(`.total_grand`).each(function () {
      total_grand += parseFloat($(this).val());
    });

    $(".total_payment").val(total_grand);

    // ===== DOWNPAYMENT FEATURE - COMMENTED OUT FOR FUTURE USE =====
    // Trigger downpayment calculation update if on step 4
    // updateDownpaymentAmount();
    // ===== END DOWNPAYMENT FEATURE =====
  });
}

// ===== DOWNPAYMENT FEATURE - COMMENTED OUT FOR FUTURE USE =====
// Function to update downpayment amount when total changes
// function updateDownpaymentAmount() {
//   const downpaymentAmountField = document.getElementById("downpayment_amount");
//   if (downpaymentAmountField) {
//     const totalPayment =
//       parseFloat(document.querySelector(".total_payment").value) || 0;
//     const downpaymentAmount = (totalPayment * 0.5).toFixed(2);
//     downpaymentAmountField.value = "₱" + downpaymentAmount;
//   }
// }
// ===== END DOWNPAYMENT FEATURE =====
