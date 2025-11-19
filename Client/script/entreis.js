const BASE_URL = "http://localhost/H-H-logger/Server/";
const userId = localStorage.getItem("user_id");
function setDefaultDates() {
  const today = new Date();
  const startDateInput = document.getElementById("today_date");
  startDateInput.value = today.toISOString().split("T")[0];
}
document.addEventListener("DOMContentLoaded", function () {
  setDefaultDates();
  getentries();
  document
    .getElementById("generate-chart")
    .addEventListener("click", getentries);
});
function getentries() {
  const date = document.getElementById("today_date").value;
  axios
    .get(BASE_URL + `entries?user_id=${userId}&date=${date}`)
    .then((response) => {
      console.log(response.data.data);
      const data = response.data.data || [];
      displayEntries(data);
    })
    .catch((error) => console.error(error));
}

function displayEntries(entries) {
  const entriesList = document.getElementById("entries-list");
  entriesList.innerHTML = "";
  if (entries && Array.isArray(entries) && entries.length > 0) {
    entries.forEach((entry) => {
      try {
        const parsed = JSON.parse(entry.parsed_json);
        if (parsed && parsed.items && Array.isArray(parsed.items)) {
          parsed.items.forEach((habit) => {
            const entryDiv = document.createElement("div");
            entryDiv.className = "entry-item";
            entryDiv.innerHTML = `
              <span>${habit.habit || "Unknown"}: ${habit.value || 0} ${
              habit.unit || ""
            }</span>
               <button class="update-btn" data-name="${
                 habit.name
               }">Update</button>
      <button class="delete-btn" data-name="${habit.name}">Delete</button>
      <button class="save-btn hidden">Save</button>
      <button class="cancel-btn hidden">Cancel</button>
            `;
            entriesList.appendChild(entryDiv);
          });
        }
      } catch (e) {
        console.error("Error parsing parsed_json:", e);
      }
    });
  } else {
    entriesList.innerHTML = "<p>No entries found for this date.</p>";
  }
}
