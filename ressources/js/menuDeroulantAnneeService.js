const menusDeroulant = document.querySelectorAll(".menuDeroulantAnneeService");
const boutonsMenuDeroulant = document.querySelectorAll(".boutonMenuDeroulant");
const arrowsDown = document.querySelectorAll(".arrowDownAnneeService");
const arrowsUp = document.querySelectorAll(".arrowUpAnneeService");

for (let i=0; i < menusDeroulant.length; i++) {
    boutonsMenuDeroulant[i].addEventListener("click", function (){
        if (arrowsUp[i].style.display === "flex"){
            arrowsUp[i].style.display = "none";
            arrowsDown[i].style.display = "flex";
            menusDeroulant[i].style.display = "none";
            //boutonsMenuDeroulant[i].style.borderBottom = "none";
        } else {
            arrowsUp[i].style.display = "flex"
            arrowsDown[i].style.display = "none"
            menusDeroulant[i].style.display = "flex";
            //boutonsMenuDeroulant[i].style.borderBottom = "solid black";
        }
    });
}
