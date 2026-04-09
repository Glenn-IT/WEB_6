const module = `${URL_BASED}component/user-management/`;
const component = `component/user-management/`;

// Handle form submission with validation
$(document).on("submit", ".modalOpenCustom form", function (e) {
  e.preventDefault();

  // Get and trim all field values
  var usernameValue = $(this).find('input[name="username"]').val().trim();
  var emailValue = $(this).find('input[name="email"]').val().trim();
  var contactValue = $(this).find('input[name="contact_no"]').val().trim();
  var passwordValue = $(this).find('input[name="password"]').val().trim();
  var confirmPasswordValue = $(this)
    .find('input[name="confirm_password"]')
    .val()
    .trim();
  var securityQuestionValue = $(this)
    .find('select[name="security_question"]')
    .val();
  var securityAnswerValue = $(this)
    .find('input[name="security_answer"]')
    .val()
    .trim();

  // Check if any field is empty
  if (
    usernameValue === "" ||
    emailValue === "" ||
    contactValue === "" ||
    passwordValue === "" ||
    confirmPasswordValue === "" ||
    securityQuestionValue === "" ||
    securityAnswerValue === ""
  ) {
    main.alertMessage(
      "warning",
      "Validation Error",
      "All fields are required! Please fill in all fields."
    );
    return false;
  }

  // Check if passwords match
  if (passwordValue !== confirmPasswordValue) {
    main.alertMessage(
      "warning",
      "Validation Error",
      "Passwords do not match! Please enter the same password in both fields."
    );
    $(this).find('input[name="confirm_password"]').focus();
    return false;
  }

  // Validate security answer length
  if (securityAnswerValue.length < 2) {
    main.alertMessage(
      "warning",
      "Validation Error",
      "Security answer must be at least 2 characters long."
    );
    $(this).find('input[name="security_answer"]').focus();
    return false;
  }

  // Validate email format
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(emailValue)) {
    main.alertMessage(
      "warning",
      "Validation Error",
      "Please enter a valid email address!"
    );
    $(this).find('input[name="email"]').focus();
    return false;
  }

  // Validate contact number (remove non-digits and check length)
  var contactDigits = contactValue.replace(/[^0-9]/g, "");
  if (contactDigits.length !== 11) {
    main.alertMessage(
      "warning",
      "Validation Error",
      "Contact number must be exactly 11 digits!"
    );
    $(this).find('input[name="contact_no"]').focus();
    return false;
  }

  // Update input values with trimmed values
  $(this).find('input[name="username"]').val(usernameValue);
  $(this).find('input[name="email"]').val(emailValue);
  $(this).find('input[name="contact_no"]').val(contactDigits);
  $(this).find('input[name="password"]').val(passwordValue);
  $(this).find('input[name="confirm_password"]').val(confirmPasswordValue);
  $(this).find('input[name="security_answer"]').val(securityAnswerValue);

  main.form_ajax(this);
});

// Real-time password match validation
$(document).on("input", "#confirm_password", function () {
  var password = $("#password").val();
  var confirmPassword = $(this).val();

  if (confirmPassword.length > 0) {
    if (password === confirmPassword) {
      $(this).removeClass("is-invalid").addClass("is-valid");
    } else {
      $(this).removeClass("is-valid").addClass("is-invalid");
    }
  } else {
    $(this).removeClass("is-valid is-invalid");
  }
});

// Real-time security answer validation
$(document).on("input", "#security_answer", function () {
  var value = $(this).val().trim();

  if (value.length >= 2) {
    $(this).removeClass("is-invalid").addClass("is-valid");
  } else if (value.length > 0) {
    $(this).removeClass("is-valid").addClass("is-invalid");
  } else {
    $(this).removeClass("is-valid is-invalid");
  }
});

// Real-time contact number validation
$(document).on("input", "#contact_no", function () {
  // Remove any non-digit characters
  var value = $(this)
    .val()
    .replace(/[^0-9]/g, "");
  $(this).val(value);

  // Visual feedback
  if (value.length === 11) {
    $(this).removeClass("is-invalid").addClass("is-valid");
  } else if (value.length > 0) {
    $(this).removeClass("is-valid").addClass("is-invalid");
  } else {
    $(this).removeClass("is-valid is-invalid");
  }
});

// Real-time email validation
$(document).on("blur", "#email", function () {
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  var value = $(this).val().trim();

  if (value.length > 0) {
    if (emailRegex.test(value)) {
      $(this).removeClass("is-invalid").addClass("is-valid");
    } else {
      $(this).removeClass("is-valid").addClass("is-invalid");
    }
  }
});

// Show/Hide password toggle with eye icon
$(document).on("click", "#togglePassword", function () {
  var passwordField = $("#password");
  var eyeIcon = $("#eyeIcon");

  if (passwordField.attr("type") === "password") {
    passwordField.attr("type", "text");
    eyeIcon.removeClass("fa-eye").addClass("fa-eye-slash");
  } else {
    passwordField.attr("type", "password");
    eyeIcon.removeClass("fa-eye-slash").addClass("fa-eye");
  }
});

// Show/Hide confirm password toggle with eye icon
$(document).on("click", "#toggleConfirmPassword", function () {
  var confirmPasswordField = $("#confirm_password");
  var confirmEyeIcon = $("#confirmEyeIcon");

  if (confirmPasswordField.attr("type") === "password") {
    confirmPasswordField.attr("type", "text");
    confirmEyeIcon.removeClass("fa-eye").addClass("fa-eye-slash");
  } else {
    confirmPasswordField.attr("type", "password");
    confirmEyeIcon.removeClass("fa-eye-slash").addClass("fa-eye");
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

$(document).on("click", ".archive", function () {
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
    "DELETE ARCHIVE",
    "Are you sure you want to Archive this record? ",
    "archiveRecord",
    formData
  );
});

function archiveRecord(formData) {
  var action = module + "archived";

  var request = main.send_ajax(formData, action, "POST", true);
  request.done(function (data) {
    if (data.status) {
      main.confirmMessage(
        "success",
        "Successfully Archived!",
        "Are you sure you want to reload this page? ",
        "reloadPage",
        ""
      );
    } else {
      main.alertMessage("danger", "Failed to Archived!", "");
    }
  });
}
