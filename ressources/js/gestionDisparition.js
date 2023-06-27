let boutonsDisparitions = document.querySelectorAll(".miseEnPage > .boutonDisparition");
let contenus = document.querySelectorAll(".miseEnPage > .miseEnPage2 > .disparition");

let boutonsDisparitions2 = document.querySelectorAll(".infosServiceConteneur > .boutonDisparition");
let contenus2 = document.querySelectorAll(".infosServiceConteneur ~ .disparition2");


for (let i = 0; i < boutonsDisparitions.length; i++) {
    boutonsDisparitions[i].addEventListener("click", function (){
        if (contenus[i].style.display === "none") {
            contenus[i].style.display = "block";
            boutonsDisparitions[i].innerHTML = "-"
        } else {
            contenus[i].style.display = "none"
            boutonsDisparitions[i].innerHTML = "+"
        }
    });
}

for (let i = 0; i < boutonsDisparitions2.length; i++) {
    boutonsDisparitions2[i].addEventListener("click", function (){
        if (contenus2[i].style.display === "block") {
            contenus2[i].style.display = "none";
            boutonsDisparitions2[i].innerHTML = "+"
        } else {
            contenus2[i].style.display = "block"
            boutonsDisparitions2[i].innerHTML = "-"
        }
    });
}