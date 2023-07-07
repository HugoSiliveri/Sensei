window.addEventListener("load", function (){
    let data = document.querySelector("#pageVoeu > data");
    let idIntervenantConnecte = 0;
    if (data !== null){
        idIntervenantConnecte = data.innerHTML;
    }

    let switchs = document.querySelectorAll(".switch");
    let paragraphes = document.querySelectorAll(".switch ~ p");
    let checkboxs = document.querySelectorAll(".switch > input");

    let fieldset = document.querySelector("#formService" + idIntervenantConnecte + " > fieldset");
    let boutonEnvoie = document.getElementById("conteneur" + idIntervenantConnecte);

    for (let i = 0; i < switchs.length; i++) {
        let idChampInput = document.getElementById("idChamp "+i);

        if (idChampInput !== null){
            checkboxs[i].checked = true;
        }

        checkboxs[i].addEventListener("change", function (){
            if (checkboxs[i].checked){
                let inputs = document.querySelectorAll("#formService" + idIntervenantConnecte + " .enLigne")
                let nbInfos = inputs.length;
                let paragraphe = paragraphes[i].innerHTML;

                let tab = paragraphe.split(" "); // ex: CM ( 12 h )

                let info = switchs[i].parentElement.parentElement.parentElement.parentElement.firstElementChild.lastElementChild.innerHTML;
                let tab2 = info.split(" "); // ex: HXM0112 Exemple ( 11 groupe(s) )
                tab2 = [...new Set(tab2)];
                let intervention = tab[0];
                let idUSReferentiel = tab2[0];
                let types = ["CM", "TD", "TP", "Stage", "Terrain", "Innovation Pédagogique"];

                let infosSup = document.querySelector("#formService" + idIntervenantConnecte + " .infosSupplementaires");
                if (infosSup === null){
                    infosSup = document.createElement("div");
                    infosSup.setAttribute("class", ".infosSupplementaires");
                }

                let enLigne = document.createElement("div");
                enLigne.setAttribute("class", "enLigne")

                let labelTypeIntervention = document.createElement("label");
                labelTypeIntervention.setAttribute("for", "typeIntervention_id");

                enLigne.innerHTML = idUSReferentiel //Mettre l'id référentiel du voeu

                let selectTypeIntervention = document.createElement("select");
                selectTypeIntervention.setAttribute("id", "typeIntervention_id");
                selectTypeIntervention.setAttribute("class", "inputCreation2");
                selectTypeIntervention.setAttribute("name", "typeIntervention" + nbInfos);
                selectTypeIntervention.setAttribute("readonly", "readonly");
                let optionTypeIntervention = document.createElement("option");
                for (let type of types) {
                    if (type === intervention){
                        optionTypeIntervention.setAttribute("value", type);
                        optionTypeIntervention.innerHTML = type;
                        optionTypeIntervention.setAttribute("selected", "selected");
                        selectTypeIntervention.appendChild(optionTypeIntervention);
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
                inputNumGroupe.setAttribute("readonly", "readonly");

                enLigne.appendChild(labelNumGroupe);
                enLigne.appendChild(inputNumGroupe);

                let idChamp = document.createElement("input");

                idChamp.setAttribute("type", "hidden");
                idChamp.setAttribute("id", "idChamp "+ i);
                enLigne.appendChild(idChamp);

                let idUSReferentielInput = document.createElement("input");
                idUSReferentielInput.setAttribute("type", "hidden");
                idUSReferentielInput.setAttribute("name", "idUSReferentiel" + nbInfos);
                idUSReferentielInput.setAttribute("value", idUSReferentiel.split("\n")[0]);

                enLigne.appendChild(idUSReferentielInput);

                infosSup.appendChild(enLigne);
                fieldset.insertBefore(infosSup, boutonEnvoie);

            } else {
                let idChamp = document.getElementById("idChamp " + i);
                let enLigne = idChamp.parentNode;
                if (enLigne != null){
                    enLigne.remove();
                }
            }
        });
    }
});


