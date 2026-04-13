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

// change_request
const openBtn = document.getElementById('js-open');
const closeBtn = document.getElementById('js-close');
const modal = document.getElementById('js-modal');

function modalWrite(cat) {
    const selected = document.getElementById(`js-${cat}`);
    const writeArea = document.getElementById(`js-${cat}-write`);
    console.log(selected);

};

openBtn.addEventListener('click', () => {
    modal.showModal();
    modalWrite('slot');
    modalWrite('method');
    modalWrite('text');
});
closeBtn.addEventListener('click', () => {
    modal.close();
})
