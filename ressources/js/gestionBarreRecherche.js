const boites = document.querySelectorAll(".autocompletion");
const champs = document.querySelectorAll(".champsRecherche")

for (const boite of boites) {
    boite.addEventListener("DOMSubtreeModified", function () {
        if (boite.innerHTML === "") {
            boite.style.display = "none";
        } else {
            boite.style.display = "block";
        }
    });
}

for (const champ of champs) {
    champ.setAttribute("autocomplete", "off")
}