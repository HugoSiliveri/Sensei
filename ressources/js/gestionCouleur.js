let infos = document.querySelectorAll(".infosService");
let servicesStatuaires = document.querySelectorAll(".serviceStatuaire");
let servicesFaits = document.querySelectorAll(".serviceFait");

for (let i = 0; i < infos.length; i++) {
    let serviceStatuaire = servicesStatuaires[i].innerHTML;
    let serviceFait = servicesFaits[i].innerHTML;
    let diff = serviceStatuaire - serviceFait;

    if (diff > 10){ // +10h de différence
        infos[i].style.backgroundColor= "red";
        infos[i].style.color = "white";
    } else if (10 >= diff && diff > 0){// -10h de différence
        infos[i].style.backgroundColor = "orange";
        infos[i].style.color = "white";
    } else if (diff <= -serviceStatuaire*0.1 && diff > -serviceStatuaire*0.2){ // entre 10 et 20% de service fait en plus
        infos[i].style.backgroundColor = "green";
        infos[i].style.color = "white";
    } else if (diff <= -serviceStatuaire*0.2) {// +20%
        infos[i].style.backgroundColor = "darkgreen";
        infos[i].style.color = "white";
    } else { // entre 0 et 10% de service fait en plus
        infos[i].style.backgroundColor = "lightgreen";
    }
}
