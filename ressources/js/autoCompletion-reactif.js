import {applyAndRegister, reactive, startReactiveDom} from "./reactive.js";

const urlAbsolu = 'https://eratosthene.imag.umontpellier.fr/Sensei/';

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

            this.suggestions.push(`${intervenant.idIntervenant ? intervenant.idIntervenant : ""} ${intervenant.nom ? intervenant.nom : ""} ${intervenant.prenom ? intervenant.prenom : ""} ${intervenant.idIntervenantReferentiel ? intervenant.idIntervenantReferentiel : ""}`);
        }
        this.afficheIntervenants();
    },
    requeteAJAX: function (stringIntervenant) {
        let intervenant = encodeURI(stringIntervenant);
        let url = `${urlAbsolu}ressources/php/requeteIntervenant.php?intervenant=${intervenant}`;
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


let autocompUS = reactive({
    suggestions: [],
    suggestions_str: "",

    videUS: function () {
        this.suggestions = [];
    },
    afficheUS: function () {
        this.suggestions_str = ``;
        for (let uniteService of this.suggestions) {
            this.suggestions_str += `<p>${uniteService}</p>`;
        }
        this.videUS();
        return this.suggestions_str;
    },
    callbackUS: function (req) {
        let tabUS = JSON.parse(req.responseText);
        for (let uniteService of tabUS) {
            this.suggestions.push(`${uniteService.idUniteService ? uniteService.idUniteService : ""} ${uniteService.idUSReferentiel ? uniteService.idUSReferentiel : ""} ${uniteService.libUS ? uniteService.libUS : ""}`);
        }
        this.afficheUS();
    },
    requeteAJAX: function (stringUS) {
        let uniteService = encodeURI(stringUS);
        let url = `${urlAbsolu}ressources/php/requeteUS.php?uniteService=${uniteService}`;
        let requete = new XMLHttpRequest();
        requete.open("GET", url, true);
        requete.addEventListener("load", function () {
            autocompUS.callbackUS(requete);
        });
        requete.send(null);
    }
}, "autocompUS");


applyAndRegister(() => {
    autocompUS.afficheUS();
});

startReactiveDom();
