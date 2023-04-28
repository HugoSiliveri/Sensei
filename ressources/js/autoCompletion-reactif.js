import {applyAndRegister, reactive, startReactiveDom} from "./reactive.js";

let autocompIntervenant = reactive({
    suggestions: [],
    suggestions_str: "",

    videIntervenants: function () {
        this.suggestions = [];
    },
    afficheIntervenants: function () {
        this.suggestions_str = ``;
        for (let intervenant of this.suggestions) {
            this.suggestions_str += `<p>${intervenant}</p>`;
        }
        this.videIntervenants();
        return this.suggestions_str;
    },
    callbackIntervenant: function (req) {
        let tabIntervenants = JSON.parse(req.responseText);
        for (let intervenant of tabIntervenants) {

            this.suggestions.push(`${intervenant.nom ? intervenant.nom : ""} ${intervenant.prenom ? intervenant.prenom : ""} ${intervenant.idIntervenantReferentiel ? intervenant.idIntervenantReferentiel : ""}`);
        }
        this.afficheIntervenants();
    },
    requeteAJAX: function (stringIntervenant) {
        let intervenant = encodeURI(stringIntervenant);
        let url = `../ressources/php/requeteIntervenant.php?intervenant=${intervenant}`;
        let requete = new XMLHttpRequest();
        requete.open("GET", url, true);
        requete.addEventListener("load", function () {
            autocompIntervenant.callbackIntervenant(requete);
        });
        requete.send(null);
    }
}, "autocompIntervenant");


applyAndRegister(() => {
    autocompIntervenant.afficheIntervenants();
});

startReactiveDom();
