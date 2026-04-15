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
        liElm.className = "col-3 card m-1 p-2 text-center";
        
        // 予約可能ならボタンを追加
        let buttonHtml = "";
        if (parseInt(item["reserve_count"]) > 0) {
            buttonHtml = `<button class="btn btn-sm btn-primary w-50 mx-auto reserve-btn" 
                            data-time="${item['time']}" 
                            data-date="${document.getElementById("dateSelect").value}">予約</button>`;
            
            liElm.innerHTML = `
                <p>${item["time"]}</p>
                <p>空 <span class="badge bg-success">(残り ${item["reserve_count"]}枠)</span></p>
                ${buttonHtml}
            `;
        } else {
            liElm.innerHTML = `
                <p>${item["time"]}</p>
                <p>満 <span class="badge bg-danger">×</span></p>
            `;
        }
        listContainer.appendChild(liElm);
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

    // 初期表示
    if (dateSelect.value) {
        updateDisplay(dateSelect.value);
    }
}

// 実行
document.addEventListener("DOMContentLoaded", init);

// ---------------------------------------




// -------------予約実行処理---------------

// 1 モーダル要素の取得
const dialog = document.querySelector("dialog");
const modalClose = document.getElementById("modalClose");

// 2 閉じるボタン
modalClose.addEventListener("click", () => {
    dialog.close();
});

// 3 予約ボタンクリック時の処理
document.getElementById("reserveInfo").addEventListener("click", async (e) => {
    if (e.target.classList.contains("reserve-btn")) {
        const date = e.target.getAttribute("data-date");
        const time = e.target.getAttribute("data-time");

        // 1. 詳細データをPHPから取得
        try {
            const response = await fetch(`./request_do.php?date=${date}&time=${time}`);
            const slots = await response.json();
            
            // 2. モーダルのテーブルを生成
            renderModalTable(slots);
            
            // 3. モーダル表示
            document.querySelector("dialog").showModal();
        } catch (error) {
            console.error("詳細取得エラー:", error);
        }
    }
});

function renderModalTable(slots) {
    const tbody = document.querySelector("#modalTable tbody");
    tbody.innerHTML = ""; 

    slots.forEach(slot => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td class="text-center">${slot.date}</td>
            <td class="text-center">${slot.time}</td>
            <td class="text-center">${slot.class_name ?? '未定'}</td>
            <td class="text-center">${slot.consultant_name ?? '未定'}</td>
            <td>
                <select name="method" class="form-select method-select" data-slot-id="${slot.slot_id}">
                    <option value="1">対面</option>
                    <option value="2">Zoom</option>
                </select>
            </td>
            <td>
                <button class="btn btn-warning final-reserve-btn" data-slot-id="${slot.slot_id}">
                    予約する
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// 4 モーダル内の「予約する」ボタンが押された時
document.querySelector("#modalTable").addEventListener("click", async (e) => {
    if (e.target.classList.contains("final-reserve-btn")) {
        const slotId = e.target.getAttribute("data-slot-id");
        const methodId = e.target.closest("tr").querySelector(".method-select").value;
 
    }
});