const boites = document.querySelectorAll(".autocompletion");


for (const boite of boites) {
    boite.addEventListener("DOMSubtreeModified", function () {
        if (boite.innerHTML === "") {
            boite.style.display = "none";
        } else {
            boite.style.display = "block";
        }
    });
}