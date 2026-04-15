// 学生予約一覧のモーダル動作
document.addEventListener("DOMContentLoaded", () => {
    const reserveModalBtn = document.getElementById("mReserveBtn");
    const modal = document.getElementById("modal");

    if (reserveModalBtn && modal) {
        reserveModalBtn.addEventListener("click", () => {
            modal.showModal();
        });
    }
});


