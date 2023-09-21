const passwordInput = document.getElementById("password");
const submitBtn = document.getElementById("submit-btn");

passwordInput.addEventListener("input", (e) => {
    if(e.target.value.length >= 8) {
        submitBtn.disabled = false;
        submitBtn.classList.remove("disabled");
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add("disabled");
    }
})