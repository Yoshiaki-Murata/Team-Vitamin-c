// モーダルを開く
const mReserveBtn = document.getElementById("mReserveBtn");
const modalDate = document.getElementById("modalDate");

mReserveBtn.addEventListener("click", () => {
    document.querySelector("dialog").showModal();
    fetchAndRender(modalDate.value);
});

// モーダルを閉じる
const closeModal = document.getElementById("closeModal");
closeModal.addEventListener("click", () => {
    document.querySelector("dialog").close();
});

// セレクトボックスが変わった時
modalDate.addEventListener("change", (e) => {
    fetchAndRender(e.target.value);
});


async function fetchAndRender(date) {
    if (!date) return;
    
    try {
        const res = await fetch(`./index_api.php?date=${date}`);
        if (!res.ok) throw new Error("データの取得に失敗しました");
        const reserveData = await res.json();
        renderModalTable(reserveData);
    } catch (error) {
        console.error(error);
    }
}

// モーダル描画関数
function renderModalTable(reserveData) {
    const tbody = document.querySelector("#modalTable tbody");
    tbody.innerHTML = ""; 

    if (reserveData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">予約情報はありません</td></tr>';
        return;
    }

    reserveData.forEach(r => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td class="text-center">${r.date}</td>
            <td class="text-center">${r.time}</td>
            <td class="text-center">${r.name}</td>
            <td class="text-center">${r.class_name ?? "未定"}</td>
        `;
        tbody.appendChild(tr);
    });
}