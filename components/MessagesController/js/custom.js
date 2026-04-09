// Search functionality - Search by email address only
$(document).on("input", "#searchMessages", function () {
  const searchTerm = $(this).val().toLowerCase().trim();

  $(".message-item").each(function () {
    const email = $(this).data("email");

    // Show/hide based on email match only
    if (email.includes(searchTerm)) {
      $(this).show();
    } else {
      $(this).hide();
    }
  });
});

// View Product - Redirect to Item Master
$(document).on("click", ".getmessage_here a", function (e) {
  const href = $(this).attr("href");

  // Check if this is a View Product link
  if (href && href.includes("page=productItem")) {
    e.preventDefault();

    // Extract item_id from URL
    const urlParams = new URLSearchParams(href.split("?")[1]);
    const itemId = urlParams.get("id");

    if (itemId) {
      // Redirect to Item Master page
      window.location.href =
        URL_BASED + "component/item-master/index?item_id=" + itemId;
    } else {
      console.error("Item ID not found in URL");
    }
  }
});
