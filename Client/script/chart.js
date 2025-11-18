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
  document.getElementById('generate-chart').addEventListener('click', generateChart);
});

function generateChart() {
  const habitName = document.getElementById('habits-list').value;
  const startDate = document.getElementById('start_date').value;
  const endDate = document.getElementById('end_date').value;

  if (!habitName || !startDate || !endDate) {
    alert('Please select a habit and date range.');
    return;
  }

  // Fetch habit data for the selected period by default hiye 1 week but it display kel el available data that is available in range
  axios.get(BASE_URL + `habits/progress?user_id=${userId}&habit_name=${encodeURIComponent(habitName)}&start_date=${startDate}&end_date=${endDate}`)
    .then(response => {
      const data = response.data.data;
      if (data && Array.isArray(data) && data.length > 0) {
        renderChart(data, habitName);
      } else {
        console.log('No data available for the selected period.');
      }
    })
    .catch(error => console.error(error));
}

function renderChart(data, habitName) {
  const ctx = document.getElementById('progress-chart').getContext('2d');
  if (window.myChart) {
    window.myChart.destroy();
  }

  const labels = data.map(item => item.date);
  const values = data.map(item => parseFloat(item.value));

  window.myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: `${habitName} Progress`,
        data: values,
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
}
