document.addEventListener("DOMContentLoaded", function () {
    const editBtn = document.getElementById("editBtn");
    const saveBtn = document.getElementById("saveBtn");
    const profileForm = document.getElementById("profileForm");
  
    // выходим, если хотя бы чего-то из этого нет на странице
    if (!editBtn || !saveBtn || !profileForm) {
      return;
    }
  
    const inputs = profileForm.querySelectorAll("input");
  
    editBtn.addEventListener("click", function () {
      inputs.forEach(input => input.removeAttribute("readonly"));
      editBtn.classList.add("hidden");
      saveBtn.classList.remove("hidden");
    });
});  