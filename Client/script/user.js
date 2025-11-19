const BASE_URL = "http://localhost/H-H-logger/Server/";
const userId = localStorage.getItem("user_id");
let currentParsedData = null;

document.addEventListener("DOMContentLoaded", function () {
  const parseButton = document.querySelector(".text_input .send");
  const clearButton = document.getElementById("clear");
  const saveButton = document.querySelector(".json .send");

  if (userId) {
    parseButton.addEventListener("click", function (e) {
      e.preventDefault();
      const rawText = document.querySelector(".text_input textarea").value;
      if (!rawText) return;

      axios
        .post(BASE_URL + "entries/process", {
          raw_text: rawText,
          user_id: userId,
        })
        .then((response) => {
          if (response.data.status === 200) {
            try {
              const parsed = response.data.data.parsed;
              currentParsedData = {
                parsed: parsed,
                raw_text: response.data.data.raw_text,
                user_id: response.data.data.user_id,
              };
              if (parsed.items && Array.isArray(parsed.items)) {
                const form = document.querySelector("#entries");
                form.innerHTML = "";
                const habitPromises = [];
                parsed.items.forEach((item, index) => {
                  const habitDiv = document.createElement("div");
                  habitDiv.className = "habit-item";
                  habitDiv.innerHTML = `
                    <span class="habit-name">${item.habit}</span>:<span class="habit-value">${item.raw_span}</span>
                    <input type="text" class="habit-input-name hidden" value="${item.habit}">
                    <input type="text" class="habit-input-value hidden" value="${item.raw_span}">
                    <button type="button" class="update-btn">Update</button>
                    <button type="button" class="save-btn hidden">Save</button>
                    <button type="button" class="cancel-btn hidden">Cancel</button>
                  `;
                  form.appendChild(habitDiv);

                  const spanName = habitDiv.querySelector(".habit-name");
                  const spanValue = habitDiv.querySelector(".habit-value");
                  const inputName = habitDiv.querySelector(".habit-input-name");
                  const inputValue = habitDiv.querySelector(".habit-input-value");
                  const updateBtn = habitDiv.querySelector(".update-btn");
                  const saveBtn = habitDiv.querySelector(".save-btn");
                  const cancelBtn = habitDiv.querySelector(".cancel-btn");

                  updateBtn.addEventListener("click", function () {
                    spanName.classList.add("hidden");
                    spanValue.classList.add("hidden");
                    inputName.classList.remove("hidden");
                    inputValue.classList.remove("hidden");
                    updateBtn.classList.add("hidden");
                    saveBtn.classList.remove("hidden");
                    cancelBtn.classList.remove("hidden");
                    inputName.focus();
                  });

                  saveBtn.addEventListener("click", function () {
                    const newName = inputName.value.trim();
                    const newValue = inputValue.value.trim();
                    if (newName && newValue) {
                      spanName.textContent = newName;
                      spanValue.textContent = newValue;
                      currentParsedData.parsed.items[index].habit = newName;
                      currentParsedData.parsed.items[index].raw_span = newValue;
                      // Update value if it's numeric
                      const numValue = parseFloat(newValue);
                      if (!isNaN(numValue)) {
                        currentParsedData.parsed.items[index].value = numValue;
                      }
                    }
                    spanName.classList.remove("hidden");
                    spanValue.classList.remove("hidden");
                    inputName.classList.add("hidden");
                    inputValue.classList.add("hidden");
                    updateBtn.classList.remove("hidden");
                    saveBtn.classList.add("hidden");
                    cancelBtn.classList.add("hidden");
                  });

                  cancelBtn.addEventListener("click", function () {
                    inputName.value = spanName.textContent;
                    inputValue.value = spanValue.textContent;
                    spanName.classList.remove("hidden");
                    spanValue.classList.remove("hidden");
                    inputName.classList.add("hidden");
                    inputValue.classList.add("hidden");
                    updateBtn.classList.remove("hidden");
                    saveBtn.classList.add("hidden");
                    cancelBtn.classList.add("hidden");
                  });
                });
              }
            } catch (error) {
              console.error("Error parsing JSON:", error);
            }
          } else {
            console.error("Failed to process entry:", response.data.error);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    });

    saveButton.addEventListener("click", function (e) {
      e.preventDefault();
      if (!currentParsedData) return;

      axios
        .post(BASE_URL + "entries/save", currentParsedData)
        .then((response) => {
          if (response.data.status === 200) {
            // Create habits after saving entry
            const habitPromises = currentParsedData.parsed.items.map((item) => {
              const habit = {
                name: item.habit,
                category: item.category,
                VALUE: item.value,
                unit: item.unit,
                user_id: userId,
                active: "1",
              };
              return axios.post(BASE_URL + "habits/create", habit);
            });

            Promise.all(habitPromises)
              .then((responses) => {
                const allSuccess = responses.every(
                  (response) => response.data.status === 200
                );
                if (allSuccess) {
                  alert("Entry and habits saved successfully!");
                } else {
                  alert("Entry saved, but some habits failed to create.");
                }
                document.querySelector(".text_input textarea").value = "";
                document.querySelector("#entries").innerHTML = "";
                currentParsedData = null;
              })
              .catch((error) => {
                console.error("Error creating habits:", error);
                alert("Entry saved, but error creating habits.");
                document.querySelector(".text_input textarea").value = "";
                document.querySelector("#entries").innerHTML = "";
                currentParsedData = null;
              });
          } else {
            console.error("Failed to save entry:", response.data.error);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    });

    clearButton.addEventListener("click", function () {
      document.querySelector(".text_input textarea").value = "";
      document.querySelector("#entries").innerHTML = "";
      currentParsedData = null;
    });
  } else {
    console.error("User not logged in");
  }
});
// Quick form save button event listener
const quickSaveButton = document.querySelector(".predefined .send");
quickSaveButton.addEventListener("click", function (e) {
  e.preventDefault();
  const sleep = document.getElementById("sleep").value;
  const water = document.getElementById("water").value;
  const running = document.getElementById("running").value;
  const promises = [];

  if (sleep !== "") {
    promises.push(
      axios.post(BASE_URL + "habits/create", {
        name: "sleeping",
        category: "Health",
        unit: "hours",
        VALUE: parseFloat(sleep),
        user_id: userId,
        active: "1",
      })
    );
  }

  if (water !== "") {
    promises.push(
      axios.post(BASE_URL + "habits/create", {
        name: "drinking water",
        category: "Health",
        unit: "ml",
        VALUE: parseFloat(water),
        user_id: userId,
        active: "1",
      })
    );
  }

  if (running !== "") {
    promises.push(
      axios.post(BASE_URL + "habits/create", {
        name: "running",
        category: "Sport",
        unit: "km",
        VALUE: parseFloat(running),
        user_id: userId,
        active: "1",
      })
    );
  }

  if (promises.length > 0) {
    Promise.all(promises)
      .then((responses) => {
        const allSuccess = responses.every(
          (response) => response.data.status === 200
        );
        if (allSuccess) {
          alert("Habits saved successfully!");
          document.getElementById("sleep").value = "";
          document.getElementById("water").value = "";
          document.getElementById("running").value = "";
        } else {
          console.error("Some habits failed to save");
        }
      })
      .catch((error) => {
        console.error("Error saving habits:", error);
      });
  } else {
    alert("Please fill at least one habit field.");
  }
});
