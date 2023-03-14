let searchSettingsIcon = document.getElementById("search_settings_icon");
let headerSearchOptions = document.getElementById("header_search_options");
headerSearchOptions.style.height = "0";

searchSettingsIcon.addEventListener("click", function(event) {
    event.preventDefault();
    headerSearchOptions.style.height = headerSearchOptions.style.height == "15vh" ? "0" : "15vh";
})

let otherOptionsDiv = document.getElementById("other_options");
let restrictCheckbox = document.getElementById("restrict");

if (restrictCheckbox) {
    restrictCheckbox.addEventListener("change", function() {
        otherOptionsDiv.style.display = restrictCheckbox.checked ? "block" : "none";
    });
}