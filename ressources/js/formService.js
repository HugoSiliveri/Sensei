function afficherFormService(idIntervenant){
    let form = document.getElementById("formService" + idIntervenant);
    let fieldset = document.querySelector("#formService" + idIntervenant + " > fieldset");
    let info = document.getElementById("infoService" + idIntervenant);

    if (form !== null){
        if (form.style.display === "block"){
            form.style.display = "none";
            fieldset.setAttribute("disabled", "disabled");
        } else {
            form.style.display = "block";
            fieldset.removeAttribute("disabled");
            if (info != null){
                info.style.display = "none";
            }
        }
    }
}
