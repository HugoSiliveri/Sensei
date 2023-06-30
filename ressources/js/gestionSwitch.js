window.addEventListener("load", function (){
    let data = document.querySelector("#pageVoeu > data");
    let idIntervenantConnecte = 0;
    if (data !== null){
        idIntervenantConnecte = data.innerHTML;
    }

    let switchs = document.querySelectorAll(".switch");
    let paragraphes = document.querySelectorAll(".switch ~ p");
    let checkboxs = document.querySelectorAll(".switch > input");

    let fieldset = document.querySelector("#formVoeu" + idIntervenantConnecte + " > fieldset");
    let boutonEnvoie = document.getElementById("conteneur" + idIntervenantConnecte);

    for (let i = 0; i < switchs.length; i++) {
        checkboxs[i].addEventListener("change", function (){
            if (checkboxs[i].checked){
                let inputs = document.querySelectorAll("#formVoeu" + idIntervenantConnecte + " > fieldset enLigne")
                let nbInfos = inputs.length;
                let paragraphe = paragraphes[i].innerHTML;

                let tab = paragraphe.split(" "); // ex: CM ( 12 h )

                let info = switchs[i].parentElement.parentElement.parentElement.parentElement.firstElementChild.lastElementChild.innerHTML;
                let tab2 = info.split(" "); // ex: HXM0112 Exemple ( 11 groupe(s) )
                tab2 = [...new Set(tab2)];
                let intervention = tab[0];
                let idUSReferentiel = tab2[0];
                let types = ["CM", "TD", "TP", "Stage", "Terrain", "Innovation Pédagogique"];

                console.log(tab2);

                let infosSup = document.createElement("div");
                infosSup.setAttribute("class", "infosSupplementaires");

                let enLigne = document.createElement("div");
                infosSup.setAttribute("class", "enLigne")

                let labelTypeIntervention = document.createElement("label");
                infosSup.setAttribute("for", "typeIntervention_id");

                enLigne.innerHTML = idUSReferentiel //Mettre l'id référentiel du voeu

                let selectTypeIntervention = document.createElement("select");
                infosSup.setAttribute("id", "typeIntervention_id");
                infosSup.setAttribute("class", "inputCreation2");
                infosSup.setAttribute("name", "typeIntervention" + nbInfos);
                let optionTypeIntervention = document.createElement("option");
                for (const type of types) {
                    if (type === intervention){
                        optionTypeIntervention.setAttribute("value", type);
                        optionTypeIntervention.setAttribute("selected", "selected");
                    }
                }
                selectTypeIntervention.appendChild(optionTypeIntervention);
                enLigne.appendChild(labelTypeIntervention);
                enLigne.appendChild(selectTypeIntervention);
                enLigne.innerHTML += " - ";

                let labelVolumeHoraire = document.createElement("label");
                labelVolumeHoraire.setAttribute("for", "volumeHoraire_id");

                let inputVolumeHoraire = document.createElement("input");
                inputVolumeHoraire.setAttribute("class", "inputCreation2");
                inputVolumeHoraire.setAttribute("id", "volumeHoraire_id");
                inputVolumeHoraire.setAttribute("type", "text");
                inputVolumeHoraire.setAttribute("name", "volumeHoraire" + nbInfos);
                inputVolumeHoraire.setAttribute("value", tab[3]);

                enLigne.appendChild(labelVolumeHoraire);
                enLigne.appendChild(inputVolumeHoraire);
                enLigne.innerHTML += " - ";

                let labelNumGroupe = document.createElement("label");
                labelNumGroupe.setAttribute("for", "numGroupeIntervention_id");

                let inputNumGroupe = document.createElement("input");
                inputNumGroupe.setAttribute("class", "inputCreation2");
                inputNumGroupe.setAttribute("id", "numGroupeIntervention_id");
                inputNumGroupe.setAttribute("type", "text");
                inputNumGroupe.setAttribute("name", "numGroupeIntervention" + nbInfos);
                inputNumGroupe.setAttribute("value", tab[1]);

                enLigne.appendChild(labelNumGroupe);
                enLigne.appendChild(inputNumGroupe);
                infosSup.appendChild(enLigne);

                fieldset.insertBefore(infosSup, boutonEnvoie);
            }
        });
    }
});


