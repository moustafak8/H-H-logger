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
  axios.get(BASE_URL + "habits?user_id=" + userId).then((response) => {
    console.log(response.data);
    displayHabits(response.data);
  }).catch(error => console.error(error));
}
function setDefaultDates() {
  const today = new Date();
  const lastWeek = new Date();
  lastWeek.setDate(today.getDate() - 7);

  const startDateInput = document.getElementById('start_date');
  const endDateInput = document.getElementById('end_date');

  startDateInput.value = lastWeek.toISOString().split('T')[0];
  endDateInput.value = today.toISOString().split('T')[0];
}
document.addEventListener('DOMContentLoaded', function() {
  fetchHabits();
  setDefaultDates();
});
