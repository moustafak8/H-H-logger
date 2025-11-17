const BASE_URL = "http://localhost/H-H-logger/Server/";
const userId = localStorage.getItem('user_id');
let currentParsedData = null;

document.addEventListener('DOMContentLoaded', function() {
    const parseButton = document.querySelector('.text_input .send');
    const clearButton = document.getElementById('clear');
    const saveButton = document.querySelector('.json .send');

    if (userId) {
        parseButton.addEventListener('click', function(e) {
            e.preventDefault();
            const rawText = document.querySelector('.text_input textarea').value;
            if (!rawText) return;

            axios.post(BASE_URL + "entries/process", {
                raw_text: rawText,
                user_id: userId
            }).then((response) => {
                if (response.data.status === 200) {
                    try {
                        const parsed = response.data.data.parsed;
                        currentParsedData = {
                            parsed: parsed,
                            raw_text: response.data.data.raw_text,
                            user_id: response.data.data.user_id
                        };
                        if (parsed.items && Array.isArray(parsed.items)) {
                            const form = document.querySelector("#entries");
                            form.innerHTML = '';
                            parsed.items.forEach(item => {
                                const p = document.createElement("p");
                                p.textContent = `${item.habit} : ${item.raw_span}`;
                                form.appendChild(p);
                            });
                        }
                    } catch (error) {
                        console.error("Error parsing JSON:", error);
                    }
                } else {
                    console.error("Failed to process entry:", response.data.error);
                }
            }).catch(error => {
                console.error("Error:", error);
            });
        });

        saveButton.addEventListener('click', function(e) {
            e.preventDefault();
            if (!currentParsedData) return;

            axios.post(BASE_URL + "entries/save", currentParsedData).then((response) => {
                if (response.data.status === 200) {
                    alert("Entry saved successfully!");
                    document.querySelector('.text_input textarea').value = '';
                    document.querySelector("#entries").innerHTML = '';
                    currentParsedData = null;
                } else {
                    console.error("Failed to save entry:", response.data.error);
                }
            }).catch(error => {
                console.error("Error:", error);
            });
        });

        clearButton.addEventListener('click', function() {
            document.querySelector('.text_input textarea').value = '';
            document.querySelector("#entries").innerHTML = '';
            currentParsedData = null;
        });
    } else {
        console.error("User not logged in");
    }
});
     // Quick form save button event listener
        const quickSaveButton = document.querySelector('.predefined .send');
        quickSaveButton.addEventListener('click', function(e) {
            e.preventDefault();
            const sleep = document.getElementById('sleep').value;
            const water = document.getElementById('water').value;
            const running = document.getElementById('running').value;
            const promises = [];

            if (sleep !== "") {
                promises.push(axios.post(BASE_URL + "habits/create", {
                    name: "sleeping",
                    category: "Health",
                    unit: "hours",
                    VALUE: parseFloat(sleep),
                    user_id: userId,
                    active: "1"
                }));
            }

            if (water !== "") {
                promises.push(axios.post(BASE_URL + "habits/create", {
                    name: "drinking water",
                    category: "Health",
                    unit: "ml",
                    VALUE: parseFloat(water),
                    user_id: userId,
                    active: "1"
                }));
            }

            if (running !== "") {
                promises.push(axios.post(BASE_URL + "habits/create", {
                    name: "running",
                    category: "Sport",
                    unit: "km",
                    VALUE: parseFloat(running),
                    user_id: userId,
                    active: "1"
                }));
            }

            if (promises.length > 0) {
                Promise.all(promises).then((responses) => {
                    const allSuccess = responses.every(response => response.data.status === 200);
                    if (allSuccess) {
                        alert("Habits saved successfully!");
                        document.getElementById('sleep').value = '';
                        document.getElementById('water').value = '';
                        document.getElementById('running').value = '';
                    } else {
                        console.error("Some habits failed to save");
                    }
                }).catch(error => {
                    console.error("Error saving habits:", error);
                });
            } else {
                alert("Please fill at least one habit field.");
            }
        });
    

            
                