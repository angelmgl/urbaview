const popup = document.getElementById("share-popup");
const openBtn = document.getElementById("open-popup");
const closeBtn = document.getElementById("close-popup");

openBtn.addEventListener("click", () => {
    popup.classList.add("open");
});

closeBtn.addEventListener("click", () => {
    popup.classList.remove("open");
});