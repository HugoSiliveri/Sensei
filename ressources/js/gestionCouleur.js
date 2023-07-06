let infos = document.querySelectorAll(".infosServiceConteneur > .infosService");
let voeux = document.querySelectorAll(".enLigne > p");
let servicesStatuaires = document.querySelectorAll(".serviceStatuaire");
let servicesFaits = document.querySelectorAll(".serviceFait");

for (let i = 0; i < infos.length; i++) {
    let serviceStatuaire = servicesStatuaires[i].innerHTML;
    let serviceFait = servicesFaits[i].innerHTML;
    let diff = serviceStatuaire - serviceFait;

    let infosTab = infos[i].innerHTML.split(" ");

    if (diff > 10) { // +10h de différence
        infos[i].style.backgroundColor = "red";
        infos[i].style.color = "white";
        for (const voeu of voeux) {
            let voeuTab = voeu.innerHTML.split(" ");
            let nom = infosTab[1].replace("\n", "");
            if (infosTab[0] === voeuTab[1] && nom === voeuTab[2]) {
                voeu.style.backgroundColor = "red";
                voeu.style.color = "white";
            }
        }
    } else if (10 >= diff && diff > 0) {// -10h de différence
        infos[i].style.backgroundColor = "orange";
        infos[i].style.color = "white";
        for (const voeu of voeux) {
            let voeuTab = voeu.innerHTML.split(" ");
            let nom = infosTab[1].replace("\n", "");
            if (infosTab[0] === voeuTab[1] && nom === voeuTab[2]) {
                voeu.style.backgroundColor = "orange";
                voeu.style.color = "white";
            }
        }
    } else if (diff <= -serviceStatuaire * 0.1 && diff > -serviceStatuaire * 0.2) { // entre 10 et 20% de service fait en plus
        infos[i].style.backgroundColor = "green";
        infos[i].style.color = "white";
        for (const voeu of voeux) {
            let voeuTab = voeu.innerHTML.split(" ");
            let nom = infosTab[1].replace("\n", "");
            if (infosTab[0] === voeuTab[1] && nom === voeuTab[2]) {
                voeu.style.backgroundColor = "green";
                voeu.style.color = "white";
            }
        }
    } else if (diff <= -serviceStatuaire * 0.2) {// +20%
        infos[i].style.backgroundColor = "darkgreen";
        infos[i].style.color = "white";
        for (const voeu of voeux) {
            let voeuTab = voeu.innerHTML.split(" ");
            let nom = infosTab[1].replace("\n", "");
            if (infosTab[0] === voeuTab[1] && nom === voeuTab[2]) {
                voeu.style.backgroundColor = "darkgreen";
                voeu.style.color = "white";
            }
        }
    } else { // entre 0 et 10% de service fait en plus
        infos[i].style.backgroundColor = "lightgreen";
        for (const voeu of voeux) {
            let voeuTab = voeu.split(" ");
            let nom = infosTab[1].replace("\n", "");
            if (infosTab[0] === voeuTab[1] && nom === voeuTab[2]) {
                voeu.style.backgroundColor = "lightgreen";
            }
        }
    }
}

let infos2 = document.querySelectorAll(".infosService2");

for (let i = 0; i < infos2.length; i++){
    infos2[i].style.backgroundColor="darkblue";
    infos2[i].style.color = "white";
}
