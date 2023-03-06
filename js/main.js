let passwordInput = document.getElementById("password");
let passwordShowIcon = document.getElementById("pass-show");
passwordShowIcon.addEventListener("click", function() {
    let newType = passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", newType);
    
    passwordShowIcon.classList.toggle("bi-eye-slash-fill");
});