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
    callbackIntervenant: function (data) {
        let tabIntervenants = JSON.parse(data);
        for (let intervenant of tabIntervenants) {
            this.suggestions.push(`${intervenant.idIntervenant ? intervenant.idIntervenant : ""} ${intervenant.nom ? intervenant.nom : ""} ${intervenant.prenom ? intervenant.prenom : ""} ${intervenant.idIntervenantReferentiel ? intervenant.idIntervenantReferentiel : ""}`);
        }
        this.afficheIntervenants();
    },
    requeteAJAX: function (stringIntervenant) {
        let intervenant = encodeURI(stringIntervenant);
        let url = `${urlAbsolu}ressources/php/requeteIntervenant.php?intervenant=${intervenant}`;
        fetch(url)
            .then(response => {
                if (response.ok) {
                    return response.text();
                } else {
                    throw new Error('Une erreur s\'est produite.');
                }
            })
            .then(data => {
                autocompIntervenant.callbackIntervenant(data);
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
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
    callbackUS: function (data) {
        let tabUS = JSON.parse(data);
        for (let uniteService of tabUS) {
            this.suggestions.push(`${uniteService.idUniteService ? uniteService.idUniteService : ""} ${uniteService.idUSReferentiel ? uniteService.idUSReferentiel : ""} ${uniteService.libUS ? uniteService.libUS : ""}`);
        }
        this.afficheUS();
    },
    requeteAJAX: function (stringUS) {
        let uniteService = encodeURI(stringUS);
        let url = `${urlAbsolu}ressources/php/requeteUS.php?uniteService=${uniteService}`;
        fetch(url)
            .then(response => {
                if (response.ok) {
                    return response.text();
                } else {
                    throw new Error('Une erreur s\'est produite.');
                }
            })
            .then(data => {
                autocompUS.callbackUS(data);
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }
}, "autocompUS");


applyAndRegister(() => {
    autocompUS.afficheUS();
});

startReactiveDom();
