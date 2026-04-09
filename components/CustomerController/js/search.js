// Enhanced search functionality
document.addEventListener("DOMContentLoaded", function () {
  const searchInputs = document.querySelectorAll('input[name="search"]');

  // Track active suggestions to prevent conflicts
  let activeSuggestionsInput = null;

  // Add search suggestions functionality
  searchInputs.forEach(function (input) {
    let timeout;

    input.addEventListener("input", function () {
      clearTimeout(timeout);
      const query = this.value.trim();

      if (query.length >= 2) {
        timeout = setTimeout(() => {
          fetchSearchSuggestions(query, this);
        }, 300);
      } else {
        hideSuggestions(this);
      }
    });

    // Handle keyboard navigation
    input.addEventListener("keydown", function (e) {
      const suggestionsDiv = this.parentNode.querySelector(
        ".search-suggestions"
      );
      if (!suggestionsDiv || suggestionsDiv.style.display === "none") return;

      const items = suggestionsDiv.querySelectorAll(".search-suggestion-item");
      let selectedIndex = -1;

      // Find currently selected item
      items.forEach((item, index) => {
        if (item.classList.contains("selected")) {
          selectedIndex = index;
        }
      });

      switch (e.key) {
        case "ArrowDown":
          e.preventDefault();
          selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
          updateSelection(items, selectedIndex);
          break;
        case "ArrowUp":
          e.preventDefault();
          selectedIndex = Math.max(selectedIndex - 1, -1);
          updateSelection(items, selectedIndex);
          break;
        case "Enter":
          e.preventDefault();
          if (selectedIndex >= 0 && items[selectedIndex]) {
            selectSuggestion(
              items[selectedIndex].textContent,
              items[selectedIndex]
            );
          }
          break;
        case "Escape":
          hideSuggestions(this);
          break;
      }
    });

    // Focus event
    input.addEventListener("focus", function () {
      activeSuggestionsInput = this;
    });

    // Blur event with delay to allow clicks on suggestions
    input.addEventListener("blur", function () {
      setTimeout(() => {
        if (activeSuggestionsInput === this) {
          hideSuggestions(this);
          activeSuggestionsInput = null;
        }
      }, 150);
    });
  });

  // Global click handler to hide suggestions
  document.addEventListener("click", function (e) {
    // Check if click is outside all search inputs and suggestions
    const isSearchInput = e.target.matches('input[name="search"]');
    const isSuggestion = e.target.closest(".search-suggestions");

    if (!isSearchInput && !isSuggestion) {
      searchInputs.forEach((input) => hideSuggestions(input));
      activeSuggestionsInput = null;
    }
  });

  function updateSelection(items, selectedIndex) {
    items.forEach((item, index) => {
      if (index === selectedIndex) {
        item.classList.add("selected");
        item.style.backgroundColor = "#007bff";
        item.style.color = "white";
      } else {
        item.classList.remove("selected");
        item.style.backgroundColor = "";
        item.style.color = "";
      }
    });
  }

  function fetchSearchSuggestions(query, inputElement) {
    // Create suggestions dropdown if it doesn't exist
    let suggestionsDiv = inputElement.parentNode.querySelector(
      ".search-suggestions"
    );
    if (!suggestionsDiv) {
      suggestionsDiv = document.createElement("div");
      suggestionsDiv.className =
        "search-suggestions position-absolute bg-white border rounded-2 shadow-sm w-100";
      suggestionsDiv.style.zIndex = "1000";
      suggestionsDiv.style.top = "100%";
      suggestionsDiv.style.left = "0";
      suggestionsDiv.style.maxHeight = "200px";
      suggestionsDiv.style.overflowY = "auto";
      suggestionsDiv.style.display = "none";
      inputElement.parentNode.style.position = "relative";
      inputElement.parentNode.appendChild(suggestionsDiv);
    }

    // Show loading state
    suggestionsDiv.innerHTML =
      '<div class="search-suggestion-item p-2 text-muted">Searching...</div>';
    suggestionsDiv.style.display = "block";

    // Get base URL - try multiple methods
    let baseUrl = document.body.getAttribute("data-url") || "";
    if (!baseUrl) {
      baseUrl =
        window.location.origin +
        window.location.pathname.replace(/\/[^\/]*$/, "/");
    }

    // Make AJAX request for real suggestions
    fetch(baseUrl + "search_suggestions.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "query=" + encodeURIComponent(query),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        // Only update if this input is still active
        if (activeSuggestionsInput !== inputElement) return;

        if (data.suggestions && data.suggestions.length > 0) {
          suggestionsDiv.innerHTML = data.suggestions
            .map(
              (suggestion) =>
                `<div class="search-suggestion-item p-2 cursor-pointer" 
                   data-suggestion="${suggestion.replace(/"/g, "&quot;")}"
                   onmousedown="selectSuggestion('${suggestion.replace(
                     /'/g,
                     "\\'"
                   )}', this)"
                   onmouseover="this.style.backgroundColor='#f8f9fa'"
                   onmouseout="this.style.backgroundColor=''">${suggestion}</div>`
            )
            .join("");
          suggestionsDiv.style.display = "block";
        } else {
          suggestionsDiv.innerHTML =
            '<div class="search-suggestion-item p-2 text-muted">No suggestions found</div>';
          setTimeout(() => hideSuggestions(inputElement), 2000);
        }
      })
      .catch((error) => {
        console.error("Search suggestions error:", error);

        // Only show fallback if this input is still active
        if (activeSuggestionsInput !== inputElement) return;

        // Fallback to mock suggestions on error
        const mockSuggestions = [
          "Massage Therapy",
          "Relaxation Treatment",
          "Spa Services",
          "Deep Tissue Massage",
          "Hot Stone Therapy",
          "Aromatherapy",
          "Swedish Massage",
          "Thai Massage",
        ];

        const filteredSuggestions = mockSuggestions.filter((item) =>
          item.toLowerCase().includes(query.toLowerCase())
        );

        if (filteredSuggestions.length > 0) {
          suggestionsDiv.innerHTML = filteredSuggestions
            .map(
              (suggestion) =>
                `<div class="search-suggestion-item p-2 cursor-pointer" 
                   onmousedown="selectSuggestion('${suggestion}', this)"
                   onmouseover="this.style.backgroundColor='#f8f9fa'"
                   onmouseout="this.style.backgroundColor=''">${suggestion}</div>`
            )
            .join("");
          suggestionsDiv.style.display = "block";
        } else {
          hideSuggestions(inputElement);
        }
      });
  }

  function hideSuggestions(inputElement) {
    const suggestionsDiv = inputElement.parentNode.querySelector(
      ".search-suggestions"
    );
    if (suggestionsDiv) {
      suggestionsDiv.style.display = "none";
      suggestionsDiv.innerHTML = "";
    }
  }

  // Global function to select suggestion
  window.selectSuggestion = function (suggestion, element) {
    // Find the input element
    const searchField =
      element.closest(".search-field") ||
      element.closest("form") ||
      element.parentNode;
    const input =
      searchField.querySelector('input[name="search"]') ||
      document.querySelector('input[name="search"]');

    if (input) {
      input.value = suggestion;
      hideSuggestions(input);

      // Submit the form
      const form = input.closest("form");
      if (form) {
        form.submit();
      }
    }
  };

  // Add loading state to search buttons
  const searchForms = document.querySelectorAll('form[action*="index"]');
  searchForms.forEach(function (form) {
    form.addEventListener("submit", function () {
      const submitBtn = this.querySelector('button[type="submit"]');
      if (submitBtn) {
        const originalHtml = submitBtn.innerHTML;
        submitBtn.innerHTML =
          '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Searching...';
        submitBtn.disabled = true;

        // Re-enable after 5 seconds (fallback)
        setTimeout(() => {
          submitBtn.innerHTML = originalHtml;
          submitBtn.disabled = false;
        }, 5000);
      }
    });
  });
});

// Function to highlight search terms in results
function highlightSearchTerms(text, searchTerm) {
  if (!searchTerm) return text;

  const regex = new RegExp(`(${searchTerm})`, "gi");
  return text.replace(regex, '<mark class="bg-warning">$1</mark>');
}

// Add search analytics (optional)
function trackSearch(query) {
  // You can implement analytics tracking here
  console.log("Search performed:", query);
}
