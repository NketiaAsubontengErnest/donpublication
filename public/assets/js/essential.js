document.querySelector("#show-add-book").addEventListener("click", function () {
    document.querySelector(".popup").classList.add("activepop");
});

document.querySelector(".popup .close-btn").addEventListener("click", function () {
    document.querySelector(".popup").classList.remove("activepop");
});



