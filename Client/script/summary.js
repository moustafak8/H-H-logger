const BASE_URL = "http://localhost/H-H-logger/Server/";
const userId = localStorage.getItem("user_id");

function displayHabits(response) {
  const habits = response.data;
  const habitsList = document.getElementById("habits-list");
  habitsList.innerHTML = "";
  habits.forEach((habit) => {
    const habitOption = document.createElement("option");
    habitOption.value = habit.name;
    habitOption.textContent = habit.name;
    habitsList.appendChild(habitOption);
  });
}

function fetchHabits() {
  axios
    .get(BASE_URL + "habits?user_id=" + userId)
    .then((response) => {
      console.log(response.data);
      displayHabits(response.data);
    })
    .catch((error) => console.error(error));
}
function setDefaultDates() {
  const today = new Date();
  const lastWeek = new Date();
  lastWeek.setDate(today.getDate() - 7);

  const startDateInput = document.getElementById("start_date");
  const endDateInput = document.getElementById("end_date");

  startDateInput.value = lastWeek.toISOString().split("T")[0];
  endDateInput.value = today.toISOString().split("T")[0];
}
document.addEventListener("DOMContentLoaded", function () {
  fetchHabits();
  setDefaultDates();
  document
    .getElementById("generate-chart")
    .addEventListener("click", generatesummary);
});
function generatesummary() {
  const habitName = document.getElementById("habits-list").value;
  const startDate = document.getElementById("start_date").value;
  const endDate = document.getElementById("end_date").value;
  if (!habitName || !startDate || !endDate) {
    alert("Please select a habit and date range.");
    return;
  }
  axios
    .get(
      BASE_URL +
        `habits/progress?user_id=${userId}&habit_name=${encodeURIComponent(
          habitName
        )}&start_date=${startDate}&end_date=${endDate}`
    )
    .then((response) => {
      const data = response.data.data;
      if (data && Array.isArray(data) && data.length > 0) {
        summary(data);
      } else {
        console.log("No data available for the selected period.");
      }
    })
    .catch((error) => console.error(error));
}
function summary(data){
    const habitName = document.getElementById("habits-list").value;
    const payload = { habit: habitName, data: data };
    axios.post(BASE_URL + "entries/generate" , payload).then((response) => {
          if (response.data.status === 200){
            const summaryText = response.data.data.summary;
            document.getElementById("summary-display").innerHTML = `<p>${summaryText}</p>`;
          } else {
            const errorMsg = response.data.data?.error || response.data.message || "Unknown error";
            document.getElementById("summary-display").innerHTML = `<p>Error: ${errorMsg}</p>`;
          }
          }).catch((error) => {
            console.error(error);
            document.getElementById("summary-display").innerHTML = `<p>An error occurred while generating the summary.</p>`;
          });
}
