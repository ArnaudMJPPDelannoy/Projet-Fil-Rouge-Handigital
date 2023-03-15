let oldPasswordInput = document.getElementById("old_pass");
let oldPasswordShowIcon = document.getElementById("pass-show");
oldPasswordShowIcon.addEventListener("click", function() {
    let newType = oldPasswordInput.getAttribute("type") === "password" ? "text" : "password";
    oldPasswordInput.setAttribute("type", newType);
    
    oldPasswordShowIcon.classList.toggle("bi-eye-slash-fill");
});

let newPasswordInput = document.getElementById("new_pass");
let newPasswordShowIcon = document.getElementById("pass-show2");
newPasswordShowIcon.addEventListener("click", function() {
    let newType = newPasswordInput.getAttribute("type") === "password" ? "text" : "password";
    newPasswordInput.setAttribute("type", newType);
    
    newPasswordShowIcon.classList.toggle("bi-eye-slash-fill");
});