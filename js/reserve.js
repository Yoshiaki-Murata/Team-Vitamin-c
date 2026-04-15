// -------------予約情報描画---------------


// 1. 指定した日付のデータを取得する関数
async function fetchReserveData(date) {
    if (!date) return [];
    try {
        const res = await fetch(`./request_do.php?date=${date}`);
        if (!res.ok) throw new Error('Network response was not ok');
        return await res.json();
    } catch (error) {
        console.error("取得エラー:", error);
        return [];
    }
}

// 2. 取得したデータを画面に描画する関数
function renderReserveList(data) {
    const listContainer = document.getElementById("reserveInfo");
    listContainer.innerHTML = "";

    data.forEach(item => {
        const liElm = document.createElement("li");
        const timeElm = document.createElement("p");
        const methodElm = document.createElement("p");
        const countElm = document.createElement("span");
        const reserveBtn = document.createElement("button");

        liElm.className = "col-3 card m-1 p-2 text-center";
        timeElm.textContent = item["time"];

        // 予約可能数（reserve_count）に応じた表示
        if (parseInt(item["reserve_count"]) > 0) {
            methodElm.textContent = "空 ";
            countElm.textContent = `(残り ${item["reserve_count"]}枠)`;
            countElm.className = "badge bg-success";
            reserveBtn.className = "btn btn-sm btn-primary w-50 mx-auto reserve-btn"
            reserveBtn.textContent = "予約"

            methodElm.appendChild(countElm);
            liElm.appendChild(timeElm);
            liElm.appendChild(methodElm);
            liElm.appendChild(reserveBtn);
            listContainer.appendChild(liElm);

        } else {
            methodElm.textContent = "満 ";
            countElm.textContent = "×";
            countElm.className = "badge bg-danger";

            methodElm.appendChild(countElm);
            liElm.appendChild(timeElm);
            liElm.appendChild(methodElm);
            listContainer.appendChild(liElm);
        }

        // methodElm.appendChild(countElm);
        // liElm.appendChild(timeElm);
        // liElm.appendChild(methodElm);
        // listContainer.appendChild(liElm);
    });
}

// 3. 画面の更新処理をまとめた関数
async function updateDisplay(date) {
    const data = await fetchReserveData(date);
    renderReserveList(data);
}

// 4. メイン処理（初期化とイベント設定）
function init() {
    const dateSelect = document.getElementById("dateSelect");

    // 選択が変わった時の処理
    dateSelect.addEventListener("change", (e) => {
        updateDisplay(e.target.value);
    });

    // 初期表示（ページ読み込み時の選択値で一度実行）
    if (dateSelect.value) {
        updateDisplay(dateSelect.value);
    }
}

// 実行
document.addEventListener("DOMContentLoaded", init);

// ---------------------------------------




// -------------予約実行処理---------------

// 1 モーダルを開く・閉じる
const dialog = document.querySelector("dialog");
const modalClose = document.getElementById("modalClose");

modalClose, addEventListener("click", () => {
    dialog.close();
})

// 2 クリックしたボタンの情報を取得する
function getReserve() {
    const btn = document.querySelectorAll(".reserve-btn")
    btn.forEach(b => {
        b.addEventListener("click", (e) => {
            console.log(e.target.textContent);
        })
    })
}
getReserve();

// 3　モ‐ダル描画
function modalRender(data) {

    const tableElm = document.getElementById("modalTable")
    data.forEach(item => {
        const tbodyElm = document.createElement("tbody");
        const dateTh = document.createElement("td");
        dateTh.classList = "text-center";
        dateTh.textContent = ""

        const timeTh = document.createElement("td");
        timeTh.classList = "text-center";
        timeThtextContent = "";

        const classTh = document.createElement("td");
        classTh.classList = "text-center";
        classTh.textContent = "";

        const roomTh = document.createElement("td");
        roomTh.classList = "text-center";
        roomTh.textContent = "";

        const consultantTh = document.createElement("td");
        consultantTh.classList = "text-center";
        consultantTh.textContent = "";

        const methodTh = document.createElement("td");
        methodTh.classList = "text-center";
        const selectElm = document.createElement("select")
        selectElm.name = "method";
        selectElm.id = "method";
        selectElm.classList = "form-select";
        const optionElmLocal = document.createElement("option")
        optionElmLocal.value = 1;
        optionElmLocal.textContent = "対面"
        const optionElmZoom = document.createElement("option")
        optionElmZoom.value = 2
        optionElmZoom.textContent = "zoom"
        selectElm.appendChild(optionElmLocal);
        selectElm.appendChild(optionElmZoom);
        methodTh.appendChild(selectElm);

        const btnTh = document.createElement("td")
        btnTh.classList = "text-center"
        const reserveBtn = document.createElement("button")
        reserveBtn.classList = "btn btn-warning"
        btnTh.appendChild(reserveBtn)

        tbodyElm.appendChild(dateTh);
        tbodyElm.appendChild(timeTh);
        tbodyElm.appendChild(roomTh);
        tbodyElm.appendChild(consultantTh)
        tbodyElm.appendChild(methodTh);
        tbodyElm.append(btnTh);

    })
}