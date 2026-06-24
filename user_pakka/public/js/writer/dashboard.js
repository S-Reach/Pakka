const modal = document.getElementById("withdrawModal");

// OPEN
function openModal() {
    modal.classList.add("show");
    document.body.classList.add("modal-open");
}

// CLOSE
function closeModal() {
    modal.classList.remove("show");
    document.body.classList.remove("modal-open");
}

// click outside to close
modal.addEventListener("click", function (e) {
    if (e.target === modal) {
        closeModal();
    }
});

// ESC key close
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        closeModal();
    }
});

// reset modal on page reload/back
window.addEventListener("pageshow", function () {
    closeModal();
});

// close on form submit
modal.querySelector("form").addEventListener("submit", function () {
    closeModal();
});