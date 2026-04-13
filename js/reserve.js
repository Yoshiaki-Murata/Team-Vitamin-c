async function reserve_info(date) {
    if (!date) {
        return [];
    }
    try {
        const res = await fetch(`./../request.php?date=${date}`)
        const data = await res.json();
        return data;
    } catch (error) {
        console.error("エラー:", error);
        return [];
    }


}

function selectedDateInfo() {
    const a = document.getElementById("dateSelect")
    a.addEventListener("change", (e) => {
        return date = e.target.value
    })
}

 function renderSelect(data){
    const b = document.getElementById("reserveInfo")
    b.innerHTML="";
    date.forEach(d => {
        const liElm=document.createElement("li")
        const timeElm=document.createElement("p")
        const methodElm=document.createElement("p")
        const countElm=document.createElement("span")

        liElm.className="col card m-1"
        timeElm.textContent=d["time"]
        countElm.textContent=d["reserve_count"]
        if(d["reserve_count"]>0){
            methodElm.textContent="空"
        }else{
            methodElm.textContent="満"
        }
        methodElm.appendChild(countElm);
        liElm.appendChild(timeElm);
        liElm.appendChild(methodElm);
        b.appendChild(liElm);
    });

}

async function init(){

}