let messageOptionsLineHeight = 7.5;

let messageOptionDots = document.getElementById("message_option_dots");
let messageOptions = document.getElementById("message_options");
let messageOptionsHeight = (messageOptionsLineHeight * messageOptions.children.length) + "vh";
messageOptionDots.addEventListener("click", function(event) {
    event.preventDefault();
    messageOptions.style.height = messageOptions.style.height == messageOptionsHeight ? "0" : messageOptionsHeight;
})