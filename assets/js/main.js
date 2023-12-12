const updateButtons = document.querySelectorAll(".update_btn");
const categoryNameInput = document.getElementById("categoryNameModify");
const categoryIDInput = document.getElementById("categoryID");

window.addEventListener('load', function() {
  document.getElementById("categoryPopup").style.display = "none";
  document.getElementById("plantPopup").style.display = "none";
});

// Add hovered class to selected list item
let list = document.querySelectorAll(".navigation li");

function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Menu Toggle
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
  navigation.classList.toggle("active");
  main.classList.toggle("active");
};

// Popup functions
function openPopupC() {
  document.getElementById("categoryPopup").style.display = "block";
}

function closePopupC() {
  document.getElementById("categoryPopup").style.display = "none";
}
function openModifyPopup() {
  document.getElementById("modifyPopup").style.display = "block";
}

function closeModifyPopup() {
  document.getElementById("modifyPopup").style.display = "none";
}


function openPopupP() {
  document.getElementById("plantPopup").style.display = "block";
}

function closePopupP() {
  document.getElementById("plantPopup").style.display = "none";
}

updateButtons.forEach((btn) => {
  btn.addEventListener("click", () => {
    const categoryName = btn.parentElement.parentElement.childNodes[1].textContent;
    categoryNameInput.value = categoryName;
    // console.log(btn.parentElement.childNodes[1].value);
    categoryIDInput.value = btn.parentElement.childNodes[1].value;
    openModifyPopup();
  });
});