function afficherPopupSuppression(typeElement, idElement){
    if (confirm("Voulez-vous vraiment supprimer l'élément ?")){
        let url = window.location.href;
        url += "/../supprimer" + typeElement + "/" + idElement;
        window.location.replace(url);
    }
}