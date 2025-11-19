const BASE_URL = "http://localhost/H-H-logger/Server/";
const userId = localStorage.getItem("user_id");

function displayHabits(response) {
  const habits = response.data;
  const habitsList = document.getElementById("habits-list");
  habitsList.innerHTML = "";
  habits.forEach((habit) => {
    const habitDiv = document.createElement("div");
    habitDiv.className = "habit-item";
    habitDiv.innerHTML = `
      <span class="habit-name">${habit.name}</span>
      <input type="text" class="habit-input hidden" value="${habit.name}">
      <button class="update-btn" data-name="${habit.name}">Update</button>
      <button class="delete-btn" data-name="${habit.name}">Delete</button>
      <button class="save-btn hidden">Save</button>
      <button class="cancel-btn hidden">Cancel</button>
    `;
    habitsList.appendChild(habitDiv);
  });

  document.querySelectorAll(".update-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const habitDiv = this.parentElement;
      const span = habitDiv.querySelector(".habit-name");
      const input = habitDiv.querySelector(".habit-input");
      const updateBtn = habitDiv.querySelector(".update-btn");
      const saveBtn = habitDiv.querySelector(".save-btn");
      const cancelBtn = habitDiv.querySelector(".cancel-btn");

      span.classList.add("hidden");
      input.classList.remove("hidden");
      updateBtn.classList.add("hidden");
      saveBtn.classList.remove("hidden");
      cancelBtn.classList.remove("hidden");
      input.focus();
    });
  });

  document.querySelectorAll(".save-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const habitDiv = this.parentElement;
      const input = habitDiv.querySelector(".habit-input");
      const oldName = habitDiv
        .querySelector(".update-btn")
        .getAttribute("data-name");
      const newName = input.value.trim();
      if (newName && newName !== oldName) {
        updateHabit(oldName, newName);
      } else {
        cancelEdit(habitDiv);
      }
    });
  });
  document.querySelectorAll(".delete-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const habitDiv = this.parentElement;
      const habitname = habitDiv
        .querySelector(".delete-btn")
        .getAttribute("data-name");
      if (confirm("Are you sure you want to delete this habit?")) {
        deleteHabit(habitname);
      }
    });
  });

  document.querySelectorAll(".cancel-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const habitDiv = this.parentElement;
      cancelEdit(habitDiv);
    });
  });
}

function cancelEdit(habitDiv) {
  const span = habitDiv.querySelector(".habit-name");
  const input = habitDiv.querySelector(".habit-input");
  const updateBtn = habitDiv.querySelector(".update-btn");
  const saveBtn = habitDiv.querySelector(".save-btn");
  const cancelBtn = habitDiv.querySelector(".cancel-btn");

  span.classList.remove("hidden");
  input.classList.add("hidden");
  updateBtn.classList.remove("hidden");
  saveBtn.classList.add("hidden");
  cancelBtn.classList.add("hidden");
  input.value = span.textContent;
}

function updateHabit(oldName, newName) {
  axios
    .post(BASE_URL + "habits/update", {
      name: oldName,
      new_name: newName,
      user_id: userId,
    })
    .then((response) => {
      if (response.data.status === 200) {
        fetchHabits();
      } else {
        alert("Update failed");
      }
    })
    .catch((error) => console.error(error));
}
function deleteHabit(habitname) {
  axios
    .post(BASE_URL + "habits/delete", {
      name: habitname,
      user_id: userId,
    })
    .then((response) => {
      if (response.data.status === 200) {
        fetchHabits();
      } else {
        alert("delete failed");
      }
    })
    .catch((error) => console.error(error));
}
function fetchHabits() {
  axios
    .get(BASE_URL + "habits?user_id=" + userId)
    .then((response) => {
      displayHabits(response.data);
    })
    .catch((error) => console.error(error));
}

document.addEventListener("DOMContentLoaded", fetchHabits);
