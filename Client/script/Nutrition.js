const BASE_URL = "http://localhost/H-H-logger/Server/";

function getNutritionAdvice() {
  const mealDescription = document.getElementById("meal-input").value.trim();
  const displayDiv = document.getElementById("nutrition-display");
  const button = document.getElementById("get-nutrition-advice");
  const btnText = button.querySelector(".btn-text");
  const btnLoader = button.querySelector(".btn-loader");

  if (!mealDescription) {
    alert("Please describe your meal.");
    return;
  }

  button.disabled = true;
  btnText.classList.add("hidden");
  btnLoader.classList.add("show-inline-flex");
  displayDiv.innerHTML = `
    <div class="loading-message">
      <i class="fa-solid fa-spinner"></i>
      Analyzing your meal...
    </div>
  `;
  displayDiv.classList.add("show");

  axios
    .post(BASE_URL + "nutrition/coach", { text: mealDescription })
    .then((response) => {
      button.disabled = false;
      btnText.classList.remove("hidden");
      btnLoader.classList.remove("show-inline-flex");

      if (response.data.status === 200) {
        const data = response.data.data;
        if (data.estimated_calories !== undefined && data.suggestion) {
          displayDiv.innerHTML = `
            <div class="calories-section">
              <div class="calories-label">Estimated Calories</div>
              <div class="calories-value">
                ${data.estimated_calories}
                <span class="calories-unit">kcal</span>
              </div>
            </div>
            <div class="suggestion-section">
              <div class="suggestion-label">
                <i class="fa-solid fa-lightbulb"></i>
                Next Meal Suggestion
              </div>
              <div class="suggestion-text">${data.suggestion}</div>
            </div>
          `;
          displayDiv.classList.add("show");
        } else {
          displayDiv.innerHTML = `
            <div class="error-message">
              <i class="fa-solid fa-exclamation-triangle"></i>
              Invalid response from nutrition coach. Please try again.
            </div>
          `;
          displayDiv.classList.add("show");
        }
      } else {
        const errorMsg =
          response.data.data?.error || response.data.message || "Unknown error";
        displayDiv.innerHTML = `
          <div class="error-message">
            <i class="fa-solid fa-exclamation-triangle"></i>
            Error: ${errorMsg}
          </div>
        `;
        displayDiv.classList.add("show");
      }
    })
    .catch((error) => {
      console.error(error);
      button.disabled = false;
      btnText.classList.remove("hidden");
      btnLoader.classList.remove("show-inline-flex");

      const errorMessage =
        error.response?.data?.data?.error ||
        error.response?.data?.message ||
        "An error occurred while getting nutrition advice. Please check your connection and try again.";

      displayDiv.innerHTML = `
        <div class="error-message">
          <i class="fa-solid fa-exclamation-triangle"></i>
          ${errorMessage}
        </div>
      `;
      displayDiv.classList.add("show");
    });
}

document.addEventListener("DOMContentLoaded", function () {
  const button = document.getElementById("get-nutrition-advice");
  const textarea = document.getElementById("meal-input");
  button.addEventListener("click", getNutritionAdvice);
});
