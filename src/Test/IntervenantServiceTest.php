<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Intervenant;
use App\Sensei\Model\Repository\IntervenantRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\IntervenantService;
use Exception;
use PHPUnit\Framework\TestCase;
use TypeError;

class IntervenantServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->intervenantRepositoryMock = $this->createMock(IntervenantRepository::class);
        $this->intervenantMock = $this->createMock(Intervenant::class);
        $this->service = new IntervenantService($this->intervenantRepositoryMock);
    }

    public function testRecupererParIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererParIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        self::assertEquals([], $this->service->recupererParIdentifiant(0));
    }

    public function testRecupererParIdentifiantExistant()
    {
        $fakeIntervenant = new Intervenant(6184, "Siliveri", "Hugo", 4, 1, "hugo.siliveri@etu.umontpellier.fr", null, "e20210002781", 0);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(1702)->willReturn($fakeIntervenant);
        self::assertEquals($fakeIntervenant, $this->service->recupererParIdentifiant(1702));
    }

    public function testRechercherIntervenantNull()
    {
        $this->expectException(TypeError::class);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRechercherIntervenantIncomplete()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("La recherche est incomplète !");
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->rechercherIntervenant("");
    }

    public function testRechercherIntervenantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est incorrect !");
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(1903201)->willReturn(null);
        $this->service->rechercherIntervenant("1903201 Toto Titi");
    }

    public function testRechercherIntervenantAvecUniquementNomEtPrenom()
    {
        $this->expectException(Exception::class);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        self::assertEquals(null, $this->service->rechercherIntervenant("Toto Titi"));
    }

    public function testRechercherIntervenantExistantAvecUniquementIdentifiant()
    {
        $fakeIntervenant = new Intervenant(4903, "Andre", "Jean Baptiste", 2, 3, null, null, null, 0);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(4903)->willReturn($fakeIntervenant);
        self::assertEquals($fakeIntervenant, $this->service->rechercherIntervenant("4903"));
    }

    public function testRechercherIntervenantExistantAvecChaine()
    {
        $fakeIntervenant = new Intervenant(4903, "Andre", "Jean Baptiste", 2, 3, null, null, null, 0);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(4903)->willReturn($fakeIntervenant);
        self::assertEquals($fakeIntervenant, $this->service->rechercherIntervenant("4903 Andre Jean Baptiste"));
    }

    public function testCreerIntervenantVide(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucune information fournie !");
        $this->intervenantRepositoryMock->method("ajouterSansIdIntervenant")->with([])->willReturn(null);
        $this->service->creerIntervenant([]);
    }

    public function testCreerIntervenantPasVide(){
        $array = ["beurre", "jambon"];
        $this->intervenantRepositoryMock->method("ajouterSansIdIntervenant")->with($array)->willReturn(null);
        self::assertNull($this->service->creerIntervenant($array));
    }

    public function testModifierIntervenantInexistante(){
        $this->expectException(ServiceException::class);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->modifierIntervenant([
            "idIntervenant" => 0,
            "nom" => "jambon",
            "prenom" => "beurre",
            "idStatut" => 0,
            "idDroit" => 1,
            "emailInstitutionnel" => "",
            "emailUsage" => "jambonbeurre@email.fr",
            "idIntervenantReferentiel" => "",
            "deleted" => 0]);
    }

    public function testModifierIntervenantExistante(){
        $fakeIntervenant = new Intervenant(4903, "Andre", "Jean Baptiste", 2, 3, null, null, null, 0);;
        $fakeIntervenantTab = [
            "idIntervenant" => 4903,
            "nom" => "Andre",
            "prenom" => "Baptiste",
            "idStatut" => 2,
            "idDroit" => 3,
            "emailInstitutionnel" => null,
            "emailUsage" => null,
            "idIntervenantReferentiel" => null,
            "deleted" => 0];
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(4903)->willReturn($fakeIntervenant);
        $this->intervenantMock->method("setIdIntervenant")->with(4903);
        $this->intervenantMock->method("setNom")->with("Andre");
        $this->intervenantMock->method("setPrenom")->with("Baptiste");
        $this->intervenantMock->method("setIdStatut")->with(2);
        $this->intervenantMock->method("setIdDroit")->with(1);
        $this->intervenantMock->method("setEmailInstitutionnel")->with("tdzysa");
        $this->intervenantMock->method("setEmailUsage")->with("Andfezfadre");
        $this->intervenantMock->method("setIdIntervenantReferentiel")->with("fdzfdaf");
        $this->intervenantMock->method("setDeleted")->with(0);
        self::assertNull($this->service->modifierIntervenant($fakeIntervenantTab));
    }

    public function testRecupererParUIDInexistant(){
        $this->intervenantRepositoryMock->method("recupererParUID")->with("")->willReturn(null);
        self::assertNull($this->service->recupererParUID(""));
    }

    public function testRecupererParUIDExistant(){
        $fakeIntervenant = new Intervenant(1, "Akrout", "Hugo", 1, 3, "hugo.akrout@umontpellier.fr", null, "p00000008902", 0);
        $this->intervenantRepositoryMock->method("recupererParUID")->with("p00000008902")->willReturn($fakeIntervenant);
        self::assertEquals($fakeIntervenant, $this->service->recupererParUID("p00000008902"));
    }

    public function testRecupererParEmailInstitutionnelInexistant(){
        $this->intervenantRepositoryMock->method("recupererParEmailInstitutionnel")->with("")->willReturn(null);
        self::assertNull($this->service->recupererParEmailInstitutionnel(""));
    }

    public function testRecupererParEmailInstitutionnelExistant(){
        $fakeIntervenant = new Intervenant(1, "Akrout", "Hugo", 1, 3, "hugo.akrout@umontpellier.fr", null, "p00000008902", 0);
        $this->intervenantRepositoryMock->method("recupererParEmailInstitutionnel")->with("hugo.akrout@umontpellier.fr")->willReturn($fakeIntervenant);
        self::assertEquals($fakeIntervenant,$this->service->recupererParEmailInstitutionnel("hugo.akrout@umontpellier.fr"));
    }

    public function testRecupererIntervenantsAvecAnneeEtDepartementNonVacataireVide(){
        $this->intervenantRepositoryMock->method("recupererIntervenantsAvecAnneeEtDepartementNonVacataire")->with(1909, 1)->willReturn([]);
        self::assertEquals([], $this->service->recupererIntervenantsAvecAnneeEtDepartementNonVacataire(1909, 1));
    }

    public function testRecupererIntervenantsAvecAnneeEtDepartementVacataireVide(){
        $this->intervenantRepositoryMock->method("recupererIntervenantsAvecAnneeEtDepartementVacataire")->with(1909, 1)->willReturn([]);
        self::assertEquals([], $this->service->recupererIntervenantsAvecAnneeEtDepartementVacataire(1909, 1));
    }

}