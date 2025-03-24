document.addEventListener("DOMContentLoaded", function () {
    const editBtn = document.getElementById("editBtn");
    const saveBtn = document.getElementById("saveBtn");
    const inputs = document.querySelectorAll("#profileForm input");

    editBtn.addEventListener("click", function () {
        inputs.forEach(input => input.removeAttribute("readonly"));
        editBtn.classList.add("hidden");
        saveBtn.classList.remove("hidden");
    });
});