const BASE_URL = "http://localhost/H-H-logger/Server/";
const userId = localStorage.getItem('user_id');
if (userId) {
    axios.get(BASE_URL + "entries").then((response)=>{
        if(response.data.status==200){
            const entries=response.data.data;
            const form=document.querySelector("#entries");
            entries.forEach(entry => {
                if (entry.user_id == userId) {
                    const parsed = JSON.parse(entry.parsed_json);
                    if (parsed.items && Array.isArray(parsed.items)) {
                        parsed.items.forEach(item => {
                            const p=document.createElement("p");
                            p.textContent = `${item.habit} : ${item.raw_span}`;
                            form.appendChild(p);
                        });
                    }
                }
            });
        }
        else {
        console.error("Failed to fetch entries:", response.data.error);
      }
    })
} else {
    console.error("User not logged in");
}
